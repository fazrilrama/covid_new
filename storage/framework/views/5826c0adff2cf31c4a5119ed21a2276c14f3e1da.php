<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
	<?php echo e(method_field($method)); ?>

	<?php endif; ?>
	<?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="<?php echo e($type); ?>" required="required">
				<?php if(!empty($advanceNotice->created_at)): ?>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Project</label>
					<div class="col-sm-9">
						<p class="form-control-static"><?php echo e($advanceNotice->project->name); ?></p>
					</div>
				</div>
				<div class="form-group">
					<label for="created_at" class="col-sm-3 control-label"><?php echo e(__('lang.created_at')); ?></label>
					<div class="col-sm-9">
						<p class="form-control-static"><?php echo e($advanceNotice->created_at); ?></p>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group required">
					<label for="contract_number" class="col-sm-3 control-label"><?php echo e(__('lang.contract')); ?></label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"><?php echo e($advanceNotice->contract_number); ?></p>
							<input type="hidden" name="contract_number" value="<?php echo e($advanceNotice->contract_number); ?>" required="required">
						<?php else: ?>
							<select name="contract_number" id="inputcontract" class="form-control select2" required="required">
	                            <option value="">-- <?php echo e(__('lang.choose')); ?> Kontrak --</option>
	                            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                            <option value="<?php echo e($contract->id); ?>" <?php echo e(old('contract_number', $advanceNotice->contract_number) == $contract->number_contract ? 'selected' : ''); ?>><?php echo e($contract->number_contract); ?></option>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                        </select>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group required">
					<label for="spmp_number" class="col-sm-3 control-label"><?php echo e(__('lang.spk')); ?></label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"><?php echo e($advanceNotice->spmp_number); ?></p>
							<input type="hidden" name="spmp_number" value="<?php echo e($advanceNotice->spmp_number); ?>" required="required">
						<?php else: ?>
							<select name="spmp_number" id="spk_number" class="form-control" required="required">
	                    		<option value="">-- <?php echo e(__('lang.choose')); ?> No. SPK --</option>
	                    	</select>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
		            <label for="an_doc" class="col-sm-3 control-label">
		            	<?php if($type == 'inbound'): ?> 
		            		PO <?php echo e(__('lang.document')); ?>

		            	<?php else: ?>
		            		DO <?php echo e(__('lang.document')); ?>

		            	<?php endif; ?>
		            </label>
		            <div class="col-sm-9">
		            	<?php if($method != 'PUT'): ?>
		                	<input type="file" name="an_doc" class="form-control" placeholder="an_doc" value="<?php echo e(old('an_doc', $advanceNotice->an_doc)); ?>">
		                <?php endif; ?>
		                <?php if(!empty($advanceNotice->an_doc)): ?>
		                	<a href="<?php echo e(\Storage::disk('public')->url($advanceNotice->an_doc)); ?>" target="_blank"><?php echo e(__('lang.see_doc')); ?></a>
		                <?php endif; ?>
		            </div>
		        </div>
				<div class="form-group required">
					<label for="advance_notice_activity_id" class="col-sm-3 control-label"><?php echo e(__('lang.activity')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="advance_notice_activity_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.activity')); ?> --</option>
							<?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->advance_notice_activity_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group required">
					<label for="transport_type_id" class="col-sm-3 control-label"><?php echo e(__('lang.type_transpotation')); ?></label>
					<div class="col-sm-9">
						<select class="form-control" name="transport_type_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.type_transpotation')); ?> --</option>
							<?php $__currentLoopData = $transport_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->transport_type_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="owner" class="col-sm-3 control-label"><?php echo e(__('lang.ownership')); ?> </label>
					<div class="col-sm-9">
						<select class="form-control select2" name="owner" <?php echo e($method != 'PUT' ?'': 'disabled'); ?>>
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?>  <?php echo e(__('lang.ownership')); ?>  --</option>
							<?php $__currentLoopData = $parties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($value->id); ?>" <?php if($advanceNotice->owner == $value->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="pic" class="col-sm-3 control-label">PIC</label>
					<div class="col-sm-9">
						<?php if($method != 'PUT'): ?>
							<select id="selectpic" class="form-control select2" name="pic">
								<option value="" selected disabled>--<?php echo e(__('lang.choose')); ?>  PIC / <?php echo e(__('lang.manual')); ?>  --</option>
								<?php $__currentLoopData = $pic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						<?php else: ?>
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="pic" class="form-control" placeholder="PIC" value="<?php echo e(old('pic', $advanceNotice->pic)); ?>">
						<?php endif; ?>
					</div>
				</div>
				
				<div class="form-group required">
					<label for="sptb_num" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?> </label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="ref_code" class="form-control" placeholder="<?php echo e(__('lang.customer_reference')); ?>" value="<?php echo e(old('ref_code', $advanceNotice->ref_code)); ?>" required="required">
					</div>
				</div>
			
                <?php if(!empty($advanceNotice->status)): ?>
					<div class="form-group required">
						<label for="status" class="col-sm-3 control-label">Status</label>
						<div class="col-sm-9">
							<p class="form-control-static">
								<?php if(!Auth::user()->hasRole('CargoOwner')): ?>
								<?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status); ?>

								<?php else: ?>
								<?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status)); ?>

								<?php endif; ?>
							</p>
						</div>
					</div>
                <?php endif; ?>
			</div>
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="origin_id" class="col-sm-3 control-label"><?php echo e(__('lang.origin')); ?></label>
					<div class="col-sm-9">
						<select class="form-control select2" name="origin_id" id="origin_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.origin')); ?> --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->origin_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label"><?php echo e(__('lang.etd')); ?></label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="etd" class="datepicker form-control" placeholder="<?php echo e(__('lang.etd')); ?>" value="<?php echo e(old('etd', $advanceNotice->etd)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_id" class="col-sm-3 control-label">
						<?php echo e(__('lang.shipper')); ?>

					</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="shipper_id" id="shipper_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.shipper')); ?></option>
							<?php $__currentLoopData = $shippers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>" <?php if($advanceNotice->shipper_id == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label">
						<?php echo e(__('lang.shipper_address')); ?> 
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="shipper_address" name="shipper_address" class="form-control" placeholder="<?php echo e(__('lang.shipper_address')); ?>" value="<?php echo e(old('shipper_address', $advanceNotice->shipper_address)); ?>" required="required">
					</div>
				</div>
				<?php if((isset($advanceNotice) && $advanceNotice->type == 'outbound') || $type == 'outbound'): ?>
					<div id="warehouse">
						<div class="form-group required">
							<label for="warehouse_id" class="col-sm-3 control-label">
								<?php echo e(__('lang.warehouse')); ?>

							</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="warehouse_id" id="warehouse_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
									<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.warehouse')); ?></option>
									<?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($wh->id); ?>" <?php echo e($wh->id == $advanceNotice->warehouse_id ? 'selected="selected"' : ''); ?>><?php echo e($wh->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?></label>
					<div class="col-sm-9">
						<select class="form-control select2" name="destination_id" id="destination_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> <?php echo e(__('lang.destination')); ?> --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->destination_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label"><?php echo e(__('lang.eta')); ?></label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="eta" class="end-datepicker form-control" placeholder="Est. Time Arrival" value="<?php echo e(old('eta', $advanceNotice->eta)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_id" class="col-sm-3 control-label">
						<?php if($type == 'inbound'): ?> <?php echo e(__('lang.branch')); ?> <?php else: ?> <?php echo e(__('lang.consignee')); ?> <?php endif; ?>
					</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="consignee_id" id="consignee_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- <?php echo e(__('lang.choose')); ?> --</option>
							<?php $__currentLoopData = $consignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>" <?php if($advanceNotice->consignee_id == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_address" class="col-sm-3 control-label">
						<?php if($type == 'inbound'): ?> <?php echo e(__('lang.branch_address')); ?> <?php else: ?> <?php echo e(__('lang.consignee_address')); ?> <?php endif; ?>
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="consignee_address" name="consignee_address" class="form-control" placeholder="Address" value="<?php echo e(old('consignee_address', $advanceNotice->consignee_address)); ?>" required="required">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	
	<?php if(!empty($method)): ?>
	<?php if($method == 'POST'): ?>
	<div class="box-footer">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="disclaimer" required="required"> <?php echo e(__('lang.disclaimer')); ?>

			</label>
		</div>
		<br>
		<button type="submit" class="btn btn-info"><?php echo e(__('lang.save')); ?></button>
	</div>
	<?php elseif($method == 'PUT'): ?>
	<?php endif; ?>
	<?php endif; ?>
	<!-- /.box-footer -->
