<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center;">
        Laporan On Location Stock
    </h3>
    <div class="row">
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <table width="100%">
                    <tr>
                        <td width='25%'>Nama Branch</td>
                        <td width='5%'>:</td>
                        <td width='70%'><?php echo e($warehouse->branch->name); ?></td>
                    </tr>
                    <tr>
                        <td>Nama Gudang</td>
                        <td>:</td>
                        <td><?php echo e($warehouse->name); ?></td>
                    </tr>
                </table>
            </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">SKU</th>
                    <th rowspan="2">Nama SKU </th>
                    <th rowspan="2">UoM</th>
                    <th rowspan="2">Storage</th>
                    <th rowspan="2">Stok Akhir</th>
                </tr>
                </thead>

                <tbody>

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($detail['sku']); ?></td>
                        <td><?php echo e($detail['sku_name']); ?></td>
                        <td><?php echo e($detail['uom_name']); ?></td>
                        <td><?php echo e($detail['storages']); ?></td>
                        <td><?php echo e($detail['stock']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>