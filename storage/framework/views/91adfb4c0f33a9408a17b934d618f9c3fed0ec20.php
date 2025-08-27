<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
	<ul class="progressbar">
	    <li>Buat <?php if($advanceNotice->type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?></li>
	    <li>Tambah Item</li>
	    <li class="active">Complete</li>
	</ul>
</div>
<h1><?php if($advanceNotice->type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?> - #<?php echo e($advanceNotice->code); ?>

    <?php if($advanceNotice->status == 'Completed'): ?>
        <?php if(Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor')): ?>
        <button data-toggle="modal" data-target="#modal-otp" class="btn btn-sm btn-primary pull-right">
        	<i class="fa fa-check"></i> Close
		</button>
        <?php endif; ?>
        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
        	<?php if($advanceNotice->employee_name == ''): ?>
		        <button data-toggle="modal" data-target="#modal-assignto" class="btn btn-sm btn-primary pull-right">
		        	<i class="fa fa-plus"></i> Assign To
		        </button>
		    <?php else: ?>
			    <a href="JavaScript:poptastic('<?php echo e(route('stock_transfer_order.print_sptb', ['advance_notice' => $advanceNotice->id])); ?>')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
			      <i class="fa fa-download"></i> Print <?php echo e($advanceNotice->type == 'inbound' ? 'SPBM' : 'SPBK'); ?>

			  	</a>
		    <?php endif; ?>
			
        <?php endif; ?>
    <?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-header with-border">
	    		<h3 class="box-title">Informasi Data</h3>
		        <div class="box-tools pull-right">
		          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		          </button>
		        </div>
		        <!-- /.box-tools -->
      		</div>

      		<form  action="#" method="POST" class="form-horizontal" >
				<?php echo csrf_field(); ?>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label">Project</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->project->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label">Tanggal Dibuat</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->created_at); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">No. Kontrak</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->contract_number); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">No. SPK</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->spmp_number); ?></p>
								</div>
							</div>
							
								
								
									
								
							
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">Jenis Kegiatan</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->advance_notice_activity->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">Moda Transportasi</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->transport_type->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Customer Reference</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->ref_code); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										<?php if(!Auth::user()->hasRole('CargoOwner')): ?>
										<?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status); ?>

										<?php else: ?>
										<?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status)); ?>

										<?php endif; ?>
									</p>
								</div>
							</div>
							<div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">Pembuat</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(@$advanceNotice->user->user_id); ?></p>
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">No. SPTB</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(@$advanceNotice->sptb_num); ?></p>
								</div>
							</div> -->
							<div class="form-group">
								<label for="user_id" class="col-sm-3 control-label">No. SPPK</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(@$advanceNotice->sppk_num); ?> (<a href="<?php echo e(\Storage::disk('public')->url($advanceNotice->sppk_doc)); ?>"  target="_blank">Lihat Dokumen</a>)</p>
								</div>
							</div>							
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="origin" class="col-sm-3 control-label">Origin</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->origin->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="etd" class="col-sm-3 control-label">Est. Time Delivery</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->etd); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_id" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> Shipper <?php else: ?> Cabang/Subcabang <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_address" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> Shipper <?php else: ?> Cabang/Subcabang <?php endif; ?> Address
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="destination" class="col-sm-3 control-label">Destination</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->destination->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="eta" class="col-sm-3 control-label">Est. Time Arrival</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->eta); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_id" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_address" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?> Address
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Warehouse Supervisor</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->employee_name); ?></p>
								</div>
							</div>
							<?php if($advanceNotice->warehouse): ?>
								<div class="form-group">
									<label for="ref_code" class="col-sm-3 control-label">Warehouse</label>
									<div class="col-sm-9">
										<p class="form-control-static"><?php echo e($advanceNotice->warehouse->name); ?></p>
									</div>
								</div>
							<?php endif; ?>
							<!-- <div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Annotation</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->annotation ?? '-'); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Contractor</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->contractor ?? '-'); ?></p>
								</div>
							</div> -->
							<!-- <div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label">Head Cabang/Subcabang</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->head_ds); ?></p>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-header with-border">
	    		<h3 class="box-title">Informasi Barang</h3>
		        <div class="box-tools pull-right">
		          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		          </button>
		        </div>
		        <!-- /.box-tools -->
      		</div>
			<div class="box-body">
				<div class="table-responsive">
				  <table class="data-table table table-bordered table-hover no-margin item-transaction-table" width="100%">

				      <thead>
				          <tr>
							<th>ID:</th>
				            <th>Item SKU:</th>
				            <th>Item Name:</th>
				            <th>Group Reference:</th>
				            <th>Qty:</th>
				            <th>UOM:</th>
				            <th>Weight:</th>
				            <th>Volume:</th>
				          </tr>
				      </thead>

				      <tbody>
				      <?php $__currentLoopData = $advanceNotice->details->where('status', '<>', 'canceled'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				          <tr>
							<td><?php echo e($detail->id); ?></td>
							<td><?php echo e($detail->item->sku); ?></td>
							<td><?php echo e($detail->item->name); ?></td>
							<td><?php echo e($detail->ref_code); ?></td>
							<td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
							<td><?php echo e($detail->uom->name); ?></td>
							<td><?php echo e(number_format($detail->weight, 2, ',', '.')); ?></td>
							<td><?php echo e(number_format($detail->volume, 2, ',', '.')); ?></td>
				          </tr>
				      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				      </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo $__env->make('otp', ['url' => route('stock_transfer_order.closed', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('assignto', ['url' => route('stock_transfer_order.assignto', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>