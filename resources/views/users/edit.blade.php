@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
<h1>Edit User - {{$user->user_id}}</h1>
@stop

@section('content')
<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal" name="userForm">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf

	<div class="row">
		<div class="col-md-12">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title">Data Pengguna</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
					<!-- /.box-tools -->
				</div>
				@include('users.form')
			</div>
		</div>
		<div class="col-md-12">
			<!-- <div class="box box-default"> -->
				<!-- <div class="box-header with-border"> -->
					<!-- <h3 class="box-title">Role User</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div> -->
					
				<!-- </div> -->
				<!-- <div class="box-body"> -->
					<!-- <div class="table-responsive"> -->
						<!-- <table class="table table-bordered table-hover no-margin" width="100%">
							<tbody>
								@foreach($roles as $role)
								<tr>
									<td>
										<input type="checkbox" name="roles[]" value="{{$role->id}}" @if($user->roles->contains($role->id)){{'checked'}}@endif>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$role->name}}
									</td>
								</tr>
								@endforeach
							</tbody>
						</table> -->
					<!-- </div> -->
				<!-- </div> -->
			<!-- </div> -->
			<!-- <input type="hidden" name="roles[]" value="{{$role->id}}"@if($user->roles->contains($role->id)){{'checked'}}@endif>> -->
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
					@if($user->projects->count()>0)
					<ul class="list-group">
						@foreach($user->projects as $project)
						<li class="list-group-item">{{$project->project_id}} - {{$project->name}}</li>
						@endforeach
					</ul>
					@endif
				</div>
				<div class="box-footer">
					<a href="{{route('users.edit_projects',$user->id)}}" class="btn btn-primary">Edit Akses Project</a>
				</div>
				<!-- /.box-footer -->
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title">Role User</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
					<!-- /.box-tools -->
				</div>
				<div class="box-body" id="roles-checkbox">
					<div class="form-group">
						<div class="col-sm-12">
							<select class="form-control" name="roles" id="roles" required>
								<option value="" disabled>-- Pilih Roles --</option>
								@foreach($roles as $value)
								<option value="{{$value->id}}" @if($user->roles->contains($value->id) == $value->id){{'selected'}}@endif>{{$value->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					@foreach($permissions as $value)
					<div class="col-md-3 checkbox-roles">
						<input type="checkbox" id="{{$value->id}}" name="permissions[]" value="{{$value->id}}" @if($user->permissions->contains($value->id)){{'checked'}}@endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$value->name}}
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-footer">
				<button type="submit" class="btn btn-info pull-right" onclick="validateForm()">Simpan</button>
			</div>
			<!-- /.box-footer -->
		</div>
	</div>
</div>
</form>
@endsection

@section('js')
<script>
	$('#body').ready(function(){
		$('#roles-checkbox input:checkbox').removeAttr('checked');
		$.ajax({
			method: 'GET',
			url: '/permissionedit/' + $('#user_id_edit').val(),
			dataType: "json",
			success: function(data){
				if (data.length >= 1) {
					for (var i = 0; i <= data.length; i++) {
						$("#"+data[i].permission_id).prop('checked',true);
					}
				}  else {
					alert("Data Roles tidak ditemukan");
				}
			},
			error:function(){
				console.log('error '+ data);
			}
		});
	});

	$('#roles').on('change', function(){
		$('#roles-checkbox input:checkbox').removeAttr('checked');
		$.ajax({
			method: 'GET',
			url: '/permission/' + $(this).val(),
			dataType: "json",
			success: function(data){
				if (data.length >= 1) {
					for (var i = 0; i <= data.length; i++) {
						$("#"+data[i].permission_id).prop('checked',true);
					}
				}  else {
					alert("Data Roles tidak ditemukan");
				}
			},
			error:function(){
				console.log('error '+ data);
			}
		});
	});

	 function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
</script>
@endsection