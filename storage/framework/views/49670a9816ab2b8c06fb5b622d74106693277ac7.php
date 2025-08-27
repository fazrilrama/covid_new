<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
	<ul class="progressbar">
	    <li>Buat <?php if($advanceNotice->type=='inbound'): ?> AIN <?php else: ?> AON <?php endif; ?></li>
	    <li><?php echo e(__('lang.create')); ?> Item</li>
	    <?php if(Auth::user()->hasRole('WarehouseManager') && !$advanceNotice->employee_name): ?>
            <li class="active"><?php echo e(__('lag.assign_suppervisor_warehouse')); ?></li>
            <li>Complete</li>
        <?php elseif(Auth::user()->hasRole('WarehouseManager') && $advanceNotice->employee_name): ?>
            <li><?php echo e(__('lag.assign_suppervisor_warehouse')); ?></li>
            <li class="active">Complete</li>
        <?php endif; ?>

        <?php if(!Auth::user()->hasRole('WarehouseManager')): ?>
            <li class="active">Complete</li>
        <?php endif; ?>

	</ul>
</div>
<h1>
	<?php if($advanceNotice->type=='inbound'): ?>
		AIN <?php else: ?> AON 
	<?php endif; ?> - #<?php echo e($advanceNotice->code); ?>

    <?php if($advanceNotice->status == 'Completed'): ?>
        <?php if(Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor')): ?>
        <button data-toggle="modal" data-target="#modal-otp" class="btn btn-sm btn-primary pull-right">
        	<i class="fa fa-check"></i> Close
		</button>
		<a href="JavaScript:poptastic('<?php echo e(route('advance_notices.print_unloading', ['advance_notice' => $advanceNotice->id])); ?>')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px">
			<i class="fa fa-download"></i> <?php echo e(__('lang.print_accepted_receipt')); ?>

		</a>
		<?php endif; ?>
        <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
        	<?php if($advanceNotice->is_accepted == 1): ?>
	        	<?php if($advanceNotice->employee_name == ''): ?>
			        <button data-toggle="modal" data-target="#modal-assignto" class="btn btn-sm btn-primary pull-right">
			        	<i class="fa fa-plus"></i> <?php echo e(__('lang.assign_to')); ?>

			        </button>
			    <?php else: ?>
				    <a href="JavaScript:poptastic('<?php echo e(route('advance_notices.print_sptb', ['advance_notice' => $advanceNotice->id])); ?>')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px">
				      <i class="fa fa-download"></i> Print <?php echo e($advanceNotice->type == 'inbound' ? 'SPBM' : 'SPBK'); ?>

				  	</a>
			    <?php endif; ?>
			<?php else: ?>
				<button data-toggle="modal" data-target="#modal-an-validation" class="btn btn-sm btn-danger pull-right" style="margin-right: 10px">
		        	<i class="fa fa-check"></i> Validasi
		        </button>
			<?php endif; ?>

		    
        <?php endif; ?>
    <?php endif; ?>
	<?php if((Auth::user()->hasRole('Admin-BGR') || Auth::user()->hasRole('WarehouseSupervisor')) && $advanceNotice->status == 'Closed'): ?>
		<a href="JavaScript:poptastic('<?php echo e(route('advance_notices.print_ba', ['advance_notice' => $advanceNotice->id])); ?>')" class="btn btn-info"><i class="fa fa-print"></i> BA Print</a>
	<?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-header with-border">
	    		<h3 class="box-title"><?php echo e(__('lang.information_data')); ?></h3>
		        <div class="box-tools pull-right">
		          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		          </button>
		        </div>
		        <!-- /.box-tools -->
      		</div>
			<?php if($advanceNotice->status == 'Processed' && $advanceNotice->accepted_note != null && $advanceNotice->is_accepted == 2): ?>
			<div class="alert alert-success">
					<label for="" style="color: red"><?php echo e($advanceNotice->accepted_note); ?></label>
			</div>
			<?php endif; ?>

      		<form  action="#" method="POST" class="form-horizontal" >
				<?php echo csrf_field(); ?>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label"><?php echo e(__('lang.company')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->project->company->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="project_id" class="col-sm-3 control-label"><?php echo e(__('lang.project')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->project->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label"><?php echo e(__('lang.created_at')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->created_at); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.contract')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->contract_number); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.spk')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->spmp_number); ?></p>
								</div>
							</div>
							
								
								
									
								
							
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> 
					            		PO <?php echo e(__('lang.document')); ?>

					            	<?php else: ?>
					            		DO <?php echo e(__('lang.document')); ?>

					            	<?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static">
										<?php if(!empty($advanceNotice->an_doc)): ?>
						                	<a href="<?php echo e(\Storage::disk('public')->url($advanceNotice->an_doc)); ?>" target="_blank"><?php echo e(__('lang.see_doc')); ?></a>
						                <?php endif; ?>
									</p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label"><?php echo e(__('lang.activity')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->advance_notice_activity->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="transport_type_id" class="col-sm-3 control-label"><?php echo e(__('lang.type_transpotation')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->transport_type->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->ref_code); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="owner" class="col-sm-3 control-label"><?php echo e(__('lang.ownership')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->owner) ? '' : $advanceNotice->ownership->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="owner" class="col-sm-3 control-label"><?php echo e(__('lang.pic')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->pic); ?></p>
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
								<label for="user_id" class="col-sm-3 control-label"><?php echo e(__('lang.created_by')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(@$advanceNotice->user->user_id); ?></p>
								</div>
							</div>					
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->origin->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="etd" class="col-sm-3 control-label"><?php echo e(__('lang.etd')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->etd); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_id" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> <?php echo e(__('lang.shipper')); ?> <?php else: ?> <?php echo e(__('lang.branch')); ?> <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="shipper_address" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> <?php echo e(__('lang.shipper_address')); ?> <?php else: ?> <?php echo e(__('lang.branch_address')); ?> <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->destination->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="eta" class="col-sm-3 control-label"><?php echo e(__('lang.eta')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->eta); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_id" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> <?php echo e(__('lang.branch')); ?><?php else: ?> <?php echo e(__('lang.consignee')); ?> <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="consignee_address" class="col-sm-3 control-label">
									<?php if($advanceNotice->type == 'inbound'): ?> <?php echo e(__('lang.branch_address')); ?> <?php else: ?> <?php echo e(__('lang.consignee_address')); ?> <?php endif; ?>
								</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_supervisor')); ?></label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($advanceNotice->employee_name); ?></p>
								</div>
							</div>
							<?php if($advanceNotice->warehouse): ?>
								<div class="form-group">
									<label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse')); ?></label>
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
				  <table class="data-table table table-bordered table-hover no-margin" width="100%">

				      <thead>
				          <tr>
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

<?php echo $__env->make('otp', ['annotation' => 'yes',  'url' => route('advance_notices.closed', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('an_validation', ['advanceNotice' => $advanceNotice, 'url' => route('advance_notices.validation', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php if($advanceNotice->is_sto == 1): ?>
	<?php echo $__env->make('assignto', ['url' => route('stock_transfer_order.assignto', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php else: ?>
	<?php echo $__env->make('assignto', ['advanceNotice' => $advanceNotice, 'url' => route('advance_notices.assignto', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>