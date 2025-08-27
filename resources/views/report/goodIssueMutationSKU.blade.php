@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Laporan POD per Item</h1>
@stop

@section('content')
    @section('additional_field')
        <div class="col-sm-6">
            <div class="form-group">
                <label for="branch" class="col-sm-4 col-form-label">Project</label>
                <div class="col-sm-8">
                    <select name="project" id="project" class="form-control select2">
                        <option value="all" selected>--Semua Project--</option>
                    </select>
                    <input type="hidden" id="selected_project" value="{{ !empty($data['project']) ? $data['project'] : '' }}">
                    
                </div>
            </div>
        
        </div>
        <input type="hidden" id="report_branch" value="{{ route('project_list') }}">
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
                                    <label for="date_from" class="col-sm-4 col-form-label">Tanggal Filter</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="datepicker-normal form-control" placeholder="Tanggal mulai" value="{{ $data['date_from'] }}" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-save"></i> Export Excel</button>
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
        <table class="data-table table table-bordered table-hover no-margin" id="list-item" width="100%">
            <thead>
                <tr>
                    <th>SKU:</th>
                    <th>Item:</th>
                    <th>UoM:</th>
                    <th>Outstanding Delivery Kemarin:</th>
                    <th>Pengiriman Dibuat Hari Ini:</th>
                    <th>Total In Delivery:</th>
                    <th>Delivered:</th>
                    <th>Outstanding:</th>
                    <th>Akumulasi Terkirim:</th>
                    <th>Total All Delivery:</th>
                </tr>
            </thead>
        
            <tbody> 
                @foreach($collections as $collection)
                @if($collection['begining'] > 0 || $collection['receiving'] > 0 || $collection['delivery'] > 0 )
                <tr>
                    <td>{{ $collection['sku'] }}</td>
                    <td>{{ $collection['item'] }}</td>
                    <td>{{ $collection['uom_name'] }}</td>
                    <td>{{ number_format($collection['begining'], 0, ',', '.') }}</td>
                    <td>{{ number_format($collection['receiving'], 0, ',', '.') }}</td>
                    <td>{{ number_format($collection['begining'] + $collection['receiving'], 0, ',', '.') }}</td>

                    <td>{{ number_format($collection['delivery'], 0, ',', '.') }}</td>
                    <td>{{ number_format($collection['begining'] + $collection['receiving'] - $collection['delivery'], 0, ',', '.') }}</td>
                    <td>{{ number_format($collection['akumulasi'], 0, ',', '.') }}</td>
                    <td>{{ number_format($collection['akumulasi'] + ($collection['begining'] + $collection['receiving'] - $collection['delivery']), 0, ',', '.') }}</td>


                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
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
                    if($('#selected_project').val() == value.project_id) {
                        $("#project").append("<option value='"+value.project_id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#project").append("<option value='"+value.project_id+"'>"+value.name+"</option>");
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });

    </script>
@endsection