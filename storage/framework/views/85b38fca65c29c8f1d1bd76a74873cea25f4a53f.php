<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Kode Gudang</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Gudang" value="<?php echo e(old('code', $warehouse->code)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama Gudang</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $warehouse->name)); ?>">
			</div>
		</div>
		<!-- <div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Email</label>
			<div class="col-sm-9">
				<input type="email" name="email" class="form-control" placeholder="Email Kepala" value="<?php echo e(old('name', $warehouse->email)); ?>">
			</div>
		</div> -->
		<div class="form-group required">
			<label for="branch_id" class="col-sm-3 control-label">Cabang/Subcabang</label>
			<div class="col-sm-9">
				<select class="form-control" name="branch_id" id="party_id" required>
					<option value="" disabled>-- Pilih Cabang/Subcabang --</option>
					<?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($branch->id); ?>" <?php if($warehouse->branch_id == $branch->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($branch->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		
		<div id="select_region_city">
			<div class="form-group required">
				<label for="region_id" class="col-sm-3 control-label">Province</label>
				<div class="col-sm-9">
					<select class="form-control" name="region_id" id="region-id-warehouse">
						<?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($key); ?>" <?php echo e($key == $warehouse->region_id ? 'selected="selected"' : ''); ?>><?php echo e($value); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>

			<div id="select_city">
				<div class="form-group required">
					<label for="city_id" class="col-sm-3 control-label">Kota</label>
					<div class="col-sm-9">
						<select class="form-control" name="city_id" id="city_id">
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($key); ?>" <?php echo e($key == $warehouse->city_id ? 'selected="selected"' : ''); ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group required">
			<label for="address" class="col-sm-3 control-label">Address</label>
			<div class="col-sm-9">
				<input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo e(old('address', $warehouse->address)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="postal_code" class="col-sm-3 control-label">Postal Code</label>
			<div class="col-sm-9">
				<input type="number" name="postal_code" class="form-control" placeholder="Postal Code" value="<?php echo e(old('postal_code', $warehouse->postal_code)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
			<div class="col-sm-9">
				<input type="number" name="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo e(old('phone_number', $warehouse->phone_number)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
			<div class="col-sm-9">
				<input type="number" name="fax_number" class="form-control" placeholder="Fax Number" value="<?php echo e(old('fax_number', $warehouse->fax_number)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="total_weight" class="col-sm-3 control-label">Total Kapasitas (kg)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="total_weight" class="form-control" placeholder="Total Kapasitas" value="<?php echo e(old('total_weight', $warehouse->total_weight)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="total_volume" class="col-sm-3 control-label">Total Volume (m3)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="total_volume" class="form-control" placeholder="Total Volume" value="<?php echo e(old('total_volume', $warehouse->total_volume)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Length (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="length" class="form-control" placeholder="Length" value="<?php echo e(old('length', $warehouse->length)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="width" class="col-sm-3 control-label">Width (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="width" class="form-control" placeholder="Width" value="<?php echo e(old('width', $warehouse->width)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="tall" class="col-sm-3 control-label">Height (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="tall" class="form-control" placeholder="Height" value="<?php echo e(old('tall', $warehouse->tall)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="latitude" class="col-sm-3 control-label">Latitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude" value="<?php echo e(old('latitude', $warehouse->latitude)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="longitude" class="col-sm-3 control-label">Longitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude" value="<?php echo e(old('longitude', $warehouse->longitude)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="ownership" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
				<select class="form-control" name="ownership">
					<option value="milik" <?php if($warehouse->ownership == 'milik'): ?><?php echo e('selected'); ?><?php endif; ?>>MILIK</option>
					<option value="sewa" <?php if($warehouse->ownership == 'sewa'): ?><?php echo e('selected'); ?><?php endif; ?>>SEWA</option>
					<option value="manajemen" <?php if($warehouse->ownership == 'manajemen'): ?><?php echo e('selected'); ?><?php endif; ?>>MANAJEMEN</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Aktif</label>
			<div class="col-sm-9">
				<select class="form-control" name="is_active" <?php if($warehouse->is_active != null): ?> readonly <?php endif; ?>>
					<option value="0" <?php if($warehouse->is_active == 0): ?><?php echo e('selected'); ?><?php endif; ?>>Tidak Aktif</option>
					<option value="1" <?php if($warehouse->is_active == 1): ?><?php echo e('selected'); ?><?php endif; ?>>Aktif</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Status Operasi</label>
			<div class="col-sm-9">
				<select class="form-control" name="status">
					<option value="" <?php if($warehouse->status == NULL): ?><?php echo e('selected'); ?><?php endif; ?>>Belum Memilih</option>
					<option value="Beroperasi" <?php if($warehouse->status == 'Beroperasi'): ?><?php echo e('selected'); ?><?php endif; ?>>Beroperasi</option>
					<option value="Sewa Lepas" <?php if($warehouse->status == 'Sewa Lepas'): ?><?php echo e('selected'); ?><?php endif; ?>>Sewa Lepas</option>
					<option value="Idle" <?php if($warehouse->status == 'Idle'): ?><?php echo e('selected'); ?><?php endif; ?>>Idle</option>
					<option value="Tutup" <?php if($warehouse->status == 'Tutup'): ?><?php echo e('selected'); ?><?php endif; ?>>Tutup</option>
					<option value="Rusak" <?php if($warehouse->status == 'Rusak'): ?><?php echo e('selected'); ?><?php endif; ?>>Rusak</option>
					<option value="Open Storage" <?php if($warehouse->status == 'Open Storage'): ?><?php echo e('selected'); ?><?php endif; ?>>Open Storage</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="warehouse_type" class="col-sm-3 control-label">Warehouse Type</label>
			<div class="col-sm-9">
				<select class="form-control" name="warehouse_type">
					<?php $__currentLoopData = $warehouse_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($wt['id']); ?>" <?php if($warehouse->type_id == $wt['id']): ?> <?php echo e('selected'); ?> <?php endif; ?>><?php echo e($wt['name']); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="percentage_buffer" class="col-sm-3 control-label">Buffer Stock</label>
			<div class="col-sm-8">
				<input type="number" step="1" name="percentage_buffer" class="form-control" placeholder="Percentage Buffer" value="<?php echo e(old('percentage_buffer', $warehouse->percentage_buffer)); ?>">
			</div>
			<div class="col-sm-1">
				<label for="percentage_buffer" class="control-label">%</label>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>