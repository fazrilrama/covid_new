@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Gudang List
		<form action="{{ route('warehouses.index') }}" method="GET">
			@if(!Auth::user()->hasRole('AdminBulog'))
			<a href="{{url('warehouses/create')}}" type="button" class="btn btn-success" title="Create">
				<i class="fa fa-plus"></i> Tambah
			</a>
			@endif
			@if(Auth::user()->hasRole('Superadmin'))
			<button class="btn btn-sm btn-warning" type="submit" name="submit" value="1">
				<i class="fa fa-download"></i> Export ke Excel
			</button>
			@endif
		</form>
    </h1>
@stop

@section('content')

	<div class="table-responsive">
	    {{-- {!! $html->table(['class' => 'data-table table table-bordered table-hover no-margin']) !!} --}}
	    <table class="data-table table table-bordered table-hover no-margin" id="example" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Code:</th>
	                <th>Name:</th>
	                <th>Region:</th>
	                <th>Branch:</th>
	                <th>Total Luas Terpasang:</th>
	                <th>Total Volume:</th>
	                <th>Total Kapasitas(kg) Terpasang:</th>
	                <th>Total Luas Terpakai(m<sup>2</sup>):</th>
	                <th>Utilitas Gudang (Luas):</th>
					<th>Gudang</th>
					<th class="no-sort">
					Status
					<select class="select" name="status" id="select">
						<option value=" ">Semua</option>
						<option value=".Aktif">Aktif</option>
						<option value="Tidak Aktif">Tidak Aktif</option>
					</select>
					</th>
					<th>Operasi:</th>
					<th>Type:</th>
	                <th>Action:</th>
	            </tr>
	            <tbody>
			        @foreach($warehouse as $item)
			            <tr>
			            	<td>{{ $item->code }}</td>
			            	<td>{{ $item->name }}</td>
			            	<td>
			            		@if($item->region)
			            			{{ $item->region->name }}</td>
			            		@endif
			            	<td>
			            		@if($item->branch)
			            			{{ $item->branch->name }}
			            		@endif
			            	</td>
			            	<td>{{ number_format($item->length*$item->width, 2, ',', '.') }}</td>
			            	<td>{{ number_format($item->total_volume, 2, ',', '.') }}</td>
			            	<td>{{ number_format($item->total_weight, 2, ',', '.') }}</td>
			            	<td>
			            		@php
			            			$contracts = $item->contracts();
				                    $total_rented_space = 0;
				                    foreach($contracts->where('is_active', 1)->get() as $contract) {
				                        $total_rented_space += $contract->pivot->rented_space;   
				                    }
				                    
			            		@endphp
			            		{{ number_format($total_rented_space, 2, ',', '.') }}
			            	</td>
			            	<td>
			            		@php
			            			$contracts = $item->contracts();

				                    $total_rented_space = 0;
				                    $utility_space = 0;
				                    $total_space = $item->length * $item->width;
				                    foreach($contracts->where('is_active', 1)->get() as $contract) {
				                        $total_rented_space += $contract->pivot->rented_space;   
				                    }
				                    if (!empty($total_space)) {
				                        $utility_space = ($total_rented_space > 0) ? round(($total_rented_space / $total_space)*100,2) : 0 ;
				                    }else {
				                        $utility_space = 0 ;
				                    }

			            		@endphp
			            		{{ number_format($utility_space, 2, ',', '.') }}%
			            	</td>
							<td>{{ strtoupper($item->ownership) }}</td>
							<td>
							{{$item->is_active == 1 ? '.Aktif' : 'Tidak Aktif'}}
							</td>
							<td>{{ $item->status ?? 'Belum Memilih' }}</td>
							<td>
								@if($item->type_warehouse)
									<span class="badge bg-secondary">{{ $item->type_warehouse }}</span>
								@else
									<span class="badge bg-secondary">-</span>
								@endif
							</td>
			            	<td>
			            		<div class="btn-toolbar">
			            			<div class="btn-group" role="group">
										@if(auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('WarehouseManager'))
										<a href="{{route('to_add_user', $item->id)}}" type="button" class="btn btn-warning">
											<i class="fa fa-user"></i>
										</a>
										@endif
										<a href="{{route('warehouses.edit', $item->id)}}" type="button" class="btn btn-primary">
											<i class="fa fa-pencil"></i>
										</a>

										
										@if(auth()->user()->hasPermission('delete-WarehousesList'))
										<form action="{{route('warehouses.destroy', $item->id)}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
										</form>
										@endif
									</div>
			                    </div>
			            	</td>
			            </tr>
			        @endforeach
			    </tbody>
	        </thead>
	    </table>
	</div>

@endsection

@section('custom_script')
	<script>
	var table = $('#example').DataTable({
			"columnDefs": [ {
			"targets": 'no-sort',
			"orderable": true,
		} ]
	});

	$('#select').on('change', function(){
		table.column(10).search(this.value).draw();   
		$('.input-sm').val('');
	});
	</script>
@endsection