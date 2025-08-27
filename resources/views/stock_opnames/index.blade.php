@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Stock Opname</h1>
	@if(Auth::user()->hasRole('WarehouseSupervisor') || Auth::user()->hasRole('WarehouseManager'))
        <a href="{{route('stock_opnames.create')}}" type="button" class="btn btn-success" title="Create">
            <i class="fa fa-plus"></i> Tambah
        </a>
    @endif
@stop

@section('content')
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Tanggal:</th>
					<th>Calculated By:</th>
					<th>Description:</th>
					<th>Status:</th>
	                <th></th>
	            </tr>
	        </thead>
	     	   
	        <tbody>
				@foreach($collections as $collection)
					<tr>
					<td>{{  Carbon\Carbon::parse($collection->date)->format('d M Y H:m') }}</td>
					<td>
					@if($collection->calculated_by != null)
					<ul>
					@foreach(json_decode($collection->calculated_by) as $key => $value)
						<li><span>{{$value}} </span></li>
						@endforeach
					</ul>
					@endif
					</td>
					<td>
					@if($collection->note != null)
					<ul>
					@foreach(json_decode($collection->note) as $key => $value)
					<li><span>{{$value}} </span></li>
					@endforeach
					</ul>
					@endif
					</td>
					<td>
						{{$collection->status}}
					</td>
					<td>
						<div class="btn-group" role="group">
							<a href="{{ url('stock_opnames/' . $collection->id. '/view') }}" type="button" class="btn btn-primary" title="View">
								<i class="fa fa-eye"></i>
							</a>
						</div>
						@if($collection->status == 'Processed')
						<div class="btn-group" role="group">
							<a href="{{ url('stock_opnames/' . $collection->id .'/edit') }}" type="button" class="btn btn-warning" title="Edit">
								<i class="fa fa-pencil"></i>
							</a>
						</div>
						<div class="btn-group" role="group">
							<form action="{{ url('stock_opnames', [$collection->id]) }}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
								<input type="hidden" name="_method" value="DELETE">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
							</form>
						</div>
						@endif
					</td>
					</tr>
				@endforeach
	      </tbody>
	    </table>
	</div>

@endsection