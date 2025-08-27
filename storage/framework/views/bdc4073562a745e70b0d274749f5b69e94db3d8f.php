<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>List Rencana - Sampai Tujuan 
    </h1>
    Tanggal Filter: <code><?php echo e(\Carbon\Carbon::parse($date)->format('d-m-Y')); ?></code>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#menu2">Sisa Pengiriman Hari Sebelumnya</a></li>
  <li><a data-toggle="tab" href="#home">On Delivery</a></li>
  <li><a data-toggle="tab" href="#menu1">Barang Terkirim</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $collections[0]['receiving']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detail->code); ?></td>
                    <td><?php echo e($detail->consignee->name); ?></td>
                    <td><?php echo e($detail->created_at); ?></td>
                
               
                <td>
                    <table class="table" width="100%">
                     <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                        <td><?php echo e($barang->item->name); ?></td>
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </td>
                <td>
                    <table class="table" width="100%">
                     <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                        <td><?php echo e(number_format($barang->qty, 0, ',', '.')); ?></td>
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </td>
                <td>
                    <table class="table" width="100%">
                     <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                        <td><?php echo e($barang->uom->name); ?></td>
                        </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
  </div>
  <div id="menu1" class="tab-pane fade">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $collections[0]['begining']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detail->code); ?></td>
                    <td><?php echo e($detail->consignee->name); ?></td>
                    <td><?php echo e($detail->received_by); ?></td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($barang->item->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e(number_format($barang->qty, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($barang->uom->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
  </div>
  <div id="menu2" class="tab-pane fade in active">
    <div class="table-responsive">
        <table class="table no-margin table-search" width="100%" id="last-date">
            <thead>
            <tr>
                <th  rowspan="2">Kode:</th>
                <th  rowspan="2" >Consignee:</th>
                <th  rowspan="2" >Dibuat Tanggal:</th>
                <th colspan="3" style="text-align: center">Item</th>
            </tr>
            <tr>
                <th>Item:</th>
                <th>Qty:</th>
                <th>UoM:</th>
                
            </tr>
            </thead>

            <tbody>
            <?php $__currentLoopData = $collections[0]['begining_delivery']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detail->code); ?></td>
                    <td><?php echo e($detail->consignee->name); ?></td>
                    <td><?php echo e($detail->created_at); ?></td>

                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($barang->item->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e(number_format($barang->qty, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $detail->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($barang->uom->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
  </div>
</div>
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
    <script>
        var table = $('.table-search').DataTable();
        
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>