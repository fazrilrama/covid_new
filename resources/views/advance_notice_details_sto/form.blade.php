<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
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
				<select class="form-control" name="item_and" id="item_and" required>
					<option value="">-- Pilih Item --</option>
					@foreach($items as $itemValue)
                        <option value="{{$itemValue->id.','.$advanceNoticeDetail->stock_advance_notice_id}}" @if(old('item_id', $advanceNoticeDetail->item_id) == $itemValue->id){{'selected'}}@endif>
                            {{$itemValue->sku}} - {{$itemValue->name}} 
                            @if($advanceNoticeDetail->header->type == 'outbound') 
                                (Outstanding dari Stock Allocation:{{$itemValue->stock}}) 
                            @endif
                        </option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">
                @if($advanceNoticeDetail->header->type == 'outbound')
                <select name="group_references" id="input" class="form-control" required="">
                    <option value="">-- Pilih Group Reference --</option>
                    <!-- @if(!empty($groupReferences))
                        @foreach($groupReferences as $groupReference)
                        <option value="{{ $groupReference->ref_code }}" {{ old('group_reference', $advanceNoticeDetail->ref_code) == $groupReference->ref_code ? 'selected' : '' }} >{{ $groupReference->ref_code }}</option>
                        @endforeach
                    @elseif(session('groupReferences'))
                        @foreach(session('groupReferences') as $groupReference)
                        <option value="{{ $groupReference->ref_code }}" {{ old('group_reference', $advanceNoticeDetail->ref_code) == $groupReference->ref_code ? 'selected' : '' }} >{{ $groupReference->ref_code }}</option>
                        @endforeach
                    @endif -->
                </select>
                @else
                	<input type="text" name="group_references" class="form-control" placeholder="Group Reference" value="{{old('group_reference', $advanceNoticeDetail->ref_code)}}" required>
                @endif
                @if($advanceNoticeDetail->header->type == 'outbound')
                	<p class="help-block">Group ref diambil dari stock item yang tersedia</p>
                @endif
            </div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<p class="form-control-static" id="default_uom_name">
					@if(session('uom_name'))
						{{session('uom_name')}}
					@else
						{{@$advanceNoticeDetail->uom->name}}
					@endif
				</p>
				<input type="hidden" name="uom_id" value="{{old('uom_id', @$advanceNoticeDetail->uom_id)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="text" id="qty_change_and" name="qty_change_and" class="form-control" placeholder="qty"  value="{{old('qty', $advanceNoticeDetail->qty)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="text" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $advanceNoticeDetail->weight)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="text" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $advanceNoticeDetail->volume)}}" required>
			</div>
		</div>
        <input type="hidden" name="weight_fix" value="">
        <input type="hidden" name="volume_fix" value="">
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
        	$('select[name="item_and"]').change(function(){
                var id = $(this).val();
                console.log('id', id);
                $.ajax({
                    type: "GET",
                    url: "/advance_notice_details/item/" + id,
                    success: function (data) {
                        $('select[name="group_references"]').empty();
                        //console.log('data', data);
                        //console.log('data', data.ref_codes);
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
                        $('#default_uom_name').text(uom_name);
                        
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

                        $('select[name="group_references"]').append('<option value="" selected disabled>-- Pilih Group Reference --</option>');
                        $.each(data.ref_codes, function (key, value) {
	                        if(key == 0){
                                $('select[name="group_references"]').append('<option value="' + value.ref_code + '"selected>' + value.ref_code  + '</option>');
                            }
                            else{
                                $('select[name="group_references"]').append('<option value="' + value.ref_code + '">' + value.ref_code  + '</option>');
                            }
                            
                        });
                    }
                });
            });

            var id = $('select[name="item_and"]').val();
            console.log('id', id);
            $.ajax({
                type: "GET",
                url: "/advance_notice_details/item/" + id,
                success: function (data) {
                    $('select[name="group_references"]').empty();
                    //console.log('data', data);
                    //console.log('data', data.ref_codes);
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
                    $('#default_uom_name').text(uom_name);
                    
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

                    $('select[name="group_references"]').append('<option value="" selected disabled>-- Pilih Group Reference --</option>');
                    $.each(data.ref_codes, function (key, value) {
                        if(key == 0){
                            $('select[name="group_references"]').append('<option value="' + value.ref_code + '"selected>' + value.ref_code  + '</option>');
                        }
                        else{
                            $('select[name="group_references"]').append('<option value="' + value.ref_code + '">' + value.ref_code  + '</option>');
                        }
                        
                    });
                }
            });
        });

        $('input[name="qty_change_and"]').keyup(() => { setWeightVolume() });
	    
	    function setWeightVolume(){
	    	var weight = $('input[name="qty_change_and"]').val() * $('input[name="weight_fix"]').val();
	        var volume = $('input[name="qty_change_and"]').val() * $('input[name="volume_fix"]').val();
	        $('input[name="weight"]').val(weight);
	        $('input[name="volume"]').val(volume);
	    } 
    </script>
    

@endsection