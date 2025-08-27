<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
    <ul class="progressbar">
        <li>Buat <?php if($type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?></li>
        <li class="active">Tambah Item</li>
        <li>Complete</li>
    </ul>
</div>
<h1>Edit <?php if($type=='inbound'): ?> STOI <?php else: ?> STOO <?php endif; ?> #<?php echo e($advanceNotice->code); ?>

  <?php if($advanceNotice->status == 'Processed' && $total_qty_items > 0): ?>
      <?php if(Auth::user()->hasRole('WarehouseSupervisor') || Auth::user()->hasRole('CargoOwner')): ?>
      <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target='#modal-otp'>
        <i class="fa fa-check"></i> <?php echo e(Auth::user()->hasRole('CargoOwner') ? 'Submit' : 'Complete'); ?>

      </button>
      <?php endif; ?>
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
      <?php echo $__env->make('stock_transfer_order.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            <?php if($advanceNotice->status != 'Completed'): ?>
              <div class="btn-group" role="group">
                <a href="<?php echo e(url('advance_notice_details_sto/create/'.$advanceNotice->id)); ?>" type="button" class="btn btn-success" title="Create">
                  <i class="fa fa-plus"></i> Tambah Barang
                </a>
              </div>
            <?php endif; ?>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-tools -->
      </div>
    </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="data-table table table-bordered table-hover no-margin item-transaction-table" width="100%">
              <thead>
                  <tr>
                    <th>ID:</th>
                    <th>Item SKU:</th>
                    <th>Item Name:</th>
                    <th>Group Ref:</th>
                    <th>Qty:</th>
                    <th>UOM:</th>
                    <th>Weight:</th>
                    <th>Volume:</th>
                    <th></th>
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
                      <td>
                        <?php if($advanceNotice->status != 'Completed' && $advanceNotice->status != 'Canceled'): ?>
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(url('advance_notice_details_sto/'.$detail->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                                <form action="<?php echo e(url('advance_notice_details_sto', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                </form>
                            </div>
                        </div>
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

<?php echo $__env->make('otp', ['url' => route('stock_transfer_order.completed', ['advance_notice' => $advanceNotice->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>