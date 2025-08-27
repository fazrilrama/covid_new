<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1><?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?> List
	<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
	  	<a href="<?php echo e(url('stock_transports/create/'.$type)); ?>" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> <?php echo e(__('lang.create')); ?>

	    </a>
	<?php endif; ?>
	<?php if($type=='inbound'): ?>
	<form action="<?php echo e(route('stock_transports.index',$type)); ?>" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
	<?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
			<thead>
				<tr>
					<?php if($type=='inbound'): ?>
					<th>GR#:</th>
					<?php else: ?>
					<th>DP#:</th>
					<?php endif; ?>
					<?php if($type=='inbound'): ?>
					<th>AIN#:</th>
					<?php else: ?>
					<th>AON#:</th>
					<?php endif; ?>
					<th><?php echo e(__('lang.origin')); ?>:</th>
					<th><?php echo e(__('lang.destination')); ?>:</th>
					<th>ETD:</th>
					<th>ETA:</th>
					<th><?php echo e(__('lang.shipper')); ?>:</th>
					<?php if($type=='inbound'): ?>
					<th><?php echo e(__('lang.consignee')); ?>:</th>
					<?php endif; ?>
					<th>Status:</th>
					<!-- <th>Updated At:</th> -->
					<th width="15%"></th>
				</tr>
			</thead>
	        
	        <tbody>

	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
                  <td><?php echo e($item->code); ?></td>
	                <td><?php echo e($item->advance_code); ?></td>
	                <td><?php echo e(!empty($item->origin) ? $item->origin : ''); ?></td>
	                <td><?php echo e(!empty($item->destination) ? $item->destination : ''); ?></td>
	                <td><?php echo e($item->etd); ?></td>
	                <td><?php echo e($item->eta); ?></td>
					<td><?php echo e($item->shipper_name); ?></td>
					<?php if($type=='inbound'): ?>
					<td><?php echo e(!empty($item->consignee_name) ? $item->consignee_name : ''); ?></td>
					<?php endif; ?>
					<td><?php echo e(($item->status == 'Pending') ? 'Planning' : $item->status); ?></td>
					<td>
	                	<div class="btn-toolbar">
                            <?php if(Auth::user()->id == $item->user_id): ?>
								<?php if($item->status == 'Pending' || $item->status == 'Processed'): ?>
		                		<div class="btn-group" role="group">
			                      	<a href="<?php echo e(url('stock_transports/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
			                      		<i class="fa fa-pencil"></i>
			                      	</a>
			                    </div>
			                    <div class="btn-group" role="group">
		                      		<form action="<?php echo e(url('stock_transports', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
	          									<input type="hidden" name="_method" value="DELETE">
	          									<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
			                      		<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
			                      	</form>
			                    </div>
		                    	<?php else: ?>
			                    <div class="btn-group" role="group">
									<a href="<?php echo e(url('stock_transports/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
										<i class="fa fa-eye"></i>
									</a>
								</div>
								<?php endif; ?>
							<?php else: ?>
								<div class="btn-group" role="group">
									<a href="<?php echo e(url('stock_transports/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
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
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>