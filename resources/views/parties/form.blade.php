<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Code</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Code" value="{{old('code', $party->code)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $party->name)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="address" class="col-sm-3 control-label">Address</label>
			<div class="col-sm-9">
				<input type="text" name="address" class="form-control" placeholder="Address" value="{{old('address', $party->address)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="postal_code" class="col-sm-3 control-label">Postal Code</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="postal_code" class="form-control" placeholder="Postal Code" value="{{old('postal_code', $party->postal_code)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="phone_number" class="col-sm-3 control-label">Phone Number</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone_number', $party->phone_number)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="fax_number" class="col-sm-3 control-label">Fax Number</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="fax_number" class="form-control" placeholder="Fax Number" value="{{old('fax_number', $party->fax_number)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="fax_number" class="col-sm-3 control-label">Longitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude" value="{{old('longitude', $party->longitude)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="fax_number" class="col-sm-3 control-label">Latitude</label>
			<div class="col-sm-9">
				<input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude" value="{{old('latitude', $party->latitude)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Province</label>
			<div class="col-sm-9">
				<select class="form-control" name="region_id" id="region_id">
					@foreach($regions as $id => $value)
					<option value="{{$id.','.$party->id}}" @if($party->region_id == $id){{'selected'}}@endif>{{$value}}</option>
					@endforeach
				</select>
			</div>
        </div>
        
		<div id="select_city">
            <div class="form-group required">
                <label for="city_id" class="col-sm-3 control-label">Kota</label>
                <div class="col-sm-9">
                    <select class="form-control" name="city_id">
                        @foreach($cities as $key => $value)
                            <option value="{{$key}}" {{ $key == $party->city_id ? 'selected="selected"' : '' }}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
		
		<div class="form-group">
			<label for="city_id" class="col-sm-3 control-label">Pilih Jenis</label>
			<div class="col-sm-9">
				<div class="table-responsive">
	              	<table class="table table-bordered table-hover no-margin" width="100%">
		                <tbody>
			                @foreach($party_types as $party_type)
			                	<tr>
			                        <td>
			                        	<input data-index="{{$loop->index}}" data-label="{{$party_type->name}}" data-total="{{count($party_types)}}" type="checkbox" name="party_types[]" class="party_types" value="{{$party_type->name}}" @if($party->party_types->contains($party_type->id)){{'checked'}}@endif/>
			                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$party_type->name}}
			                        </td>
			                    </tr>
			                @endforeach
		                </tbody>
	              	</table>
	          	</div>
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
        $('.party_types').on('change', function(){
            //alert($(this).val());

            var total_index = $(this).data("total");
            var index = $(this).data("index");

            console.log(index);
            
            if($(this).data("label") == "Divre" && $('.party_types')[index].checked == true){
                for (i = 0; i < total_index; i++) { 

                    if($($('.party_types')[i]).data("label") == 'Subdivre'){
                        $('.party_types')[i].disabled = true;
                        $('.party_types')[index].disabled = false;

                        $("#parent_label").css("display","block");
                        $("#parent_divre").css("display","block");
                        $("#parent_subdivre").css("display","none");

                        $.ajax({
                            method: 'GET',
                            url: '/parties/get_json/' + 1,
                            dataType: "json",
                            success: function(data){
                              $('#parent_divre').val(data.name);
                          },
                          error:function(){
                            console.log('error '+ data);
                            }
                        });
                    }
                }
            }
            if($(this).data("label") == "Subdivre" && $('.party_types')[index].checked == true){
                for (i = 0; i < total_index; i++) { 

                    if($($('.party_types')[i]).data("label") == 'Divre'){
                        $('.party_types')[i].disabled = true;
                        $('.party_types')[index].disabled = false;

                        $("#parent_label").css("display","block");
                        $("#parent_subdivre").css("display","block");
                        $("#parent_divre").css("display","none");
                    }
                }
            }

            if(($(this).data("label") == "Subdivre" || $(this).data("label") == "Divre") && $('.party_types')[index].checked == false){

                $('.party_types').attr("disabled", false);

                $("#parent_label").css("display","none");
                $("#parent_divre").css("display","none");
                $("#parent_subdivre").css("display","none");
            }        
        });

        var total_index = $('.party_types:checked').data("total");
        var index_divre = $('.party_types')[2];
        var label_divre = $($('.party_types')[2]).data("label");

        var index_subdivre = $('.party_types')[3];
        var label_subdivre = $($('.party_types')[3]).data("label");

        //console.log(index_divre);
        
        if(label_divre == "Divre" && index_divre.checked == true){

            $('.party_types')[3].disabled = true;
            $('.party_types')[2].disabled = false;

            $("#parent_label").css("display","block");
            $("#parent_divre").css("display","block");
            $("#parent_subdivre").css("display","none");

            $.ajax({
                method: 'GET',
                url: '/parties/get_json/' + 1,
                dataType: "json",
                success: function(data){
                  $('#parent_divre').val(data.name);
              },
              error:function(){
                console.log('error '+ data);
                }
            });
        }
        if(label_subdivre == "Subdivre" && index_subdivre.checked == true){
            $('.party_types')[3].disabled = false;
            $('.party_types')[2].disabled = true;

            $("#parent_label").css("display","block");
            $("#parent_subdivre").css("display","block");
            $("#parent_divre").css("display","none");
        }

        if((label_subdivre == "Subdivre" || label_divre == "Divre") && (index_divre.checked == false && index_subdivre.checked == false)){

            $('.party_types').attr("disabled", false);

            $("#parent_label").css("display","none");
            $("#parent_divre").css("display","none");
            $("#parent_subdivre").css("display","none");
        }   
    });
</script>

@endsection