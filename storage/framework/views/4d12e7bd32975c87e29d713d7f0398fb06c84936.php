<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Storage Entries List #<?php echo e($storage->code); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Item SKU:</th>
	                <th>Qty:</th>
	                <th>Uom:</th>
	                <th>Weight:</th>
	                <th>Volume:</th>
	                <th>Updated At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $storage->stock_entry_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock_entry_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($stock_entry_detail->item->sku); ?></td>
	                <td><?php echo e($stock_entry_detail->qty); ?></td>
	                <td><?php echo e($stock_entry_detail->uom->name); ?></td>
	                <td><?php echo e($stock_entry_detail->weight); ?></td>
	                <td><?php echo e($stock_entry_detail->volume); ?></td>
	                <td><?php echo e($stock_entry_detail->updated_at); ?></td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>