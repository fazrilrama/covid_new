<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group">
			<label for="stock_entry_id" class="col-sm-3 control-label">
				{{ $stockEntryDetail->header->type == 'outbound' ? 'Picking Plan' : 'Putaway' }} #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{$stockEntryDetail->header->code}}</p>
				<input type="hidden" name="stock_entry_id" value="{{$stockEntryDetail->stock_entry_id}}">
				<input type="hidden" name="stock_entry_detail_id" value="{{$stockEntryDetail->id}}">
				<input type="hidden" name="stock_transport_id_ajax" value="{{$stockEntryDetail->header->stock_transport_id}}">
                <input type="hidden" name="doc_type" value="{{ $stockEntryDetail->header->type }}">
            </div>
		</div>
		<div class="form-group required">
			<label for="item_id" class="col-sm-3 control-label">Item SKU</label>
			<div class="col-sm-9">
				@if($method == 'PUT')
					<input data-ref-code="{{$stockEntryDetail->ref_code}}" data-control-date="{{$stockEntryDetail->control_date}}" type="hidden" name="item_sed" id="item_sed" value="{{$stockEntryDetail->item_id}}" />

					<input class="form-control" readonly value="{{ $stockEntryDetail->item->sku }} - {{$stockEntryDetail->item->name}} - {{$stockEntryDetail->ref_code}} (Item Maksimal :{{ $stockEntryDetail->item_outstanding}})" />
					

				@elseif($method == 'POST')
					<select class="form-control" name="item_sed" id="item_sed" required>
	                    <option value="">-- Pilih Item SKU --</option>
						@foreach($items as $item)
	                        <option data-ref-code="{{$item->ref_code}}" data-control-date="{{$item->control_date}}" value="{{$item->item_id}}"
	                        	{{ (old('item_sed') == $item->item_id) ? "selected":"" }}>
	                        	{{$item->item->sku}} - {{$item->item->name}} - {{$item->ref_code}}
								(Item Maksimal :{{$item->item_outstanding}}) 
							</option>
						@endforeach
					</select>

					<p class="help-block">Item yang tersedia berdasarkan dokumen referensi</p>
				@endif		
			</div>
		</div>
		<div class="form-group required">
			<label for="ref_code" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">
				<input type="text" name="ref_code" id="inputgroup_reference" class="form-control" value="{{ old('ref_code', $stockEntryDetail->ref_code)  }}" required readonly />                
			</div>
		</div>
		<div class="form-group required">
			<label for="warehouse" class="col-sm-3 control-label">Gudang</label>
			<div class="col-sm-9">

				<input id="warehouse_name" type="text" class="form-control" placeholder="warehouse" 
				@if($stockEntry->warehouse)
					value="{{old('warehouse_name', $stockEntry->warehouse->name)}}"
				@endif readonly>

				<input id="warehouse_id" type="hidden" name="warehouse_id" class="form-control" placeholder="warehouse" value="{{old('warehouse_id', $stockEntry->warehouse_id)}}">
			</div>
		</div>
		

		@if($stockEntry->type == 'outbound' && $method == 'POST')
			<div class="form-group required">
				<label for="storage_id" class="col-sm-3 control-label">Storage</label>
				<div class="col-sm-9">
					<select class="form-control" name="storage_id" required>
						<option value="" disabled>-- Pilih Storage --</option>
					</select>
				</div>
			</div>
		@else
			<div class="form-group required">
				<label for="storage_id" class="col-sm-3 control-label">Storage</label>
				<div class="col-sm-9">
					<select class="form-control" name="storage_id" required>
						<option value="" disabled>-- Pilih Storage --</option>
						@foreach($storages as $storage)
							<option value="{{$storage->id}}" @if(old('storage_id', $stockEntryDetail->storage_id) == $storage->id){{'selected'}}@endif>
								{{$storage->code}} 
							</option>
						@endforeach
					</select>
				</div>
			</div>
		@endif

		@if($stockEntry->type == 'outbound')
			<div class="form-group required">
				<label for="control_date" class="col-sm-3 control-label">Control Date</label>
				<div class="col-sm-9">
					@if($method == 'POST')
			        
						<select class="form-control" name="control_date" required>
							<option value="" disabled>-- Pilih Control Date --</option>
						</select>

					@else
						<select class="form-control" name="control_date" required>
							<option value="" disabled>-- Pilih Control Date --</option>
							@foreach($getControlDate as $gcd)
								<option data-stock-allocation-id="{{ $gcd->sa_id }}"  value="{{$gcd->control_date}}" @if(old('control_date', $stockEntryDetail->control_date) == $gcd->control_date){{'selected'}}@endif>
									{{$gcd->control_date}} ({{$gcd->name}} | {{$gcd->ref_code}} | {{$gcd->s_code}} | {{$gcd->qty_available}})
								</option>
							@endforeach
						</select>
					@endif
					<input class="hidden" type="text" name="stock_allocation_id" value="" readonly />
				</div>
			</div>
			
		@endif

		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="text" name="qty" id="qty_change_sed" class="form-control" placeholder="" value="{{old('qty', $stockEntryDetail->qty)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<input type="text" name="uom_name" class="form-control" value="{{old('uom_name', @$stockEntryDetail->uom->name)}}" readonly>
				<input type="hidden" name="uom_id" value="{{old('uom_id', @$stockEntryDetail->uom_id)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="text" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $stockEntryDetail->weight)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="text" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $stockEntryDetail->volume)}}" required>
			</div>
		</div>
		
		<input type="hidden" name="weight_fix" value="{{ old('weight_fix', $weightVal) }}">
        <input type="hidden" name="volume_fix" value="{{ old('volume_fix', $volumeVal) }}">
        
		<div class="form-group">
			<label for="control_method" class="col-sm-3 control-label">Control Method</label>
			<div class="col-sm-9">
				<input id="control_method" type="text" name="control_method" class="form-control" placeholder="Control Method" value="{{old('control_method', @$stockEntryDetail->item->control_method->name)}}" readonly>
			</div>
		</div>

		@if($stockEntry->type == 'inbound')
	        <div class="form-group required">
				<label for="control_date" class="col-sm-3 control-label">Control Date</label>
				<div class="col-sm-9">
					<input type="text" name="control_date" class="form-control datepicker" placeholder="Control Date" value="{{old('control_date', $stockEntryDetail->control_date)}}" required readonly>
	            </div>
			</div>
		@endif
			
		
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>

