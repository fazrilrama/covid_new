<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group">
			<label for="stock_delivery_id" class="col-sm-3 control-label">
				<?php echo e($stockDeliveryDetail->header->type == 'outbound' ? 'Goods Issue' : ''); ?> #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static"><?php echo e($stockDeliveryDetail->header->code); ?></p>
				<input type="hidden" name="stock_delivery_id" value="<?php echo e($stockDeliveryDetail->stock_delivery_id); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Item SKU</label>
			<div class="col-sm-9">
				<select class="form-control" name="item_id" id="item" required>
                    <option value="">-- Pilih Item SKU --</option>
					<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($value->id); ?>" <?php if($stockDeliveryDetail->item_id == $value->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value->sku); ?> - <?php echo e($value->name); ?></</option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="ref_code" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">
				<select name="ref_code" id="inputgroup_reference" class="form-control" required>
                    <option value="">-- Pilih Group Ref --</option>
                    <?php if(!empty(old('ref_code', $stockDeliveryDetail->ref_code))): ?>
                        <?php if(!empty($refCodes)): ?>
                            <?php $__currentLoopData = $refCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($refCode->ref_code); ?>" <?php echo e(old('ref_code', $stockDeliveryDetail->ref_code) != $refCode->ref_code ?'': 'selected'); ?>><?php echo e($refCode->ref_code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif(!empty(session('refCodes'))): ?>
                            <?php $__currentLoopData = session('refCodes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($refCode->ref_code); ?>" <?php echo e(old('ref_code', $stockDeliveryDetail->ref_code) != $refCode->ref_code ?'': 'selected'); ?>><?php echo e($refCode->ref_code); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <option value="<?php echo e(old('ref_code', $stockDeliveryDetail->ref_code)); ?>"><?php echo e(old('ref_code', $stockDeliveryDetail->ref_code)); ?></option>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
			</div>
		</div>
		<div class="form-group required">
			<label for="control_date" class="col-sm-3 control-label">Control Date</label>
			<div class="col-sm-9">
				<select name="control_date" id="control_date" class="form-control" required>
                    <option value="">-- Pilih Control Date --</option>
                    <?php if(!empty(old('control_date', $stockDeliveryDetail->control_date))): ?>
                        <?php if(!empty($controlDates)): ?>
                            <?php $__currentLoopData = $controlDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controlDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($stockDeliveryDetail->control_date); ?>" <?php echo e(old('control_date', $stockDeliveryDetail->control_date) != $controlDate->control_date ?'': 'selected'); ?>><?php echo e($controlDate->control_date); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif(!empty(session('controlDates'))): ?>
                            <?php $__currentLoopData = session('controlDates'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controlDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($controlDate->control_date); ?>" <?php echo e(old('control_date', $stockDeliveryDetail->control_date) != $controlDate->control_date ?'': 'selected'); ?>><?php echo e($controlDate->control_date); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <option value="<?php echo e(old('control_date', $stockDeliveryDetail->control_date)); ?>"><?php echo e(old('control_date', $stockDeliveryDetail->control_date)); ?></option>
                        <?php endif; ?>
                    <?php endif; ?>
                </select>
			</div>
		</div>
		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="text" name="qty" class="form-control" placeholder="Qty" value="<?php echo e(old('qty', $stockDeliveryDetail->qty)); ?>"  required>
			</div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<p class="form-control-static" id="default_uom_name"><?php echo e(@$stockDeliveryDetail->uom->name); ?></p>
				<input type="hidden" name="uom_id" value="<?php echo e(old('uom_id', @$stockDeliveryDetail->uom_id)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="text" name="weight" class="form-control" placeholder="Weight" value="<?php echo e(old('weight', $stockDeliveryDetail->weight)); ?>" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="text" name="volume" class="form-control" placeholder="Volume" value="<?php echo e(old('volume', $stockDeliveryDetail->volume)); ?>" required>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
            $('input[name="qty"]').keyup(function() {
                var qty = $(this).val();
                $('input[name="weight"]').val(baseWeight * qty);
                $('input[name="volume"]').val(baseVolume * qty);
            });
        });
    </script>
<?php $__env->stopSection(); ?>