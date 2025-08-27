<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>GOOD ISSUE REPORT</b></th>
    </tr>
       <tr> 
        <th><b><?php echo e($project->name); ?></b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th><b><?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></b></th>
        </tr>

    </thead>
</table>
<table>
    <thead>
    <tr>
        <th>SKU:</th>
        <th>Item:</th>
        <th>UoM:</th>
        <th>Outstanding Delivery Kemarin:</th>
        <th>Pengiriman Dibuat Hari Ini:</th>
        <th>Total In Delivery:</th>
        <th>Delivered:</th>
        <th>Outstanding:</th>
        <th>Akumulasi Terkirim:</th>
        <th>Total All Delivery:</th>


    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($collection['begining'] > 0 || $collection['receiving'] > 0 || $collection['delivery'] > 0 ): ?>
        <tr>
            <td><?php echo e($collection['sku']); ?></td>
            <td><?php echo e($collection['item']); ?></td>
            <td><?php echo e($collection['uom_name']); ?></td>
            <td><?php echo e(number_format($collection['begining'], 2, ',', '.')); ?></td>
            <td><?php echo e(number_format($collection['receiving'], 2, ',', '.')); ?></td>
            <td><?php echo e(number_format($collection['begining'] + $collection['receiving'], 2, ',', '.')); ?></td>

            <td><?php echo e(number_format($collection['delivery'], 2, ',', '.')); ?></td>
            <td><?php echo e(number_format($collection['begining'] + $collection['receiving'] - $collection['delivery'], 2, ',', '.')); ?></td>
            <td><?php echo e(number_format($collection['akumulasi'], 2, ',', '.')); ?></td>
            <td><?php echo e(number_format($collection['akumulasi'] + ($collection['begining'] + $collection['receiving'] - $collection['delivery']), 2, ',', '.')); ?></td>


        </tr>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>