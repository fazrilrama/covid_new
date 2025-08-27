<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
    <div class="form-group">
		<label for="from_uom_id" class="col-sm-3 control-label">From UOM</label>
		<div class="col-sm-9">
			<select class="form-control" name="from_uom_id">
				@foreach($uoms as $id => $value)
				<option value="{{$id}}" @if($uomConversion->from_uom_id == $id){{'selected'}}@endif>{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="to_uom_id" class="col-sm-3 control-label">To UOM</label>
		<div class="col-sm-9">
			<select class="form-control" name="to_uom_id">
				@foreach($uoms as $id => $value)
				<option value="{{$id}}" @if($uomConversion->to_uom_id == $id){{'selected'}}@endif>{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="box-body">
		<div class="form-group required">
			<label for="multiplier" class="col-sm-3 control-label">Multiplier</label>
			<div class="col-sm-9">
				<input type="text" name="multiplier" class="form-control" placeholder="Multiplier" value="{{old('multiplier', $uomConversion->multiplier)}}">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>