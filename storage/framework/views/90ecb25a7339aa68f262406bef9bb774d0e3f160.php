<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Edit Project</h1>
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
			<?php echo $__env->make('projects.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
  <div class="col-md-6">
    <div class="box box-default">
      <!--<div class="box-header with-border">
        <h3 class="box-title">Informasi Jenis</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover no-margin" width="100%">
              <tbody>
              <?php $__currentLoopData = $party_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                      <td><input type="checkbox" name="party_types[]" value="<?php echo e($party_type->id); ?>" <?php if($party->party_types->contains($party_type->id)): ?><?php echo e('checked'); ?><?php endif; ?>>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($party_type->name); ?>

                      </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
        </div>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      <button type="submit" class="btn btn-info pull-right">Simpan</button>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
</div>

</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>