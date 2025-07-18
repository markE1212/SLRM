<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Approval $approval
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'View Approval' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Student Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="approvalActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="approvalActions">
                <?= $this->Html->link(
                    '<i class="fas fa-edit me-2"></i>' . __('Edit Approval'), 
                    ['action' => 'edit', $approval->approval_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Approvals'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-plus me-2"></i>' . __('New Approval'), 
                    ['action' => 'add'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <hr class="dropdown-divider">
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash me-2 text-danger"></i>' . __('Delete'),
                    ['action' => 'delete', $approval->approval_id],
                    [
                        'confirm' => __('Are you sure you want to delete approval #{0}?', $approval->approval_id), 
                        'class' => 'dropdown-item text-danger', 
                        'escapeTitle' => false
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>
<div class="line mb-4"></div>
<!--/Header-->

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            
            <!-- Approval Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <h5 class="mb-0">Approval #<?= h($approval->approval_id) ?></h5>
                    <div class="ms-auto">
                        <?php 
                        $statusClass = 'secondary';
                        $statusText = h($approval->status ?? 'Unknown');
                        $statusIcon = 'fa-question-circle';
                        
                        switch(strtolower($statusText)) {
                            case 'approved':
                            case '1':
                                $statusClass = 'success';
                                $statusIcon = 'fa-check-circle';
                                break;
                            case 'rejected':
                            case '2':
                                $statusClass = 'danger';
                                $statusIcon = 'fa-times-circle';
                                break;
                            case 'pending':
                            case '0':
                                $statusClass = 'warning text-dark';
                                $statusIcon = 'fa-clock';
                                break;
                        }
                        ?>
                        <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2">
                            <i class="fas <?= $statusIcon ?> me-1" style="font-size: 0.8rem;"></i>
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Approval ID</label>
                                <div class="fw-bold text-primary fs-5">#<?= h($approval->approval_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Request ID</label>
                                <div class="fw-bold">
                                    <?php if ($approval->hasValue('leaverequest') && $approval->leaverequest): ?>
                                        <?= $this->Html->link(
                                            '#' . h($approval->request_id), 
                                            ['controller' => 'Leaverequests', 'action' => 'view', $approval->request_id],
                                            ['class' => 'text-decoration-none fw-bold']
                                        ) ?>
                                    <?php else: ?>
                                        #<?= h($approval->request_id) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Approved Date</label>
                                <div class="fw-bold">
                                    <?= $approval->approved_at ? h($approval->approved_at->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-users me-2"></i>
                    <h5 class="mb-0">Related Information</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-chalkboard-teacher me-1 text-primary"></i>
                                    Approved By (Lecturer)
                                </label>
                                <div class="fw-semibold">
                                    <?php if ($approval->hasValue('lecturer') && $approval->lecturer): ?>
                                        <?= $this->Html->link(
                                            h($approval->lecturer->name), 
                                            ['controller' => 'Lecturers', 'action' => 'view', $approval->lecturer->lecturer_id],
                                            ['class' => 'text-decoration-none fw-bold']
                                        ) ?>
                                        <?php if (!empty($approval->lecturer->department)): ?>
                                            <small class="d-block text-muted"><?= h($approval->lecturer->department) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-file-alt me-1 text-primary"></i>
                                    Leave Request
                                </label>
                                <div class="fw-semibold">
                                    <?php if ($approval->hasValue('leaverequest') && $approval->leaverequest): ?>
                                        <?= $this->Html->link(
                                            'Request #' . h($approval->request_id), 
                                            ['controller' => 'Leaverequests', 'action' => 'view', $approval->request_id],
                                            ['class' => 'text-decoration-none fw-bold']
                                        ) ?>
                                        <?php if (!empty($approval->leaverequest->reason)): ?>
                                            <small class="d-block text-muted"><?= h($approval->leaverequest->reason) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Request #<?= h($approval->request_id) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remarks Card -->
            <?php if (!empty($approval->remarks)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-warning text-dark d-flex align-items-center">
                    <i class="fas fa-comment-dots me-2"></i>
                    <h5 class="mb-0">Remarks</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="remarks-content p-3 bg-white rounded border-start border-warning border-4">
                        <?= $this->Text->autoParagraph(h($approval->remarks)) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- System Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-secondary text-white d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-calendar-plus me-1 text-primary"></i>
                                    Created
                                </label>
                                <div class="fw-semibold">
                                    <?= $approval->created ? h($approval->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-edit me-1 text-primary"></i>
                                    Last Modified
                                </label>
                                <div class="fw-semibold">
                                    <?= $approval->modified ? h($approval->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-success text-white d-flex align-items-center">
                    <i class="fas fa-bolt me-2"></i>
                    <h5 class="mb-0">Quick Actions</h5>
                    <small class="ms-auto opacity-75">Manage this approval</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-edit me-2"></i>' . __('Edit Approval'), 
                                ['action' => 'edit', $approval->approval_id], 
                                [
                                    'class' => 'btn btn-warning btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-list me-2"></i>' . __('All Approvals'), 
                                ['action' => 'index'], 
                                [
                                    'class' => 'btn btn-outline-primary btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-plus me-2"></i>' . __('New Approval'), 
                                ['action' => 'add'], 
                                [
                                    'class' => 'btn btn-outline-success btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($approval->hasValue('leaverequest')): ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-file-alt me-2"></i>' . __('View Leave Request'),
                                    ['controller' => 'Leaverequests', 'action' => 'view', $approval->request_id],
                                    [
                                        'class' => 'btn btn-outline-info btn-lg w-100 d-flex align-items-center justify-content-center',
                                        'escapeTitle' => false
                                    ]
                                ) ?>
                            <?php else: ?>
                                <?= $this->Form->postLink(
                                    '<i class="fas fa-trash me-2"></i>' . __('Delete Approval'),
                                    ['action' => 'delete', $approval->approval_id],
                                    [
                                        'confirm' => __('Are you sure you want to delete approval #{0}?', $approval->approval_id), 
                                        'class' => 'btn btn-outline-danger btn-lg w-100 d-flex align-items-center justify-content-center',
                                        'escapeTitle' => false
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.card {
    transition: all 0.3s ease;
    border-radius: 15px !important;
}

.card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.info-item {
    background: rgba(255, 255, 255, 0.7);
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
    border: none;
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    color: #212529;
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
}

.btn-outline-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.4);
}

.btn-outline-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 202, 240, 0.4);
}

.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
    padding: 1.5rem;
}

.card-body {
    border-radius: 0 0 15px 15px;
}

.line {
    height: 3px;
    background: linear-gradient(to right, #4e54c8, #8f94fb, #4e54c8);
    width: 100%;
    margin-bottom: 1rem;
}

.badge {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.remarks-content {
    font-style: italic;
    line-height: 1.6;
    min-height: 60px;
}

/* Status-specific styling */
.badge.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e91e63 100%) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
}

/* Link styling */
a.text-decoration-none {
    color: #0d6efd;
    font-weight: 500;
}

a.text-decoration-none:hover {
    color: #0a58ca;
    text-decoration: underline !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .col-md-4 .info-item,
    .col-md-6 .info-item {
        margin-bottom: 1rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
    
    .badge {
        font-size: 0.75rem !important;
        padding: 0.5rem 1rem !important;
    }
}

/* Animation for status badge */
.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

/* Hover effects for dropdown */
.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* Print styles */
@media print {
    .dropdown, .card-header .btn, .line {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .bg-gradient-primary,
    .bg-gradient-info,
    .bg-gradient-warning,
    .bg-gradient-secondary,
    .bg-gradient-success {
        background: #f8f9fa !important;
        color: #212529 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Add loading animation for buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.href || this.href.includes('delete')) return;
            
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            this.disabled = true;
            
            // Reset after a delay (in case navigation fails)
            setTimeout(() => {
                this.innerHTML = originalContent;
                this.disabled = false;
            }, 3000);
        });
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'e':
                    e.preventDefault();
                    const editBtn = document.querySelector('a[href*="edit"]');
                    if (editBtn) editBtn.click();
                    break;
                case 'l':
                    e.preventDefault();
                    const listBtn = document.querySelector('a[href*="index"]');
                    if (listBtn) listBtn.click();
                    break;
                case 'n':
                    e.preventDefault();
                    const addBtn = document.querySelector('a[href*="add"]');
                    if (addBtn) addBtn.click();
                    break;
            }
        }
    });
    
    // Add print functionality
    const printBtn = document.createElement('button');
    printBtn.className = 'btn btn-outline-secondary btn-lg position-fixed bottom-0 end-0 m-3 rounded-circle';
    printBtn.innerHTML = '<i class="fas fa-print"></i>';
    printBtn.title = 'Print Approval Details';
    printBtn.style.width = '60px';
    printBtn.style.height = '60px';
    printBtn.style.zIndex = '999';
    
    printBtn.addEventListener('click', function() {
        window.print();
    });
    
    document.body.appendChild(printBtn);
});
</script>