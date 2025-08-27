@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Storage List
	@if(!Auth::user()->hasRole('AdminBulog'))

    	<a href="{{url('storages/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
	@endif
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Storage ID:</th>
	                <th>Warehouse ID:</th>
	                <th>Warehouse:</th>
	                <th>Komoditas:</th>
	                <th>Space (m<sup>2</sup>):</th>
	                <th>Volume (m<sup>3</sup>):</th>
					<th>Status</th>
	                <!-- <th>Used Weight:</th>
	                <th>Used Volume:</th>
	                <th>Utility Weight:</th>
	                <th>Utility Volume:</th> -->
	                <th>Aksi</th>
	            </tr>
	        </thead>
	        
	        <tbody>
		        @foreach($collections as $item)
		            <tr>
		                <td>{{$item->code}}</td>
		                <td>{{empty($item->warehouse) ?'': $item->warehouse->code}}</td>
		                <td>{{empty($item->warehouse) ?'': $item->warehouse->name}}</td>
		                <td>{{empty($item->commodity) ?'': $item->commodity->name}}</td>
		                <td>{{$item->width * $item->length}}</td>
		                <td>{{$item->volume}}</td>
						<th>{{ $item->is_active == '0' ? 'Tidak Aktif' : 'Aktif' }}</th>
		                <!-- <td>{{$item->used_weight}}</td>
		                <td>{{$item->used_volume}}</td>
		                <td>{{@number_format(($item->used_weight/$item->weight)/100, 2, '.','.') }}%</td>
		                <td>{{@number_format(($item->used_volume/$item->volume)/100, 2, '.', '.') }}%</td> -->
		                <td>
		                	<div class="btn-toolbar">
								@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-Storages'))
									<div class="btn-group" role="group">
										<a href="{{url('storages/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
											<i class="fa fa-pencil"></i>
										</a>
									</div>
								@endif

								@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-Storages'))
									<div class="btn-group" role="group">
										<form action="{{url('storages', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
										</form>
									</div>
								@endif	
		                    </div>
		                </td>
		            </tr>
		        @endforeach
	      	</tbody>
	    </table>
	</div>

@endsection