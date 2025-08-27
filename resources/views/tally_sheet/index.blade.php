@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Tally Sheet</h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>ID:</th>
	                <th>#:</th>
	                <th>Origin:</th>
	                <th>Destination:</th>
	                <th>ETD:</th>
	                <th>ETA:</th>
	                <th>Updated At:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->id}}</td>
	                <td>{{$item->code}}</td>
	                <td>{{$item->origin}}</td>
	                <td>{{$item->destination}}</td>
	                <td>{{$item->etd}}</td>
	                <td>{{$item->eta}}</td>
	                <td>{{$item->updated_at}}</td>
	                <td>
	                	<div class="btn-group" role="group">
		                   	<a href="{{url('tally_sheet/'.$item->id.'/show')}}" type="button" class="btn btn-primary" title="Show">
		                   		<i class="fa fa-eye"></i>
		                   	</a>
		                </div>
	                </td>
	            </tr>
	        @endforeach
	      </tbody>
	    </table>
	</div>

@endsection