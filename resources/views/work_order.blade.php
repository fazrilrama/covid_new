@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
	<h1>Work Order</h1>
@stop

@section('content')

	<h2 class="page-header">AIN</h2>
	<div class="table-responsive">
		<table id="work-order-ain" class="data-table table no-margin" width="100%">
			<thead>
				<tr>
					<th>Project</th>
					<th>AIN #</th>
					<th>Storage Area</th>
					<th>Warehouse</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>ETD</th>
					<th>ETA</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				@foreach($inbounds as $item)
					<tr @if($item->row_color) bgcolor="{{$item->row_color}}" style="color: #fff;" @endif>
						<td>{{$item->project->name}}</td>
						<td>{{$item->code}}</td>
						<td>{{$item->consignee->name}}</td>
						<td>{{ $item->warehouse['name']}}</td>
						<td>{{$item->origin->name}}</td>
						<td>{{$item->destination->name}}</td>
						<td>{{$item->etd}}</td>
						<td>{{$item->eta}}</td>
						<td>@if($item->status == 'Completed') Submitted @else {{$item->status}} @endif</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<hr>

	<h2 class="page-header">AON</h2>
	<div class="table-responsive">
		<table id="work-order-aon" class="data-table table no-margin" width="100%">

			<thead>
				<tr>
					<th>Project</th>
					<th>AON #</th>
					<th>Storage Area</th>
					<th>Warehouse</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>ETD</th>
					<th>ETA</th>
					<th>Status</th>
				</tr>
			</thead>

			<tbody>
				@foreach($outbounds as $item)
					<tr @if($item->row_color) bgcolor="{{$item->row_color}}" style="color: #fff;" @endif>
						<td>{{$item->project->name}}</td>
						<td>{{$item->code}}</td>
						<td>{{$item->shipper->name}}</td>
						<td>{{$item->warehouse['name']}}</td>
						<td>{{$item->origin->name}}</td>
						<td>{{$item->destination->name}}</td>
						<td>{{$item->etd}}</td>
						<td>{{$item->eta}}</td>
						<td>@if($item->status == 'Completed') Submitted @else {{$item->status}} @endif</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

@endsection

@section('js')
	<script type='text/javascript'>
        setTimeout("location.reload();",1500000);
        $(document).ready(function() {
            $('#work-order-ain').DataTable( {
                destroy: true,
                "order": [[ 8, "desc" ]]
            } );

            $('#work-order-aon').DataTable( {
                destroy: true,
                "order": [[ 8, "desc" ]]
            } );
        } );
	</script>
@endsection