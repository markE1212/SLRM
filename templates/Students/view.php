<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'View Student' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Student Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="studentActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="studentActions">
                <?= $this->Html->link(
                    '<i class="fas fa-edit me-2"></i>' . __('Edit Student'), 
                    ['action' => 'edit', $student->student_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-list me-2"></i>' . __('All Students'), 
                    ['action' => 'index'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-user-plus me-2"></i>' . __('Add New Student'), 
                    ['action' => 'add'], 
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
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                    <i class="fas fa-user-graduate me-2"></i>
                    <h5 class="mb-0"><?= h($student->name) ?></h5>
                    <div class="ms-auto">
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
                                <label class="form-label text-muted small fw-bold">Matrix Number</label>
                                <div class="fw-bold"><?= h($student->matrix_number ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Program</label>
                                <div class="fw-bold"><?= h($student->program ?? '—') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                    <i class="fas fa-address-card me-2"></i>
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    Email Address
                                </label>
                                <div class="fw-semibold">
                                    <?php if ($student->email): ?>
                                        <a href="mailto:<?= h($student->email) ?>" class="text-decoration-none">
                                            <?= h($student->email) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">
                                    <i class="fas fa-phone me-1 text-primary"></i>
                                    Phone Number
                                </label>
                                <div class="fw-semibold">
                                    <?php if ($student->phone): ?>
                                        <a href="tel:<?= h($student->phone) ?>" class="text-decoration-none">
                                            <?= h($student->phone) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <?= $student->created ? h($student->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
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
                                    <?= $student->modified ? h($student->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-warning text-dark d-flex align-items-center">
                    <i class="fas fa-bolt me-2"></i>
                    <h5 class="mb-0">Quick Actions</h5>
                    <small class="ms-auto opacity-75">Manage this student</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-edit me-2"></i>' . __('Edit Student'), 
                                ['action' => 'edit', $student->student_id], 
                                [
                                    'class' => 'btn btn-warning btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-list me-2"></i>' . __('All Students'), 
                                ['action' => 'index'], 
                                [
                                    'class' => 'btn btn-outline-primary btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Html->link(
                                '<i class="fas fa-user-plus me-2"></i>' . __('Add New Student'), 
                                ['action' => 'add'], 
                                [
                                    'class' => 'btn btn-outline-success btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->postLink(
                                '<i class="fas fa-trash me-2"></i>' . __('Delete Student'),
                                ['action' => 'delete', $student->student_id],
                                [
                                    'confirm' => __('Are you sure you want to delete student "{0}"?', $student->name), 
                                    'class' => 'btn btn-outline-danger btn-lg w-100 d-flex align-items-center justify-content-center',
                                    'escapeTitle' => false
                                ]
                            ) ?>
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

/* Link styling */
a[href^="mailto:"], a[href^="tel:"] {
    color: #0d6efd;
    font-weight: 500;
}

a[href^="mailto:"]:hover, a[href^="tel:"]:hover {
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
    .bg-gradient-secondary {
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
    
    // Add copy functionality for contact information
    const emailElement = document.querySelector('a[href^="mailto:"]');
    const phoneElement = document.querySelector('a[href^="tel:"]');
    
    if (emailElement) {
        emailElement.addEventListener('click', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                copyToClipboard(this.textContent, 'Email copied to clipboard!');
            }
        });
        
        emailElement.title = 'Click to email, Ctrl+Click to copy';
    }
    
    if (phoneElement) {
        phoneElement.addEventListener('click', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                copyToClipboard(this.textContent, 'Phone number copied to clipboard!');
            }
        });
        
        phoneElement.title = 'Click to call, Ctrl+Click to copy';
    }
    
    // Copy to clipboard function
    function copyToClipboard(text, message) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                showToast(message);
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast(message);
        }
    }
    
    // Show toast notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success alert-dismissible fade show';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
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
    printBtn.title = 'Print Student Details';
    printBtn.style.width = '60px';
    printBtn.style.height = '60px';
    printBtn.style.zIndex = '999';
    
    printBtn.addEventListener('click', function() {
        window.print();
    });
    
    document.body.appendChild(printBtn);
});
</script>