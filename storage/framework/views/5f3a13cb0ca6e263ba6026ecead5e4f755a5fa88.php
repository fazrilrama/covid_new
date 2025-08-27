<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
    <ul class="progressbar">
        <li>Buat <?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?></li>
        <?php if($stockTransport->status == 'Completed'): ?>
          <li>Tambah Item</li>
          <li class="active">Complete</li>
        <?php else: ?>
          <li class="active">Tambah Item</li>
          <li>Complete</li>
        <?php endif; ?>

    </ul>
</div>
<h1>Edit <?php if($type=='inbound'): ?> Goods Receiving <?php else: ?> Delivery Plan <?php endif; ?> #<?php echo e($stockTransport->code); ?>

  <?php if($stockTransport->status == 'Processed'): ?>
    <a href="JavaScript:poptastic('<?php echo e(route('stock_transports.print', ['stock_transport' => $stockTransport->id])); ?>')" type="button" class="btn btn-warning pull-right" title="Cetak Tally Sheet">
        <i class="fa fa-download"></i> Cetak
    </a>
  <?php endif; ?>
  <?php if($stockTransport->status == 'Processed' && $stockTransport->type == 'inbound' && $jumlah_item_transport_no_actual == 0 && $jumlah_item_transport_no_actual_koli > 0 && $stockTransport->do_number != null && $stockTransport->police_number != null): ?>
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'><i class="fa fa-check"></i> Complete</button>
  <?php endif; ?>

  <!-- jika outbound tidak perlu pengecekan data actual -->
  <?php if($stockTransport->status == 'Processed' && $stockTransport->type == 'outbound'): ?>
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'><i class="fa fa-check"></i> Complete</button>
  <?php endif; ?>
</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Data</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <?php echo $__env->make('stock_transports.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    <?php echo $__env->make('stock_transports.do_info', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        <?php if($stockTransport->status == 'Processed' || $stockTransport->status == 'Pending'): ?>
        <div class="box-tools pull-right">
            <div class="btn-toolbar">
                <?php if($stockTransport->is_sent == 0): ?>
                <div class="btn-group" role="group">
                    <a href="<?php echo e(url('stock_transport_details/create/'.$stockTransport->id)); ?>" type="button" class="btn btn-success" title="Create">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </a>
                </div>
                <?php endif; ?>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                  <?php if($stockTransport->type == 'inbound'): ?>
                  <th>Control Date:</th>
                  <th>Expired Date:</th>
                  <?php endif; ?>
                  <th>Koli:</th>
                  <?php if($stockTransport->type == 'inbound'): ?>
                      <th>Plan Qty Bruto:</th>
                      <th>Actual Qty Bruto:</th>
                  <?php else: ?>
                      <th>Actual Qty Bruto:</th>
                  <?php endif; ?>
                  <th>Netto</th>
                  <th>UOM:</th>
                  <?php if($stockTransport->type == 'inbound'): ?>
                      <th>Actual Weight:</th>
                      <th>Actual Volume:</th>
                  <?php endif; ?>
                  <th></th>
              </tr>
              </thead>

              <tbody>
              <?php $__currentLoopData = $stockTransport->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                      <td><?php echo e($detail->item->sku); ?></td>
                      <td><?php echo e($detail->item->name); ?></td>
                      <td><?php echo e($detail->ref_code); ?></td>
                      <?php if($stockTransport->type == 'inbound'): ?>
                      <td><?php echo e($detail->control_date); ?></td>
                      <td><?php echo e($detail->expired_date ?? '-'); ?></td>
                      <?php endif; ?>
                      <td><?php echo e(number_format($detail->koli, 0, ',', '.')); ?></td>
                      <td><?php echo e(number_format($detail->plan_qty, 2, ',', '.')); ?></td>
                      <?php if($stockTransport->type == 'inbound'): ?>
                          <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
                      <?php endif; ?>
                      <td><?php echo e(number_format($detail->netto, 2, ',', '.')); ?></td>
                      <td><?php echo e($detail->uom->name); ?></td>
                      <?php if($stockTransport->type == 'inbound'): ?>
                          <td><?php echo e(number_format($detail->weight, 2, ',', '.')); ?></td>
                          <td><?php echo e(number_format($detail->volume, 2, ',', '.')); ?></td>
                      <?php endif; ?>
                      <td>
                        <div class="btn-toolbar">
                           <?php if($stockTransport->editable): ?>
                            <?php if($stockTransport->is_sent != 1): ?>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(url('stock_transport_details/'.$detail->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                  <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                                <form action="<?php echo e(url('stock_transport_details', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                  <input type="hidden" name="_method" value="DELETE">
                                  <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                  <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                </form>
                            </div>
                            <?php else: ?>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(url('stock_transport_details/'.$detail->id.'/show')); ?>" type="button" class="btn btn-primary" title="Show">
                                  <i class="fa fa-eye"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                           <?php endif; ?>
                          </div>
                      </td>
                  </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</div>

<?php echo $__env->make('otp', ['url' => route('stock_transports.completed', ['stock_transport' => $stockTransport->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('send-foms', ['url' => route('stock_transports.sendapistocktransport', ['stock_transport' => $stockTransport->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<!-- <?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
            $('.stock-completed').click(function() {
                $('#modal-otp').modal();
            });
        });        
    </script>
<?php $__env->stopSection(); ?> -->

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>