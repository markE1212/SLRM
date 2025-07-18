<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Leaverequest $leaverequest
 */
?>
<!-- Header -->
<div class="row text-body-secondary">
    <div class="col-10">
        <h1 class="my-0 page_title"><?= 'Permohonan Cuti Pelajar' ?></h1>
        <h6 class="sub_title"><?= h($system_name) ?></h6>
    </div>
    <div class="col-2 text-end">
        <div class="dropdown mx-3 mt-2">
            <button class="btn p-0 border-0" type="button" id="orederStatistics"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-bars text-primary"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                <li><?= $this->Html->link(__('Edit Permohonan'), ['action' => 'edit', $leaverequest->request_id], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
                <li><?= $this->Form->postLink(
                    __('Padam Permohonan'),
                    ['action' => 'delete', $leaverequest->request_id],
                    [
                        'confirm' => __('Anda pasti ingin memadam permohonan # {0}?', $leaverequest->request_id),
                        'class' => 'dropdown-item',
                        'escapeTitle' => false
                    ]
                ) ?></li>
                <li><hr class="dropdown-divider"></li>
                <li><?= $this->Html->link(__('Senarai Cuti'), ['action' => 'index'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
                <li><?= $this->Html->link(__('Permohonan Baru'), ['action' => 'add'], ['class' => 'dropdown-item', 'escapeTitle' => false]) ?></li>
            </div>
        </div>
    </div>
</div>

<div class="line mb-4"></div>

<style>
.putih {
    background-color: #ffffff;
    color: #000000;
}

.capital {
    text-transform: uppercase;
}
</style>

<div class="row">
    <!-- Main Content -->
    <div class="col-md-9">
        <div class="card rounded-0 mb-3 border-0 shadow putih">
            <div class="card-body">

                <?= $this->Html->image('top.png', ['width' => '100%']) ?>

                <!-- Letter Reference & Date -->
                <div class="table-responsive mt-4 mb-3">
                    <table width="100%">
                        <tr>
                            <td width="70%"></td>
                            <td>No. Rujukan Surat</td>
                            <td>:</td>
                            <td>UiTM/100(<?= h($leaverequest->request_id) ?>)</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Tarikh</td>
                            <td>:</td>
                            <td><?= h(date('d F Y', strtotime($leaverequest->leave_date))) ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Student Address -->
                <?php if ($leaverequest->has('student')): ?>
                    <p>
                        <strong><?= h($leaverequest->student->name) ?></strong><br/>
                        Pelajar, Program Sains Data,<br/>
                        UiTM Cawangan Selangor Kampus Puncak Perdana,<br/>
                        Jalan Pulau Indah Au10/A, Puncak Perdana,<br/>
                        40150 Shah Alam,<br/>
                        <b>Selangor</b>
                    </p>
                <?php else: ?>
                    <p>Pelajar tidak dijumpai.</p>
                <?php endif; ?>

                <br/><br/>

                <!-- Letter Body -->
                <b class="capital">permohonan cuti pelajar</b>
                <br/><br/>

                Dengan hormatnya saya merujuk kepada perkara di atas dan ingin memohon kebenaran untuk bercuti pada tarikh yang dinyatakan di bawah berikutan sebab-sebab tertentu seperti yang akan disertakan dalam permohonan ini.
                <br/><br/>
                Saya sedar bahawa pemohonan ini tertakluk kepada kelulusan pihak berkuasa dan saya akan mematuhi segala prosedur serta garis panduan berkaitan.
                <br/><br/>
                Sekian, terima kasih.
                <br/><br/>
                <b>(<?= $leaverequest->student ? h($leaverequest->student->name) : '—' ?>)</b><br/>
                Pelajar, Program Sains Data<br/>
                UiTM Cawangan Selangor Kampus Puncak Perdana
                <br/><br/>
                SURAT DIJANA OLEH KOMPUTER. TIADA TANDATANGAN DIPERLUKAN.
                <br/><br/>

                <?= $this->Html->image('bottom.png', ['width' => '100%']) ?>
            </div>
        </div>
    </div>

    <!-- Sidebar - Approval Status -->
    <div class="col-md-3">
        <div class="card bg-body-tertiary border-0 shadow mb-3">
            <div class="card-header pb-0">
                <h5 class="card-title">Status Kelulusan</h5>
            </div>
            <div class="card-body pt-0">
                <?php 
                // Get the approval record (hasOne relationship)
                $approval = $leaverequest->approval ?? null;
                ?>

                <?php if ($approval): ?>
                    <?php 
                    // Check status (handle both lowercase and capitalized versions)
                    $status = strtolower($approval->status ?? '');
                    ?>
                    <?php if ($status === 'approved'): ?>
                        <p class="text-center text-success fw-bold fs-5"><?= __('Diluluskan') ?></p>
                    <?php elseif ($status === 'rejected'): ?>
                        <p class="text-center text-danger fw-bold fs-5"><?= __('Tidak Lulus') ?></p>
                    <?php else: ?>
                        <p class="text-center text-warning fw-bold fs-5"><?= __('Belum Diproses') ?></p>
                    <?php endif; ?>

                    <!-- Lecturer Info -->
                    <div class="text-center small text-muted mt-2">
                        <?php if ($approval->has('lecturer') && $approval->lecturer): ?>
                            <?= h($approval->lecturer->name) ?><br/>
                            <?= $approval->approved_at ? h($approval->approved_at->i18nFormat('dd-MMM-yyyy HH:mm')) : '—' ?>
                        <?php else: ?>
                            <?php if ($approval->lecturer_id): ?>
                                Pensyarah ID: <?= h($approval->lecturer_id) ?><br/>
                            <?php endif; ?>
                            <?= $approval->approved_at ? h($approval->approved_at->i18nFormat('dd-MMM-yyyy HH:mm')) : 'Belum diproses' ?>
                        <?php endif; ?>
                    </div>

                    <!-- Remarks if any -->
                    <?php if (!empty($approval->remarks)): ?>
                        <div class="mt-3">
                            <small class="text-muted">Catatan:</small><br/>
                            <small><?= h($approval->remarks) ?></small>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-center text-warning fw-bold fs-5"><?= __('Belum Diproses') ?></p>
                    <div class="text-center small text-muted mt-2">
                        Permohonan belum dikemukakan untuk kelulusan
                    </div>
                <?php endif; ?>

                <br/>
                
                <!-- Download PDF Button - Only show if approved -->
                <?php if ($approval && $status === 'approved'): ?>
                    <?= $this->Html->link(
                        '<i class="fa-solid fa-download me-2"></i>' . __('Muat Turun PDF'),
                        ['action' => 'pdf', $leaverequest->request_id],
                        [
                            'class' => 'btn btn-success w-100',
                            'escapeTitle' => false
                        ]
                    ) ?>
                <?php elseif ($approval && $status === 'rejected'): ?>
                    <div class="alert alert-danger text-center small mb-0">
                        <i class="fa-solid fa-times-circle me-1"></i>
                        Permohonan tidak diluluskan
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center small mb-0">
                        <i class="fa-solid fa-clock me-1"></i>
                        Menunggu kelulusan pensyarah
                    </div>
                <?php endif; ?>

                           </div>
        </div>

        <!-- Request Details Card -->
        <div class="card bg-body-tertiary border-0 shadow mb-3">
            <div class="card-header pb-0">
                <h6 class="card-title">Butiran Permohonan</h6>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><small class="text-muted">ID Permohonan:</small></td>
                        <td><small><strong><?= h($leaverequest->request_id) ?></strong></small></td>
                    </tr>
                    <tr>
                        <td><small class="text-muted">Tarikh Cuti:</small></td>
                        <td><small><?= h($leaverequest->leave_date->i18nFormat('dd-MMM-yyyy')) ?></small></td>
                    </tr>
                    <tr>
                        <td><small class="text-muted">Sebab:</small></td>
                        <td><small><?= h($leaverequest->reason) ?></small></td>
                    </tr>
                    <tr>
                        <td><small class="text-muted">Status:</small></td>
                        <td>
                            <span class="badge bg-<?= strtolower($leaverequest->status ?? '') === 'approved' ? 'success' : (strtolower($leaverequest->status ?? '') === 'rejected' ? 'danger' : 'warning') ?> badge-sm">
                                <?= h(ucfirst($leaverequest->status ?? 'Pending')) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><small class="text-muted">Tarikh Mohon:</small></td>
                        <td><small><?= h($leaverequest->created->i18nFormat('dd-MMM-yyyy HH:mm')) ?></small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
