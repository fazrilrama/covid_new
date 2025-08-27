<!-- form start -->
<form id="changeStock" action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body col-sm-7">
		<div class="form-group">
			<label for="stock_advance_notice_id" class="col-sm-3 control-label">
				Internal Movement
			</label>
			<div class="col-sm-9">
				<p class="form-control-static"><?php echo e($stock_internal->code); ?></p>
				<input type="hidden" name="internal_movement_id" value="<?php echo e($stock_internal->id); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Storage</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="storages" id="storages" style="width: 100% !important;"  required>
					<option value="" disabled selected>-- Pilih Storage --</option>
					<?php if($method == 'POST'): ?>
                    <?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($storage->id); ?> "><?php echo e($storage->code); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
					<?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($storage->id); ?>" <?php echo e($stock_internal_movement_detail->origin_storage_id == $storage->id ? 'selected' : ''); ?>><?php echo e($storage->code); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endif; ?>
				</select>
                <input type="hidden" name="stock">
			</div>
		</div>
		<div class="detail_container_wrapper">
		<?php if($method == 'PUT'): ?>
			<div class="form-group">
				<label for="item_id" class="col-sm-3 control-label">Item</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="item" id="item" style="width: 100% !important;"  required>
						<?php if(!$detail): ?>
						<option value="<?php echo e($stock_internal_movement_detail->item_id); ?>"  data-code="<?php echo e($stock_internal_movement_detail->ref_code); ?>" selected><?php echo e($stock_internal_movement_detail->item->sku); ?> - <?php echo e($stock_internal_movement_detail->item->name); ?> | <?php echo e($stock_internal_movement_detail->ref_code); ?> | 0 </option>
						<?php $__currentLoopData = $origin_storage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($storage['item']); ?>" data-code="<?php echo e($storage['ref_code']); ?>" <?php echo e($storage['ref_code'] == $stock_internal_movement_detail->ref_code && $storage['item'] == $stock_internal_movement_detail->item_id ? 'selected' : ''); ?> ><?php echo e($storage['sku']); ?> | <?php echo e($storage['ref_code']); ?> | <?php echo e($storage['stock']); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php else: ?>
						<?php $__currentLoopData = $origin_storage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($storage['item']); ?>" data-code="<?php echo e($storage['ref_code']); ?>" <?php echo e($storage['ref_code'] == $stock_internal_movement_detail->ref_code && $storage['item'] == $stock_internal_movement_detail->item_id ? 'selected' : ''); ?> ><?php echo e($storage['sku']); ?> - <?php echo e($storage['sku_name']); ?> | <?php echo e($storage['ref_code']); ?> | <?php echo e($storage['stock']); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</select>
						
				</div>
			</div>
		<?php endif; ?>
		</div>
		<!-- <input type="hidden" name="storage_id" value="<?php echo e(old('storage_id')); ?>" /> -->
		<div class="content_wrapper">
		<?php if($method == 'PUT'): ?>
			<div class="form-group required">
				<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
				<div class="col-sm-9">  
					<input type="text" name="ref_code" value="<?php echo e($stock_internal_movement_detail->ref_code); ?>" class="form-control" placeholder="Ref Code" required readonly>
				</div>
			</div>
			<div class="form-group required">
				<label for="group_reference" class="col-sm-3 control-label">Stock Saat Ini</label>
				<div class="col-sm-9">  
					<input type="text" name="stock" value="<?php echo e($detail ? $detail[0]['stock'] : '0'); ?>" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group required">
				<label for="qty" class="col-sm-3 control-label">Qty</label>
				<div class="col-sm-9">
				<input type="number" step="0.01" id="qty_movement" name="qty_movement" class="form-control" placeholder="qty"  value="<?php echo e($stock_detial); ?>" required>
				</div>
				<?php if($method == 'PUT'): ?>
					
					<input type="hidden" step="0.01" name="qty_old" value="">
				<?php endif; ?>
			</div>
			<div class="form-group">
				<label for="destination_storage" class="col-sm-3 control-label">Destination Storage</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="dest_storage" id="dest_storage" style="width: 100% !important;"  required>
						<option value="" selected disabled>-- Pilih Destination Storage --</option>
						<?php $__currentLoopData = $storages_exeption; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($storage->id); ?>" <?php echo e($storage->id == $stock_internal_movement_detail->dest_storage_id ? 'selected' : ''); ?>><?php echo e($storage->code); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
						
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-info pull-right">Simpan</button>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<!-- /.box-body -->
	
	<!-- /.box-footer -->
