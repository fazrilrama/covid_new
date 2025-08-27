@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Laporan AIN AON</h1>
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
            </div>
        </div>
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouse" class="form-control select2" required="">
                    <option value="0" selected disabled>--Pilih Warehouse--</option>
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" id="selected_branch" value="{{ !empty($data['branch_id']) ? $data['branch_id'] : '' }}">
    <input type="hidden" id="selected_warehouse" value="{{ !empty($data['warehouse_id']) ? $data['warehouse_id'] : '' }}">

    <input type="hidden" id="report_branch" value="{{ route('report_brach') }}">
    <input type="hidden" id="report_warehouse" value="{{ route('report_warehouse') }}">
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
                                    <label for="date_to" class="col-sm-4 col-form-label">Type</label>
                                    <div class="col-sm-8">
                                    <select name="type" id="type" class="form-control select2" required="">
                                        <option value="inbound" @if($data['type'] == 'inbound') {{'selected'}} @endif >Inbound</option>
                                        <option value="outbound" @if($data['type'] == 'outbound') {{'selected'}} @endif>Outbound</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    @if($search)
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
                    <th>ID#:</th>
	                <th>@if($data['type']=='inbound') AIN @else AON @endif:</th>
                    <th>Storage Area:</th>
	                <th>Origin:</th>
                    <th>Destination:</th>
	                <th>Outstanding:</th>
	                <th>Status:</th>
                    @if(!Auth::user()->hasRole('CargoOwner'))
	                <th>Receiving Status:</th>
                    @endif
                    
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @if(isset($collections))
            @foreach($collections as $item)
                @if($item->item_outstanding > 0)
	            <tr>
                    <td>{{$item->id}}</td>
	                <td>{{$item->item_code}}</td>
                    <td>
                        {{ (isset($item->item_storage_area)) ? $item->item_storage_area : '-'}}
                    </td>
	                <td>{{!empty($item->item_origin) ? $item->item_origin : ''}}</td>
	                <td>{{!empty($item->item_destination) ? $item->item_destination : ''}}</td>
	                <td align="center">
                        {{ number_format($item->item_outstanding, 2, ',', '.') }}
                        <p>
                            <select>
                                @foreach($detail_advance_notices[$item->id] as $detail)
                                    <option>
                                        {{ $detail->item->name }} | {{ $detail->ref_code }} | ots: {{ $detail->detail_outstanding }}
                                    </option>
                                @endforeach
                            </select>
                        </p>
                    </td>
	                <td>
                        @if(Auth::user()->hasRole('CargoOwner'))
                            {{ ($item->item_status == 'Pending' ? 'Planning' : ($item->item_status == 'Completed' ? 'Submitted' : $item->item_status)) }}
                        @else
                            {{ ($item->item_status == 'Pending' ? 'Planning' : $item->item_status ) }}
                        @endif
                    </td>
                    @if(!Auth::user()->hasRole('CargoOwner'))
    	                <td width="15%">
                            {{ ($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial') }}
                        </td>
                    @endif
                    
	                <td>
	                	<div class="btn-toolbar">
	                		
	                    </div>
	                </td>
	            </tr>
                @endif
	        @endforeach
            @endif
	      </tbody>
	    </table>
    @endif
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
                    $("#warehouse").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        if($('#selected_warehouse').val() == value.id) {
                            $("#warehouse").append("<option value='"+value.id+"' selected>"+value.code +' - ' +value.name+"</option>");
                        } else {
                            $("#warehouse").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        $('#branch').change(function() {
            $("#warehouse").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#branch").val()
                },
                dataType: 'json',
                success: function(data) {
                    $("#warehouse").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        $("#warehouse").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                    });
                }
            });
        });

    </script>
@endsection