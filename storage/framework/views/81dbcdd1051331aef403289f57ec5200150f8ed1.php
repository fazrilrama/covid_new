<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center;">
        Laporan Stok Wina, Pelanggan, Fisik
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
            <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4">
                <table width="100%">
                    <tr>
                        <td width='45%'>Tanggal Filter</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($filter); ?></td>
                    </tr>
                </table>
            </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Project</th>
                    <th rowspan="2">SKU</th>
                    <th rowspan="2">Nama SKU </th>
                    <th rowspan="2">UoM</th>
                    <th rowspan="2">Stok Awal</th>
                    <th rowspan="2">Masuk</th>
                    <th rowspan="2">Keluar</th>
                    <th colspan="3">Stok Akhir</th>
                </tr>
                <tr>
                    <th>Wina</th>
                    <th>Pelanggan</th>
                    <th>Fisik </th>
                </tr>
                </thead>

                <tbody>

                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($detail['project']); ?></td>
                        <td><?php echo e($detail['sku']); ?></td>
                        <td><?php echo e($detail['sku_name']); ?></td>
                        <td><?php echo e($detail['uom_name']); ?></td>
                        <td><?php echo e($detail['begining']); ?></td>
                        <td><?php echo e($detail['after_begining_in']); ?></td>
                        <td><?php echo e($detail['after_begining_out']); ?></td>
                        <td><?php echo e($detail['stock']); ?></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                <tr>
                    <td align="center"><b>Kepala Gudang<b></td>
                    <td align="center"><b>SPV Warehouse</b></td>
                    <td align="center"><b>KA Divre</b></td>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 120px">
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">  
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">                        
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                        <td rowspan="5" style="vertical-align: bottom;text-align: center">
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        </td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>