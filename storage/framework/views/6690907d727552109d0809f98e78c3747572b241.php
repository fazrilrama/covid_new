<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
  <div class="container">
      <ul class="progressbar">
          <li class="active"><?php echo e(__('lang.create')); ?> <?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?></li>
          <li>Tambah Item</li>
          <li>Complete</li>
      </ul>
  </div>
  <h1><?php echo e(__('lang.create')); ?> <?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-header with-border">
        <h3 class="box-title"><?php echo e(__('lang.information_data')); ?></h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
			<?php echo $__env->make('stock_transports.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			<?php echo $__env->make('form_confirmation', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>