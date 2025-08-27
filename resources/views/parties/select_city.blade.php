<div class="form-group required">
	<label for="city_id" class="col-sm-3 control-label">Kota</label>
	<div class="col-sm-9">
		<select class="form-control select2" name="city_id">
			@foreach($cities as $city)
				<option value="{{$city->id}}" 
					@if($party != null)
						@if($party->city_id == $city->id)
							{{'selected'}}
						@endif
					@endif
				>
					{{$city->name}}
				</option>
			@endforeach
		</select>
	</div>
</div>