<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
    <div class="form-group">
		<label for="from_uom_id" class="col-sm-3 control-label">From UOM</label>
		<div class="col-sm-9">
			<select class="form-control" name="from_uom_id">
				<?php $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($id); ?>" <?php if($uomConversion->from_uom_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="to_uom_id" class="col-sm-3 control-label">To UOM</label>
		<div class="col-sm-9">
			<select class="form-control" name="to_uom_id">
				<?php $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($id); ?>" <?php if($uomConversion->to_uom_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	<div class="box-body">
		<div class="form-group required">
			<label for="multiplier" class="col-sm-3 control-label">Multiplier</label>
			<div class="col-sm-9">
				<input type="text" name="multiplier" class="form-control" placeholder="Multiplier" value="<?php echo e(old('multiplier', $uomConversion->multiplier)); ?>">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>