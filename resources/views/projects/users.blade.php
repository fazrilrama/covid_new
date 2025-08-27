@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Daftar User Project - {{$project->name}}</h1>
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
		                      	<a href="{{route('users.edit_projects',$user->id)}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
	                    </div>
	                </td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection