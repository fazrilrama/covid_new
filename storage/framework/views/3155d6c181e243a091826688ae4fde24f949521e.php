<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <?php if($type == 'inbound'): ?>
        <h1>Laporan Stock On Staging IN</h1>
    <?php else: ?>
        <h1>Laporan Stock On Staging OUT</h1>
    <?php endif; ?>
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
            <form action="<?php echo e(route('report.stock-on-staging',$type)); ?>" method="GET" class="form-horizontal">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sku" class="col-sm-4 col-form-label">Item SKU</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="sku" name="sku" required>
                                        <option value="" selected disabled>--Pilih SKU--</option>
                                        <option value="">All</option>
                                        <?php $__currentLoopData = $skus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sku): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sku->item_id); ?>" <?php echo e(!empty($data['sku']) ? ($data['sku'] == $sku->item_id ? 'selected' : '') : ''); ?>><?php echo e($sku->item->sku); ?> / <?php echo e($sku->item->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ref" class="col-sm-4 col-form-label">Group Ref</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="ref" name="ref" required>
                                        <option value="" selected disabled>--Pilih Group Ref--</option>
                                        <?php $__currentLoopData = $ref_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($ref); ?>" <?php echo e(!empty($data['ref']) ? ($data['ref'] == $ref ? 'selected' : '') : ''); ?>><?php echo e($ref); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- <div class="form-group">
                                <label for="storage_id" class="col-sm-4 col-form-label">Warehouse ID</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="warehouse_id" name="warehouse_id" required>
                                        <option value="" selected disabled>--Pilih Warehouse--</option>
                                        <option value="">All</option>
                                        <?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($storage->id); ?>" <?php echo e(!empty($data['storage_id']) ? ($data['storage_id'] == $storage->id ? 'selected' : '') : ''); ?>><?php echo e($storage->code); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div> -->
                            <?php if($type == 'outbound'): ?>
                                <div class="form-group">
                                    <label for="storage_id" class="col-sm-4 col-form-label">Storage ID</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="storage_id" name="storage_id" required>
                                            <option value="" selected disabled>--Pilih Storage--</option>
                                            <option value="">All</option>
                                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($warehouse->warehouse->name); ?>">
                                                    <?php $__currentLoopData = $warehouse->storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($storage->id); ?>" <?php echo e(!empty($data['storage_id']) ? ($data['storage_id'] == $storage->id ? 'selected' : '') : ''); ?>><?php echo e($storage->code); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>                                       
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="control_date" class="col-sm-4 col-form-label">Control Date</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="control_date" name="control_date" required>
                                        <option value="" selected disabled>--Pilih Control Date--</option>
                                        <option value="">All</option>
                                        <?php $__currentLoopData = $control_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $control_date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($control_date); ?>" <?php echo e(!empty($data['control_date']) ? ($data['control_date'] == $control_date ? 'selected' : '') : ''); ?>><?php echo e($control_date); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-sm btn-primary" name="submit" value="0">
                        <i class="fa fa-search"></i> Cari
                    </button>
                    <button class="btn btn-sm btn-success" name="submit" value="1">
                        <i class="fa fa-download"></i> Export ke Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover no-margin" width="100%">
        <thead>
            <tr>
                <th>No Transaksi:</th>
                <th>Item SKU / Name:</th>
                <th>Group Ref:</th>
                <th>Control Date:</th>
                <th>Uom:</th>
                <?php if($type == 'outbound'): ?>
                    <th>Storage:</th>
                <?php endif; ?>
                <!-- <th>Notes:</th> -->
                <th>QTY:</th>
                <!-- <th>Available:</th> -->
            </tr>
        </thead>
        <tbody> 
            <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php if($collection->type == 'inbound'): ?>
                        <td>SA/IN:<?php echo e($collection->code); ?></td>
                    <?php else: ?>
                        <td>SA/OUT:<?php echo e($collection->code); ?></td>
                    <?php endif; ?>
                    <td><?php echo e($collection->item->sku); ?> / <?php echo e($collection->item->name); ?></td>
                    <td><?php echo e($collection->ref_code); ?></td>
                    <td><?php echo e($collection->control_date); ?></td>
                    <td><?php echo e($collection->item->default_uom->name); ?></td>
                    <?php if($type == 'outbound'): ?>
                        <td><?php echo e($collection->storage->code); ?></td>
                    <?php endif; ?>
                    <!-- <td><?php echo e($collection->item_notes); ?></td> -->
                    <td><?php echo e(number_format($collection->qty, 2, ',', '.')); ?></td>
                    <!-- <td><?php echo e(number_format($collection->item_qty_available, 2, ',', '.')); ?></td> -->
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($type == 'outbound'): ?>
                <tr>
                    <td colspan="6" style="text-align: right;font-weight: bold;font-size: 16px">Stock Staging</td>
                    <td colspan="1" style="font-weight: bold;font-size: 16px">
                        <?php echo e(number_format($stock, 2, ',', '.')); ?>

                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: right;font-weight: bold;font-size: 16px">Stock Staging</td>
                    <td colspan="1" style="font-weight: bold;font-size: 16px">
                        <?php echo e(number_format($stock, 2, ',', '.')); ?>

                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>