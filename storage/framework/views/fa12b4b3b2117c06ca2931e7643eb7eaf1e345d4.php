<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Estimasi jadwal pengiriman barang</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <a href="<?php echo e(url('/report/estimated_delivery_notes/print')); ?>" class="btn btn-sm btn-primary" onclick="cetak()">Cetak</a>
			
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th>No:</th>
	                <th>ETD:</th>
	                <th>DN#:</th>
	                <th>SHIPPER:</th>
	                <th>CONSIGNEE:</th>
	                <th>ASAL:</th>
	                <th>TUJUAN:</th>
	                <th>Kg:</th>
	                <th>M3:</th>
	                <th>Pcs:</th>
	                <th>Created At:</th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($key+1); ?></td>
	                <td><?php echo e($item->etd); ?></td>
	                <td><?php echo e($item->code); ?></td>
	                <td><?php echo e(empty($item->shipper) ?'': $item->shipper->name); ?></td>
	                <td><?php echo e(empty($item->consignee) ?'': $item->consignee->name); ?></td>
	                <td><?php echo e(empty($item->origin) ?'': $item->origin->name); ?></td>
	                <td><?php echo e(empty($item->destination) ?'': $item->destination->name); ?></td>
	                <td><?php echo e($item->qty); ?></td>
	                <td><?php echo e($item->weight); ?></td>
	                <td><?php echo e($item->volume); ?></td>
	                <td><?php echo e($item->created_at); ?></td>
	            </tr>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	      </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>