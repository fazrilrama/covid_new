<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="id" class="col-sm-3 control-label">Id</label>
			<div class="col-sm-9">
				<input type="text" name="id" class="form-control" placeholder="Id" value="{{old('id', $region->id)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Code</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Code" value="{{old('code', $region->code)}}" required>
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $region->name)}}" required>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>