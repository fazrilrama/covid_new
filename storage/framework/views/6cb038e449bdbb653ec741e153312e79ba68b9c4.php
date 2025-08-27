<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Edit Project - <?php echo e($project->name); ?></h1>
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
			<?php echo $__env->make('projects.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
  <?php if($project_storage->count() > 0): ?>
    <div class="col-md-6">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Storage List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>  
        <div class="table-responsive">
          <table class="data-table table" width="100%">
              <thead>
                <tr>
                    <th>Code</th>
                    <th>Warehouse</th>
                    <th>Cabang/Subcabang</th>
                    <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <?php $__currentLoopData = $project_storage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                      <td><?php echo e($ps->storage->code); ?></td>
                      <td><?php echo e($ps->storage->warehouse->name); ?></td>
                      <td><?php echo e($ps->storage->warehouse->branch->name); ?></td>
                      <td>
                        <div class="btn-group" role="group">
                          <form action="<?php echo e(route('delete_project_storage')); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                            <input type="hidden" name="storage_project_id" value="<?php echo e($ps->id); ?>">
                            <input type="hidden" name="project_id" value="<?php echo e($ps->project_id); ?>">
                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                          </form>
                        </div>
                      </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
          </table>
        </div>
        
      </div>
    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>