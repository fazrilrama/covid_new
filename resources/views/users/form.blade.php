<div class="box-body">
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_id" class="col-sm-3 control-label">User ID</label>
			<div class="col-sm-9">
				@if($user->user_id)
				<p class="form-control-static">{{$user->user_id}}</p>
				<input type="hidden" id="user_id_edit" name="user_id_edit" value="{{ $user->id }}">
				@else
				<input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{old('user_id', $user->user_id)}}">
				@endif
				<p class="help-block">User ID tidak dapat dirubah setelah dibuat, terdiri dari minimal 8 karakter, maksimal 12. Tidak boleh mengandung karakter khusus.</p>
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
			<label for="mobile_number" class="col-sm-3 control-label">No.Handphone</label>
			<div class="col-sm-9">
				<input type="text" onkeypress="return isNumberKey(event)" name="mobile_number" class="form-control" placeholder="No. Handphone" value="{{old('mobile_number', $user->mobile_number)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="email" class="col-sm-3 control-label">Email</label>
			<div class="col-sm-9">
				<input type="email" name="email" class="form-control" placeholder="Email" value="{{old('email', $user->email)}}">
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="branch_id" class="col-sm-3 control-label">Cabang</label>
			<div class="col-sm-9">
				<select class="form-control" name="branch_id">
					<option value="">-- Pilih --</option>
					@foreach($branches as $branch)
					<option value="{{$branch->id}}" @if($user->branch_id == $branch->id){{'selected'}}@endif>{{$branch->name}}</option>
					@endforeach
				</select>
				<p class="help-block">Biarkan kosong jika berada di kantor pusat</p>
				<!-- <p class="help-block">Biarkan kosong jika berada di kantor pusat</p> -->
			</div>
		</div>
		<div class="form-group required">
			<label for="work_position" class="col-sm-3 control-label">Jabatan Pekerjaan</label>
			<div class="col-sm-9">
				<input type="text" name="work_position" class="form-control" placeholder="Jabatan Pekerjaan" value="{{old('work_position', $user->work_position)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="password" class="col-sm-3 control-label">Password</label>
			<div class="col-sm-9">
				<input type="password" name="password" class="form-control" placeholder="Password">
				<p class="help-block">Password terdiri dari minimal 8 karakter, maksimal 12. Terdiri dari minimal 1 huruf besar, 1 huruf kecil dan 1 angka. Tidak boleh mengandung karakter khusus.</p>
			</div>
		</div>
		<div class="form-group required">
			<label for="password_confirm" class="col-sm-3 control-label">Konfirmasi Password</label>
			<div class="col-sm-9">
				<input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
			</div>
		</div>
	</div>
</div>