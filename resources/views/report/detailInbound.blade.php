@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Report Detail Transaksi Inbound</h1>
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
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Item</label>
            <div class="col-sm-8">
                <select name="item" id="item" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_item" value="{{ !empty($data['item']) ? $data['item'] : '' }}">
            </div>
        </div>
    </div>
    <input type="hidden" id="report_warehouse" value="{{ route('report_warehouse') }}">
    <input type="hidden" id="warehouse-stock-management" value="{{ route('api.detail_inbound') }}">
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
    @if($data['item'] != 'all')
    
    <table class="table" width="100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align: center"><p><h4>Branch: {{ $warehouse->branch_name }}</h4><th>
                <th colspan="3" style="text-align: center"><p><h4>Warehouse: {{ $warehouse->name }}</h4></p><th>
                <th colspan="3" style="text-align: center"><p><h4>Project: {{ $warehouse->project_name }}</h4></p><th>
                
            </tr>
            <tr>
                <th colspan="9" style="text-align: center"><p><h4> {{ $item->sku }} - {{ $item->name }}</p></h4></th>
            </tr>
        </thead>
    </table>
    @endif

    
    <table class=table table-bordered table-hover no-margin" width="100%" id="warehouse-stock">
        @if($data['item'] == 'all')
        <thead>
            <tr>
                <th>From:</th>
                <th>SKU</th>
                <th>Description</th>
                <th>Date</th>
                <th>Doc No</th>
                <th>PIC</th>
                <th>Driver</th>
                <th>Plat No</th>
                <th>Qty</th>
                <th>UoM</th>
            </tr>
        </thead>
        @else 
        <thead>
            <tr>
                <th>Date</th>
                <th>Doc No</th>
                <th>PIC</th>
                <th>Driver</th>
                <th>Plat No</th>
                <th>Qty</th>
                <th>UoM</th>
            </tr>
        </thead>
        @endif
            
           
        <tbody>

      </tbody>
    </table>
</div>
@endif
<input type="hidden" id="report_item_project" value="{{ route('warehouse_item_project') }}">

@endsection

@section('custom_script')
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $("#warehouses").append("<option value='' selected disabled>Pilih Warehouse</option>");
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
        $('#warehouses').on('change',function() {
            // alert($('#report_item_project').val());
            $("#item").empty();
            $("#item").append("<option value='all' selected>Semua Item</option>");
            $("#storage").empty();
            $("#storage").append("<option value='all' selected>Semua Storage</option>");

            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val(),
                },
                success: function(data) { 
                    $.each(data,function(i, value){
                        $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                    });
                }
            });
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
                    $("#item").append("<option value='all' selected>Semua Item</option>");
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
    </script>
    <script>
        if($('#selected_item').val() == 'all') {
                var table = $('#warehouse-stock').DataTable({
                "retrieve": true,
                "processing": true,
                'ajax': {
                    "type": "GET",
                    "url": $('#warehouse-stock-management').val(),
                    "data": function (d) {
                        d.id="";
                        d.warehouse_id = $("#selected_warehouse").val();
                        d.item = $("#selected_item").val();
                        d.date_to = $("#date_to").val();
                        d.date_from = $("#date_from").val();
                    }
                }
                ,"order": [[ 0, "asc" ]]
                ,'columns': [
                    {
                        render: function (data, type, full, meta) {
                            return full.parties_name;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.sku;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.item_name;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.control_date;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.ref_code;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.pic;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.driver_name;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.police_number;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return parseFloat(full.qty).toFixed(2);
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.uom;
                        }
                    }
                ]
            });
        } else {
            var table = $('#warehouse-stock').DataTable({
                "retrieve": true,
                "processing": true,
                'ajax': {
                    "type": "GET",
                    "url": $('#warehouse-stock-management').val(),
                    "data": function (d) {
                        d.id="";
                        d.warehouse_id = $("#selected_warehouse").val();
                        d.item = $("#selected_item").val();
                        d.date_to = $("#date_to").val();
                        d.date_from = $("#date_from").val();
                    }
                }
                ,"order": [[ 0, "asc" ]]
                ,'columns': [
                    {
                        render: function (data, type, full, meta) {
                            return full.control_date;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.ref_code;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.pic;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.driver_name;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.police_number;
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return parseFloat(full.qty).toFixed(2);
                        }
                    },
                    {
                        render: function (data, type, full, meta) {
                            return full.uom;
                        }
                    }
                ]
            });
        }
        
    </script>
@endsection