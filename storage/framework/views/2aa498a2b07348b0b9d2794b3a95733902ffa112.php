<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal" onsubmit="changeAttributePutaway()">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
	<?php echo e(method_field($method)); ?>

	<?php endif; ?>
	<?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="<?php echo e($type); ?>">
				<input type="hidden" name="get_method_form_stock_entries" id="method_form_stock_entries" value="<?php echo e($method); ?>">
				<div class="form-group required">
					<label for="stock_transport_id" class="col-sm-3 control-label"><?php echo e(__('lang.document_reference')); ?> #</label>
					<div class="col-sm-9">
						<select <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> class="form-control" name="stock_transport_id" id="stock_transport_id">
                            <option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.document_reference')); ?> --</option>
							<?php $__currentLoopData = $stock_transports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($id); ?>" <?php if($stockEntry->stock_transport_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>>
									<?php echo e($value); ?>

								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
					<div class="col-sm-9">
						<input id="ref_code" type="text" name="ref_code" class="form-control" placeholder="<?php echo e(__('lang.customer_reference')); ?>" value="<?php echo e(old('ref_code', $stockEntry->ref_code)); ?>" readonly>
						<p class="help-block"><?php echo e(__('lang.customer_reference_desc')); ?></p>
					</div>
				</div>
				<?php if($method == 'POST'): ?>
					<div class="form-group required">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
						<div class="col-sm-9">
	                        <select required name="employee_name" class="form-control">
	                            <option value="">-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.warehouse_checker')); ?> --</option>
	                        </select>
						</div>
					</div>
					<?php if($type == 'outbound'): ?>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.consignee')); ?></label>
						<div class="col-sm-9">
							<input id="consignee_entries" type="text" name="consignee_entries" class="form-control" placeholder="<?php echo e(__('lang.consignee')); ?>" value="" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.consignee_address')); ?></label>
						<div class="col-sm-9">
							<input id="consignee_address_entries" type="text" name="consignee_address_entries" class="form-control" placeholder="Consingee Address" value="" disabled>
						</div>
					</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="form-group required">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
						<div class="col-sm-9">
	                        <input type="text" id="employee_name" class="form-control" value="<?php echo e($stockEntry->employee_name); ?>" readonly>
						</div>
					</div>
					<?php if($type == 'outbound'): ?>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.consignee')); ?></label>
						<div class="col-sm-9">
							<input id="consignee_entries" type="text" name="consignee_entries" class="form-control" placeholder="<?php echo e(__('lang.consignee')); ?>" value="<?php echo e($stockEntry->stock_transport->consignee->name); ?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.consignee_address')); ?></label>
						<div class="col-sm-9">
							<input id="consignee_address_entries" type="text" name="consignee_address_entries" class="form-control" placeholder="<?php echo e(__('lang.consignee_address')); ?>" value="<?php echo e($stockEntry->stock_transport->consignee->address); ?>" disabled>
						</div>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="total_pallet" class="col-sm-3 control-label">Total <?php echo e(__('lang.pallet')); ?></label>
					<div class="col-sm-9">
						<input type="number" step="0.01" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_pallet" class="form-control" required placeholder="Total Pallet" value="<?php echo e(old('total_pallet', !empty($stockEntry->total_pallet) ? $stockEntry->total_pallet : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_labor" class="col-sm-3 control-label">Total <?php echo e(__('lang.labor')); ?></label>
					<div class="col-sm-9">
						<input type="number" step="0.01" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_labor" class="form-control" required placeholder="Total Labor" value="<?php echo e(old('total_labor', !empty($stockEntry->total_labor) ? $stockEntry->total_labor : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_forklift" class="col-sm-3 control-label">Total Forklift</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_forklift" class="form-control" required placeholder="Total Forklift" value="<?php echo e(old('total_forklift', !empty($stockEntry->total_forklift) ? $stockEntry->total_forklift : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_koli" class="col-sm-3 control-label">Total <?php echo e(__('lang.colly')); ?></label>
					<div class="col-sm-9">
						<input type="number" step="1" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_koli" class="form-control" required placeholder="Total Koli" value="<?php echo e(old('total_koli', !empty($stockEntry->total_koli) ? $stockEntry->total_koli : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_volume" class="form-control" required placeholder="Total Volume" value="<?php echo e(old('total_volume', !empty($stockEntry->total_volume) ? $stockEntry->total_volume : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_berat" class="col-sm-3 control-label">Total Berat</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" <?php echo e(($stockEntry->status == 'Completed' ? 'disabled' : '')); ?> type="text" name="total_berat" class="form-control" required placeholder="Total Forklift" value="<?php echo e(old('total_berat', !empty($stockEntry->total_berat) ? $stockEntry->total_berat : 0)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="forklift_start_time" class="col-sm-3 control-label">Forklift <?php echo e(__('lang.start_time')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="forklift_start_time" class="form-control datetimepickerdisable" id="datetimepickerdisable" placeholder="Forklift Start Time" value="<?php echo e(old('forklift_start_time', $stockEntry->forklift_start_time)); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label for="forklift_end_time" class="col-sm-3 control-label">Forklift <?php echo e(__('lang.end_time')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="forklift_end_time" class="form-control datetimepickerptend" id="datetimepickerptend" placeholder="Forklift End Time" value="<?php echo e(old('forklift_end_time', $stockEntry->forklift_end_time)); ?>" disabled>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<div class="btn-toolbar pull-right">
            <?php if($stockEntry->status !== 'Completed'): ?>
			<div class="btn-group" role="group">
				<button type="submit" class="btn btn-info"><?php echo e(__('lang.save')); ?></button>
			</div>
            <?php endif; ?>
		</div>
	</div>
	<!-- /.box-footer -->
</form>
<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
            $('input[name="total_forklift"]').keyup(function(){
                var inputLength = $(this).val();
                console.log(inputLength);
                if(inputLength != ""){
                    if (inputLength != '0') {
                        $('input[name="forklift_start_time"]').removeAttr('disabled');
                        $('input[name="forklift_end_time"]').removeAttr('disabled');
                        return
                    }
                }
                $('input[name="forklift_start_time"]').attr('disabled', 'true');
                $('input[name="forklift_end_time"]').attr('disabled', 'true');
            })
            // $('.btn-completed').click(function() {
            //     $('form#otp').attr('action', "<?php echo e(route('stock_entries.status', ['stock_entry' => $stockEntry->id, 'status' => 'Completed'])); ?>");
            //     $('#modal-otp').modal('show');
            // })
        });
    </script>
<?php $__env->stopSection(); ?>