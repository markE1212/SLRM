<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Student> $students
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
                            <li><?= $this->Html->link(__('<i class="fa-solid fa-plus"></i> New Student'), ['action' => 'add'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
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
                            <th><?= $this->Paginator->sort('student_id', 'ID') ?></th>
                            <th><?= $this->Paginator->sort('name', 'Student') ?></th>
                            <th><?= $this->Paginator->sort('matrix_number', 'Matrix') ?></th>
                            <th><?= $this->Paginator->sort('program', 'Program') ?></th>
                            <th><?= $this->Paginator->sort('status', 'Status') ?></th>
                            <th><?= $this->Paginator->sort('created', 'Enrolled') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <strong class="text-primary"><?= $this->Number->format($student->student_id) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-2">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <strong><?= h($student->name) ?></strong>
                                            <?php if (!empty($student->email)): ?>
                                                <br><small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?= $this->Text->truncate(h($student->email), 25) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-soft">
                                        <i class="fas fa-id-card me-1"></i>
                                        <?= h($student->matrix_number) ?>
                                    </span>
                                    <?php if (!empty($student->phone)): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <?= h($student->phone) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-graduation-cap me-1 text-muted"></i>
                                    <strong><?= h($student->program) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    $statusIcon = 'fa-question';
                                    $statusText = h($student->status ?? 'Unknown');
                                    
                                    switch(strtolower($statusText)) {
                                        case 'active':
                                            $statusClass = 'success';
                                            $statusIcon = 'fa-check-circle';
                                            break;
                                        case 'inactive':
                                        case 'disabled':
                                            $statusClass = 'warning text-dark';
                                            $statusIcon = 'fa-pause-circle';
                                            break;
                                        case 'archived':
                                            $statusClass = 'secondary';
                                            $statusIcon = 'fa-archive';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <i class="fas <?= $statusIcon ?> me-1"></i>
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $student->created ? $student->created->format('M d, Y') : '<span class="text-muted">—</span>' ?>
                                    <br><small class="text-muted"><?= $student->created ? $student->created->format('H:i') : '' ?></small>
                                </td>
                                <td class="actions text-center">
                                    <div class="btn-group shadow" role="group" aria-label="Actions">
                                        <?= $this->Html->link(__('<i class="far fa-folder-open"></i>'), ['action' => 'view', $student->student_id], ['class' => 'btn btn-outline-primary btn-xs', 'escapeTitle' => false, 'title' => 'View Details']) ?>
                                        
                                        <?= $this->Html->link(__('<i class="fa-regular fa-pen-to-square"></i>'), ['action' => 'edit', $student->student_id], ['class' => 'btn btn-outline-warning btn-xs', 'escapeTitle' => false, 'title' => 'Edit']) ?>
                                        
                                        <?php if (!empty($student->email)): ?>
                                            <?= $this->Html->link(__('<i class="fas fa-envelope"></i>'), 'mailto:' . h($student->email), ['class' => 'btn btn-outline-info btn-xs', 'escapeTitle' => false, 'title' => 'Send Email']) ?>
                                        <?php endif; ?>
                                        
                                        <?php if ($student->status !== 'Archived'): ?>
                                            <?= $this->Html->link(__('<i class="fas fa-archive"></i>'), ['action' => 'archived', $student->student_id], ['class' => 'btn btn-outline-secondary btn-xs', 'escapeTitle' => false, 'title' => 'Archive']) ?>
                                        <?php endif; ?>
                                        
                                        <?php $this->Form->setTemplates([
                                            'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        ]); ?>
                                        <?= $this->Form->postLink(
                                            __('<i class="fa-regular fa-trash-can"></i>'),
                                            ['action' => 'delete', $student->student_id],
                                            [
                                                'confirm' => __('Are you sure you want to delete student "{0}"?', $student->name),
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
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No students found</h5>
                                    <p class="text-muted">Try adjusting your search criteria or add a new student.</p>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-plus me-2"></i>Add First Student',
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

               <div class="tab-pane container fade px-0" id="report">
            <div class="row pb-3">
                <div class="col-md-4">
                  <div class="stat_card card-1 bg-body-tertiary">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="stat_card card-2 bg-body-tertiary">
                    <h3><?php echo $total_students_active; ?></h3>
                    <p>Active Students</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="stat_card card-3 bg-body-tertiary">
                    <h3><?php echo $total_students_archived; ?></h3>
                    <p>Archived Students</p>
                  </div>
                </div>
            </div>
            
<div class="row">
    <div class="col-md-6">
    <div class="card bg-body-tertiary border-0 shadow mb-4">
        <div class="card-body">
            <div class="card-title mb-0">Students (Monthly)</div>
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
            label: '# of Students(s)',
            data: <?php echo json_encode($countArray); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)','rgba(75, 192, 192, 0.2)','rgba(153, 102, 255, 0.2)','rgba(89, 233, 28, 0.2)','rgba(255, 5, 5, 0.2)','rgba(255, 128, 0, 0.2)','rgba(153, 153, 153, 0.2)','rgba(15, 207, 210, 0.2)','rgba(44, 13, 181, 0.2)','rgba(86, 172, 12, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)','rgba(75, 192, 192, 1)','rgba(153, 102, 255, 1)','rgba(89, 233, 28, 1)','rgba(255, 5, 5, 1)','rgba(255, 128, 0, 1)','rgba(153, 153, 153, 1)','rgba(15, 207, 210, 1)','rgba(44, 13, 181, 1)','rgba(86, 172, 12, 1)'
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
                text: 'Students (Monthly)',
                font: {
                  size: 15
                    }
                },
            subtitle: {
                display: false,
                text: '<?php echo $system_name; ?>'
                },
            legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
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
        <div class="card-title mb-0">Students by Status</div>
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
            label: '# of Students(s)',
            data: [<?= json_encode($total_students_active); ?>, <?= json_encode($total_students_disabled); ?>, <?= json_encode($total_students_archived); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)',
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
                text: 'Students by Status',
                font: {
                  size: 15
                    }
                },
            subtitle: {
                display: false,
                text: '<?php echo $system_name; ?>'
                },
            legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
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
                $sub = 'students';
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
                    'placeholder' => 'Search name, matrix, program, email...',
                    'class' => 'form-control form-control-sm',
                    'value' => $this->request->getQuery('search'),
                    'autocomplete' => 'off'
                ]) ?>
                <small class="text-muted">Search across all fields</small>
            </div>
            
            <hr class="my-3">
            <small class="text-muted fw-bold">OR FILTER BY SPECIFIC FIELDS:</small>
            
            <!-- Student ID Filter -->
            <div class="mb-3 mt-2">
                <?= $this->Form->control('student_id', [
                    'label' => 'Student ID',
                    'class' => 'form-control form-control-sm',
                    'value' => $this->request->getQuery('student_id'),
                    'placeholder' => 'Enter student ID...',
                    'type' => 'number'
                ]) ?>
            </div>
            
            <!-- Name Filter -->
            <div class="mb-3">
                <?= $this->Form->control('name', [
                    'label' => 'Student Name',
                    'class' => 'form-control form-control-sm',
                    'value' => $this->request->getQuery('name'),
                    'placeholder' => 'Enter name...'
                ]) ?>
            </div>
            
            <!-- Matrix Number Filter -->
            <div class="mb-3">
                <?= $this->Form->control('matrix_number', [
                    'label' => 'Matrix Number',
                    'class' => 'form-control form-control-sm',
                    'value' => $this->request->getQuery('matrix_number'),
                    'placeholder' => 'Enter matrix number...'
                ]) ?>
            </div>
            
            <!-- Program Filter -->
            <div class="mb-3">
                <?= $this->Form->control('program', [
                    'label' => 'Program',
                    'class' => 'form-control form-control-sm',
                    'value' => $this->request->getQuery('program'),
                    'placeholder' => 'Enter program...'
                ]) ?>
            </div>
            
            <!-- Status Filter -->
            <div class="mb-3">
                <?= $this->Form->control('status', [
                    'label' => 'Status',
                    'class' => 'form-control form-control-sm',
                    'empty' => '-- All Status --',
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
                        echo "Found {$totalRecords} student(s)";
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
                        <strong class="text-success"><?= h($total_students_active) ?></strong><br>
                        <small class="text-muted">Active</small>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="p-2 bg-warning bg-opacity-10 rounded">
                        <strong class="text-warning"><?= h($total_students_disabled) ?></strong><br>
                        <small class="text-muted">Disabled</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-secondary bg-opacity-10 rounded">
                        <strong class="text-secondary"><?= h($total_students_archived) ?></strong><br>
                        <small class="text-muted">Archived</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-primary bg-opacity-10 rounded">
                        <strong class="text-primary"><?= h($total_students) ?></strong><br>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
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
    
    // Add tooltips to action buttons
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
    
    // Clear other fields when general search is used
    $('#search').on('input', function() {
        if ($(this).val().length > 0) {
            // Optionally clear specific field filters when using general search
            // Uncomment the lines below if you want this behavior
            // $('#student-id, #name, #matrix-number, #program').val('');
        }
    });
    
    // Clear general search when specific fields are used
    $('#student-id, #name, #matrix-number, #program').on('input', function() {
        if ($(this).val().length > 0) {
            // Optionally clear general search when using specific filters
            // Uncomment the line below if you want this behavior
            // $('#search').val('');
        }
    });
    
    // Real-time search (optional - searches as you type)
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Uncomment the line below to enable real-time search
            // $('#search-form').submit();
        }, 1000); // Wait 1 second after user stops typing
    });
    
    // Form validation
    $('#search-form').on('submit', function(e) {
        // Check if at least one field has a value
        let hasValue = false;
        $(this).find('input[type="text"], input[type="number"], select').each(function() {
            if ($(this).val() && $(this).val().trim() !== '') {
                hasValue = true;
                return false; // Break the loop
            }
        });
        
        // If no search criteria provided, you might want to show all results
        // This is optional - remove if you want to prevent empty searches
        if (!hasValue) {
            // Allow the form to submit anyway to show all results
            return true;
        }
        
        return true;
    });
    
    // Highlight search terms in results (optional)
    function highlightSearchTerms() {
        const searchTerm = $('#search').val();
        if (searchTerm && searchTerm.length > 2) {
            $('.table tbody tr').each(function() {
                const row = $(this);
                row.find('td').each(function() {
                    const cell = $(this);
                    const text = cell.text();
                    if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const highlightedText = text.replace(regex, '<mark>$1</mark>');
                        cell.html(highlightedText);
                    }
                });
            });
        }
    }
    
    // Call highlight function on page load if there's a search term
    highlightSearchTerms();
    
    // Add loading state to search button
    $('#search-form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i>Searching...');
        
        // Re-enable after a short delay (in case of quick response)
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fa-solid fa-search me-1"></i>Search');
        }, 2000);
    });
    
    // Auto-focus on search input
    $('#search').focus();
    
    // Add keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#search').focus().select();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            $('#search').val('');
            $('#search-form input, #search-form select').val('');
        }
    });
});
</script>
