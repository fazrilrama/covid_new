@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>List Rencana - Sampai Tujuan 
    </h1>
    Tanggal Filter: <code>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</code>
@stop

@section('content')
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#menu2">Sisa Pengiriman Hari Sebelumnya</a></li>
  <li><a data-toggle="tab" href="#home">On Delivery</a></li>
  <li><a data-toggle="tab" href="#menu1">Barang Terkirim</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>
            <tbody>
            @foreach($collections[0]['receiving'] as $detail)
                <tr>
                    <td>{{$detail->code}}</td>
                    <td>{{ $detail->consignee->name }}</td>
                    <td>{{ $detail->created_at }}</td>
                
               
                <td>
                    <table class="table" width="100%">
                     @foreach($detail->details as $barang)
                        <tr>
                        <td>{{ $barang->item->name }}</td>
                        </tr>
                     @endforeach
                    </table>
                </td>
                <td>
                    <table class="table" width="100%">
                     @foreach($detail->details as $barang)
                        <tr>
                        <td>{{ number_format($barang->qty, 0, ',', '.') }}</td>
                        </tr>
                     @endforeach
                    </table>
                </td>
                <td>
                    <table class="table" width="100%">
                     @foreach($detail->details as $barang)
                        <tr>
                        <td>{{ $barang->uom->name }}</td>
                        </tr>
                     @endforeach
                    </table>
                </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
  </div>
  <div id="menu1" class="tab-pane fade">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>
            <tbody>
            @foreach($collections[0]['begining'] as $detail)
                <tr>
                    <td>{{$detail->code}}</td>
                    <td>{{ $detail->consignee->name }}</td>
                    <td>{{ $detail->received_by }}</td>
                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ $barang->item->name }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ number_format($barang->qty, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ $barang->uom->name }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
  </div>
  <div id="menu2" class="tab-pane fade in active">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%" id="last-date">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>

            <tbody>
            @foreach($collections[0]['begining_delivery'] as $detail)
                <tr>
                    <td>{{$detail->code}}</td>
                    <td>{{ $detail->consignee->name }}</td>
                    <td>{{ $detail->created_at }}</td>

                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ $barang->item->name }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ number_format($barang->qty, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        @foreach($detail->details as $barang)
                            <tr>
                            <td>{{ $barang->uom->name }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>
    
@endsection
@section('custom_script')
    <script>
        var table = $('.table-search').DataTable();
        
    </script>
@endsection
