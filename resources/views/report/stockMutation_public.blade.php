@extends('adminlte::page_dua')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Turn Over Stock</h1>
@stop

@section('content')

    @include('report.searchDateForm', ['print_this' => true])
    

    @if($search)
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Branch:</th>
                    <th>Nama Gudang:</th>
                    <th>Kode Gudang:</th>
                    <th>Status:</th>
                    <th>QTY IN:</th>
                    <th>QTY OUT:</th>
                    <th>TURNOVER:</th>

                </tr>
            </thead>
        
            <tbody> 
                @foreach($stock as $collection)
                    <tr>
                        <td>{{ $collection->branch }}</td>
                        <td>{{ $collection->name }}</td>
                        <td>{{ $collection->code }}</td>
                        <td>{{ strtoupper($collection->ownership) }}</td>
                        <td>{{ number_format($collection->qty_inbound, 2, ',', '.') }}</td>
                        <td>{{ number_format($collection->qty_outbound, 2, ',', '.') }}</td>
                        <td>{{ number_format($collection->qty_inbound + $collection->qty_outbound, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection

@section('custom_script')
@endsection