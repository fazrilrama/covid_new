@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Daftar Perusahaan
    	<a href="{{url('companies/create')}}" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Kode Perusahaan:</th>
	                <th>Name:</th>
	                <th>Address:</th>
	                <th>Type:</th>
	                <th>City:</th>
	                <th width="15%"></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        @foreach($collections as $item)
	            <tr>
	                <td>{{$item->code}}</td>
	                <td>{{$item->name}}</td>
	                <td>{{$item->address}}</td>
	                <td>{{$item->company_type->name}}</td>
	                <td>{{$item->city->name}}</td>
					<td>
	                	<div class="btn-toolbar">
							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-CompanyList'))
								<div class="btn-group" role="group">
									<a href="{{url('companies/'.$item->id.'/edit')}}" type="button" class="btn btn-primary" title="Edit">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							@endif

							@if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-CompanyList'))
								<div class="btn-group" role="group">
									<form action="{{url('companies', [$item->id])}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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