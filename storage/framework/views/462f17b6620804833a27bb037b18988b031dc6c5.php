<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1><?php if($type=='inbound'): ?> Putaway <?php else: ?> Picking Plan <?php endif; ?> List
	<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
		<a href="<?php echo e(url('stock_entries/create/'.$type)); ?>" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> <?php echo e(__('lang.create')); ?>

		</a>
	<?php endif; ?>
	<form action="<?php echo e(route('stock_entries.index',$type)); ?>" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
	<!-- <?php if($type == 'inbound'): ?>
	<a href="<?php echo e(url('to_storage_list/'.$type)); ?>" type="button" class="btn btn-warning" title="Close Storage">
		<i class="fa fa-database"></i> Tutup Storage
	</a>
	<?php else: ?>
	<a href="<?php echo e(url('to_storage_list/'.$type)); ?>" type="button" class="btn btn-primary" title="Close Storage">
		<i class="fa fa-database"></i> Buka Storage
	</a>
	<?php endif; ?> -->
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<input type="hidden" value="<?php echo e(Auth::user()->hasRole('Transporter') ? 'transporter' : ''); ?>" id="login">
<div class="table-responsive">
	<table class="data-table table table-bordered table-hover no-margin" id="table-putaway" width="100%">
		<thead>
			<tr>
				<th>Nomor</th>
				<?php if($type=='inbound'): ?>
					<th>PA#:</th>
					<th>GR#:</th>
				<?php else: ?> 
					<th>PP#:</th>
					<?php if(!Auth::user()->hasRole('Transporter')): ?>
					<th>DP#:</th>
					<?php endif; ?>
					<th><?php echo e(__('lang.consignee')); ?></th>
					<th><?php echo e(__('lang.destination')); ?></th>
				<?php endif; ?>
				
				<th><?php echo e(__('lang.customer_reference')); ?>:</th>
				<th><?php echo e(__('lang.warehouse_supervisor')); ?>:</th>
				<th><?php echo e(__('lang.warehouse_checker')); ?>:</th>
				<th>Status:</th>
				<th width="15%"></th>
			</tr>
		</thead>

		<tbody>
			<?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td><?php echo e($loop->iteration); ?></td>
				<td><?php echo e($item->code); ?></td>
				<!-- <td><?php echo e(empty($item->stock_transport) ?'': $item->stock_transport->code); ?></td> -->
				<?php if(!Auth::user()->hasRole('Transporter')): ?>
				<td><?php echo e($item->transport_code); ?></td>
				<?php endif; ?>
				<?php if($type=='outbound'): ?>
				<td><?php echo e($item->stock_transport->consignee->name); ?></td>
				<td><?php echo e($item->stock_transport->destination->name); ?></td>
				<?php endif; ?>
				<td><?php echo e($item->ref_code); ?></td>
				<td>
					<?php echo e($item->user->user_id); ?>

				</td>
				<td>
					<?php echo e($item->employee_name); ?>

				</td>
				<?php if(Auth::user()->hasRole('CargoOwner')): ?>
					<td>
						<?php echo e(ucfirst(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status)))); ?>

					</td>
				<?php else: ?>
					<td>
						<?php echo e(ucfirst(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Submitted' ? 'Completed' : $item->status)))); ?>

					</td>
				<?php endif; ?>
				<td>
					<div class="btn-toolbar">
						<?php if(Auth::user()->id == $item->user_id): ?>
							<?php if($item->editable): ?>
								<div class="btn-group" role="group">
									<a href="<?php echo e(url('stock_entries/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
								<div class="btn-group" role="group">
									<form action="<?php echo e(url('stock_entries', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
										<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
									</form>
								</div>
							<?php else: ?>
								<div class="btn-group" role="group">
									<a href="<?php echo e(url('stock_entries/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
										<i class="fa fa-eye"></i>
									</a>
								</div>
							<?php endif; ?>
						<?php else: ?>
							<div class="btn-group" role="group">
								<a href="<?php echo e(url('stock_entries/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
<script>
	if($('#login').val() == 'transporter') {
		var table = $('#table-putaway').DataTable({
			"order": [[ 0, "asc" ]],
		});
	}
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>