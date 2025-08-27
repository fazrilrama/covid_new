@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
	<ul class="progressbar">
	    <li>Buat @if($advanceNotice->type=='inbound') AIN @else AON @endif</li>
	    <li>{{ __('lang.create') }} Item</li>
	    @if(Auth::user()->hasRole('WarehouseManager') && !$advanceNotice->employee_name)
            <li class="active">{{ __('lag.assign_suppervisor_warehouse') }}</li>
            <li>Complete</li>
        @elseif(Auth::user()->hasRole('WarehouseManager') && $advanceNotice->employee_name)
            <li>{{ __('lag.assign_suppervisor_warehouse') }}</li>
            <li class="active">Complete</li>
        @endif

        @if(!Auth::user()->hasRole('WarehouseManager'))
            <li class="active">Complete</li>
        @endif

	</ul>
</div>
<h1>
	@if($advanceNotice->type=='inbound')
		AIN @else AON 
	@endif - #{{$advanceNotice->code}}
    @if($advanceNotice->status == 'Completed')
        @if(Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor'))
        <button data-toggle="modal" data-target="#modal-otp" class="btn btn-sm btn-primary pull-right">
        	<i class="fa fa-check"></i> Close
		</button>
		<a href="JavaScript:poptastic('{{ route('advance_notices.print_unloading', ['advance_notice' => $advanceNotice->id]) }}')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px">
			<i class="fa fa-download"></i> {{ __('lang.print_accepted_receipt') }}
		</a>
		@endif
        @if(Auth::user()->hasRole('WarehouseManager'))
        	@if($advanceNotice->is_accepted == 1)
	        	@if($advanceNotice->employee_name == '')
			        <button data-toggle="modal" data-target="#modal-assignto" class="btn btn-sm btn-primary pull-right">
			        	<i class="fa fa-plus"></i> {{ __('lang.assign_to') }}
			        </button>
			    @else
				    <a href="JavaScript:poptastic('{{ route('advance_notices.print_sptb', ['advance_notice' => $advanceNotice->id]) }}')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px">
				      <i class="fa fa-download"></i> Print {{ $advanceNotice->type == 'inbound' ? 'SPBM' : 'SPBK' }}
				  	</a>
			    @endif
			@else
				<button data-toggle="modal" data-target="#modal-an-validation" class="btn btn-sm btn-danger pull-right" style="margin-right: 10px">
		        	<i class="fa fa-check"></i> Validasi
		        </button>
			@endif

		    
        @endif
    @endif
	@if((Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor')) && $advanceNotice->status == 'Closed')
		<a href="JavaScript:poptastic('{{ route('advance_notices.print_ba', ['advance_notice' => $advanceNotice->id]) }}')" class="btn btn-info"><i class="fa fa-print"></i> BA Print</a>
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
			@if($advanceNotice->status == 'Processed' && $advanceNotice->accepted_note != null && $advanceNotice->is_accepted == 2)
			<div class="alert alert-success">
					<label for="" style="color: red">{{ $advanceNotice->accepted_note }}</label>
			</div>
			@endif

      		<form  action="#" method="POST" class="form-horizontal" >
				@csrf
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label">{{ __('lang.company') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->project->company->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label">{{ __('lang.project') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->project->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label">{{ __('lang.created_at') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->created_at}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.contract') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->contract_number}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.spk') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->spmp_number}}</p>
								</div>
							</div>
							{{--<div class="form-group">--}}
								{{--<label for="user_id" class="col-sm-3 control-label">Pembuat</label>--}}
								{{--<div class="col-sm-9">--}}
									{{--<p class="form-control-static">{{empty($advanceNotice->user) ?'': $advanceNotice->user->first_name}}</p>--}}
								{{--</div>--}}
							{{--</div>--}}
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">
									@if($advanceNotice->type == 'inbound') 
					            		PO {{ __('lang.document') }}
					            	@else
					            		DO {{ __('lang.document') }}
					            	@endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										@if(!empty($advanceNotice->an_doc))
						                	<a href="{{ \Storage::disk('public')->url($advanceNotice->an_doc) }}" target="_blank">{{ __('lang.see_doc') }}</a>
						                @endif
									</p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">{{ __('lang.activity') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->advance_notice_activity->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">{{ __('lang.type_transpotation') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->transport_type->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->ref_code}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="owner" class="col-sm-3 control-label">{{ __('lang.ownership') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ empty($advanceNotice->owner) ? '' : $advanceNotice->ownership->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="owner" class="col-sm-3 control-label">{{ __('lang.pic') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->pic}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										@if(!Auth::user()->hasRole('CargoOwner'))
										{{ ($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status }}
										@else
										{{ ($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status) }}
										@endif
									</p>
								</div>
							</div>
							<div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">{{ __('lang.created_by') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{@$advanceNotice->user->user_id}}</p>
								</div>
							</div>					
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="origin" class="col-sm-3 control-label">{{ __('lang.origin') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->origin->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="etd" class="col-sm-3 control-label">{{ __('lang.etd') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->etd}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_id" class="col-sm-3 control-label">
									@if($advanceNotice->type == 'inbound') {{ __('lang.shipper') }} @else {{ __('lang.branch') }} @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_address" class="col-sm-3 control-label">
									@if($advanceNotice->type == 'inbound') {{ __('lang.shipper_address') }} @else {{ __('lang.branch_address') }} @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="destination" class="col-sm-3 control-label">{{ __('lang.destination') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->destination->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="eta" class="col-sm-3 control-label">{{ __('lang.eta') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->eta}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_id" class="col-sm-3 control-label">
									@if ($advanceNotice->type == 'inbound') {{ __('lang.branch') }}@else {{ __('lang.consignee') }} @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_address" class="col-sm-3 control-label">
									@if ($advanceNotice->type == 'inbound') {{ __('lang.branch_address') }} @else {{ __('lang.consignee_address') }} @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.warehouse_supervisor') }}</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->employee_name}}</p>
								</div>
							</div>
							@if($advanceNotice->warehouse)
								<div class="form-group">
									<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.warehouse') }}</label>
									<div class="col-sm-9">
										<p class="form-control-static">{{$advanceNotice->warehouse->name}}</p>
									</div>
								</div>
							@endif
							<!-- <div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Annotation</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->annotation ?? '-'}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Contractor</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->contractor ?? '-'}}</p>
								</div>
							</div> -->
							<!-- <div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Head Cabang/Subcabang</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->head_ds}}</p>
								</div>
							</div> -->
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
				            <th>Group Reference:</th>
				            <th>Qty:</th>
				            <th>UOM:</th>
				            <th>Weight:</th>
				            <th>Volume:</th>
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
				          </tr>
				      @endforeach
				      </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>

@include('otp', ['annotation' => 'yes',  'url' => route('advance_notices.closed', ['advance_notice' => $advanceNotice->id])])

@include('an_validation', ['advanceNotice' => $advanceNotice, 'url' => route('advance_notices.validation', ['advance_notice' => $advanceNotice->id])])

@if($advanceNotice->is_sto == 1)
	@include('assignto', ['url' => route('stock_transfer_order.assignto', ['advance_notice' => $advanceNotice->id])])
@else
	@include('assignto', ['advanceNotice' => $advanceNotice, 'url' => route('advance_notices.assignto', ['advance_notice' => $advanceNotice->id])])
@endif

@endsection
