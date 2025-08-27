<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Daftar User Login</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<p>Total User Login: <?php echo e($total_user); ?> User</p>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	            	<th>Last Login</th>
	                <th>Full Name</th>            
	            </tr>
	        </thead>
	        
	        <tbody>
		        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		            <tr>
		            	<td><?php echo e($item->created_at); ?></td>
		                <td><?php echo e($item->user->first_name); ?> <?php echo e($item->user->last_name); ?></td>		                
		            </tr>
		        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>