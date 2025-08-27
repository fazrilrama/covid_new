@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Edit Goods Issue #{{$stockDelivery->code}}
    <a href="JavaScript:poptastic('{{ url('/stock_deliveries/'.$stockDelivery->id.'/print') }}')" type="button" class="btn btn-warning pull-right" title="Cetak"><i class="fa fa-download"></i> Cetak</a>
    @if(session()->get('current_project')->id == '337' && $stockDelivery->driver_name != null && $stockDelivery->police_number != null && $stockDelivery->type_payment != null)
      @if($stockDelivery->status == 'Processed')
      <button type="button" class="btn btn-primary pull-right margin-right stock-completed"><i class="fa fa-check"></i> Completed</button>
      @endif
    @else
      @if($stockDelivery->status == 'Processed')
      <button type="button" class="btn btn-primary pull-right margin-right stock-completed"><i class="fa fa-check"></i> Completed</button>
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
      @include('stock_deliveries.form')
    </div>
    @include('stock_deliveries.do_info')
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            
            <!-- 
            @if( ($stockDelivery->status !== 'Completed') || ($stockDelivery->status !== 'Closed') )
            <div class="btn-group" role="group">
              <a href="{{url('stock_delivery_details/create/'.$stockDelivery->id)}}" type="button" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah Barang
              </a>
            </div>
            @endif -->

            <!--             <div class="btn-group" role="group">
              <a href="{{url('stock_deliveries/copy_details/'.$stockDelivery->id)}}" type="button" class="btn btn-primary" title="Copy" onclick="return confirm('Anda akan mengcopy daftar barang dari dokumen referensi, apakah Anda ingin melanjutkan?');">
                <i class="fa fa-download"></i> Copy Barang
              </a>
            </div> -->

            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        </div>
        <!-- /.box-tools -->
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
                        <th>Item SKU:</th>
                        <th>Item Name</th>
                        <th>Group Ref:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Weight:</th>
                        <th>Volume:</th>
                        <th>Control Date:</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <tbody>
                @foreach($stockDelivery->details as $detail)
                    <tr>
                        <td>{{$detail->item->sku}}</td>
                        <td>{{$detail->item->name}}</td>
                        <td>{{$detail->ref_code}}</td>
                        <td>{{$detail->qty}}</td>
                        <td>{{$detail->uom->name}}</td>
                        <td>{{$detail->weight}}</td>
                        <td>{{$detail->volume}}</td>
                        <td>{{$detail->control_date}}</td>
                        <!-- <td>
                            @if($stockDelivery->status != 'Completed')
                            <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                    <a href="{{url('stock_delivery_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="btn-group" role="group">
                                    <form action="{{url('stock_delivery_details', [$detail->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td> -->
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
</div>

@include('otp', ['url' => route('stock_deliveries.completed', ['stock_deliveries' => $stockDelivery->id]), 'url_closed' => route('stock_deliveries.closed', ['stock_deliveries' => $stockDelivery->id])])

@endsection

@section('js')
    <script>
        $(document).ready(function () {
          $("#sisa_tagihan").hide();

            $('.stock-completed').click(function() {
                $('#modal-otp').modal();
            });
        });        
        /* $(document).ready(function () {
            $('.stock-completed').click(function() {
                $('#modal-otp-closed').modal();
            });
        }); */
    </script>

    <script type="text/javascript">
        var newwindow;
        function poptastic(url)
        {
            newwindow=window.open(url,'name','height=800,width=1600');
            if (window.focus) {newwindow.focus()}
        }
        $("#cmbpayment").change(function() {
          if($('#cmbpayment').val() == 'cod') {
            $("#sisa_tagihan").show();
            $("#number_sisa_tagihan").prop('required', true);
          } else {
            $('#sisa_tagihan').hide();
            $("#number_sisa_tagihan").val(0);
            $("#number_sisa_tagihan").prop('required', false);
          }
        });
    </script>
@endsection