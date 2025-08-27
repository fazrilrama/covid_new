@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
      <ul class="progressbar">
          <li>{{ __('lang.create') }} Good Issues</li>
          <li class="active">Complete</li>
      </ul>
    </div>
    <h1>Goods Issue - #{{$stockDelivery->code}}
        <a href="JavaScript:poptastic('{{ url('/stock_deliveries/'.$stockDelivery->id.'/print') }}')" type="button" class="btn btn-warning pull-right">
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
                                            <label for="transport_type" class="col-sm-3 control-label">{{ __('lang.document_reference') }} #</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->stock_entry->code}}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label">{{ __('lang.created_at') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->created_at}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label">{{ __('lang.type_transpotation') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->transport_type->name}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.shipper') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ empty($stockDelivery->shipper) ?'' : $stockDelivery->shipper->name}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label">{{ __('lang.shipper_address') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ empty($stockDelivery->shipper_address) ?'' : $stockDelivery->shipper_address}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->ref_code}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ ($stockDelivery->status == 'Pending') ? 'Planning' : ($stockDelivery->status)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="total_pallet" class="col-sm-3 control-label">Total {{ __('lang.colly') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ empty($stockDelivery->total_collie) ? '0' : $stockDelivery->total_collie}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_labor" class="col-sm-3 control-label">Total Weight</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ empty($stockDelivery->total_weight) ? '0' : $stockDelivery->total_weight}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_forklift" class="col-sm-3 control-label">Total Volume</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ empty($stockDelivery->total_volume) ? '0' : $stockDelivery->total_volume}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Origin</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->origin->name}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Destination</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->destination->name}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">{{ __('lang.eta') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->etd}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">{{ __('lang.etd') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->eta}}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{$stockDelivery->employee_name}}</p>
                                            </div>
                                        </div>
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
                                <th>Group Ref</th>
                                <!-- <th>Control Date:</th> -->
                                <th>Qty:</th>
                                <th>UoM:</th>
                                <th>Weight:</th>
                                <th>Volume:</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($stockDelivery->details as $detail)
                                <tr>
                                    <td>{{$detail->item->sku}}</td>
                                    <td>{{$detail->item->name}}</td>
                                    <td>{{$detail->ref_code}}</td>
                                    <!-- <td>{{$detail->control_date}}</td> -->

                                    <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                                    <td>{{$detail->uom->name}}</td>
                                    <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                                    <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
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