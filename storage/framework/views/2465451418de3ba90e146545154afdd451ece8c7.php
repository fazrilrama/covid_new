<div class="box-body">
	<div class="form-group required">
		<label for="name" class="col-sm-3 control-label">Nama</label>
		<div class="col-sm-9">
			<input type="text" name="name" class="form-control" placeholder="Nama" value="<?php echo e(old('name', $role->name)); ?>">
		</div>
	</div>
</div>
<!-- /.box-body -->

<!-- di comment untuk menghilangkan double button simpan -->
<!-- <div class="box-footer">
<button type="submit" class="btn btn-info pull-right">Simpan</button>
</div> -->
<!-- /.box-footer -->