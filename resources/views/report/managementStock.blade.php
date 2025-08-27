@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Laporan Summary Mutasi Stock</h1>
@stop

@section('content')
@if(!empty($additionalMessage))
<div class="alert alert-{{ $additionalError ? 'danger' : 'info' }}">
    {{ $additionalMessage  }}
</div>
@endif
@section('additional_field')

    <div class="col-sm-6">
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse_id" id="warehouses" class="form-control select2">
                </select>
                <input type="hidden" id="selected_warehouse" value="{{ $data['warehouse_id'] }}">
            </div>
        </div>
    </div>
    <input type="hidden" id="report_warehouse" value="{{ route('report_warehouse') }}">
    <input type="hidden" id="warehouse-stock-management" value="{{ route('api.management_stock') }}">
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
                    <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
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
    <table class=table no-margin" width="100%">
        <thead>
            <tr>
                <th rowspan="3" style="text-align: center"><p><h4>Branch: {{ $warehouse->branch_name }}</h4><th>
                <th rowspan="3" style="text-align: center"><p><h4>Warehouse: {{ $warehouse->name }}</h4></p><th>
                <th rowspan="3" style="text-align: center"><p><h4>Project: {{ $warehouse->project_name }}</h4></p><th>
            </tr>
        </thead>
    </table>

    
    <table class=table table-bordered table-hover no-margin" width="100%" id="warehouse-stock">
        <thead>
            <tr>
                <th colspan="8" style="text-align: center">Stock Gudang Saat Ini</th>
            </tr>
            <tr>
                <th>SKU:</th>
                <th>Nama SKU:</th>
                <th>Stock Awal</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Stok Akhir</th>
                <th>UoM</th>
            </tr>
        </thead>
            
           
        <tbody>

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
                $.each(data,function(i, value){
                    if($('#selected_warehouse').val() != 'all' && $('#selected_warehouse').val() == value.id){
                        $("#warehouses").append("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#warehouses").append("<option value='"+value.id+"'>"+value.name+"</option>");   
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
    </script>
    <script>
        var table = $('#warehouse-stock').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#warehouse-stock-management').val(),
                "data": function (d) {
                    d.id="";
                    d.warehouse_id = $("#selected_warehouse").val();
                    d.date_to = $("#date_to").val();
                    d.date_from = $("#date_from").val();
                }
            }
            ,"order": [[ 0, "asc" ]]
            ,'columns': [
                {
                    render: function (data, type, full, meta) {
                        return full.sku;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.sku_name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.begining;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.after_begining_in;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.after_begining_out;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.stock;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.uom_name;
                    }
                }
            ]
        });
    </script>
@endsection