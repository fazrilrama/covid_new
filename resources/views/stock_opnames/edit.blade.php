@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Stock Opname 
    @if($stock_opname->status == 'Processed')
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target='#modal-otp'>
        <i class="fa fa-check"></i> Complete
    </button>
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
      @include('stock_opnames.form')
    </div>
  </div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            @if($stock_opname->status != 'Completed')
              <div class="btn-group" role="group">
                <a href="{{url('stock_opname_details/create/'.$stock_opname->id)}}" type="button" class="btn btn-success" title="Create">
                  <i class="fa fa-plus"></i> Tambah Barang
                </a>
              </div>
            @endif
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
                    <th></th>
                  </tr>
              </thead>
              <tbody>
                @foreach($stock_opname->details as $detail)
                  <tr>
                    <td>{{ $detail->id }}</td>
                    <td>{{ $detail->item->sku }}</td>
                    <td>{{ $detail->item->name }}</td>
                    <td>{{ $detail->project ? $detail->project->name : '' }}</td>
                    <td>{{ $detail->storage ? $detail->storage->code : ''  }}</td>
                    <td>{{ number_format($detail->wina_stock,2,',', '.') }}</td>
                    <td>{{ number_format($detail->stock_taking_akhir,2,',', '.') }}</td>
                    <td>{{ $detail->uom->name }}</td>
                    <td>
                    <div class="btn-toolbar">
	                		  <div class="btn-group" role="group">
		                      	<a href="{{url('stock_opname_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
		                      		<i class="fa fa-pencil"></i>
		                      	</a>
		                    </div>
                        <div class="btn-group" role="group">
                          <form action="{{ url('stock_opname_details', [$detail->id]) }}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                          </form>
                        </div>
                    </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
        </div>
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