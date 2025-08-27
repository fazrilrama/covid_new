<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Kode Perusahaan</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Perusahaan" value="<?php echo e(old('code', $company->code)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $company->name)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="address" class="col-sm-3 control-label">Address</label>
			<div class="col-sm-9">
				<input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo e(old('address', $company->address)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="postal_code" class="col-sm-3 control-label">Postal Code</label>
			<div class="col-sm-9">
				<input type="text" name="postal_code" class="form-control" placeholder="Postal Code" value="<?php echo e(old('postal_code', $company->postal_code)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
			<div class="col-sm-9">
				<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo e(old('phone_number', $company->phone_number)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
			<div class="col-sm-9">
				<input type="text" name="fax_number" class="form-control" placeholder="Fax Number" value="<?php echo e(old('fax_number', $company->fax_number)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Kota</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="city_id">
					<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($id); ?>" <?php if($company->city_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Jenis Perusahaan</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="company_type_id">
					<?php $__currentLoopData = $company_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($id); ?>" <?php if($company->company_type_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
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