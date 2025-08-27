@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Laporan POD per No Good Isue</h1>
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

    @include('report.searchDateForm', ['print_this' => true])
    

    @if($search)
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Tanggal:</th>
                    <th>Sisa Pengiriman Awal:</th>
                    <th>Pengiriman Dibuat:</th>
                    <th>Sampai Tujuan:</th>
                    <th>Outstanding</th>
                    <th>Closing</th>
                    <th>Detail:</th>
                </tr>
            </thead>
        
            <tbody> 
                @foreach($collections as $collection)
                <tr>
                <td>{{ $collection['date'] }}</td>
                <td>{{ $collection['begining'] }}</td>
                <td>{{ $collection['receiving'] }}</td>
                <td>{{ $collection['delivery'] }}</td>
                <td>{{ $collection['begining'] + $collection['receiving'] - $collection['delivery'] }}</td>
                <td>{{ $collection['closing'] }}</td>
                    <td><a href="{{ route('report.detail_good_issue_mutation', ['date' => $collection['date'], 'project'=>$collection['project']]) }}" type="button" class="btn btn-primary" title="View" target="_blank">
                        <i class="fa fa-eye"></i>
                    </a></td>
                </tr>
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