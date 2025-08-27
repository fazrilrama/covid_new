<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Available Storage List</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Warehouse:</th>
	                <th>Storage Code:</th>
	                <th>Komoditas:</th>
	                <th>Volume:</th>
	                <th>Weight:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e(empty($item->warehouse) ?'': $item->warehouse->code); ?></td>
	                <td><?php echo e($item->code); ?></td>
	                <td><?php echo e(empty($item->commodity) ?'': $item->commodity->name); ?></td>
	                <td><?php echo e($item->volume); ?></td>
	                <td><?php echo e($item->weight); ?></td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="<?php echo e(url('storages/'.$item->id.'/entries')); ?>" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-eye"></i>
		                      	</a>
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