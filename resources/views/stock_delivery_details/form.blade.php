<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group">
			<label for="stock_delivery_id" class="col-sm-3 control-label">
				{{ $stockDeliveryDetail->header->type == 'outbound' ? 'Goods Issue' : '' }} #
			</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{$stockDeliveryDetail->header->code}}</p>
				<input type="hidden" name="stock_delivery_id" value="{{$stockDeliveryDetail->stock_delivery_id}}">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Item SKU</label>
			<div class="col-sm-9">
				<select class="form-control" name="item_id" id="item" required>
                    <option value="">-- Pilih Item SKU --</option>
					@foreach($items as $id => $value)
					<option value="{{$value->id}}" @if($stockDeliveryDetail->item_id == $value->id){{'selected'}}@endif>{{$value->sku}} - {{$value->name}}</</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="ref_code" class="col-sm-3 control-label">Group Ref</label>
			<div class="col-sm-9">
				<select name="ref_code" id="inputgroup_reference" class="form-control" required>
                    <option value="">-- Pilih Group Ref --</option>
                    @if(!empty(old('ref_code', $stockDeliveryDetail->ref_code)))
                        @if(!empty($refCodes))
                            @foreach($refCodes as $refCode)
                            <option value="{{ $refCode->ref_code }}" {{ old('ref_code', $stockDeliveryDetail->ref_code) != $refCode->ref_code ?'': 'selected'}}>{{ $refCode->ref_code }}</option>
                            @endforeach
                        @elseif(!empty(session('refCodes')))
                            @foreach(session('refCodes') as $refCode)
                            <option value="{{ $refCode->ref_code }}" {{ old('ref_code', $stockDeliveryDetail->ref_code) != $refCode->ref_code ?'': 'selected'}}>{{ $refCode->ref_code }}</option>
                            @endforeach
                        @else
                            <option value="{{ old('ref_code', $stockDeliveryDetail->ref_code) }}">{{ old('ref_code', $stockDeliveryDetail->ref_code) }}</option>
                        @endif
                    @endif
                </select>
			</div>
		</div>
		<div class="form-group required">
			<label for="control_date" class="col-sm-3 control-label">Control Date</label>
			<div class="col-sm-9">
				<select name="control_date" id="control_date" class="form-control" required>
                    <option value="">-- Pilih Control Date --</option>
                    @if(!empty(old('control_date', $stockDeliveryDetail->control_date)))
                        @if(!empty($controlDates))
                            @foreach($controlDates as $controlDate)
                            <option value="{{ $stockDeliveryDetail->control_date }}" {{ old('control_date', $stockDeliveryDetail->control_date) != $controlDate->control_date ?'': 'selected'}}>{{ $controlDate->control_date }}</option>
                            @endforeach
                        @elseif(!empty(session('controlDates')))
                            @foreach(session('controlDates') as $controlDate)
                            <option value="{{ $controlDate->control_date }}" {{ old('control_date', $stockDeliveryDetail->control_date) != $controlDate->control_date ?'': 'selected'}}>{{ $controlDate->control_date }}</option>
                            @endforeach
                        @else
                            <option value="{{ old('control_date', $stockDeliveryDetail->control_date) }}">{{ old('control_date', $stockDeliveryDetail->control_date) }}</option>
                        @endif
                    @endif
                </select>
			</div>
		</div>
		<div class="form-group required">
			<label for="qty" class="col-sm-3 control-label">Qty</label>
			<div class="col-sm-9">
				<input type="text" name="qty" class="form-control" placeholder="Qty" value="{{old('qty', $stockDeliveryDetail->qty)}}"  required>
			</div>
		</div>
		<div class="form-group">
			<label for="uom_id" class="col-sm-3 control-label">UOM</label>
			<div class="col-sm-9">
				<p class="form-control-static" id="default_uom_name">{{@$stockDeliveryDetail->uom->name}}</p>
				<input type="hidden" name="uom_id" value="{{old('uom_id', @$stockDeliveryDetail->uom_id)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight</label>
			<div class="col-sm-9">
				<input type="text" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $stockDeliveryDetail->weight)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume</label>
			<div class="col-sm-9">
				<input type="text" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $stockDeliveryDetail->volume)}}" required>
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
            $('input[name="qty"]').keyup(function() {
                var qty = $(this).val();
                $('input[name="weight"]').val(baseWeight * qty);
                $('input[name="volume"]').val(baseVolume * qty);
            });
        });
    </script>
@endsection