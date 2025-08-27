<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container">
      <ul class="progressbar">
          <li><?php echo e(__('lang.create')); ?> Good Issues</li>
          <li class="active">Complete</li>
      </ul>
    </div>
    <h1>Goods Issue - #<?php echo e($stockDelivery->code); ?>

        <a href="JavaScript:poptastic('<?php echo e(url('/stock_deliveries/'.$stockDelivery->id.'/print')); ?>')" type="button" class="btn btn-warning pull-right">
            <i class="fa fa-download"></i> <?php echo e(__('lang.print')); ?>

        </a>
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
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label"><?php echo e(__('lang.document_reference')); ?> #</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->stock_entry->code); ?></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label"><?php echo e(__('lang.created_at')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->created_at); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label"><?php echo e(__('lang.type_transpotation')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->transport_type->name); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.shipper')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockDelivery->shipper) ?'' : $stockDelivery->shipper->name); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.shipper_address')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockDelivery->shipper_address) ?'' : $stockDelivery->shipper_address); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->ref_code); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(($stockDelivery->status == 'Pending') ? 'Planning' : ($stockDelivery->status)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="total_pallet" class="col-sm-3 control-label">Total <?php echo e(__('lang.colly')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockDelivery->total_collie) ? '0' : $stockDelivery->total_collie); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_labor" class="col-sm-3 control-label">Total Weight</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockDelivery->total_weight) ? '0' : $stockDelivery->total_weight); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_forklift" class="col-sm-3 control-label">Total Volume</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockDelivery->total_volume) ? '0' : $stockDelivery->total_volume); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Origin</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->origin->name); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Destination</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->destination->name); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label"><?php echo e(__('lang.eta')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->etd); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label"><?php echo e(__('lang.etd')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->eta); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockDelivery->employee_name); ?></p>
                                            </div>
                                        </div>
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
                    <h3 class="box-title"><?php echo e(__('lang.information_item')); ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped no-margin" width="100%">
                            <thead>
                            <tr>
                                <th>Item SKU:</th>
                                <th>Item Name</th>
                                <th>Group Ref</th>
                                <!-- <th>Control Date:</th> -->
                                <th>Qty:</th>
                                <th>UoM:</th>
                                <th>Weight:</th>
                                <th>Volume:</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $stockDelivery->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detail->item->sku); ?></td>
                                    <td><?php echo e($detail->item->name); ?></td>
                                    <td><?php echo e($detail->ref_code); ?></td>
                                    <!-- <td><?php echo e($detail->control_date); ?></td> -->

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
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>