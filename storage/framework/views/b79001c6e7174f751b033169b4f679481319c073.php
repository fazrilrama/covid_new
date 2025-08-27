(function(window,$){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}});
    window.LaravelDataTables = window.LaravelDataTables || {};
    <?php $__currentLoopData = $editors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        var <?php echo e($editor->instance); ?> = window.LaravelDataTables["%1$s-<?php echo e($editor->instance); ?>"] = new $.fn.dataTable.Editor(<?php echo $editor->toJson(); ?>);
        <?php echo $editor->scripts; ?>

        <?php $__currentLoopData = (array) $editor->events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($editor->instance); ?>.on('<?php echo $event['event']; ?>', <?php echo $event['script']; ?>);
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    window.LaravelDataTables["%1$s"] = $("#%1$s").DataTable(%2$s);
})(window,jQuery);
