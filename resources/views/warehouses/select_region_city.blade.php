<div class="form-group required">
	<label for="region_id" class="col-sm-3 control-label">Province</label>
	<div class="col-sm-9">
		<select class="form-control" name="region_id" id="region-id-warehouse">
		@foreach($regions as $region)
			<option value="{{$region->id}}" {{ $region->id == $province->id ? 'selected="selected"' : '' }}>{{$region->name}}</option>
		@endforeach
		</select>
	</div>
</div>
<div id="select_city">
	<div class="form-group required">
		<label for="city_id" class="col-sm-3 control-label">Kota</label>
		<div class="col-sm-9">
			<select class="form-control" name="city_id" id="city_id">
				@foreach($cities as $city)
					<option value="{{$city->id}}">{{$city->name}}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>