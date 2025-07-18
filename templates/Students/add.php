<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Add New Student' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Student Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="studentActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="studentActions">
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Students'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
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
            
            <!-- Instructions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-success text-white d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <h5 class="mb-0">Add New Student</h5>
                    <small class="ms-auto opacity-75">Create a new student profile</small>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 d-flex align-items-center">
                                <i class="fas fa-lightbulb me-3 fs-4"></i>
                                <div>
                                    <strong>Instructions:</strong> Fill out all required fields marked with a red asterisk (*). 
                                    Make sure the matrix number is unique and email address is valid.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                    <i class="fas fa-user-plus me-2"></i>
                    <h5 class="mb-0">Student Information</h5>
                    <small class="ms-auto opacity-75">Enter student details</small>
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
                                    <i class="fas fa-flag me-1 text-success"></i>
                                    Initial Status
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('status', [
                                    'options' => [
                                        'Active' => 'Active',
                                        'Inactive' => 'Inactive'
                                    ],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Select Initial Status --',
                                    'id' => 'student-status',
                                    'default' => 'Active'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Set the initial status for this student
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
                                        '<i class="fas fa-undo me-2"></i>' . __('Reset Form'), 
                                        [
                                            'type' => 'reset',
                                            'class' => 'btn btn-outline-warning btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                    <?= $this->Form->button(
                                        '<i class="fas fa-plus me-2"></i>' . __('Add Student'), 
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-success btn-lg px-5',
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
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Student...';
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
            
            if (selectedValue === 'Active') {
                submitBtn.className = 'btn btn-success btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Active Student';
            } else if (selectedValue === 'Inactive') {
                submitBtn.className = 'btn btn-warning btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Inactive Student';
            } else {
                submitBtn.className = 'btn btn-success btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Student';
            }
        });
    }
    
    // Reset form functionality
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Clear validation classes
            requiredFields.forEach(field => {
                field.classList.remove('is-valid', 'is-invalid');
                removeFieldError(field);
            });
            
            // Reset form validation state
            form.classList.remove('was-validated');
            
            // Focus first field
            const firstField = document.getElementById('student-name');
            if (firstField) {
                setTimeout(() => firstField.focus(), 100);
            }
        });
    }
    
    // Focus first field on load
    const firstField = document.getElementById('student-name');
    if (firstField) {
        firstField.focus();
    }
});
</script>