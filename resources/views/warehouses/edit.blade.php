@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Gudang - {{$warehouse->name}}</h1>
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
			@include('warehouses.form')
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
        <h3 class="box-title">Additional Data</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <form action="{{ $action_add }}" method="POST" class="form-horizontal">
        @csrf
        <div class="box-body">
          @foreach($warehouse_add as $wa)
          <div class="form-check">
            <input 
              class="form-check-input" 
              type="checkbox" 
              value="{{ $wa['id'] }}" 
              id="warehouse_add_{{ $wa['id'] }}" 
              name="warehouse_add[]"
              {{ in_array($wa['id'], $selected) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="warehouse_add_{{ $wa['id'] }}">
              {{ $wa['name'] }}
            </label>
          </div>
          @endforeach
        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-info pull-right">Simpan</button>
        </div>
      </form>
		</div>
  </div>
</div>
@endsection