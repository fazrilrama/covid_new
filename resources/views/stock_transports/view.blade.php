@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
        <ul class="progressbar">
            <li>{{ __('lang.create') }} @if($type=='inbound') Goods Receiving @else Delivery Plan @endif</li>
            <li>{{ __('lang.create') }} Item</li>
            <li class="active">Complete</li>
        </ul>
    </div>
    <h1>@if($type=='inbound') Goods Receiving @else Delivery Plan @endif - #{{$stockTransport->code}}
        @if($type == 'inbound' AND $stockTransport->status == 'Completed')
        <a href="JavaScript:poptastic('{{ route('stock_transports.print', ['stock_transport' => $stockTransport->id]) }}')" type="button" class="btn btn-warning pull-right">
            <i class="fa fa-download"></i> {{ __('lang.print') }} GR
        </a>
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
                <form action="#" method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="project_id" class="col-sm-3 control-label">Project</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->project->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="created_at" class="col-sm-3 control-label">{{ __('lang.created_at') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->created_at}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="project_id" class="col-sm-3 control-label">
                                        {{ ($stockTransport->type == 'inbound') ? 'AIN#:' : 'AON#:'}}
                                    </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->advance_notice->code}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="transport_type_id" class="col-sm-3 control-label">{{ __('lang.type_transpotation') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->transport_type->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->ref_code}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_id" class="col-sm-3 control-label">{{ __('lang.shipper') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->shipper->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_address" class="col-sm-3 control-label">{{ __('lang.shipper_address') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->shipper->address}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label">{{ __('lang.origin') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->origin->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label">{{ __('lang.origin_address') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->origin_address}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label">{{ __('lang.origin_post_code') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->origin_postcode}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label">{{ __('lang.origin') }} Latitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->origin_latitude}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label">{{ __('lang.origin') }} Longitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->origin_longitude}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.pickup_time') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->pickup_order}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="etd" class="col-sm-3 control-label">{{ __('lang.etd') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->etd}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ ($stockTransport->status == 'Pending') ? 'Planning' : $stockTransport->status }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user_id" class="col-sm-3 control-label">{{ __('lang.created_by') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{empty($stockTransport->user) ?'': $stockTransport->user->first_name}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">Traffic</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">
                                            @if( $stockTransport->traffic == 1)
                                                PTP
                                            @elseif( $stockTransport->traffic == 2)
                                                DTD
                                            @elseif( $stockTransport->traffic == 3)
                                                DTP
                                            @elseif( $stockTransport->traffic == 4)
                                                PTD
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">Loading Type</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">
                                            @if( $stockTransport->load_type == 1)
                                                LTL
                                            @elseif( $stockTransport->load_type == 2)
                                                FTL
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="consignee_id" class="col-sm-3 control-label">{{ __('lang.consignee') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->consignee->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="consignee_address" class="col-sm-3 control-label">{{ __('lang.consignee_address') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->consignee_address}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">{{ __('lang.destination') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->destination->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">{{ __('lang.destination_address') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->dest_address}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">{{ __('lang.destination') }} Post Code</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->dest_postcode}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">{{ __('lang.destination') }} Latitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->dest_latitude}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">{{ __('lang.destination') }} Longitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->dest_longitude}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="eta" class="col-sm-3 control-label">{{ __('lang.eta') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->eta}}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{$stockTransport->employee_name}}</p>
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
                    <h3 class="box-title">Informasi Barang</h3>
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
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($stockTransport->details as $detail)
                                <tr>
                                    <td>{{$detail->item->sku}}</td>
                                    <td>{{$detail->item->name}}</td>
                                    <td>{{$detail->ref_code}}</td>
                                    <td>{{$detail->control_date}}</td>
                                    <td>{{number_format($detail->plan_qty, 2, ',', '.')}}</td>
                                    @if($stockTransport->type == 'inbound')
                                        <td>{{number_format($detail->qty, 2, ',', '.')}}</td>
                                    @endif
                                    <td>{{$detail->uom->name}}</td>
                                    @if($stockTransport->type == 'inbound')
                                        <td>{{number_format($detail->weight, 2, ',', '.')}}</td>
                                        <td>{{number_format($detail->volume, 2, ',', '.')}}</td>
                                    @endif
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
