<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Approval $approval
 * @var string[]|\Cake\Collection\CollectionInterface $lecturers
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Edit Approval' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Leave Request Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="approvalActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="approvalActions">
                <?= $this->Html->link(
                    '<i class="fas fa-eye me-2"></i>' . __('View Approval'), 
                    ['action' => 'view', $approval->approval_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Approvals'), 
                    ['action' => 'index'], 
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
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <h5 class="mb-0">Approval Information</h5>
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
                                <div class="fw-bold">#<?= h($approval->request_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Status</label>
                                <div>
                                    <?php 
                                    $statusClass = 'secondary';
                                    $statusText = h($approval->status ?? 'Pending');
                                    
                                    switch(strtolower($statusText)) {
                                        case 'pending':
                                            $statusClass = 'warning text-dark';
                                            break;
                                        case 'approved':
                                            $statusClass = 'success';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        <?= $statusText ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Created</label>
                                <div class="fw-semibold">
                                    <?= $approval->created ? h($approval->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Last Modified</label>
                                <div class="fw-semibold">
                                    <?= $approval->modified ? h($approval->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-warning text-dark d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i>
                    <h5 class="mb-0">Edit Approval Details</h5>
                    <small class="ms-auto opacity-75">Modify the approval information</small>
                </div>
                <div class="card-body p-4">
                    <?= $this->Form->create($approval, [
                        'class' => 'needs-validation',
                        'novalidate' => true
                    ]) ?>
                    
                    <fieldset>
                        <div class="row g-4">
                            <!-- Request ID -->
                            <div class="col-md-6">
                                <label for="request-id" class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-1 text-primary"></i>
                                    Request ID
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('request_id', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'request-id',
                                    'type' => 'number',
                                    'min' => 1
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    The leave request this approval is for
                                </div>
                            </div>

                            <!-- Lecturer -->
                            <div class="col-md-6">
                                <label for="lecturer-select" class="form-label fw-bold">
                                    <i class="fas fa-user-tie me-1 text-primary"></i>
                                    Lecturer
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('lecturer_id', [
                                    'options' => $lecturers ?? [],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Select Lecturer --',
                                    'id' => 'lecturer-select'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Who processed this approval
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status-select" class="form-label fw-bold">
                                    <i class="fas fa-flag me-1 text-warning"></i>
                                    Status
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('status', [
                                    'options' => [
                                        'Pending' => 'Pending',
                                        'Approved' => 'Approved',
                                        'Rejected' => 'Rejected'
                                    ],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Select Status --',
                                    'id' => 'status-select'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Current approval status
                                </div>
                            </div>

                            <!-- Approved At -->
                            <div class="col-md-6">
                                <label for="approved-at" class="form-label fw-bold">
                                    <i class="fas fa-calendar-check me-1 text-success"></i>
                                    Approved At
                                </label>
                                <?= $this->Form->control('approved_at', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'approved-at',
                                    'type' => 'datetime-local'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    When this approval was processed
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label for="remarks-textarea" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-1 text-info"></i>
                                    Remarks
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <?= $this->Form->control('remarks', [
                                    'type' => 'textarea',
                                    'rows' => 4,
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'style' => 'height: 120px;',
                                    'placeholder' => 'Add any comments or notes about this approval...',
                                    'id' => 'remarks-textarea'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Additional comments or feedback
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Action Buttons -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), 
                                        ['action' => 'index'], 
                                        [
                                            'class' => 'btn btn-outline-secondary btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                </div>
                                <div class="d-flex gap-3">
                                    <?= $this->Form->button(
                                        '<i class="fas fa-undo me-2"></i>' . __('Reset'), 
                                        [
                                            'type' => 'reset',
                                            'class' => 'btn btn-outline-warning btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                    <?= $this->Form->button(
                                        '<i class="fas fa-save me-2"></i>' . __('Update Approval'), 
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-primary btn-lg px-5',
                                            'id' => 'submit-btn',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.info-item {
    margin-bottom: 1rem;
}

.info-item label {
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card {
    transition: all 0.3s ease;
    border-radius: 15px !important;
}

.card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.form-select:focus,
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.form-select,
.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.badge {
    border-radius: 10px;
    font-weight: 600;
}

.form-text {
    font-size: 0.85rem;
    margin-top: 0.5rem;
    color: #6c757d;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none;
    padding: 1.5rem;
}

.card-body {
    border-radius: 0 0 15px 15px;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-3 {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    const submitBtn = document.getElementById('submit-btn');
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        }
        form.classList.add('was-validated');
    });
    
    // Add visual feedback for required fields
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    });
    
    // Status change handling
    const statusSelect = document.getElementById('status-select');
    statusSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        if (selectedValue === 'Approved' || selectedValue === 'Rejected') {
            // Auto-fill approved_at if it's empty and status is not pending
            const approvedAtField = document.getElementById('approved-at');
            if (!approvedAtField.value) {
                const now = new Date();
                const isoString = now.getFullYear() + '-' + 
                    String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(now.getDate()).padStart(2, '0') + 'T' + 
                    String(now.getHours()).padStart(2, '0') + ':' + 
                    String(now.getMinutes()).padStart(2, '0');
                approvedAtField.value = isoString;
            }
        }
    });
});
</script>