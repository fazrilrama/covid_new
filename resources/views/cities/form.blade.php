<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="id" class="col-sm-3 control-label">Id</label>
			<div class="col-sm-9">
				<input type="text" name="id" class="form-control" placeholder="Id" value="{{old('id', $city->id)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Code</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Code" value="{{old('code', $city->code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $city->name)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="region_id" class="col-sm-3 control-label">Province</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="region_id">
					@foreach($regions as $regionValue)
					<option value="{{$regionValue->id}}" @if($city->province_id == $regionValue->id){{'selected'}}@endif>{{$regionValue->id}} - {{ $regionValue->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>