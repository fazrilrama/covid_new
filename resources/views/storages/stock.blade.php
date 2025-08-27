@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Available Storage List</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Warehouse:</th>
	                <th>Storage Code:</th>
	                <th>Komoditas:</th>
	                <th>Volume:</th>
	                <th>Weight:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{empty($item->warehouse) ?'': $item->warehouse->code}}</td>
	                <td>{{$item->code}}</td>
	                <td>{{empty($item->commodity) ?'': $item->commodity->name}}</td>
	                <td>{{$item->volume}}</td>
	                <td>{{$item->weight}}</td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="{{url('storages/'.$item->id.'/entries')}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-eye"></i>
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