<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 style="text-align: center"><?php echo e($stockEntry->type == 'inbound' ? 'Putaway List' : 'Picking Plan List'); ?></h3>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'><?php echo e($stockEntry->type == 'inbound' ? 'PA' : 'PP'); ?>#</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockEntry->code); ?></td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>:</td>
                    <td><?php echo e($stockEntry->project->company->name); ?></td>
                </tr>
                <tr>
                    <td>Warehouse Officer</td>
                    <td>:</td>
                    <td><?php echo e($stockEntry->employee_name); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <!-- <td width='45%'><?php echo e($stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time'); ?></td> -->
                    <td width='45%'>Tanggal Dibuat</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockEntry->created_at); ?></td>
                </tr>
                <tr>
                    <!-- <td width='45%'><?php echo e($stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time'); ?></td> -->
                    <td width='45%'>Consignee</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockEntry->stock_transport->consignee->name); ?></td>
                </tr>
                <tr>
                    <!-- <td width='45%'><?php echo e($stockEntry->type == 'inbound' ? 'Putaway Time' : 'Picking Plan Time'); ?></td> -->
                    <td width='45%'>Tujuan</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockEntry->stock_transport->destination->name); ?></td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU:</th>
                        <th>Item SKU:</th>
                        <th>Group Reference:</th>
                        <th>Control Date:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Storage:</th>
                        <th>Check</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $stockEntry->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($detail->status <> 'canceled'): ?>
                        <tr>
                            <td><?php echo e($detail->item->sku); ?></td>
                            <td><?php echo e($detail->item->sku); ?> - <?php echo e($detail->item->name); ?></td>
                            <td><?php echo e($detail->ref_code); ?></td>
                            <td><?php echo e($detail->control_date); ?></td>
                            <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
                            <td><?php echo e($detail->uom->name); ?></td>
                            <td><?php echo e(@$detail->storage->code); ?></td>
                            <td></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php if(Auth::user()->hasRole('WarehouseSupervisor')): ?>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <td align="center"><b>Kepala Gudang<b></td>
                        <td align="center"><b>Checker</b></td>
                        <td align="center" colspan="3"><b>Handling</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="8"style="vertical-align: bottom;text-align: center">
                            <?php if(Auth::user()->first_name): ?>
                                (<?php echo e(Auth::user()->first_name); ?>)
                            <?php else: ?>
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            <?php endif; ?> 
                        </td>
                        <td rowspan="8"style="vertical-align: bottom;text-align: center">
                            <?php if($stockEntry->employee_name): ?>
                                (<?php echo e($stockEntry->employee_name); ?>)
                            <?php else: ?>
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            <?php endif; ?> 
                        </td>
                        <td colspan="2">Total Koli</td>
                        <td align="center">
                        <?php echo e($stockEntry->total_koli == 0 ? '':$stockEntry->total_koli); ?>

                        </td>
                       
                        
                    </tr>
                    <tr>
                    <td colspan="2">Total Berat</td>
                        <td align="center">
                        <?php echo e($stockEntry->total_berat == 0 ? '':$stockEntry->total_berat); ?>

                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">Total Volume</td>
                        <td align="center">
                            <?php echo e($stockEntry->total_volume == 0 ? '':$stockEntry->total_volume); ?>

                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Pallet</td>
                        <td align="center">
                            <?php echo e($stockEntry->total_pallet == 0 ? '':$stockEntry->total_pallet); ?>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Labor</td>
                        <td align="center">
                            <?php echo e($stockEntry->total_labor == 0 ? '':$stockEntry->total_labor); ?>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Forklift</td>
                        <td align="center">
                            <?php echo e($stockEntry->total_forklift == 0 ? '':$stockEntry->total_forklift); ?>

                        </td>
                    </tr>
                    <tr>
                        <td align="center">Type</td>
                        <td align="center">Start</td>
                        <td align="center">Finish</td>
                    </tr>
                    <tr>
                        <td align="center">Forklift</td>
                        <td align="center"><?php echo e($stockEntry->forklift_start_time); ?></td>
                        <td align="center"><?php echo e($stockEntry->forklift_end_time); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="col-sm-12 space-top">
            <table width="20%" border="1">
                <thead>
                    <tr>
                        <th align="center"><b>Detail Barang<b></th>
                        <th align="center"><b>Jumlah<b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Total Koli</td>
                    <td align="center">
                    <?php echo e($stockEntry->total_koli == 0 ? '':$stockEntry->total_koli); ?>

                    </td>
                    </tr>
                    <tr>
                    <td>Total Berat</td>
                        <td align="center">
                        <?php echo e($stockEntry->total_berat == 0 ? '':$stockEntry->total_berat); ?>

                        </td>
                    </tr>
                    <tr>
                    <td>Total Volume</td>
                        <td align="center">
                            <?php echo e($stockEntry->total_volume == 0 ? '':$stockEntry->total_volume); ?>

                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>