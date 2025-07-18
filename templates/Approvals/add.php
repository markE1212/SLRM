<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Approval $approval
 * @var int $requestId
 * @var int|null $lecturerId
 * @var \App\Model\Entity\Leaverequest $leaveRequest
 */
?>

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            
            <?= $this->Form->create($approval, [
                'class' => 'needs-validation',
                'type' => 'post',
                'url' => ['controller' => 'Approvals', 'action' => 'add', $requestId]
            ]) ?>
            
            <?= $this->Form->control('request_id', [
                'type' => 'hidden',
                'value' => $requestId
            ]) ?>

            <!-- Leave Request Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                    <i class="fas fa-file-alt me-2"></i>
                    <h5 class="mb-0">Leave Request Details</h5>
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Request ID</label>
                                <div class="fw-bold text-primary fs-5">#<?= h($leaveRequest->request_id) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Current Status</label>
                                <div>
                                    <?php 
                                    $statusClass = 'secondary';
                                    $statusText = h($leaveRequest->status ?? 'Pending');
                                    
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
                                <label class="form-label text-muted small fw-bold">Student Name</label>
                                <div class="fw-semibold">
                                    <?php
                                    // Try different possible ways to get student name
                                    $studentName = '—';
                                    
                                    // Check if leaveRequest has the student data loaded
                                    if (isset($leaveRequest->student) && !empty($leaveRequest->student->name)) {
                                        $studentName = h($leaveRequest->student->name);
                                    } elseif (isset($leaveRequest->student_name) && !empty($leaveRequest->student_name)) {
                                        $studentName = h($leaveRequest->student_name);
                                    } elseif (isset($leaveRequest->applicant_name) && !empty($leaveRequest->applicant_name)) {
                                        $studentName = h($leaveRequest->applicant_name);
                                    } elseif (isset($leaveRequest->employee_name) && !empty($leaveRequest->employee_name)) {
                                        $studentName = h($leaveRequest->employee_name);
                                    } elseif (isset($leaveRequest->name) && !empty($leaveRequest->name)) {
                                        $studentName = h($leaveRequest->name);
                                    } elseif (isset($leaveRequest->user_name) && !empty($leaveRequest->user_name)) {
                                        $studentName = h($leaveRequest->user_name);
                                    } elseif (isset($leaveRequest->full_name) && !empty($leaveRequest->full_name)) {
                                        $studentName = h($leaveRequest->full_name);
                                    }
                                    
                                    // If still not found, show debug info (remove this in production)
                                    if ($studentName === '—') {
                                        // Debug: show available fields (comment out in production)
                                        /*
                                        echo '<small class="text-warning">Debug - Available fields: ';
                                        if (isset($leaveRequest)) {
                                            echo implode(', ', array_keys($leaveRequest->toArray()));
                                        }
                                        echo '</small><br>';
                                        */
                                    }
                                    
                                    echo $studentName;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Date Submitted</label>
                                <div class="fw-semibold">
                                    <?= $leaveRequest->created ? h($leaveRequest->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="form-label text-muted small fw-bold">Reason</label>
                                <div class="border rounded p-3 bg-white">
                                    <?= h($leaveRequest->reason ?? '—') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-success text-white d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <h5 class="mb-0">Process Approval</h5>
                    <small class="ms-auto opacity-75">Choose an action for this request</small>
                </div>
                <div class="card-body p-4">
                    <fieldset>
                        <div class="row g-4">
                            <!-- Lecturer Selection -->
                            <div class="col-md-6">
                                <label for="lecturer-select" class="form-label fw-bold">
                                    <i class="fas fa-user-tie me-1 text-primary"></i>
                                    <?= __('Lecturer') ?> 
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('lecturer_id', [
                                    'options' => $lecturers,
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Choose Lecturer --',
                                    'id' => 'lecturer-select'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Who is processing this request?
                                </div>
                            </div>

                            <!-- Status Selection -->
                            <div class="col-md-6">
                                <label for="status-select" class="form-label fw-bold">
                                    <i class="fas fa-gavel me-1 text-warning"></i>
                                    <?= __('Action') ?> 
                                    <span class="text-danger">*</span>
                                </label>
                                <?= $this->Form->control('status', [
                                    'options' => [
                                        'Approved' => 'Approve', 
                                        'Rejected' => 'Reject'
                                    ],
                                    'label' => false,
                                    'class' => 'form-select form-select-lg',
                                    'required' => true,
                                    'empty' => '-- Choose Action --',
                                    'id' => 'status-select'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    What is your decision?
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label for="remarks-textarea" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-1 text-info"></i>
                                    <?= __('Comments') ?>
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <?= $this->Form->control('remarks', [
                                    'type' => 'textarea',
                                    'rows' => 4,
                                    'label' => false,
                                    'class' => 'form-control form-control-lg',
                                    'style' => 'height: 120px;',
                                    'placeholder' => __('Enter any additional comments, feedback, or reasons for your decision...'),
                                    'id' => 'remarks-textarea'
                                ]) ?>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Add any feedback or notes for the student
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
                                        '<i class="fas fa-arrow-left me-2"></i>' . __('Back to Request'), 
                                        ['controller' => 'Leaverequests', 'action' => 'view', $requestId], 
                                        [
                                            'class' => 'btn btn-outline-secondary btn-lg px-4',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                </div>
                                <div class="d-flex gap-3">
                                    <?= $this->Form->button(
                                        '<i class="fas fa-times me-2"></i>' . __('Cancel'), 
                                        [
                                            'class' => 'btn btn-outline-danger btn-lg px-4',
                                            'type' => 'button',
                                            'onclick' => 'window.history.back();',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                    <?= $this->Form->button(
                                        '<i class="fas fa-check me-2"></i>' . __('Submit Decision'), 
                                        [
                                            'class' => 'btn btn-primary btn-lg px-5',
                                            'type' => 'submit',
                                            'id' => 'submit-btn',
                                            'escapeTitle' => false
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?= $this->Form->end() ?>
            
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
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

.form-select,
.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
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
    // Add form validation feedback
    const form = document.querySelector('.needs-validation');
    const submitBtn = document.getElementById('submit-btn');
    const statusSelect = document.getElementById('status-select');
    const lecturerSelect = document.getElementById('lecturer-select');
    
    // Change submit button text based on status selection
    statusSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        if (selectedValue === 'Approved') {
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Approve';
            submitBtn.className = 'btn btn-success btn-lg px-5';
        } else if (selectedValue === 'Rejected') {
            submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Reject';
            submitBtn.className = 'btn btn-danger btn-lg px-5';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Submit Decision';
            submitBtn.className = 'btn btn-primary btn-lg px-5';
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        }
        form.classList.add('was-validated');
    });
    
    // Add visual feedback for required fields
    [statusSelect, lecturerSelect].forEach(field => {
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
});
</script>