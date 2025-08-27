<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Edit Party</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
  <?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
      <?php echo e(method_field($method)); ?>

  <?php endif; ?>
  <?php echo csrf_field(); ?>

  <div class="row">
  	<div class="col-md-6">
  		<div class="box box-default">
  			<div class="box-header with-border">
          <h3 class="box-title">Informasi Data</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
  			<?php echo $__env->make('parties.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  		</div>
  	</div>
  </div>

</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>