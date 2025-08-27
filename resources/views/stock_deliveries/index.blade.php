@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
	<h1>Goods Issue List
		@if(Auth::user()->hasRole('WarehouseSupervisor'))
			<a href="{{url('stock_deliveries/create/'.$type)}}" type="button" class="btn btn-success" title="Create">
				<i class="fa fa-plus"></i> Tambah
			</a>
		@endif
	</h1>
	<form action="{{route('stock_deliveries.index',$type)}}" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
@stop

@section('content')

	<div class="table-responsive">
		<table class="data-table table table-bordered table-hover no-margin" width="100%">
			<thead>
			<tr>
				@if(Auth::user()->hasRole('Reporting'))
				<th>Nomor:</th>
				@endif
				<th>GI#:</th>
				@if(!Auth::user()->hasRole('Reporting'))
				<th>PP#:</th>
				@endif
				<th>AON#:</th>
				<th>{{ __('lang.consignee') }}:</th>
				<th>{{ __('lang.origin') }}:</th>
				<th>{{ __('lang.destination') }}:</th>
				@if(!Auth::user()->hasRole('Reporting'))
				<th>{{ __('lang.warehouse_supervisor') }}:</th>
				@endif
				<th>{{ __('lang.created_at') }}:</th>
				<th>{{ __('lang.eta') }}:</th>
	            <th>Status</th>
	            <th>{{ __('lang.received_date') }}</th>
				<th width="15%"></th>
			</tr>
			</thead>

			<tbody>
			@foreach($collections as $item)
				<tr>
					@if(Auth::user()->hasRole('Reporting'))
					<td>{{ $loop->iteration }}</td>
					@endif
					<td>{{$item->code}}</td>
					@if(!Auth::user()->hasRole('Reporting'))
					<td>{{$item->stock_entry ? $item->stock_entry->code: ''}}</td>
					@endif
					<td>{{$item->stock_entry ? $item->stock_entry->stock_transport->advance_notice->code : '' }}</td>
					<td>{{$item->consignee ? $item->consignee->name : ''}}</td>
					<td>{{$item->origin ? $item->origin->name : ''}}</td>
					<td>{{$item->destination ? $item->destination->name : ''}}</td>
					@if(!Auth::user()->hasRole('Reporting'))
					<td>{{$item->user ? $item->user->user_id : ''}}</td>
					@endif
					<td>{{$item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:m') : $item->etd }}</td>
					<td>{{$item->eta}}</td>
	                <td>
	                	@if(Auth::user()->hasRole('CargoOwner'))
                        {{ ($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status)) }}
                        @else
                        {{ ($item->status == 'Pending' ? 'Planning' : $item->status ) }}
                        @endif
	                </td>
					<td>{{ $item->date_received }}</td>
					<td>
						<div class="btn-toolbar">
							@if( ($item->status == 'Processed') || ($item->status == 'Pending'))
							<div class="btn-group" role="group">
								<a href="{{url('stock_deliveries/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
									<i class="fa fa-pencil"></i>
								</a>
							</div>
							<div class="btn-group" role="group">
								<form action="{{url('stock_deliveries', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
								</form>
							</div>
							@elseif($item->status == 'Completed' || $item->status == 'Received')
							<div class="btn-group" role="group">
								<a href="{{url('stock_deliveries/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						</div>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>

@endsection