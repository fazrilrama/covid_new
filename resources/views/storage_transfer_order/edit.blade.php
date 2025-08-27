@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Edit Stock Transport Order</h1>
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
      @include('storage_transfer_order.form')
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
              <a href="{{url()}}" type="button" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah Barang
              </a>
            </div>
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
                    <th>Qty:</th>
                    <th>UOM:</th>
                    <th>Weight:</th>
                    <th>Volume:</th>
                    <th></th>
                  </tr>
              </thead>

              <tbody>
              
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
