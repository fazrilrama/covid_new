<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Kode Gudang</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Gudang" value="{{old('code', $warehouse->code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama Gudang</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $warehouse->name)}}">
			</div>
		</div>
		<!-- <div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Email</label>
			<div class="col-sm-9">
				<input type="email" name="email" class="form-control" placeholder="Email Kepala" value="{{old('name', $warehouse->email)}}">
			</div>
		</div> -->
		<div class="form-group required">
			<label for="branch_id" class="col-sm-3 control-label">Cabang/Subcabang</label>
			<div class="col-sm-9">
				<select class="form-control" name="branch_id" id="party_id" required>
					<option value="" disabled>-- Pilih Cabang/Subcabang --</option>
					@foreach($branches as $branch)
						<option value="{{$branch->id}}" @if($warehouse->branch_id == $branch->id){{'selected'}}@endif>{{$branch->name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		
		<div id="select_region_city">
			<div class="form-group required">
				<label for="region_id" class="col-sm-3 control-label">Province</label>
				<div class="col-sm-9">
					<select class="form-control" name="region_id" id="region-id-warehouse">
						@foreach($regions as $key => $value)
							<option value="{{$key}}" {{ $key == $warehouse->region_id ? 'selected="selected"' : '' }}>{{$value}}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div id="select_city">
				<div class="form-group required">
					<label for="city_id" class="col-sm-3 control-label">Kota</label>
					<div class="col-sm-9">
						<select class="form-control" name="city_id" id="city_id">
							@foreach($cities as $key => $value)
								<option value="{{$key}}" {{ $key == $warehouse->city_id ? 'selected="selected"' : '' }}>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group required">
			<label for="address" class="col-sm-3 control-label">Address</label>
			<div class="col-sm-9">
				<input type="text" name="address" class="form-control" placeholder="Address" value="{{old('address', $warehouse->address)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="postal_code" class="col-sm-3 control-label">Postal Code</label>
			<div class="col-sm-9">
				<input type="number" name="postal_code" class="form-control" placeholder="Postal Code" value="{{old('postal_code', $warehouse->postal_code)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
			<div class="col-sm-9">
				<input type="number" name="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone_number', $warehouse->phone_number)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
			<div class="col-sm-9">
				<input type="number" name="fax_number" class="form-control" placeholder="Fax Number" value="{{old('fax_number', $warehouse->fax_number)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="total_weight" class="col-sm-3 control-label">Total Kapasitas (kg)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="total_weight" class="form-control" placeholder="Total Kapasitas" value="{{old('total_weight', $warehouse->total_weight)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="total_volume" class="col-sm-3 control-label">Total Volume (m3)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="total_volume" class="form-control" placeholder="Total Volume" value="{{old('total_volume', $warehouse->total_volume)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Length (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="length" class="form-control" placeholder="Length" value="{{old('length', $warehouse->length)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="width" class="col-sm-3 control-label">Width (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="width" class="form-control" placeholder="Width" value="{{old('width', $warehouse->width)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="tall" class="col-sm-3 control-label">Height (m)</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="tall" class="form-control" placeholder="Height" value="{{old('tall', $warehouse->tall)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="latitude" class="col-sm-3 control-label">Latitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude" value="{{old('latitude', $warehouse->latitude)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="longitude" class="col-sm-3 control-label">Longitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude" value="{{old('longitude', $warehouse->longitude)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="ownership" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
				<select class="form-control" name="ownership">
					<option value="milik" @if($warehouse->ownership == 'milik'){{'selected'}}@endif>MILIK</option>
					<option value="sewa" @if($warehouse->ownership == 'sewa'){{'selected'}}@endif>SEWA</option>
					<option value="manajemen" @if($warehouse->ownership == 'manajemen'){{'selected'}}@endif>MANAJEMEN</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Aktif</label>
			<div class="col-sm-9">
				<select class="form-control" name="is_active" @if($warehouse->is_active != null) readonly @endif>
					<option value="0" @if($warehouse->is_active == 0){{'selected'}}@endif>Tidak Aktif</option>
					<option value="1" @if($warehouse->is_active == 1){{'selected'}}@endif>Aktif</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Status Operasi</label>
			<div class="col-sm-9">
				<select class="form-control" name="status">
					<option value="" @if($warehouse->status == NULL){{'selected'}}@endif>Belum Memilih</option>
					<option value="Beroperasi" @if($warehouse->status == 'Beroperasi'){{'selected'}}@endif>Beroperasi</option>
					<option value="Sewa Lepas" @if($warehouse->status == 'Sewa Lepas'){{'selected'}}@endif>Sewa Lepas</option>
					<option value="Idle" @if($warehouse->status == 'Idle'){{'selected'}}@endif>Idle</option>
					<option value="Tutup" @if($warehouse->status == 'Tutup'){{'selected'}}@endif>Tutup</option>
					<option value="Rusak" @if($warehouse->status == 'Rusak'){{'selected'}}@endif>Rusak</option>
					<option value="Open Storage" @if($warehouse->status == 'Open Storage'){{'selected'}}@endif>Open Storage</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="percentage_buffer" class="col-sm-3 control-label">Buffer Stock</label>
			<div class="col-sm-8">
				<input type="number" step="1" name="percentage_buffer" class="form-control" placeholder="Percentage Buffer" value="{{old('percentage_buffer', $warehouse->percentage_buffer)}}">
			</div>
			<div class="col-sm-1">
				<label for="percentage_buffer" class="control-label">%</label>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>