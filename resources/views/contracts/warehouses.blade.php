@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Contract - {{$contract->number_contract}}</h1>
@stop

@section('content')
<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
    {{ method_field($method) }}
@endif
@csrf

<div class="row">
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
        <div class="table-responsive">
          <table class="table table-bordered table-hover no-margin" width="100%">
              <!-- <thead>
                <tr>
                    <th>Nama Warehouse:</th>
                    <th></th>
                </tr>
              </thead> -->
              <tbody>
              @if($warehouses)
                @foreach($warehouses as $warehouse)
                    <tr>
                        <td>
                          <input type="checkbox" name="warehouses[]" value="{{$warehouse->id}}" {{ !$warehouse->selected ?'': 'checked' }}>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$warehouse->code}} - {{$warehouse->name}}<br/>
                          {{@$warehouse->city->name}}, {{@$warehouse->region->name}}
                        </td>
                        <td>
                          <input type="text" placeholder="Space" name="warehouses_space[{{$warehouse->id}}]" value="{{$warehouse->space}}">M2
                        </td>
                    </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-footer">
      <button type="submit" class="btn btn-info pull-right">Simpan</button>
      </div>
		</div>
	</div>
</div>
</form>
@endsection