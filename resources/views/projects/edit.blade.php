@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Edit Project - {{$project->name}}</h1>
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
			@include('projects.form')
		</div>
	</div>
  @if($project_storage->count() > 0)
    <div class="col-md-6">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Storage List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>  
        <div class="table-responsive">
          <table class="data-table table" width="100%">
              <thead>
                <tr>
                    <th>Code</th>
                    <th>Warehouse</th>
                    <th>Cabang/Subcabang</th>
                    <th>Action</th>
                </tr>
              </thead>

              <tbody>
                @foreach($project_storage as $ps)
                  <tr>
                      <td>{{$ps->storage->code}}</td>
                      <td>{{$ps->storage->warehouse->name}}</td>
                      <td>{{$ps->storage->warehouse->branch->name}}</td>
                      <td>
                        <div class="btn-group" role="group">
                          <form action="{{route('delete_project_storage')}}" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="storage_project_id" value="{{ $ps->id }}">
                            <input type="hidden" name="project_id" value="{{ $ps->project_id }}">
                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                          </form>
                        </div>
                      </td>
                  </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        
      </div>
    </div>
  @endif
</div>
@endsection