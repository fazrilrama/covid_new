<?php $__env->startSection('content'); ?>
    <div style="text-align:center;">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
    </div>
    <h3 class="col-sm-12" style="text-align: center;font-weight: bold">
    <?php if($advanceNotice->type == 'inbound'): ?>
    Surat Perintah Barang Masuk (SPBM)
    <?php else: ?>
    Surat Perintah Barang Keluar (SPBK)
    <?php endif; ?>
    </h3>
    <h4 class="col-sm-12" style="text-align: center;font-weight: bold;margin-top: 0px"><?php echo e($advanceNotice->code); ?></h4>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>Project</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->project->name); ?></td>
                </tr>
                <tr>
                    <td>Tanggal Dibuat</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->created_at); ?></td>
                </tr>
                <tr>
                    <td>No. Kontrak</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->contract_number); ?></td>
                </tr>
                <tr>
                    <td>No. SPK</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->spmp_number); ?></td>
                </tr>
                <tr>
                    <td>Jenis Kegiatan</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->advance_notice_activity->name); ?></td>
                </tr>
                <tr>
                    <td>Moda Kegiatan</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->transport_type->name); ?></td>
                </tr>
                <tr>
                    <td>Customer Reference</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->ref_code); ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <?php if(isset($from_job)): ?>
                            <?php
                            $logged_user = \App\User::find($job_user_id)
                            ?>
                            <?php if($logged_user->hasRole('CargoOwner')): ?>
                            <?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status); ?>

                            <?php else: ?>
                            <?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status)); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
                            <?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status); ?>

                            <?php else: ?>
                            <?php echo e(($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status)); ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Pembuat</td>
                    <td>:</td>
                    <td><?php echo e(@$advanceNotice->user->user_id); ?></td>
                </tr>
                <tr>
                    <td>No. SPPK</td>
                    <td>:</td>
                    <td><?php echo e(@$advanceNotice->sppk_num); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td>Origin</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->origin->name); ?></td>
                </tr>
                <tr>
                    <td>Est. Time Delivery</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->etd); ?></td>
                </tr>
                <tr>
                    <td><?php if($advanceNotice->type == 'inbound'): ?> Shipper <?php else: ?> Cabang/Subcabang <?php endif; ?></td>
                    <td>:</td>
                    <td><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->name); ?></td>
                </tr>
                <tr>
                    <td><?php if($advanceNotice->type == 'inbound'): ?> Shipper <?php else: ?> Cabang/Subcabang <?php endif; ?> Address</td>
                    <td>:</td>
                    <td><?php echo e(empty($advanceNotice->shipper) ?'' : $advanceNotice->shipper->address); ?></td>
                </tr>
                <tr>
                    <td>Destination</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->destination->name); ?></td>
                </tr>
                <tr>
                    <td>Est. Time Arrival</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->eta); ?></td>
                </tr>
                <tr>
                    <td><?php if($advanceNotice->type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?></td>
                    <td>:</td>
                    <td><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->name); ?></td>
                </tr>
                <tr>
                    <td><?php if($advanceNotice->type == 'inbound'): ?> Cabang/Subcabang <?php else: ?> Consignee <?php endif; ?> Address</td>
                    <td>:</td>
                    <td><?php echo e(empty($advanceNotice->consignee) ?'': $advanceNotice->consignee->address); ?></td>
                </tr>
                <tr>
                    <td>Warehouse</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->warehouse->name); ?></td>
                </tr>
                <tr>
                    <td>Warehouse Supervisor</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->employee_name); ?></td>
                </tr>

                <!-- NEW BULOG -->
                <!-- <tr>
                    <td>Annotation</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->annotation); ?></td>
                </tr>
                <tr>
                    <td>Contractor</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->contractor ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>Head Cabang/Subcabang</td>
                    <td>:</td>
                    <td><?php echo e($advanceNotice->head_ds ?? '-'); ?></td>
                </tr> -->
            </table>
        </div>

    </div>  
    <p>Informasi Barang</p>
    <div class="row">
        <div class="col-sm-12">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>ID:</th>
                        <th>Item SKU:</th>
                        <th>Item Name:</th>
                        <th>Group Reference:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Weight:</th>
                        <th colspan="2">Volume:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $advanceNotice->details->where('status', '<>', 'canceled'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($detail->id); ?></td>
                            <td><?php echo e($detail->item->sku); ?></td>
                            <td><?php echo e($detail->item->name); ?></td>
                            <td><?php echo e($detail->ref_code); ?></td>
                            <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
                            <td><?php echo e($detail->uom->name); ?></td>
                            <td><?php echo e(number_format($detail->weight, 2, ',', '.')); ?></td>
                            <td><?php echo e(number_format($detail->volume, 2, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>