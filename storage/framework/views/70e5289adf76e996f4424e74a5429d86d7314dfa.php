<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <p>
        Anda tidak mempunyai project. Silahkan hubungi Admin untuk informasi lebih lanjut.
        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            <?php echo e(__('Logout')); ?>

        </a>

        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>
    </p>
</body>
</html>