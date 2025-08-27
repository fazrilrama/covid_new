@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<div class="container">
	<ul class="progressbar">
	    <li>Buat Internal Movement</li>
	    <li>Tambah Item</li>
	    <li class="active">Complete</li>
	</ul>
</div>
<h1>Internal Movemet - #{{ $stock_internal_movement->code }}
    
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
								<label for="code_reference" class="col-sm-3 control-label">Referensi</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$stock_internal_movement->code_reference }}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="v" class="col-sm-3 control-label">Alasan</label>
								<div class="col-sm-9">
									<p class="form-control-static">{{$stock_internal_movement->note}}</p>
								</div>
							</div>
							<div class="form-group">
								<label for="v" class="col-sm-3 control-label">Dokumen</label>
								<div class="col-sm-9">
									@if(!empty($stock_internal_movement->document))
										<a href="{{ \Storage::disk('public')->url($stock_internal_movement->document) }}" target="_blank">See Doc</a>
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
							<th>Group Ref:</th>
							<th>Origin Storage:</th>
							<th>Destination Storage:</th>
							<th>Qty:</th>
							<th>UOM:</th>
				          </tr>
				      </thead>

				      <tbody>
				      @foreach($stock_internal_movement->detailmovement as $detail)
							<tr>
							<td>{{ $detail->id }}</td>
							<td>{{ $detail->item->sku }}</td>
							<td>{{ $detail->item->name }}</td>
							<td>{{ $detail->ref_code }}</td>
							<td>{{ $detail->origin_storage->code }}</td>
							<td>{{ $detail->dest_storage->code }}</td>
							<td>{{ $detail->movement_qty }}</td>
							<td>{{ $detail->uom->name }}</td>
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
