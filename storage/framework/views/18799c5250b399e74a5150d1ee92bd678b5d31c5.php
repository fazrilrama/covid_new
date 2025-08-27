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
					<label for="created_at" class="col-sm-3 control-label">Tanggal Dibuat</label>
					<div class="col-sm-9">
						<p class="form-control-static"><?php echo e($advanceNotice->created_at); ?></p>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group required">
					<label for="contract_number" class="col-sm-3 control-label">No. Kontrak</label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"><?php echo e($advanceNotice->contract_number); ?></p>
							<input type="hidden" name="contract_number" value="<?php echo e($advanceNotice->contract_number); ?>" required="required">
						<?php else: ?>
							<select name="contract_number" id="inputcontract" class="form-control" required="required">
	                            <option value="">-- Pilih Kontrak --</option>
	                            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                            <option value="<?php echo e($contract->id); ?>" <?php echo e(old('contract_number', $advanceNotice->contract_number) == $contract->number_contract ? 'selected' : ''); ?>><?php echo e($contract->number_contract); ?></option>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                        </select>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group required">
					<label for="spmp_number" class="col-sm-3 control-label">No. SPK</label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"><?php echo e($advanceNotice->spmp_number); ?></p>
							<input type="hidden" name="spmp_number" value="<?php echo e($advanceNotice->spmp_number); ?>" required="required">
						<?php else: ?>
							<select name="spmp_number" id="spk_number" class="form-control" required="required">
	                    		<option value="">-- Pilih No. SPK --</option>
	                    	</select>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group required">
					<label for="advance_notice_activity_id" class="col-sm-3 control-label">Jenis Kegiatan</label>
					<div class="col-sm-9">
						<select class="form-control" name="advance_notice_activity_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Jenis Kegiatan --</option>
							<?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->advance_notice_activity_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="transport_type_id" class="col-sm-3 control-label">Moda Transportasi</label>
					<div class="col-sm-9">
						<select class="form-control" name="transport_type_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Jenis Transportasi --</option>
							<?php $__currentLoopData = $transport_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->transport_type_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<!-- <div class="form-group required">
					<label for="sptb_num" class="col-sm-3 control-label">SPTB</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="sptb_num" class="form-control" placeholder="SPTB" value="<?php echo e(old('sptb_num', $advanceNotice->sptb_num)); ?>" required="required">
					</div>
				</div> -->
				<div class="form-group required">
					<label for="sptb_num" class="col-sm-3 control-label">Customer Reference</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="ref_code" class="form-control" placeholder="Customer Reference" value="<?php echo e(old('ref_code', $advanceNotice->ref_code)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="sppk_num" class="col-sm-3 control-label">SPPK</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="sppk_num" class="form-control" placeholder="SPPK" value="<?php echo e(old('sppk_num', $advanceNotice->sppk_num)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="sppk_doc" class="col-sm-3 control-label">File SPPK</label>
					<div class="col-sm-9">
						<?php if($method != 'PUT'): ?>
						<input type="file" name="sppk_doc" class="form-control" placeholder="SPTB" value="<?php echo e(old('sppk_doc', $advanceNotice->sppk_doc)); ?>" required="required">
						<?php else: ?>
						<a href="<?php echo e(\Storage::disk('public')->url($advanceNotice->sppk_doc)); ?>" target="_blank">Lihat Dokumen</a>
						<?php endif; ?>
					</div>
				</div>
				<?php if(!Auth::user()->hasRole('CargoOwner')): ?>
					<div class="form-group required">
						<label for="employee_name" class="col-sm-3 control-label">Warehouse Supervisor #</label>
						<div class="col-sm-9">
							<?php if($method == 'PUT'): ?>
							<input type="text" id="employee_name" name="employee_name" readonly class="form-control" placeholder="Warehouse Supervisor" value="<?php echo e(old('employee_name', $advanceNotice->employee_name )); ?>" required="required">
							<?php else: ?>
							<select class="form-control" name="employee_name" id="employee_name" required required="required">
								<option value="" selected disabled>-- Pilih Warehouse Supervisor --</option>
								<?php $__currentLoopData = $warehouseOfficers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouseOfficer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($warehouseOfficer->first_name); ?>" <?php if(old('employee_name', $advanceNotice->employee_name) == $advanceNotice->employee_name): ?>)<?php echo e('selected'); ?><?php endif; ?>><?php echo e($warehouseOfficer->first_name); ?></option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<?php if($advanceNotice->warehouse): ?>
						<div class="form-group">
							<label for="ref_code" class="col-sm-3 control-label">Warehouse</label>
							<div class="col-sm-9">
								<p class="form-control-static"><?php echo e($advanceNotice->warehouse->name); ?></p>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
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
					<label for="origin_id" class="col-sm-3 control-label">Origin</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="origin_id" id="origin_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Kota Asal --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->origin_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label">Est. Time Delivery</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="etd" class="datepicker form-control" placeholder="Est. Time Delivery" value="<?php echo e(old('etd', $advanceNotice->etd)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_id" class="col-sm-3 control-label">
						Shipper
					</label>
					<div class="col-sm-9">
						<select class="form-control" name="shipper_id" id="shipper_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Shipper</option>
							<?php $__currentLoopData = $shippers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>" <?php if($advanceNotice->shipper_id == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label">
						Shipper Address
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="shipper_address" name="shipper_address" class="form-control" placeholder="Address" value="<?php echo e(old('shipper_address', $advanceNotice->shipper_address)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label">Destination</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="destination_id" id="destination_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Kota Tujuan --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if($advanceNotice->destination_id == $id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label">Est. Time Arrival</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="eta" class="end-datepicker form-control" placeholder="Est. Time Arrival" value="<?php echo e(old('eta', $advanceNotice->eta)); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_id" class="col-sm-3 control-label">
						<?php if($type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?>
					</label>
					<div class="col-sm-9">
						<select class="form-control" name="consignee_id" id="consignee_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih --</option>
							<?php $__currentLoopData = $consignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>" <?php if($advanceNotice->consignee_id == $party->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_address" class="col-sm-3 control-label">
						<?php if($type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?> Address
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="consignee_address" name="consignee_address" class="form-control" placeholder="Address" value="<?php echo e(old('consignee_address', $advanceNotice->consignee_address)); ?>" required="required">
					</div>
				</div>

				<!-- <div class="form-group required">
					<label for="annotation" class="col-sm-3 control-label">
						Annotation
					</label>
					<div class="col-sm-9">
						<textarea <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="annotation" name="annotation" class="form-control" placeholder="Annotation" required="required"><?php echo e(old('annotation', $advanceNotice->annotation)); ?></textarea>
					</div>
				</div>

				<div class="form-group required">
					<label for="contractor" class="col-sm-3 control-label">
						Contractor
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="contractor" name="contractor" class="form-control" placeholder="Contractor" value="<?php echo e(old('contractor', $advanceNotice->contractor)); ?>" required="required">
					</div>
				</div> -->

				<!-- <div class="form-group required">
					<label for="contractor" class="col-sm-3 control-label">
						Head Cabang/Subcabang
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="head_ds" name="head_ds" class="form-control" placeholder="Head Cabang/Subcabang" value="<?php echo e(old('head_ds', $advanceNotice->head_ds)); ?>" required="required">
					</div>
				</div>				 -->
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	
	<?php if(!empty($method)): ?>
	<?php if($method == 'POST'): ?>
	<div class="box-footer">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="disclaimer" required="required"> Saya menjamin bahwa data yang saya masukan adalah benar
			</label>
		</div>
		<br>
		<button type="submit" class="btn btn-info">Simpan</button>
	</div>
	<?php elseif($method == 'PUT'): ?>
	<?php endif; ?>
	<?php endif; ?>
	<!-- /.box-footer -->
</form>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function () {
        $('select[id="inputcontract"]').change(function(){
            var id = $(this).val();
            console.log('id', id);
            $.ajax({
                type: "GET",
                url: "/stock_transfer_order/getspk/" + id,
                success: function (data) {
                    $('select[id="spk_number"]').empty();
                    console.log('data', data);
                    $('select[id="spk_number"]').append('<option value="" selected>-- Pilih No. SPK --</option>');
                    $.each(data, function (key, value) {
                        $('select[id="spk_number"]').append('<option value="' + value.number_spk + '">' + value.number_spk + '</option>');
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
	            var party_type = 'branch';

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
	            var party_type = 'branch';

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
	    <?php
	    	}
	   	?>
    });


</script>
<?php $__env->stopSection(); ?>