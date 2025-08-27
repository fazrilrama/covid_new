@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    @if($stockTransport->status == 'Completed')
        <h3 style="text-align: center;">
            {{ $stockTransport->type == 'inbound' ? 'Good Receiving' : 'Delivery Planning' }}
        </h3>
        <h4 style="text-align: center;">
            
            <p style="margin:0px;font-weight: bold">
                {{$stockTransport->code}}
            </p>
        </h4>
        @elseif($stockTransport->status == 'Processed' AND $stockTransport->details()->sum('qty') > 0)
        <h3 style="text-align: center;" style="text-align: center;">
            {{ $stockTransport->type == 'inbound' ? 'Good Received' : 'Delivery Planned' }}
        </h3>
    @elseif($stockTransport->status == 'Processed' AND $stockTransport->details()->sum('qty') == 0)
        <h3 style="text-align: center;">
            {{ $stockTransport->type == 'inbound' ? 'Tally Sheet' : 'Delivery Plan' }}
        </h3>
        @endif
    <div class="row">
        @if( $stockTransport->type == 'inbound' )
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>GR#</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $stockTransport->code }}</td>
                    </tr>
                    <tr>
                        <td>AIN#</td>
                        <td>:</td>
                        <td>{{ $stockTransport->advance_notice->code }}</td>
                    </tr>
                    <tr>
                        <td width='45%'>Tanggal Dibuat</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $stockTransport->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td>{{ $stockTransport->project->company->name }}</td>
                    </tr>
                    <tr>
                        <td>Warehouse Officer</td>
                        <td>:</td>
                        <td>{{ $stockTransport->employee_name }}</td>
                    </tr>
                    @if($stockTransport->warehouse)
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td>{{ $stockTransport->warehouse->name }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>Asal dan Tanggal Tiba</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{$stockTransport->origin->name}} / {{$stockTransport->etd}}</td>
                    </tr>
                    <tr>
                        <td>Kontraktor/Pengangkut</td>
                        <td>:</td>
                        <td>{{$stockTransport->shipper->name}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Penerimaan</td>
                        <td>:</td>
                        <td>{{ $stockTransport->pickup_order }}</td>
                    </tr>
                    <tr>
                        <td>Nama Driver</td>
                        <td>:</td>
                        <td>{{ $stockTransport->driver_name }}</td>
                    </tr>
                    <tr>
                        <td>No.Telp Driver</td>
                        <td>:</td>
                        <td>{{ $stockTransport->driver_phone }}</td>
                    </tr>
                    <tr>
                        <td>No.Polisi Truk</td>
                        <td>:</td>
                        <td>{{ $stockTransport->police_number }}</td>
                    </tr>
                </table>
            </div>
        @else
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>DP#</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $stockTransport->code }}</td>
                    </tr>
                    <!-- <tr>
                        <td>AON#</td>
                        <td>:</td>
                        <td>{{ $stockTransport->advance_notice->code }}</td>
                    </tr> -->
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td>{{ $stockTransport->project->company->name }}</td>
                    </tr>
                    <tr>
                        <td>Delivered From</td>
                        <td>:</td>
                        <td>{{ $stockTransport->origin->name }}</td>
                    </tr>
                    <tr>
                        <td>Warehouse Officer</td>
                        <td>:</td>
                        <td>{{ $stockTransport->employee_name }}</td>
                    </tr>
                    @if($stockTransport->warehouse)
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td>{{ $stockTransport->warehouse->name }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>Tanggal Dibuat</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $stockTransport->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Delivered To</td>
                        <td>:</td>
                        <td>{{ $stockTransport->destination->name }}</td>
                    </tr>
                </table>
            </div>
        @endif
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Item SKU</th>
                    <th rowspan="2">Group Reference</th>
                    @if( $stockTransport->type == 'inbound' )
                        <th colspan="4">Plan</th>
                    @endif
                    @if( $stockTransport->type == 'inbound' )
                        <th colspan="3">Actual</th>
                    @else
                        <th colspan="4">Actual</th>
                    @endif
                    
                </tr>
                <tr>
                    <th>Qty</th>
                    <th>UoM</th>
                    <th>Weight</th>
                    <th>Volume</th>
                    @if( $stockTransport->type == 'inbound' )
                        <th>Qty</th>
                        <th>Weight</th>
                        <th>Volume</th>
                    @endif
                </tr>
                </thead>

                <tbody>

                @foreach($stockTransport->details as $detail)
                    @if($detail->status <> 'canceled')
                        <tr>
                            <td>{{$detail->item->sku}} - {{$detail->item->name}}</td>
                            <td>{{$detail->ref_code }}</td>
                            <td>{{ number_format($detail->plan_qty) }}</td>
                            <td>{{$detail->uom->name }}</td>
                            <td>{{ number_format($detail->plan_weight) }}</td>
                            <td>{{ number_format($detail->plan_volume) }}</td>
                            @if( $stockTransport->type == 'inbound' )
                                <td>{{ number_format($detail->qty == 0 ? 0:$detail->qty) }}</td>
                                <td>{{ number_format($detail->weight == 0 ? 0:$detail->weight) }}</td>
                                <td>{{ number_format($detail->volume == 0 ? 0:$detail->volume) }}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                <tr>
                    <td align="center"><b>Kepala Gudang<b></td>
                    <td align="center"><b>Checker</b></td>
                    @if( $stockTransport->type == 'inbound' )
                        <td align="center"><b>Driver</b></td>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr style="height: 80px">
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">            
                        @if(Auth::user()->first_name)
                            ({{Auth::user()->first_name}})
                        @else
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        @endif 
                    </td>
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">
                        @if($stockTransport->employee_name)
                            ({{$stockTransport->employee_name}})
                        @else
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        @endif                
                    </td>
                    @if( $stockTransport->type == 'inbound' )
                        <td rowspan="5" style="vertical-align: bottom;text-align: center">
                            @if($stockTransport->driver_name)
                                ({{$stockTransport->driver_name}})
                            @else
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            @endif
                        </td>
                    @endif
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection


