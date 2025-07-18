<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Edit Student' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Student Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="studentActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="studentActions">
                <?= $this->Html->link(
                    '<i class="fas fa-eye me-2"></i>' . __('View Student'), 
                    ['action' => 'view', $student->student_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Students'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <hr class="dropdown-divider">
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash me-2 text-danger"></i>' . __('Delete'),
                    ['action' => 'delete', $student->student_id],
                    [
                        'confirm' => __('Are you sure you want to delete student "{0}"?', $student->name), 
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
            
            <!-- Student Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-user-graduate me-2"></i>
                    <h5 class="mb-0">Student Information</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Student ID</label>
                                <div class="fw-bold text-primary fs-5">#<?= h($student->student_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Name</label>
                                <div class="fw-bold"><?= h($student->name ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Status</label>
                                <div>
                                    <?php 
                                    $statusClass = 'secondary';
                                    $statusText = h($student->status ?? 'Unknown');
                                    
                                    switch(strtolower($statusText)) {
                                        case 'active':
                                            $statusClass = 'success';
                                            break;
                                        case 'inactive':
                                        case 'disabled':
                                            $statusClass = 'warning text-dark';
                                            break;
                                        case 'archived':
                                            $statusClass = 'secondary';
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
                                <label class="form-label text-muted small fw-bold">Matrix Number</label>
                                <div class="fw-semibold">
                                    <?= h($student->matrix_number ?? '—') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Program</label>
                                <div class="fw-semibold">
                                    <?= h($student->program ?? '—') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Created</label>
                                <div class="fw-semibold">
                                    <?= $student->created ? h($student->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Last Modified</label>
                                <div class="fw-semibold">
                                    <?= $student->modified ? h($student->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
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
                    <h5 class="mb-0">Edit Student Details</h5>
                    <small class="ms-auto opacity-75">Modify the student information</small>
                </div>
                <div class="card-body p-4">
                    <?= $this->Form->create($student, [
                        'class' => 'needs-validation',
                        'novalidate' => true
                    ]) ?>
                    
                    <fieldset>
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="student-name" class="form-label fw-bold">
                                    <i class="fas fa-user me-1 text-primary"></i>
                                    Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('name', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'student-name',
                                    'placeholder' => 'Enter full name'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Student's full name as it appears in official records
                                </div>
                            </div>

                            <!-- Matrix Number -->
                            <div class="col-md-6">
                                <label for="matrix-number" class="form-label fw-bold">
                                    <i class="fas fa-id-card me-1 text-primary"></i>
                                    Matrix Number
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('matrix_number', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'matrix-number',
                                    'placeholder' => 'e.g., 2024123456'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Unique student identification number
                                </div>
                            </div>

                            <!-- Program -->
                            <div class="col-md-6">
                                <label for="program" class="form-label fw-bold">
                                    <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                    Program
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('program', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'program',
                                    'placeholder' => 'e.g., Computer Science'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Student's field of study or program
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="student-email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    Email Address
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('email', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'student-email',
                                    'type' => 'email',
                                    'placeholder' => 'student@university.edu'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Valid email address for communication
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="student-phone" class="form-label fw-bold">
                                    <i class="fas fa-phone me-1 text-primary"></i>
                                    Phone Number
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <?= $this->Form->control('phone', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'student-phone',
                                    'placeholder' => '+60123456789'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contact phone number
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="student-status" class="form-label fw-bold">
                                    <i class="fas fa-flag me-1 text-warning"></i>
                                    Status
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('status', [
                                    'options' => [
                                        'Active' => 'Active',
                                        'Inactive' => 'Inactive',
                                        'Disabled' => 'Disabled',
                                        'Archived' => 'Archived'
                                    ],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Select Status --',
                                    'id' => 'student-status'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Update the student's current status
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Changes Summary -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-warning border-0" id="changes-alert" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Changes detected:</strong>
                                </div>
                                <ul id="changes-list" class="mt-2 mb-0"></ul>
                            </div>
                        </div>
                    </div>

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
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye me-2"></i>' . __('View Student'), 
                                        ['action' => 'view', $student->student_id], 
                                        [
                                            'class' => 'btn btn-outline-info btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                    <?= $this->Form->button(
                                        '<i class="fas fa-undo me-2"></i>' . __('Reset Changes'), 
                                        [
                                            'type' => 'reset',
                                            'class' => 'btn btn-outline-warning btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                    <?= $this->Form->button(
                                        '<i class="fas fa-save me-2"></i>' . __('Update Student'), 
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-warning btn-lg px-5',
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
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
}

.form-select:focus,
.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
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

.alert {
    border-radius: 10px;
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

.field-changed {
    background-color: #fff3cd !important;
    border-color: #ffc107 !important;
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
    // Store original values for change detection
    const originalValues = {};
    const form = document.querySelector('.needs-validation');
    const submitBtn = document.getElementById('submit-btn');
    const changesAlert = document.getElementById('changes-alert');
    const changesList = document.getElementById('changes-list');
    
    // Capture original values
    const fields = ['name', 'matrix_number', 'program', 'email', 'phone', 'status'];
    fields.forEach(fieldName => {
        const field = document.getElementById('student-' + fieldName) || document.getElementById(fieldName) || document.getElementById(fieldName.replace('_', '-'));
        if (field) {
            originalValues[fieldName] = field.value;
        }
    });
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating Student...';
        }
        form.classList.add('was-validated');
    });
    
    // Change detection
    function detectChanges() {
        const changes = [];
        let hasChanges = false;
        
        fields.forEach(fieldName => {
            const field = document.getElementById('student-' + fieldName) || document.getElementById(fieldName) || document.getElementById(fieldName.replace('_', '-'));
            if (field && field.value !== originalValues[fieldName]) {
                hasChanges = true;
                changes.push({
                    field: fieldName,
                    label: field.labels ? field.labels[0].textContent.replace('*', '').trim() : fieldName,
                    oldValue: originalValues[fieldName],
                    newValue: field.value
                });
                field.classList.add('field-changed');
            } else if (field) {
                field.classList.remove('field-changed');
            }
        });
        
        if (hasChanges) {
            changesAlert.style.display = 'block';
            changesList.innerHTML = changes.map(change => 
                `<li><strong>${change.label}:</strong> "${change.oldValue}" → "${change.newValue}"</li>`
            ).join('');
            submitBtn.disabled = false;
        } else {
            changesAlert.style.display = 'none';
            submitBtn.disabled = true;
        }
    }
    
    // Add change listeners to all form fields
    fields.forEach(fieldName => {
        const field = document.getElementById('student-' + fieldName) || document.getElementById(fieldName) || document.getElementById(fieldName.replace('_', '-'));
        if (field) {
            field.addEventListener('input', detectChanges);
            field.addEventListener('change', detectChanges);
        }
    });
    
    // Initial change detection
    detectChanges();
    
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
        if (field.type === 'email') {
            if (field.value && !isValidEmail(field.value)) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                showFieldError(field, 'Please enter a valid email address');
            } else if (field.value && field.value.trim() !== '') {
                field.classList.add('is-valid');
                field.classList.remove('is-invalid');
                removeFieldError(field);
            } else {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                showFieldError(field, 'This field is required');
            }
        } else {
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
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Auto-capitalize name field
    const nameField = document.getElementById('student-name');
    if (nameField) {
        nameField.addEventListener('blur', function() {
            // Capitalize each word
            this.value = this.value.replace(/\b\w+/g, function(word) {
                return word.charAt(0).toUpperCase() + word.substr(1).toLowerCase();
            });
            detectChanges(); // Check for changes after formatting
        });
    }
    
    // Matrix number validation
    const matrixField = document.getElementById('matrix-number');
    if (matrixField) {
        matrixField.addEventListener('input', function() {
            // Remove non-alphanumeric characters
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    }
    
    // Phone number formatting
    const phoneField = document.getElementById('student-phone');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            // Allow only numbers, spaces, + and -
            this.value = this.value.replace(/[^0-9\s\+\-]/g, '');
        });
    }
    
    // Status field styling based on selection
    const statusField = document.getElementById('student-status');
    if (statusField) {
        statusField.addEventListener('change', function() {
            const selectedValue = this.value;
            
            switch(selectedValue) {
                case 'Active':
                    submitBtn.className = 'btn btn-success btn-lg px-5';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update to Active';
                    break;
                case 'Inactive':
                    submitBtn.className = 'btn btn-warning btn-lg px-5';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update to Inactive';
                    break;
                case 'Disabled':
                    submitBtn.className = 'btn btn-secondary btn-lg px-5';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update to Disabled';
                    break;
                case 'Archived':
                    submitBtn.className = 'btn btn-dark btn-lg px-5';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update to Archived';
                    break;
                default:
                    submitBtn.className = 'btn btn-warning btn-lg px-5';
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Student';
            }
        });
    }
    
    // Reset form functionality
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Clear validation classes
            requiredFields.forEach(field => {
                field.classList.remove('is-valid', 'is-invalid', 'field-changed');
                removeFieldError(field);
            });
            
            // Reset form validation state
            form.classList.remove('was-validated');
            
            // Hide changes alert
            changesAlert.style.display = 'none';
            
            // Reset button state
            submitBtn.disabled = true;
            submitBtn.className = 'btn btn-warning btn-lg px-5';
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Student';
            
            // Focus first field
            const firstField = document.getElementById('student-name');
            if (firstField) {
                setTimeout(() => firstField.focus(), 100);
            }
        });
    }
    
    // Auto-save warning (optional)
    let autoSaveTimeout;
    fields.forEach(fieldName => {
        const field = document.getElementById('student-' + fieldName) || document.getElementById(fieldName) || document.getElementById(fieldName.replace('_', '-'));
        if (field) {
            field.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(function() {
                    // Could implement auto-save functionality here
                    console.log('Auto-save triggered');
                }, 5000); // 5 seconds after last change
            });
        }
    });
});
</script>