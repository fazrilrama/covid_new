<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
    <div class="box-body">
		<div class="form-group required">
			<label for="code" class="col-sm-3 control-label">Kode Komoditas</label>
			<div class="col-sm-9">
				<input type="text" name="code" class="form-control" placeholder="Kode Komoditas" value="{{old('code', $commodity->code)}}">
			</div>
		</div>
	</div>
	<div class="box-body">
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $commodity->name)}}">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>