@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>@if($type=='inbound') Putaway @else Picking Plan @endif List
	@if(Auth::user()->hasRole('WarehouseSupervisor'))
		<a href="{{url('stock_entries/create/'.$type)}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> {{ __('lang.create') }}
		</a>
	@endif
	<form action="{{route('stock_entries.index',$type)}}" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
	<!-- @if($type == 'inbound')
	<a href="{{url('to_storage_list/'.$type)}}" type="button" class="btn btn-warning" title="Close Storage">
		<i class="fa fa-database"></i> Tutup Storage
	</a>
	@else
	<a href="{{url('to_storage_list/'.$type)}}" type="button" class="btn btn-primary" title="Close Storage">
		<i class="fa fa-database"></i> Buka Storage
	</a>
	@endif -->
</h1>
@stop

@section('content')
<input type="hidden" value="{{ Auth::user()->hasRole('Transporter') ? 'transporter' : '' }}" id="login">
<div class="table-responsive">
	<table class="data-table table table-bordered table-hover no-margin" id="table-putaway" width="100%">
		<thead>
			<tr>
				<th>Nomor</th>
				@if($type=='inbound')
					<th>PA#:</th>
					<th>GR#:</th>
				@else 
					<th>PP#:</th>
					@if(!Auth::user()->hasRole('Transporter'))
					<th>DP#:</th>
					@endif
					<th>{{ __('lang.consignee') }}</th>
					<th>{{ __('lang.destination') }}</th>
				@endif
				
				<th>{{ __('lang.customer_reference') }}:</th>
				<th>{{ __('lang.warehouse_supervisor') }}:</th>
				<th>{{ __('lang.warehouse_checker') }}:</th>
				<th>Status:</th>
				<th width="15%"></th>
			</tr>
		</thead>

		<tbody>
			@foreach($collections as $item)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{$item->code}}</td>
				<!-- <td>{{empty($item->stock_transport) ?'': $item->stock_transport->code}}</td> -->
				@if(!Auth::user()->hasRole('Transporter'))
				<td>{{$item->transport_code}}</td>
				@endif
				@if($type=='outbound')
				<td>{{ $item->stock_transport->consignee->name }}</td>
				<td>{{ $item->stock_transport->destination->name }}</td>
				@endif
				<td>{{$item->ref_code}}</td>
				<td>
					{{$item->user->user_id}}
				</td>
				<td>
					{{$item->employee_name}}
				</td>
				@if(Auth::user()->hasRole('CargoOwner'))
					<td>
						{{ucfirst(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status)))}}
					</td>
				@else
					<td>
						{{ucfirst(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Submitted' ? 'Completed' : $item->status)))}}
					</td>
				@endif
				<td>
					<div class="btn-toolbar">
						@if(Auth::user()->id == $item->user_id)
							@if($item->editable)
								<div class="btn-group" role="group">
									<a href="{{url('stock_entries/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
								<div class="btn-group" role="group">
									<form action="{{url('stock_entries', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
									</form>
								</div>
							@else
								<div class="btn-group" role="group">
									<a href="{{url('stock_entries/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
										<i class="fa fa-eye"></i>
									</a>
								</div>
							@endif
						@else
							<div class="btn-group" role="group">
								<a href="{{url('stock_entries/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
									<i class="fa fa-eye"></i>
								</a>
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
@section('custom_script')
<script>
	if($('#login').val() == 'transporter') {
		var table = $('#table-putaway').DataTable({
			"order": [[ 0, "asc" ]],
		});
	}
    
</script>
@endsection