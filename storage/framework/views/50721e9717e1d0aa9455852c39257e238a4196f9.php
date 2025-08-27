<table>
    <thead>
    <tr>
        <th><b>OUTBOUND REPORT</b></th>
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
        <th>From</th>
        <th>SKU</th>
        <th>Description</th>
        <th>Date</th>
        <th>Destination</th>
        <th>Doc No</th>
        <th>PIC</th>
        <th>Driver</th>
        <th>Plat No</th>
        <th>Qty</th>
        <th>UoM</th>
        <th>Ref Code</th>
    </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><b><?php echo e($user->parties_name); ?></b></td>
            <td><b><?php echo e($user->sku); ?></b></td>
            <td><b><?php echo e($user->item_name); ?></b></td>
            <td><b><?php echo e(\Carbon\Carbon::parse($user->control_date )->formatLocalized('%d %B %Y')); ?></b></td>
            <td><b><?php echo e($user->consignee_name); ?></b></td>
            <td><b><?php echo e($user->ref_code); ?></b></td>
            <td><b><?php echo e($user->pic); ?></b></td>
            <td><b><?php echo e($user->driver_name); ?></b></td>
            <td><b><?php echo e($user->police_number); ?></b></td>
            <td><b><?php echo e(number_format($user->qty, 0, ',', '')); ?></b></td>
            <td><b><?php echo e($user->uom); ?></b></td>
            <td><b><?php echo e($user->item_ref_code); ?></b></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>