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
			        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			            <tr>
			                <td>
			                	<input type="checkbox" name="roles[]" value="<?php echo e($role->id); ?>" <?php if($user->roles->contains($role->id)): ?><?php echo e('checked'); ?><?php endif; ?>>
			                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($role->name); ?>

			                </td>
			            </tr>
			        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                          <input type="checkbox" name="companies[]" value="<?php echo e($company->id); ?>" <?php if($user->companies->contains($company->id)): ?><?php echo e('checked'); ?><?php endif; ?>>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($company->name); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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