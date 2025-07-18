<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Lecturer> $lecturers
 */
    use Cake\Routing\Router;
    echo $this->Html->css('select2/css/select2.css');
    echo $this->Html->script('select2/js/select2.full.min.js');
    echo $this->Html->css('jquery.datetimepicker.min.css');
    echo $this->Html->script('jquery.datetimepicker.full.js');
    echo $this->Html->script('https://cdn.jsdelivr.net/npm/apexcharts');
    echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js');
    $c_name = $this->request->getParam('controller');
    echo $this->Html->script('bootstrapModal', ['block' => 'scriptBottom']);
?>
<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?php echo $title; ?></h1>
        <h6 class="sub_title text-body-secondary"><?php echo $system_name; ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-bars text-primary"></i>
            </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                            <li><?= $this->Html->link(__('<i class="fa-solid fa-plus"></i> New Lecturer'), ['action' => 'add'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
                            </div>
        </div>
    </div>
</div>
<div class="line mb-4"></div>
<!--/Header-->
<div class="row">
    <div class="col-md-9">
        <!-- Nav tabs -->
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs nav-fill border-bottom mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#list"><i class="fa-solid fa-bars-staggered"></i> List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#report"><i class="fa-solid fa-chart-line"></i> Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#export"><i class="fa-solid fa-download"></i> Export</a>
                </li>
            </ul>
        </div>
        
        <div class="tab-content px-0">
         <div class="tab-pane fade active show" id="list">
                <div class="card bg-body-tertiary border-0 shadow mb-4">
                    <div class="card-body text-body-secondary">
                        <div class="table-responsive">
                            <table class="table table-sm table-border mb-4 table_transparent table-hover">
                                <thead>
                                    <?php
                                        $page = $this->Paginator->counter('{{page}}');
                                        $limit = 10; 
                                        $counter = ($page * $limit) - $limit + 1;
                                    ?>
                                    <tr>
                                        <th>#</th>
                                        <th><?= $this->Paginator->sort('lecturer_id', 'ID') ?></th>
                                        <th><?= $this->Paginator->sort('name', 'Lecturer') ?></th>
                                        <th><?= $this->Paginator->sort('department', 'Department') ?></th>
                                        <th><?= $this->Paginator->sort('email', 'Contact') ?></th>
                                        <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                                        <th><?= $this->Paginator->sort('created', 'Joined') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($lecturers)): ?>
                                        <?php foreach ($lecturers as $lecturer): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= $this->Number->format($lecturer->lecturer_id) ?></td>
                                            <td>
                                                <strong><?= h($lecturer->name) ?></strong>
                                                <?php if (!empty($lecturer->position)): ?>
                                                    <br><small class="text-muted"><?= h($lecturer->position) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= h($lecturer->department) ?>
                                                <?php if (!empty($lecturer->office)): ?>
                                                    <br><small class="text-muted"><?= h($lecturer->office) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($lecturer->email)): ?>
                                                    <?= $this->Text->truncate(h($lecturer->email), 25) ?>
                                                <?php endif; ?>
                                                <?php if (!empty($lecturer->phone)): ?>
                                                    <br><small class="text-muted"><?= h($lecturer->phone) ?></small>
                                                <?php endif; ?>
                                                <?php if (empty($lecturer->email) && empty($lecturer->phone)): ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                $statusText = h($lecturer->status ?? '—');
                                                
                                                if (in_array(strtolower($lecturer->status ?? ''), ['active'])) {
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Active';
                                                } elseif (in_array(strtolower($lecturer->status ?? ''), ['pending', 'inactive'])) {
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusText = 'Inactive';
                                                } elseif (in_array(strtolower($lecturer->status ?? ''), ['disabled'])) {
                                                    $statusClass = 'bg-danger';
                                                    $statusText = 'Disabled';
                                                } elseif (in_array(strtolower($lecturer->status ?? ''), ['archived'])) {
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = 'Archived';
                                                } elseif (in_array(strtolower($lecturer->status ?? ''), ['on leave'])) {
                                                    $statusClass = 'bg-info';
                                                    $statusText = 'On Leave';
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                                
                                                <?php if ($lecturer->status === 'Pending' || $lecturer->status === 'Inactive'): ?>
                                                    <br><small class="text-muted">
                                                        <i class="fa-solid fa-clock"></i> 
                                                        <?= $lecturer->created ? $lecturer->created->timeAgoInWords() : '' ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $lecturer->created ? $lecturer->created->format('M d, Y') : '<span class="text-muted">—</span>' ?>
                                                <br><small class="text-muted"><?= $lecturer->created ? $lecturer->created->format('H:i') : '' ?></small>
                                            </td>
                                            <td class="actions text-center">
                                                <div class="btn-group shadow" role="group" aria-label="Actions">
                                                    <?= $this->Html->link(__('<i class="far fa-folder-open"></i>'), ['action' => 'view', $lecturer->lecturer_id], ['class' => 'btn btn-outline-primary btn-xs', 'escapeTitle' => false, 'title' => 'View Details']) ?>
                                                    
                                                    <?= $this->Html->link(__('<i class="fa-regular fa-pen-to-square"></i>'), ['action' => 'edit', $lecturer->lecturer_id], ['class' => 'btn btn-outline-warning btn-xs', 'escapeTitle' => false, 'title' => 'Edit']) ?>
                                                    
                                                    <?php if (!empty($lecturer->email)): ?>
                                                        <?= $this->Html->link(__('<i class="fas fa-envelope"></i>'), 'mailto:' . h($lecturer->email), ['class' => 'btn btn-outline-info btn-xs', 'escapeTitle' => false, 'title' => 'Send Email']) ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php $this->Form->setTemplates([
                                                        'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                                    ]); ?>
                                                    <?= $this->Form->postLink(
                                                        __('<i class="fa-regular fa-trash-can"></i>'),
                                                        ['action' => 'delete', $lecturer->lecturer_id],
                                                        [
                                                            'confirm' => __('Are you sure you want to delete lecturer "{0}"?', $lecturer->name),
                                                            'title' => __('Delete'),
                                                            'class' => 'btn btn-outline-danger btn-xs',
                                                            'escapeTitle' => false,
                                                            'data-bs-toggle' => "modal",
                                                            'data-bs-target' => "#bootstrapModal"
                                                        ]
                                                    ) ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fa-solid fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No lecturers found</h5>
                                                <p class="text-muted">Try adjusting your search criteria or add a new lecturer.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div aria-label="Page navigation" class="mt-3 px-2">
                            <ul class="pagination justify-content-end flex-wrap">
                                <?= $this->Paginator->first('<< ' . __('First')) ?>
                                <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                                <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                                <?= $this->Paginator->next(__('Next') . ' >') ?>
                                <?= $this->Paginator->last(__('Last') . ' >>') ?>
                            </ul>
                            <div class="text-end"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-pane container fade px-0" id="report">
                <div class="row pb-3">
                    <div class="col-md-4">
                      <div class="stat_card card-1 bg-body-tertiary">
                        <h3><?php echo $total_lecturers; ?></h3>
                        <p>Total Lecturers</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat_card card-2 bg-body-tertiary">
                        <h3><?php echo $total_lecturers_active; ?></h3>
                        <p>Active Lecturers</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat_card card-3 bg-body-tertiary">
                        <h3><?php echo $total_lecturers_archived; ?></h3>
                        <p>Archived Lecturers</p>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary border-0 shadow mb-4">
                            <div class="card-body">
                                <div class="card-title mb-0">Lecturers (Monthly)</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="monthly"></canvas>
                                </div>
                                <script>
                                const ctx = document.getElementById('monthly');
                                const monthly = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($monthArray); ?>,
                                        datasets: [{
                                            label: '# of Lecturers',
                                            data: <?php echo json_encode($countArray); ?>,
                                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        },
                                        plugins: {
                                            title: {
                                                display: false,
                                                text: 'Lecturers (Monthly)',
                                                font: {
                                                  size: 15
                                                }
                                            },
                                            subtitle: {
                                                display: false,
                                                text: '<?php echo $system_name; ?>'
                                            },
                                            legend: {
                                                display: false
                                            },
                                        }
                                    }
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary border-0 shadow mb-4">
                            <div class="card-body">
                                <div class="card-title mb-0">Lecturers by Status</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="status"></canvas>
                                </div>
                                <script>
                                const ctx_2 = document.getElementById('status');
                                const status = new Chart(ctx_2, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Active', 'Disabled', 'Archived'],
                                        datasets: [{
                                            label: '# of Lecturers',
                                            data: [
                                                <?= $total_lecturers_active ?? 0 ?>, 
                                                <?= $total_lecturers_disabled ?? 0 ?>, 
                                                <?= $total_lecturers_archived ?? 0 ?>
                                            ],
                                            backgroundColor: [
                                                'rgba(75, 192, 192, 0.5)',
                                                'rgba(255, 206, 86, 0.5)',
                                                'rgba(255, 99, 132, 0.5)'
                                            ],
                                            borderColor: [
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(255, 99, 132, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        },
                                        plugins: {
                                            title: {
                                                display: false,
                                                text: 'Lecturers by Status',
                                                font: {
                                                  size: 15
                                                }
                                            },
                                            subtitle: {
                                                display: false,
                                                text: '<?php echo $system_name; ?>'
                                            },
                                            legend: {
                                                display: false
                                            },
                                        }
                                    }
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane container fade px-0" id="export">
                <?php
                    $domain = Router::url("/", true);
                    $sub = 'lecturers';
                    $combine = $domain . $sub;
                ?>
                <div class="row pb-3">
                    <div class="col-md-3 mb-2">
                        <a href='<?php echo $combine; ?>/csv' class="kosong">
                            <div class="card bg-body-tertiary border-0 shadow">
                                <div class="card-body">
                                    <div class="row mx-0">
                                        <div class="col-5 text-center mt-3 mb-3"><i class="fa-solid fa-file-csv fa-2x text-primary"></i></div>
                                        <div class="col-7 text-end m-auto">
                                            <div class="fs-4 fw-bold">CSV</div>
                                            <div class="small-text"><i class="fa-solid fa-angles-down fa-flip"></i> Download</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href='<?php echo $combine; ?>/json' class="kosong" target="_blank">
                            <div class="card bg-body-tertiary border-0 shadow">
                                <div class="card-body">
                                    <div class="row mx-0">
                                        <div class="col-5 text-center mt-3 mb-3"><i class="fa-solid fa-braille fa-2x text-warning"></i></div>
                                        <div class="col-7 text-end m-auto">
                                            <div class="fs-4 fw-bold">JSON</div>
                                            <div class="small-text"><i class="fa-solid fa-angles-down fa-flip"></i> Download</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href='<?php echo $combine; ?>/pdfList' class="kosong">
                            <div class="card bg-body-tertiary border-0 shadow">
                                <div class="card-body">
                                    <div class="row mx-0">
                                        <div class="col-5 text-center mt-3 mb-3"><i class="fa-regular fa-file-pdf fa-2x text-danger"></i></div>
                                        <div class="col-7 text-end m-auto">
                                            <div class="fs-4 fw-bold">PDF</div>
                                            <div class="small-text"><i class="fa-solid fa-angles-down fa-flip"></i> Download</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>    
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-body-tertiary border-0 shadow mb-4">
            <div class="card-body">
                <div class="card-title mb-0">Search & Filter</div>
                <div class="tricolor_line mb-3"></div>
                
                <?= $this->Form->create(null, [
                    'url' => ['action' => 'index'],
                    'type' => 'get',
                    'valueSources' => ['query', 'context'],
                    'id' => 'search-form'
                ]) ?>
                
                <!-- General Search -->
                <div class="mb-3">
                    <?= $this->Form->control('search', [
                        'label' => 'General Search',
                        'placeholder' => 'Search name, email, department...',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('search'),
                        'autocomplete' => 'off'
                    ]) ?>
                    <small class="text-muted">Search across all fields</small>
                </div>
                
                <hr class="my-3">
                <small class="text-muted fw-bold">OR FILTER BY SPECIFIC FIELDS:</small>
                
                <!-- Lecturer ID Filter -->
                <div class="mb-3 mt-2">
                    <?= $this->Form->control('lecturer_id', [
                        'label' => 'Lecturer ID',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('lecturer_id'),
                        'placeholder' => 'Enter lecturer ID...',
                        'type' => 'number'
                    ]) ?>
                </div>
                
                <!-- Name Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('name', [
                        'label' => 'Lecturer Name',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('name'),
                        'placeholder' => 'Enter name...'
                    ]) ?>
                </div>
                
                <!-- Department Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('department', [
                        'label' => 'Department',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Departments --',
                        'options' => array_combine($departments ?? [], $departments ?? []),
                        'value' => $this->request->getQuery('department')
                    ]) ?>
                </div>
                
                <!-- Email Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('email', [
                        'label' => 'Email',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('email'),
                        'placeholder' => 'Enter email...',
                        'type' => 'email'
                    ]) ?>
                </div>
                
                <!-- Status Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('status', [
                        'label' => 'Status',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Statuses --',
                        'options' => [
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'disabled' => 'Disabled',
                            'archived' => 'Archived'
                        ],
                        'value' => $this->request->getQuery('status')
                    ]) ?>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <?= $this->Form->button('<i class="fa-solid fa-search me-1"></i>Search', [
                        'class' => 'btn btn-primary btn-sm',
                        'type' => 'submit',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-refresh me-1"></i>Reset', ['action' => 'index'], [
                        'class' => 'btn btn-outline-secondary btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                </div>
                
                <?= $this->Form->end() ?>
                
                <!-- Search Results Info -->
                <?php if ($this->request->getQuery()): ?>
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted">
                            <strong>Search Results:</strong><br>
                            <?php 
                            $totalRecords = $this->Paginator->counter('{{count}}');
                            echo "Found {$totalRecords} lecturer(s)";
                            ?>
                            <?php if ($this->request->getQuery('search')): ?>
                                <br>for "<?= h($this->request->getQuery('search')) ?>"
                            <?php endif; ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card bg-body-tertiary border-0 shadow mb-4">
            <div class="card-body">
                <div class="card-title mb-0">Quick Stats</div>
                <div class="tricolor_line mb-3"></div>
                
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-success bg-opacity-10 rounded">
                            <strong class="text-success"><?= h($total_lecturers_active) ?></strong><br>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-warning bg-opacity-10 rounded">
                            <strong class="text-warning"><?= h($total_lecturers_disabled) ?></strong><br>
                            <small class="text-muted">Disabled</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-secondary bg-opacity-10 rounded">
                            <strong class="text-secondary"><?= h($total_lecturers_archived) ?></strong><br>
                            <small class="text-muted">Archived</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-primary bg-opacity-10 rounded">
                            <strong class="text-primary"><?= h($total_lecturers) ?></strong><br>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card bg-body-tertiary border-0 shadow mb-4">
            <div class="card-body">
                <div class="card-title mb-0">Quick Actions</div>
                <div class="tricolor_line mb-3"></div>
                
                <div class="d-grid gap-2">
                    <?= $this->Html->link('<i class="fa-solid fa-plus me-1"></i>New Lecturer', ['action' => 'add'], [
                        'class' => 'btn btn-success btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-filter me-1"></i>Active Only', ['action' => 'index', '?' => ['status' => 'active']], [
                        'class' => 'btn btn-outline-success btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-archive me-1"></i>Archived Only', ['action' => 'index', '?' => ['status' => 'archived']], [
                        'class' => 'btn btn-outline-secondary btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Department Quick Filter -->
        <?php if (!empty($departments) && count($departments) > 1): ?>
        <div class="card bg-body-tertiary border-0 shadow mb-4">
            <div class="card-body">
                <div class="card-title mb-0">Departments</div>
                <div class="tricolor_line mb-3"></div>
                
                <div class="d-grid gap-1">
                    <?php foreach (array_slice($departments, 0, 5) as $dept): ?>
                        <?= $this->Html->link(
                            '<i class="fa-solid fa-building me-1"></i>' . h($dept),
                            ['action' => 'index', '?' => ['department' => $dept]],
                            [
                                'class' => 'btn btn-outline-info btn-sm text-start',
                                'escapeTitle' => false
                            ]
                        ) ?>
                    <?php endforeach; ?>
                    
                    <?php if (count($departments) > 5): ?>
                        <small class="text-muted text-center mt-1">
                            And <?= count($departments) - 5 ?> more...
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

<div class="modal" id="bootstrapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fa-regular fa-circle-xmark fa-6x text-danger mb-3"></i>
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="ok">OK</button>
            </div>
        </div>
    </div>
</div>


   <style>
     .stat_card {
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .stat_card:hover {
        transform: translateY(-5px);
    }
    
    .stat_card h3 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .stat_card p {
        margin-bottom: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .card-1 {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .card-2 {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
    }
    
    .card-3 {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    
    .table_transparent {
        background-color: transparent;
    }
    
    .tricolor_line {
        height: 3px;
        background: linear-gradient(to right, #4e54c8, #8f94fb, #4e54c8);
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #4e54c8;
        border-bottom: 2px solid #4e54c8;
        background-color: transparent;
    }
    
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    .kosong {
        text-decoration: none;
        color: inherit;
    }
    
    .kosong:hover {
        text-decoration: none;
        color: inherit;
    }
</style>

<script>
$$(document).ready(function() {
    // Initialize select2 for better dropdowns
    $('select').select2({
        width: '100%',
        theme: 'bootstrap-5'
    });
    
    // Add tooltips to action buttons
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
    
    // Enhanced search functionality
    $('#search').on('input', function() {
        if ($(this).val().length > 0) {
            // Optional: Clear specific filters when using general search
        }
    });
    
    // Form submission handling
    $('#search-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Searching...');
        
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fa-solid fa-search me-1"></i>Search');
        }, 2000);
        
        return true;
    });
    
    // Highlight search terms
    function highlightSearchTerms() {
        const searchTerm = $('#search').val();
        if (searchTerm && searchTerm.length > 2) {
            $('.table tbody tr').each(function() {
                const row = $(this);
                row.find('td').each(function(index) {
                    if (index === 0 || index === row.find('td').length - 1) return;
                    
                    const cell = $(this);
                    const text = cell.text();
                    if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const highlightedText = text.replace(regex, '<mark>$1</mark>');
                        if (!cell.find('*').length) {
                            cell.html(highlightedText);
                        }
                    }
                });
            });
        }
    }
    
    highlightSearchTerms();
    
    // Auto-focus search
    $('#search').focus();
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#search').focus().select();
        }
        
        if (e.key === 'Escape') {
            $('#search').val('');
            $('#search-form input, #search-form select').val('').trigger('change');
        }
    });
    
    // Row hover effects
    $('.table tbody tr').hover(
        function() { $(this).addClass('table-active'); },
        function() { $(this).removeClass('table-active'); }
    );
    
    // Click row to view (skip action buttons)
    $('.table tbody tr').on('click', function(e) {
        if ($(e.target).closest('a, button').length > 0) return;
        
        const viewLink = $(this).find('a[href*="/view/"]');
        if (viewLink.length > 0) {
            window.location.href = viewLink.attr('href');
        }
    });
    
    $('.table tbody tr').css('cursor', 'pointer');
    
    // Quick filter highlighting
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status');
    
    if (currentStatus) {
        $(`.btn[href*="status=${currentStatus}"]`).addClass('active');
    }
    
    // Status-based action buttons (matching approvals functionality)
    $('.btn-group .btn').on('click', function(e) {
        e.stopPropagation(); // Prevent row click
        
        // Add loading state for non-delete buttons
        if (!$(this).attr('href') || $(this).attr('href').includes('delete')) {
            return;
        }
        
        const originalContent = $(this).html();
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');
        $(this).addClass('loading');
        
        // Reset after navigation attempt
        setTimeout(() => {
            $(this).html(originalContent);
            $(this).removeClass('loading');
        }, 2000);
    });
    
    // Clear specific filters when using general search
    $('#search').on('input', function() {
        if ($(this).val().length > 0) {
            // Optionally clear specific field filters when using general search
            // $('#lecturer-id, #name, #department, #email').val('').trigger('change');
        }
    });
    
    // Clear general search when specific fields are used
    $('#lecturer-id, #name, #department, #email').on('change input', function() {
        if ($(this).val() && $(this).val().trim() !== '') {
            // Optionally clear general search when using specific filters
            // $('#search').val('');
        }
    });
    
    // Email validation enhancement
    $('#email').on('blur', function() {
        const email = $(this).val();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Please enter a valid email address</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Department filter enhancement
    $('#department').on('change', function() {
        const selectedDept = $(this).val();
        if (selectedDept) {
            $('.btn[href*="department="]').removeClass('btn-outline-info').addClass('btn-outline-secondary');
            $(`.btn[href*="department=${encodeURIComponent(selectedDept)}"]`)
                .removeClass('btn-outline-secondary')
                .addClass('btn-outline-info');
        }
    });
});
  </script>