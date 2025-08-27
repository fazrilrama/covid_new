    <?php if($search): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>

                        <a style="float: right;" href="JavaScript:poptastic('<?php echo e(url('/report/stock_mutation/print')); ?>')" class="btn btn-sm btn-primary">Cetak</a>
                    </div>
                    <div class="box-body">
                        <?php if(!empty($item)): ?>
                        <div class="div-item">
                            <table width="100%">
                                <tr>
                                    <td width='10%'>From</td>
                                    <td width='3%'>:</td>
                                    <td width='87%'><?php echo e(!empty($data['date_from']) ? \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y') : ''); ?></td>
                                </tr>
                                <tr>
                                    <td>To</td>
                                    <td>:</td>
                                    <td><?php echo e(!empty($data['date_to']) ? \Carbon\Carbon::parse($data['date_to'])->format('d/m/Y') : ''); ?></td>
                                </tr>
                            </table>
                            <hr>
                            <table width="60%">
                                <tr>
                                    <td width='33%'>SKU : <?php echo e($item->sku); ?></td>
                                    <td width='33%'>Nama : <?php echo e($item->name); ?></td>
                                    <td width='33%'>UOM : <?php echo e($item->default_uom->name); ?></td>
                                </tr>
                                <!-- <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>UOM</td>
                                    <td>:</td>
                                    <td><?php echo e($item->default_uom->name); ?></td>
                                </tr> -->
                            </table>
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-6 col-sm-offset-6 table-responsive">
                            <table class="data-table table table-bordered table-hover no-margin" width="100%">
                        
                                <thead>
                                    <tr>
                                        <th>Date:</th>
                                        <th>Begining:</th>
                                        <th>Receiving:</th>
                                        <th>Delivery:</th>
                                        <th>Closing:</th>
                                    </tr>
                                </thead>
                            
                                <tbody> 
                                    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $begining = !empty($collection->begining_qty) ? $collection->begining_qty : 0;
                                            $ending = !empty($collection->ending_qty) ? $collection->ending_qty : 0;
                                            $receiving = $collection->type == 'inbound' ? ($ending - $begining) : 0;
                                            $delivery =  $collection->type == 'outbound' ? ($ending - $begining) : 0;
                                        ?>
                                        <tr>
                                            <td><?php echo e($collection->control_date); ?></td>
                                            <td><?php echo e(!empty($collection->begining_qty) ? $collection->begining_qty : 0); ?></td>
                                            <td><?php echo e(abs($receiving)); ?></td>
                                            <td><?php echo e(abs($delivery)); ?></td>
                                            <td><?php echo e(!empty($collection->ending_qty) ? $collection->ending_qty : 0); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <script type="text/javascript">
        window.print();
    </script>