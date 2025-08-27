<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Transaksi per Item</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('report.info', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php $__env->startSection('additional_field'); ?>
        <div class="col-sm-6">

            <div class="form-group">
                <label for="date_to" class="col-sm-4 col-form-label">Item</label>
                <div class="col-sm-8">
                    <select name="item" id="item" class="form-control select2">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($itemOption->id); ?>" <?php echo e(!empty($data['item']) ? ($data['item'] == $itemOption->id ? 'selected' : '') : ''); ?>>
                                <?php echo e($itemOption->sku . ' - ' . $itemOption->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>

    <?php echo $__env->make('report.searchDateForm', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php if($search): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm" width="100%" id="dtBasicExample">

            <thead>
                <tr>
                    <th class="no-sort">No:</th>
                    <th>SKU:</th>
                    <th>Warehouse:</th>
                    <th>Transaction Number:</th>
                    <th>Date:</th>
                    <th>Qty:</th>
                    <th>Weight:</th>
                    <th>Volume:</th>
                    <th>UOM:</th>
                    <th>Status:</th>
                    <th>Type:</th>

                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($collection->item_index); ?></td>                        
                        <td><?php echo e($collection->item_sku); ?></td>
                        <td><?php echo e($collection->item_warehouse); ?></td>
                        <td><?php echo e($collection->item_code); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($collection->item_control_date)->format('Y-m-d')); ?></td>
                        <td><?php echo e(number_format($collection->item_qty, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($collection->item_weight, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($collection->item_volume, 2, ',', '.')); ?></td>
                        <td><?php echo e($collection->item_uom_name); ?></td>
                        <td><?php echo e($collection->status); ?></td>
                        <td><?php echo e($collection->type); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total Barang : <?php echo e(number_format($stock, 2, ',', '.')); ?> </b></td>
                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
<script>
    var table = $('#dtBasicExample').DataTable({
        "order": [[ 4, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,5,6,7,8,9,10] }
        ]
    });

    // var count = 0;
	// var count_aktif_kurang =0;
	// count_aktif_bulan = 0;
	// var filterAllDataAktifLebih = table.rows().data().each(function(val, i) {
	// 	// console.log(val[5], val[5] == '.Aktif');
	// 	if (val[10] == 'inbound'){
    //         // console.log(val[6])
    //         text = val[5].substring(0, val[5].indexOf(','));
    //         text = text.split('.').join("");
	// 		count = count + parseFloat(text) 
    //         console.log(count);
	// 	};
	// 	if (val[10] == 'outbound'){
    //         text = val[5].substring(0, val[5].indexOf(','));
    //         text = text.split('.').join("");
	// 		count_aktif_kurang += parseFloat(text) 
	// 	};
	// })
	// // $('#aktif_mati').text(count.toFixed(2));
	// $('#end_of_month').text(count_aktif_kurang.toFixed(2));
    // $('#sisa').text(($('#aktif_mati').text() - count_aktif_kurang))
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>