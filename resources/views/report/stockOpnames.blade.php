@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Stock Opnames</h1>
@stop

@section('content')

    @section('additional_field')
    <div class="col-sm-6">
        <div class="form-group">
            <label for="branch" class="col-sm-4 col-form-label">Cabang</label>
            <div class="col-sm-8">
                <select name="branch" id="branch" class="form-control select2" required="">
                    <option value="" selected disabled>--Pilih Cabang--</option>
                </select>
                <input type="hidden" id="selected_branch" value="{{ !empty($data['branch_id']) ? $data['branch_id'] : '' }}">
            </div>
        </div>
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouses" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_warehouse" value="{{ !empty($data['warehouse']) ? $data['warehouse'] : '' }}">
            </div>
        </div>
       
    </div>
    <input type="hidden" id="report_warehouse" value="{{ route('report.warehouse_stockOpname') }}">
    @endsection

    @include('report.searchDateForm')
    

    @if($search)
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Tanggal SO:</th>
                    <th>Divre:</th>
                    <th>Gudang:</th>
                    <th>Anggota:</th>
					<th>Status:</th>
					<th>Detail:</th>
                </tr>
            </thead>
        
            <tbody>
            @foreach($stock_opnames as $stock_opname)
            <tr>
            <td>{{ $stock_opname->date }}</td>
            <td>{{ $stock_opname->warehouse ? $stock_opname->warehouse->branch->name : '' }}</td>
            <td>{{ $stock_opname->warehouse ? $stock_opname->warehouse->name : '' }}</td>
            <td>
            <ul>
                @if($stock_opname->calculated_by != null) 
                @foreach(json_decode($stock_opname->calculated_by) as $calculated)
                <li>{{ $calculated }}</li>
                @endforeach
                @endif
            </ul>
            </td>
            <td>
            {{ $stock_opname->status }}
            </td>
            <td>
            <a href="{{ route('stock_opnames.edit', $stock_opname->id) }}" type="button" class="btn btn-primary detail_barang"><i class="fa fa-eye"></i></a>
            </td>
            </tr>        
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <input type="hidden" id="report_branch" value="{{ route('report_brach') }}">

@endsection

@section('custom_script')
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_branch').val(),
            dataType: "json",
            success: function(data){
                // $("#warehouses").empty();
                $.each(data,function(i, value){
                    if($('#selected_branch').val() == value.id) {
                        $("#branch").append("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#branch").append("<option value='"+value.id+"'>"+value.name+"</option>");
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
        if($('#selected_branch').val() != '') {
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#selected_branch").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#warehouses").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        if($('#selected_warehouse').val() == value.id && $('#selected_warehouse').val() != 0) {
                            $("#warehouses").append("<option value='"+value.id+"' selected>"+value.code +' - ' +value.name+"</option>");
                        } else {
                            $("#warehouseS").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        $('#branch').change(function() {
            $("#warehouses").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#branch").val()
                },
                dataType: 'json',
                success: function(data) {
                    $("#warehouses").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        $("#warehouses").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                    });
                }
            });
        });
    </script>
@endsection