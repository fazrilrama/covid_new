@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <div class="container">
        <ul class="progressbar">
            <li class="active">Buat Stock Opname</li>
            <li>Edit Item</li>
            <li>Print</li>
        </ul>
    </div>
    <h1 style="float:left">Buat Stock Opname</h1>
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
			@include('stock_opnames.form')
		</div>
	</div>
</div>
@endsection