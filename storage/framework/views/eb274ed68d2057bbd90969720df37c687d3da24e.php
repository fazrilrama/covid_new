<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Edit Contract - <?php echo e($contract->number_contract); ?></h1>
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
        <h3 class="box-title">Daftar Rented Warehouse</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
			<div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover no-margin" width="100%">
              <!-- <thead>
                <tr>
                    <th>Nama Warehouse:</th>
                    <th></th>
                </tr>
              </thead> -->
              <tbody>
              <?php if($warehouses): ?>
                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                          <input type="checkbox" name="warehouses[]" value="<?php echo e($warehouse->id); ?>" <?php echo e(!$warehouse->selected ?'': 'checked'); ?>>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($warehouse->code); ?> - <?php echo e($warehouse->name); ?><br/>
                          <?php echo e(@$warehouse->city->name); ?>, <?php echo e(@$warehouse->region->name); ?>

                        </td>
                        <td>
                          <input type="text" placeholder="Space" name="warehouses_space[<?php echo e($warehouse->id); ?>]" value="<?php echo e($warehouse->space); ?>">M2
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-footer">
      <button type="submit" class="btn btn-info pull-right">Simpan</button>
      </div>
		</div>
	</div>
</div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>