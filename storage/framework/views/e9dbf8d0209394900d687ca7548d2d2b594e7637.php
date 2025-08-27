<div class="form-group required">
	<label for="city_id" class="col-sm-3 control-label">Kota</label>
	<div class="col-sm-9">
		<select class="form-control select2" name="city_id">
			<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($city->id); ?>" 
					<?php if($party != null): ?>
						<?php if($party->city_id == $city->id): ?>
							<?php echo e('selected'); ?>

						<?php endif; ?>
					<?php endif; ?>
				>
					<?php echo e($city->name); ?>

				</option>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</select>
	</div>
</div>