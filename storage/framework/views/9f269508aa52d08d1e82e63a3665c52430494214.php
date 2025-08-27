<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<?php if($status == 'create'): ?>
			<div class="form-group required">
				<label for="company_id" class="col-sm-3 control-label">Project</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="project_id[]" multiple>
						<option value="" disabled>-- Pilih Project --</option>
						<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($project->id); ?>">
								<?php echo e($project->project_id); ?> - <?php echo e($project->name); ?>

							</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>
		<?php else: ?>
			
			<div class="form-group required">
				<label for="warehouse_id" class="col-sm-3 control-label">Project</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="project_id[]" multiple required>
	                    <option value="" disabled>-- Pilih Project --</option>
						<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php 
                                $project_dipilih = '';
                                
                                foreach($item_project[$item->id] as $data){
                                    $project_dipilih .= $data->project->project_id.' , ';
                                }
                            ?> 

                            <?php if(stristr($project_dipilih,$project->project_id)): ?>
                                <option value="<?php echo e($project->id); ?>" selected>
                                	<?php echo e($project->project_id); ?> - <?php echo e($project->name); ?>

                                </option>
                            <?php else: ?>
                                <option value="<?php echo e($project->id); ?>">
                                	<?php echo e($project->project_id); ?> - <?php echo e($project->name); ?>

                                </option>
                            <?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group required">
			<label for="sku" class="col-sm-3 control-label">SKU</label>
			<div class="col-sm-9">
				<input type="text" name="sku" class="form-control" placeholder="Sku" value="<?php echo e(old('sku', $item->sku)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $item->name)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="additional_reference" class="col-sm-3 control-label">Additional Reference</label>
			<div class="col-sm-9">
				<input type="text" name="additional_reference" class="form-control" placeholder="Additional Reference" value="<?php echo e(old('additional_reference', $item->additional_reference)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-3 control-label">Description</label>
			<div class="col-sm-9">
				<textarea name="description" rows="10" class="form-control"><?php echo e(old('description', $item->description)); ?></textarea>
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Handling Tarif</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="handling_tarif" class="form-control" placeholder="Handling Tarif" value="<?php echo e(old('handling_tarif', $item->handling_tarif)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Length (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="length" class="form-control" placeholder="Length" value="<?php echo e(old('length', $item->length)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="width" class="col-sm-3 control-label">Width (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="width" class="form-control" placeholder="Width" value="<?php echo e(old('width', $item->width)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="height" class="col-sm-3 control-label">Height (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="height" class="form-control" placeholder="Height" value="<?php echo e(old('height', $item->height)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume (m3)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="volume" class="form-control" placeholder="Volume" value="<?php echo e(old('volume', $item->volume)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight (kg)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="weight" class="form-control" placeholder="Weight" value="<?php echo e(old('weight', $item->weight)); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="min_qty" class="col-sm-3 control-label">Min Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="min_qty" class="form-control" placeholder="Min Qty" value="<?php echo e(old('min_qty', $item->min_qty)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="default_uom_id" class="col-sm-3 control-label">Default UOM</label>
			<div class="col-sm-9">
				<select class="form-control" name="default_uom_id">
					<?php $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($id); ?>" <?php if($item->default_uom_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Komoditas</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id">
					<?php $__currentLoopData = $commodities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commodity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($commodity->id); ?>" <?php if($item->commodity_id == $commodity->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($commodity->code); ?> - <?php echo e($commodity->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="type_id" class="col-sm-3 control-label">Jenis</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="type_id">
					<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($type->id); ?>" <?php if($item->type_id == $type->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($type->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="packing_id" class="col-sm-3 control-label">Packing</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="packing_id">
					<?php $__currentLoopData = $packings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $packing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($packing->id); ?>" <?php if($item->packing_id == $packing->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($packing->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="control_method_id" class="col-sm-3 control-label">Control Method</label>
			<div class="col-sm-9">
				<select class="form-control" name="control_method_id">
					<?php $__currentLoopData = $control_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($id); ?>" <?php if($item->control_method_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
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

<?php $__env->startSection('custom_script'); ?>
<script>
    $('input[name="length"]').keyup(() => { setVolume() });
    $('input[name="width"]').keyup(() => { setVolume() });
    $('input[name="height"]').keyup(() => { setVolume() });

    function setVolume(){
        var volume = $('input[name="length"]').val() * $('input[name="width"]').val() * $('input[name="height"]').val();
        $('input[name="volume"]').val(volume);
    }
</script>
<?php $__env->stopSection(); ?>