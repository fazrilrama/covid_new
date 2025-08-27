<!-- form start -->
<form id="changeStock" action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
	<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
        <?php echo e(method_field($method)); ?>

    <?php endif; ?>
    <?php echo csrf_field(); ?>
	<div class="box-body col-sm-7">
		<div class="form-group">
			<label for="stock_advance_notice_id" class="col-sm-3 control-label">
				<?php echo e($advanceNoticeDetail->header->type == 'outbound' ? 'AON' : 'AIN'); ?> #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static"><?php echo e($advanceNoticeDetail->header->code); ?></p>
				<input type="hidden" name="stock_advance_notice_id" value="<?php echo e($advanceNoticeDetail->stock_advance_notice_id); ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Item</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="item_and" id="item_and" style="width: 100% !important;"  required>
					<option value="" disabled selected>-- Pilih Item --</option>
                    <?php if($advanceNotice->type == 'inbound'): ?> 
    					<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($itemValue->id); ?>" <?php if(old('item_and', $advanceNoticeDetail->item_id) == $itemValue->id): ?><?php echo e('selected'); ?><?php endif; ?>>
                                <?php echo e($itemValue->sku); ?> - <?php echo e($itemValue->name); ?> 
                            </option>
    					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option data-ref-code="<?php echo e($itemValue->ref_code); ?>" data-stock="<?php echo e($itemValue->qty); ?>" value="<?php echo e($itemValue->item_id); ?>" <?php if(old('item_and', $advanceNoticeDetail->item_id) == $itemValue->item_id): ?><?php echo e('selected'); ?><?php endif; ?>>
                                <?php echo e($itemValue->item->sku); ?> - <?php echo e($itemValue->item->name); ?> - <?php echo e($itemValue->ref_code); ?> 
                                <!-- - <?php echo e($itemValue->storage->code); ?>  -->
                                <?php if($advanceNoticeDetail->header->type == 'outbound'): ?> 
                                    (Outstanding dari Stock Allocation:
                                        <?php echo e($itemValue->stock); ?>

                                    ) 
                                <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
				</select>
                <input type="hidden" name="stock">
			</div>
		</div>
        <!-- <input type="hidden" name="storage_id" value="<?php echo e(old('storage_id')); ?>" /> -->
		<div class="form-group required">
			<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">  
                <?php if($advanceNotice->type == 'inbound'): ?> 
            	   <input type="text" name="group_references" class="form-control" placeholder="Group Reference" value="<?php echo e(old('group_references', $advanceNoticeDetail->ref_code)); ?>" required>
                <?php else: ?>
                    <input type="text" name="group_references" class="form-control" placeholder="Group Reference" value="<?php echo e(old('group_references', $advanceNoticeDetail->ref_code)); ?>" required readonly>
                <?php endif; ?>
            </div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<input type="text" name="uom_name" class="form-control" value="<?php echo e(old('uom_name', @$advanceNoticeDetail->uom->name)); ?>" readonly>
				<input type="hidden" name="uom_id" value="<?php echo e(old('uom_id', @$advanceNoticeDetail->uom_id)); ?>">
			</div>
		</div>
		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" id="qty_change_and" name="qty_change_and" class="form-control" placeholder="qty"  value="<?php echo e(old('qty_change_and', $advanceNoticeDetail->qty)); ?>" required>
			</div>
            <?php if($method == 'PUT'): ?>
                <input type="hidden" step="0.01" name="old_qty" value="<?php echo e(old('old_qty', $old_qty)); ?>" required>
            <?php endif; ?>
		</div>

		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="weight" class="form-control" placeholder="Weight" value="<?php echo e(old('weight', $advanceNoticeDetail->weight)); ?>" required readonly>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="volume" class="form-control" placeholder="Volume" value="<?php echo e(old('volume', $advanceNoticeDetail->volume)); ?>" required readonly>
			</div>
		</div>
        <?php if($method == 'PUT'): ?>
            <input type="hidden" name="weight_fix" value="<?php echo e(old('weight_fix',$advanceNoticeDetail->item->weight)); ?>">
            <input type="hidden" name="volume_fix" value="<?php echo e(old('volume_fix',$advanceNoticeDetail->item->volume)); ?>">
        <?php else: ?>
            <input type="hidden" name="weight_fix" value="<?php echo e(old('weight_fix')); ?>">
            <input type="hidden" name="volume_fix" value="<?php echo e(old('volume_fix')); ?>">
        <?php endif; ?>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Simpan</button>
        </div>
	</div>
	<!-- /.box-body -->
	
	<!-- /.box-footer -->
</form>

<?php $__env->startSection('js'); ?>
    
    <script>
    	
        $(document).ready(function () {
        	$('select[name="item_and"]').change(function(){
                
                var item_ref_code = $("select[name='item_and'] option:selected").attr('data-ref-code');
                var stock = $("select[name='item_and'] option:selected").attr('data-stock');
                var id = $(this).val();
                $('input[name="stock"]').val(stock);
                //var item_storage_id = $("select[name='item_and'] option:selected").attr('data-storage-id');
                console.log('id', id);
                $.ajax({
                    type: "GET",
                    url: "/advance_notice_details/item/" + id,
                    success: function (data) {
                        var qty = $('input[name="qty_change_and"]').val();

                        weight = data.weight;
                        volume = data.volume;
                        uom_id = data.default_uom_id;
                        uom_name = data.uom_name;
                    
                        $('input[name="weight"]').val(weight);
                        $('input[name="volume"]').val(volume);
                        $('input[name="weight_fix"]').val(weight);
                        $('input[name="volume_fix"]').val(volume);
                        $('input[name="uom_id"]').val(uom_id);
                        $('input[name="group_references"]').val(item_ref_code);
                        $('input[name="uom_name"]').val(uom_name);

                        //$('input[name="storage_id"]').val(item_storage_id);
                        
                        var weightNew = weight;
                        var volumeNew = volume;

                        if (qty) {
                            volumeNew = qty * volume;
                            weightNew = qty * weight;
                        }
                        else{
                            volumeNew = 1 * volume;
                            weightNew = 1 * weight;
                        }

                        console.log(volumeNew);

                        $('input[name="volume"]').val(volumeNew.toFixed(2));
                		$('input[name="weight"]').val(weightNew.toFixed(2));
                    }
                });
            });

            var stock = $("select[name='item_and'] option:selected").attr('data-stock');
            $('input[name="stock"]').val(stock);

            $('input[name="qty_change_and"]').keyup(() => { setWeightVolume() });
        
            function setWeightVolume(){
                var weight = $('input[name="qty_change_and"]').val() * $('input[name="weight_fix"]').val();
                var volume = $('input[name="qty_change_and"]').val() * $('input[name="volume_fix"]').val();
                $('input[name="weight"]').val(weight);
                $('input[name="volume"]').val(volume);
            } 
        });

        
    </script>
    

<?php $__env->stopSection(); ?>