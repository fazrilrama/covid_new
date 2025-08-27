@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
    <ul class="progressbar">
        <li>Buat @if($type=='inbound') AIN @else AON @endif</li>
        @if($advanceNotice->status == 'Completed')
          <li>Tambah Item</li>
          <li class="active">Complete</li>
        @else
          <li class="active">Tambah Item</li>
          <li>Complete</li>
        @endif

    </ul>
</div>
<h1>Edit @if($type=='inbound') AIN @else AON @endif #{{$advanceNotice->code}}
  @if($advanceNotice->status == 'Processed' && $total_qty_items > 0)
      @if(Auth::user()->hasRole('WarehouseSupervisor') || Auth::user()->hasRole('CargoOwner'))
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
        @if($advanceNotice->status == 'Processed' && $advanceNotice->accepted_note != null && $advanceNotice->is_accepted == 2)
			<div class="alert alert-danger">
					Ditolak dengan catatan:  {{ $advanceNotice->accepted_note }}
			</div>
			@endif

        <!-- /.box-tools -->
      </div>
      @include('advance_notices.form')
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
            @if($advanceNotice->status != 'Completed')
              <div class="btn-group" role="group">
                <a href="{{url('advance_notice_details/create/'.$advanceNotice->id)}}" type="button" class="btn btn-success" title="Create">
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
          <table class="data-table table table-bordered table-hover no-margin" width="100%">
              <thead>
                  <tr>
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
              @foreach($advanceNotice->details->where('status', '<>', 'canceled') as $detail)
                  <tr>
                      <td>{{$detail->item->sku}}</td>
                      <td>{{$detail->item->name}}</td>
                      <td>{{$detail->ref_code}}</td>
                      <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                      <td>{{$detail->uom->name}}</td>
                      <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                      <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
                      <td>
                        @if($advanceNotice->status != 'Completed' && $advanceNotice->status != 'Canceled')
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                <a href="{{url('advance_notice_details/'.$detail->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
                                <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                                <form action="{{url('advance_notice_details', [$detail->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                </form>
                            </div>
                        </div>
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

@include('otp', ['url' => route('advance_notices.completed', ['advance_notice' => $advanceNotice->id])])

@endsection
