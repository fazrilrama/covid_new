<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Tally Sheet #<?php echo e($stockTransport->code); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                                <th>ID:</th>
                                <th>Item SKU:</th>
                                <th>Qty:</th>
                                <th>UOM:</th>
                                <th>Weight:</th>
                                <th>Volume:</th>
                                <th>Control Date:</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detail->id); ?></td>
                                    <td><?php echo e($detail->item->sku); ?></td>
                                    <td><?php echo e($detail->qty); ?></td>
                                    <td><?php echo e($detail->uom->name); ?></td>
                                    <td><?php echo e($detail->weight); ?></td>
                                    <td><?php echo e($detail->volume); ?></td>
                                    <td><?php echo e($detail->control_date); ?></td>
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