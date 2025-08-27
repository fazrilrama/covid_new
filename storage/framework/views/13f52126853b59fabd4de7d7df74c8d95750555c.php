<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 class="col-sm-12" style="text-align: center;font-weight: bold">
    Tanda Terima
    </h3>
    <h4 class="col-sm-12" style="text-align: center;font-weight: bold;margin-top: 0px"><?php echo e($advanceNotice->code); ?></h4>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>AIN#</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->code); ?></td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->project->company->name); ?></td>
                </tr>
                <?php if($advanceNotice->warehouse): ?>
                    <tr>
                        <td>Warehouse</td>
                        <td>:</td>
                        <td><?php echo e($advanceNotice->warehouse->name); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td width='45%'>Asal </td>
                    <td width='5%'>:</td>
                    <td width='50%'><?php echo e($advanceNotice->origin->name); ?> </td>
                </tr>
                <tr>
                    <td>Kontraktor/Pengangkut</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->shipper->name); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>No.Polisi Truk</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Koli</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Weight</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td>Total Volume</td>
                    <td>:</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 space-top">
            <table id="print-transport" width="100%" border="1">
                <thead>
                <tr>
                    <th rowspan="2">Item SKU</th>
                    <th rowspan="2">Group Reference</th>
                    <th colspan="2">Total</th>
                </tr>
                <tr>
                    <th>Qty</th>
                    <th>UoM</th>
                </tr>
                </thead>

                <tbody>

                <?php $__currentLoopData = $advanceNotice->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($detail->status <> 'canceled'): ?>
                        <tr>
                            <td><?php echo e($detail->item->sku); ?> - <?php echo e($detail->item->name); ?></td>
                            <td><?php echo e($detail->ref_code); ?></td>
                            <td><?php echo e(number_format($detail->qty)); ?></td>
                            <td><?php echo e($detail->uom->name); ?></td>
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
                    <td align="center"><b>Driver</b></td>
                    <td align="center"><b>Tanggal dan Waktu <br> Penerimaan</b></td>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 80px">
                    <td width="27.5%"rowspan="5" style="vertical-align: bottom;text-align: center">            
                        <?php if(Auth::user()->first_name): ?>
                            (<?php echo e(Auth::user()->first_name); ?>)
                        <?php else: ?>
                            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                        <?php endif; ?> 
                    </td>
                    <td  width="27.5%" rowspan="5" style="vertical-align: bottom;text-align: center">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td  width="27.5%" rowspan="5" style="vertical-align: bottom;text-align: center">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </td>
                    <td  width="17.5%"rowspan="5" style="vertical-align: bottom;text-align: center">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>