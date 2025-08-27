@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
      <ul class="progressbar">
          <li class="active">{{ __('lang.create') }} @if($type=='inbound') Putaway @else Picking Plan @endif</li>
          <li>{{ __('lang.create') }} Item</li>
          <li>Complete</li>
      </ul>
    </div>
    <h1>{{ __('lang.create') }} @if($type=='inbound') Putaway @else Picking Plan @endif</h1>
@stop

@section('content')

@if(empty($warehouses))
    <div class="alert alert-danger">
        <p>Anda tidak memiliki storage. Silahkan tambah data storage terlebih dahulu</p>
    </div>
@endif

<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-header with-border">
        <h3 class="box-title">{{ __('lang.information_data') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
			@include('stock_entries.form')
			@include('form_confirmation')
		</div>
	</div>
</div>
@endsection