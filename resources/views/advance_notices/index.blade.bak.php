@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>@if($type=='inbound') AIN @else AON @endif List
    
    <form action="{{route('advance_notices.index',$type)}}" method="GET">
        @if(Auth::user()->hasRole('CargoOwner'))
            <a href="{{url('advance_notices/create/'.$type)}}" type="button" class="btn btn-sm btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah
            </a>
        @endif
        <button class="btn btn-sm btn-warning" name="submit" value="1">
            <i class="fa fa-download"></i> Export ke Excel
        </button>
    </form>
</h1>
@stop

@section('content')
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
                    <th>ID#:</th>
	                <th>@if($type=='inbound') AIN @else AON @endif:</th>
                    <th>Storage Area:</th>
	                <th>Origin:</th>
                    <th>Destination:</th>
	                <!-- <th>ETD:</th>
	                <th>ETA:</th> -->
	                <th>Outstanding:</th>
                    <!-- <th>Arrived</th> -->
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
	        @if(isset($collections))
            @foreach($collections as $item)
	            <tr>
                    <td>{{$item->id}}</td>
	                <td>{{$item->item_code}}</td>
                    <td>
                        {{ (isset($item->item_storage_area)) ? $item->item_storage_area : '-'}}
                    </td>
	                <td>{{!empty($item->item_origin) ? $item->item_origin : ''}}</td>
	                <td>{{!empty($item->item_destination) ? $item->item_destination : ''}}</td>
	                <!-- <td>{{$item->item_etd}}</td>
	                <td>{{$item->item_eta}}</td> -->
	                <td align="center">
                        {{ number_format($item->item_outstanding, 2, ',', '.') }}
                        <p>
                            <select>
                                @foreach($detail_advance_notices[$item->id] as $detail)
                                    <option>
                                        {{ $detail->item->name }} | {{ $detail->ref_code }} | ots: {{ $detail->detail_outstanding }}
                                    </option>
                                @endforeach
                            </select>
                        </p>
                    </td>
                    <!-- <td align="center">
                        @if($item->is_arrived == 0)
                            No 
                        @else
                            Yes
                        @endif
                    </td> -->
	                <td>
                        @if(Auth::user()->hasRole('CargoOwner'))
                            {{ ($item->item_status == 'Pending' ? 'Planning' : ($item->item_status == 'Completed' ? 'Submitted' : $item->item_status)) }}
                        @else
                            {{ ($item->item_status == 'Pending' ? 'Planning' : $item->item_status ) }}
                        @endif
                    </td>
                    @if(!Auth::user()->hasRole('CargoOwner'))
    	                <td width="15%">
                            {{ ($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial') }}
                        </td>
                    @endif
                    @if(Auth::user()->hasRole('WarehouseManager'))
			@if($item->user2)
                        	<td>{{$item->user2->first_name}} {{$item->user2->last_name}}</td>
                        @else
                        	<td>spv belum di assign</td>
                     
                        @endif
                    @endif
	                <td>
	                	<div class="btn-toolbar">
	                		@if(Auth::user()->id == $item->user_id)
                                @if($item->editable)
                                    <div class="btn-group" role="group">
                                        <a href="{{ url('advance_notices/' . $item->id .'/edit') }}" type="button" class="btn btn-primary" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <form action="{{ url('advance_notices', [$item->id]) }}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                        </form>
                                    </div>
                                    @else
                                    <div class="btn-group" role="group">
                                        <a href="{{ url('advance_notices/' . $item->id. '/show') }}" type="button" class="btn btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="btn-group" role="group">
                                    <a href="{{ url('advance_notices/' . $item->id . '/show') }}" type="button" class="btn btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            @endif
	                    </div>
	                </td>
	            </tr>
	        @endforeach
            @endif
	      </tbody>
	    </table>
	</div>
@endsection
