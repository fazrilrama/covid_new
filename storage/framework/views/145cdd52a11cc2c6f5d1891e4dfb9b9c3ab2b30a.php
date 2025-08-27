<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>
    Daftar Storage
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
                <tr>
                    <th>Storage ID:</th>
                    <th>Warehouse ID:</th>
                    <th>Warehouse:</th>
                    <th>Status </th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
	        
	        <tbody>
                <?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->code); ?></td>
                        <td><?php echo e(empty($item->warehouse) ?'': $item->warehouse->code); ?></td>
                        <td><?php echo e(empty($item->warehouse) ?'': $item->warehouse->name); ?></td>
                        <td>
                            <?php if($item->status == 1): ?>
                                Buka
                            <?php elseif($item->status == 0): ?>
                                Tutup
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->status == 1 && $type == 'inbound'): ?>
                                <div class="btn-toolbar">
                                    <div class="btn-group" role="group">
                                        <form action="<?php echo e(route('change_storage_status')); ?>" method="POST" onclick="return confirm('Anda yakin ingin menutup storage ini?');">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                            <input type="hidden" name="storage_id" value="<?php echo e($item->id); ?>">
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="type" value="inbound">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-undo"></i></a></button>
                                        </form>
                                    </div>  
                                </div>
                            <?php elseif($item->status == 0 && $type == 'outbound'): ?>
                                <div class="btn-toolbar">
                                    <div class="btn-group" role="group">
                                        <form action="<?php echo e(route('change_storage_status')); ?>" method="POST" onclick="return confirm('Anda yakin ingin membuka storage ini?');">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                            <input type="hidden" name="storage_id" value="<?php echo e($item->id); ?>">
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="type" value="outbound">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-undo"></i></a></button>
                                        </form>
                                    </div>  
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
	    </table>
	</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>