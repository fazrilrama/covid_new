@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Profile</h1>
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
			
            <!-- form start -->
			<form action="{{ route('profile') }}" method="POST" class="form-horizontal">
			    @csrf
				<div class="box-body">
					<div class="form-group">
						<label for="user_id" class="col-sm-3 control-label">User ID</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{$user->user_id}}</p>
						</div>
					</div>
					<div class="form-group required">
						<label for="first_name" class="col-sm-3 control-label">Nama Depan</label>
						<div class="col-sm-9">
							<input type="text" name="first_name" class="form-control" placeholder="Nama Depan" value="{{old('first_name', $user->first_name)}}">
						</div>
					</div>
					<div class="form-group">
						<label for="last_name" class="col-sm-3 control-label">Nama Belakang</label>
						<div class="col-sm-9">
							<input type="text" name="last_name" class="form-control" placeholder="Nama Belakang" value="{{old('last_name', $user->last_name)}}">
						</div>
					</div>
					<div class="form-group required">
						<label for="mobile_number" class="col-sm-3 control-label">Mobile Number</label>
						<div class="col-sm-9">
							<input type="text" name="mobile_number" class="form-control" placeholder="Mobile Number" value="{{old('mobile_number', $user->mobile_number)}}">
						</div>
					</div>
					@if($user->branch)
					<div class="form-group">
						<label for="branch_id" class="col-sm-3 control-label">Cabang</label>
						<div class="col-sm-9">
							<p class="form-control-static">{{$user->branch->name}}</p>
						</div>
					</div>
					@endif
					<div class="form-group required">
						<label for="work_position" class="col-sm-3 control-label">Posisi</label>
						<div class="col-sm-9">
							<input type="text" name="work_position" class="form-control" placeholder="Posisi" value="{{old('work_position', $user->work_position)}}">
						</div>
					</div>
					<div class="form-group required">
						<label for="email" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-9">
							<input type="text" name="email" class="form-control" placeholder="Email" value="{{old('email', $user->email)}}">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-9">
							<input type="password" name="password" class="form-control" placeholder="Password">
							<p class="help-block">Biarkan kosong jika tidak ingin mengganti password. Password terdiri dari minimal 8 karakter, maksimal 12. Terdiri dari minimal 1 huruf besar, 1 huruf kecil dan 1 angka. Tidak boleh mengandung karakter khusus.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="password_confirm" class="col-sm-3 control-label">Konfirmasi Password</label>
						<div class="col-sm-9">
							<input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
							<p class="help-block">Isi dengan password yang sama jika ingin merubah password</p>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
				<button type="submit" class="btn btn-info pull-right">Simpan</button>
				</div>
				<!-- /.box-footer -->
			</form>
		</div>
	</div>
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
	      	@if($user->projects->count()>0)
	      	<ul class="list-group">
	        @foreach($user->projects as $project)
	        	<li class="list-group-item">{{$project->project_id}} - {{$project->name}}</li>
	        @endforeach
	        </ul>
	        @endif
	      </div>
	      <!-- /.box-footer -->
	    </div>
    </div>
</div>
@stop