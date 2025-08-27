<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Project</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="project_id">
					<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($projectValue->id); ?>" <?php if($contract->project_id == $projectValue->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($projectValue->project_id); ?> - <?php echo e($projectValue->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Charge Method</label>
			<div class="col-sm-9">
				<select class="form-control" name="charge_method">
					<option value="" selected disabled>
						Select Charge Method
					</option>
					<option value="Weight Based" <?php if($contract->charge_method == 'Weight Based'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Weight Based
					</option>
					<option value="Volume Based" <?php if($contract->charge_method == 'Volume Based'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Volume Based
					</option>
					<option value="Unit Based" <?php if($contract->charge_method == 'Unit Based'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Unit Based
					</option>
					<option value="CW Based" <?php if($contract->charge_method == 'CW Based'): ?><?php echo e('selected'); ?><?php endif; ?>>
						CW Based
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Charge Space</label>
			<div class="col-sm-9">
				<select class="form-control" name="charge_space">
					<option value="" selected disabled>
						Select Charge Space
					</option>
					<option value="Fix" <?php if($contract->charge_space == 'Fix'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Fix
					</option>
					<option value="Variable" <?php if($contract->charge_space == 'Variable'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Variable
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="number_contract" class="col-sm-3 control-label">No. Contract</label>
			<div class="col-sm-9">
				<input type="text" name="number_contract" class="form-control" placeholder="No. Contract" value="<?php echo e(old('number_contract', $contract->number_contract)); ?>">
			</div>
		</div>
		<div class="form-group">
            <label for="do_attachment" class="col-sm-3 control-label">Contract Document</label>
            <div class="col-sm-9">
                <input type="file" name="contract_doc" class="form-control" placeholder="SPTB" value="<?php echo e(old('contract_doc', $contract->contract_doc)); ?>">
                <?php if(!empty($contract->contract_doc)): ?>
                	<a href="<?php echo e(\Storage::disk('public')->url($contract->contract_doc)); ?>" target="_blank">See Doc</a>
                <?php endif; ?>
            </div>
        </div>
		<div class="form-group required">
			<label for="start_date" class="col-sm-3 control-label">Start Date</label>
			<div class="col-sm-9">
				<input type="text" name="start_date" class="datepicker-normal form-control" placeholder="Start Date" value="<?php echo e(old('start_date', $contract->start_date)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="end_date" class="col-sm-3 control-label">End Date</label>
			<div class="col-sm-9">
				<input type="text" name="end_date" class="end-datepicker-normal form-control" placeholder="End Date" value="<?php echo e(old('end_date', $contract->end_date)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="space_allocated" class="col-sm-3 control-label">Space</label>
			<div class="col-sm-9">
				<input type="number" name="space_allocated" class="form-control" placeholder="Space (m2)" value="<?php echo e(old('space_allocated', $contract->space_allocated)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_space" class="col-sm-3 control-label">Unit Space</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_space">
					<option value="" selected disabled>
						Select Unit Space
					</option>
					<option value="(m2)" <?php if($contract->unit_space == '(m2)'): ?><?php echo e('selected'); ?><?php endif; ?>>
						(m2)
					</option>
					<option value="(m3)" <?php if($contract->unit_space == '(m3)'): ?><?php echo e('selected'); ?><?php endif; ?>>
						(m3)
					</option>
					<option value="Kg" <?php if($contract->unit_space == 'Kg'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_space" class="col-sm-3 control-label">Tariff Space</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_space" class="form-control" placeholder="Tariff Space" value="<?php echo e(old('tariff_space', $contract->tariff_space)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_handling_in" class="col-sm-3 control-label">Unit Handling In</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_handling_in">
					<option value="" selected disabled>
						Select Unit Handling In
					</option>
					<option value="(m3)" <?php if($contract->unit_handling_in == '(m3)'): ?><?php echo e('selected'); ?><?php endif; ?>>
						(m3)
					</option>
					<option value="Kg" <?php if($contract->unit_handling_in == 'Kg'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_handling_in" class="col-sm-3 control-label">Tariff Handling In</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_handling_in" class="form-control" placeholder="Tariff Handling In" value="<?php echo e(old('tariff_handling_in', $contract->tariff_handling_in)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_handling_out" class="col-sm-3 control-label">Unit Handling Out</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_handling_out">
					<option value="" selected disabled>
						Select Unit Handling Out
					</option>
					<option value="(m3)" <?php if($contract->unit_handling_out == '(m3)'): ?><?php echo e('selected'); ?><?php endif; ?>>
						(m3)
					</option>
					<option value="Kg" <?php if($contract->unit_handling_out == 'Kg'): ?><?php echo e('selected'); ?><?php endif; ?>>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_handling_out" class="col-sm-3 control-label">Tariff Handling Out</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_handling_out" class="form-control" placeholder="Tariff Handling Out" value="<?php echo e(old('tariff_handling_out', $contract->tariff_handling_out)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
				<select class="form-control" name="is_active">
					<option value="1" <?php if($contract->is_active == 1): ?><?php echo e('selected'); ?><?php endif; ?>>Aktif</option>
					<option value="0" <?php if($contract->is_active == 0): ?><?php echo e('selected'); ?><?php endif; ?>>Tidak Aktif</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Commodity</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id" required>
                    <option value="" selected disabled>-- Pilih Commodity --</option>
					<?php $__currentLoopData = $commodities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commodity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($commodity->id); ?>" <?php if(old('commodity_id', $contract->commodity_id) == $commodity->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($commodity->code); ?> - <?php echo e($commodity->name); ?></option>
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