<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body col-sm-7">
		<div class="form-group">
			<label for="stock_advance_notice_id" class="col-sm-3 control-label">
				{{ $advanceNoticeDetail->header->type == 'outbound' ? 'AON' : 'AIN' }} #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{$advanceNoticeDetail->header->code}}</p>
				<input type="hidden" name="stock_advance_notice_id" value="{{$advanceNoticeDetail->stock_advance_notice_id}}">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Item</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="item_and" id="item_and" style="width: 100% !important;"  required>
					<option value="" disabled selected>-- Pilih Item --</option>
                    @if($advanceNotice->type == 'inbound') 
    					@foreach($items as $itemValue)
                            <option value="{{$itemValue->id}}" @if(old('item_and', $advanceNoticeDetail->item_id) == $itemValue->id){{'selected'}}@endif>
                                {{$itemValue->sku}} - {{$itemValue->name}} 
                            </option>
    					@endforeach
                    @else
                        @foreach($items as $itemValue)
                            <option data-ref-code="{{$itemValue->ref_code}}" data-stock="{{$itemValue->qty}}" value="{{$itemValue->item_id}}" @if(old('item_and', $advanceNoticeDetail->item_id) == $itemValue->item_id){{'selected'}}@endif>
                                {{$itemValue->item->sku}} - {{$itemValue->item->name}} - {{$itemValue->ref_code}} 
                                <!-- - {{$itemValue->storage->code}}  -->
                                @if($advanceNoticeDetail->header->type == 'outbound') 
                                    (Outstanding dari Stock Allocation:
                                        {{$itemValue->stock}}
                                    ) 
                                @endif
                            </option>
                        @endforeach
                    @endif
				</select>
                <input type="hidden" name="stock">
			</div>
		</div>
        <!-- <input type="hidden" name="storage_id" value="{{ old('storage_id') }}" /> -->
		<div class="form-group required">
			<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">  
                @if($advanceNotice->type == 'inbound') 
            	   <input type="text" name="group_references" class="form-control" placeholder="Group Reference" value="{{old('group_references', $advanceNoticeDetail->ref_code)}}" required>
                @else
                    <input type="text" name="group_references" class="form-control" placeholder="Group Reference" value="{{old('group_references', $advanceNoticeDetail->ref_code)}}" required readonly>
                @endif
            </div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<input type="text" name="uom_name" class="form-control" value="{{old('uom_name', @$advanceNoticeDetail->uom->name)}}" readonly>
				<input type="hidden" name="uom_id" value="{{old('uom_id', @$advanceNoticeDetail->uom_id)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" id="qty_change_and" name="qty_change_and" class="form-control" placeholder="qty"  value="{{old('qty_change_and', $advanceNoticeDetail->qty)}}" required>
			</div>
            @if($method == 'PUT')
                <input type="hidden" step="0.01" name="old_qty" value="{{old('old_qty', $old_qty)}}" required>
            @endif
		</div>

		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $advanceNoticeDetail->weight)}}" required readonly>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="number" step="0.01" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $advanceNoticeDetail->volume)}}" required readonly>
			</div>
		</div>
        @if($method == 'PUT')
            <input type="hidden" name="weight_fix" value="{{ old('weight_fix',$advanceNoticeDetail->item->weight) }}">
            <input type="hidden" name="volume_fix" value="{{ old('volume_fix',$advanceNoticeDetail->item->volume) }}">
        @else
            <input type="hidden" name="weight_fix" value="{{ old('weight_fix') }}">
            <input type="hidden" name="volume_fix" value="{{ old('volume_fix') }}">
        @endif
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Simpan</button>
        </div>
	</div>
	<!-- /.box-body -->
	
	<!-- /.box-footer -->
</form>

@section('js')
    
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
    

@endsection