

<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Turn Over Stock</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('report.searchDateForm', ['print_this' => true], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    

    <?php if($search): ?>
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Branch:</th>
                    <th>Nama Gudang:</th>
                    <th>Kode Gudang:</th>
                    <th>Status:</th>
                    <th>QTY IN:</th>
                    <th>QTY OUT:</th>
                    <th>TURNOVER:</th>

                </tr>
            </thead>
        
            <tbody> 
                <?php $__currentLoopData = $stock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($collection->branch); ?></td>
                        <td><?php echo e($collection->name); ?></td>
                        <td><?php echo e($collection->code); ?></td>
                        <td><?php echo e(strtoupper($collection->ownership)); ?></td>
                        <td><?php echo e(number_format($collection->qty_inbound, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($collection->qty_outbound, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($collection->qty_inbound + $collection->qty_outbound, 2, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page_dua', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>