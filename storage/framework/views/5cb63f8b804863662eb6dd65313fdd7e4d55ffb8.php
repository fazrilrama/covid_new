<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Table Konversi Satuan Unit
    	<a href="<?php echo e(url('uom_conversions/create')); ?>" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>From:</th>
	                <th>To:</th>
	                <th>Multiplier:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->from_uom['name']); ?></td>
	                <td><?php echo e($item->to_uom['name']); ?></td>
	                <td><?php echo e($item->multiplier); ?></td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="<?php echo e(route('uom_conversions.edit',$item->id)); ?>" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
		                    <div class="btn-group" role="group">
	                      		<form action="<?php echo e(url('uom_conversions', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
		                      		<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
		                      	</form>
		                    </div>
	                    </div>
	                </td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>