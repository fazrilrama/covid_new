@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Daftar User Login</h1>
@stop

@section('content')
	<p>Total User Login: {{ $total_user }} User</p>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	            	<th>Last Login</th>
	                <th>Full Name</th>            
	            </tr>
	        </thead>
	        
	        <tbody>
		        @foreach($collections as $item)
		            <tr>
		            	<td>{{$item->created_at}}</td>
		                <td>{{$item->user->first_name}} {{$item->user->last_name}}</td>		                
		            </tr>
		        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection
