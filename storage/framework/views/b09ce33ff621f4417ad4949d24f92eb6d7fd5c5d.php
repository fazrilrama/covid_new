<?php $__env->startSection('content'); ?>
        <h3>Loading Order</h3>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'>Loading Order#</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockTransport->code); ?></td>
                </tr>
                <tr>
                    <td>Delivered From</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->origin->name); ?></td>
                </tr>
                <tr>
                    <td>Customer Reference</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->code); ?></td>
                </tr>
                <tr>
                    <td>Warehouse Officer</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->origin->name); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'>Date and Time</td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($stockTransport->created_at); ?></td>
                </tr>
                <tr>
                    <td>Delivery To</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->destination->name); ?></td>
                </tr>
                <tr>
                    <td>Start</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->destination->name); ?></td>
                </tr>
                <tr>
                    <td>Finish</td>
                    <td>:</td>
                    <td><?php echo e($stockTransport->destination->name); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Descriptions</th>
                        <th>Group Reff.</th>
                        <th>Qty</th>
                        <th>Unit</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($detail->status <> 'canceled'): ?>
                    <tr>
                        <td><?php echo e($detail->item->sku); ?></td>
                        <td><?php echo e($detail->item->name); ?></td>
                        <td><?php echo e($detail->qty); ?></td>
                        <td><?php echo e($detail->uom->name); ?></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>

        <div class="col-sm-12 space-top">
            <h5><strong>PUTAWAY LIST</strong></h5>
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Descriptions</th>
                        <th>Group Reff.</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Picking Loc</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($detail->status <> 'canceled'): ?>
                    <tr>
                        <td><?php echo e($detail->item->sku); ?></td>
                        <td><?php echo e($detail->item->name); ?></td>
                        <td><?php echo e($detail->qty); ?></td>
                        <td><?php echo e($detail->uom->name); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="60%" border="1">
                <thead>
                    <tr align="center">
                        <th>Admin</th>
                        <th>Checker</th>
                        <th colspan="3">Handling</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="5"></td>
                        <td rowspan="5"></td>
                        <td colspan="3">Total Labor : 2</td>
                    </tr>
                    <tr>
                        <td colspan="3">Total Pallet : 8</td>
                    </tr>
                    <tr>
                        <td colspan="3">Forklift</td>
                    </tr>
                    <tr>
                        <td align="center">Type</td>
                        <td align="center">Start</td>
                        <td align="center">Finish</td>
                    </tr>
                    <tr>
                        <td align="center">2 Ton</td>
                        <td align="center">12:00</td>
                        <td align="center">16:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>