<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Handling <?php echo e($type=='inbound' ? 'In' : 'Out'); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('report.info', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Pencarian Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <form method="GET" class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                            <?php echo $__env->yieldContent('additional_field'); ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="date_from" class="col-sm-4 col-form-label">Tanggal Mulai</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="datepicker-normal form-control" placeholder="Tanggal mulai" value="<?php echo e($data['date_from']); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_to" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_to" id="date_to" class="end-datepicker-normal form-control" placeholder="Tanggal akhir" value="<?php echo e($data['date_to']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="date_from" class="col-sm-4 col-form-label">Warehouse</label>
                                    <div class="col-sm-8">
                                        <select name="warehouse_id" class="form-control select2" required>
                                            <option value="" selected disabled>--Pilih Warehouse--</option>
                                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($warehouse->id); ?>" <?php echo e(!empty($data['warehouse_id']) ? ($data['warehouse_id'] == $warehouse->id ? 'selected' : '') : ''); ?>>
                                                    <?php echo e($warehouse->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
                        <!-- <?php if(isset($print_this)): ?>
                            <a href="JavaScript:poptastic('/report/stock_mutation/print')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
                                <i class="fa fa-print"></i> Cetak Mutasi Hari Ini
                            </a>
                        <?php endif; ?> -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if($search): ?>
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Date:</th>
                    <th><?php echo e($type == 'inbound' ? 'Good Receiving' : 'Good Issue'); ?>#:</th>
                    <th>Activity</th>
                    <th>Warehouse</th>
                    <th>Item:</th>
                    <th>Handling Tarif:</th>
                    <th>Qty:</th>
                    <th>Weight:</th>
                    <th>Volume</th>
                    <th>Charge</th>
                    <th>Tarif</th>
                    <th>Total Tarif</th>
<!--                     <th>Rates</th>
                    <th>Total</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(Carbon\Carbon::parse($item->created_at)->format('Y-m-d')); ?></td>
                        <td><?php echo e($item->item_code); ?></td>
                        <td><?php echo e($item->item_activity_name); ?></td>
                        <td><?php echo e($item->item_warehouse_name); ?></td>
                        <td><?php echo e($item->item_name); ?></td>
                        <td>Rp.<?php echo e(number_format($item->item_handling_tarif, 2, ',', '.')); ?>,-</td>
                        <td><?php echo e(number_format($item->item_qty, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($item->item_weight, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($item->item_volume, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($item->charge, 2, ',', '.')); ?></td>
                        <td>Rp.<?php echo e(number_format($item->tarif, 2, ',', '.')); ?>,-</td>
                        <td>Rp.<?php echo e(number_format($item->total_tarif, 2, ',', '.')); ?>,-</td>
<!--                         <td></td>
                        <td><?php echo e(number_format($item->total, 2, ',', '.')); ?></td> -->
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <!-- <tfoot>
                <tr>
                    <td colspan="3" align="right">Total: </td>
                    <td><?php echo e(number_format($total_qty, 2, ',', '.')); ?></td>
                    <td><?php echo e(number_format($total_weight, 2, ',', '.')); ?></td>
                    <td><?php echo e(number_format($total_volume, 2, ',', '.')); ?></td>
                    <td></td>
                    <td><?php echo e(number_format($total_rates, 2, ',', '.')); ?></td> 
                </tr>
            </tfoot> -->
        </table>
    </div>
    <?php endif; ?>

<script type="text/javascript">
    var newwindow;
    function poptastic(url)
    {
        newwindow=window.open(url,'name','height=800,width=1600');
        if (window.focus) {newwindow.focus()}
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>