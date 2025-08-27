<!-- form start -->
<input type="hidden" name="created_attttt" value="{{$stockTransportDetail->header->created_at}}">
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group">
			<label for="stock_transport_id" class="col-sm-3 control-label">
				{{ $stockTransportDetail->header->type == 'outbound' ? 'Delivery Plan' : 'Goods Receiving' }} #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{$stockTransportDetail->header->code}}</p>
				<input type="hidden" name="stock_transport_id" value="{{$stockTransportDetail->stock_transport_id}}">
				<input type="hidden" name="advance_notice_id" value="{{$stockTransportDetail->header->advance_notice_id}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="item_id" class="col-sm-3 control-label">Item SKU</label>
			<div class="col-sm-9">
				@if($method == 'PUT')
					<input type="hidden" name="item_id" value="{{$stockTransportDetail->item_id}}" />
					<textarea class="form-control" readonly>{{ $stockTransportDetail->item->sku }}  - {{$stockTransportDetail->item->name}} - {{$stockTransportDetail->ref_code}} (Item Maksimal :{{$stockTransportDetail->item_outstanding}})</textarea>

				@elseif($method == 'POST')
					<select class="form-control" name="item_id" required>
	                    <option value="">-- Pilih Item SKU --</option>
						@foreach($items as $item)
	                        <option data-ref-code="{{$item->ref_code}}" value="{{$item->item_id}}" @if(old('item_id', $stockTransportDetail->item_id) == $item->item_id){{'selected'}}@endif>
	                        	{{$item->item->sku}} - {{$item->item->name}} - {{$item->ref_code}} (Item Maksimal :{{$item->item_outstanding}}) 
							</option>
						@endforeach
					</select>

					<p class="help-block">Item yang tersedia berdasarkan dokumen referensi</p>
				@endif		

				
			</div>
		</div>
		<div class="form-group required">
			<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">
				<input type="text" name="ref_code"  class="form-control" value="{{ old ('ref_code', $stockTransportDetail->ref_code) }}" readonly />                	
                @if($stockTransportDetail->header->type == 'outbound')
                	<p class="help-block">Group ref diambil dari stock item yang tersedia</p>
                @endif
			</div>
		</div>
		<div class="form-group required">
			<label for="qty_change" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="plan_qty" id="qty_change_std" class="form-control" placeholder="Qty" value="{{old('plan_qty', $stockTransportDetail->plan_qty)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<input type="text" name="uom_name" value="{{old('uom_name', @$stockTransportDetail->uom->name)}}" readonly class="form-control">
				<input type="hidden" name="uom_id" value="{{old('uom_id', @$stockTransportDetail->uom_id)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="plan_weight" class="form-control" placeholder="Weight" value="{{old('plan_weight', $stockTransportDetail->plan_weight)}}" required readonly>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="plan_volume" class="form-control" placeholder="Volume" value="{{old('plan_volume', $stockTransportDetail->plan_volume)}}" required readonly>
			</div>
		</div>

        <input type="hidden" name="weight_val" class="form-control" placeholder="Weight" value="{{old('weight_val', $weightVal)}}">
        <input type="hidden" name="volume_val" class="form-control" placeholder="Volume" value="{{old('volume_val', $volumeVal)}}">
		<div class="form-group">
			<label for="control_method" class="col-sm-3 control-label">Control Method</label>
			<div class="col-sm-9">
				<input id="control_method" type="text" name="control_method" class="form-control" placeholder="Control Method" value="{{old('control_method', $method == 'PUT' ? $stockTransportDetail->item->control_method->name : '')}}" readonly>
			</div>
		</div>
		<div class="form-group required @if($stockTransportDetail->header->type == 'outbound') hidden @endif">
			<label for="control_date" class="col-sm-3 control-label">Control Date</label>
			<div class="col-sm-9">
				<input id="control_date" type="text" name="control_date" class="form-control datepicker-normal" placeholder="Control Date" value="{{old('control_date', empty($stockTransportDetail->control_date) ? $stockTransportDetail->header->created_at : $stockTransportDetail->control_date )}}" required>
			</div>
		</div>
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
            $('form').submit(function(){
                $('select[name="uom_id"]').removeAttr('disabled');
                return true;
            });

            $('#qty_change_std').keyup(function() {
	            var qty = $(this).val();
	            
	            var weight = $('input[name="weight_val"]').val();
        		var volume = $('input[name="volume_val"]').val();

	            if (weight != 0 && volume!= 0) {
	                var new_weight = qty*weight;
	                var new_volume = qty*volume;
	                $('input[name="plan_weight"]').val(new_weight.toFixed(2));
	                $('input[name="plan_volume"]').val(new_volume.toFixed(2));
	            }
	        });

            //buat data actual gr
	        $('#actual_qty_change_std').keyup(function() {
            	var qty = $(this).val();

	            console.log(qty);
	            
	            var weight = $('input[name="weight_val"]').val();
        		var volume = $('input[name="volume_val"]').val();

	            if (weight != 0 && volume!= 0) {
	                var new_weight = qty*weight;
	                var new_volume = qty*volume;
	                $('input[name="weight"]').val(new_weight.toFixed(2));
	                $('input[name="volume"]').val(new_volume.toFixed(2));
	            }
	        });

	        function formatDate(date) {
	            var d = new Date(date),
	            month = '' + (d.getMonth() + 1),
	            day = '' + d.getDate(),
	            year = d.getFullYear();

	            if (month.length < 2) month = '0' + month;
	            if (day.length < 2) day = '0' + day;

	            return [year, month, day].join('-');
	        }
	        
	        // Untuk nambah barang
            $('select[name="item_id"]').on('change', function(){

                var item_ref_code = $("select[name='item_id'] option:selected").attr('data-ref-code');

                $('#item').attr('disabled', 'true');
                $('select[name="ref_code"]').attr('disabled', 'true');
                $('select[name="ref_code"]').attr('disabled', 'true');
                $('select[name="warehouse_id"]').attr('disabled', 'true');
                $('select[name="storage_id"]').attr('disabled', 'true');
                $('.btn-info').attr('disabled', 'true');
                $.ajax({
                    type: 'GET', //THIS NEEDS TO BE GET
                    url: '/control-method?id='+this.value + '&parentId=' + $('input[name="stock_transport_id"]').val() + '&ain_id=' + $('input[name="advance_notice_id"]').val() + '&stock_transport_id=' + $('input[name="stock_transport_id_ajax"]').val(),
                    dataType: 'json',
                    success: function (data) {
                        var qty = $('input[name="plan_qty"]').val();
                        
                        weight = data[0].weight;
                        volume = data[0].volume;
                        
                        baseWeight = data[0].weight;
                        baseVolume = data[0].volume;

                        $('input[name="weight_val"]').val(weight);
                        $('input[name="volume_val"]').val(volume);
                        
                        var weightNew = data[0].weight;
                        var volumeNew = data[0].volume;

                        if (qty != '' || qty != null) {
                            volumeNew = qty * data[0].volume;
                            weightNew = qty * data[0].weight;
                        }

                        $('input[name="uom_id"]').val(data[0].default_uom_id);
                        $('input[name="uom_name"]').val(data[0].default_uom_name);
                        $('input[name="plan_volume"]').val(volumeNew.toFixed(2));
                        $('input[name="plan_weight"]').val(weightNew.toFixed(2));
                        $('input[name="ref_code"]').val(item_ref_code);

                        $('select[name="ref_code"]').empty();
                        
                        //group reference ketika buat detail transport
                        $.each(data[0].ref_code_array, function(key, value) {
                            $('select[name="ref_code"]').append('<option value="'+ value.ref_code +'">'+ value.ref_code +'</option>');
                        });

                        $('select[name="control_date"]').empty();
                        $.each(data[0].control_dates, function (key, value) { 
                            $('select[name="control_date"]').append('<option value="'+ value.control_date +'">'+ value.control_date +'</option>');
                        });
                        
                        if ($('input[name="doc_type"]').val() == 'outbound') {
                            $('select[name="warehouse_id"]').empty();
                            $.each(data[0].warehouses, function (key, value) { 
                                $('select[name="warehouse_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');
                            });

                            $('select[name="storage_id"]').empty();
                            $.each(data[0].storages, function (key, value) { 
                                $('select[name="storage_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');
                            });
                        }
                        
                        $('#item').removeAttr('disabled');
                        $('select[name="ref_code"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');

                        document.getElementById('control_method').value = data[0].control_method;
                        var controlDate = document.getElementById('control_method').value;
                        if(controlDate = 'FIFO'){
                            console.log('fifo', data[0].control_date);
                            $('input[name="control_date"]').val(formatDate(new Date()));
                        } else if (controlDate = 'LIFO'){
                            console.log('fifo', formatDate(new Date()));
                            $('input[name="control_date"]').val(formatDate(new Date()));
                        } else {
                            document.getElementById('control_date').reset();
                            $('input[name="control_date"]').val(formatDate(new Date()));
                        }
                    },
                    error:function(error){
                        console.log('error ', error);
                        $('#item').removeAttr('disabled');
                        $('select[name="ref_code"]').removeAttr('disabled');
                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                    }
                });
            });
        });
    </script>
@endsection
