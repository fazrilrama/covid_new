@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit User - {{$user->user_id}}</h1>
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
        <h3 class="box-title">Daftar Akses Project</h3>
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
                      <th>Nama Project:</th>
                  </tr>
                </thead> -->
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>
                          <input type="checkbox" name="projects[]" value="{{$project->id}}" @if($user->projects->contains($project->id)){{'checked'}}@endif>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{@$project->project_id}} - {{@$project->company->name}} - {{@$project->name}}
                        </td>
                    </tr>
                @endforeach
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