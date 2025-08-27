<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Tambah Storage ke Projek</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-6">
		<div class="box box-default">
			<div class="box-header with-border">
        		<form action="<?php echo e($action); ?>" method="POST" class="form-horizontal">
					<?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
					<?php echo e(method_field($method)); ?>

					<?php endif; ?>
					<?php echo csrf_field(); ?>
					<div class="box-body">
						<input type="hidden" value="<?php echo e($project->id); ?>" name="project_id" />
						<div class="form-group required">
							<label for="branch_id" class="col-sm-3 control-label">Cabang/Subcabang</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="party_id" required>
									<option value="" disabled selected>-- Pilih Cabang/Subcabang --</option>
									<?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e(old('party_id', $branch->id)); ?>"><?php echo e($branch->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
						</div>
						
						<div class="form-group required">
							<label for="company_id" class="col-sm-3 control-label">Warehouse</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="warehouse_id">
									<option value="" disabled>-- Pilih Warehouse --</option>
									<?php if(!empty(old('warehouse_id'))): ?>
										<?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e(old('warehouse_id', $warehouse->id)); ?>">
												<?php echo e($warehouse->name); ?>

											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label for="company_id" class="col-sm-3 control-label">Storage</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="storage_id">
									<option value="" disabled>-- Pilih Storage --</option>
									<?php if(!empty(old('storage_id'))): ?>
										<?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e(old('storage_id', $storage->id)); ?>">
												<?php echo e($storage->code); ?>

											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</select>
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
    </div>
</div>
<?php $__env->stopSection(); ?>
			
<?php $__env->startSection('js'); ?>
	<script>
		$(document).ready(function () {
			$('select[name="party_id"]').on('change', function(){
				var value = $(this).val();
	            $.ajax({
	                type: 'GET', //THIS NEEDS TO BE GET
	                url: '/ajax/get_warehouse_project/' + value,
	                dataType: 'json',
	                success: function (data) {
	                	$('select[name="warehouse_id"]').empty();
	                	$.each(data, function(key, value) {
	                        if(key == 0){
	                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
	                        }
	                        else{
	                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
	                        }
	                    });
	                	$('select[name="warehouse_id"]').trigger("change");

	                },
	                error:function(error){
	                    console.log('error ', error);
	                }

	           	});
			});

			var value = $('select[name="party_id"]').val();
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: '/ajax/get_warehouse_project/' + value,
                dataType: 'json',
                success: function (data) {
                	$('select[name="warehouse_id"]').empty();
                	$.each(data, function(key, value) {
                		if(key == 0){
                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'" selected>'+ value.name +'</option>');
                        }
                        else{
                        	$('select[name="warehouse_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        }
                        $('select[name="warehouse_id"]').trigger("change");
                    });
                },
                error:function(error){
                    console.log('error ', error);
                }

           	});

			$('select[name="warehouse_id"]').on('change', function(){
				var value = $(this).val();
	            $.ajax({
	                type: 'GET', //THIS NEEDS TO BE GET
	                url: '/ajax/get_storage_project/' + value,
	                dataType: 'json',
	                success: function (data) {
	                	$('select[name="storage_id"]').empty();
	                	$.each(data, function(key, value) {
	                        if(key == 0){
	                			$('select[name="storage_id"]').append('<option value="'+ value.id +'" selected>'+ value.code +'</option>');	
	                		}
	                		else{
	                			$('select[name="storage_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');	
	                		}
	                    });
	                },
	                error:function(error){
	                    console.log('error ', error);
	                }

	           	});
			});

			var value = $('select[name="warehouse_id"]').val();
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: '/ajax/get_storage_project/' + value,
                dataType: 'json',
                success: function (data) {
                	$('select[name="storage_id"]').empty();
                	$.each(data, function(key, value) {
                		if(key == 0){
                			$('select[name="storage_id"]').append('<option value="'+ value.id +'" selected>'+ value.code +'</option>');	
                		}
                		else{
                			$('select[name="storage_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');	
                		}
                        
                    });
                },
                error:function(error){
                    console.log('error ', error);
                }

           	});
		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>