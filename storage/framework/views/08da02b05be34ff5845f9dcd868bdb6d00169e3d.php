<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Stock Opname</h1>
	<?php if(Auth::user()->hasRole('WarehouseSupervisor') || Auth::user()->hasRole('WarehouseManager')): ?>
        <a href="<?php echo e(route('stock_opnames.create')); ?>" type="button" class="btn btn-success" title="Create">
            <i class="fa fa-plus"></i> Tambah
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Tanggal:</th>
					<th>Calculated By:</th>
					<th>Description:</th>
					<th>Status:</th>
	                <th></th>
	            </tr>
	        </thead>
	     	   
	        <tbody>
				<?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
					<td><?php echo e(Carbon\Carbon::parse($collection->date)->format('d M Y H:m')); ?></td>
					<td>
					<?php if($collection->calculated_by != null): ?>
					<ul>
					<?php $__currentLoopData = json_decode($collection->calculated_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><span><?php echo e($value); ?> </span></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
					<?php endif; ?>
					</td>
					<td>
					<?php if($collection->note != null): ?>
					<ul>
					<?php $__currentLoopData = json_decode($collection->note); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li><span><?php echo e($value); ?> </span></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
					<?php endif; ?>
					</td>
					<td>
						<?php echo e($collection->status); ?>

					</td>
					<td>
						<div class="btn-group" role="group">
							<a href="<?php echo e(url('stock_opnames/' . $collection->id. '/view')); ?>" type="button" class="btn btn-primary" title="View">
								<i class="fa fa-eye"></i>
							</a>
						</div>
						<?php if($collection->status == 'Processed'): ?>
						<div class="btn-group" role="group">
							<a href="<?php echo e(url('stock_opnames/' . $collection->id .'/edit')); ?>" type="button" class="btn btn-warning" title="Edit">
								<i class="fa fa-pencil"></i>
							</a>
						</div>
						<div class="btn-group" role="group">
							<form action="<?php echo e(url('stock_opnames', [$collection->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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