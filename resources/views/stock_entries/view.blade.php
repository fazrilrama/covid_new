@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
      <ul class="progressbar">
          <li>Buat @if($type=='inbound') Putaway @else Picking Plan @endif</li>
          <li>{{ __('lang.create') }} Item</li>
          <li class="active">Complete</li>
      </ul>
    </div>
    <h1>@if($type=='inbound') Putaway @else Picking Plan @endif - #{{$stockEntry->code}}
        <a href="JavaScript:poptastic('{{ route('stock_entries.print', ['stock_entry' => $stockEntry->id]) }}')" type="button" class="btn btn-warning pull-right">
            <i class="fa fa-download"></i> {{ __('lang.print') }}
        </a>
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
                <form action="#" method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="code" class="col-sm-3 control-label">@if($type=='inbound') GR# @else DP# @endif</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{empty($stockEntry->stock_transport) ?'': $stockEntry->stock_transport->code}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.created_at') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->created_at}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->ref_code}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->employee_name}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ ($stockEntry->status == 'Pending') ? 'Planning' : ($stockEntry->status)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="total_pallet" class="col-sm-3 control-label">Total {{ __('lang.pallet') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_pallet}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_labor" class="col-sm-3 control-label">Total {{ __('lang.labor') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_labor}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_forklift" class="col-sm-3 control-label">Total Forklift</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_forklift}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_koli" class="col-sm-3 control-label">Total {{ __('lang.colly') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_koli}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_berat" class="col-sm-3 control-label">Total Berat</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_berat}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockEntry->total_volume}}</p>
                                            </div>
                                        </div>
                                        @if(!empty($stockEntry->forklift_start_time))
                                            <div class="form-group">
                                                <label for="project_id" class="col-sm-3 control-label">Forklift {{ __('lang.start_time') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{$stockEntry->forklift_start_time}}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!empty($stockEntry->forklift_end_time))
                                            <div class="form-group">
                                                <label for="project_id" class="col-sm-3 control-label">Forklift {{ __('lang.end_time') }}</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{$stockEntry->forklift_end_time}}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('lang.information_item') }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped no-margin" width="100%">
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
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($stockEntry->details as $detail)
                                @if($detail->status <> 'canceled')
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
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
