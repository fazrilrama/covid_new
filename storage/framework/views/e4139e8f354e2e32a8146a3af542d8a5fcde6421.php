<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container">
      <ul class="progressbar">
          <li>Buat <?php if($type=='inbound'): ?> Putaway <?php else: ?> Picking Plan <?php endif; ?></li>
          <li><?php echo e(__('lang.create')); ?> Item</li>
          <li class="active">Complete</li>
      </ul>
    </div>
    <h1><?php if($type=='inbound'): ?> Putaway <?php else: ?> Picking Plan <?php endif; ?> - #<?php echo e($stockEntry->code); ?>

        <a href="JavaScript:poptastic('<?php echo e(route('stock_entries.print', ['stock_entry' => $stockEntry->id])); ?>')" type="button" class="btn btn-warning pull-right">
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
                                            <label for="code" class="col-sm-3 control-label"><?php if($type=='inbound'): ?> GR# <?php else: ?> DP# <?php endif; ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(empty($stockEntry->stock_transport) ?'': $stockEntry->stock_transport->code); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.created_at')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->created_at); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label"><?php echo e(__('lang.customer_reference')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->ref_code); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label"><?php echo e(__('lang.warehouse_checker')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->employee_name); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e(($stockEntry->status == 'Pending') ? 'Planning' : ($stockEntry->status)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="total_pallet" class="col-sm-3 control-label">Total <?php echo e(__('lang.pallet')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_pallet); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_labor" class="col-sm-3 control-label">Total <?php echo e(__('lang.labor')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_labor); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_forklift" class="col-sm-3 control-label">Total Forklift</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_forklift); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_koli" class="col-sm-3 control-label">Total <?php echo e(__('lang.colly')); ?></label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_koli); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_berat" class="col-sm-3 control-label">Total Berat</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_berat); ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static"><?php echo e($stockEntry->total_volume); ?></p>
                                            </div>
                                        </div>
                                        <?php if(!empty($stockEntry->forklift_start_time)): ?>
                                            <div class="form-group">
                                                <label for="project_id" class="col-sm-3 control-label">Forklift <?php echo e(__('lang.start_time')); ?></label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static"><?php echo e($stockEntry->forklift_start_time); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($stockEntry->forklift_end_time)): ?>
                                            <div class="form-group">
                                                <label for="project_id" class="col-sm-3 control-label">Forklift <?php echo e(__('lang.end_time')); ?></label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static"><?php echo e($stockEntry->forklift_end_time); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
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
                                <th>Group Ref:</th>
                                <th>Control Date:</th>
                                <th>Storage:</th>
                                <th>Qty:</th>
                                <th>UOM:</th>
                                <th>Weight:</th>
                                <th>Volume:</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $stockEntry->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($detail->status <> 'canceled'): ?>
                                    <tr>
                                        <td><?php echo e($detail->item->sku); ?></td>
                                        <td><?php echo e($detail->item->name); ?></td>
                                        <td><?php echo e($detail->ref_code); ?></td>
                                        <td><?php echo e($detail->control_date); ?></td>
                                        <td><?php echo e(@$detail->storage->code); ?></td>
                                        <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
                                        <td><?php echo e($detail->uom->name); ?></td>
                                        <td><?php echo e(number_format($detail->weight, 2, ',', '.')); ?></td>
                                        <td><?php echo e(number_format($detail->volume, 2, ',', '.')); ?></td>
                                    </tr>
                                <?php endif; ?>
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