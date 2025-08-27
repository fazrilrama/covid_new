@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
        <ul class="progressbar">
            <li class="active">Buat @if($type=='inbound') AIN @else AON @endif</li>
            <li>Tambah Item</li>
            <li>Complete</li>
        </ul>
    </div>
    <h1 style="float:left">Create @if($type=='inbound') AIN @else AON @endif</h1>
@stop

@section('content')
<div class="row">
	<div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                    <h3 class="box-title">Informasi Data</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            <!-- /.box-tools -->
            </div>
			@include('advance_notices.form')
			@include('form_confirmation')
		</div>
	</div>
</div>
@endsection