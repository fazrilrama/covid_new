
<?php if(!empty($additionalMessage)): ?>
<div class="alert alert-<?php echo e($additionalError ? 'danger' : 'info'); ?>">
    <?php echo e($additionalMessage); ?>

</div>
<?php endif; ?>