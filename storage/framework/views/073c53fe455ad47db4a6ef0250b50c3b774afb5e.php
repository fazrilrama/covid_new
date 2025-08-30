<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Edit Gudang - <?php echo e($warehouse->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
			<?php echo $__env->make('warehouses.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
        <h3 class="box-title">Additional Data</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <form action="<?php echo e($action_add); ?>" method="POST" class="form-horizontal">
        <?php echo csrf_field(); ?>
        <div class="box-body">
          <?php $__currentLoopData = $warehouse_add; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="form-check">
            <input 
              class="form-check-input" 
              type="checkbox" 
              value="<?php echo e($wa['id']); ?>" 
              id="warehouse_add_<?php echo e($wa['id']); ?>" 
              name="warehouse_add[]"
              <?php echo e(in_array($wa['id'], $selected) ? 'checked' : ''); ?>

            >
            <label class="form-check-label" for="warehouse_add_<?php echo e($wa['id']); ?>">
              <?php echo e($wa['name']); ?>

            </label>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-info pull-right">Simpan</button>
        </div>
      </form>
		</div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>