@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
	<ul class="progressbar">
	    <li>Buat @if($advanceNotice->type=='inbound') STOI @else STOO @endif</li>
	    <li>Tambah Item</li>
	    <li class="active">Complete</li>
	</ul>
</div>
<h1>@if($advanceNotice->type=='inbound') STOI @else STOO @endif - #{{$advanceNotice->code}}
    @if($advanceNotice->status == 'Completed')
        @if(Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor'))
        <button data-toggle="modal" data-target="#modal-otp" class="btn btn-sm btn-primary pull-right">
        	<i class="fa fa-check"></i> Close
		</button>
        @endif
        @if(Auth::user()->hasRole('WarehouseManager'))
        	@if($advanceNotice->employee_name == '')
		        <button data-toggle="modal" data-target="#modal-assignto" class="btn btn-sm btn-primary pull-right">
		        	<i class="fa fa-plus"></i> Assign To
		        </button>
		    @else
			    <a href="JavaScript:poptastic('{{ route('stock_transfer_order.print_sptb', ['advance_notice' => $advanceNotice->id]) }}')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
			      <i class="fa fa-download"></i> Print {{ $advanceNotice->type == 'inbound' ? 'SPBM' : 'SPBK' }}
			  	</a>
		    @endif
			
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

      		<form  action="#" method="POST" class="form-horizontal" >
				@csrf
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label">Project</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->project->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label">Tanggal Dibuat</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->created_at}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">No. Kontrak</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->contract_number}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">No. SPK</label>
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
								<label for="transport_type_id" class="col-sm-3 control-label">Jenis Kegiatan</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->advance_notice_activity->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">Moda Transportasi</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->transport_type->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Customer Reference</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->ref_code}}</p>
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
								<label for="user_id" class="col-sm-3 control-label">Pembuat</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{@$advanceNotice->user->user_id}}</p>
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">No. SPTB</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{@$advanceNotice->sptb_num}}</p>
								</div>
							</div> -->
							<div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">No. SPPK</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{@$advanceNotice->sppk_num}} (<a href="{{ \Storage::disk('public')->url($advanceNotice->sppk_doc) }}"  target="_blank">Lihat Dokumen</a>)</p>
								</div>
							</div>							
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="origin" class="col-sm-3 control-label">Origin</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->origin->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="etd" class="col-sm-3 control-label">Est. Time Delivery</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->etd}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_id" class="col-sm-3 control-label">
									@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_address" class="col-sm-3 control-label">
									@if($advanceNotice->type == 'inbound') Shipper @else Cabang/Subcabang @endif Address
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{ empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="destination" class="col-sm-3 control-label">Destination</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->destination->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="eta" class="col-sm-3 control-label">Est. Time Arrival</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->eta}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_id" class="col-sm-3 control-label">
									@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_address" class="col-sm-3 control-label">
									@if ($advanceNotice->type == 'inbound') Cabang/Subcabang @else Consignee @endif Address
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Warehouse Supervisor</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$advanceNotice->employee_name}}</p>
								</div>
							</div>
							@if($advanceNotice->warehouse)
								<div class="form-group">
									<label for="ref_code" class="col-sm-3 control-label">Warehouse</label>
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
				  <table class="data-table table table-bordered table-hover no-margin item-transaction-table" width="100%">

				      <thead>
				          <tr>
							<th>ID:</th>
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
							<td>{{$detail->id}}</td>
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

@include('otp', ['url' => route('stock_transfer_order.closed', ['advance_notice' => $advanceNotice->id])])

@include('assignto', ['url' => route('stock_transfer_order.assignto', ['advance_notice' => $advanceNotice->id])])

@endsection
