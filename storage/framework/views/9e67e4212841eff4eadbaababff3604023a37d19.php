<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Add User - <?php echo e($warehouse->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- form start -->
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
		        <form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
					<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
				        <?php echo e(method_field($method)); ?>

				    <?php endif; ?>
				    <?php echo csrf_field(); ?>
					<div class="box-body">
						<input type="hidden" name="warehouse_id" value="<?php echo e($warehouse->id); ?>" />
						<div class="form-group">
							<label class="col-sm-3 control-label">Cabang/Subcabang</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?php echo e($party->name); ?>" readonly>
							</div>
						</div>
						<div class="form-group required">
							<label for="user_id" class="col-sm-3 control-label">Pilih User</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="user_id" required>
				                    <option value="" disabled>-- Pilih User --</option>
									<?php $__currentLoopData = $user_officer_supervisor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<?php if($user->warehouse_name == 'free'): ?>
											<option value="<?php echo e($user->id); ?>">
				                            	<?php echo e($user->first_name); ?> - <?php echo e($user->last_name); ?> 
				                            	(<?php echo e($user->role); ?>)
				                            	(<?php echo e($user->warehouse_name); ?>)
				                            </option>
				                        <?php else: ?>
				                        	<option value="<?php echo e($user->id); ?>" disabled>
				                            	<?php echo e($user->first_name); ?> - <?php echo e($user->last_name); ?> 
				                            	(<?php echo e($user->role); ?>)
				                            	(<?php echo e($user->warehouse_name); ?>)
				                            </option>
				                        <?php endif; ?>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


								</select>
							</div>
						</div>
						
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-left">Simpan</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Warehouse User List</h3>
				<div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		         </div>
		         <div class="table-responsive">
		          	<table class="table" width="100%">
		              	<thead>
		                	<tr>
			                    <th>Name</th>
			                    <th>Role</th>
			                    <th>Action</th>
			                </tr>
		              	</thead>

		              	<tbody>
			                <?php $__currentLoopData = $warehouse_officers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                  	<tr>
			                      	<td><?php echo e($wo->user->first_name); ?> <?php echo e($wo->user->last_name); ?></td>
			                      	<td><?php echo e($wo->user->roles->first()->name); ?></td>
			                      	<td>
			                        	<div class="btn-group" role="group">
			                          		<form action="<?php echo e(route('delete_warehouse_officer')); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
					                            <input type="hidden" name="_method" value="DELETE">
					                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
					                            <input type="hidden" name="warehouse_officer_id" value="<?php echo e($wo->id); ?>">
					                            <input type="hidden" name="warehouse_id" value="<?php echo e($wo->warehouse_id); ?>">
					                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
			                          		</form>
			                        	</div>
			                      	</td>
			                  	</tr>
			                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		              	</tbody>
		          	</table>
		        </div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>