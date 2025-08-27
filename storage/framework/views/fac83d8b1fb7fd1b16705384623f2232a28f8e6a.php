<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Internal Movement
    <?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
        <a href="<?php echo e(route('stock_internal_movements.create')); ?>" type="button" class="btn btn-success" title="Create">
            <i class="fa fa-plus"></i> Tambah
        </a>
    <?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Code:</th>
	                <th>Storage Origin:</th>
                    <th>Storage Destination:</th>
	                <th>Item:</th>
	                <th>Ref Code:</th>
	                <th>Jumlah:</th>
	                <th>Status:</th>
	                <th>Action</th>
	            </tr>
	        </thead>
	        
	        <tbody>
            <?php $__currentLoopData = $internal_movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
				<td><?php echo e($internal_movement->code); ?></td>
				<td>
					<table>
						<?php $__currentLoopData = $internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($detail->origin_storage ? $detail->origin_storage->code : ''); ?></td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>
				</td>
				<td>
					<table>
						<?php $__currentLoopData = $internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($detail->dest_storage ? $detail->dest_storage->code : ''); ?></td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>
				</td>
				<td>
					<table>
						<?php $__currentLoopData = $internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($detail->item ? $detail->item->sku : ''); ?> - <?php echo e($detail->item ? $detail->item->name : ''); ?></td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>
				</td>
				<td>
					<table>
						<?php $__currentLoopData = $internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($detail->ref_code); ?> </td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>
				</td>
				<td>
					<table>
						<?php $__currentLoopData = $internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($detail->movement_qty); ?> <?php echo e($detail->uom ? $detail->uom->name : ''); ?> </td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>
				</td>
				<td>
					<?php echo e($internal_movement->status); ?>

				</td>
				<td>
					<div class="btn-group" role="group">
						<a href="<?php echo e(url('stock_internal_movements/' . $internal_movement->id)); ?>" type="button" class="btn btn-primary" title="View">
							<i class="fa fa-eye"></i>
						</a>
					</div>
					<?php if($internal_movement->status == 'Processed'): ?>
					<div class="btn-group" role="group">
						<a href="<?php echo e(url('stock_internal_movements/' . $internal_movement->id .'/edit')); ?>" type="button" class="btn btn-warning" title="Edit">
							<i class="fa fa-pencil"></i>
						</a>
					</div>
					<div class="btn-group" role="group">
						<form action="<?php echo e(url('stock_internal_movements', [$internal_movement->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
							<input type="hidden" name="_method" value="DELETE">
							<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
							<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
						</form>
					</div>
					<?php endif; ?>
				</td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>