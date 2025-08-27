@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>@if($type=='inbound') Goods Receiving @else Delivery Plan @endif List
	@if(Auth::user()->hasRole('WarehouseSupervisor'))
	  	<a href="{{url('stock_transports/create/'.$type)}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> {{ __('lang.create') }}
	    </a>
	@endif
	@if($type=='inbound')
	<form action="{{route('stock_transports.index',$type)}}" method="GET">
		<button class="btn btn-sm btn-success" name="submit" value="2">
			<i class="fa fa-download"></i> Export Data
		</button>
	</form>
	@endif
</h1>
@stop

@section('content')
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
			<thead>
				<tr>
					@if($type=='inbound')
					<th>GR#:</th>
					@else
					<th>DP#:</th>
					@endif
					@if($type=='inbound')
					<th>AIN#:</th>
					@else
					<th>AON#:</th>
					@endif
					<th>{{ __('lang.origin') }}:</th>
					<th>{{ __('lang.destination') }}:</th>
					<th>ETD:</th>
					<th>ETA:</th>
					<th>{{ __('lang.shipper') }}:</th>
					@if($type=='inbound')
					<th>{{ __('lang.consignee') }}:</th>
					@endif
					<th>Status:</th>
					<!-- <th>Updated At:</th> -->
					<th width="15%"></th>
				</tr>
			</thead>
	        
	        <tbody>

	        @foreach($collections as $item)
	            <tr>
                  <td>{{$item->code}}</td>
	                <td>{{$item->advance_code}}</td>
	                <td>{{ !empty($item->origin) ? $item->origin : ''}}</td>
	                <td>{{ !empty($item->destination) ? $item->destination : ''}}</td>
	                <td>{{$item->etd}}</td>
	                <td>{{$item->eta}}</td>
					<td>{{$item->shipper_name}}</td>
					@if($type=='inbound')
					<td>{{ !empty($item->consignee_name) ? $item->consignee_name : ''}}</td>
					@endif
					<td>{{($item->status == 'Pending') ? 'Planning' : $item->status}}</td>
					<td>
	                	<div class="btn-toolbar">
                            @if(Auth::user()->id == $item->user_id)
								@if($item->status == 'Pending' || $item->status == 'Processed')
		                		<div class="btn-group" role="group">
			                      	<a href="{{url('stock_transports/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
			                      		<i class="fa fa-pencil"></i>
			                      	</a>
			                    </div>
			                    <div class="btn-group" role="group">
		                      		<form action="{{url('stock_transports', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
	          									<input type="hidden" name="_method" value="DELETE">
	          									<input type="hidden" name="_token" value="{{ csrf_token() }}">
			                      		<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
			                      	</form>
			                    </div>
		                    	@else
			                    <div class="btn-group" role="group">
									<a href="{{url('stock_transports/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
										<i class="fa fa-eye"></i>
									</a>
								</div>
								@endif
							@else
								<div class="btn-group" role="group">
									<a href="{{url('stock_transports/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
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