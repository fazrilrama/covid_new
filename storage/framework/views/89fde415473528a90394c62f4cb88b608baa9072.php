<?php $__env->startSection('adminlte_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/iCheck/square/blue.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/css/auth.css')); ?>">
<style type="text/css">
.field-icon {
  float: right;
  margin-right: 8px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>
<?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'login-page'); ?>

<?php $__env->startSection('body'); ?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>"><img src=<?php echo e(asset('logo.png')); ?> class="img-responsive"></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?php echo e(trans('adminlte::adminlte.login_message')); ?></p>

        <?php if(session('message')): ?>
        <div class="alert alert-info">
            <?php echo e(session('message')); ?>

        </div>
        <?php endif; ?>

        <?php if($errors->has('locked_account')): ?>
        <div class="alert alert-danger">
            <?php echo e($errors->first('locked_account')); ?>

        </div>
        <?php endif; ?>
        <form action="<?php echo e(url(config('adminlte.login_url', 'login'))); ?>" method="post">
            <?php echo csrf_field(); ?>


            <div class="form-group has-feedback <?php echo e($errors->has('user_id') ? 'has-error' : ''); ?>">
                <input type="user_id" name="user_id" class="form-control" value="<?php echo e(old('user_id')); ?>"
                placeholder="User ID">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <?php if($errors->has('user_id')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('user_id')); ?></strong>
                </span>
                <?php endif; ?>
            </div>
            <div class="form-group has-feedback <?php echo e($errors->has('password') ? 'has-error' : ''); ?>">
                <input type="password" name="password" class="form-control"
                placeholder="<?php echo e(trans('adminlte::adminlte.password')); ?>" id="password_change">
                <!-- <span class="glyphicon glyphicon-lock form-control-feedback" onclick="myFunction()"></span> -->
                <span class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunction()"></span>
                <?php if($errors->has('password')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('password')); ?></strong>
                </span>
                <?php endif; ?>
            </div>

            <p style="text-align:center;"><?php echo captcha_img('flat'); ?></p>
            <p style="text-align:center;"><input type="text" name="captcha" placeholder="Isi Captcha"></p>
            <?php if($errors->has('captcha')): ?>
            <span class="help-block text-center">
                <strong style="color:red;"><?php echo e($errors->first('captcha')); ?></strong>
            </span>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> <?php echo e(trans('adminlte::adminlte.remember_me')); ?>

                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit"
                    class="btn btn-primary btn-block btn-flat"><?php echo e(trans('adminlte::adminlte.sign_in')); ?></button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <div class="auth-links">
            <a href="<?php echo e(url(config('adminlte.password_reset_url', 'password/reset'))); ?>"
            class="text-center"
            ><?php echo e(trans('adminlte::adminlte.i_forgot_my_password')); ?></a>
            <br>
            <a href="<?php echo e(url('unlock_account')); ?>" class="text-center">Unlock Account</a>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>© 2020 Powered By <a href="https://www.bgrlogistics.id/">BGR ACCESS</a>.</strong> All rights
            reserved.
            </div>
        </div>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('adminlte_js'); ?>
<script src="<?php echo e(asset('vendor/adminlte/plugins/iCheck/icheck.min.js')); ?>"></script>
<script>
    function myFunction() {
        var x = document.getElementById("password_change");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
    });
</script>
<?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>