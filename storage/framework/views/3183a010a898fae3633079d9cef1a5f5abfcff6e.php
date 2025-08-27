<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Tally Sheet</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>#:</th>
	                <th>Origin:</th>
	                <th>Destination:</th>
	                <th>ETD:</th>
	                <th>ETA:</th>
	                <th>Updated At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->id); ?></td>
	                <td><?php echo e($item->code); ?></td>
	                <td><?php echo e($item->origin); ?></td>
	                <td><?php echo e($item->destination); ?></td>
	                <td><?php echo e($item->etd); ?></td>
	                <td><?php echo e($item->eta); ?></td>
	                <td><?php echo e($item->updated_at); ?></td>
	                <td>
	                	<div class="btn-group" role="group">
		                   	<a href="<?php echo e(url('tally_sheet/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="Show">
		                   		<i class="fa fa-eye"></i>
		                   	</a>
		                </div>
	                </td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>