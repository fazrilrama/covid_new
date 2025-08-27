@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Tally Sheet #{{$stockTransport->code}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Barang</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover no-margin" width="100%">
                            <thead>
                            <tr>
                                <th>ID:</th>
                                <th>Item SKU:</th>
                                <th>Qty:</th>
                                <th>UOM:</th>
                                <th>Weight:</th>
                                <th>Volume:</th>
                                <th>Control Date:</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($stockTransport->details as $detail)
                                <tr>
                                    <td>{{$detail->id}}</td>
                                    <td>{{$detail->item->sku}}</td>
                                    <td>{{$detail->qty}}</td>
                                    <td>{{$detail->uom->name}}</td>
                                    <td>{{$detail->weight}}</td>
                                    <td>{{$detail->volume}}</td>
                                    <td>{{$detail->control_date}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection