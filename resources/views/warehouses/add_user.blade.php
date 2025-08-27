@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Add User - {{$warehouse->name}}</h1>
@stop

@section('content')
<!-- form start -->
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
						<input type="hidden" name="warehouse_id" value="{{$warehouse->id}}" />
						<div class="form-group">
							<label class="col-sm-3 control-label">Cabang/Subcabang</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="{{$party->name}}" readonly>
							</div>
						</div>
						<div class="form-group required">
							<label for="user_id" class="col-sm-3 control-label">Pilih User</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="user_id" required>
				                    <option value="" disabled>-- Pilih User --</option>
									@foreach($user_officer_supervisor as $user)
										@if($user->warehouse_name == 'free')
											<option value="{{ $user->id }}">
				                            	{{$user->first_name}} - {{ $user->last_name }} 
				                            	({{$user->role}})
				                            	({{$user->warehouse_name}})
				                            </option>
				                        @else
				                        	<option value="{{ $user->id }}" disabled>
				                            	{{$user->first_name}} - {{ $user->last_name }} 
				                            	({{$user->role}})
				                            	({{$user->warehouse_name}})
				                            </option>
				                        @endif
									@endforeach


								</select>
							</div>
						</div>
						
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-left">Simpan</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Warehouse User List</h3>
				<div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		         </div>
		         <div class="table-responsive">
		          	<table class="table" width="100%">
		              	<thead>
		                	<tr>
			                    <th>Name</th>
			                    <th>Role</th>
			                    <th>Action</th>
			                </tr>
		              	</thead>

		              	<tbody>
			                @foreach($warehouse_officers as $wo)
			                  	<tr>
			                      	<td>{{$wo->user->first_name}} {{$wo->user->last_name}}</td>
			                      	<td>{{$wo->user->roles->first()->name}}</td>
			                      	<td>
			                        	<div class="btn-group" role="group">
			                          		<form action="{{route('delete_warehouse_officer')}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
					                            <input type="hidden" name="_method" value="DELETE">
					                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
					                            <input type="hidden" name="warehouse_officer_id" value="{{$wo->id}}">
					                            <input type="hidden" name="warehouse_id" value="{{$wo->warehouse_id}}">
					                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
			                          		</form>
			                        	</div>
			                      	</td>
			                  	</tr>
			                @endforeach
		              	</tbody>
		          	</table>
		        </div>
			</div>
		</div>
	</div>
</div>

@endsection