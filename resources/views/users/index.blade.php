@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Daftar User
    	<a href="{{url('users/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
	</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>User ID:</th>
	                <th>Nama Depan:</th>
	                <th>Nama Belakang:</th>
	                <th>Cabang/Subcabang:</th>
	                <th>Role:</th>
	                <th>Project:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $user)
	            <tr>
	                <td>{{$user->user_id}}</td>
	                <td>{{$user->first_name}}</td>
	                <td>{{$user->last_name}}</td>
	                <td>{{@$user->branch->name}}</td>
	                <td>
	                	@foreach($user->roles()->orderBy('name')->get() as $role)
	                		{{$role->name}}<br/>
	                	@endforeach
	                </td>
	                <td>
	                	<select>
	                		@foreach($user->projects()->orderBy('name','asc')->get() as $project)
	                			<option>{{$project->name}}</option>
		                	@endforeach
	                	</select>
	                	
	                </td>
	                <td>
	                	<div class="btn-toolbar">
							@if(Auth::user()->hasRole('Superadmin|Admin-Client') || Auth::user()->can('update-UserList'))
								<div class="btn-group" role="group">
									<a href="{{url('users/'.$user->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							@endif

							@if(Auth::user()->hasRole('Superadmin|Admin-Client') || Auth::user()->can('delete-UserList'))
								<div class="btn-group" role="group">
									<form action="{{url('users', [$user->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
									</form>
								</div>
							@endif
	                    </div>
	                </td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection