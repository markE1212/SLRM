<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Approval> $approvals
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
        <h6 class="sub_title text-body-secondary"><?php echo $system_name ?? 'Leave Request Management System'; ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-bars text-primary"></i>
            </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                            
                            <li><?= $this->Html->link(__('<i class="fa-solid fa-eye"></i> View All Requests'), ['controller' => 'Leaverequests', 'action' => 'index'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
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
                                        <th><?= $this->Paginator->sort('approval_id', 'ID') ?></th>
                                        <th><?= $this->Paginator->sort('request_id', 'Request ID') ?></th>
                                        <th><?= $this->Paginator->sort('Lecturers.name', 'Lecturer') ?></th>
                                        <th><?= $this->Paginator->sort('status') ?></th>
                                        <th><?= $this->Paginator->sort('created', 'Requested At') ?></th>
                                        <th><?= $this->Paginator->sort('approved_at', 'Processed At') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($approvals)) : ?>
                                        <?php foreach ($approvals as $approval): ?>
                                        <tr>
                                            <td><?php echo $counter++ ?></td>
                                            <td><?= $this->Number->format($approval->approval_id) ?></td>
                                            <td>
                                                <strong><?= h($approval->request_id) ?></strong>
                                                <?php if (!empty($approval->remarks)): ?>
                                                    <br><small class="text-muted"><?= $this->Text->truncate(h($approval->remarks), 30) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $approval->lecturer ? h($approval->lecturer->name) : '<span class="text-muted">—</span>' ?>
                                                <?php if (!empty($approval->lecturer->department)): ?>
                                                    <br><small class="text-muted"><?= h($approval->lecturer->department) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                $statusText = h($approval->status ?? '—');
                                                
                                                if (in_array(strtolower($approval->status ?? ''), ['approved', '1'])) {
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Approved';
                                                } elseif (in_array(strtolower($approval->status ?? ''), ['pending', '0'])) {
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusText = 'Pending';
                                                } elseif (in_array(strtolower($approval->status ?? ''), ['rejected', '2'])) {
                                                    $statusClass = 'bg-danger';
                                                    $statusText = 'Rejected';
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                                
                                                <?php if ($approval->status === 'Pending' || $approval->status === 'pending' || $approval->status === 0): ?>
                                                    <br><small class="text-muted">
                                                        <i class="fa-solid fa-clock"></i> 
                                                        <?= $approval->created ? $approval->created->timeAgoInWords() : '' ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $approval->created ? $approval->created->format('M d, Y') : '<span class="text-muted">—</span>' ?>
                                                <br><small class="text-muted"><?= $approval->created ? $approval->created->format('H:i') : '' ?></small>
                                            </td>
                                            <td>
                                                <?= $approval->approved_at ? $approval->approved_at->format('M d, Y') : '<span class="text-muted">—</span>' ?>
                                                <br><small class="text-muted"><?= $approval->approved_at ? $approval->approved_at->format('H:i') : '' ?></small>
                                            </td>
                                            <td class="actions text-center">
                                                <div class="btn-group shadow" role="group" aria-label="Actions">
                                                    <?= $this->Html->link(__('<i class="far fa-folder-open"></i>'), ['action' => 'view', $approval->approval_id], ['class' => 'btn btn-outline-primary btn-xs', 'escapeTitle' => false, 'title' => 'View Details']) ?>
                                                    
                                                    <?php if (in_array(strtolower($approval->status ?? ''), ['pending', '0'])): ?>
                                                        <?= $this->Html->link(__('<i class="fa-solid fa-check"></i>'), ['action' => 'add', $approval->request_id, '?' => ['status' => 'Approved']], ['class' => 'btn btn-outline-success btn-xs', 'escapeTitle' => false, 'title' => 'Approve']) ?>
                                                        <?= $this->Html->link(__('<i class="fa-solid fa-times"></i>'), ['action' => 'add', $approval->request_id, '?' => ['status' => 'Rejected']], ['class' => 'btn btn-outline-danger btn-xs', 'escapeTitle' => false, 'title' => 'Reject']) ?>
                                                    <?php endif; ?>
                                                    
                                                    <?= $this->Html->link(__('<i class="fa-regular fa-pen-to-square"></i>'), ['action' => 'edit', $approval->approval_id], ['class' => 'btn btn-outline-warning btn-xs', 'escapeTitle' => false, 'title' => 'Edit']) ?>
                                                    
                                                    <?php $this->Form->setTemplates([
                                                        'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                                    ]); ?>
                                                    <?= $this->Form->postLink(
                                                        __('<i class="fa-regular fa-trash-can"></i>'),
                                                        ['action' => 'delete', $approval->approval_id],
                                                        [
                                                            'confirm' => __('Are you sure you want to delete this approval record?'),
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
                                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No approvals found</h5>
                                                <p class="text-muted">Try adjusting your search criteria or add a new approval.</p>
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
                    <div class="col-md-3">
                      <div class="stat_card card-1 bg-body-tertiary">
                        <h3><?php echo $totalApprovals ?? 0; ?></h3>
                        <p>Total Approvals</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat_card card-2 bg-body-tertiary">
                        <h3><?php echo $totalPending ?? 0; ?></h3>
                        <p>Pending Approvals</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat_card card-3 bg-body-tertiary">
                        <h3><?php echo $totalApproved ?? 0; ?></h3>
                        <p>Approved</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="stat_card card-4 bg-body-tertiary">
                        <h3><?php echo $totalRejected ?? 0; ?></h3>
                        <p>Rejected</p>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary border-0 shadow mb-4">
                            <div class="card-body">
                                <div class="card-title mb-0">Monthly Approvals Trend</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="monthly"></canvas>
                                </div>
                                <script>
                                const ctx = document.getElementById('monthly');
                                const monthly = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: <?php echo json_encode($monthArray ?? []); ?>,
                                        datasets: [{
                                            label: '# of Approvals',
                                            data: <?php echo json_encode($countArray ?? []); ?>,
                                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: { stepSize: 1 }
                                            }
                                        },
                                        plugins: {
                                            title: {
                                                display: false,
                                                text: 'Monthly Approvals Trend'
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
                                <div class="card-title mb-0">Approval Status Distribution</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="status"></canvas>
                                </div>
                                <script>
                                const ctx_2 = document.getElementById('status');
                                const status = new Chart(ctx_2, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Approved', 'Pending', 'Rejected'],
                                        datasets: [{
                                            label: '# of Approvals',
                                            data: [
                                                <?= $totalApproved ?? 0 ?>, 
                                                <?= $totalPending ?? 0 ?>, 
                                                <?= $totalRejected ?? 0 ?>
                                            ],
                                            backgroundColor: [
                                                'rgba(75, 192, 192, 0.8)',
                                                'rgba(255, 206, 86, 0.8)',
                                                'rgba(255, 99, 132, 0.8)'
                                            ],
                                            borderColor: [
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(255, 99, 132, 1)'
                                            ],
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            title: {
                                                display: false
                                            },
                                            legend: {
                                                position: 'bottom',
                                            },
                                        }
                                    }
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Statistics -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-body-tertiary border-0 shadow mb-4">
                            <div class="card-body">
                                <div class="card-title mb-0">Processing Performance</div>
                                <div class="tricolor_line mb-3"></div>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="p-3 bg-info bg-opacity-10 rounded">
                                            <h4 class="text-info">
                                                <?= number_format(($totalApproved + $totalRejected) > 0 ? (($totalApproved + $totalRejected) / $totalApprovals) * 100 : 0, 1) ?>%
                                            </h4>
                                            <small class="text-muted">Processing Rate</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-success bg-opacity-10 rounded">
                                            <h4 class="text-success">
                                                <?= $totalApproved > 0 ? number_format(($totalApproved / $totalApprovals) * 100, 1) : 0 ?>%
                                            </h4>
                                            <small class="text-muted">Approval Rate</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                                            <h4 class="text-warning">
                                                <?= $totalPending > 0 ? number_format(($totalPending / $totalApprovals) * 100, 1) : 0 ?>%
                                            </h4>
                                            <small class="text-muted">Pending Rate</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                                            <h4 class="text-danger">
                                                <?= $totalRejected > 0 ? number_format(($totalRejected / $totalApprovals) * 100, 1) : 0 ?>%
                                            </h4>
                                            <small class="text-muted">Rejection Rate</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane container fade px-0" id="export">
                <?php
                    $domain = Router::url("/", true);
                    $sub = 'approvals';
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
                        'placeholder' => 'Search ID, status, lecturer...',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('search'),
                        'autocomplete' => 'off'
                    ]) ?>
                    <small class="text-muted">Search across all fields</small>
                </div>
                
                <hr class="my-3">
                <small class="text-muted fw-bold">OR FILTER BY SPECIFIC FIELDS:</small>
                
                <!-- Approval ID Filter -->
                <div class="mb-3 mt-2">
                    <?= $this->Form->control('approval_id', [
                        'label' => 'Approval ID',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('approval_id'),
                        'placeholder' => 'Enter approval ID...',
                        'type' => 'number'
                    ]) ?>
                </div>
                
                <!-- Request ID Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('request_id', [
                        'label' => 'Request ID',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('request_id'),
                        'placeholder' => 'Enter request ID...',
                        'type' => 'number'
                    ]) ?>
                </div>
                
                <!-- Lecturer Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('lecturer_id', [
                        'label' => 'Lecturer',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Lecturers --',
                        'options' => $lecturers ?? [],
                        'value' => $this->request->getQuery('lecturer_id')
                    ]) ?>
                </div>
                
                <!-- Lecturer Name Filter (Alternative) -->
                <div class="mb-3">
                    <?= $this->Form->control('lecturer_name', [
                        'label' => 'Lecturer Name',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('lecturer_name'),
                        'placeholder' => 'Enter lecturer name...'
                    ]) ?>
                </div>
                
                <!-- Status Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('status', [
                        'label' => 'Status',
                        'class' => 'form-control form-control-sm',
                        'empty' => '-- All Statuses --',
                        'options' => [
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected'
                        ],
                        'value' => $this->request->getQuery('status')
                    ]) ?>
                </div>
                
                <!-- Remarks Filter -->
                <div class="mb-3">
                    <?= $this->Form->control('remarks', [
                        'label' => 'Remarks',
                        'class' => 'form-control form-control-sm',
                        'value' => $this->request->getQuery('remarks'),
                        'placeholder' => 'Search in remarks...'
                    ]) ?>
                </div>
                
                <!-- Date Range Filters -->
                <div class="mb-3">
                    <?= $this->Form->control('date_from', [
                        'label' => 'Created From',
                        'class' => 'form-control form-control-sm',
                        'type' => 'date',
                        'value' => $this->request->getQuery('date_from')
                    ]) ?>
                </div>
                
                <div class="mb-3">
                    <?= $this->Form->control('date_to', [
                        'label' => 'Created To',
                        'class' => 'form-control form-control-sm',
                        'type' => 'date',
                        'value' => $this->request->getQuery('date_to')
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
                            echo "Found {$totalRecords} approval(s)";
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
                            <strong class="text-warning"><?= h($totalPending ?? 0) ?></strong><br>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-success bg-opacity-10 rounded">
                            <strong class="text-success"><?= h($totalApproved ?? 0) ?></strong><br>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-danger bg-opacity-10 rounded">
                            <strong class="text-danger"><?= h($totalRejected ?? 0) ?></strong><br>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-primary bg-opacity-10 rounded">
                            <strong class="text-primary"><?= h($totalApprovals ?? 0) ?></strong><br>
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
                   
                                       <?= $this->Html->link('<i class="fa-solid fa-clock me-1"></i>Pending Only', ['action' => 'index', '?' => ['status' => 'pending']], [
                        'class' => 'btn btn-outline-warning btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-check me-1"></i>Approved Only', ['action' => 'index', '?' => ['status' => 'approved']], [
                        'class' => 'btn btn-outline-success btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-times me-1"></i>Rejected Only', ['action' => 'index', '?' => ['status' => 'rejected']], [
                        'class' => 'btn btn-outline-danger btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                    
                    <?= $this->Html->link('<i class="fa-solid fa-list me-1"></i>All Requests', ['controller' => 'Leaverequests', 'action' => 'index'], [
                        'class' => 'btn btn-outline-info btn-sm',
                        'escapeTitle' => false
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card bg-body-tertiary border-0 shadow mb-4">
            <div class="card-body">
                <div class="card-title mb-0">Recent Activity</div>
                <div class="tricolor_line mb-3"></div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Today</small>
                            <p class="mb-0 small"><?= $totalApproved ?> requests approved</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Pending</small>
                            <p class="mb-0 small"><?= $totalPending ?> awaiting review</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Rejected</small>
                            <p class="mb-0 small"><?= $totalRejected ?> requests declined</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="bootstrapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
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
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    }
    
    .card-3 {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    }
    
    .card-4 {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
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
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 15px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .timeline-content {
        padding-left: 10px;
    }
    
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -21px;
        top: 15px;
        height: 100%;
        width: 2px;
        background-color: #dee2e6;
    }
</style>

<script>
$(document).ready(function() {
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
    
    // Date validation
    $('#date-from, #date-to').on('change', function() {
        const dateFrom = $('#date-from').val();
        const dateTo = $('#date-to').val();
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert('Date From cannot be greater than Date To');
            $(this).val('');
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
    
    // Auto-refresh pending count every 30 seconds (optional)
    setInterval(function() {
        // You can implement auto-refresh logic here if needed
        console.log('Auto-refresh check...');
    }, 30000);
});
</script>