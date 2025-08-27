<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Edit Goods Issue #<?php echo e($stockDelivery->code); ?>

    <a href="JavaScript:poptastic('<?php echo e(url('/stock_deliveries/'.$stockDelivery->id.'/print')); ?>')" type="button" class="btn btn-warning pull-right" title="Cetak"><i class="fa fa-download"></i> Cetak</a>
    <?php if(session()->get('current_project')->id == '337' && $stockDelivery->driver_name != null && $stockDelivery->police_number != null && $stockDelivery->type_payment != null): ?>
      <?php if($stockDelivery->status == 'Processed'): ?>
      <button type="button" class="btn btn-primary pull-right margin-right stock-completed"><i class="fa fa-check"></i> Completed</button>
      <?php endif; ?>
    <?php else: ?>
      <?php if($stockDelivery->status == 'Processed'): ?>
      <button type="button" class="btn btn-primary pull-right margin-right stock-completed"><i class="fa fa-check"></i> Completed</button>
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
      <?php echo $__env->make('stock_deliveries.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    <?php echo $__env->make('stock_deliveries.do_info', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Barang</h3>
        <div class="box-tools pull-right">
          <div class="btn-toolbar">
            
            <!-- 
            <?php if( ($stockDelivery->status !== 'Completed') || ($stockDelivery->status !== 'Closed') ): ?>
            <div class="btn-group" role="group">
              <a href="<?php echo e(url('stock_delivery_details/create/'.$stockDelivery->id)); ?>" type="button" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Tambah Barang
              </a>
            </div>
            <?php endif; ?> -->

            <!--             <div class="btn-group" role="group">
              <a href="<?php echo e(url('stock_deliveries/copy_details/'.$stockDelivery->id)); ?>" type="button" class="btn btn-primary" title="Copy" onclick="return confirm('Anda akan mengcopy daftar barang dari dokumen referensi, apakah Anda ingin melanjutkan?');">
                <i class="fa fa-download"></i> Copy Barang
              </a>
            </div> -->

            <div class="btn-group" role="group">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
        </div>
        <!-- /.box-tools -->
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
    </div>
        <!-- /.box-tools -->
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="data-table table table-bordered table-hover no-margin" width="100%">
            
                <thead>
                    <tr>
                        <th>Item SKU:</th>
                        <th>Item Name</th>
                        <th>Group Ref:</th>
                        <th>Qty:</th>
                        <th>UOM:</th>
                        <th>Weight:</th>
                        <th>Volume:</th>
                        <th>Control Date:</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <tbody>
                <?php $__currentLoopData = $stockDelivery->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($detail->item->sku); ?></td>
                        <td><?php echo e($detail->item->name); ?></td>
                        <td><?php echo e($detail->ref_code); ?></td>
                        <td><?php echo e($detail->qty); ?></td>
                        <td><?php echo e($detail->uom->name); ?></td>
                        <td><?php echo e($detail->weight); ?></td>
                        <td><?php echo e($detail->volume); ?></td>
                        <td><?php echo e($detail->control_date); ?></td>
                        <!-- <td>
                            <?php if($stockDelivery->status != 'Completed'): ?>
                            <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                    <a href="<?php echo e(url('stock_delivery_details/'.$detail->id.'/edit')); ?>" type="button" class="btn btn-primary" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="btn-group" role="group">
                                    <form action="<?php echo e(url('stock_delivery_details', [$detail->id])); ?>" method="POST" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a></button>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td> -->
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
</div>

<?php echo $__env->make('otp', ['url' => route('stock_deliveries.completed', ['stock_deliveries' => $stockDelivery->id]), 'url_closed' => route('stock_deliveries.closed', ['stock_deliveries' => $stockDelivery->id])], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
          $("#sisa_tagihan").hide();

            $('.stock-completed').click(function() {
                $('#modal-otp').modal();
            });
        });        
        /* $(document).ready(function () {
            $('.stock-completed').click(function() {
                $('#modal-otp-closed').modal();
            });
        }); */
    </script>

    <script type="text/javascript">
        var newwindow;
        function poptastic(url)
        {
            newwindow=window.open(url,'name','height=800,width=1600');
            if (window.focus) {newwindow.focus()}
        }
        $("#cmbpayment").change(function() {
          if($('#cmbpayment').val() == 'cod') {
            $("#sisa_tagihan").show();
            $("#number_sisa_tagihan").prop('required', true);
          } else {
            $('#sisa_tagihan').hide();
            $("#number_sisa_tagihan").val(0);
            $("#number_sisa_tagihan").prop('required', false);
          }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>