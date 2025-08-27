@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center;">
        Laporan Stok Wina, Bulog, Fisik
    </h3>
    <div class="row">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <table width="100%">
                    <tr>
                        <td width='25%'>Nama Branch</td>
                        <td width='5%'>:</td>
                        <td width='70%'>{{ $warehouse->branch->name }}</td>
                    </tr>
                    <tr>
                        <td>Nama Gudang</td>
                        <td>:</td>
                        <td>{{ $warehouse->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
                <table width="100%">
                <tr>
                        <td width='45%'>Stok Awal</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $filter }}</td>
                    </tr>
                    <tr>
                        <td width='45%'>Stok Akhir</td>
                        <td width='5%'>:</td>
                        <td width='50%'>{{ $filter_2 }}</td>
                    </tr>
                </table>
            </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Project</th>
                    <th rowspan="2">SKU</th>
                    <th rowspan="2">Nama SKU </th>
                    <th rowspan="2">UoM</th>
                    <th rowspan="2">GR</th>
                    <th rowspan="3">Brutto</th>
                    <th rowspan="2">Koli</th>
                    <th colspan="3">Netto</th>
                </tr>
                <tr>
                    <th>Wina</th>
                    <th>Bulog</th>
                    <th>Fisik </th>
                </tr>
                </thead>

                <tbody>

                @foreach($data as $detail)
                    <tr>
                        <td>{{$detail['sku']}}</td>
                        <td>{{$detail['name_sku']}}</td>
                        <td>{{$detail['uom_name']}}</td>
                        <td>{{$detail['code']}}</td>
                        <td>{{ number_format($detail['qty'], 2, ',', '.') }}</td>
                        <td>{{ number_format($detail['koli'], 2, ',', '.') }}</td>
                        <td>{{ number_format($detail['netto'], 2, ',', '.') }}</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                <tr>
                    <td align="center"><b>Kepala Gudang<b></td>
                    <td align="center"><b>SPV Warehouse</b></td>
                    <td align="center"><b>KA Divre</b></td>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 120px">
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">  
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">                        
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                        <td rowspan="5" style="vertical-align: bottom;text-align: center">
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        </td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('custom_script')
@endsection


