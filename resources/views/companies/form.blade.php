<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Kode Perusahaan</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Perusahaan" value="{{old('code', $company->code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $company->name)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="address" class="col-sm-3 control-label">Address</label>
			<div class="col-sm-9">
				<input type="text" name="address" class="form-control" placeholder="Address" value="{{old('address', $company->address)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="postal_code" class="col-sm-3 control-label">Postal Code</label>
			<div class="col-sm-9">
				<input type="text" name="postal_code" class="form-control" placeholder="Postal Code" value="{{old('postal_code', $company->postal_code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
			<div class="col-sm-9">
				<input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone_number', $company->phone_number)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
			<div class="col-sm-9">
				<input type="text" name="fax_number" class="form-control" placeholder="Fax Number" value="{{old('fax_number', $company->fax_number)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Kota</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="city_id">
					@foreach($cities as $id => $value)
					<option value="{{$id}}" @if($company->city_id == $id){{'selected'}}@endif>{{$value}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Jenis Perusahaan</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="company_type_id">
					@foreach($company_types as $id => $value)
					<option value="{{$id}}" @if($company->company_type_id == $id){{'selected'}}@endif>{{$value}}</option>
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