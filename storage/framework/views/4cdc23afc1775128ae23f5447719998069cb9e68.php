<select name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" class="form-control">
    <?php if($count === 0): ?>
    <option value="">-- Tidak Tersedia --</option>
    <?php else: ?>
    <option value="" selected disabled>-- Pilih --</option>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($r->{$key}); ?>"><?php echo e($r->{$label}); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</select>