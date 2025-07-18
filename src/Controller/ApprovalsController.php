<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;

class ApprovalsController extends AppController
{
    /**
     * Initialization hook method.
     */
    public function initialize(): void
    {
        parent::initialize();

        // Load required components
        $this->loadComponent('Flash');
        $this->loadComponent('Search.Search', ['actions' => ['index']]);
    }

    /**
     * Index method - Displays list of approvals with student, reason, lecturer
     *
     * @return void
     */
  public function index()
{
    $this->set('title', 'Leave Request Approvals');

    // Get the necessary tables for querying
    $approvalTable = $this->getTableLocator()->get('Approvals');
    $lecturerTable = $this->getTableLocator()->get('Lecturers');
    $leaverequestsTable = $this->getTableLocator()->get('Leaverequests');

    // Build base query with associations
    $query = $approvalTable->find()
        ->contain([
            'Lecturers' => [
                'fields' => ['lecturer_id', 'name', 'department']
            ],
            'Leaverequests' => [
                'fields' => ['request_id', 'reason', 'status']
            ]
        ]);

    // SEARCH FILTERS
    
    // General search filter
    $search = $this->request->getQuery('search');
    if (!empty($search)) {
        $search = trim($search);
        
        // Build OR conditions - only search integer fields if the search term is numeric
        $orConditions = [
            'Approvals.status LIKE' => "%$search%",
            'Approvals.remarks LIKE' => "%$search%",
            'Lecturers.name LIKE' => "%$search%",
            'Leaverequests.reason LIKE' => "%$search%"
        ];
        
        // Only search ID fields if the search term is numeric
        if (is_numeric($search)) {
            $orConditions['Approvals.approval_id'] = (int)$search;
            $orConditions['Approvals.request_id'] = (int)$search;
        }
        
        $query->where(['OR' => $orConditions]);
    }

    // Specific field filters (only apply if general search is not used)
    if (empty($search)) {
        // Approval ID filter
        $approvalId = $this->request->getQuery('approval_id');
        if (!empty($approvalId)) {
            $query->where(['Approvals.approval_id' => $approvalId]);
        }

        // Request ID filter
        $requestId = $this->request->getQuery('request_id');
        if (!empty($requestId)) {
            $query->where(['Approvals.request_id' => $requestId]);
        }

        // Lecturer ID filter
        $lecturerId = $this->request->getQuery('lecturer_id');
        if (!empty($lecturerId)) {
            $query->where(['Approvals.lecturer_id' => $lecturerId]);
        }

        // Lecturer name filter
        $lecturerName = $this->request->getQuery('lecturer_name');
        if (!empty($lecturerName)) {
            $query->where(['Lecturers.name LIKE' => "%$lecturerName%"]);
        }

        // Remarks filter
        $remarks = $this->request->getQuery('remarks');
        if (!empty($remarks)) {
            $query->where(['Approvals.remarks LIKE' => "%$remarks%"]);
        }
    }

    // Status filter (always apply regardless of search type)
    $status = $this->request->getQuery('status');
    if (!empty($status)) {
        // Handle different status formats
        $statusValue = strtolower(trim($status));
        switch ($statusValue) {
            case 'pending':
            case '0':
                $query->where(['Approvals.status IN' => ['Pending', 'pending', '0', 0]]);
                break;
            case 'approved':
            case '1':
                $query->where(['Approvals.status IN' => ['Approved', 'approved', '1', 1]]);
                break;
            case 'rejected':
            case '2':
                $query->where(['Approvals.status IN' => ['Rejected', 'rejected', '2', 2]]);
                break;
            default:
                $query->where(['Approvals.status LIKE' => "%$status%"]);
        }
    }

    // Date range filters
    $dateFrom = $this->request->getQuery('date_from');
    if (!empty($dateFrom)) {
        $query->where(['Approvals.created >=' => $dateFrom . ' 00:00:00']);
    }

    $dateTo = $this->request->getQuery('date_to');
    if (!empty($dateTo)) {
        $query->where(['Approvals.created <=' => $dateTo . ' 23:59:59']);
    }

    // Order by latest first
    $query->order(['Approvals.created' => 'DESC']);

    // Paginate the results
    $approvals = $this->paginate($query);

    // Get lecturers list for dropdown filter
    $lecturers = $lecturerTable->find('list', [
        'keyField' => 'lecturer_id',
        'valueField' => 'name'
    ])
    ->where([
        'lecturer_id IS NOT' => null,
        'name IS NOT' => null,
        'name !=' => ''
    ])
    ->order(['name' => 'ASC'])
    ->toArray();

    // STATISTICS CALCULATION
    
    // Base query for stats (without pagination)
    $statsQuery = $approvalTable->find();
    
    // Total counts
    $totalApprovals = $statsQuery->count();
    
    // Status-specific counts - handle different status formats
    $totalApproved = $approvalTable->find()
        ->where(['status IN' => ['Approved', 'approved', '1', 1]])
        ->count();
        
    $totalPending = $approvalTable->find()
        ->where(['status IN' => ['Pending', 'pending', '0', 0]])
        ->count();
        
    $totalRejected = $approvalTable->find()
        ->where(['status IN' => ['Rejected', 'rejected', '2', 2]])
        ->count();

    // MONTHLY STATS for chart
    $monthlyQuery = $approvalTable->find()
        ->select([
            'count' => $approvalTable->find()->func()->count('*'),
            'month' => 'MONTH(Approvals.created)',
            'year' => 'YEAR(Approvals.created)',
            'date' => "DATE_FORMAT(Approvals.created, '%b-%Y')"
        ])
        ->where([
            'Approvals.created >=' => date('Y-m-d H:i:s', strtotime('-11 months')),
            'Approvals.created <=' => date('Y-m-d H:i:s')
        ])
        ->groupBy(['year', 'month'])
        ->orderBy(['year' => 'ASC', 'month' => 'ASC']);

    // Process monthly results
    $monthlyResults = $monthlyQuery->all()->toList();
    $expectedMonths = [];
    for ($i = 11; $i >= 0; $i--) {
        $expectedMonths[] = date('M-Y', strtotime("-$i months"));
    }

    $totalByMonth = [];
    foreach ($expectedMonths as $expectedMonth) {
        $count = 0;
        foreach ($monthlyResults as $result) {
            if ($expectedMonth === $result->date) {
                $count = $result->count;
                break;
            }
        }
        $totalByMonth[] = ['month' => $expectedMonth, 'count' => $count];
    }

    $monthArray = array_column($totalByMonth, 'month');
    $countArray = array_column($totalByMonth, 'count');

    // Debug information (optional - remove in production)
    if ($this->request->getQuery('debug')) {
        $this->log('Query SQL: ' . $query->sql(), 'debug');
        $this->log('Query parameters: ' . print_r($this->request->getQuery(), true), 'debug');
        $this->log('Total results: ' . $approvals->count(), 'debug');
    }

    // Pass all variables to view
    $this->set(compact(
        'approvals', 
        'lecturers',
        'totalApprovals', 
        'totalApproved', 
        'totalPending', 
        'totalRejected', 
        'monthArray', 
        'countArray'
    ));
}
    /**
     * View a single approval
     *
     * @param string|int|null $id Approval ID
     * @return void
     * @throws NotFoundException
     */
    public function view($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid approval ID.'));
        }

