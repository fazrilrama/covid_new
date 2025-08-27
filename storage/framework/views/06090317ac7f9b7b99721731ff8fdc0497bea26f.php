<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
	<h1>Goods Issue List
		<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
			<a href="<?php echo e(url('stock_deliveries/create/'.$type)); ?>" type="button" class="btn btn-success" title="Create">
				<i class="fa fa-plus"></i> Tambah
			</a>
		<?php endif; ?>
	</h1>
	<form action="<?php echo e(route('stock_deliveries.index',$type)); ?>" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
		<table class="data-table table table-bordered table-hover no-margin" width="100%">
			<thead>
			<tr>
				<?php if(Auth::user()->hasRole('Reporting')): ?>
				<th>Nomor:</th>
				<?php endif; ?>
				<th>GI#:</th>
				<?php if(!Auth::user()->hasRole('Reporting')): ?>
				<th>PP#:</th>
				<?php endif; ?>
				<th>AON#:</th>
				<th><?php echo e(__('lang.consignee')); ?>:</th>
				<th><?php echo e(__('lang.origin')); ?>:</th>
				<th><?php echo e(__('lang.destination')); ?>:</th>
				<?php if(!Auth::user()->hasRole('Reporting')): ?>
				<th><?php echo e(__('lang.warehouse_supervisor')); ?>:</th>
				<?php endif; ?>
				<th><?php echo e(__('lang.created_at')); ?>:</th>
				<th><?php echo e(__('lang.eta')); ?>:</th>
	            <th>Status</th>
	            <th><?php echo e(__('lang.received_date')); ?></th>
				<th width="15%"></th>
			</tr>
			</thead>

			<tbody>
			<?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<?php if(Auth::user()->hasRole('Reporting')): ?>
					<td><?php echo e($loop->iteration); ?></td>
					<?php endif; ?>
					<td><?php echo e($item->code); ?></td>
					<?php if(!Auth::user()->hasRole('Reporting')): ?>
					<td><?php echo e($item->stock_entry ? $item->stock_entry->code: ''); ?></td>
					<?php endif; ?>
					<td><?php echo e($item->stock_entry ? $item->stock_entry->stock_transport->advance_notice->code : ''); ?></td>
					<td><?php echo e($item->consignee ? $item->consignee->name : ''); ?></td>
					<td><?php echo e($item->origin ? $item->origin->name : ''); ?></td>
					<td><?php echo e($item->destination ? $item->destination->name : ''); ?></td>
					<?php if(!Auth::user()->hasRole('Reporting')): ?>
					<td><?php echo e($item->user ? $item->user->user_id : ''); ?></td>
					<?php endif; ?>
					<td><?php echo e($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:m') : $item->etd); ?></td>
					<td><?php echo e($item->eta); ?></td>
	                <td>
	                	<?php if(Auth::user()->hasRole('CargoOwner')): ?>
                        <?php echo e(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status))); ?>

                        <?php else: ?>
                        <?php echo e(($item->status == 'Pending' ? 'Planning' : $item->status )); ?>

                        <?php endif; ?>
	                </td>
					<td><?php echo e($item->date_received); ?></td>
					<td>
						<div class="btn-toolbar">
							<?php if( ($item->status == 'Processed') || ($item->status == 'Pending')): ?>
							<div class="btn-group" role="group">
								<a href="<?php echo e(url('stock_deliveries/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
									<i class="fa fa-pencil"></i>
								</a>
							</div>
							<div class="btn-group" role="group">
								<form action="<?php echo e(url('stock_deliveries', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
									<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
								</form>
							</div>
							<?php elseif($item->status == 'Completed' || $item->status == 'Received'): ?>
							<div class="btn-group" role="group">
								<a href="<?php echo e(url('stock_deliveries/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
									<i class="fa fa-eye"></i>
								</a>
							</div>
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