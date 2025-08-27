<div class="table-responsive">
  <table class="data-table table table-bordered table-hover no-margin" width="100%">

      <thead>
          <tr>
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
              <td><?php echo e($detail->item->sku); ?></td>
              <td><?php echo e($detail->item->name); ?></td>
              <td><?php echo e($detail->ref_code); ?></td>
              <td><?php echo e($detail->qty); ?></td>
              <td><?php echo e($detail->uom->name); ?></td>
              <td><?php echo e($detail->weight); ?></td>
              <td><?php echo e($detail->volume); ?></td>
              <td>
                <?php if($advanceNotice->status != 'Completed' && $advanceNotice->status != 'Canceled'): ?>
                <div class="btn-toolbar">
                    <div class="btn-group" role="group">
                        <a href="<?php echo e(url('advance_notice_details/'.$detail->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <div class="btn-group" role="group">
                        <form action="<?php echo e(url('advance_notice_details', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
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