        $approval = $this->fetchTable('Approvals')->get($id, [
            'contain' => ['Leaverequests', 'Lecturers']
        ]);

        $this->set(compact('approval'));
    }

    /**
     * Add method - For approving/rejecting leave requests manually
     *
     * @return \Cake\Http\Response|null|void Redirects on success, renders otherwise.
     */
   public function add($requestId = null)
{
    // Step 1: Check if the requestId is valid
    if (!$requestId) {
        $this->log('No requestId passed', 'debug');
        throw new NotFoundException(__('Invalid request ID.'));
    }

    // Step 2: Load related models
    $leaverequestsTable = $this->getTableLocator()->get('Leaverequests');
    $lecturersTable = $this->getTableLocator()->get('Lecturers');
    $approvalTable = $this->getTableLocator()->get('Approvals');

    // Step 3: Check if the leave request exists
    $leaveRequest = $leaverequestsTable->find('all')
    ->contain(['Students']) // Add this to load student association
    ->where(['request_id' => $requestId])
    ->first();
    if (!$leaveRequest) {
        $this->log('No leave request found for requestId: ' . $requestId, 'debug');
        throw new NotFoundException(__('Leave request not found.'));
    }

    // Step 4: Check if approval already exists
    $existingApproval = $approvalTable->find()->where(['request_id' => $requestId])->first();
    
    if ($existingApproval) {
        // Check if the approval has already been processed (not pending)
        if ($existingApproval->status !== 'pending' && 
            !empty($existingApproval->status) && 
            $existingApproval->status !== 'Pending') {
            $this->Flash->error(__('This leave request has already been processed.'));
            return $this->redirect(['controller' => 'Leaverequests', 'action' => 'view', $requestId]);
        }
        
        // Use existing approval for editing
        $approval = $existingApproval;
        $this->log('Using existing approval ID: ' . $approval->approval_id, 'debug');
    } else {
        // Step 5: Create a new approval entity
        $approval = $approvalTable->newEmptyEntity();
        $this->log('Creating new approval entity', 'debug');
    }

    // Add debug info at the top
    $this->log('=== APPROVAL ADD METHOD START ===', 'debug');
    $this->log('Request method: ' . $this->request->getMethod(), 'debug');
    $this->log('Is POST: ' . ($this->request->is('post') ? 'YES' : 'NO'), 'debug');
    $this->log('Raw POST data: ' . print_r($this->request->getData(), true), 'debug');

    // Step 6: Check if the form is being submitted
    if ($this->request->is('post')) {
        $this->log('=== PROCESSING POST REQUEST ===', 'debug');
        
        // Get form data
        $formData = $this->request->getData();
        
        // Log the raw form data
        $this->log('Raw form data: ' . print_r($formData, true), 'debug');

        // Ensure request_id is set
        $formData['request_id'] = $requestId;

        // Get lecturer ID from session
        $identity = $this->request->getAttribute('identity');
        $lecturerId = $identity['lecturer_id'] ?? null;
        
        $this->log('Identity from session: ' . print_r($identity, true), 'debug');
        $this->log('Lecturer ID from session: ' . ($lecturerId ?? 'NULL'), 'debug');

        // Validate required fields before processing
        $validationErrors = [];
        
        if (empty($formData['status'])) {
            $validationErrors[] = 'Status is required';
        }

        if (empty($formData['lecturer_id'])) {
            $validationErrors[] = 'Lecturer is required';
        }

        if (!empty($validationErrors)) {
            $this->log('Validation errors: ' . implode(', ', $validationErrors), 'debug');
            foreach ($validationErrors as $error) {
                $this->Flash->error(__($error));
            }
            $lecturers = $this->_getLecturersList($lecturersTable);
            $this->set(compact('approval', 'leaveRequest', 'lecturers', 'requestId'));
            return;
        }

        // Set lecturer ID in form data if not already set
        if (!empty($lecturerId) && empty($formData['lecturer_id'])) {
            $formData['lecturer_id'] = $lecturerId;
        }

        // Log processed form data
        $this->log('Processed form data: ' . print_r($formData, true), 'debug');

        // Patch the approval entity
        $approval = $approvalTable->patchEntity($approval, $formData);
        
        // Log any validation errors
        $validationErrors = $approval->getErrors();
        if (!empty($validationErrors)) {
            $this->log('Entity validation errors: ' . print_r($validationErrors, true), 'debug');
        }

        // Set timestamp
        $approval->approved_at = \Cake\I18n\FrozenTime::now();

        // Log the approval entity before save
        $this->log('Approval entity before save: ' . print_r($approval->toArray(), true), 'debug');
        $this->log('Entity is dirty: ' . ($approval->isDirty() ? 'YES' : 'NO'), 'debug');
        $this->log('Entity has errors: ' . ($approval->hasErrors() ? 'YES' : 'NO'), 'debug');

        // Try to save the approval
        if ($approvalTable->save($approval)) {
            $this->log('=== APPROVAL SAVED SUCCESSFULLY ===', 'debug');
            $this->log('Approval saved with ID: ' . $approval->approval_id, 'debug');
            
            // Update the leave request status based on approval status
            $leaveRequest->status = $formData['status'];
            $this->log('Updating leave request status to: ' . $formData['status'], 'debug');
            
            if ($leaverequestsTable->save($leaveRequest)) {
                $this->log('Leave request status updated successfully', 'debug');
                $statusText = ($formData['status'] === 'Approved') ? 'approved' : 'rejected';
                $this->Flash->success(__('The leave request has been ' . $statusText . '.'));
                return $this->redirect(['controller' => 'Leaverequests', 'action' => 'view', $requestId]);
            } else {
                $this->log('Failed to update leave request: ' . print_r($leaveRequest->getErrors(), true), 'debug');
                $this->Flash->error(__('Approval saved but failed to update leave request status.'));
            }
        } else {
            // Save failed - get detailed error information
            $errors = $approval->getErrors();
            $this->log('=== APPROVAL SAVE FAILED ===', 'debug');
            $this->log('Save errors: ' . print_r($errors, true), 'debug');
            
            // Display specific error messages
            if (empty($errors)) {
                $this->Flash->error(__('Unknown error occurred while saving approval.'));
            } else {
                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $this->Flash->error(__('Error in ' . $field . ': ' . $error));
                    }
                }
            }
        }
    } else {
        $this->log('=== NOT A POST REQUEST ===', 'debug');
    }

    // Fetch lecturers for dropdown
    $lecturers = $this->_getLecturersList($lecturersTable);
    
    $this->log('Available lecturers count: ' . count($lecturers), 'debug');
    $this->log('Available lecturers: ' . print_r($lecturers, true), 'debug');

    // Set variables for view
    $this->set(compact('approval', 'leaveRequest', 'lecturers', 'requestId'));
    
    $this->log('=== APPROVAL ADD METHOD END ===', 'debug');
}