</form>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function () {
        $('#selectpic').select2({
			tags: true
		});
		$('select[id="inputcontract"]').change(function(){
            var id = $(this).val();
            console.log('id', id);
            $.ajax({
                type: "GET",
                url: "/advance_notices/getspk/" + id,
                success: function (data) {
                    $('select[id="spk_number"]').empty();
                    console.log('data', data);
                    $('select[id="spk_number"]').append('<option value="">-- Pilih No. SPK --</option>');
                    $.each(data, function (key, value) {
                    	if(key == 0){
                    		$('select[id="spk_number"]').append('<option value="' + value.number_spk + '" selected>' + value.number_spk + '</option>');
                    	}
                    	else{
                    		$('select[id="spk_number"]').append('<option value="' + value.number_spk + '">' + value.number_spk + '</option>');
                    	}
                        
                    });
                }
            });
        });
        <?php
        	if($type == 'inbound'){
        ?>
	        //ain destination hanya divre
	        $('select[name="destination_id"]').on('change', function(){
	            var city_id = $(this).val();
	            var party_type = 'consignee,branch';

	            $.ajax({
	                type: 'GET',
	                url: '/parties/get-list',
	                data: {
	                    city_id: city_id,
	                    party_type: party_type,
	                    name: 'consignee_id',
	                    id: 'consignee_id',
	                },
	                success:function(responseHtml) {
	                    $('select[name="consignee_id"]').html(responseHtml);
	                }
	            })
	        });
	    <?php
			}
	    	else{
	   	?>

	    	//ain origin hanya divre
	        $('select[name="origin_id"]').on('change', function(){
	            var city_id = $(this).val();
	            var party_type = 'shipper,branch';

	            $.ajax({
	                type: 'GET',
	                url: '/parties/get-list',
	                data: {
	                    city_id: city_id,
	                    party_type: party_type,
	                    name: 'shipper_id',
	                    id: 'shipper_id',
	                },
	                success:function(responseHtml) {
	                    $('select[name="shipper_id"]').html(responseHtml);
	                }
	            })
	        });


			$('select[name="shipper_id"]').on('change', function(){
				var contract_id = $('#inputcontract').val();
				var shipper_id = $(this).val();

	            $.ajax({
	                type: 'GET',
	                url: '/warehouses/get-list',
	                data: {
	                    shipper_id: shipper_id,
						contract_id: contract_id
	                },
	                success:function(responseHtml) {
	                    $('#warehouse').html(responseHtml);
	                }
	            })
			});

	    <?php
	    	}
	   	?>
    });


</script>
<?php $__env->stopSection(); ?>