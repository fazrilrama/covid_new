@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Stock Opname</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>SKU:</th>
					<th>Item:</th>
					<th>Stock Awal #:</th>
					<th>Allocated:</th>
					<th>Available:</th>
					<th>Actual Qty:</th>
					<th>Difference:</th>
					<th>Description:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $collection)
	            <tr>
	                <td></td>
	                <td>{{ $collection->sku }}</td>
	                <td>{{ $collection->name }}</td>
	                <td>{{ $collection->on_hand }}</td>
	                <td>{{ $collection->allocated }}</td>
	                <td>{{ $collection->available }}</td>
	                <td>{{ $collection->actual_qty }}</td>
	                <td>{{ $collection->difference }}</td>
	                <td>{{ $collection->description }}</td>
                    <td>
                        <a href="{{ route('opnames.edit', ['item' => $collection->id ]) }}" class="btn btn-large btn-block btn-primary btn-flat">
                            <i class="fa fa-edit"></i> Entry
                        </a>
                    </td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection