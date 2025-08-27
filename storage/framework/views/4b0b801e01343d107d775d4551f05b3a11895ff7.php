<div class="modal fade" id="modal-an-validation">
    <div class="modal-dialog modal-sm">
        <form action="<?php echo e($url); ?>" method="POST" id="assignto" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Validation</h4>
                </div>
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <textarea class="form-control" name="accepted_note" placeholder="note"></textarea>
                    </div>   
                    <div class="form-group required">
                        <select class="form-control" name="is_accepted" required>
                            <option value="" selected disabled>-- Pilih Validasi --</option>
                            <option value="1">Terima</option>
                            <option value="2">Tolak</option>
                        </select>
                    </div>        
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-flat">Lanjutkan</button>
                </div>
            </div>
        </form>
    </div>
</div>