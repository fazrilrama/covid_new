<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('adminlte_css'); ?>
    <style>
    .red {
        background-color: #da413e !important;
    }
    .green {
        background-color: #4BB543 !important;
    }
    .orange {
        background-color: #FFC107 !important;
    }
    .blue {
        background-color: #add8e6 !important;
    }
    .table-parent {
        overflow-x: scroll;
    }
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Report Mutasi Barang Per Bulan</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

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
                                    <label for="date_from" class="col-sm-4 col-form-label">Bulan</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="month-picker form-control" placeholder="Tanggal mulai" value="<?php echo e($data['date_from']); ?>" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-search"></i> Search</button>
                                <button class="btn btn-sm btn-success" name="submit" value="2" id="export_excel"><i class="fa fa-download"></i> Export ke Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if(isset($data['report'])): ?>
    <div class="row">
        <div class="table-responsive">
            <table id="warehouse-contract" class="data-table table table-bordered table-hover no-margin" width="125%">
                <thead>
                    <tr>
                        <th rowspan="2">SKU</th>
                        <th rowspan="2">PRODUCT</th>
                        <th rowspan="2">Komoditi</th>
                        <th rowspan="2">UoM</th>
                        <th rowspan="2">STOK AWAL</th>
                        <th rowspan="2">MASUK</th>
                        <th colspan="<?php echo e($data['date_difference']); ?>" class="text-center">Hari</th>
                        <th rowspan="2">Keluar By Monitoring</th>
                        <th rowspan="2">Asumsi Barang Keluar</th>
                        <th rowspan="2">Stock Akhir</th>
                        <th rowspan="2">Rata Rata Barang Keluar</th>
                        <th rowspan="2">Persediaan Rata Rata</th>
                        <th rowspan="2">TOR Parsial</th>
                        <th rowspan="2">Keterangan</th>
                        <th rowspan="2">
                        Persediaan Barang yang Harus di Penuhi
                        </th>
                        <th rowspan="2">Safety Stock</th>
                        <th rowspan="2">Rekomendasi</th>
                        <th rowspan="2">Nilai Barang</th>
                    </tr>
                    <tr>
                        <?php for($i=1; $i<=$data['date_difference']; $i++): ?>
                        <th><?php echo e($i); ?></th>
                        <?php endfor; ?>
                        
                    </tr>
                </thead>
            
                <tbody> 
                    <?php $__currentLoopData = $data['report']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($report['sku']); ?></td>
                            <td><?php echo e($report['sku_name']); ?></td>
                            <td><?php echo e($report['commodity_name']); ?></td>
                            <td><?php echo e($report['uom_name']); ?></td>
                            <td><?php echo e($report['begining']); ?></td>
                            <td><?php echo e(number_format($report['after_begining_in'], 2,',', '.')); ?></td>
                            <?php if( session()->get('current_project')->id == 337): ?>
                            <?php for($i=1; $i<=$data['date_difference']; $i++): ?>
                                <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? number_format($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i],2, ',', '.') : 0); ?></td>
                            <?php endfor; ?>
                            <?php else: ?>
                            <?php for($i=1; $i<=$data['date_difference']; $i++): ?>

                                <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? number_format($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']][$i],2, ',', '.') : 0); ?></td>
                            <?php endfor; ?>
                            <?php endif; ?>
                            <?php if( session()->get('current_project')->id == 337): ?>
                            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]),2, ',', '.') : 0); ?></td>
                            <?php else: ?>
                            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]),2, ',', '.') : 0); ?></td>
                            <?php endif; ?>
                            <?php
                            if( session()->get('current_project')->id == 337){
                                $keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) : 0 ;

                                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3) : 0;

                            }else {
                                $keluar =isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) : 0;
                                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3) : 0;
                            }
                            $persediaan_rata_rata = ($report['begining'] + $report['stock']) / 2 ;

                            if($persediaan_rata_rata > 0) {
                                $tor = round(($keluar/$persediaan_rata_rata) / $data['pembagi'] * 100, 2);
                            } elseif($persediaan_rata_rata == 0){
                                $tor = '-';
                            }
                            else {
                                $tor = 0;
                            }   

                            $asumsi = 10 * $rata_rata_barang_keluar;
                            $safety_stock = round(($report['begining'] + $report['after_begining_in']) * ($report['percentage_buffer'] / 100),2);
                            $persediaan_yg_harus_dipenuhi = $asumsi - $report['stock'] + $safety_stock;
                            
                            ?>
                            <td><?php echo e($asumsi); ?></td>
                            <td><?php echo e($report['stock']); ?></td>
                            <?php if( session()->get('current_project')->id == 337): ?>
                            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3),2, ',', '.') : 0); ?></td>
                            <?php else: ?>
                            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? number_format(round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3),2, ',', '.') : 0); ?></td>
                            <?php endif; ?>
                            <td><?php echo e(($report['begining'] + $report['stock']) / 2); ?></td>
                            
                            <td>
                            <?php echo e($tor); ?>

                            </td>
                            <td>
                            <?php if($tor == '-'): ?>
                                <?php echo e($tor); ?>

                            <?php else: ?>
                                <?php if($tor > 3): ?> 
                                <?php echo e('FAST'); ?>

                                <?php elseif($tor >= 1 && $tor <= 3): ?>
                                <?php echo e('SLOW'); ?>

                                <?php else: ?> 
                                <?php echo e('NON'); ?>

                                <?php endif; ?>
                            <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($persediaan_yg_harus_dipenuhi); ?>

                            </td>
                            <td>
                                <?php echo e($safety_stock); ?>

                            </td>
                            <td>
                                <?php if($report['stock'] <= $safety_stock): ?>
                                    <?php echo e('Beli'); ?>

                                <?php elseif($report['stock'] > $safety_stock): ?>
                                    <?php echo e('Tidak Perlu Beli'); ?>

                                <?php else: ?> 
                                    <?php echo e('-'); ?>

                                <?php endif; ?>
                            </td>
                            <td></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script src='<?php echo e(asset('vendor/mustache/js/mustache.min.js')); ?>'></script>
    <script src="<?php echo e(asset('vendor/replaceSymbol/replaceSymbol.js')); ?>"> </script>
    <script>
        $('.month-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>