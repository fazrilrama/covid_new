@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>@if($type=='inbound') Advance Inbound Notice @else Advance Outbound Notice @endif List
    
    <form action="{{route('advance_notices.index',$type)}}" method="GET">
        @if(Auth::user()->hasRole('CargoOwner') || (Auth::user()->hasRole('WarehouseSupervisor') && session()->get('current_project')->id == '337'))
            <a href="{{url('advance_notices/create/'.$type)}}" type="button" class="btn btn-sm btn-success" title="Create">
                <i class="fa fa-plus"></i> {{ __('lang.create') }}
            </a>
        @endif
    </form>
</h1>
@stop

@section('content')
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Processed</a></li>
  @if(!Auth::user()->hasRole('CargoOwner'))
  <li><a data-toggle="tab" href="#menu2">Closed</a></li>
  @endif
</ul>

<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
        <div class="table-responsive">
            <table class="data-table table table-bordered table-hover no-margin" width="100%">
            
                <thead>
                    <tr>
                        @if(Auth::user()->hasRole('Reporting'))
                        <th>NO#:</th>
                        @else
                        <th>ID#:</th>
                        @endif
                        <th>{{__('lang.no_refference')}}:</th>
                        <th>@if($type=='inbound') AIN @else AON @endif:</th>
                        <th>@if($type=='inbound') {{__('lang.shipper')}}: @else {{__('lang.consignee')}}: @endif</th>
                        <th>{{__('lang.storage_area')}}:</th>
                        <th>{{__('lang.origin')}}:</th>
                        <th>{{__('lang.destination')}}:</th>

                        <!-- <th>ETD:</th>
                        <th>ETA:</th> -->
                        <th>Outstanding:</th>
                        <!-- <th>Arrived</th> -->
                        @if(Auth::user()->hasRole('CargoOwner'))
                        <th>Status:</th>
                        @endif
                        @if(!Auth::user()->hasRole('CargoOwner'))
                        <th>{{__('lang.receiving_status')}}:</th>
                        @endif
                        @if(Auth::user()->hasRole('WarehouseManager'))
                        <th>{{__('lang.assign_to')}}:</th>
                        @endif
                        <th>{{__('lang.created_at')}}:</th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody>
                @if(isset($collections))
                @foreach($collections as $item)
                    <tr>
                        @if(Auth::user()->hasRole('Reporting'))
                        <td>{{$loop->iteration}}</td>
                        @else
                        <td>{{$item->id}}</td>
                        @endif
                        <td>{{$item->item_ref_code}}</td>
                        <td>{{$item->item_code}}</td>
                        <td width="20%">@if($type=='inbound') {{ $item->consingee_name }} @else {{ $item->consingee_name  }} @endif</td>
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
                        @if(Auth::user()->hasRole('CargoOwner'))
                        <td>
                                {{ ($item->item_status == 'Pending' ? 'Planning' : ($item->item_status == 'Completed' ? 'Submitted' : $item->item_status)) }}
                           
                        </td>
                        @endif
                        @if(!Auth::user()->hasRole('CargoOwner'))
                            <td width="15%">
                                {{ ($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial') }}
                            </td>
                        @endif
                        @if(Auth::user()->hasRole('WarehouseManager'))
                            <td>{{ ($item->item_employee_name) }}</td>
                        @endif
                        <td> {{ $item->item_created_at }}</td>
                        
                        <td>
                            <div class="btn-toolbar">
                                @if(Auth::user()->id == $item->user_id || (Auth::user()->hasRole('WarehouseSupervisor') && session()->get('current_project')->id == '337'))
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
    </div>
    @if(!Auth::user()->hasRole('CargoOwner'))
    <div id="menu2" class="tab-pane">
        <div class="table-responsive">
            <table class="data-table table table-bordered table-hover no-margin" width="100%">
            
                <thead>
                    <tr>
                        @if(Auth::user()->hasRole('Reporting'))
                        <th>NO#:</th>
                        @else
                        <th>ID#:</th>
                        @endif
                        <th>{{__('lang.message')}}:</th>
                        <th>@if($type=='inbound') AIN @else AON @endif:</th>
                        <th>@if($type=='inbound') {{__('lang.shipper')}}: @else {{__('lang.consignee')}}: @endif</th>
                        <th>{{__('lang.storage_area')}}:</th>
                        <th>{{__('lang.origin')}}:</th>
                        <th>{{__('lang.destination')}}:</th>

                        <!-- <th>ETD:</th>
                        <th>ETA:</th> -->
                        <th>Outstanding:</th>
                        <!-- <th>Arrived</th> -->
                        @if(Auth::user()->hasRole('CargoOwner'))
                        <th>Status:</th>
                        @endif
                        @if(!Auth::user()->hasRole('CargoOwner'))
                        <th>{{__('lang.receiving_status')}}:</th>
                        @endif
                        @if(Auth::user()->hasRole('WarehouseManager'))
                        <th>{{__('lang.assign_to')}}:</th>
                        @endif
                        <th>{{__('lang.created_at')}}:</th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody>
                @if(isset($collections_closed))
                @foreach($collections_closed as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->item_code}}</td>
                        <td>{{$item->item_ref_code}}</td>
                        <td width="20%">@if($type=='inbound') {{ $item->consingee_name }} @else {{ $item->consingee_name  }} @endif</td>
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
                        @if(!Auth::user()->hasRole('CargoOwner'))
                            <td width="15%">
                                {{ ($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial') }}
                            </td>
                        @endif
                        @if(Auth::user()->hasRole('WarehouseManager'))
                            <td>{{ ($item->item_employee_name) }}</td>
                        @endif
                        <td> {{ $item->item_created_at }}</td>
                        
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
    </div>
    @endif
</div
@endsection
