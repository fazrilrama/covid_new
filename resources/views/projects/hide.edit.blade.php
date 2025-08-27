@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Project</h1>
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
			@include('projects.form')
		</div>
	</div>
  <div class="col-md-6">
    <div class="box box-default">
      <!--<div class="box-header with-border">
        <h3 class="box-title">Informasi Jenis</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover no-margin" width="100%">
              <tbody>
              @foreach($party_types as $party_type)
                  <tr>
                      <td><input type="checkbox" name="party_types[]" value="{{$party_type->id}}" @if($party->party_types->contains($party_type->id)){{'checked'}}@endif>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$party_type->name}}
                      </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
        </div>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      <button type="submit" class="btn btn-info pull-right">Simpan</button>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
</div>

</form>
@endsection