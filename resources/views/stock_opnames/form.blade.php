<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Warehouse</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							@if(Auth::user()->hasRole('WarehouseSupervisor'))
							<p class="form-control-static">{{ $warehouse->code }} - {{ $warehouse->name }}</p>
							@else
								<select name="warehouse" id="warehouses" class="form-control select2" required="">
								</select>
							@endif
						@else
							<p class="form-control-static">{{ $stock_opname->warehouse->code }} - {{ $stock_opname->warehouse->name }}</p>
						@endif
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Kota/Kabupaten</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							@if(Auth::user()->hasRole('WarehouseSupervisor'))
							<p class="form-control-static">{{ $warehouse->city->name }}</p>
							@else
							<p class="form-control-static" id="city_id"></p>
							@endif
						@else
						<p class="form-control-static">{{ $stock_opname->warehouse->city->name }}</p>
						@endif
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Alamat</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							@if(Auth::user()->hasRole('WarehouseSupervisor'))
								<p class="form-control-static">{{ $warehouse->address }}</p>
							@else
							<p class="form-control-static" id="address"></p>
							@endif
						@else
						<p class="form-control-static">{{ $stock_opname->warehouse->address }}</p>
						@endif
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Kapasitas</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							@if(Auth::user()->hasRole('WarehouseSupervisor'))
							<p class="form-control-static">{{ number_format($warehouse->length * $warehouse->width, 2, ',', '.') }}</p>
							@else
							<p class="form-control-static" id="capacity"></p>
							@endif
						@else
						<p class="form-control-static">{{ number_format($stock_opname->warehouse->length * $stock_opname->warehouse->width, 2, ',', '.') }}</p>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Tanggal dan Waktu</label>
					<div class="col-sm-9">
					@if($method == 'POST')
					<input type="text" name="date_stock_opname" id="datetimepickerdisable" class="form-control" placeholder="" value="{{old('pickup_order')}}"  required>
					@else
					<p class="form-control-static">{{ $stock_opname->date }}</p>
					@endif
					</div>
				</div>
				@if($method == 'POST')
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Spv. Warehouse</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							<input type="text" class="form-control" required name="anggota[]">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Lead Warehouse</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							<input type="text" class="form-control" required name="anggota[]">
						@endif
					</div>
				</div>
				<div class="form-group">
				<label for="project_id" class="col-sm-3 control-label">Cheker</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							<input type="text" class="form-control" required name="anggota[]">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Anggota Tambahan</label>
					<div class="col-sm-9">
						@if($method == 'POST')
						<div class="customer_records card">
							<input type="text" class="form-control" name="anggota[]">
							<a class="extra-fields-customer" href="#">Tambah Anggota</a>
						</div>
						@endif
					
						<div class="customer_records_dynamic card"></div>

						</div>
					</div>
				</div>
				@else
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Dihitung Oleh</label>
					<div class="col-sm-9">
						<ul>
						@if($stock_opname->calculated_by != null)
						@foreach(json_decode($stock_opname->calculated_by) as $key => $value)
							<li class="form-control-static">{{ $value }}</li>
						@endforeach
						@endif
						</ul>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	@if($method == 'POST')
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	@endif
	<!-- /.box-footer -->
    <input type="hidden" id="report_warehouse" value="{{ route('warehouse_stockopname') }}">

</form>
@section('custom_script')
<script>
		$('.extra-fields-customer').click(function() {
        $('.customer_records').clone().appendTo('.customer_records_dynamic');
        $('.customer_records_dynamic .customer_records').addClass('single remove');
        $('.single .extra-fields-customer').remove();
        $('.single').append('<a href="#" class="remove-field btn-remove-customer">Remove Fields</a>');
        $('.customer_records_dynamic > .single').attr("class", "remove");
        $('.customer_records_dynamic select').attr("class", "form-control");    
        $('.customer_records_dynamic .selection').remove();
        $('.customer_records_dynamic .select2-container ').remove();
        $('select').select2({
            tags: true
        })
        
        $('.customer_records_dynamic input select').each(function() {
            var count = 0;
            var fieldname = $(this).attr("name");
            $(this).attr('name', fieldname + count);
            count++;
        });


        });

        $(document).on('click', '.remove-field', function(e) {
            $(this).parent('.remove').remove();
            e.preventDefault();
        });

		$.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $('#warehouses').append("<option value='' disabled selected>Pilih Warehouse</option>");
                $.each(data,function(i, value){
					$("#warehouses").append("<option value='"+value.id+"'>"+value.code +'-'+ value.name +"</option>");
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
		$('#warehouses').change(function() {
            $("#item").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val()
                },
                dataType: 'json',
                success: function(data) { 
					console.log(data);
					$('#city_id').text(data.city_name)
					$('#address').text(data.address)
					$('#capacity').text(data.total_weight)
                }
            });
        });
</script>
@endsection