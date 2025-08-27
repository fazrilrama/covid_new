@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
      <ul class="progressbar">
          <li>{{ __('lang.create') }} @if($type=='inbound') Putaway @else Picking Plan @endif</li>
          @if($stockEntry->status == 'Completed')
            <li>{{ __('lang.create') }} Item</li>
            <li class="active">Complete</li>
          @else
            <li class="active">Tambah Item</li>
            <li>Complete</li>
          @endif

      </ul>
    </div>
<h1>{{ __('lang.edit') }} @if($type=='inbound') Putaway @else Picking Plan @endif #{{$stockEntry->code}}
  <a href="JavaScript:poptastic('{{ route('stock_entries.print', ['stock_entry' => $stockEntry->id]) }}')" type="button" class="btn btn-warning pull-right" title="Cetak Putaway List">
      <i class="fa fa-download"></i> {{ __('lang.print') }}
  </a>
  @if($stockEntry->status == 'Processed' && $outstanding == 0 && $jumlah_item_entry_no_storage == 0)
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'>
      <i class="fa fa-check"></i> Complete</button>
  @endif
</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">{{ __('lang.information_data') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
        @include('stock_entries.form')
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">{{ __('lang.create') }}</h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            @if($stockEntry->status == 'Processed')
            <div class="btn-group" role="group">
              <a href="{{url('stock_entry_details/create/'.$stockEntry->id)}}" type="button" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> {{ __('lang.create') }} Barang
              </a>
            </div>
            <!-- <div class="btn-group" role="group">
              <a href="{{url('stock_entries/copy_details/'.$stockEntry->id)}}" type="button" class="btn btn-primary" title="Copy" onclick="return confirm('Anda akan mengcopy daftar barang dari dokumen referensi, apakah Anda ingin melanjutkan?');">
                <i class="fa fa-download"></i> Copy Barang
              </a>
            </div> -->
            @endif
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        </div>
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="item-stock-entry" class="data-table table table-bordered table-hover no-margin" width="100%">

              <thead>
                  <tr>
                      <th>Item SKU:</th>
                      <th>Item Name</th>
                      <th>Group Ref:</th>
                      <th>Control Date:</th>
                      <th>Storage:</th>
                      <th>Qty:</th>
                      <th>UOM:</th>
                      <th>Weight:</th>
                      <th>Volume:</th>
                      <th></th>
                  </tr>
              </thead>

              <tbody>
              @foreach($stockEntry->details->where('status', '<>', 'canceled') as $detail)
                <tr>
                    <td>{{$detail->item->sku}}</td>
                    <td>{{$detail->item->name}}</td>
                    <td>{{$detail->ref_code}}</td>
                    <td>{{$detail->control_date}}</td>
                    <td>{{@$detail->storage->code}}</td>
                    <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                    <td>{{$detail->uom->name}}</td>
                    <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                    <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
                    <td>
                      @if($detail->status == 'draft')
                        @if($stockEntry->status == 'Pending' || $stockEntry->status == 'Processed')
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                <a href="{{url('stock_entry_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                                <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                            <form action="{{url('stock_entry_details', [$detail->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                            </form>
                            </div>
                        </div>
                        @endif
                      @endif
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

@include('otp', ['url' => route('stock_entries.status', ['stock_entry' => $stockEntry->id, 'status' => 'Completed'])])

@endsection
