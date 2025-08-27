<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Stock Opname</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>SKU:</th>
					<th>Item:</th>
					<th>Stock Awal #:</th>
					<th>Allocated:</th>
					<th>Available:</th>
					<th>Actual Qty:</th>
					<th>Difference:</th>
					<th>Description:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td></td>
	                <td><?php echo e($collection->sku); ?></td>
	                <td><?php echo e($collection->name); ?></td>
	                <td><?php echo e($collection->on_hand); ?></td>
	                <td><?php echo e($collection->allocated); ?></td>
	                <td><?php echo e($collection->available); ?></td>
	                <td><?php echo e($collection->actual_qty); ?></td>
	                <td><?php echo e($collection->difference); ?></td>
	                <td><?php echo e($collection->description); ?></td>
                    <td>
                        <a href="<?php echo e(route('opnames.edit', ['item' => $collection->id ])); ?>" class="btn btn-large btn-block btn-primary btn-flat">
                            <i class="fa fa-edit"></i> Entry
                        </a>
                    </td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>