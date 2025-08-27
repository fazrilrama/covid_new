<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
	<ul class="progressbar">
	    <li>Buat Internal Movement</li>
	    <li>Tambah Item</li>
	    <li class="active">Complete</li>
	</ul>
</div>
<h1>Internal Movemet - #<?php echo e($stock_internal_movement->code); ?>

    
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
								<label for="code_reference" class="col-sm-3 control-label">Referensi</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($stock_internal_movement->code_reference); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="v" class="col-sm-3 control-label">Alasan</label>
								<div class="col-sm-9">
									<p class="form-control-static"><?php echo e($stock_internal_movement->note); ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="v" class="col-sm-3 control-label">Dokumen</label>
								<div class="col-sm-9">
									<?php if(!empty($stock_internal_movement->document)): ?>
										<a href="<?php echo e(\Storage::disk('public')->url($stock_internal_movement->document)); ?>" target="_blank">See Doc</a>
									<?php endif; ?>
								</div>
							</div>
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
							<th>Group Ref:</th>
							<th>Origin Storage:</th>
							<th>Destination Storage:</th>
							<th>Qty:</th>
							<th>UOM:</th>
				          </tr>
				      </thead>

				      <tbody>
				      <?php $__currentLoopData = $stock_internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
							<td><?php echo e($detail->id); ?></td>
							<td><?php echo e($detail->item->sku); ?></td>
							<td><?php echo e($detail->item->name); ?></td>
							<td><?php echo e($detail->ref_code); ?></td>
							<td><?php echo e($detail->origin_storage->code); ?></td>
							<td><?php echo e($detail->dest_storage->code); ?></td>
							<td><?php echo e($detail->movement_qty); ?></td>
							<td><?php echo e($detail->uom->name); ?></td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				      </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>