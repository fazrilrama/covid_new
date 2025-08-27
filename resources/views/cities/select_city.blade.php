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