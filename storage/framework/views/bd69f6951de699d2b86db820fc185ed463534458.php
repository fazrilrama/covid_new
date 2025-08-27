<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Daftar Perusahaan
    	<a href="<?php echo e(url('companies/create')); ?>" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Kode Perusahaan:</th>
	                <th>Name:</th>
	                <th>Address:</th>
	                <th>Type:</th>
	                <th>City:</th>
	                <th width="15%"></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->code); ?></td>
	                <td><?php echo e($item->name); ?></td>
	                <td><?php echo e($item->address); ?></td>
	                <td><?php echo e($item->company_type->name); ?></td>
	                <td><?php echo e($item->city->name); ?></td>
					<td>
	                	<div class="btn-toolbar">
							<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-CompanyList')): ?>
								<div class="btn-group" role="group">
									<a href="<?php echo e(url('companies/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							<?php endif; ?>

							<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-CompanyList')): ?>
								<div class="btn-group" role="group">
									<form action="<?php echo e(url('companies', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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