<div class="form-group required">
    <select class="form-control" name="employee_name" id="employee_name" required>
        <option value="" selected disabled>-- Pilih Warehouse Supervisor --</option>
        <?php $__currentLoopData = $warehouse_officer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option data-spv="<?php echo e($wo->user->id); ?>" value="<?php echo e($wo->user->first_name); ?> <?php echo e($wo->user->last_name); ?>"><?php echo e($wo->user->user_id); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <input type="hidden" name="employee_id" id="select-wh-id">
</div>