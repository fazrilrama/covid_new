<?php if($type == 'outbound' && \Request::route()->getName() != 'stock_deliveries.create'): ?>
<div class="box box-default">
    <div class="box-header with-border">
        <div class="box-header">
            <h3 class="box-title">DO Info</h3>
            <br><br>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <!-- /.box-tools -->
            <form id="changeAttributeGoodReceiving" action="<?php echo e($action); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
            <?php if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) ): ?>
                <?php echo e(method_field($method)); ?>

            <?php endif; ?>
            <?php echo csrf_field(); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="do_number" class="col-sm-3 control-label">DO Number</label>
                                <div class="col-sm-9">
                                    <input type="text" name="do_number" id="do_number" class="form-control" placeholder="Do Number" value="<?php echo e(old('do_number', $stockDelivery->do_number)); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="do_attachment" class="col-sm-3 control-label">DO Attachment</label>
                                <div class="col-sm-9">
                                    <input type="file" name="do_attachment" class="form-control" placeholder="SPTB" value="<?php echo e(old('do_attachment', $stockDelivery->do_attachment)); ?>">
                                    <?php if(!empty($stockDelivery->do_attachment)): ?>
                                    <a href="<?php echo e(\Storage::disk('public')->url($stockDelivery->do_attachment)); ?>" target="_blank">See Attachment</a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="driver_name" class="col-sm-3 control-label">Driver Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Driver Name" value="<?php echo e(old('driver_name', $stockDelivery->driver_name)); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="driver_phone" class="col-sm-3 control-label">Driver Phone</label>
                                <div class="col-sm-9">
                                    <input type="number" name="driver_phone" id="driver_phone" class="form-control" placeholder="Driver Phone" value="<?php echo e(old('driver_phone', $stockDelivery->driver_phone)); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="driver_phone" class="col-sm-3 control-label">Sim Number</label>
                                <div class="col-sm-9">
                                    <input type="number" name="sim_number" id="sim_number" class="form-control" placeholder="Sim Number" value="<?php echo e(old('sim_number', $stockDelivery->sim_number)); ?>" >
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="police_number" class="col-sm-3 control-label">Police Number</label>
                                <div class="col-sm-9">
                                    <input type="text" name="police_number" id="police_number" class="form-control" placeholder="Police Number" value="<?php echo e(old('police_number', $stockDelivery->police_number)); ?>" >
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <label for="wp_number" class="col-sm-3 control-label">Weight of Proof Number</label>
                                <div class="col-sm-9">
                                    <input type="text" name="wp_number" id="wp_number" class="form-control" placeholder="Weight of Proof Number" value="<?php echo e(old('wp_number', $stockDelivery->wp_number)); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fleet_arrived" class="col-sm-3 control-label">Fleet of Arrival</label>
                                <div class="col-sm-9">
                                    <input type="text" name="fleet_arrived" id="fleet_arrived" class="form-control" placeholder="Fleet of Arrival" value="<?php echo e(old('fleet_arrived', $stockDelivery->fleet_arrived)); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="unloading_start" class="col-sm-3 control-label">loading Start</label>
                                <div class="col-sm-9">
                                    <input type="text" name="unloading_start" id="unloading_start" class="form-control" placeholder="Unloading Start" value="<?php echo e(old('unloading_start', $stockDelivery->unloading_start)); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="unloading_end" class="col-sm-3 control-label">loading Finish</label>
                                <div class="col-sm-9">
                                    <input type="text" name="unloading_end" id="unloading_end" class="form-control" placeholder="Unloading Finish" value="<?php echo e(old('unloading_end', $stockDelivery->unloading_end)); ?>" readonly>
                                </div>
                            </div>
                            <?php if(session()->get('current_project')->id == '337'): ?>
                            <div class="form-group">
                                <label for="payment_method" class="col-sm-3 control-label">Payment Method</label>
                                <div class="col-sm-9">
                                <select name="payment_method" id="cmbpayment" required class="form-control">
                                    <option value="" disabled selected>Pilih Payment</option>
                                    <option value="lunas" <?php echo e($stockDelivery->type_payment == 'lunas' ? 'selected' : ''); ?>>Lunas</option>
                                    <option value="cod" <?php echo e($stockDelivery->type_payment == 'cod' ? 'selected' : ''); ?>>COD</option>
                                </select>
                                </div>
                            </div>
                                <?php if($stockDelivery->type_payment == 'cod'): ?>
                                <div class="form-group" id="sisa_tagihan">
                                    <label for="payment_method" class="col-sm-3 control-label">Sisa Tagihan</label>
                                    <div class="col-sm-9">
                                        <input type="number" value="<?php echo e(old('sisa_tagihan', $stockDelivery->remaining_total)); ?>" name="sisa_tagihan" id="number_sisa_tagihan" class="form-control">
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="form-group" id="sisa_tagihan">
                                    <label for="payment_method" class="col-sm-3 control-label">Sisa Tagihan</label>
                                    <div class="col-sm-9">
                                        <input type="number" value="0" name="sisa_tagihan" id="number_sisa_tagihan" class="form-control">
                                    </div>
                                </div>
                                <?php endif; ?>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-info btn-completed" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>
<?php endif; ?>