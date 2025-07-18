<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class LeaverequestsController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Search.Search', [
            'actions' => ['index']
        ]);

        // Load related models if needed
        $studentsTable = $this->getTableLocator()->get('Students');
$leaveTypesTable = $this->getTableLocator()->get('LeaveTypes');
    }

    /**
     * Index method - Displays list of leave requests
     *
     * @return void
     */
    public function index()
{
    $this->set('title', 'Leave Requests List');

    // Configure pagination (without contain - that goes in the query)
    $this->paginate = [
        'maxLimit' => 10,
        'order' => ['Leaverequests.request_id' => 'DESC'] // Show newest first
    ];

    // Build the search query with contains
    $query = $this->Leaverequests->find('search', ['search' => $this->request->getQueryParams()])
        ->contain(['Students', 'LeaveTypes']);

    // Log the SQL query for debugging
    $this->log('Leave requests search query: ' . $query->sql(), 'debug');
    $this->log('Search parameters: ' . print_r($this->request->getQueryParams(), true), 'debug');

    // Apply additional manual filters if needed
    $searchParams = $this->request->getQueryParams();

    // Handle general search if not caught by Search plugin
    if (!empty($searchParams['search'])) {
        $searchTerm = $searchParams['search'];
        $query->where([
            'OR' => [
                'Students.name LIKE' => "%{$searchTerm}%",
                'Students.matrix_number LIKE' => "%{$searchTerm}%",
                'LeaveTypes.type_name LIKE' => "%{$searchTerm}%",
                'Leaverequests.reason LIKE' => "%{$searchTerm}%"
            ]
        ]);
    }

    // Individual field filters
    if (!empty($searchParams['request_id'])) {
        $query->where(['Leaverequests.request_id' => $searchParams['request_id']]);
    }

    if (!empty($searchParams['student_id'])) {
        $query->where(['Leaverequests.student_id' => $searchParams['student_id']]);
    }

    if (!empty($searchParams['student_name'])) {
        $query->where(['Students.name LIKE' => "%{$searchParams['student_name']}%"]);
    }

    if (!empty($searchParams['leave_type'])) {
        $query->where(['LeaveTypes.type_name LIKE' => "%{$searchParams['leave_type']}%"]);
    }

    if (!empty($searchParams['status'])) {
        $query->where(['Leaverequests.status' => $searchParams['status']]);
    }

    // Handle date range filter
    if (!empty($searchParams['date_range'])) {
        $dates = explode(' - ', $searchParams['date_range']);
        if (count($dates) === 2) {
            $startDate = \DateTime::createFromFormat('m/d/Y', trim($dates[0]));
            $endDate = \DateTime::createFromFormat('m/d/Y', trim($dates[1]));
            
            if ($startDate && $endDate) {
                $query->where([
                    'Leaverequests.leave_date >=' => $startDate->format('Y-m-d'),
                    'Leaverequests.leave_date <=' => $endDate->format('Y-m-d')
                ]);
            }
        }
    }

    // Handle individual date filters
    if (!empty($searchParams['date_from'])) {
        $query->where(['Leaverequests.leave_date >=' => $searchParams['date_from']]);
    }

    if (!empty($searchParams['date_to'])) {
        $query->where(['Leaverequests.leave_date <=' => $searchParams['date_to']]);
    }

    // Paginate results - pass the query directly
    $leaverequests = $this->paginate($query);

    // Calculate statistics with proper status checking
    $totalRequests = $this->Leaverequests->find()->count();
    $pendingCount = $this->Leaverequests->find()->where([
        'OR' => [
            'status' => 'Pending',
            'status' => 'pending'
        ]
    ])->count();
    $approvedCount = $this->Leaverequests->find()->where([
        'OR' => [
            'status' => 'Approved',
            'status' => 'approved'
        ]
    ])->count();
    $rejectedCount = $this->Leaverequests->find()->where([
        'OR' => [
            'status' => 'Rejected',
            'status' => 'rejected'
        ]
    ])->count();

    $this->set([
        'total_leaverequests' => $totalRequests,
        'total_pending' => $pendingCount,
        'total_approved' => $approvedCount,
        'total_rejected' => $rejectedCount
    ]);

    // Monthly statistics
    $expectedMonths = [];
    for ($i = 11; $i >= 0; $i--) {
        $expectedMonths[] = date('M-Y', strtotime("-$i months"));
    }

    $monthlyQuery = $this->Leaverequests->find()
        ->select([
            'count' => $this->Leaverequests->find()->func()->count('*'),
            'month' => 'MONTH(leave_date)',
            'year' => 'YEAR(leave_date)',
            'date' => "DATE_FORMAT(leave_date, '%b-%Y')"
        ])
        ->where([
            'leave_date >=' => date('Y-m-01', strtotime('-11 months')),
            'leave_date <=' => date('Y-m-t')
        ])
        ->groupBy(['year', 'month'])
        ->order(['year' => 'ASC', 'month' => 'ASC']);

    $results = $monthlyQuery->all()->toList();

    $totalByMonth = [];
    foreach ($expectedMonths as $expectedMonth) {
        $found = false;
        $count = 0;
        foreach ($results as $result) {
            if ($expectedMonth === $result->date) {
                $found = true;
                $count = $result->count;
                break;
            }
        }
        $totalByMonth[] = ['month' => $expectedMonth, 'count' => $found ? $count : 0];
    }

    $monthArray = array_column($totalByMonth, 'month');
    $countArray = array_column($totalByMonth, 'count');

    // Get unique values for filter dropdowns
    $studentsTable = $this->getTableLocator()->get('Students');
    $leaveTypesTable = $this->getTableLocator()->get('LeaveTypes');

    // Create proper options for dropdowns
    $students = $studentsTable->find('list', [
        'keyField' => 'student_id',
        'valueField' => 'name'
    ])->where(['status !=' => 'archived'])->all();

    $leaveTypes = $leaveTypesTable->find('list', [
        'keyField' => 'type_name', 
        'valueField' => 'type_name'
    ])->all();

    $this->set(compact('leaverequests', 'monthArray', 'countArray', 'students', 'leaveTypes'));
}

    /**
     * View a single leave request
     */
    public function view($id = null)
{
    $this->set('title', 'Leave Request Details');

    if (!$id) {
        throw new NotFoundException(__('Invalid leave request ID.'));
    }

    $leaverequest = $this->Leaverequests->get($id, [
        'contain' => [
            'Students', 
            'Approvals' => [
                'Lecturers'  // Include lecturer information in the approval
            ]
        ]
    ]);

    // Debug log to see the structure
    $this->log('Leave request data: ' . print_r($leaverequest->toArray(), true), 'debug');

    $this->set(compact('leaverequest'));
}

    /**
     * Add a new leave request
     */
  public function add()
{
    $this->set('title', 'New Leave Request');

    // Create a new empty entity for the leave request
    $leaverequest = $this->Leaverequests->newEmptyEntity();

    // Check if the form was submitted (POST request)
    if ($this->request->is('post')) {
        // Get the form data
        $data = $this->request->getData();

        // Debug log the incoming data
        $this->log('Form data received: ' . print_r($data, true), 'debug');

        // Handle file upload logic if an attachment is provided
        $file = $data['attachment_path'] ?? null;
        if ($file instanceof \Laminas\Diactoros\UploadedFile && $file->getError() === UPLOAD_ERR_OK) {
            $uploadPath = WWW_ROOT . 'files' . DS . 'attachments' . DS;

            // Create the directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            try {
                // Get the file name and move it to the upload folder
                $fileName = time() . '_' . $file->getClientFilename(); // Add timestamp to avoid conflicts
                $file->moveTo($uploadPath . $fileName);

                // Store the path in the 'attachment_path' field
                $data['attachment_path'] = 'attachments/' . $fileName;
                $this->log('File uploaded successfully: ' . $fileName, 'debug');
            } catch (\Exception $e) {
                $this->log('File upload error: ' . $e->getMessage(), 'error');
                $this->Flash->error(__('File upload failed: ' . $e->getMessage()));
                unset($data['attachment_path']);
            }
        } else {
            // If no file was uploaded, unset the attachment_path field
            unset($data['attachment_path']);
        }

        // Patch the form data into the empty entity (populating it)
        $leaverequest = $this->Leaverequests->patchEntity($leaverequest, $data);

        // Debug log validation errors
        if ($leaverequest->hasErrors()) {
            $this->log('Leave request validation errors: ' . print_r($leaverequest->getErrors(), true), 'error');
        }

        // Save the leave request
        if ($this->Leaverequests->save($leaverequest)) {
            $this->log('Leave request saved successfully with ID: ' . $leaverequest->request_id, 'debug');
            
            // After saving the leave request, create an approval record
            try {
                $approvalsTable = $this->getTableLocator()->get('Approvals');
                
               $approvalData = [
    'request_id' => $leaverequest->request_id,
    'status' => 'Pending',  // Capital P
    'lecturer_id' => 1,     // Use a default lecturer ID (change to a real ID from your lecturers table)
    'remarks' => '',
    'approved_at' => null
];
                
                $this->log('Creating approval with data: ' . print_r($approvalData, true), 'debug');
                
                $approval = $approvalsTable->newEmptyEntity();
                $approval = $approvalsTable->patchEntity($approval, $approvalData);

                // Check for validation errors
                if ($approval->hasErrors()) {
                    $this->log('Approval validation errors: ' . print_r($approval->getErrors(), true), 'error');
                    $this->Flash->warning(__('Leave request saved, but approval could not be created due to validation errors.'));
                } else {
                    // Save the approval record
                    if ($approvalsTable->save($approval)) {
                        $this->log('Approval created successfully', 'debug');
                        $this->Flash->success(__('Your leave request has been submitted and approval created.'));
                    } else {
                        $this->log('Approval save failed: ' . print_r($approval->getErrors(), true), 'error');
                        $this->Flash->warning(__('Leave request saved, but approval could not be created.'));
                    }
                }
            } catch (\Exception $e) {
                $this->log('Exception creating approval: ' . $e->getMessage(), 'error');
                $this->Flash->warning(__('Leave request saved, but approval could not be created. Error: ' . $e->getMessage()));
            }

            // Redirect back to the list of leave requests
            return $this->redirect(['action' => 'index']);
        } else {
            $this->log('Leave request save failed: ' . print_r($leaverequest->getErrors(), true), 'error');
            $this->Flash->error(__('Failed to submit leave request. Please fix the errors below.'));
        }
    }

    // Fetch the list of students - with proper error handling
    try {
        $students = $this->Leaverequests->Students->find('list', [
            'keyField' => 'student_id',
            'valueField' => 'name'
        ])
        ->where([
            'student_id IS NOT' => null,
            'name IS NOT' => null,
            'name !=' => ''
        ])
        ->order(['name' => 'ASC'])
        ->toArray();
        
        $this->log('Students loaded: ' . count($students) . ' records', 'debug');
    } catch (\Exception $e) {
        $this->log('Error loading students: ' . $e->getMessage(), 'error');
        $students = [];
        $this->Flash->warning(__('Could not load students list.'));
    }

    // Fetch the list of leave types - with flexible field detection
    try {
        $leaveTypesTable = $this->getTableLocator()->get('LeaveTypes');
        
        // Try to determine the correct field names
        $schema = $leaveTypesTable->getSchema();
        $columns = $schema->columns();
        
        $this->log('LeaveTypes table columns: ' . implode(', ', $columns), 'debug');
        
        // Try common field name combinations
        $keyField = null;
        $valueField = null;
        
        // Check for primary key
        if (in_array('id', $columns)) {
            $keyField = 'id';
        } elseif (in_array('leave_type_id', $columns)) {
            $keyField = 'leave_type_id';
        } elseif (in_array('type_id', $columns)) {
            $keyField = 'type_id';
        }
        
        // Check for name field
        if (in_array('type_name', $columns)) {
            $valueField = 'type_name';
        } elseif (in_array('name', $columns)) {
            $valueField = 'name';
        } elseif (in_array('title', $columns)) {
            $valueField = 'title';
        }
        
        if ($keyField && $valueField) {
            $leaveTypes = $leaveTypesTable->find('list', [
                'keyField' => $keyField,
                'valueField' => $valueField
            ])
            ->where([
                $keyField . ' IS NOT' => null,
                $valueField . ' IS NOT' => null,
                $valueField . ' !=' => ''
            ])
            ->order([$valueField => 'ASC'])
            ->toArray();
            
            $this->log('Leave types loaded: ' . count($leaveTypes) . ' records using fields: ' . $keyField . ', ' . $valueField, 'debug');
        } else {
            // Fallback: just get all records and let CakePHP handle it
            $leaveTypes = $leaveTypesTable->find('all')->toArray();
            $this->log('Leave types loaded as array: ' . count($leaveTypes) . ' records', 'debug');
            
            // Convert to simple array for dropdown
            $leaveTypesArray = [];
            foreach ($leaveTypes as $type) {
                // Try to find a suitable key and value
                $key = $type->id ?? $type->leave_type_id ?? $type->type_id ?? null;
                $value = $type->type_name ?? $type->name ?? $type->title ?? 'Unknown';
                if ($key) {
                    $leaveTypesArray[$key] = $value;
                }
            }
            $leaveTypes = $leaveTypesArray;
        }
    } catch (\Exception $e) {
        $this->log('Error loading leave types: ' . $e->getMessage(), 'error');
        $leaveTypes = [];
        $this->Flash->warning(__('Could not load leave types list.'));
    }

    // Pass the data to the view
    $this->set(compact('leaverequest', 'students', 'leaveTypes'));
}

    /**
     * Edit an existing leave request
     */
  public function edit($id = null)
{
    $this->set('title', 'Edit Leave Request');

    if (!$id) {
        throw new NotFoundException(__('Invalid leave request ID.'));
    }

    $leaverequest = $this->Leaverequests->get($id, ['contain' => []]);

    EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
        foreach ($logs as $log) {
            $log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Edit']);
            $log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Leaverequests']);
            $log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
            $log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
            $log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
        }
    });

    if ($this->request->is(['patch', 'post', 'put'])) {
        // Get the form data
        $data = $this->request->getData();
        
        // Debug: Log the incoming data
        $this->log('Edit form data: ' . print_r($data, true), 'debug');

        // Handle file upload logic if an attachment is provided
        $file = $data['attachment_path'] ?? null;
        if ($file instanceof \Laminas\Diactoros\UploadedFile && $file->getError() === UPLOAD_ERR_OK) {
            $uploadPath = WWW_ROOT . 'files' . DS . 'attachments' . DS;

            // Create the directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            try {
                // Get the file name and move it to the upload folder
                $fileName = time() . '_' . $file->getClientFilename(); // Add timestamp to avoid conflicts
                $file->moveTo($uploadPath . $fileName);

                // Store the path in the 'attachment_path' field
                $data['attachment_path'] = 'attachments/' . $fileName;
                
                $this->log('File uploaded successfully: ' . $fileName, 'debug');
            } catch (\Exception $e) {
                $this->log('File upload error: ' . $e->getMessage(), 'error');
                $this->Flash->error(__('File upload failed: ' . $e->getMessage()));
                unset($data['attachment_path']);
            }
        } else {
            // If no new file was uploaded, keep the existing attachment
            if (isset($data['attachment_path']) && empty($data['attachment_path'])) {
                unset($data['attachment_path']);
            }
        }

        // Patch the entity with the form data
        $leaverequest = $this->Leaverequests->patchEntity($leaverequest, $data);
        
        // Debug: Log validation errors
        if ($leaverequest->hasErrors()) {
            $this->log('Validation errors: ' . print_r($leaverequest->getErrors(), true), 'debug');
        }

        // Debug: Log the entity before save
        $this->log('Entity before save: ' . print_r($leaverequest->toArray(), true), 'debug');

        if ($this->Leaverequests->save($leaverequest)) {
            $this->log('Leave request updated successfully', 'debug');
            $this->Flash->success(__('The leave request has been updated.'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->log('Save failed for leave request', 'debug');
            $this->Flash->error(__('The leave request could not be updated. Please check the form data.'));
        }
    }

    // Get dropdown options
    $students = $this->Leaverequests->Students->find('list', [
        'keyField' => 'student_id',
        'valueField' => 'name'
    ])->all();
    
    $leaveTypes = $this->Leaverequests->LeaveTypes->find('list', [
        'keyField' => 'type_name',
        'valueField' => 'type_name'
    ])->all();

    $this->set(compact('leaverequest', 'students', 'leaveTypes'));
}

    /**
     * Delete a leave request
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!$id) {
            throw new NotFoundException(__('Invalid leave request ID.'));
        }

        $leaverequest = $this->Leaverequests->get($id);

        if ($this->Leaverequests->delete($leaverequest)) {
            $this->Flash->success(__('The leave request has been deleted.'));
        } else {
            $this->Flash->error(__('The leave request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Archive a leave request (soft archive via status)
     */
    public function archived($id = null)
    {
        $this->set('title', 'Archive Leave Request');

        if (!$id) {
            throw new NotFoundException(__('Invalid leave request ID.'));
        }

        $leaverequest = $this->Leaverequests->get($id, ['contain' => []]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $leaverequest = $this->Leaverequests->patchEntity($leaverequest, $this->request->getData());
            $leaverequest->status = 2; // Archived

            if ($this->Leaverequests->save($leaverequest)) {
                $this->Flash->success(__('The leave request has been archived.'));
                return $this->redirect($this->referer());
            }

            $this->Flash->error(__('The leave request could not be archived. Please, try again.'));
        }

        $this->set(compact('leaverequest'));
    }

    /**
     * Export all leave requests as CSV
     */
    public function csv()
    {
        $this->response = $this->response->withDownload('leaverequests.csv');
        $leaverequests = $this->paginate($this->Leaverequests);
        $_serialize = 'leaverequests';

        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('leaverequests', '_serialize'));
    }

    /**
     * Export all leave requests as PDF
     */
    public function pdfList()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->paginate = [
            'contain' => ['Students'],
            'maxLimit' => 10
        ];
        $leaverequests = $this->paginate($this->Leaverequests);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'orientation' => 'portrait',
                'download' => true,
                'filename' => 'leaverequests_List.pdf'
            ]
        );

        $this->set(compact('leaverequests'));
    }

    /**
     * Export a single leave request as PDF
     */
    public function pdf($request_id = null)
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $leaverequest = $this->Leaverequests->get($request_id, ['contain' => ['Students']]);
        $this->set(compact('leaverequest'));

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'orientation' => 'portrait',
                'download' => true,
                'filename' => "Leaverequests_{$request_id}.pdf"
            ]
        );
    }

    /**
     * JSON view - returns paginated data as JSON
     */
    public function json()
    {
        $this->viewBuilder()->setLayout('json');
        $this->set('leaverequests', $this->paginate());
        $this->viewBuilder()->setOption('serialize', 'leaverequests');
    }
}