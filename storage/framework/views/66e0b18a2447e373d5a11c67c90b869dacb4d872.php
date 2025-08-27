<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Mutasi Stock</b></th>
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
        <th>Date:</th>
        <th>Item:</th>
        <th>UOM</th>
        <th>Begining:</th>
        <th>Receiving:</th>
        <th>In Standing</th>
        <th>Delivery:</th>
        <th>Out Standing</th>
        <th>Closing:</th>
    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
        <td><?php echo e($collection['date']); ?></td>
        <td><?php echo e($collection['item']); ?></td>
        <td><?php echo e($collection['uom_name']); ?></td>
        <td><?php echo e(number_format($collection['begining'], 2, ',', '.')); ?></td>
        <td><?php echo e(number_format($collection['receiving'], 2, ',', '.')); ?></td>
        <?php if($loop->last): ?>
        <td><?php echo e(number_format($jumlah_inhandling, 2, ',', '.')); ?></td>
        <?php else: ?>
        <td>0,00</td>
        <?php endif; ?>
        <td><?php echo e(number_format($collection['delivery'], 2, ',', '.')); ?></td>
        <?php if($loop->last): ?>
        <td><?php echo e(number_format($jumlah_outhandling, 2, ',', '.')); ?></td>
        <?php else: ?> 
        <td>0,00</td>
        <?php endif; ?>
        
        <td><?php echo e(number_format($collection['closing'], 2, ',', '.')); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>