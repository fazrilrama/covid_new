<!-- form start -->
<input type="hidden" name="created_attttt" value="<?php echo e($stockTransportDetail->header->created_at); ?>">
<form action="<?php echo e($action_actual); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="form-group">
			<label for="stock_transport_id" class="col-sm-3 control-label">
				<?php echo e($stockTransportDetail->header->type == 'outbound' ? 'Delivery Plan' : 'Goods Receiving'); ?> #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static"><?php echo e($stockTransportDetail->header->code); ?></p>
				<input type="hidden" name="stock_transport_id" value="<?php echo e($stockTransportDetail->stock_transport_id); ?>">
				<input type="hidden" name="advance_notice_id" value="<?php echo e($stockTransportDetail->header->advance_notice_id); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="actual-qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="qty" id="actual_qty_change_std" class="form-control" placeholder="Actual QTY" value="<?php echo e(old('qty', $stockTransportDetail->qty)); ?>" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="weight" class="form-control" placeholder="Actual Weight" value="<?php echo e(old('weight', $stockTransportDetail->weight)); ?>" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="volume" class="form-control" placeholder="Actual Volume" value="<?php echo e(old('volume', $stockTransportDetail->volume)); ?>" required>
			</div>
		</div>

        <input type="hidden" name="weight_val" class="form-control" placeholder="Weight" value="<?php echo e(old('weight_val', $weightVal)); ?>">
        <input type="hidden" name="volume_val" class="form-control" placeholder="Volume" value="<?php echo e(old('volume_val', $volumeVal)); ?>">
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Perbarui</button>
	</div>
	<!-- /.box-footer -->
</form>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
            $('form').submit(function(){
                $('select[name="uom_id"]').removeAttr('disabled');
                return true;
            });
        });


    </script>
<?php $__env->stopSection(); ?>
