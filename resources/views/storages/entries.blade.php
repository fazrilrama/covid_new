@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Storage Entries List #{{$storage->code}}</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Item SKU:</th>
	                <th>Qty:</th>
	                <th>Uom:</th>
	                <th>Weight:</th>
	                <th>Volume:</th>
	                <th>Updated At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($storage->stock_entry_detail as $stock_entry_detail)
	            <tr>
	                <td>{{$stock_entry_detail->item->sku}}</td>
	                <td>{{$stock_entry_detail->qty}}</td>
	                <td>{{$stock_entry_detail->uom->name}}</td>
	                <td>{{$stock_entry_detail->weight}}</td>
	                <td>{{$stock_entry_detail->volume}}</td>
	                <td>{{$stock_entry_detail->updated_at}}</td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection