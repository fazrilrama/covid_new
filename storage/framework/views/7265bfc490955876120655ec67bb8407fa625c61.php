<!-- form start -->
<form id="changeAttributeDelivery" action="<?php echo e($action); ?>" method="<?php echo e($method); ?>" class="form-horizontal" enctype="multipart/form-data">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
	<?php echo e(method_field($method)); ?>

	<?php endif; ?>
	<?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="<?php echo e($type); ?>">
				<input type="hidden" name="method_form_stock_deliveries" id="method_form_stock_deliveries" value="<?php echo e($method); ?>">
				<div class="form-group required">
					<label for="stock_entry_id" class="col-sm-3 control-label"><?php echo e(__('lang.document_reference')); ?> #</label>
					<div class="col-sm-9">
						<select class="form-control" name="stock_entry_id" id="stock_entry_id">
							<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.document_reference')); ?> --</option>
							<?php $__currentLoopData = $stock_entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if(old('stock_entry_id',$stockDelivery->stock_entry_id) == $id): ?><?php echo e('selected'); ?><?php endif; ?>>
								<?php echo e($value); ?>

							</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="transport_type_id" class="col-sm-3 control-label"><?php echo e(__('lang.type_transpotation')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="transport_type_id" id="transport_type_id" readonly>
							<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.type_transpotation')); ?> --</option>
							<?php $__currentLoopData = $transport_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if(old('transport_type_id',$stockDelivery->transport_type_id) == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
					<div class="col-sm-9">
						<input type="text" id="ref_code" name="ref_code" readonly class="form-control" placeholder="<?php echo e(__('lang.customer_reference')); ?>" value="<?php echo e(old('ref_code', $stockDelivery->ref_code)); ?>">
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="vehicle_code_num" class="col-sm-3 control-label">Vehicle Code Number</label>
					<div class="col-sm-9">
						<input type="text" name="vehicle_code_num"  readonly id="vehicle_code_num" class="form-control" placeholder="Vehicle Code Number" value="<?php echo e(old('vehicle_code_num', $stockDelivery->vehicle_code_num)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="vehicle_plate_num" class="col-sm-3 control-label">Vehicle Plate Number</label>
					<div class="col-sm-9">
						<input type="text" name="vehicle_plate_num" readonly id="vehicle_plate_num" class="form-control" placeholder="Vehicle Plate Number" value="<?php echo e(old('vehicle_plate_num', $stockDelivery->vehicle_plate_num)); ?>">
					</div>
				</div> -->
				<div class="form-group">
					<label for="total_collie" class="col-sm-3 control-label">Total <?php echo e(__('lang.colly')); ?></label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_collie" id="total_collie" class="form-control" placeholder="Total Collie" value="<?php echo e(old('total_collie', $stockDelivery->total_collie)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_weight" class="col-sm-3 control-label">Total Weight</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_weight" id="total_weight" class="form-control" placeholder="Total Weight" value="<?php echo e(old('total_weight', $stockDelivery->total_weight)); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_volume" id="total_volume" class="form-control" placeholder="Total Volume" value="<?php echo e(old('total_volume', $stockDelivery->total_volume)); ?>">
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label"><?php echo e(__('lang.pickup_time')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="pickup_order" id="datetimepickerdisable" class="form-control" placeholder="Pick Up Order" value="<?php echo e(old('pickup_order', $stockDelivery->pickup_order)); ?>">
					</div>
				</div>
				<?php if(!empty($stockDelivery->status)): ?>
				<div class="form-group required">
					<label for="ref_code" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-9">	
						<p class="form-control-static">
							<?php echo e(($stockDelivery->status == 'Pending') ? 'Planning' : $stockDelivery->status); ?>

						</p>
					</div>
				</div>
                <?php endif; ?>
			</div>
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="origin_id" class="col-sm-3 control-label"><?php echo e(__('lang.origi')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="origin_id" readonly>
							<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.origin')); ?> --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($id); ?>" <?php if( old('origin_id',$stockDelivery->origin_id) == $id): ?><?php echo e('selected'); ?><?php endif; ?>>
									<?php echo e($value); ?>

								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label" ><?php echo e(__('lang.etd')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="etd" id="etd" readonly class="form-control datepicker" placeholder="Est. Time Delivery" value="<?php echo e(old('etd', $stockDelivery->etd)); ?>">
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_id" class="col-sm-3 control-label" ><?php echo e(__('lang.shipper')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="shipper_id" id="shipper_id" readonly>
							<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.shipper')); ?> --</option>
							<?php $__currentLoopData = $shippers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>" <?php if( old('shipper_id', $stockDelivery->shipper_id) == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>>
								<?php echo e($party->name); ?>

							</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label" ><?php echo e(__('lang.shipper_address')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="shipper_address" id="shipper_address" readonly class="form-control" placeholder="<?php echo e(__('lang.shipper_address')); ?>" value="<?php echo e(old('shipper_address', $stockDelivery->shipper_address)); ?>">
					</div>
				</div>
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="destination_id" readonly>
							<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.destination')); ?> --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($id); ?>" <?php if( old('destination_id',$stockDelivery->destination_id) == $id): ?><?php echo e('selected'); ?><?php endif; ?>>
									<?php echo e($value); ?>

								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label" ><?php echo e(__('lang.eta')); ?></label>
					<div class="col-sm-9">
						<input type="text" name="eta" id="eta" class="form-control end-datepicker" readonly placeholder="ETA" value="<?php echo e(old('eta', $stockDelivery->eta)); ?>">
					</div>
				</div>
				
					<div class="form-group required">
						<label for="consignee_id" class="col-sm-3 control-label" ><?php echo e(__('lang.consignee')); ?></label>
						<div class="col-sm-9">
							<select class="form-control" name="consignee_id" id="consignee_id" readonly>
								<option value="" selected disabled>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.consignee')); ?>  --</option>
								<?php $__currentLoopData = $consignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($party->id); ?>" <?php if(old('consignee_id',$stockDelivery->consignee_id) == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>>
										<?php echo e($party->name); ?>

									</option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label for="consignee_address" class="col-sm-3 control-label"><?php echo e(__('lang.consignee_address')); ?></label>
						<div class="col-sm-9">
							<input type="text" name="consignee_address" id="consignee_address" readonly class="form-control" placeholder="Address" value="<?php echo e(old('consignee_address', $stockDelivery->consignee_address)); ?>">
						</div>
					</div>
				
				<div class="form-group required">
					<label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<select required name="employee_name" id="employee_name" class="form-control" <?php echo e(($stockDelivery->status == 'Completed' ? 'disabled' : '')); ?>>
	                            <option value="">-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.warehouse_checker')); ?> --</option>
	                            <?php $__currentLoopData = $warehouseOfficers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouseOfficer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                <?php
	                                    $whofficerName = ($warehouseOfficer->last_name) ? $warehouseOfficer->first_name. ' ' . $warehouseOfficer->last_name : $warehouseOfficer->first_name;
	                                ?>
	                                <option value="<?php echo e($whofficerName); ?>" <?php echo e(old('employee_name', $stockDelivery->employee_name) == $whofficerName ? 'selected' : ''); ?>>
	                                	<?php echo e($warehouseOfficer->user_id); ?>

	                                </option>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                        </select>
	                    <?php else: ?>
	                    	<input type="text" id="employee_name" name="employee_name" readonly class="form-control" placeholder="Warehouse Officer" value="<?php echo e(old('employee_name', $stockDelivery->employee_name )); ?>">
	                    <?php endif; ?>
					</div>
				</div>

				<input type="hidden" name="status" value="Processed">
			</div>
		</div>
		<?php if(!empty($method)): ?>
			<?php if($method == 'POST'): ?>
				<div class="box-footer">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="disclaimer" required> <?php echo e(__('lang.disclaimer')); ?>

						</label>
					</div>
					<br>
					<button type="submit" class="btn btn-info"> <?php echo e(__('lang.save')); ?></button>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<!-- /.box-body -->
	<!-- <div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div> -->
	
	
	<!-- /.box-footer -->
</form>