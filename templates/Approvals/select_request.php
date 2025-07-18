<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface|\App\Model\Entity\Leaverequest[] $pendingRequests
 */
$this->set('title', 'Select Leave Request for Approval');
?>
<div class="card bg-body-tertiary border-0 shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Pending Leave Requests</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($pendingRequests)) : ?>
            <div class="list-group">
                <?php foreach ($pendingRequests as $request): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center p-3 mb-2 bg-light rounded">
                        <div>
                            <h6 class="mb-2 text-dark"><strong>ID:</strong> <?= h($request->request_id) ?></h6>
                            <small class="text-muted">
                                <strong>Student:</strong> <?= $request->has('student') ? h($request->student->name) : '—' ?><br>
                                <strong>Reason:</strong> <?= h($request->reason ?? '—') ?>
                            </small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <?= $this->Html->link(
    'Approve/Reject',
    ['action' => 'add', $request->request_id], 
    ['class' => 'btn btn-primary btn-sm ms-2']
) ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No pending requests found.</p>
        <?php endif; ?>

        <div class="mt-4">
            <!-- Back Button -->
            <?= $this->Html->link(__('Back to Approvals'), ['controller' => 'Approvals', 'action' => 'index'], ['class' => 'btn btn-outline-secondary btn-lg w-100']) ?>
        </div>
    </div>
</div>
