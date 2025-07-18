<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Leaverequest $leaverequest
 * @var string[]|\Cake\Collection\CollectionInterface $students
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Edit Leave Request' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Leave Request Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="requestActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="requestActions">
                <?= $this->Html->link(
                    '<i class="fas fa-eye me-2"></i>' . __('View Request'), 
                    ['action' => 'view', $leaverequest->request_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Requests'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <hr class="dropdown-divider">
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash me-2 text-danger"></i>' . __('Delete'),
                    ['action' => 'delete', $leaverequest->request_id],
                    [
                        'confirm' => __('Are you sure you want to delete request #{0}?', $leaverequest->request_id), 
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
            
            <!-- Request Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <h5 class="mb-0">Leave Request Information</h5>
                </div>
                <div class="card-body bg-dark">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Request ID</label>
                                <div class="fw-bold text-primary fs-5">#<?= h($leaverequest->request_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Status</label>
                                <div>
                                    <?php 
                                    $statusClass = 'secondary';
                                    $statusText = h($leaverequest->status ?? 'Pending');
                                    
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
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Attachment</label>
                                <div class="fw-semibold">
                                    <?php if (!empty($leaverequest->attachment_path)): ?>
                                        <a href="/files/<?= h($leaverequest->attachment_path) ?>" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-paperclip me-1"></i>
                                            <?= basename($leaverequest->attachment_path) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No attachment</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Created</label>
                                <div class="fw-semibold">
                                    <?= $leaverequest->created ? h($leaverequest->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Last Modified</label>
                                <div class="fw-semibold">
                                    <?= $leaverequest->modified ? h($leaverequest->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
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
                    <h5 class="mb-0">Edit Request Details</h5>
                    <small class="ms-auto opacity-75">Modify the leave request information</small>
                </div>
                <div class="card-body p-4">
                    <?= $this->Form->create($leaverequest, [
                        'type' => 'file',
                        'class' => 'needs-validation',
                        'novalidate' => true
                    ]) ?>
                    
                    <fieldset>
                        <div class="row g-4">
                            <!-- Student -->
                            <div class="col-md-6">
                                <label for="student-select" class="form-label fw-bold">
                                    <i class="fas fa-user-graduate me-1 text-primary"></i>
                                    Student
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('student_id', [
                                    'options' => $students ?? [],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Select Student --',
                                    'id' => 'student-select'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select the student requesting leave
                                </div>
                            </div>

                            <!-- Leave Date -->
                            <div class="col-md-6">
                                <label for="leave-date" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1 text-primary"></i>
                                    Leave Date
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('leave_date', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'leave-date',
                                    'type' => 'date'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Date when leave is requested
                                </div>
                            </div>

                            <!-- Leave Type -->
                            <div class="col-md-6">
                                <label for="leave-type" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1 text-primary"></i>
                                    Leave Type
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('leave_type', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'leave-type',
                                    'placeholder' => 'e.g., Medical, Personal, Emergency'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Type or category of leave being requested
                                </div>
                            </div>


                            <!-- Reason -->
                            <div class="col-12">
                                <label for="reason-textarea" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-1 text-info"></i>
                                    Reason for Leave
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('reason', [
                                    'type' => 'textarea',
                                    'rows' => 4,
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'style' => 'height: 120px;',
                                    'placeholder' => 'Please provide a detailed reason for the leave request...',
                                    'id' => 'reason-textarea',
                                    'required' => true
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Provide a clear explanation for the leave request
                                </div>
                            </div>

                            <!-- Attachment -->
                            <div class="col-12">
                                <label for="attachment-file" class="form-label fw-bold">
                                    <i class="fas fa-paperclip me-1 text-info"></i>
                                    Attachment
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <?= $this->Form->control('attachment_path', [
                                    'type' => 'file',
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'attachment-file',
                                    'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Upload supporting documents (PDF, DOC, DOCX, JPG, PNG - Max 5MB)
                                    <?php if (!empty($leaverequest->attachment_path)): ?>
                                        <br><strong>Current file:</strong> <?= basename($leaverequest->attachment_path) ?>
                                    <?php endif; ?>
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
                                        '<i class="fas fa-save me-2"></i>' . __('Update Request'), 
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

.line {
    height: 3px;
    background: linear-gradient(to right, #4e54c8, #8f94fb, #4e54c8);
    width: 100%;
    margin-bottom: 1rem;
}

/* Form validation styles */
.was-validated .form-control:valid,
.was-validated .form-select:valid {
    border-color: #28a745;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.94-.94 1.83 1.83L8.03 4.7l-.94-.94L4.17 6.68z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
}

.was-validated .form-control:invalid,
.was-validated .form-select:invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6L7.4 7.4M7.4 4.6L5.8 7.4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
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

/* File upload styling */
.form-control[type="file"] {
    padding: 0.75rem;
}

.form-control[type="file"]::-webkit-file-upload-button {
    padding: 0.5rem 1rem;
    margin-right: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.form-control[type="file"]::-webkit-file-upload-button:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
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
    
    // Real-time validation feedback
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
        
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
                validateField(this);
            }
        });
    });
    
    function validateField(field) {
        if (field.value && field.value.trim() !== '') {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
            removeFieldError(field);
        } else {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            showFieldError(field, 'This field is required');
        }
    }
    
    function showFieldError(field, message) {
        removeFieldError(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function removeFieldError(field) {
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Status change handling
    const statusSelect = document.getElementById('status-select');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Change button color based on status
            if (selectedValue === 'Approved') {
                submitBtn.className = 'btn btn-success btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Approve Request';
            } else if (selectedValue === 'Rejected') {
                submitBtn.className = 'btn btn-danger btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Reject Request';
            } else if (selectedValue === 'Pending') {
                submitBtn.className = 'btn btn-warning btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-clock me-2"></i>Set Pending';
            } else {
                submitBtn.className = 'btn btn-primary btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Request';
            }
        });
    }
    
    // Leave date validation
    const leaveDateField = document.getElementById('leave-date');
    if (leaveDateField) {
        leaveDateField.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                showFieldError(this, 'Leave date cannot be in the past');
            } else {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                removeFieldError(this);
            }
        });
    }
    
    // File upload validation
    const fileField = document.getElementById('attachment-file');
    if (fileField) {
        fileField.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showFieldError(this, 'File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showFieldError(this, 'Please upload a valid file type (PDF, DOC, DOCX, JPG, PNG)');
                    this.value = '';
                    return;
                }
                
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                removeFieldError(this);
            }
        });
    }
    
    // Character counter for reason field
    const reasonField = document.getElementById('reason-textarea');
    if (reasonField) {
        reasonField.addEventListener('input', function() {
            const maxLength = 500; // Adjust as needed
            const currentLength = this.value.length;
            
            let counter = this.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('small');
                counter.className = 'char-counter text-muted mt-1';
                this.parentNode.appendChild(counter);
            }
            
            counter.textContent = `${currentLength}/${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                counter.className = 'char-counter text-warning mt-1';
            } else {
                counter.className = 'char-counter text-muted mt-1';
            }
        });
    }
    
    // Focus first field on load
    const firstField = document.getElementById('student-select');
    if (firstField) {
        firstField.focus();
    }
});
</script>