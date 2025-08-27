<div class="form-group required">
    <label for="warehouse_id" class="col-sm-3 control-label">
        Warehouse
    </label>
    <div class="col-sm-9">
        <select class="form-control" name="warehouse_id" id="warehouse_id" required="required">
            <option value="" disabled selected>-- Pilih Warehouse</option>
            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($wh->id); ?>"><?php echo e($wh->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>