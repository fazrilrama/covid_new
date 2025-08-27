@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Edit Stock Opname
    @if($stock_opname->status == 'Processed')
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target='#modal-otp'>
        <i class="fa fa-check"></i> Complete
    </button>
    @else
    <a href="{{route('stock_opnames.print', $stock_opname->id) }}" type="button" class="btn btn-success" title="Create" target="_blank">
    <i class="fa fa-save"></i> Print
    </a>
    <a href="{{route('stock_opnames.export', $stock_opname->id) }}" type="button" class="btn btn-success" title="Create" target="_blank">
    <i class="fa fa-save"></i> Export
    </a>
    @endif
</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Data</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Warehouse</label>
                            <div class="col-sm-9">

                                <p class="form-control-static">{{ $stock_opname->warehouse->code }} -
                                    {{ $stock_opname->warehouse->name }}</p>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Kota/Kabupaten</label>
                            <div class="col-sm-9">

                                <p class="form-control-static">{{ $stock_opname->warehouse->city->name }}</p>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{ $stock_opname->warehouse->address }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Kapasitas</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    {{ number_format($stock_opname->warehouse->length * $stock_opname->warehouse->width, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Tanggal dan Waktu</label>
                            <div class="col-sm-9">

                                <p class="form-control-static">{{ $stock_opname->date }}</p>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Dihitung Oleh</label>
                            <div class="col-sm-9">

                                <ul>
                                    @if($stock_opname->calculated_by != null)

                                    @foreach(json_decode($stock_opname->calculated_by) as $key => $value)
                                    <li class="form-control-static">{{ $value }}</li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="form-group">
                            <label for="project_id" class="col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-9">

                                <ul>
                                    @if($stock_opname->note != null)
                                    @foreach(json_decode($stock_opname->note) as $key => $value)
                                    <li class="form-control-static">{{ $value }}</li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Barang</h3>
                <div class="box-tools pull-right">
                    <div class="btn-toolbar">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="dtBasicExample" class="data-table table table-bordered table-hover no-margin item-transaction-table"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="no-sort">ID:</th>
                                <th>Item SKU:</th>
                                <th>Item Name:</th>
                                <th>Project:</th>
                                <th>Storage:</th>
                                <th>Wina Stock Qty:</th>
                                <th>Stock Opname Qty:</th>
                                <th>UOM:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock_opname->details as $detail)
                            <tr>
                                <td>{{ $detail->id }}</td>
                                <td>{{ $detail->item->sku }}</td>
                                <td>{{ $detail->item->name }}</td>
                                <td>{{ $detail->project ? $detail->project->name : '' }}</td>
                                <td>{{ $detail->storage ? $detail->storage->code : '' }}</td>
                                <td>{{ number_format($detail->wina_stock,2,',', '.') }}</td>
                                <td>{{ number_format($detail->stock_taking_akhir,2,',', '.') }}</td>
                                <td>{{ $detail->uom->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('keterangan', ['url' => route('stock_opnames.completed', ['stock_opname' => $stock_opname->id])])
@endsection

@section('custom_script')
<script>
    var table = $('#dtBasicExample').DataTable({
        "order": [[ 5, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,4,6] }
        ]
    });
    </script>
  @endsection