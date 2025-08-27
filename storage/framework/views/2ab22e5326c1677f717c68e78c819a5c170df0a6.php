<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="container">
    <ul class="progressbar">
        <li>Buat Internal Movement</li>
        <li class="active">Tambah Item</li>
        <li>Complete</li>
    </ul>
</div>
<h1>Edit Internal Movement
  <?php if($stock_internal_movement->status == 'Processed'): ?>
      <?php if(Auth::user()->hasRole('WarehouseSupervisor') && count($stock_internal_movement->detailmovement) > 0): ?>
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
      <?php echo $__env->make('stock_internal_movement.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
            <?php if($stock_internal_movement->status != 'Completed'): ?>
              <div class="btn-group" role="group">
                <a href="<?php echo e(url('stock_internal_movement_details/create/'.$stock_internal_movement->id)); ?>" type="button" class="btn btn-success" title="Create">
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
                    <th>Origin Storage:</th>
                    <th>Destination Storage:</th>
                    <th>Qty:</th>
                    <th>UOM:</th>
                    <th></th>
                  </tr>
              </thead>
              <tbody>
              <?php $__currentLoopData = $stock_internal_movement->detailmovement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($detail->id); ?></td>
                  <td><?php echo e($detail->item->sku); ?></td>
                  <td><?php echo e($detail->item->name); ?></td>
                  <td><?php echo e($detail->ref_code); ?></td>
                  <td><?php echo e($detail->origin_storage->code); ?></td>
                  <td><?php echo e($detail->dest_storage->code); ?></td>
                  <td><?php echo e($detail->movement_qty); ?></td>
                  <td><?php echo e($detail->uom->name); ?></td>
                  <td>
                  <div class="btn-group" role="group">
                    <a href="<?php echo e(route('stock_internal_movement_details.edit', ['stock_internal_movement_detail' => $detail->id])); ?>" type="button" class="btn btn-warning" title="Edit">
                      <i class="fa fa-pencil"></i>
                    </a>
                  </div>
                  <div class="btn-group" role="group">
                  <form action="<?php echo e(url('stock_internal_movement_details', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                  </form>
                </div>
                  
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


<?php echo $__env->make('otp', ['url' => route('stock_internal_movements.completed', ['internal_movement' => $stock_internal_movement->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>