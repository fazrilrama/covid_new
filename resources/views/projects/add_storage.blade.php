@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Tambah Storage ke Projek</h1>
@stop

@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
        		<form action="{{ $action }}" method="POST" class="form-horizontal">
					@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
					{{ method_field($method) }}
					@endif
					@csrf
					<div class="box-body">
						<input type="hidden" value="{{$project->id}}" name="project_id" />
						<div class="form-group required">
							<label for="branch_id" class="col-sm-3 control-label">Cabang/Subcabang</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="party_id" required>
									<option value="" disabled selected>-- Pilih Cabang/Subcabang --</option>
									@foreach($branches as $branch)
										<option value="{{ old('party_id', $branch->id) }}">{{$branch->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group required">
							<label for="company_id" class="col-sm-3 control-label">Warehouse</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="warehouse_id">
									<option value="" disabled>-- Pilih Warehouse --</option>
									@if(!empty(old('warehouse_id')))
										@foreach($warehouses as $warehouse)
											<option value="{{ old('warehouse_id', $warehouse->id) }}">
												{{$warehouse->name}}
											</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label for="company_id" class="col-sm-3 control-label">Storage</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="storage_id">
									<option value="" disabled>-- Pilih Storage --</option>
									@if(!empty(old('storage_id')))
										@foreach($storages as $storage)
											<option value="{{ old('storage_id', $storage->id) }}">
												{{$storage->code}}
											</option>
										@endforeach
									@endif
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
      		</div>
      	</div>
    </div>
</div>
@endsection
			
@section('js')
	<script>
		$(document).ready(function () {
			$('select[name="party_id"]').on('change', function(){
				var value = $(this).val();
	            $.ajax({
	                type: 'GET', //THIS NEEDS TO BE GET
	                url: '/ajax/get_warehouse_project/' + value,
	                dataType: 'json',
	                success: function (data) {
	                	$('select[name="warehouse_id"]').empty();
	                	$.each(data, function(key, value) {
	                        if(key == 0){
	                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
	                        }
	                        else{
	                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
	                        }
	                    });
	                	$('select[name="warehouse_id"]').trigger("change");

	                },
	                error:function(error){
	                    console.log('error ', error);
	                }

	           	});
			});

			var value = $('select[name="party_id"]').val();
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: '/ajax/get_warehouse_project/' + value,
                dataType: 'json',
                success: function (data) {
                	$('select[name="warehouse_id"]').empty();
                	$.each(data, function(key, value) {
                		if(key == 0){
                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
                        }
                        else{
                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        }
                        $('select[name="warehouse_id"]').trigger("change");
                    });
                },
                error:function(error){
                    console.log('error ', error);
                }

           	});

			$('select[name="warehouse_id"]').on('change', function(){
				var value = $(this).val();
	            $.ajax({
	                type: 'GET', //THIS NEEDS TO BE GET
	                url: '/ajax/get_storage_project/' + value,
	                dataType: 'json',
	                success: function (data) {
	                	$('select[name="storage_id"]').empty();
	                	$.each(data, function(key, value) {
	                        if(key == 0){
	                			$('select[name="storage_id"]').append('<option value="'+ value.id +'" selected>'+ value.code +'</option>');	
	                		}
	                		else{
	                			$('select[name="storage_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');	
	                		}
	                    });
	                },
	                error:function(error){
	                    console.log('error ', error);
	                }

	           	});
			});

			var value = $('select[name="warehouse_id"]').val();
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: '/ajax/get_storage_project/' + value,
                dataType: 'json',
                success: function (data) {
                	$('select[name="storage_id"]').empty();
                	$.each(data, function(key, value) {
                		if(key == 0){
                			$('select[name="storage_id"]').append('<option value="'+ value.id +'" selected>'+ value.code +'</option>');	
                		}
                		else{
                			$('select[name="storage_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');	
                		}
                        
                    });
                },
                error:function(error){
                    console.log('error ', error);
                }

           	});
		});
	</script>
@endsection