</form>
<input type="hidden" id="method" value="<?php echo e($method); ?>">
<div id="detail-url" url="#" data-url="<?php echo e(route('stock_internal_movement_details.storage', ':id')); ?>"></div>
<template id="detail_container_render">
<div class="form-group">
	<label for="item_id" class="col-sm-3 control-label">Item</label>
	<div class="col-sm-9">
		<select class="form-control select2" name="item" id="item" style="width: 100% !important;"  required>
			<option value="" selected disabled>-- Pilih Item/Ref Code --</option>
			{{ #dataDetail}}
			<option value="{{ item }}" data-code="{{ ref_code }}">{{ sku }} - {{ sku_name  }} | {{ ref_code }} | {{ stock }}</option>
			{{/dataDetail}}
		</select>
			
	</div>
</div>
</template>
<template id="ref_code_detail_render">
	{{ #dataDetail}}
	<div class="form-group required">
		<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
		<div class="col-sm-9">  
			<input type="text" name="ref_code" value="{{ ref_code }}" class="form-control" placeholder="Ref Code" readonly>
		</div>
	</div>
	<div class="form-group required">
		<label for="group_reference" class="col-sm-3 control-label">Stock Saat Ini</label>
		<div class="col-sm-9">  
			<input type="text" name="stock" value="{{ stock }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group required">
		<label for="qty" class="col-sm-3 control-label">Qty</label>
		<div class="col-sm-9">
			<input type="number" step="0.01" id="qty_movement" name="qty_movement" class="form-control" placeholder="qty"  value="{{ stock }}" required>
		</div>
		<?php if($method == 'PUT'): ?>
			<input type="hidden" step="0.01" name="old_qty" value="" max="{{ stock }}" required>
		<?php endif; ?>
	</div>
	<div class="form-group">
		<label for="destination_storage" class="col-sm-3 control-label">Destination Storage</label>
		<div class="col-sm-9">
			<select class="form-control select2" name="dest_storage" id="dest_storage" style="width: 100% !important;"  required>
				<option value="" selected disabled>-- Pilih Destination Storage --</option>
				{{ #storage}}
				<option value="{{ id }}">{{ code }}</option>
				{{/storage}}
			</select>
				
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	{{/dataDetail}}
</template>


<?php $__env->startSection('custom_script'); ?>
	<script src='<?php echo e(asset('vendor/mustache/js/mustache.min.js')); ?>'></script>
    <script src="<?php echo e(asset('vendor/replaceSymbol/replaceSymbol.js')); ?>"> </script>
	
    <script>
		$('#storages').on('change', function(){
			$('.select2').select2();
			var url = replaceSymbol('#detail-url', 'data-url', $(this).val());
            $.get(url, function (data, textStatus, xhr) {
                $('.detail_container_wrapper').html('');
                $('.detail_container_wrapper').append(Mustache.render($('#detail_container_render').html(), {
                    dataDetail : data
                }));
				$('.content_wrapper').html('');	
				$('#item').on('change', function(){
					$('.select2').select2();
					// alert($(this).find(':selected').data('code'));
					optional = '?item_id='+$(this).val() +'&ref_code='+$(this).find(':selected').data('code')
					$.get(url+''+optional, function (data, textStatus, xhr) {
						$('.content_wrapper').html('');	
						$('.content_wrapper').append(Mustache.render($('#ref_code_detail_render').html(), {
							dataDetail : data
						}));
					});
				});
            });
			
		});
		$('#item').on('change', function(){
			$('.select2').select2();
			// alert($(this).find(':selected').data('code'));
			var url = replaceSymbol('#detail-url', 'data-url', $(this).val());
			optional = '?item_id='+$(this).val() +'&ref_code='+$(this).find(':selected').data('code')

			$('.content_wrapper').html('');	
			$.get(url+''+optional, function (data, textStatus, xhr) {
				$('.content_wrapper').html('');	
				$('.content_wrapper').append(Mustache.render($('#ref_code_detail_render').html(), {
					dataDetail : data
				}));
			});
		});
		
	</script>

<?php $__env->stopSection(); ?>