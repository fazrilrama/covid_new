<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
      <ul class="progressbar">
          <li><?php echo e(__('lang.create')); ?> <?php if($type=='inbound'): ?> Putaway <?php else: ?> Picking Plan <?php endif; ?></li>
          <?php if($stockEntry->status == 'Completed'): ?>
            <li><?php echo e(__('lang.create')); ?> Item</li>
            <li class="active">Complete</li>
          <?php else: ?>
            <li class="active">Tambah Item</li>
            <li>Complete</li>
          <?php endif; ?>

      </ul>
    </div>
<h1><?php echo e(__('lang.edit')); ?> <?php if($type=='inbound'): ?> Putaway <?php else: ?> Picking Plan <?php endif; ?> #<?php echo e($stockEntry->code); ?>

  <a href="JavaScript:poptastic('<?php echo e(route('stock_entries.print', ['stock_entry' => $stockEntry->id])); ?>')" type="button" class="btn btn-warning pull-right" title="Cetak Putaway List">
      <i class="fa fa-download"></i> <?php echo e(__('lang.print')); ?>

  </a>
  <?php if($stockEntry->status == 'Processed' && $outstanding == 0 && $jumlah_item_entry_no_storage == 0): ?>
    <button type="button" class="btn btn-primary margin-right pull-right btn-completed" data-toggle="modal" href='#modal-otp'>
      <i class="fa fa-check"></i> Complete</button>
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
        <?php echo $__env->make('stock_entries.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo e(__('lang.create')); ?></h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            <?php if($stockEntry->status == 'Processed'): ?>
            <div class="btn-group" role="group">
              <a href="<?php echo e(url('stock_entry_details/create/'.$stockEntry->id)); ?>" type="button" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> <?php echo e(__('lang.create')); ?> Barang
              </a>
            </div>
            <!-- <div class="btn-group" role="group">
              <a href="<?php echo e(url('stock_entries/copy_details/'.$stockEntry->id)); ?>" type="button" class="btn btn-primary" title="Copy" onclick="return confirm('Anda akan mengcopy daftar barang dari dokumen referensi, apakah Anda ingin melanjutkan?');">
                <i class="fa fa-download"></i> Copy Barang
              </a>
            </div> -->
            <?php endif; ?>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        </div>
        <!-- /.box-tools -->
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="item-stock-entry" class="data-table table table-bordered table-hover no-margin" width="100%">

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
                      <th></th>
                  </tr>
              </thead>

              <tbody>
              <?php $__currentLoopData = $stockEntry->details->where('status', '<>', 'canceled'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                    <td>
                      <?php if($detail->status == 'draft'): ?>
                        <?php if($stockEntry->status == 'Pending' || $stockEntry->status == 'Processed'): ?>
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(url('stock_entry_details/'.$detail->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                            <form action="<?php echo e(url('stock_entry_details', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                            </form>
                            </div>
                        </div>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
</div>

<?php echo $__env->make('otp', ['url' => route('stock_entries.status', ['stock_entry' => $stockEntry->id, 'status' => 'Completed'])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>