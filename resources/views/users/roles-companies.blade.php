<div class="col-md-6">
	<div class="box box-default">
		<div class="box-header with-border">
          <h3 class="box-title">Role User</h3>

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
			        @foreach($roles as $role)
			            <tr>
			                <td>
			                	<input type="checkbox" name="roles[]" value="{{$role->id}}" @if($user->roles->contains($role->id)){{'checked'}}@endif>
			                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$role->name}}
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

	<div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Akses Perusahaan</h3>

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
                @foreach($companies as $company)
                    <tr>
                        <td>
                          <input type="checkbox" name="companies[]" value="{{$company->id}}" @if($user->companies->contains($company->id)){{'checked'}}@endif>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$company->name}}
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