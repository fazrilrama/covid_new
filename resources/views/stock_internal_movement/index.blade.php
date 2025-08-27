@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Internal Movement
    @if(Auth::user()->hasRole('WarehouseSupervisor'))
        <a href="{{route('stock_internal_movements.create')}}" type="button" class="btn btn-success" title="Create">
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
	                <th>Code:</th>
	                <th>Storage Origin:</th>
                    <th>Storage Destination:</th>
	                <th>Item:</th>
	                <th>Ref Code:</th>
	                <th>Jumlah:</th>
	                <th>Status:</th>
	                <th>Action</th>
	            </tr>
	        </thead>
	        
	        <tbody>
            @foreach($internal_movements as $internal_movement)
            <tr>
				<td>{{ $internal_movement->code }}</td>
				<td>
					<table>
						@foreach($internal_movement->detailmovement as $detail)
						<tr>
							<td>{{ $detail->origin_storage ? $detail->origin_storage->code : '' }}</td>
						</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table>
						@foreach($internal_movement->detailmovement as $detail)
						<tr>
							<td>{{ $detail->dest_storage ? $detail->dest_storage->code : '' }}</td>
						</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table>
						@foreach($internal_movement->detailmovement as $detail)
						<tr>
							<td>{{ $detail->item ? $detail->item->sku : ''}} - {{ $detail->item ? $detail->item->name : '' }}</td>
						</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table>
						@foreach($internal_movement->detailmovement as $detail)
						<tr>
							<td>{{ $detail->ref_code }} </td>
						</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table>
						@foreach($internal_movement->detailmovement as $detail)
						<tr>
							<td>{{ $detail->movement_qty }} {{ $detail->uom ? $detail->uom->name : ''}} </td>
						</tr>
						@endforeach
					</table>
				</td>
				<td>
					{{ $internal_movement->status  }}
				</td>
				<td>
					<div class="btn-group" role="group">
						<a href="{{ url('stock_internal_movements/' . $internal_movement->id) }}" type="button" class="btn btn-primary" title="View">
							<i class="fa fa-eye"></i>
						</a>
					</div>
					@if($internal_movement->status == 'Processed')
					<div class="btn-group" role="group">
						<a href="{{ url('stock_internal_movements/' . $internal_movement->id .'/edit') }}" type="button" class="btn btn-warning" title="Edit">
							<i class="fa fa-pencil"></i>
						</a>
					</div>
					<div class="btn-group" role="group">
						<form action="{{ url('stock_internal_movements', [$internal_movement->id]) }}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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