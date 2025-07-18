<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lecturer $lecturer
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Edit Lecturer' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Leave Request Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="lecturerActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="lecturerActions">
                <?= $this->Html->link(
                    '<i class="fas fa-eye me-2"></i>' . __('View Lecturer'), 
                    ['action' => 'view', $lecturer->lecturer_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Lecturers'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <hr class="dropdown-divider">
                <?php if ($lecturer->status !== 'Archived'): ?>
                    <?= $this->Form->postLink(
                        '<i class="fas fa-archive me-2 text-warning"></i>' . __('Archive'),
                        ['action' => 'archived', $lecturer->lecturer_id],
                        [
                            'confirm' => __('Are you sure you want to archive lecturer "{0}"?', $lecturer->name), 
                            'class' => 'dropdown-item text-warning', 
                            'escapeTitle' => false
                        ]
                    ) ?>
                <?php endif; ?>
                <?= $this->Form->postLink(
                    '<i class="fas fa-trash me-2 text-danger"></i>' . __('Delete'),
                    ['action' => 'delete', $lecturer->lecturer_id],
                    [
                        'confirm' => __('Are you sure you want to delete lecturer "{0}"?', $lecturer->name), 
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
            
            <!-- Lecturer Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-user-tie me-2"></i>
                    <h5 class="mb-0">Lecturer Information</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Lecturer ID</label>
                                <div class="fw-bold text-primary fs-5">#<?= h($lecturer->lecturer_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Name</label>
                                <div class="fw-bold"><?= h($lecturer->name ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Status</label>
                                <div>
                                    <?php 
                                    $statusClass = 'secondary';
                                    $statusText = h($lecturer->status ?? 'Unknown');
                                    
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
                                <label class="form-label text-muted small fw-bold">Department</label>
                                <div class="fw-semibold">
                                    <?= h($lecturer->department ?? '—') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Email</label>
                                <div class="fw-semibold">
                                    <?= h($lecturer->email ?? '—') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Created</label>
                                <div class="fw-semibold">
                                    <?= $lecturer->created ? h($lecturer->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Last Modified</label>
                                <div class="fw-semibold">
                                    <?= $lecturer->modified ? h($lecturer->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
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
                    <h5 class="mb-0">Edit Lecturer Details</h5>
                    <small class="ms-auto opacity-75">Modify the lecturer information</small>
                </div>
                <div class="card-body p-4">
                    <?= $this->Form->create($lecturer, [
                        'class' => 'needs-validation',
                        'novalidate' => true
                    ]) ?>
                    
                    <fieldset>
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="lecturer-name" class="form-label fw-bold">
                                    <i class="fas fa-user me-1 text-primary"></i>
                                    Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('name', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'lecturer-name',
                                    'placeholder' => 'Enter full name'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Lecturer's full name as it appears in records
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6">
                                <label for="lecturer-department" class="form-label fw-bold">
                                    <i class="fas fa-building me-1 text-primary"></i>
                                    Department
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('department', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'lecturer-department',
                                    'placeholder' => 'Enter department name'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Academic department or faculty
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="lecturer-email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    Email Address
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('email', [
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'required' => true,
                                    'id' => 'lecturer-email',
                                    'type' => 'email',
                                    'placeholder' => 'lecturer@university.edu'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Official institutional email address
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="lecturer-status" class="form-label fw-bold">
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
                                    'id' => 'lecturer-status'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Current employment status
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
                                        '<i class="fas fa-save me-2"></i>' . __('Update Lecturer'), 
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

/* Status-specific styling */
.form-select[id="lecturer-status"] option[value="Active"] {
    color: #28a745;
}

.form-select[id="lecturer-status"] option[value="Inactive"],
.form-select[id="lecturer-status"] option[value="Disabled"] {
    color: #ffc107;
}

.form-select[id="lecturer-status"] option[value="Archived"] {
    color: #6c757d;
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
            if (this.value && this.value.trim() !== '') {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
        
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
                if (this.value && this.value.trim() !== '') {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            }
        });
    });
    
    // Email validation
    const emailField = document.getElementById('lecturer-email');
    if (emailField) {
        emailField.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                
                // Add custom error message
                let errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    this.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = 'Please enter a valid email address';
            } else if (email) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                
                // Remove custom error message
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Status change handling
    const statusSelect = document.getElementById('lecturer-status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Change button color based on status
            if (selectedValue === 'Active') {
                submitBtn.className = 'btn btn-success btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Activate Lecturer';
            } else if (selectedValue === 'Archived') {
                submitBtn.className = 'btn btn-secondary btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-archive me-2"></i>Archive Lecturer';
            } else if (selectedValue === 'Disabled' || selectedValue === 'Inactive') {
                submitBtn.className = 'btn btn-warning btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-pause me-2"></i>Update Status';
            } else {
                submitBtn.className = 'btn btn-primary btn-lg px-5';
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Lecturer';
            }
        });
    }
    
    // Department suggestions (basic implementation)
    const departmentField = document.getElementById('lecturer-department');
    if (departmentField) {
        const commonDepartments = [
            'Computer Science',
            'Information Technology',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'English',
            'Business Administration',
            'Engineering',
            'Psychology'
        ];
        
        departmentField.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            if (value.length > 1) {
                const suggestions = commonDepartments.filter(dept => 
                    dept.toLowerCase().includes(value)
                );
                
                // Remove existing datalist
                const existingDatalist = document.getElementById('department-suggestions');
                if (existingDatalist) {
                    existingDatalist.remove();
                }
                
                if (suggestions.length > 0) {
                    const datalist = document.createElement('datalist');
                    datalist.id = 'department-suggestions';
                    
                    suggestions.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion;
                        datalist.appendChild(option);
                    });
                    
                    document.body.appendChild(datalist);
                    this.setAttribute('list', 'department-suggestions');
                }
            }
        });
    }
    
    // Character counters for text fields
    const textFields = document.querySelectorAll('input[type="text"], input[type="email"]');
    textFields.forEach(field => {
        field.addEventListener('input', function() {
            const maxLength = this.getAttribute('maxlength');
            if (maxLength) {
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;
                
                let counter = this.parentNode.querySelector('.char-counter');
                if (!counter) {
                    counter = document.createElement('small');
                    counter.className = 'char-counter text-muted';
                    this.parentNode.appendChild(counter);
                }
                
                counter.textContent = `${currentLength}/${maxLength} characters`;
                
                if (remaining < 10) {
                    counter.className = 'char-counter text-warning';
                } else {
                    counter.className = 'char-counter text-muted';
                }
            }
        });
    });
    
    // Auto-capitalize name field
    const nameField = document.getElementById('lecturer-name');
    if (nameField) {
        nameField.addEventListener('blur', function() {
            // Capitalize each word
            this.value = this.value.replace(/\b\w+/g, function(word) {
                return word.charAt(0).toUpperCase() + word.substr(1).toLowerCase();
            });
        });
    }
    
    // Focus first field on load
    const firstField = document.querySelector('input:not([type="hidden"])');
    if (firstField) {
        firstField.focus();
    }
});
</script>