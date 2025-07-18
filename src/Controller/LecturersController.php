<?php
declare(strict_types=1);

namespace App\Controller;

use AuditStash\Meta\RequestMetadata;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Lecturers Controller
 *
 * @property \App\Model\Table\LecturersTable $Lecturers
 */
class LecturersController extends AppController
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
        $this->set('lecturers', $this->paginate());
        $this->viewBuilder()->setOption('serialize', 'lecturers');
    }
	
	public function csv()
	{
		$this->response = $this->response->withDownload('lecturers.csv');
		$lecturers = $this->Lecturers->find();
		$_serialize = 'lecturers';

		$this->viewBuilder()->setClassName('CsvView.Csv');
		$this->set(compact('lecturers', '_serialize'));
	}
	
	public function pdfList()
	{
		$this->viewBuilder()->enableAutoLayout(false); 
		$lecturers = $this->paginate($this->Lecturers);
		$this->viewBuilder()->setClassName('CakePdf.Pdf');
		$this->viewBuilder()->setOption(
			'pdfConfig',
			[
				'orientation' => 'portrait',
				'download' => true, 
				'filename' => 'lecturers_List.pdf' 
			]
		);
		$this->set(compact('lecturers'));
	}
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
   public function index()
{
    $this->set('title', 'Lecturers List');
    
    // Configure pagination
    $this->paginate = [
        'maxLimit' => 10,
        'order' => ['Lecturers.lecturer_id' => 'DESC'] // Show newest first
    ];
    
    // Build the search query
    $query = $this->Lecturers->find('search', ['search' => $this->request->getQueryParams()]);
    
    // Log the SQL query for debugging
    $this->log('Lecturers search query: ' . $query->sql(), 'debug');
    $this->log('Search parameters: ' . print_r($this->request->getQueryParams(), true), 'debug');
    
    // Apply additional manual filters if needed
    $searchParams = $this->request->getQueryParams();
    
    // Handle general search if not caught by Search plugin
    if (!empty($searchParams['search'])) {
        $searchTerm = $searchParams['search'];
        $query->where([
            'OR' => [
                'Lecturers.name LIKE' => "%{$searchTerm}%",
                'Lecturers.department LIKE' => "%{$searchTerm}%",
                'Lecturers.email LIKE' => "%{$searchTerm}%"
            ]
        ]);
    }
    
    // Individual field filters
    if (!empty($searchParams['lecturer_id'])) {
        $query->where(['Lecturers.lecturer_id' => $searchParams['lecturer_id']]);
    }
    
    if (!empty($searchParams['name'])) {
        $query->where(['Lecturers.name LIKE' => "%{$searchParams['name']}%"]);
    }
    
    if (!empty($searchParams['department'])) {
        $query->where(['Lecturers.department LIKE' => "%{$searchParams['department']}%"]);
    }
    
    if (!empty($searchParams['email'])) {
        $query->where(['Lecturers.email LIKE' => "%{$searchParams['email']}%"]);
    }
    
    if (!empty($searchParams['status'])) {
        $query->where(['Lecturers.status' => $searchParams['status']]);
    }
    
    // Paginate the results
    $lecturers = $this->paginate($query);
    
    // Calculate statistics with proper status handling
    $totalLecturers = $this->Lecturers->find()->count();
    $totalLecturersArchived = $this->Lecturers->find()->where([
        'OR' => [
            'status' => 'Archived',
            'status' => 'archived',
            'status' => 2
        ]
    ])->count();
    $totalLecturersActive = $this->Lecturers->find()->where([
        'OR' => [
            'status' => 'Active',
            'status' => 'active',
            'status' => 1
        ]
    ])->count();
    $totalLecturersDisabled = $this->Lecturers->find()->where([
        'OR' => [
            'status' => 'Disabled',
            'status' => 'disabled',
            'status' => 0
        ]
    ])->count();
    
    // Set statistics variables
    $this->set('total_lecturers', $totalLecturers);
    $this->set('total_lecturers_archived', $totalLecturersArchived);
    $this->set('total_lecturers_active', $totalLecturersActive);
    $this->set('total_lecturers_disabled', $totalLecturersDisabled);
    
    // Monthly statistics - fix the date function calls
    $currentYear = date('Y');
    $this->set('january', $this->Lecturers->find()->where(['MONTH(created)' => 1, 'YEAR(created)' => $currentYear])->count());
    $this->set('february', $this->Lecturers->find()->where(['MONTH(created)' => 2, 'YEAR(created)' => $currentYear])->count());
    $this->set('march', $this->Lecturers->find()->where(['MONTH(created)' => 3, 'YEAR(created)' => $currentYear])->count());
    $this->set('april', $this->Lecturers->find()->where(['MONTH(created)' => 4, 'YEAR(created)' => $currentYear])->count());
    $this->set('may', $this->Lecturers->find()->where(['MONTH(created)' => 5, 'YEAR(created)' => $currentYear])->count());
    $this->set('jun', $this->Lecturers->find()->where(['MONTH(created)' => 6, 'YEAR(created)' => $currentYear])->count());
    $this->set('july', $this->Lecturers->find()->where(['MONTH(created)' => 7, 'YEAR(created)' => $currentYear])->count());
    $this->set('august', $this->Lecturers->find()->where(['MONTH(created)' => 8, 'YEAR(created)' => $currentYear])->count());
    $this->set('september', $this->Lecturers->find()->where(['MONTH(created)' => 9, 'YEAR(created)' => $currentYear])->count());
    $this->set('october', $this->Lecturers->find()->where(['MONTH(created)' => 10, 'YEAR(created)' => $currentYear])->count());
    $this->set('november', $this->Lecturers->find()->where(['MONTH(created)' => 11, 'YEAR(created)' => $currentYear])->count());
    $this->set('december', $this->Lecturers->find()->where(['MONTH(created)' => 12, 'YEAR(created)' => $currentYear])->count());

    // Chart data for monthly statistics
    $query = $this->Lecturers->find();
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

    // Get unique departments for filter dropdown
    $departments = $this->Lecturers->find()
        ->select(['department'])
        ->where(['department IS NOT' => null, 'department !=' => ''])
        ->distinct(['department'])
        ->orderBy(['department' => 'ASC'])
        ->all()
        ->extract('department')
        ->toArray();

    $this->set(compact('lecturers', 'monthArray', 'countArray', 'departments'));
}
    /**
     * View method
     *
     * @param string|null $id Lecturer id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->set('title', 'Lecturers Details');
        $lecturer = $this->Lecturers->get($id, contain: []);
        $this->set(compact('lecturer'));

        $this->set(compact('lecturer'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->set('title', 'New Lecturers');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Add']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Lecturers']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $lecturer = $this->Lecturers->newEmptyEntity();
        if ($this->request->is('post')) {
            $lecturer = $this->Lecturers->patchEntity($lecturer, $this->request->getData());
            if ($this->Lecturers->save($lecturer)) {
                $this->Flash->success(__('The lecturer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lecturer could not be saved. Please, try again.'));
        }
        $this->set(compact('lecturer'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lecturer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->set('title', 'Lecturers Edit');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Edit']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Lecturers']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $lecturer = $this->Lecturers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lecturer = $this->Lecturers->patchEntity($lecturer, $this->request->getData());
            if ($this->Lecturers->save($lecturer)) {
                $this->Flash->success(__('The lecturer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lecturer could not be saved. Please, try again.'));
        }
        $this->set(compact('lecturer'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lecturer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Delete']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Lecturers']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $this->request->allowMethod(['post', 'delete']);
        $lecturer = $this->Lecturers->get($id);
        if ($this->Lecturers->delete($lecturer)) {
            $this->Flash->success(__('The lecturer has been deleted.'));
        } else {
            $this->Flash->error(__('The lecturer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function archived($id = null)
    {
		$this->set('title', 'Lecturers Edit');
		EventManager::instance()->on('AuditStash.beforeLog', function ($event, array $logs) {
			foreach ($logs as $log) {
				$log->setMetaInfo($log->getMetaInfo() + ['a_name' => 'Archived']);
				$log->setMetaInfo($log->getMetaInfo() + ['c_name' => 'Lecturers']);
				$log->setMetaInfo($log->getMetaInfo() + ['ip' => $this->request->clientIp()]);
				$log->setMetaInfo($log->getMetaInfo() + ['url' => Router::url(null, true)]);
				$log->setMetaInfo($log->getMetaInfo() + ['slug' => $this->Authentication->getIdentity('slug')->getIdentifier('slug')]);
			}
		});
        $lecturer = $this->Lecturers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lecturer = $this->Lecturers->patchEntity($lecturer, $this->request->getData());
			$lecturer->status = 2; //archived
            if ($this->Lecturers->save($lecturer)) {
                $this->Flash->success(__('The lecturer has been archived.'));

				return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The lecturer could not be archived. Please, try again.'));
        }
        $this->set(compact('lecturer'));
    }
}
