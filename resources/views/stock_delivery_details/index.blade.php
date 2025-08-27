@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Stock Delivery Detail List
    	<a href="{{url('stock_delivery_details/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>Advance Notice:</th>
	                <th>Item SKU:</th>
	                <th>UOM:</th>
	                <th>Qty:</th>
	                <th>Created At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->id}}</td>
	                <td>{{$item->header->code}}</td>
	                <td>{{$item->item->sku}}</td>
	                <td>{{$item->uom->name}}</td>
	                <td>{{$item->qty}}</td>
	                <td>{{$item->created_at}}</td>
	                <td>
	                	<div class="btn-toolbar">
	                		<div class="btn-group" role="group">
		                      	<a href="{{url('stock_delivery_details/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
		                    <div class="btn-group" role="group">
	                      		<form action="{{url('stock_delivery_details', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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