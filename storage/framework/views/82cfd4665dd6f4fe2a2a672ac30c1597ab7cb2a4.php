<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Gudang List
		<form action="<?php echo e(route('warehouses.index')); ?>" method="GET">
			<?php if(!Auth::user()->hasRole('AdminBulog')): ?>
			<a href="<?php echo e(url('warehouses/create')); ?>" type="button" class="btn btn-success" title="Create">
				<i class="fa fa-plus"></i> Tambah
			</a>
			<?php endif; ?>
			<?php if(Auth::user()->hasRole('Superadmin')): ?>
			<button class="btn btn-sm btn-warning" type="submit" name="submit" value="1">
				<i class="fa fa-download"></i> Export ke Excel
			</button>
			<?php endif; ?>
		</form>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    
	    <table class="data-table table table-bordered table-hover no-margin" id="example" width="100%">
	    
	        <thead>
	            <tr>
	                <th>Code:</th>
	                <th>Name:</th>
	                <th>Region:</th>
	                <th>Branch:</th>
	                <th>Total Luas Terpasang:</th>
	                <th>Total Volume:</th>
	                <th>Total Kapasitas(kg) Terpasang:</th>
	                <th>Total Luas Terpakai(m<sup>2</sup>):</th>
	                <th>Utilitas Gudang (Luas):</th>
					<th>Gudang</th>
					<th class="no-sort">
					Status
					<select class="select" name="status" id="select">
						<option value=" ">Semua</option>
						<option value=".Aktif">Aktif</option>
						<option value="Tidak Aktif">Tidak Aktif</option>
					</select>
					</th>
					<th>Operasi:</th>
					<th>Type:</th>
	                <th>Action:</th>
	            </tr>
	            <tbody>
			        <?php $__currentLoopData = $warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			            <tr>
			            	<td><?php echo e($item->code); ?></td>
			            	<td><?php echo e($item->name); ?></td>
			            	<td>
			            		<?php if($item->region): ?>
			            			<?php echo e($item->region->name); ?></td>
			            		<?php endif; ?>
			            	<td>
			            		<?php if($item->branch): ?>
			            			<?php echo e($item->branch->name); ?>

			            		<?php endif; ?>
			            	</td>
			            	<td><?php echo e(number_format($item->length*$item->width, 2, ',', '.')); ?></td>
			            	<td><?php echo e(number_format($item->total_volume, 2, ',', '.')); ?></td>
			            	<td><?php echo e(number_format($item->total_weight, 2, ',', '.')); ?></td>
			            	<td>
			            		<?php
			            			$contracts = $item->contracts();
				                    $total_rented_space = 0;
				                    foreach($contracts->where('is_active', 1)->get() as $contract) {
				                        $total_rented_space += $contract->pivot->rented_space;   
				                    }
				                    
			            		?>
			            		<?php echo e(number_format($total_rented_space, 2, ',', '.')); ?>

			            	</td>
			            	<td>
			            		<?php
			            			$contracts = $item->contracts();

				                    $total_rented_space = 0;
				                    $utility_space = 0;
				                    $total_space = $item->length * $item->width;
				                    foreach($contracts->where('is_active', 1)->get() as $contract) {
				                        $total_rented_space += $contract->pivot->rented_space;   
				                    }
				                    if (!empty($total_space)) {
				                        $utility_space = ($total_rented_space > 0) ? round(($total_rented_space / $total_space)*100,2) : 0 ;
				                    }else {
				                        $utility_space = 0 ;
				                    }

			            		?>
			            		<?php echo e(number_format($utility_space, 2, ',', '.')); ?>%
			            	</td>
							<td><?php echo e(strtoupper($item->ownership)); ?></td>
							<td>
							<?php echo e($item->is_active == 1 ? '.Aktif' : 'Tidak Aktif'); ?>

							</td>
							<td><?php echo e($item->status ?? 'Belum Memilih'); ?></td>
							<td>
								<?php if($item->type_warehouse): ?>
									<span class="badge bg-secondary"><?php echo e($item->type_warehouse); ?></span>
								<?php else: ?>
									<span class="badge bg-secondary">-</span>
								<?php endif; ?>
							</td>
			            	<td>
			            		<div class="btn-toolbar">
			            			<div class="btn-group" role="group">
										<?php if(auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('WarehouseManager')): ?>
										<a href="<?php echo e(route('to_add_user', $item->id)); ?>" type="button" class="btn btn-warning">
											<i class="fa fa-user"></i>
										</a>
										<?php endif; ?>
										<a href="<?php echo e(route('warehouses.edit', $item->id)); ?>" type="button" class="btn btn-primary">
											<i class="fa fa-pencil"></i>
										</a>

										
										<?php if(auth()->user()->hasPermission('delete-WarehousesList')): ?>
										<form action="<?php echo e(route('warehouses.destroy', $item->id)); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
											<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
										</form>
										<?php endif; ?>
									</div>
			                    </div>
			            	</td>
			            </tr>
			        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			    </tbody>
	        </thead>
	    </table>
	</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
	<script>
	var table = $('#example').DataTable({
			"columnDefs": [ {
			"targets": 'no-sort',
			"orderable": true,
		} ]
	});

	$('#select').on('change', function(){
		table.column(10).search(this.value).draw();   
		$('.input-sm').val('');
	});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>