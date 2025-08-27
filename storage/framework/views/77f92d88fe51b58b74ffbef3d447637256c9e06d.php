<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
	<h1>Work Order</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<h2 class="page-header">AIN</h2>
	<div class="table-responsive">
		<table id="work-order-ain" class="data-table table no-margin" width="100%">
			<thead>
				<tr>
					<th>Project</th>
					<th>AIN #</th>
					<th>Storage Area</th>
					<th>Warehouse</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>ETD</th>
					<th>ETA</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				<?php $__currentLoopData = $inbounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr <?php if($item->row_color): ?> bgcolor="<?php echo e($item->row_color); ?>" style="color: #fff;" <?php endif; ?>>
						<td><?php echo e($item->project->name); ?></td>
						<td><?php echo e($item->code); ?></td>
						<td><?php echo e($item->consignee->name); ?></td>
						<td><?php echo e($item->warehouse['name']); ?></td>
						<td><?php echo e($item->origin->name); ?></td>
						<td><?php echo e($item->destination->name); ?></td>
						<td><?php echo e($item->etd); ?></td>
						<td><?php echo e($item->eta); ?></td>
						<td><?php if($item->status == 'Completed'): ?> Submitted <?php else: ?> <?php echo e($item->status); ?> <?php endif; ?></td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>

	<hr>

	<h2 class="page-header">AON</h2>
	<div class="table-responsive">
		<table id="work-order-aon" class="data-table table no-margin" width="100%">

			<thead>
				<tr>
					<th>Project</th>
					<th>AON #</th>
					<th>Storage Area</th>
					<th>Warehouse</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>ETD</th>
					<th>ETA</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				<?php $__currentLoopData = $outbounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr <?php if($item->row_color): ?> bgcolor="<?php echo e($item->row_color); ?>" style="color: #fff;" <?php endif; ?>>
						<td><?php echo e($item->project->name); ?></td>
						<td><?php echo e($item->code); ?></td>
						<td><?php echo e($item->shipper->name); ?></td>
						<td><?php echo e($item->warehouse['name']); ?></td>
						<td><?php echo e($item->origin->name); ?></td>
						<td><?php echo e($item->destination->name); ?></td>
						<td><?php echo e($item->etd); ?></td>
						<td><?php echo e($item->eta); ?></td>
						<td><?php if($item->status == 'Completed'): ?> Submitted <?php else: ?> <?php echo e($item->status); ?> <?php endif; ?></td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script type='text/javascript'>
        setTimeout("location.reload();",1500000);
        $(document).ready(function() {
            $('#work-order-ain').DataTable( {
                destroy: true,
                "order": [[ 8, "desc" ]]
            } );

            $('#work-order-aon').DataTable( {
                destroy: true,
                "order": [[ 8, "desc" ]]
            } );
        } );
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>