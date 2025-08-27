@extends('layouts.print')
@section('content')
    <div style="text-align:center;">
        <img src="{{ asset('logo.png') }}" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center;">
        Laporan On Location Stock
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
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">SKU</th>
                    <th rowspan="2">Nama SKU </th>
                    <th rowspan="2">UoM</th>
                    <th rowspan="2">Storage</th>
                    <th rowspan="2">Stok Akhir</th>
                </tr>
                </thead>

                <tbody>

                @foreach($data as $detail)
                    <tr>
                        <td>{{$detail['sku']}}</td>
                        <td>{{$detail['sku_name']}}</td>
                        <td>{{$detail['uom_name']}}</td>
                        <td>{{$detail['storages']}}</td>
                        <td>{{$detail['stock'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('custom_script')
@endsection


