<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Daftar User Project - <?php echo e($project->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>User ID:</th>
	                <th>First Name:</th>
	                <th>Last Name:</th>
	                <th>Email:</th>
	                <th>Updated At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($user->id); ?></td>
	                <td><?php echo e($user->user_id); ?></td>
	                <td><?php echo e($user->first_name); ?></td>
	                <td><?php echo e($user->last_name); ?></td>
	                <td><?php echo e($user->email); ?></td>
	                <td><?php echo e($user->updated_at); ?></td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="<?php echo e(route('users.edit_projects',$user->id)); ?>" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
	                    </div>
	                </td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>