@section('js')
    <script>
        $(document).ready(function () {
        	$('select[name="item_sed"]').change(function(){
                var id = $(this).val();
                var item_ref_code = $("select[name='item_sed'] option:selected").attr('data-ref-code');
                var item_control_date = $("select[name='item_sed'] option:selected").attr('data-control-date');
                var stock_entry_id = $('input[name="stock_entry_id"]').val();

                console.log('test1');
                console.log(item_ref_code);

                $.ajax({
                    type: "GET",
                    url: "/ajax/get_item_sed/" + id+'?ref_code='+item_ref_code+'&stock_entry_id='+stock_entry_id,
                    success: function (data) {
                        
                        
                        //console.log('data', data);
                        //console.log('data', data.ref_codes);
                        var qty = $('input[name="qty"]').val();

                        console.log(data.weight);

                        weight = data.weight;
	                    volume = data.volume;
	                    uom_id = data.default_uom_id;
	                    uom_name = data.uom_name;
	                    control_method = data.control_method.name;
	                	sed_type = data.sed_type;

	                	
	                	$('input[name="weight"]').val(weight);
	                    $('input[name="volume"]').val(volume);
	                    $('input[name="weight_fix"]').val(weight);
	                    $('input[name="volume_fix"]').val(volume);
	                    $('input[name="uom_id"]').val(uom_id);
	                    $('input[name="control_method"]').val(control_method);
	                    $('input[name="control_date"]').val(item_control_date);
	                    $('input[name="uom_name"]').val(uom_name);
	                    $('input[name="ref_code"]').val(item_ref_code);
                        
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

                		if(sed_type == 'outbound'){
	                		$('select[name="storage_id"]').empty();
	                	
	                        $('select[name="storage_id"]').append('<option value="" disabled>-- Pilih Storage --</option>');
		                    $.each(data.storages, function (key, value) {         
	                        	if(key == 1){
		                			$('select[name="storage_id"]').append('<option value="' + value.id + '" selected>' + value.code  + '</option>');
		                		}
		                		else{
		                			$('select[name="storage_id"]').append('<option value="' + value.id + '">' + value.code  + '</option>');
		                		}  
	                        });
		                }
	                    // $('input[name="ref_code"]').trigger("change");
	                    $('select[name="storage_id"]').trigger("change");
                    }
                });
            });

            var id = $('select[name="item_sed"]').val();
            var item_ref_code = $("select[name='item_sed'] option:selected").attr('data-ref-code');
            var item_control_date = $("select[name='item_sed'] option:selected").attr('data-control-date');
            var stock_entry_id = $('input[name="stock_entry_id"]').val();

            console.log('test1');
            // console.log(item_ref_code);

            $.ajax({
                type: "GET",
                url: "/ajax/get_item_sed/" + id+'?ref_code='+item_ref_code+'&stock_entry_id='+stock_entry_id,
                success: function (data) {
                    
                    
                    //console.log('data', data);
                    //console.log('data', data.ref_codes);
                    var qty = $('input[name="qty"]').val();

                    console.log(data.weight);

                    weight = data.weight;
                    volume = data.volume;
                    uom_id = data.default_uom_id;
                    uom_name = data.uom_name;
                    control_method = data.control_method.name;
                	sed_type = data.sed_type;

                	
                	$('input[name="weight"]').val(weight);
                    $('input[name="volume"]').val(volume);
                    $('input[name="weight_fix"]').val(weight);
                    $('input[name="volume_fix"]').val(volume);
                    $('input[name="uom_id"]').val(uom_id);
                    $('input[name="control_method"]').val(control_method);
                    $('input[name="control_date"]').val(item_control_date);
                    $('input[name="uom_name"]').val(uom_name);
                    $('input[name="ref_code"]').val(item_ref_code);
                    
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

            		if(sed_type == 'outbound'){
            			$('select[name="storage_id"]').empty();
	                	
                        $('select[name="storage_id"]').append('<option value="" disabled>-- Pilih Storage --</option>');
	                    $.each(data.storages, function (key, value) {         
                        	if(key == 1){
	                			$('select[name="storage_id"]').append('<option value="' + value.id + '" selected>' + value.code  + '</option>');
	                		}
	                		else{
	                			$('select[name="storage_id"]').append('<option value="' + value.id + '">' + value.code  + '</option>');
	                		}  
                        });
	                }
	                // $('input[name="ref_code"]').trigger("change");
                    $('select[name="storage_id"]').trigger("change");
                }
            });

            //untuk control date ketika create detail entry inbound
            // $('input[name="ref_code"]').change(function(){
            //     var ref_code = $(this).val();
            //     var id = $('select[name="item_sed"]').val();
            //     var stock_entry_id = $('input[name="stock_entry_id"]').val();

            //     console.log("test2");
            //     console.log(ref_code);


            //     $.ajax({
            //         type: "GET",
            //         url: "/ajax/get_item_sed/" + id+'?ref_code='+ref_code+'&stock_entry_id='+stock_entry_id,
            //         success: function (data) {
            //             //console.log('data', data);
            //             //console.log('data', data.ref_codes);
            //             var qty = $('input[name="qty"]').val();

	           //          control_date = data.control_date;

	           //          $('input[name="control_date"]').val(control_date);
            //         }
            //     });
            // });

            $('select[name="storage_id"]').change(function(){
            	var storage_id = $(this).val();
                var item_id = $('#item_sed').val();
                var ref_code = $('input[name="ref_code"]').val();
                var stock_entry_id = $('input[name="stock_entry_id"]').val();
                var stock_entry_detail_id = $('input[name="stock_entry_detail_id"]').val();

                $.ajax({
                    type: "GET",
                    url: "/ajax/get_control_date?ref_code="+ref_code+"&item_id="+item_id+"&storage_id="+storage_id+"&stock_entry_id="+stock_entry_id+"&stock_entry_detail_id="+stock_entry_detail_id,
                    success: function (data) {
                        //console.log('data', data);
                        //console.log('data', data.ref_codes);
                        $('select[name="control_date"]').empty();
	                	
                        $('select[name="control_date"]').append('<option value="" disabled>-- Pilih Control Date --</option>');
	                    $.each(data, function (key, value) {         
                        	if(key == 1){
	                			$('select[name="control_date"]').append('<option value="' + value.control_date + '" selected>' + value.control_date  + ' ('+ value.name +' | '+ value.ref_code+' | '+ value.s_code +' | '+ value.qty_available +')</option>');
	                		}
	                		else{
	                			$('select[name="control_date"]').append('<option  value="' + value.control_date + '">' + value.control_date  + ' ('+ value.name +' | '+ value.ref_code +' | '+ value.s_code +' | '+ value.qty_available +')</option>');
	                		}
                        });

                        $('select[name="control_date"]').trigger("change");
                    }
                }); 
            });

            var storage_id = $('select[name="storage_id"]').val();
            var item_id = $('#item_sed').val();
            var ref_code = $('input[name="ref_code"]').val();
            var stock_entry_id = $('input[name="stock_entry_id"]').val();
            var stock_entry_detail_id = $('input[name="stock_entry_detail_id"]').val();

                $.ajax({
                    type: "GET",
                    url: "/ajax/get_control_date?ref_code="+ref_code+"&item_id="+item_id+"&storage_id="+storage_id+"&stock_entry_id="+stock_entry_id+"&stock_entry_detail_id="+stock_entry_detail_id,
                	success: function (data) {
                    //console.log('data', data);
                    //console.log('data', data.ref_codes);
                    $('select[name="control_date"]').empty();
                	
                    $('select[name="control_date"]').append('<option value="" disabled>-- Pilih Control Date --</option>');
                    $.each(data, function (key, value) {         
                    	if(key == 1){
                			$('select[name="control_date"]').append('<option value="' + value.control_date + '" selected>' + value.control_date  + ' ('+ value.name +' | '+ value.ref_code+' | '+ value.s_code +' | '+ value.qty_available +')</option>');
                		}
                		else{
                			$('select[name="control_date"]').append('<option  value="' + value.control_date + '">' + value.control_date  + ' ('+ value.name +' | '+ value.ref_code +' | '+ value.s_code +' | '+ value.qty_available +')</option>');
                		}
                    });

                    $('select[name="control_date"]').trigger("change");
                }
            }); 

            $('#qty_change_sed').keyup(function() {
	        	console.log('test');
	            var qty = $(this).val();

	            console.log(qty);
	            
	            var weight = $('input[name="weight_fix"]').val();
	    		var volume = $('input[name="volume_fix"]').val();

	            if (weight != 0 && volume!= 0) {
	                var new_weight = qty*weight;
	                var new_volume = qty*volume;
	                $('input[name="weight"]').val(new_weight.toFixed(2));
	                $('input[name="volume"]').val(new_volume.toFixed(2));
	            }
	        });

            
        });


    </script>
@endsection