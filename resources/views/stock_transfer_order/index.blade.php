@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>@if($type=='inbound') STOI @else STOO @endif List
    @if(Auth::user()->hasRole('CargoOwner'))
        <a href="{{url('stock_transfer_order/create/'.$type)}}" type="button" class="btn btn-success" title="Create">
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
	                <th>@if($type=='inbound') STOI @else STOO @endif#:</th>
                    <th>Storage Area:</th>
	                <th>Origin:</th>
                    <th>Destination:</th>
	                <th>ETD:</th>
	                <th>ETA:</th>
	                <th>Outstanding:</th>
                    <th>Arrived</th>
	                <th>Status:</th>
                    @if(!Auth::user()->hasRole('CargoOwner'))
	                <th>Receiving Status:</th>
                    @endif
                    @if(Auth::user()->hasRole('WarehouseManager'))
                    <th>Assign To:</th>
                    @endif
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->code}}</td>
                    <td>
                        @if($type=='inbound')
                            {{ (isset($item->consignee->name)) ? $item->consignee->name : '-'}}
                        @else
                            {{ (isset($item->shipper->name)) ? $item->shipper->name : '-'}}
                        @endif
                    </td>
	                <td>{{!empty($item->origin->name) ? $item->origin->name : ''}}</td>
	                <td>{{!empty($item->destination->name) ? $item->destination->name : ''}}</td>
	                <td>{{$item->etd}}</td>
	                <td>{{$item->eta}}</td>
	                <td align="center">{{ number_format($item->outstanding, 2, ',', '.') }}</td>
                    <td align="center">
                        @if($item->is_arrived == 0)
                            No 
                        @else
                            Yes
                        @endif
                    </td>
	                <td>@if(Auth::user()->hasRole('CargoOwner'))
                        {{ ($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status)) }}
                        @else
                        {{ ($item->status == 'Pending' ? 'Planning' : $item->status ) }}
                        @endif
                    </td>
                    @if(!Auth::user()->hasRole('CargoOwner'))
	                <td width="15%">{{ ($item->outstanding == 0  && ($item->status == 'Completed' || $item->status == 'Closed') ? 'Full' : 'Partial') }}</td>
                    @endif
                    @if(Auth::user()->hasRole('WarehouseManager'))
                    <td>{{$item->employee_name}}</td>
                    @endif
	                <td>
	                	<div class="btn-toolbar">
	                		@if(Auth::user()->id == $item->user_id)
                                @if($item->editable)
                                    <div class="btn-group" role="group">
                                        <a href="{{url('stock_transfer_order/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <form action="{{url('stock_transfer_order', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                        </form>
                                    </div>
                                    @else
                                    <div class="btn-group" role="group">
                                        <a href="{{url('stock_transfer_order/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="btn-group" role="group">
                                    <a href="{{url('stock_transfer_order/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="View">
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