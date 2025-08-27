<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
    <div class="box-body">
		<div class="form-group required">
			<label for="row" class="col-sm-3 control-label">Storage ID</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Storage" value="{{old('code', $storage->code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="warehouse_id" class="col-sm-3 control-label">Warehouse</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="warehouse_id" required>
                    <option value="" selected disabled>-- Pilih Warehouse --</option>
					@foreach($warehouses as $warehouseValue)
					<option value="{{$warehouseValue->id}}" @if(old('warehouse_id', $storage->warehouse_id) == $warehouseValue->id){{'selected'}}@endif>{{$warehouseValue->code}} - {{ $warehouseValue->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="row" class="col-sm-3 control-label">Row</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="row" class="form-control" placeholder="Row" value="{{old('row', $storage->row)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="column" class="col-sm-3 control-label">Column</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="column" class="form-control" placeholder="Column" value="{{old('column', $storage->column)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="level" class="col-sm-3 control-label">Level</label>
			<div class="col-sm-9">
				<input type="text" name="level" class="form-control" placeholder="Level" value="{{old('level', $storage->level)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Length (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="length" class="form-control" placeholder="Length" value="{{old('length', $storage->length)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="width" class="col-sm-3 control-label">Width (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="width" class="form-control" placeholder="Width" value="{{old('width', $storage->width)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="height" class="col-sm-3 control-label">Height (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="height" class="form-control" placeholder="Height" value="{{old('height', $storage->height)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Total Volume (m3)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $storage->volume)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Total Capacity (kg)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $storage->weight)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Komoditas</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id" required>
                    <option value="" selected disabled>-- Pilih Komoditas --</option>
					@foreach($commodities as $commodity)
					<option value="{{$commodity->id}}" @if(old('commodity_id', $storage->commodity_id) == $commodity->id){{'selected'}}@endif>{{$commodity->code}} - {{$commodity->name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Kondisi</label>
			<div class="col-sm-9">
				<select class="form-control" name="is_active" required>
                    <option value="" selected disabled>-- Pilih Kondisi --</option>
					<option value="1" @if(old('is_active', $storage->is_active) == 1){{'selected'}}@endif>Aktif</option>
					<option value="0" @if(old('is_active', $storage->is_active) == 0){{'selected'}}@endif>Tidak Aktif</option>
				</select>
			</div>
		</div>
		<div class="box-footer">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="is_quarantine" @if($storage->is_quarantine == 1){{'checked'}}@endif> Apakah termasuk storage karantina?
				</label>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>

@section('custom_script')
<script>
    $(document).ready(function () {
        $('input[name="length"]').keyup(() => { setVolume(); });
        $('input[name="width"]').keyup(() => { setVolume(); });
        $('input[name="height"]').keyup(() => { setVolume(); });
    });

    function setVolume(){
        var volume = $('input[name="length"]').val() * $('input[name="width"]').val() * $('input[name="height"]').val();
        $('input[name="volume"]').val(volume);
    }
</script>
@endsection