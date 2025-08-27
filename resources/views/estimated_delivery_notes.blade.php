@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Estimasi jadwal pengiriman barang</h1>
@stop

@section('content')

    <a href="{{ url('/report/estimated_delivery_notes/print') }}" class="btn btn-sm btn-primary" onclick="cetak()">Cetak</a>
			
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>No:</th>
	                <th>ETD:</th>
	                <th>DN#:</th>
	                <th>SHIPPER:</th>
	                <th>CONSIGNEE:</th>
	                <th>ASAL:</th>
	                <th>TUJUAN:</th>
	                <th>Kg:</th>
	                <th>M3:</th>
	                <th>Pcs:</th>
	                <th>Created At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $key => $item)
	            <tr>
	                <td>{{$key+1}}</td>
	                <td>{{$item->etd}}</td>
	                <td>{{$item->code}}</td>
	                <td>{{empty($item->shipper) ?'': $item->shipper->name}}</td>
	                <td>{{empty($item->consignee) ?'': $item->consignee->name}}</td>
	                <td>{{empty($item->origin) ?'': $item->origin->name}}</td>
	                <td>{{empty($item->destination) ?'': $item->destination->name}}</td>
	                <td>{{$item->qty}}</td>
	                <td>{{$item->weight}}</td>
	                <td>{{$item->volume}}</td>
	                <td>{{$item->created_at}}</td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@stop
