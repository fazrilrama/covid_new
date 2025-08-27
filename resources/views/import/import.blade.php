@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Import</h1>
@stop

@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
          <form action="{{ $action }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">
              <div class="form-group required">
                <div class="col-sm-12">
                    <input type="file" name="import" class="form-control" />
                </div>
              </div>
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-info pull-right">Simpan</button>
            </div>
          </form>
        </div>
		</div>
	</div>
</div>
@endsection