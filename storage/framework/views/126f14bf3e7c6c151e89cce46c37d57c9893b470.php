<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Daftar User Terkunci</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>User ID:</th>
	                <th>Name:</th>
	                <th>Email:</th>
	                <th>Locked At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->user_id); ?></td>
	                <td><?php echo e($item->first_name); ?></td>
	                <td><?php echo e($item->email); ?></td>
	                <td><?php echo e($item->created_at); ?></td>
	                <td><a href="<?php echo e(route('user-locked-unlock', ['user_id' => $item->user_id])); ?>" class="btn btn-primary">Unlock</a></td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>