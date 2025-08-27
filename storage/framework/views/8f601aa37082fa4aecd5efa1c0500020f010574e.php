<!-- form start -->
<form id="changeStock" action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Warehouse</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
							<p class="form-control-static"><?php echo e($warehouse->code); ?> - <?php echo e($warehouse->name); ?></p>
							<?php else: ?>
								<select name="warehouse" id="warehouses" class="form-control select2" required="">
								</select>
							<?php endif; ?>
						<?php else: ?>
							<p class="form-control-static"><?php echo e($stock_opname->warehouse->code); ?> - <?php echo e($stock_opname->warehouse->name); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Kota/Kabupaten</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
							<p class="form-control-static"><?php echo e($warehouse->city->name); ?></p>
							<?php else: ?>
							<p class="form-control-static" id="city_id"></p>
							<?php endif; ?>
						<?php else: ?>
						<p class="form-control-static"><?php echo e($stock_opname->warehouse->city->name); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Alamat</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
								<p class="form-control-static"><?php echo e($warehouse->address); ?></p>
							<?php else: ?>
							<p class="form-control-static" id="address"></p>
							<?php endif; ?>
						<?php else: ?>
						<p class="form-control-static"><?php echo e($stock_opname->warehouse->address); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Kapasitas</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
							<p class="form-control-static"><?php echo e(number_format($warehouse->length * $warehouse->width, 2, ',', '.')); ?></p>
							<?php else: ?>
							<p class="form-control-static" id="capacity"></p>
							<?php endif; ?>
						<?php else: ?>
						<p class="form-control-static"><?php echo e(number_format($stock_opname->warehouse->length * $stock_opname->warehouse->width, 2, ',', '.')); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Tanggal dan Waktu</label>
					<div class="col-sm-9">
					<?php if($method == 'POST'): ?>
					<input type="text" name="date_stock_opname" id="datetimepickerdisable" class="form-control" placeholder="" value="<?php echo e(old('pickup_order')); ?>"  required>
					<?php else: ?>
					<p class="form-control-static"><?php echo e($stock_opname->date); ?></p>
					<?php endif; ?>
					</div>
				</div>
				<?php if($method == 'POST'): ?>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Spv. Warehouse</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<input type="text" class="form-control" required name="anggota[]">
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Lead Warehouse</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<input type="text" class="form-control" required name="anggota[]">
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
				<label for="project_id" class="col-sm-3 control-label">Cheker</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
							<input type="text" class="form-control" required name="anggota[]">
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Anggota Tambahan</label>
					<div class="col-sm-9">
						<?php if($method == 'POST'): ?>
						<div class="customer_records card">
							<input type="text" class="form-control" name="anggota[]">
							<a class="extra-fields-customer" href="#">Tambah Anggota</a>
						</div>
						<?php endif; ?>
					
						<div class="customer_records_dynamic card"></div>

						</div>
					</div>
				</div>
				<?php else: ?>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Dihitung Oleh</label>
					<div class="col-sm-9">
						<ul>
						<?php if($stock_opname->calculated_by != null): ?>
						<?php $__currentLoopData = json_decode($stock_opname->calculated_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<li class="form-control-static"><?php echo e($value); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<?php if($method == 'POST'): ?>
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<?php endif; ?>
	<!-- /.box-footer -->
    <input type="hidden" id="report_warehouse" value="<?php echo e(route('warehouse_stockopname')); ?>">

</form>
<?php $__env->startSection('custom_script'); ?>
<script>
		$('.extra-fields-customer').click(function() {
        $('.customer_records').clone().appendTo('.customer_records_dynamic');
        $('.customer_records_dynamic .customer_records').addClass('single remove');
        $('.single .extra-fields-customer').remove();
        $('.single').append('<a href="#" class="remove-field btn-remove-customer">Remove Fields</a>');
        $('.customer_records_dynamic > .single').attr("class", "remove");
        $('.customer_records_dynamic select').attr("class", "form-control");    
        $('.customer_records_dynamic .selection').remove();
        $('.customer_records_dynamic .select2-container ').remove();
        $('select').select2({
            tags: true
        })
        
        $('.customer_records_dynamic input select').each(function() {
            var count = 0;
            var fieldname = $(this).attr("name");
            $(this).attr('name', fieldname + count);
            count++;
        });


        });

        $(document).on('click', '.remove-field', function(e) {
            $(this).parent('.remove').remove();
            e.preventDefault();
        });

		$.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $('#warehouses').append("<option value='' disabled selected>Pilih Warehouse</option>");
                $.each(data,function(i, value){
					$("#warehouses").append("<option value='"+value.id+"'>"+value.code +'-'+ value.name +"</option>");
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
		$('#warehouses').change(function() {
            $("#item").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val()
                },
                dataType: 'json',
                success: function(data) { 
					console.log(data);
					$('#city_id').text(data.city_name)
					$('#address').text(data.address)
					$('#capacity').text(data.total_weight)
                }
            });
        });
</script>
<?php $__env->stopSection(); ?>