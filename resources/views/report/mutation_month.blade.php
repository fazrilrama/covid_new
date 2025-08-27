@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('adminlte_css')
    <style>
    .red {
        background-color: #da413e !important;
    }
    .green {
        background-color: #4BB543 !important;
    }
    .orange {
        background-color: #FFC107 !important;
    }
    .blue {
        background-color: #add8e6 !important;
    }
    .table-parent {
        overflow-x: scroll;
    }
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
@stop

@section('content_header')
    <h1>Report Mutasi Barang Per Bulan</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Pencarian Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <form method="GET" class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                            @yield('additional_field')
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="date_from" class="col-sm-4 col-form-label">Bulan</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="month-picker form-control" placeholder="Tanggal mulai" value="{{ $data['date_from'] }}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-search"></i> Search</button>
                                <button class="btn btn-sm btn-success" name="submit" value="2" id="export_excel"><i class="fa fa-download"></i> Export ke Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(isset($data['report']))
    <div class="row">
        <div class="table-responsive">
            <table id="warehouse-contract" class="data-table table table-bordered table-hover no-margin" width="125%">
                <thead>
                    <tr>
                        <th rowspan="2">SKU</th>
                        <th rowspan="2">PRODUCT</th>
                        <th rowspan="2">Komoditi</th>
                        <th rowspan="2">UoM</th>
                        <th rowspan="2">STOK AWAL</th>
                        <th rowspan="2">MASUK</th>
                        <th colspan="{{ $data['date_difference'] }}" class="text-center">Hari</th>
                        <th rowspan="2">Keluar By Monitoring</th>
                        <th rowspan="2">Asumsi Barang Keluar</th>
                        <th rowspan="2">Stock Akhir</th>
                        <th rowspan="2">Rata Rata Barang Keluar</th>
                        <th rowspan="2">Persediaan Rata Rata</th>
                        <th rowspan="2">TOR Parsial</th>
                        <th rowspan="2">Keterangan</th>
                        <th rowspan="2">
                        Persediaan Barang yang Harus di Penuhi
                        </th>
                        <th rowspan="2">Safety Stock</th>
                        <th rowspan="2">Rekomendasi</th>
                        <th rowspan="2">Nilai Barang</th>
                    </tr>
                    <tr>
                        @for($i=1; $i<=$data['date_difference']; $i++)
                        <th>{{$i}}</th>
                        @endfor
                        
                    </tr>
                </thead>
            
                <tbody> 
                    @foreach($data['report'] as $report)
                        <tr>
                            <td>{{ $report['sku'] }}</td>
                            <td>{{ $report['sku_name'] }}</td>
                            <td>{{ $report['commodity_name'] }}</td>
                            <td>{{ $report['uom_name'] }}</td>
                            <td>{{ $report['begining'] }}</td>
                            <td>{{ number_format($report['after_begining_in'], 2,',', '.') }}</td>
                            @if( session()->get('current_project')->id == 337)
                            @for($i=1; $i<=$data['date_difference']; $i++)
                                <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? number_format($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i],2, ',', '.') : 0 }}</td>
                            @endfor
                            @else
                            @for($i=1; $i<=$data['date_difference']; $i++)

                                <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? number_format($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']][$i],2, ',', '.') : 0 }}</td>
                            @endfor
                            @endif
                            @if( session()->get('current_project')->id == 337)
                            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]),2, ',', '.') : 0 }}</td>
                            @else
                            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]),2, ',', '.') : 0 }}</td>
                            @endif
                            @php
                            if( session()->get('current_project')->id == 337){
                                $keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) : 0 ;

                                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3) : 0;

                            }else {
                                $keluar =isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) : 0;
                                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3) : 0;
                            }
                            $persediaan_rata_rata = ($report['begining'] + $report['stock']) / 2 ;

                            if($persediaan_rata_rata > 0) {
                                $tor = round(($keluar/$persediaan_rata_rata) / $data['pembagi'] * 100, 2);
                            } elseif($persediaan_rata_rata == 0){
                                $tor = '-';
                            }
                            else {
                                $tor = 0;
                            }   

                            $asumsi = 10 * $rata_rata_barang_keluar;
                            $safety_stock = round(($report['begining'] + $report['after_begining_in']) * ($report['percentage_buffer'] / 100),2);
                            $persediaan_yg_harus_dipenuhi = $asumsi - $report['stock'] + $safety_stock;
                            
                            @endphp
                            <td>{{ $asumsi  }}</td>
                            <td>{{ $report['stock'] }}</td>
                            @if( session()->get('current_project')->id == 337)
                            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3),2, ',', '.') : 0 }}</td>
                            @else
                            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3),2, ',', '.') : 0 }}</td>
                            @endif
                            <td>{{ ($report['begining'] + $report['stock']) / 2 }}</td>
                            
                            <td>
                            {{$tor}}
                            </td>
                            <td>
                            @if($tor == '-')
                                {{$tor}}
                            @else
                                @if($tor > 3) 
                                {{  'FAST' }}
                                @elseif($tor >= 1 && $tor <= 3)
                                {{  'SLOW' }}
                                @else 
                                {{ 'NON' }}
                                @endif
                            @endif
                            </td>
                            <td>
                                {{ $persediaan_yg_harus_dipenuhi }}
                            </td>
                            <td>
                                {{ $safety_stock }}
                            </td>
                            <td>
                                @if($report['stock'] <= $safety_stock)
                                    {{ 'Beli' }}
                                @elseif($report['stock'] > $safety_stock)
                                    {{ 'Tidak Perlu Beli' }}
                                @else 
                                    {{ '-' }}
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection

@section('custom_script')
    <script src='{{ asset('vendor/mustache/js/mustache.min.js') }}'></script>
    <script src="{{ asset('vendor/replaceSymbol/replaceSymbol.js') }}"> </script>
    <script>
        $('.month-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
    </script>
@endsection