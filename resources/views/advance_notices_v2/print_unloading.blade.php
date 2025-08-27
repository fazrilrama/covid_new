@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 class="col-sm-12" style="text-align: center;font-weight: bold">
    Tanda Terima
    </h3>
    <h4 class="col-sm-12" style="text-align: center;font-weight: bold;margin-top: 0px">{{$advanceNotice->code}}</h4>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>AIN#</td>
                    <td>:</td>
                    <td>{{ $advanceNotice->code }}</td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>:</td>
                    <td>{{ $advanceNotice->project->company->name }}</td>
                </tr>
                @if($advanceNotice->warehouse)
                    <tr>
                        <td>Warehouse</td>
                        <td>:</td>
                        <td>{{ $advanceNotice->warehouse->name }}</td>
                    </tr>
                @endif
                <tr>
                    <td width='45%'>Asal </td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{$advanceNotice->origin->name}} </td>
                </tr>
                <tr>
                    <td>Kontraktor/Pengangkut</td>
                    <td>:</td>
                    <td>{{$advanceNotice->shipper->name}}</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>No.Polisi Truk</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Koli</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Weight</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Volume</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Item SKU</th>
                    <th rowspan="2">Group Reference</th>
                    <th colspan="2">Total</th>
                </tr>
                <tr>
                    <th>Qty</th>
                    <th>UoM</th>
                </tr>
                </thead>

                <tbody>

                @foreach($advanceNotice->details as $detail)
                    @if($detail->status <> 'canceled')
                        <tr>
                            <td>{{$detail->item->sku}} - {{$detail->item->name}}</td>
                            <td>{{$detail->ref_code }}</td>
                            <td>{{ number_format($detail->qty) }}</td>
                            <td>{{$detail->uom->name }}</td>
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
                    <td align="center"><b>Driver</b></td>
                    <td align="center"><b>Tanggal dan Waktu <br> Penerimaan</b></td>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 80px">
                    <td width="27.5%"rowspan="5" style="vertical-align: bottom;text-align: center">            
                        @if(Auth::user()->first_name)
                            ({{Auth::user()->first_name}})
                        @else
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        @endif 
                    </td>
                    <td  width="27.5%" rowspan="5" style="vertical-align: bottom;text-align: center">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td  width="27.5%" rowspan="5" style="vertical-align: bottom;text-align: center">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td  width="17.5%"rowspan="5" style="vertical-align: bottom;text-align: center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection


