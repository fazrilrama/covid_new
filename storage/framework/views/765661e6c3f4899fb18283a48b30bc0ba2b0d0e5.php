<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="contract_id" class="col-sm-3 control-label">Contract</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="contract_id">
					<?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($id); ?>" <?php if($spk->contract_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="number_spk" class="col-sm-3 control-label">No. SPK</label>
			<div class="col-sm-9">
				<input type="text" name="number_spk" class="form-control" placeholder="No. SPK" value="<?php echo e(old('number_spk', $spk->number_spk)); ?>">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>