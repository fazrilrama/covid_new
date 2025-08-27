<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Management Stock</b></th>
    </tr>
       <tr> 
        <th><b><?php echo e($project); ?></b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <?php if($date == null || $date == ''): ?>
        <th><b>s/d <?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></b></th>
        <?php else: ?> 
        <th><b><?php echo e($date); ?></b></th>
        <?php endif; ?>
        </tr>

    </thead>
</table>
<table>
    <thead>
    <tr>
        <th>SKU</th>
        <th>Nama SKU</th>
        <th>UoM</th>
        <th>Stock Awal</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Stock Akhir</th>
    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($user['sku']); ?></td>
            <td><?php echo e($user['sku_name']); ?></td>
            <td><?php echo e($user['uom_name']); ?></td>
            <td><?php echo e($user['begining']); ?></td>
            <td><?php echo e($user['after_begining_in']); ?></td>
            <td><?php echo e($user['after_begining_out']); ?></td>
            <td><?php echo e($user['stock']); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>