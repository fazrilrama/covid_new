@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
    <ul class="progressbar">
        <li>Buat @if($type=='inbound') Goods Receiving @else Delivery Plan @endif</li>
        @if($stockTransport->status == 'Completed')
          <li>Tambah Item</li>
          <li class="active">Complete</li>
        @else
          <li class="active">Tambah Item</li>
          <li>Complete</li>
        @endif

    </ul>
</div>
<h1>{{ __('lang.edit') }} @if($type=='inbound') Goods Receiving @else Delivery Plan @endif #{{$stockTransport->code}}
  @if($stockTransport->status == 'Processed')
    <a href="JavaScript:poptastic('{{ route('stock_transports.print', ['stock_transport' => $stockTransport->id]) }}')" type="button" class="btn btn-warning pull-right" title="Cetak Tally Sheet">
        <i class="fa fa-download"></i> Cetak
    </a>
  @endif
  @if($stockTransport->status == 'Processed' && $stockTransport->type == 'inbound' && $jumlah_item_transport_no_actual == 0)
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'><i class="fa fa-check"></i> Complete</button>
  @endif

  <!-- jika outbound tidak perlu pengecekan data actual -->
  @if($stockTransport->status == 'Processed' && $stockTransport->type == 'outbound')
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'><i class="fa fa-check"></i> Complete</button>
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
      @include('stock_transports.form')
    </div>
    @include('stock_transports.do_info')
  </div>
</div>
<div class="row">
  <div class="col-md-12">

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        @if($stockTransport->status == 'Processed' || $stockTransport->status == 'Pending')
        <div class="box-tools pull-right">
            <div class="btn-toolbar">
                @if($stockTransport->is_sent == 0)
                <div class="btn-group" role="group">
                    <a href="{{url('stock_transport_details/create/'.$stockTransport->id)}}" type="button" class="btn btn-success" title="Create">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </a>
                </div>
                @endif
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        @endif
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="data-table table table-bordered table-hover no-margin" width="100%">
              <thead>
                  <tr>
                      <th>Item SKU:</th>
                      <th>Item Name:</th>
                      <th>Group Ref:</th>
                      <th>Control Date:</th>
                      
                      @if($stockTransport->type == 'inbound')
                        <th>Plan Qty:</th>
                        <th>Actual Qty:</th>
                      @else
                        <th>Actual Qty:</th>
                      @endif
                      <th>UOM:</th>
                      @if($stockTransport->type == 'inbound')
                        <th>Actual Weight:</th>
                        <th>Actual Volume:</th>
                      @endif
                      <th></th>
                  </tr>
              </thead>

              <tbody>
              @foreach($stockTransport->details as $detail)
                  <tr>
                      <td>{{$detail->item->sku}}</td>
                      <td>{{$detail->item->name}}</td>
                      <td>{{$detail->ref_code}}</td>
                      <td>{{$detail->control_date}}</td>
                      @if($stockTransport->type == 'inbound')
                        <td>{{number_format($detail->plan_qty, 2, ',', '.')}}</td>
                        <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                      @else
                        <td>{{number_format($detail->plan_qty, 2, ',', '.')}}</td>
                      @endif
                      <td>{{$detail->uom->name}}</td>
                      @if($stockTransport->type == 'inbound')
                        <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                        <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
                      @endif
                      <td>
                        <div class="btn-toolbar">
                           @if($stockTransport->editable)
                            @if($stockTransport->is_sent != 1)
                            <div class="btn-group" role="group">
                                <a href="{{url('stock_transport_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                                  <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                                <form action="{{url('stock_transport_details', [$detail->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                </form>
                            </div>
                            @else
                            <div class="btn-group" role="group">
                                <a href="{{url('stock_transport_details/'.$detail->id.'/show')}}" type="button" class="btn btn-primary" title="Show">
                                  <i class="fa fa-eye"></i>
                                </a>
                            </div>
                            @endif
                           @endif
                          </div>
                      </td>
                  </tr>
              @endforeach
              </tbody>
            </table>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</div>

@include('otp', ['url' => route('stock_transports.completed', ['stock_transport' => $stockTransport->id])])
@include('send-foms', ['url' => route('stock_transports.sendapistocktransport', ['stock_transport' => $stockTransport->id])])

@endsection

<!-- @section('js')
    <script>
        $(document).ready(function () {
            $('.stock-completed').click(function() {
                $('#modal-otp').modal();
            });
        });        
    </script>
@endsection -->
