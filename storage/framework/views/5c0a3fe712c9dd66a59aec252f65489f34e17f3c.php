<div class="content-loader">

<table>
    <thead>
    
    <tr>
        <th><b>Report Mutasi Good Issue</b></th>
    </tr>
       <tr> 
        <th><b><?php echo e($project->name); ?></b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <?php if($tanggal_filter == null || $tanggal_filter == ''): ?>
        <th><b>s/d <?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></b></th>
        <?php else: ?> 
        <th><b> Report Filter: <?php echo e($tanggal_filter); ?></b></th>
        <?php endif; ?>
        </tr>

    </thead>
</table>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Tanggal:</th>
            <th>Sisa Pengiriman Awal:</th>
            <th>Pengiriman Dibuat:</th>
            <th>Total In Delivery</th> 
            <th>Sampai Tujuan:</th>
            <th>Outstanding</th>
            <th>Akumulasi:</th>
        </tr>

    </thead>

    <tbody> 
        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($collection['date']); ?></td>
            <td><?php echo e($collection['begining']); ?></td>
            <td><?php echo e($collection['receiving']); ?></td>
            <td><?php echo e($collection['begining'] + $collection['receiving']); ?></td>
            <td><?php echo e($collection['delivery']); ?></td>
            <td><?php echo e($collection['begining'] + $collection['receiving'] - $collection['delivery']); ?></td>
            <td><?php echo e($collection['closing']); ?></td>
            <td></td>


        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>