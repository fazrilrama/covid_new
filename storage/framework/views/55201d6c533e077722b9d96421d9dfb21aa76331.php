<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container">
        <ul class="progressbar">
            <li><?php echo e(__('lang.create')); ?> <?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?></li>
            <li><?php echo e(__('lang.create')); ?> Item</li>
            <li class="active">Complete</li>
        </ul>
    </div>
    <h1><?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?> - #<?php echo e($stockTransport->code); ?>

        <?php if($type == 'inbound' AND $stockTransport->status == 'Completed'): ?>
        <a href="JavaScript:poptastic('<?php echo e(route('stock_transports.print', ['stock_transport' => $stockTransport->id])); ?>')" type="button" class="btn btn-warning pull-right">
            <i class="fa fa-download"></i> <?php echo e(__('lang.print')); ?> GR
        </a>
        <?php endif; ?>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo e(__('lang.information_data')); ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <form action="#" method="POST" class="form-horizontal">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="project_id" class="col-sm-3 control-label">Project</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->project->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="created_at" class="col-sm-3 control-label"><?php echo e(__('lang.created_at')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->created_at); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="project_id" class="col-sm-3 control-label">
                                        <?php echo e(($stockTransport->type == 'inbound') ? 'AIN#:' : 'AON#:'); ?>

                                    </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->advance_notice->code); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="transport_type_id" class="col-sm-3 control-label"><?php echo e(__('lang.type_transpotation')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->transport_type->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->ref_code); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_id" class="col-sm-3 control-label"><?php echo e(__('lang.shipper')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->shipper->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_address" class="col-sm-3 control-label"><?php echo e(__('lang.shipper_address')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->shipper->address); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->origin->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin_address')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->origin_address); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin_post_code')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->origin_postcode); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin')); ?> Latitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->origin_latitude); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="origin" class="col-sm-3 control-label"><?php echo e(__('lang.origin')); ?> Longitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->origin_longitude); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.pickup_time')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->pickup_order); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="etd" class="col-sm-3 control-label"><?php echo e(__('lang.etd')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->etd); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e(($stockTransport->status == 'Pending') ? 'Planning' : $stockTransport->status); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user_id" class="col-sm-3 control-label"><?php echo e(__('lang.created_by')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e(empty($stockTransport->user) ?'': $stockTransport->user->first_name); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">Traffic</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">
                                            <?php if( $stockTransport->traffic == 1): ?>
                                                PTP
                                            <?php elseif( $stockTransport->traffic == 2): ?>
                                                DTD
                                            <?php elseif( $stockTransport->traffic == 3): ?>
                                                DTP
                                            <?php elseif( $stockTransport->traffic == 4): ?>
                                                PTD
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label">Loading Type</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static">
                                            <?php if( $stockTransport->load_type == 1): ?>
                                                LTL
                                            <?php elseif( $stockTransport->load_type == 2): ?>
                                                FTL
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="consignee_id" class="col-sm-3 control-label"><?php echo e(__('lang.consignee')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->consignee->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="consignee_address" class="col-sm-3 control-label"><?php echo e(__('lang.consignee_address')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->consignee_address); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->destination->name); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination_address')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->dest_address); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?> Post Code</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->dest_postcode); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?> Latitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->dest_latitude); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="col-sm-3 control-label"><?php echo e(__('lang.destination')); ?> Longitude</label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->dest_longitude); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="eta" class="col-sm-3 control-label"><?php echo e(__('lang.eta')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->eta); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo e($stockTransport->employee_name); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Barang</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover no-margin" width="100%">

                            <thead>
                            <tr>
                                <th>Item SKU:</th>
                                <th>Item Name:</th>
                                <th>Group Ref:</th>
                                <th>Control Date:</th>
                                <?php if($stockTransport->type == 'inbound'): ?>
                                    <th>Plan Qty:</th>
                                    <th>Actual Qty:</th>
                                <?php else: ?>
                                    <th>Actual Qty:</th>
                                <?php endif; ?>
                                <th>UOM:</th>
                                <?php if($stockTransport->type == 'inbound'): ?>
                                    <th>Actual Weight:</th>
                                    <th>Actual Volume:</th>
                                <?php endif; ?>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detail->item->sku); ?></td>
                                    <td><?php echo e($detail->item->name); ?></td>
                                    <td><?php echo e($detail->ref_code); ?></td>
                                    <td><?php echo e($detail->control_date); ?></td>
                                    <td><?php echo e(number_format($detail->plan_qty, 2, ',', '.')); ?></td>
                                    <?php if($stockTransport->type == 'inbound'): ?>
                                        <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($detail->uom->name); ?></td>
                                    <?php if($stockTransport->type == 'inbound'): ?>
                                        <td><?php echo e(number_format($detail->weight, 2, ',', '.')); ?></td>
                                        <td><?php echo e(number_format($detail->volume, 2, ',', '.')); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>