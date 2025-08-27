@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Item List
	@if(!Auth::user()->hasRole('AdminBulog'))
    	<a href="{{url('items/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
	@endif
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>SKU:</th>
	                <th>Name:</th>
	                <th>Ref:</th>
	                <th>Uom:</th>
	                <th>Control Method:</th>
	                <th>Komoditas:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->sku}}</td>
	                <td>{{$item->name}}</td>
	                <td>{{$item->additional_reference}}</td>
	                <td>
		                @if($item->default_uom)
		                	{{$item->default_uom->name}}
		                @endif
	                </td>
	                <td>{{$item->control_method->name}}</td>
	                <td>{{$item->commodity->name}}</td>
	                <td>
	                	<div class="btn-toolbar">
							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('WarehouseManager'))
								<div class="btn-group" role="group">
									<a href="{{url('items/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							@endif

							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('WarehouseManager'))
								<div class="btn-group" role="group">
									<form action="{{url('items', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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