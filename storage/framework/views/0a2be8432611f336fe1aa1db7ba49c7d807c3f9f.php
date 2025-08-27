<div class="table-responsive">
<table class="data-table table table-bordered table-hover no-margin" width="100%">
    <thead>
        <tr>
            <th>Date:</th>
            <th>Put Away#:</th>
            <th>Qty:</th>
            <th>Weight:</th>
            <th>Volume</th>
        </tr>
    </thead>
    
    <tbody>
    <?php
        $totalQty = 0;
        $totalWeight = 0;
        $totalVolume = 0;
    ?>
    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $itemQty = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('qty');
            $itemWeight = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('weight');
            $itemVolume = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('volume');
            $totalQty += $itemQty;
            $totalWeight += $itemWeight;
            $totalVolume += $itemVolume;
        ?>
        <tr>
            <td><?php echo e(Carbon\Carbon::parse($item->created_at)->format('Y-m-d')); ?></td>
            <td><?php echo e($item->code); ?></td>
            <td><?php echo e($itemQty); ?></td>
            <td><?php echo e($itemWeight); ?></td>
            <td><?php echo e($itemVolume); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
<tfoot>
    <tr>
        <td colspan="2" align="right">Total: </td>
        <td><?php echo e($totalQty); ?></td>
        <td><?php echo e($totalWeight); ?></td>
        <td><?php echo e($totalVolume); ?></td>
    </tr>
</tfoot>
</table>
</div>
<script type="text/javascript">
    window.print();
</script>