<?php
declare(strict_types=1);

namespace App\Controller;

use AuditStash\Meta\RequestMetadata;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Students Controller
 *
 * @property \App\Model\Table\StudentsTable $Students
 */
class StudentsController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('Search.Search', [
			'actions' => ['index'],
		]);
	}
	
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
	}

	/*public function viewClasses(): array
    {
        return [JsonView::class];
		return [JsonView::class, XmlView::class];
    }*/
	
	public function json()
    {
		$this->viewBuilder()->setLayout('json');
        $this->set('students', $this->paginate());
        $this->viewBuilder()->setOption('serialize', 'students');
    }
	
	public function csv()
	{
		$this->response = $this->response->withDownload('students.csv');
		$students = $this->Students->find();
		$_serialize = 'students';

		$this->viewBuilder()->setClassName('CsvView.Csv');
		$this->set(compact('students', '_serialize'));
	}
	
	public function pdfList()
	{
		$this->viewBuilder()->enableAutoLayout(false); 
		$students = $this->paginate($this->Students);
		$this->viewBuilder()->setClassName('CakePdf.Pdf');
		$this->viewBuilder()->setOption(
			'pdfConfig',
			[
				'orientation' => 'portrait',
				'download' => true, 
				'filename' => 'students_List.pdf' 
			]
		);
		$this->set(compact('students'));
	}
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
   public function index()
{
    $this->set('title', 'Students List');
    
    // Configure pagination
    $this->paginate = [
        'maxLimit' => 10,
        'order' => ['Students.student_id' => 'DESC'] // Show newest first
    ];
    
    // Build the search query
    $query = $this->Students->find('search', ['search' => $this->request->getQueryParams()]);
    
    // Log the SQL query for debugging
    $this->log('Students search query: ' . $query->sql(), 'debug');
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
                'Students.program LIKE' => "%{$searchTerm}%",
                'Students.email LIKE' => "%{$searchTerm}%",
                'Students.phone LIKE' => "%{$searchTerm}%"
            ]
        ]);
    }
    
    // Individual field filters
    if (!empty($searchParams['student_id'])) {
        $query->where(['Students.student_id' => $searchParams['student_id']]);
    }
    
    if (!empty($searchParams['name'])) {
        $query->where(['Students.name LIKE' => "%{$searchParams['name']}%"]);
    }
    
    if (!empty($searchParams['matrix_number'])) {
        $query->where(['Students.matrix_number LIKE' => "%{$searchParams['matrix_number']}%"]);
    }
    
    if (!empty($searchParams['program'])) {
        $query->where(['Students.program LIKE' => "%{$searchParams['program']}%"]);
    }
    
    if (!empty($searchParams['status'])) {
        $query->where(['Students.status' => $searchParams['status']]);
    }
    
    // Paginate the results
    $students = $this->paginate($query);
    
    // Calculate statistics
    $totalStudents = $this->Students->find()->count();
    $totalStudentsArchived = $this->Students->find()->where(['status' => 'Archived'])->count();
    $totalStudentsActive = $this->Students->find()->where(['status' => 'Active'])->count();
    $totalStudentsDisabled = $this->Students->find()->where(['status' => 'Disabled'])->count();
    
    // Set statistics variables
    $this->set('total_students', $totalStudents);
    $this->set('total_students_archived', $totalStudentsArchived);
    $this->set('total_students_active', $totalStudentsActive);
    $this->set('total_students_disabled', $totalStudentsDisabled);
    
    // Monthly statistics
    $currentYear = date('Y');
    $this->set('january', $this->Students->find()->where(['MONTH(created)' => 1, 'YEAR(created)' => $currentYear])->count());
    $this->set('february', $this->Students->find()->where(['MONTH(created)' => 2, 'YEAR(created)' => $currentYear])->count());
    $this->set('march', $this->Students->find()->where(['MONTH(created)' => 3, 'YEAR(created)' => $currentYear])->count());
    $this->set('april', $this->Students->find()->where(['MONTH(created)' => 4, 'YEAR(created)' => $currentYear])->count());
    $this->set('may', $this->Students->find()->where(['MONTH(created)' => 5, 'YEAR(created)' => $currentYear])->count());
    $this->set('jun', $this->Students->find()->where(['MONTH(created)' => 6, 'YEAR(created)' => $currentYear])->count());
    $this->set('july', $this->Students->find()->where(['MONTH(created)' => 7, 'YEAR(created)' => $currentYear])->count());
    $this->set('august', $this->Students->find()->where(['MONTH(created)' => 8, 'YEAR(created)' => $currentYear])->count());
    $this->set('september', $this->Students->find()->where(['MONTH(created)' => 9, 'YEAR(created)' => $currentYear])->count());
    $this->set('october', $this->Students->find()->where(['MONTH(created)' => 10, 'YEAR(created)' => $currentYear])->count());
    $this->set('november', $this->Students->find()->where(['MONTH(created)' => 11, 'YEAR(created)' => $currentYear])->count());
    $this->set('december', $this->Students->find()->where(['MONTH(created)' => 12, 'YEAR(created)' => $currentYear])->count());

    // Chart data for monthly statistics
    $query = $this->Students->find();
    $expectedMonths = [];
    for ($i = 11; $i >= 0; $i--) {
        $expectedMonths[] = date('M-Y', strtotime("-$i months"));
    }

    $query->select([
        'count' => $query->func()->count('*'),
        'date' => $query->func()->date_format(['created' => 'identifier', "%b-%Y"]),
        'month' => 'MONTH(created)',
        'year' => 'YEAR(created)'
    ])
        ->where([
            'created >=' => date('Y-m-01', strtotime('-11 months')),
            'created <=' => date('Y-m-t')
        ])
        ->groupBy(['year', 'month'])
        ->orderBy(['year' => 'ASC', 'month' => 'ASC']);

    $results = $query->all()->toArray();

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

        $totalByMonth[] = [
            'month' => $expectedMonth,
            'count' => $count
        ];
    }

    $this->set([
        'results' => $totalByMonth,
        '_serialize' => ['results']
    ]);

    // Data as JSON arrays for report chart
    $totalByMonth = json_encode($totalByMonth);
    $dataArray = json_decode($totalByMonth, true);
    $monthArray = [];
    $countArray = [];
    foreach ($dataArray as $data) {
        $monthArray[] = $data['month'];
        $countArray[] = $data['count'];
    }

    $this->set(compact('students', 'monthArray', 'countArray'));
}

    /**
     * View method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->set('title', 'Students Details');
        $student = $this->Students->get($id, contain: []);
        $this->set(compact('student'));

        $this->set(compact('student'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->set('title', 'New Students');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Add']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Students']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $student = $this->Students->newEmptyEntity();
        if ($this->request->is('post')) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('The student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $this->set(compact('student'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->set('title', 'Students Edit');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Edit']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Students']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $student = $this->Students->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('The student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $this->set(compact('student'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Delete']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Students']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $this->request->allowMethod(['post', 'delete']);
        $student = $this->Students->get($id);
        if ($this->Students->delete($student)) {
            $this->Flash->success(__('The student has been deleted.'));
        } else {
            $this->Flash->error(__('The student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function archived($id = null)
    {
		$this->set('title', 'Students Edit');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Archived']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Students']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $student = $this->Students->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
			$student->status = 2; //archived
            if ($this->Students->save($student)) {
                $this->Flash->success(__('The student has been archived.'));

				return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The student could not be archived. Please, try again.'));
        }
        $this->set(compact('student'));
    }
}
