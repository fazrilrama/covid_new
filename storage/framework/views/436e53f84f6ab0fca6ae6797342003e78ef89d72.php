<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Mutasi Stock per Bulan</b></th>
    </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th><b><?php echo e($first_date); ?> s/d <?php echo e($end_date); ?></b></th>
        </tr>

    </thead>
</table>
<table>
    <thead>
        <tr>
            <th>SKU</th>
            <th>PRODUCT</th>
            <th>Komoditi</th>
            <th>UoM</th>
            <th>STOK AWAL</th>
            <th>MASUK</th>
            <?php for($i=1; $i<=$data['date_difference']; $i++): ?>
            <th><?php echo e($i); ?></th>
            <?php endfor; ?>
            <th>Keluar By Monitoring</th>
            <th>Asumsi Barang Keluar</th>
            <th>Stock Akhir</th>
            <th>Rata Rata Barang Keluar</th>
            <th>Persediaan Rata Rata</th>
            <th>TOR Parsial</th>
            <th>Keterangan</th>
            <th>
            Persediaan Barang yang Harus di Penuhi
            </th>
            <th>Safety Stock</th>
            <th>Rekomendasi</th>
            <th>Nilai Barang</th>
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
            <td><?php echo e($report['after_begining_in']); ?></td>
            <?php if( session()->get('current_project')->id == 337): ?>
            <?php for($i=1; $i<=$data['date_difference']; $i++): ?>
                <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? $data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i] : 0); ?></td>
            <?php endfor; ?>
            <?php else: ?>
            <?php for($i=1; $i<=$data['date_difference']; $i++): ?>

                <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? $data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']][$i] : 0); ?></td>
            <?php endfor; ?>
            <?php endif; ?>
            <?php if( session()->get('current_project')->id == 337): ?>
            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]): 0); ?></td>
            <?php else: ?>
            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) : 0); ?></td>
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
            $safety_stock = ($report['begining'] + $report['after_begining_in'] )* ($report['percentage_buffer'] / 100);
            $persediaan_yg_harus_dipenuhi = $asumsi - $report['stock'] + $safety_stock;
            
            ?>
            <td><?php echo e($asumsi); ?></td>
            <td><?php echo e($report['stock']); ?></td>
            <?php if( session()->get('current_project')->id == 337): ?>
            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3) : 0); ?></td>
            <?php else: ?>
            <td><?php echo e(isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3) : 0); ?></td>
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

                <?php else: ?>
                    <?php echo e('Tidak Perlu Beli'); ?>

                <?php endif; ?>
            </td>
            <td></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>