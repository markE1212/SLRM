<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lecturer $lecturer
 */
?>

<!--Header-->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= isset($title) ? h($title) : 'Lecturer Details' ?></h1>
        <h6 class="sub_title text-body-secondary"><?= isset($system_name) ? h($system_name) : 'Leave Request Management System' ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="lecturerActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical text-primary fs-4"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="lecturerActions">
                <?= $this->Html->link(
                    '<i class="fas fa-edit me-2"></i>' . __('Edit Lecturer'), 
                    ['action' => 'edit', $lecturer->lecturer_id], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <?= $this->Html->link(
                    '<i class="fas fa-plus me-2"></i>' . __('Add New Lecturer'), 
                    ['action' => 'add'], 
                    ['class' => 'dropdown-item', 'escapeTitle' => false]
                ) ?>
                <hr class="dropdown-divider">
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
        <div class="col-lg-9">
            
            <!-- Lecturer Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="lecturer-avatar me-3">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0"><?= h($lecturer->name) ?></h4>
                            <small class="opacity-75"><?= h($lecturer->department) ?></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <?php 
                        $statusClass = 'secondary';
                        $statusIcon = 'fa-question';
                        $statusText = h($lecturer->status ?? 'Unknown');
                        
                        switch(strtolower($statusText)) {
                            case 'active':
                                $statusClass = 'success';
                                $statusIcon = 'fa-check-circle';
                                break;
                            case 'inactive':
                            case 'disabled':
                                $statusClass = 'warning';
                                $statusIcon = 'fa-pause-circle';
                                break;
                            case 'archived':
                                $statusClass = 'secondary';
                                $statusIcon = 'fa-archive';
                                break;
                        }
                        ?>
                        <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2">
                            <i class="fas <?= $statusIcon ?> me-1"></i>
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detailed Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    <h5 class="mb-0">Lecturer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Basic Information
                                </h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label class="info-label">Full Name</label>
                                        <div class="info-value"><?= h($lecturer->name) ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Lecturer ID</label>
                                        <div class="info-value">
                                            <span class="badge bg-primary-soft">#<?= $this->Number->format($lecturer->lecturer_id) ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Department</label>
                                        <div class="info-value">
                                            <i class="fas fa-building me-1 text-muted"></i>
                                            <?= h($lecturer->department) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-address-book me-2 text-primary"></i>
                                    Contact Information
                                </h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label class="info-label">Email Address</label>
                                        <div class="info-value">
                                            <i class="fas fa-envelope me-1 text-muted"></i>
                                            <a href="mailto:<?= h($lecturer->email) ?>" class="text-decoration-none">
                                                <?= h($lecturer->email) ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Status</label>
                                        <div class="info-value">
                                            <span class="badge bg-<?= $statusClass ?> px-3 py-2">
                                                <i class="fas <?= $statusIcon ?> me-1"></i>
                                                <?= $statusText ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="col-12">
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-database me-2 text-primary"></i>
                                    System Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <label class="info-label">Date Created</label>
                                            <div class="info-value">
                                                <i class="fas fa-calendar-plus me-1 text-muted"></i>
                                                <?= $lecturer->created ? h($lecturer->created->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <label class="info-label">Last Modified</label>
                                            <div class="info-value">
                                                <i class="fas fa-calendar-edit me-1 text-muted"></i>
                                                <?= $lecturer->modified ? h($lecturer->modified->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                                            </div>
                                        </div>
                                        <br><br><br><br><br>