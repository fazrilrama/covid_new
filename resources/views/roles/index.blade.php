@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Role List</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>Name:</th>
	                <th>Jumlah User:</th>
	                <th>Updated At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $role)
	            <tr>
	                <td>{{$role->id}}</td>
	                <td>{{$role->name}}</td>
	                <td>{{$role->User->count()}}</td>
	                <td>{{$role->updated_at}}</td>
	                <td>
	                	<div class="btn-toolbar">
							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-RoleList'))
								<div class="btn-group" role="group">
									<a href="{{url('roles/'.$role->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							@endif

							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-RoleList')) 
								<div class="btn-group" role="group">
									<form action="{{url('roles', [$role->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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