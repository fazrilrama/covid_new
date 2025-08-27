<div class="form-group required">
	<label for="region_id" class="col-sm-3 control-label">Province</label>
	<div class="col-sm-9">
		<select class="form-control" name="region_id" id="region-id-warehouse">
		<?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<option value="<?php echo e($region->id); ?>" <?php echo e($region->id == $province->id ? 'selected="selected"' : ''); ?>><?php echo e($region->name); ?></option>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</select>
	</div>
</div>
<div id="select_city">
	<div class="form-group required">
		<label for="city_id" class="col-sm-3 control-label">Kota</label>
		<div class="col-sm-9">
			<select class="form-control" name="city_id" id="city_id">
				<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($city->id); ?>"><?php echo e($city->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
</div>