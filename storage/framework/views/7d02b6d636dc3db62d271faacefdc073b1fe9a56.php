window.LaravelDataTables = window.LaravelDataTables || {};
window.LaravelDataTables.options = %2$s
window.LaravelDataTables.editors = [];
<?php $__currentLoopData = $editors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
window.LaravelDataTables.editors["<?php echo e($editor->instance); ?>"] = <?php echo $editor->toJson(); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
