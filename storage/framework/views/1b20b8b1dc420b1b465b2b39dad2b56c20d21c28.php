<!-- form start -->
<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
	<?php echo e(method_field($method)); ?>

	<?php endif; ?>
	<?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Project</label>
					<div class="col-sm-9">
						<p class="form-control-static"></p>
					</div>
				</div>
				<div class="form-group">
					<label for="created_at" class="col-sm-3 control-label">Tanggal Dibuat</label>
					<div class="col-sm-9">
						<p class="form-control-static"></p>
					</div>
				</div>
				<div class="form-group required">
					<label for="contract_number" class="col-sm-3 control-label">No. Kontrak</label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"></p>
							<input type="hidden" name="contract_number" value="" required="required">
						<?php else: ?>
							<select name="contract_number" id="inputcontract" class="form-control" required="required">
	                            <option value="">-- Pilih Kontrak --</option>
	                            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                            <option value="<?php echo e($contract->id); ?>" ><?php echo e($contract->number_contract); ?></option>
	                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                        </select>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group required">
					<label for="spmp_number" class="col-sm-3 control-label">No. SPK</label>
					<div class="col-sm-9">
						<?php if($method == 'PUT'): ?>
							<p class="form-control-static"></p>
							<input type="hidden" name="spmp_number" value="" required="required">
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
							<option value="<?php echo e($id); ?>" ><?php echo e($value); ?></option>
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
							<option value="<?php echo e($id); ?>" ><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group required">
					<label for="sptb_num" class="col-sm-3 control-label">Customer Reference</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" name="ref_code" class="form-control" placeholder="Customer Reference" value="<?php echo e(old('ref_code' )); ?>" required="required">
					</div>
				</div>
				
			</div>
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="origin_id" class="col-sm-3 control-label">Origin</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="origin_id" id="origin_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Kota Asal --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>"><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label">Est. Time Delivery</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="etd" class="datepicker form-control" placeholder="Est. Time Delivery" value="<?php echo e(old('etd')); ?>" required="required">
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
							<option value="<?php echo e($party->id); ?>" ><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label">
						Shipper Address
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="shipper_address" name="shipper_address" class="form-control" placeholder="Address" value="<?php echo e(old('shipper_address')); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label">Destination</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="destination_id" id="destination_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih Kota Tujuan --</option>
							<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>"><?php echo e($value); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label">Est. Time Arrival</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'disabled'); ?> type="text" name="eta" class="end-datepicker form-control" placeholder="Est. Time Arrival" value="<?php echo e(old('eta')); ?>" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_id" class="col-sm-3 control-label">
						Penerima barang
					</label>
					<div class="col-sm-9">
						<select class="form-control" name="consignee_id" id="consignee_id" <?php echo e($method != 'PUT' ?'': 'disabled'); ?> required="required">
							<option value="" disabled selected>-- Pilih --</option>
							<?php $__currentLoopData = $consignees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $party): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($party->id); ?>"><?php echo e($party->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_address" class="col-sm-3 control-label">
						Penerima barang Address
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="consignee_address" name="consignee_address" class="form-control" placeholder="Address" value="<?php echo e(old('consignee_address')); ?>" required="required">
					</div>
				</div>

				<!-- <div class="form-group required">
					<label for="annotation" class="col-sm-3 control-label">
						Annotation
					</label>
					<div class="col-sm-9">
						<textarea <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="annotation" name="annotation" class="form-control" placeholder="Annotation" required="required"><?php echo e(old('annotation')); ?></textarea>
					</div>
				</div>

				<div class="form-group required">
					<label for="contractor" class="col-sm-3 control-label">
						Contractor
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="contractor" name="contractor" class="form-control" placeholder="Contractor" value="<?php echo e(old('contractor' )); ?>" required="required">
					</div>
				</div> -->

				<!-- <div class="form-group required">
					<label for="contractor" class="col-sm-3 control-label">
						Head Cabang/Subcabang
					</label>
					<div class="col-sm-9">
						<input <?php echo e($method != 'PUT' ?'': 'readonly'); ?> type="text" id="head_ds" name="head_ds" class="form-control" placeholder="Head Cabang/Subcabang" value="<?php echo e(old('head_ds' )); ?>" required="required">
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
                url: "/advance_notices/getspk/" + id,
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
	    
    });


</script>
<?php $__env->stopSection(); ?>