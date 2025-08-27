@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
    <ul class="progressbar">
        <li>Buat Internal Movement</li>
        <li class="active">Tambah Item</li>
        <li>Complete</li>
    </ul>
</div>
<h1>Edit Internal Movement
  @if($stock_internal_movement->status == 'Processed')
      @if(Auth::user()->hasRole('WarehouseSupervisor') && count($stock_internal_movement->detailmovement) > 0)
      <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target='#modal-otp'>
        <i class="fa fa-check"></i> {{Auth::user()->hasRole('CargoOwner') ? 'Submit' : 'Complete'}}
      </button>
      @endif
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
      @include('stock_internal_movement.form')
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
            @if($stock_internal_movement->status != 'Completed')
              <div class="btn-group" role="group">
                <a href="{{url('stock_internal_movement_details/create/'.$stock_internal_movement->id)}}" type="button" class="btn btn-success" title="Create">
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
          <table class="data-table table table-bordered table-hover no-margin item-transaction-table" width="100%">
              <thead>
                  <tr>
                    <th>ID:</th>
                    <th>Item SKU:</th>
                    <th>Item Name:</th>
                    <th>Group Ref:</th>
                    <th>Origin Storage:</th>
                    <th>Destination Storage:</th>
                    <th>Qty:</th>
                    <th>UOM:</th>
                    <th></th>
                  </tr>
              </thead>
              <tbody>
              @foreach($stock_internal_movement->detailmovement as $detail)
                <tr>
                  <td>{{ $detail->id }}</td>
                  <td>{{ $detail->item->sku }}</td>
                  <td>{{ $detail->item->name }}</td>
                  <td>{{ $detail->ref_code }}</td>
                  <td>{{ $detail->origin_storage->code }}</td>
                  <td>{{ $detail->dest_storage->code }}</td>
                  <td>{{ $detail->movement_qty }}</td>
                  <td>{{ $detail->uom->name }}</td>
                  <td>
                  <div class="btn-group" role="group">
                    <a href="{{ route('stock_internal_movement_details.edit', ['stock_internal_movement_detail' => $detail->id]) }}" type="button" class="btn btn-warning" title="Edit">
                      <i class="fa fa-pencil"></i>
                    </a>
                  </div>
                  <div class="btn-group" role="group">
                  <form action="{{ url('stock_internal_movement_details', [$detail->id]) }}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                  </form>
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


@include('otp', ['url' => route('stock_internal_movements.completed', ['internal_movement' => $stock_internal_movement->id])])

@endsection
