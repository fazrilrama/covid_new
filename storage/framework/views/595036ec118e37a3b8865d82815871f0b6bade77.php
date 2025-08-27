<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="id" class="col-sm-3 control-label">Id</label>
			<div class="col-sm-9">
				<input type="text" name="id" class="form-control" placeholder="Id" value="<?php echo e(old('id', $city->id)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Code</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Code" value="<?php echo e(old('code', $city->code)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $city->name)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="region_id" class="col-sm-3 control-label">Province</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="region_id">
					<?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regionValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($regionValue->id); ?>" <?php if($city->province_id == $regionValue->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($regionValue->id); ?> - <?php echo e($regionValue->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>