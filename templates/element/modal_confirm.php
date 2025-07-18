<!-- templates/element/modal_confirm.php -->
<div class="modal fade" id="bootstrapModal" tabindex="-1" role="dialog" aria-labelledby="bootstrapModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sahkan Tindakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fa-regular fa-circle-xmark fa-6x text-danger mb-3 d-block mx-auto"></i>
                <p id="confirmMessage">Adakah anda pasti?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="ok" class="btn btn-primary">OK</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal JS -->
<?= $this->Html->scriptBlock("
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('bootstrapModal');
        let okBtn = document.getElementById('ok');

        if (modal && okBtn) {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const url = button.getAttribute('data-url');
                okBtn.href = url;
            });
        }
    });
", ['block' => 'scriptBottom']) ?>