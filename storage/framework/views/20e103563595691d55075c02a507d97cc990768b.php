<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Storage List
	<?php if(!Auth::user()->hasRole('AdminBulog')): ?>

    	<a href="<?php echo e(url('storages/create')); ?>" type="button" class="btn btn-success" title="Create">
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
	                <th>Storage ID:</th>
	                <th>Warehouse ID:</th>
	                <th>Warehouse:</th>
	                <th>Komoditas:</th>
	                <th>Space (m<sup>2</sup>):</th>
	                <th>Volume (m<sup>3</sup>):</th>
					<th>Status</th>
	                <!-- <th>Used Weight:</th>
	                <th>Used Volume:</th>
	                <th>Utility Weight:</th>
	                <th>Utility Volume:</th> -->
	                <th>Aksi</th>
	            </tr>
	        </thead>
	        
	        <tbody>
		        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		            <tr>
		                <td><?php echo e($item->code); ?></td>
		                <td><?php echo e(empty($item->warehouse) ?'': $item->warehouse->code); ?></td>
		                <td><?php echo e(empty($item->warehouse) ?'': $item->warehouse->name); ?></td>
		                <td><?php echo e(empty($item->commodity) ?'': $item->commodity->name); ?></td>
		                <td><?php echo e($item->width * $item->length); ?></td>
		                <td><?php echo e($item->volume); ?></td>
						<th><?php echo e($item->is_active == '0' ? 'Tidak Aktif' : 'Aktif'); ?></th>
		                <!-- <td><?php echo e($item->used_weight); ?></td>
		                <td><?php echo e($item->used_volume); ?></td>
		                <td><?php echo e(@number_format(($item->used_weight/$item->weight)/100, 2, '.','.')); ?>%</td>
		                <td><?php echo e(@number_format(($item->used_volume/$item->volume)/100, 2, '.', '.')); ?>%</td> -->
		                <td>
		                	<div class="btn-toolbar">
								<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-Storages')): ?>
									<div class="btn-group" role="group">
										<a href="<?php echo e(url('storages/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
											<i class="fa fa-pencil"></i>
										</a>
									</div>
								<?php endif; ?>

								<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-Storages')): ?>
									<div class="btn-group" role="group">
										<form action="<?php echo e(url('storages', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
											<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
										</form>
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