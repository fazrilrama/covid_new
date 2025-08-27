@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Daftar User Perusahaan - {{$company->name}}</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>User ID:</th>
	                <th>First Name:</th>
	                <th>Last Name:</th>
	                <th>Email:</th>
	                <th>Updated At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $user)
	            <tr>
	                <td>{{$user->id}}</td>
	                <td>{{$user->user_id}}</td>
	                <td>{{$user->first_name}}</td>
	                <td>{{$user->last_name}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->updated_at}}</td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="{{url('users/'.$user->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
		                    <div class="btn-group" role="group">
	                      		<form action="{{url('users', [$user->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
		                      		<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
		                      	</form>
		                    </div>
	                    </div>
	                </td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection