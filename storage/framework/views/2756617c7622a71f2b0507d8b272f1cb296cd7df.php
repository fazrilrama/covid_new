<div class="btn-group special" role="group" style="box-sizing:border-box;">
    <a href="<?php echo e($add_user); ?>" type="button" class="btn btn-warning" title="Add User">
        <i class="fa fa-user"></i>
    </a>
    <a href="<?php echo e($edit_url); ?>" type="button" class="btn btn-primary" title="Edit">
    <i class="fa fa-pencil"></i>
    </a>
    <?php echo Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]); ?>

    <button type="submit" class="btn btn-danger button-right" title="Delete"><i class="fa fa-trash"></i></button>
    <?php echo Form::close(); ?>

</div>