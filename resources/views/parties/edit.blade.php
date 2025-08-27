@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Party</h1>
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
          <h3 class="box-title">Informasi Data</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
  			@include('parties.form')
  		</div>
  	</div>
  </div>

</form>
@endsection