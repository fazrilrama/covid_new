<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Komoditas</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id">
					<?php $__currentLoopData = $commodities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commodity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($commodity->id); ?>" <?php if($type->commodity_id == $commodity->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($commodity->code); ?> - <?php echo e($commodity->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
	<div class="box-body">
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $type->name)); ?>">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>