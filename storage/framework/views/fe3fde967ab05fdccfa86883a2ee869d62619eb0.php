<div class="modal fade" id="modal-assignto">
    <div class="modal-dialog modal-sm">
        <form action="<?php echo e($url); ?>" method="POST" id="assignto" role="form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">ASSIGN TO</h4>
                </div>
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <p align="center">
                        Harap memilih WH Supervisor untuk di assign
                    </p>
                    <?php if(isset($advanceNotice) && $advanceNotice->type == 'outbound'): ?>
                    <div class="form-group required">
                        <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                            <option value="" selected disabled>-- Pilih Warehouse --</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($warehouse->id == $advanceNotice->warehouse_id): ?>
                            <option value="<?php echo e($warehouse->id); ?>" selected><?php echo e($warehouse->name); ?></option>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php else: ?>
                    <div class="form-group required">
                        <select class="form-control" name="warehouse_id" id="warehouse_id" required>
                            <option value="" selected disabled>-- Pilih Warehouse --</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($warehouse->id); ?>"><?php echo e($warehouse->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>  
                    <?php endif; ?>
                    <div id="select_warehouse_officer"></div>                  
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-flat">Lanjutkan</button>
                </div>
            </div>
        </form>
    </div>
</div>