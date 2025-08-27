@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Provinces List
    	<a href="{{url('regions/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	            	<th>Id:</th>
	                <th>Name:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	            	<td>{{$item->id}}</td>
	                <td>{{$item->name}}</td>
	                <td>
	                	<div class="btn-toolbar">
	                		@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-Regions')) 
	                		<div class="btn-group" role="group">
		                      	<a href="{{url('regions/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
		                    @endif

		                    @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-Regions'))
		                    <div class="btn-group" role="group">
	                      		<form action="{{url('regions', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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