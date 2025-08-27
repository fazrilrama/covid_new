<div class="content-loader">

<table>
    <thead>
    
    <tr>
        <th><b>Laporan Stock Per Lokasi</b></th>
    </tr>
       <tr> 
        <th><b><?php echo e($project); ?></b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th><?php echo e($warehouse ? $warehouse->name : ''); ?></th>
        </tr>
        <tr>
        <?php if($date == null || $date == ''): ?>
        <th><b>Sampai dengan: <?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></b></th>
        <?php endif; ?>
        </tr>

    </thead>
</table>
<table border="1" width="100%">
    <thead>
        <tr>
            <th >Storage</th>
            <th >SKU:</th>
            <th >Nama SKU:</th>
            <th >Stock</th>
            <th >UoM:</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
        <tr>
            <td><?php echo e($d['storages']); ?></td>
            <td><?php echo e($d['sku']); ?></td>
            <td><?php echo e($d['sku_name']); ?></td>
            <td><?php echo e($d['stock']); ?></td>
            <td><?php echo e($d['uom_name']); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>