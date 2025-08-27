@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center">{{ $stockEntry->type == 'inbound' ? 'Putaway List' : 'Picking Plan List' }}</h3>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'>{{ $stockEntry->type == 'inbound' ? 'PA' : 'PP' }}#</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockEntry->code }}</td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>:</td>
                    <td>{{ $stockEntry->project->company->name }}</td>
                </tr>
                <tr>
                    <td>Warehouse Officer</td>
                    <td>:</td>
                    <td>{{ $stockEntry->employee_name }}</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <!-- <td width='45%'>{{ $stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time' }}</td> -->
                    <td width='45%'>Tanggal Dibuat</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockEntry->created_at }}</td>
                </tr>
                <tr>
                    <!-- <td width='45%'>{{ $stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time' }}</td> -->
                    <td width='45%'>Consignee</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockEntry->stock_transport->consignee->name }}</td>
                </tr>
                <tr>
                    <!-- <td width='45%'>{{ $stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time' }}</td> -->
                    <td width='45%'>Tujuan</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockEntry->stock_transport->destination->name }}</td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU:</th>
                        <th>Item SKU:</th>
                        <th>Group Reference:</th>
                        <th>Control Date:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Storage:</th>
                        <th>Check</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($stockEntry->details as $detail)
                        @if($detail->status <> 'canceled')
                        <tr>
                            <td>{{$detail->item->sku}}</td>
                            <td>{{$detail->item->sku}} - {{$detail->item->name}}</td>
                            <td>{{$detail->ref_code}}</td>
                            <td>{{$detail->control_date}}</td>
                            <td>{{ number_format($detail->qty, 2, ',', '.') }}</td>
                            <td>{{$detail->uom->name}}</td>
                            <td>{{@$detail->storage->code}}</td>
                            <td></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(Auth::user()->hasRole('WarehouseSupervisor'))
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <td align="center"><b>Kepala Gudang<b></td>
                        <td align="center"><b>Checker</b></td>
                        <td align="center" colspan="3"><b>Handling</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="8"style="vertical-align: bottom;text-align: center">
                            @if(Auth::user()->first_name)
                                ({{Auth::user()->first_name}})
                            @else
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            @endif 
                        </td>
                        <td rowspan="8"style="vertical-align: bottom;text-align: center">
                            @if($stockEntry->employee_name)
                                ({{$stockEntry->employee_name}})
                            @else
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            @endif 
                        </td>
                        <td colspan="2">Total Koli</td>
                        <td align="center">
                        {{$stockEntry->total_koli == 0 ? '':$stockEntry->total_koli }}
                        </td>
                       
                        
                    </tr>
                    <tr>
                    <td colspan="2">Total Berat</td>
                        <td align="center">
                        {{$stockEntry->total_berat == 0 ? '':$stockEntry->total_berat }}
                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">Total Volume</td>
                        <td align="center">
                            {{$stockEntry->total_volume == 0 ? '':$stockEntry->total_volume }}
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Pallet</td>
                        <td align="center">
                            {{$stockEntry->total_pallet == 0 ? '':$stockEntry->total_pallet }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Labor</td>
                        <td align="center">
                            {{$stockEntry->total_labor == 0 ? '':$stockEntry->total_labor }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Forklift</td>
                        <td align="center">
                            {{$stockEntry->total_forklift == 0 ? '':$stockEntry->total_forklift }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center">Type</td>
                        <td align="center">Start</td>
                        <td align="center">Finish</td>
                    </tr>
                    <tr>
                        <td align="center">Forklift</td>
                        <td align="center">{{  $stockEntry->forklift_start_time }}</td>
                        <td align="center">{{  $stockEntry->forklift_end_time }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        <div class="col-sm-12 space-top">
            <table width="20%" border="1">
                <thead>
                    <tr>
                        <th align="center"><b>Detail Barang<b></th>
                        <th align="center"><b>Jumlah<b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Total Koli</td>
                    <td align="center">
                    {{$stockEntry->total_koli == 0 ? '':$stockEntry->total_koli }}
                    </td>
                    </tr>
                    <tr>
                    <td>Total Berat</td>
                        <td align="center">
                        {{$stockEntry->total_berat == 0 ? '':$stockEntry->total_berat }}
                        </td>
                    </tr>
                    <tr>
                    <td>Total Volume</td>
                        <td align="center">
                            {{$stockEntry->total_volume == 0 ? '':$stockEntry->total_volume }}
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection