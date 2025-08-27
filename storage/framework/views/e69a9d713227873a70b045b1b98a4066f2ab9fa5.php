<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Contract List
    	<a href="<?php echo e(url('contracts/create')); ?>" type="button" class="btn btn-success" title="Create">
			<i class="fa fa-plus"></i> Tambah
		</a>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="row">
		<div class="col-md-4 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Kontrak Belum Berakhir</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="aktif">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Kontrak Berakhir 1 Bulan Kedepan</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="end_of_month">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Status Aktif dan Kontrak Habis</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="aktif_mati">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive">
	    <table id="contract-list" class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>No. Contract:</th>
	                <th>Project:</th>
	                <th>Start Date:</th>
	                <th>End Date:</th>
	                <th>Space (m2):</th>
	                <th>Status:</th>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
		        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		            <tr>
		                <td><?php echo e($item->number_contract); ?></td>
		                <td><?php echo e(@$item->project->name); ?></td>
		                <td><?php echo e($item->start_date); ?></td>
		                <td><?php echo e($item->end_date); ?></td>
		                <td><?php echo e($item->space_allocated); ?></td>
		                <td><?php echo e(($item->is_active == 1) ? '.Aktif' : 'Tidak Aktif'); ?></td>
		                <td>
		                	<div class="btn-toolbar">
								<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('update-ContractList')): ?> 
									<div class="btn-group" role="group">
										<a href="<?php echo e(url('contracts/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
											<i class="fa fa-pencil"></i>
										</a>
									</div>
								<?php endif; ?>
								<?php if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('delete-ContractList')): ?>
									<div class="btn-group" role="group">
										<form action="<?php echo e(url('contracts', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
											<button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
										</form>
									</div>
								<?php endif; ?>
		                    </div>
		                </td>
		            </tr>
		        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
	<script>
	var table = $('#contract-list').DataTable();
	var now = moment(new Date()); //todays date
	// var filteredData = table
	// 	.column(3, {search: 'applied'})
	// 	.data()
	// 	.filter( function ( value, index ) {
	// 		// var end = moment(value); // another date
	// 		var duration =  now.diff(value, 'days')
	// 		console.log(value,duration, duration <= 0  ? true : false)
	// 		return duration <= 0  ? true : false;
	// 	});
	
	// var filteredDataAktif = table
	// 	.column(3, {search: 'applied'})
	// 	.data()
	// 	.filter( function ( value, index ) {
	// 		// var end = moment(value); // another date
	// 		var duration =  now.diff(value, 'days')
	// 		// console.log(duration)
	// 		return duration <= 30 && duration >= 0  ? true : false;
	// 	});
	

	
	// table.column(5).data().filter(function(value,index){return value === 'Aktif'? true : false}).length
	count = 0;
	count_aktif_kurang =0;
	count_aktif_bulan = 0;
	var filterAllDataAktifLebih = table.rows().data().each(function(val, i) {
		// console.log(val[5], val[5] == '.Aktif');
		if (val[5] == '.Aktif' && val[3] < now.format("YYYY-MM-DD")){
			count++;
		};
		if (val[5] == '.Aktif' && now.diff(val[3], 'days') <= 0){
			count_aktif_kurang++;
		};
		if (val[5] == '.Aktif' && now.diff(val[3], 'days') <= 0 && now.diff(val[3], 'days') >= -30){
			count_aktif_bulan++;
		};
	})
	$('#aktif_mati').text(count);
	$('#end_of_month').text(count_aktif_bulan);
	$('#aktif').text(count_aktif_kurang);
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>