/**
 * Helper method to get lecturers list with correct field names
 */
private function _getLecturersList($lecturersTable)
{
    try {
        // Use the correct field names based on your table structure
        $lecturers = $lecturersTable
            ->find('list', [
                'keyField' => 'lecturer_id',  // Primary key
                'valueField' => 'name'        // Display name
            ])
            ->where([
                'lecturer_id IS NOT' => null,
                'name IS NOT' => null,
                'name !=' => '',
                'status' => 'active'  // Only get active lecturers
            ])
            ->all()
            ->toArray();
            
        $this->log('Successfully loaded ' . count($lecturers) . ' lecturers', 'debug');
        return $lecturers;
        
    } catch (Exception $e) {
        $this->log('Error loading lecturers: ' . $e->getMessage(), 'debug');
        
        // Fallback: manually create the list
        $lecturersData = $lecturersTable
            ->find()
            ->select(['lecturer_id', 'name'])
            ->where([
                'lecturer_id IS NOT' => null,
                'name IS NOT' => null,
                'name !=' => '',
                'status' => 'active'
            ])
            ->all();

        $lecturersList = [];
        foreach ($lecturersData as $lecturer) {
            $lecturersList[$lecturer->lecturer_id] = $lecturer->name;
        }
        
        $this->log('Fallback: created list with ' . count($lecturersList) . ' lecturers', 'debug');
        return $lecturersList;
    }
}
// Add this method to your ApprovalsController for debugging
public function debugDatabase($requestId = null)
{
    if (!$requestId) {
        $requestId = 30; // Use your test request ID
    }
    
    $leaverequestsTable = $this->getTableLocator()->get('Leaverequests');
    $approvalTable = $this->getTableLocator()->get('Approvals');
    
    // Check leave request
    $leaveRequest = $leaverequestsTable->find()->where(['request_id' => $requestId])->first();
    echo "<h3>Leave Request Data:</h3>";
    if ($leaveRequest) {
        echo "<pre>" . print_r($leaveRequest->toArray(), true) . "</pre>";
    } else {
        echo "No leave request found!";
    }
    
    // Check approvals
    $approvals = $approvalTable->find()->where(['request_id' => $requestId])->all();
    echo "<h3>Approval Records:</h3>";
    if ($approvals->count() > 0) {
        foreach ($approvals as $approval) {
            echo "<pre>" . print_r($approval->toArray(), true) . "</pre>";
        }
    } else {
        echo "No approval records found!";
    }
    
    // Check all approvals (to see if any exist)
    $allApprovals = $approvalTable->find()->all();
    echo "<h3>All Approvals Count: " . $allApprovals->count() . "</h3>";
    
    exit; // Stop execution to see the output
}

    /**
     * Edit method
     */
    public function edit($id = null)
{
    // Add this line to set the title
    $this->set('title', 'Edit Approval');
    
    if (!$id) {
        throw new NotFoundException(__('Invalid approval ID.'));
    }

    $approval = $this->fetchTable('Approvals')->get($id, ['contain' => []]);

    if ($this->request->is(['patch', 'post', 'put'])) {
        $approval = $this->fetchTable('Approvals')->patchEntity($approval, $this->request->getData());

        if ($this->fetchTable('Approvals')->save($approval)) {
            $this->Flash->success(__('Approval updated successfully.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error(__('Failed to update approval.'));
    }

    // Improved lecturers list loading
    $lecturers = $this->fetchTable('Lecturers')->find('list', [
        'keyField' => 'lecturer_id',
        'valueField' => 'name'
    ])
    ->where([
        'lecturer_id IS NOT' => null,
        'name IS NOT' => null,
        'name !=' => ''
    ])
    ->order(['name' => 'ASC'])
    ->toArray();

    $this->set(compact('approval', 'lecturers'));
}

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!$id) {
            throw new NotFoundException(__('Invalid approval ID.'));
        }

        $approval = $this->fetchTable('Approvals')->get($id);
        if ($this->fetchTable('Approvals')->delete($approval)) {
            $this->Flash->success(__('Approval deleted successfully.'));
        } else {
            $this->Flash->error(__('Failed to delete approval.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Export approvals as CSV
     */
    public function csv()
    {
        $this->response = $this->response->withDownload('approvals.csv');
        $approvals = $this->paginate($this->Approvals);
        $_serialize = 'approvals';

        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->set(compact('approvals', '_serialize'));
    }

    /**
     * Export approvals as PDF
     */
    public function pdfList()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $approvals = $this->paginate($this->Approvals);

        $this->viewBuilder()->setClassName('CakePdf.Pdf');
        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'orientation' => 'portrait',
                'download' => true,
                'filename' => 'approvals_List.pdf'
            ]
        );

        $this->set(compact('approvals'));
    }
}
