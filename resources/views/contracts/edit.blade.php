@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Contract - {{$contract->number_contract}}</h1>
@stop

@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
        <h3 class="box-title">Informasi Data</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
			@include('contracts.form')
		</div>
	</div>
  <div class="col-md-6">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Daftar Rented Warehouse</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        <table class="table">
          <thead>
            <th>Warehouse</th>
          </thead>
          <tbody>
            @if($contract->warehouses->count()>0)
            @foreach($contract->warehouses as $warehouse)
              <tr>
                <td>{{$warehouse->code}} - {{$warehouse->name}} | {{empty($warehouse->space) ? '0': $warehouse->space}} m2</td>
              </tr>
            @endforeach
            @endif
          </tbody>
        </table>
        
      </div>
      <div class="box-footer">
        <a href="{{route('contracts.edit_warehouses',$contract->id)}}" class="btn btn-primary">Edit Warehouse</a>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
</div>
@endsection