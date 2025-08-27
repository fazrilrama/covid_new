<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <?php if($stockTransport->status == 'Completed'): ?>
        <h3 style="text-align: center;">
            <?php echo e($stockTransport->type == 'inbound' ? 'Good Receiving' : 'Delivery Planning'); ?>

        </h3>
        <h4 style="text-align: center;">
            
            <p style="margin:0px;font-weight: bold">
                <?php echo e($stockTransport->code); ?>

            </p>
        </h4>
        <?php elseif($stockTransport->status == 'Processed' AND $stockTransport->details()->sum('qty') > 0): ?>
        <h3 style="text-align: center;" style="text-align: center;">
            <?php echo e($stockTransport->type == 'inbound' ? 'Good Received' : 'Delivery Planned'); ?>

        </h3>
    <?php elseif($stockTransport->status == 'Processed' AND $stockTransport->details()->sum('qty') == 0): ?>
        <h3 style="text-align: center;">
            <?php echo e($stockTransport->type == 'inbound' ? 'Tally Sheet' : 'Delivery Plan'); ?>

        </h3>
        <?php endif; ?>
    <div class="row">
        <?php if( $stockTransport->type == 'inbound' ): ?>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>GR#</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($stockTransport->code); ?></td>
                    </tr>
                    <tr>
                        <td>AIN#</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->advance_notice->code); ?></td>
                    </tr>
                    <tr>
                        <td width='45%'>Tanggal Dibuat</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($stockTransport->created_at); ?></td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->project->company->name); ?></td>
                    </tr>
                    <tr>
                        <td>Warehouse Officer</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->employee_name); ?></td>
                    </tr>
                    <?php if($stockTransport->warehouse): ?>
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td><?php echo e($stockTransport->warehouse->name); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>Asal dan Tanggal Tiba</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($stockTransport->origin->name); ?> / <?php echo e($stockTransport->etd); ?></td>
                    </tr>
                    <tr>
                        <td>Kontraktor/Pengangkut</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->shipper->name); ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Penerimaan</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->pickup_order); ?></td>
                    </tr>
                    <tr>
                        <td>Nama Driver</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->driver_name); ?></td>
                    </tr>
                    <tr>
                        <td>No.Telp Driver</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->driver_phone); ?></td>
                    </tr>
                    <tr>
                        <td>No.Polisi Truk</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->police_number); ?></td>
                    </tr>
                </table>
            </div>
        <?php else: ?>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>DP#</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($stockTransport->code); ?></td>
                    </tr>
                    <!-- <tr>
                        <td>AON#</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->advance_notice->code); ?></td>
                    </tr> -->
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->project->company->name); ?></td>
                    </tr>
                    <tr>
                        <td>Delivered From</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->origin->name); ?></td>
                    </tr>
                    <tr>
                        <td>Warehouse Officer</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->employee_name); ?></td>
                    </tr>
                    <?php if($stockTransport->warehouse): ?>
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td><?php echo e($stockTransport->warehouse->name); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                <table width="100%">
                    <tr>
                        <td width='45%'>Tanggal Dibuat</td>
                        <td width='5%'>:</td>
                        <td width='50%'><?php echo e($stockTransport->created_at); ?></td>
                    </tr>
                    <tr>
                        <td>Delivered To</td>
                        <td>:</td>
                        <td><?php echo e($stockTransport->destination->name); ?></td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Item SKU</th>
                    <th rowspan="2">Group Reference</th>
                    <?php if( $stockTransport->type == 'inbound' ): ?>
                        <th colspan="4">Plan</th>
                    <?php endif; ?>
                    <?php if( $stockTransport->type == 'inbound' ): ?>
                        <th colspan="3">Actual</th>
                    <?php else: ?>
                        <th colspan="4">Actual</th>
                    <?php endif; ?>
                    
                </tr>
                <tr>
                    <th>Qty</th>
                    <th>UoM</th>
                    <th>Weight</th>
                    <th>Volume</th>
                    <?php if( $stockTransport->type == 'inbound' ): ?>
                        <th>Qty</th>
                        <th>Weight</th>
                        <th>Volume</th>
                    <?php endif; ?>
                </tr>
                </thead>

                <tbody>

                <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($detail->status <> 'canceled'): ?>
                        <tr>
                            <td><?php echo e($detail->item->sku); ?> - <?php echo e($detail->item->name); ?></td>
                            <td><?php echo e($detail->ref_code); ?></td>
                            <td><?php echo e(number_format($detail->plan_qty)); ?></td>
                            <td><?php echo e($detail->uom->name); ?></td>
                            <td><?php echo e(number_format($detail->plan_weight)); ?></td>
                            <td><?php echo e(number_format($detail->plan_volume)); ?></td>
                            <?php if( $stockTransport->type == 'inbound' ): ?>
                                <td><?php echo e(number_format($detail->qty == 0 ? 0:$detail->qty)); ?></td>
                                <td><?php echo e(number_format($detail->weight == 0 ? 0:$detail->weight)); ?></td>
                                <td><?php echo e(number_format($detail->volume == 0 ? 0:$detail->volume)); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                <tr>
                    <td align="center"><b>Kepala Gudang<b></td>
                    <td align="center"><b>Checker</b></td>
                    <?php if( $stockTransport->type == 'inbound' ): ?>
                        <td align="center"><b>Driver</b></td>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 80px">
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">            
                        <?php if(Auth::user()->first_name): ?>
                            (<?php echo e(Auth::user()->first_name); ?>)
                        <?php else: ?>
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        <?php endif; ?> 
                    </td>
                    <td rowspan="5" style="vertical-align: bottom;text-align: center">
                        <?php if($stockTransport->employee_name): ?>
                            (<?php echo e($stockTransport->employee_name); ?>)
                        <?php else: ?>
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        <?php endif; ?>                
                    </td>
                    <?php if( $stockTransport->type == 'inbound' ): ?>
                        <td rowspan="5" style="vertical-align: bottom;text-align: center">
                            <?php if($stockTransport->driver_name): ?>
                                (<?php echo e($stockTransport->driver_name); ?>)
                            <?php else: ?>
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>