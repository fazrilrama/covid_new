<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1><?php if($type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?> List
    <?php if(Auth::user()->hasRole('CargoOwner')): ?>
        <a href="<?php echo e(url('stock_transfer_order/create/'.$type)); ?>" type="button" class="btn btn-success" title="Create">
            <i class="fa fa-plus"></i> Tambah
        </a>
    <?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
	                <th><?php if($type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?>#:</th>
                    <th>Storage Area:</th>
	                <th>Origin:</th>
                    <th>Destination:</th>
	                <th>ETD:</th>
	                <th>ETA:</th>
	                <th>Outstanding:</th>
                    <th>Arrived</th>
	                <th>Status:</th>
                    <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
	                <th>Receiving Status:</th>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                    <th>Assign To:</th>
                    <?php endif; ?>
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	            <tr>
	                <td><?php echo e($item->code); ?></td>
                    <td>
                        <?php if($type=='inbound'): ?>
                            <?php echo e((isset($item->consignee->name)) ? $item->consignee->name : '-'); ?>

                        <?php else: ?>
                            <?php echo e((isset($item->shipper->name)) ? $item->shipper->name : '-'); ?>

                        <?php endif; ?>
                    </td>
	                <td><?php echo e(!empty($item->origin->name) ? $item->origin->name : ''); ?></td>
	                <td><?php echo e(!empty($item->destination->name) ? $item->destination->name : ''); ?></td>
	                <td><?php echo e($item->etd); ?></td>
	                <td><?php echo e($item->eta); ?></td>
	                <td align="center"><?php echo e(number_format($item->outstanding, 2, ',', '.')); ?></td>
                    <td align="center">
                        <?php if($item->is_arrived == 0): ?>
                            No 
                        <?php else: ?>
                            Yes
                        <?php endif; ?>
                    </td>
	                <td><?php if(Auth::user()->hasRole('CargoOwner')): ?>
                        <?php echo e(($item->status == 'Pending' ? 'Planning' : ($item->status == 'Completed' ? 'Submitted' : $item->status))); ?>

                        <?php else: ?>
                        <?php echo e(($item->status == 'Pending' ? 'Planning' : $item->status )); ?>

                        <?php endif; ?>
                    </td>
                    <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
	                <td width="15%"><?php echo e(($item->outstanding == 0  && ($item->status == 'Completed' || $item->status == 'Closed') ? 'Full' : 'Partial')); ?></td>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasRole('WarehouseManager')): ?>
                    <td><?php echo e($item->employee_name); ?></td>
                    <?php endif; ?>
	                <td>
	                	<div class="btn-toolbar">
	                		<?php if(Auth::user()->id == $item->user_id): ?>
                                <?php if($item->editable): ?>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(url('stock_transfer_order/'.$item->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <form action="<?php echo e(url('stock_transfer_order', [$item->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                        </form>
                                    </div>
                                    <?php else: ?>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(url('stock_transfer_order/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(url('stock_transfer_order/'.$item->id.'/show')); ?>" type="button" class="btn btn-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
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
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>