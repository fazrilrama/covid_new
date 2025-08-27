@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Daftar User Terkunci</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>User ID:</th>
	                <th>Name:</th>
	                <th>Email:</th>
	                <th>Locked At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->user_id}}</td>
	                <td>{{$item->first_name}}</td>
	                <td>{{$item->email}}</td>
	                <td>{{$item->created_at}}</td>
	                <td><a href="{{ route('user-locked-unlock', ['user_id' => $item->user_id]) }}" class="btn btn-primary">Unlock</a></td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection