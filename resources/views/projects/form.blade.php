<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Project ID</label>
			<div class="col-sm-9">
				<input type="text" name="project_id" class="form-control" placeholder="Project id" value="{{old('project_id', !empty($namingSeries) ? $namingSeries : (!empty($project) ? $project->project_id : '') )}}" {{ empty($project->id) ?: 'readonly' }}>
			</div>
		</div>
		<div class="form-group required">
            <label for="name" class="col-sm-3 control-label">Nama Project</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $project->name)}}">
            </div>
        </div>

		{{-- @if($isSuperadmin) --}}
			<div class="form-group required">
				<label for="company_id" class="col-sm-3 control-label">Project Owner</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="company_id" required>
						@foreach($companies as $id => $value)
						<option value="{{$id}}" @if($project->company_id == $id){{'selected'}}@endif>{{$value}}</option>
						@endforeach
					</select>
				</div>
			</div>
		{{-- @endif --}}
		<div class="form-group required">
			<label for="description" class="col-sm-3 control-label">Deskripsi Project</label>
			<div class="col-sm-9">
				<textarea rows="10" name="description" class="form-control" placeholder="description">{{old('description', $project->description)}}</textarea>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
		@if($method == 'PUT')
			<a href="{{route('to_add_project_storage',$project)}}" class="btn btn-warning pull-right" style="margin-right: 10px">Add Storage</a>
		@endif
	</div>
	<!-- /.box-footer -->
</form>
