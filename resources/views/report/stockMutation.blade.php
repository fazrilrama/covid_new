@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">


@section('content_header')
    <h1>Laporan Mutasi Stok Per Item</h1>
@stop

@section('content')

    @section('additional_field')
    <div class="col-sm-6">
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouses" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_warehouse" value="{{ !empty($data['warehouse']) ? $data['warehouse'] : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Item</label>
            <div class="col-sm-8">
                <select name="item" id="item" class="form-control select2" required="">
                <option value="all">--Semua Item--</option>
                </select>
                <input type="hidden" id="selected_item" value="{{ !empty($data['item']) ? $data['item'] : '' }}">
            </div>
        </div>
    </div>
    <input type="hidden" id="report_warehouse" value="{{ route('warehouse_list') }}">
    <input type="hidden" id="report_item_project" value="{{ route('warehouse_item_project') }}">
    @endsection
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
                                    <label for="date_from" class="col-sm-4 col-form-label">Tanggal Mulai</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="datepicker-normal form-control" placeholder="Tanggal mulai" value="{{ $data['date_from'] }}" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_to" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_to" id="date_to" class="end-datepicker-normal form-control" placeholder="Tanggal akhir" value="{{ $data['date_to'] }}" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        @if($data['item'] != 'all')
                        <button class="btn btn-sm btn-success" name="submit" value="1" id="export_excel"><i class="fa fa-download"></i> Export ke Excel</button>
                        @endif
                        <!-- @if(isset($print_this))
                            <a href="JavaScript:poptastic('/report/stock_mutation/print')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
                                <i class="fa fa-print"></i> Cetak Mutasi Hari Ini
                            </a>
                        @endif -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    @if($search)
    <div class="table-responsive">
        <table class="table table-bordered table-hover no-margin" width="100%" id="data_stock">
            <thead>
                <tr>
                    <th>Date:</th>
                    <th>Item:</th>
                    <th>UOM</th>
                    <th>Begining:</th>
                    <th>Receiving:</th>
                    <th>In Standing</th>
                    <th>Delivery:</th>
                    <th>Out Standing</th>
                    <th>Closing:</th>
                </tr>
            </thead>
        
            <tbody> 
                @if($data['item'] != 'all')
                    @foreach($collections as $collection)
                        <tr>
                            <td>{{ $collection['date'] }}</td>
                            <td>{{ $collection['item'] }}</td>
                            <td>{{ $collection['uom_name'] }}</td>
                            <td>{{ number_format($collection['begining'], 2, ',', '.') }}</td>
                            <td>{{ number_format($collection['receiving'], 2, ',', '.') }}</td>
                            @if ($loop->last)
                            <td>{{ number_format($jumlah_inhandling, 2, ',', '.') }}</td>
                            @else
                            <td>0,00</td>
                            @endif
                            <td>{{ number_format($collection['delivery'], 2, ',', '.') }}</td>
                            @if ($loop->last)
                            <td>{{ number_format($jumlah_outhandling, 2, ',', '.') }}</td>
                            @else 
                            <td>0,00</td>
                            @endif
                            
                            <td>{{ number_format($collection['closing'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach($collections as $key => $collection)
                        <tr>
                            <td width="25%">{{ $key }}</td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ $c['date'] }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ $c['uom_name'] }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['begining'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['receiving'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['instanding'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['delivery'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['outstanding'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                @foreach($collection as $k => $c)
                                    <tr>
                                    <td>{{ number_format($c['closing'], 2, ',', '.') }}</td>   
                                    </tr>
                                @endforeach
                                </table>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @endif
@endsection

@section('custom_script')
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $('#warehouses').append("<option value='0' disabled selected>Pilih Warehouse</option>");
                $.each(data,function(i, value){
                    if($('#selected_warehouse').val() != '' && $('#selected_warehouse').val() == i){
                        $("#warehouses").append("<option value='"+i+"' selected>"+value+"</option>");
                    } else {
                        $("#warehouses").append("<option value='"+i+"'>"+value+"</option>");   
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
        if($('#selected_item').val() != '') {
            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#selected_warehouse").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $.each(data,function(i, value){
                        if($('#selected_item').val() == value.id) {
                            $("#item").append("<option value='"+value.id+"' selected>"+value.sku +' - ' +value.name+"</option>");
                        } else {
                            $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        $('#warehouses').change(function() {
            $("#item").empty();
            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#item").append("<option value='all' selected>--Semua Item--</option>");
                    if($('#item').val() == 'all') {
                        $('#export_excel').hide();
                    } else {
                        $('#export_excel').show();
                    }
                    $.each(data,function(i, value){
                        $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                    });
                }
            });
        });
        $('#item').change(function(){
            console.log('sini '+$('#item').val());
            if($('#item').val() == 'all') {
                $('#export_excel').hide();
            } else {
                $('#export_excel').show();
            }
        })

    </script>

    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#data_stock').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'print',
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL' 
                }
            ],
            "order": [[ 0, "desc" ]],
            "pagingType": "full_numbers"
        } );
    } );
    </script>
@endsection