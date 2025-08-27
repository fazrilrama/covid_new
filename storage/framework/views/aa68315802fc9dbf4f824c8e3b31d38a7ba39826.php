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
        <th><b>s/d <?php echo e(\Carbon\Carbon::now()->format('d-m-Y')); ?></b></th>
        </tr>

    </thead>
</table>
<table>
    <thead>
    <tr>
        <th rowspan="2">Kode GI</th>
        <th rowspan="2">Dibuat Pada</th>
        <th rowspan="2">Origin</th>
        <th rowspan="2">Destination</th>
        <th rowspan="2">Shipper</th>
        <th rowspan="2">Consignee</th>
        <th rowspan="2">ETA</th>
        <th rowspan="2">Received Date</th>
        <th rowspan="2">Received By</th>
        <th colspan="3">Detail Barang</th>
        <th rowspan="2">Status</th>

    </tr>
    <tr>
        <th>Item:</th>
        <th>Qty:</th>
        <th>UoM:</th>
        
    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="text-align: center;"><b><?php echo e($delivery->code); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->created_at); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->origin->name); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->destination->name); ?></b></td>
            <td style="word-wrap:break-word"> <b><?php echo e($delivery->shipper->name); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->consignee->name); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->eta); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->updated_at); ?></b></td>
            <td style="word-wrap:break-word"><b><?php echo e($delivery->received_by); ?></b></td>
            <td>
                <?php echo e($delivery->item_name); ?>

            </td>
            <td>
                <?php echo e(number_format($delivery->qty, 0, ',', '')); ?>

            </td>
            <td>
                    <?php echo e($delivery->uom_name); ?>

            </td>

            <td style="word-wrap:break-word"><b><?php echo e($delivery->status == 'Processed'  ? 'Loading' : ($delivery->status == 'Completed' ? 'Siap Untuk Diantarkan' : 'Received')); ?></b></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>