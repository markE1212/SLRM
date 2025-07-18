<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Leaverequest> $leaverequests
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
                            <li><?= $this->Html->link(__('<i class="fa-solid fa-plus"></i> New Leave Request'), ['action' => 'add'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
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
                            <th><?= $this->Paginator->sort('request_id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('student_id', 'Student') ?></th>
                            <th><?= $this->Paginator->sort('leave_date', 'Leave Date') ?></th>
                            <th><?= $this->Paginator->sort('leave_type', 'Type') ?></th>
                            <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                            <th><?= $this->Paginator->sort('created', 'Submitted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leaverequests)): ?>
                            <?php foreach ($leaverequests as $leaverequest): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <strong class="text-primary"><?= $this->Number->format($leaverequest->request_id) ?></strong>
                                    <?php if (!empty($leaverequest->attachment_path)): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-paperclip me-1"></i>Has attachment
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-2">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <?php if ($leaverequest->student): ?>
                                                <strong><?= $this->Html->link($leaverequest->student->name, ['controller' => 'Students', 'action' => 'view', $leaverequest->student->student_id], ['class' => 'text-decoration-none']) ?></strong>
                                                <br><small class="text-muted">ID: <?= h($leaverequest->student->student_id) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($leaverequest->leave_date): ?>
                                        <strong><?= h($leaverequest->leave_date->format('d M Y')) ?></strong>
                                        <br><small class="text-muted"><?= $leaverequest->leave_date->format('l') ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">No Date</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($leaverequest->has('leaveType') && $leaverequest->leaveType): ?>
                                        <span class="badge bg-info-soft">
                                            <i class="fas fa-tag me-1"></i>
                                            <?= h($leaverequest->leaveType->type_name) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">No Type</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    $statusIcon = 'fa-question';
                                    $statusText = h($leaverequest->status ?? 'Pending');
                                    
                                    switch(strtolower($statusText)) {
                                        case 'approved':
                                            $statusClass = 'success';
                                            $statusIcon = 'fa-check-circle';
                                            break;
                                        case 'pending':
                                            $statusClass = 'warning text-dark';
                                            $statusIcon = 'fa-clock';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'danger';
                                            $statusIcon = 'fa-times-circle';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <i class="fas <?= $statusIcon ?> me-1"></i>
                                        <?= $statusText ?>
                                    </span>
                                    
                                    <?php if ($statusText === 'Pending'): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-hourglass-half me-1"></i>
                                            <?= $leaverequest->created ? $leaverequest->created->timeAgoInWords() : '' ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $leaverequest->created ? $leaverequest->created->format('M d, Y') : '<span class="text-muted">—</span>' ?>
                                    <br><small class="text-muted"><?= $leaverequest->created ? $leaverequest->created->format('H:i') : '' ?></small>
                                </td>
                                <td class="actions text-center">
                                    <div class="btn-group shadow" role="group" aria-label="Actions">
                                        <?= $this->Html->link(__('<i class="far fa-folder-open"></i>'), ['action' => 'view', $leaverequest->request_id], ['class' => 'btn btn-outline-primary btn-xs', 'escapeTitle' => false, 'title' => 'View Details']) ?>
                                        
                                        <?php if ($leaverequest->status === 'Pending'): ?>
                                            <?= $this->Html->link(__('<i class="fa-regular fa-pen-to-square"></i>'), ['action' => 'edit', $leaverequest->request_id], ['class' => 'btn btn-outline-warning btn-xs', 'escapeTitle' => false, 'title' => 'Edit']) ?>
                                            <?= $this->Html->link(__('<i class="fas fa-gavel"></i>'), ['controller' => 'Approvals', 'action' => 'add', $leaverequest->request_id], ['class' => 'btn btn-outline-success btn-xs', 'escapeTitle' => false, 'title' => 'Process Approval']) ?>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($leaverequest->attachment_path)): ?>
                                            <?= $this->Html->link(__('<i class="fas fa-download"></i>'), '/files/'.$leaverequest->attachment_path, ['class' => 'btn btn-outline-info btn-xs', 'escapeTitle' => false, 'title' => 'Download Attachment', 'target' => '_blank']) ?>
                                        <?php endif; ?>
                                        
                                        <?php $this->Form->setTemplates([
                                            'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        ]); ?>
                                        <?= $this->Form->postLink(
                                            __('<i class="fa-regular fa-trash-can"></i>'),
                                            ['action' => 'delete', $leaverequest->request_id],
                                            [
                                                'confirm' => __('Are you sure you want to delete leave request #{0}?', $leaverequest->request_id),
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
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No leave requests found</h5>
                                    <p class="text-muted">Try adjusting your search criteria or create a new leave request.</p>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-plus me-2"></i>Create First Request',
                                        ['action' => 'add'],
                                        ['class' => 'btn btn-primary', 'escapeTitle' => false]
                                    ) ?>
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
                        <h3><?php echo $total_leaverequests; ?></h3>
                        <p>Total Requests</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat_card card-2 bg-body-tertiary">
                        <h3><?php echo $total_pending; ?></h3>
                        <p>Pending Requests</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat_card card-3 bg-body-tertiary">
                        <h3><?php echo $total_approved; ?></h3>
                        <p>Approved Requests</p>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary border-0 shadow mb-4">
                            <div class="card-body">
                                <div class="card-title mb-0">Leave Requests (Monthly)</div>
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
                                            label: '# of Leave Requests',
                                            data: <?php echo json_encode($countArray); ?>,
                                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
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
                                                text: 'Leave Requests (Monthly)',
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
                                <div class="card-title mb-0">Requests by Status</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="status"></canvas>
                                </div>
                                <script>
                                const ctx_2 = document.getElementById('status');
                                const status = new Chart(ctx_2, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Pending', 'Approved', 'Rejected'],
                                        datasets: [{
                                            label: '# of Requests',
                                            data: [<?= json_encode($total_pending); ?>, <?= json_encode($total_approved); ?>, <?= json_encode($total_rejected); ?>],
                                            backgroundColor: [
                                                'rgba(255, 206, 86, 0.5)',
                                                'rgba(75, 192, 192, 0.5)',
                                                'rgba(255, 99, 132, 0.5)'
                                            ],
                                            borderColor: [
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(75, 192, 192, 1)',
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
                                                text: 'Leave Requests by Status',
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
                    $sub = 'leaverequests';
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
                        'placeholder' => 'Search student, leave type, reason...',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('search'),
                        'autocomplete' => 'off'
                    ]) ?>
                    <small class="text-muted">Search across all fields</small>
                </div>
                
                <hr class="my-3">
                <small class="text-muted fw-bold">OR FILTER BY SPECIFIC FIELDS:</small>
                
                <!-- Request ID Filter -->
                <div class="mb-3 mt-2">
                    <?= $this->Form->control('request_id', [
                        'label' => 'Request ID',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('request_id'),
                        'placeholder' => 'Enter request ID...',
                        'type' => 'number'
                    ]) ?>
                </div>
                
                <!-- Student Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('student_id', [
                        'label' => 'Student',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Students --',
                        'options' => $students,
                        'value' => $this->request->getQuery('student_id')
                    ]) ?>
                </div>
                
                <!-- Student Name Filter (Alternative) -->
                <div class="mb-3">
                    <?= $this->Form->control('student_name', [
                        'label' => 'Student Name',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('student_name'),
                        'placeholder' => 'Enter student name...'
                    ]) ?>
                </div>
                
                <!-- Leave Type Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('leave_type', [
                        'label' => 'Leave Type',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Types --',
                        'options' => $leaveTypes,
                        'value' => $this->request->getQuery('leave_type')
                    ]) ?>
                </div>
                
                <!-- Status Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('status', [
                        'label' => 'Status',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Statuses --',
                        'options' => [
                            'Pending' => 'Pending',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected'
                        ],
                        'value' => $this->request->getQuery('status')
                    ]) ?>
                </div>
                
                <!-- Date Range Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('date_range', [
                        'label' => 'Date Range',
                        'type' => 'text',
                        'class' => 'form-control form-control-sm date-range-picker',
                        'placeholder' => 'Select date range',
                        'value' => $this->request->getQuery('date_range'),
                        'readonly' => true
                    ]) ?>
                    <small class="text-muted">Click to select date range</small>
                </div>
                
                <!-- Individual Date Filters (Alternative) -->
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date_from', [
                                'label' => 'From Date',
                                'type' => 'date',
                                'class' => 'form-control form-control-sm',
                                'value' => $this->request->getQuery('date_from')
                            ]) ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <?= $this->Form->control('date_to', [
                                'label' => 'To Date',
                                'type' => 'date',
                                'class' => 'form-control form-control-sm',
                                'value' => $this->request->getQuery('date_to')
                            ]) ?>
                        </div>
                    </div>
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
                            echo "Found {$totalRecords} request(s)";
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
                        <div class="p-2 bg-warning bg-opacity-10 rounded">
                            <strong class="text-warning"><?= h($total_pending) ?></strong><br>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-success bg-opacity-10 rounded">
                            <strong class="text-success"><?= h($total_approved) ?></strong><br>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-danger bg-opacity-10 rounded">
                            <strong class="text-danger"><?= h($total_rejected) ?></strong><br>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-primary bg-opacity-10 rounded">
                            <strong class="text-primary"><?= h($total_leaverequests) ?></strong><br>
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
                    <?= $this->Html->link('<i class="fa-solid fa-plus me-1"></i>New Request', ['action' => 'add'], [
                        'class' => 'btn btn-success btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-filter me-1"></i>Pending Only', ['action' => 'index', '?' => ['status' => 'Pending']], [
                        'class' => 'btn btn-outline-warning btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-check me-1"></i>Approved Only', ['action' => 'index', '?' => ['status' => 'Approved']], [
                        'class' => 'btn btn-outline-success btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                </div>
            </div>
        </div>
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
$(document).ready(function() {
    // Initialize select2 for better dropdowns
    $('select').select2({
        width: '100%',
        theme: 'bootstrap-5'
    });
    
    // Initialize date range picker
    $('.date-range-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    
    // Add tooltips to action buttons
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
});

$(document).ready(function() {
    // Initialize select2 for better dropdowns
    $('select').select2({
        width: '100%',
        theme: 'bootstrap-5'
    });
    
    // Initialize date range picker with better formatting
    $('.date-range-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'MM/DD/YYYY'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')]
        }
    });

    // Handle date range picker events
    $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    
    // Add tooltips to action buttons
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
    
    // Clear conflicting fields logic
    $('#search').on('input', function() {
        if ($(this).val().length > 0) {
            // Optionally clear specific field filters when using general search
            // Uncomment if you want this behavior
            // $('#request-id, #student-id, #student-name, #leave-type').val('').trigger('change');
        }
    });
    
    // Clear general search when specific fields are used
    $('#request-id, #student-id, #student-name, #leave-type').on('change input', function() {
        if ($(this).val() && $(this).val().trim() !== '') {
            // Optionally clear general search when using specific filters
            // Uncomment if you want this behavior
            // $('#search').val('');
        }
    });
    
    // Handle date filter conflicts
    $('.date-range-picker').on('apply.daterangepicker', function() {
        // Clear individual date fields when date range is selected
        $('#date-from, #date-to').val('');
    });
    
    $('#date-from, #date-to').on('change', function() {
        if ($(this).val()) {
            // Clear date range when individual dates are selected
            $('.date-range-picker').val('');
        }
    });
    
    // Real-time search (optional)
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Uncomment to enable real-time search
            // $('#search-form').submit();
        }, 1000);
    });
    
    // Form validation and enhancement
    $('#search-form').on('submit', function(e) {
        // Validate date range
        const dateFrom = $('#date-from').val();
        const dateTo = $('#date-to').val();
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            e.preventDefault();
            alert('Start date cannot be after end date!');
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Searching...');
        
        return true;
    });
    
    // Highlight search terms in results
    function highlightSearchTerms() {
        const searchTerm = $('#search').val();
        if (searchTerm && searchTerm.length > 2) {
            $('.table tbody tr').each(function() {
                const row = $(this);
                row.find('td').each(function(index) {
                    // Skip action column (usually last column)
                    if (index === row.find('td').length - 1) return;
                    
                    const cell = $(this);
                    const text = cell.text();
                    if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const highlightedText = text.replace(regex, '<mark>$1</mark>');
                        // Only apply if cell doesn't contain HTML elements
                        if (!cell.find('*').length) {
                            cell.html(highlightedText);
                        }
                    }
                });
            });
        }
    }
    
    // Apply highlighting on page load
    highlightSearchTerms();
    
    // Auto-focus on search input
    $('#search').focus();
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#search').focus().select();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            $('#search').val('');
            $('#search-form input, #search-form select').val('').trigger('change');
        }
    });
    
    // Smart status badge colors
    $('.table tbody tr').each(function() {
        const statusCell = $(this).find('td .badge');
        const status = statusCell.text().toLowerCase().trim();
        
        statusCell.removeClass('bg-success bg-warning bg-danger bg-secondary');
        
        if (status === 'approved') {
            statusCell.addClass('bg-success');
        } else if (status === 'pending') {
            statusCell.addClass('bg-warning');
        } else if (status === 'rejected') {
            statusCell.addClass('bg-danger');
        } else {
            statusCell.addClass('bg-secondary');
        }
    });
    
    // Add row hover effects
    $('.table tbody tr').hover(
        function() {
            $(this).addClass('table-active');
        },
        function() {
            $(this).removeClass('table-active');
        }
    );
    
    // Quick filter buttons
    $('.btn[href*="status="]').on('click', function() {
        // Add visual feedback for quick filter buttons
        $('.btn[href*="status="]').removeClass('active');
        $(this).addClass('active');
    });
    
    // Initialize current quick filter button as active
    const currentStatus = new URLSearchParams(window.location.search).get('status');
    if (currentStatus) {
        $(`.btn[href*="status=${currentStatus}"]`).addClass('active');
    }
    
    // Enhanced pagination info
    const paginationInfo = $('.pagination').siblings('div').last();
    if (paginationInfo.length) {
        paginationInfo.addClass('small text-muted mt-2');
    }
    
    // Add loading overlay for better UX
    function showLoading() {
        if (!$('#loading-overlay').length) {
            $('body').append(`
                <div id="loading-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255,255,255,0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                ">
                    <div class="text-center">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                        <div class="mt-2">Loading...</div>
                    </div>
                </div>
            `);
        }
    }
    
    function hideLoading() {
        $('#loading-overlay').remove();
    }
    
    // Show loading on form submit and page navigation
    $('#search-form, .pagination a').on('click', function() {
        showLoading();
        
        // Hide loading after a delay if page doesn't change
        setTimeout(hideLoading, 5000);
    });
    
    // Hide loading when page loads
    $(window).on('load', function() {
        hideLoading();
    });
});
</script>
