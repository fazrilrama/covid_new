<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
	<?php echo e(method_field($method)); ?>

	<?php endif; ?>
	<?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Project ID</label>
			<div class="col-sm-9">
				<input type="text" name="project_id" class="form-control" placeholder="Project id" value="<?php echo e(old('project_id', !empty($namingSeries) ? $namingSeries : (!empty($project) ? $project->project_id : '') )); ?>" <?php echo e(empty($project->id) ?: 'readonly'); ?>>
			</div>
		</div>
		<div class="form-group required">
            <label for="name" class="col-sm-3 control-label">Nama Project</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $project->name)); ?>">
            </div>
        </div>

		
			<div class="form-group required">
				<label for="company_id" class="col-sm-3 control-label">Project Owner</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="company_id" required>
						<?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($id); ?>" <?php if($project->company_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>
		
		<div class="form-group required">
			<label for="description" class="col-sm-3 control-label">Deskripsi Project</label>
			<div class="col-sm-9">
				<textarea rows="10" name="description" class="form-control" placeholder="description"><?php echo e(old('description', $project->description)); ?></textarea>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
		<?php if($method == 'PUT'): ?>
			<a href="<?php echo e(route('to_add_project_storage',$project)); ?>" class="btn btn-warning pull-right" style="margin-right: 10px">Add Storage</a>
		<?php endif; ?>
	</div>
	<!-- /.box-footer -->
</form>
