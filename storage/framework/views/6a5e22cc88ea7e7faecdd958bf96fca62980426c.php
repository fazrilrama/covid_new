<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Tambah Barang</h1>
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
			<?php echo $__env->make('stock_entry_details.form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script type="text/javascript">
  $(document).ready(function() {
        $('select[name="warehouse_id"]').on('change', function() {
            // $('select[name="warehouse_id"]').attr('disabled', 'true');
            // $('select[name="storage_id"]').attr('disabled', 'true');
            $('.btn-info').attr('disabled', 'true');
            var warehouseId = $(this).val();
            if(warehouseId) {
                $.ajax({
                    url: '/select-warehouse/'+warehouseId,
                    type: "GET",
                    data: {
                        'item_id': $('select[name="item_id"]').val(),
                        'doc_type': $('input[name="doc_type"]').val(),
                    },
                    dataType: "json",
                    success:function(data) {
                        $('select[name="storage_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="storage_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });

                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                    },
                    error:function(error){
                        $('select[name="warehouse_id"]').removeAttr('disabled');
                        $('select[name="storage_id"]').removeAttr('disabled');
                        $('.btn-info').removeAttr('disabled');
                    }
                });
            }else{
                $('select[name="storage_id"]').empty();
            }
        });

        $('.btn-info').attr('disabled', 'true');
        var warehouseId = $(this).val();
        if(warehouseId) {
            $.ajax({
                url: '/select-warehouse/'+warehouseId,
                type: "GET",
                data: {
                    'item_id': $('select[name="item_id"]').val(),
                    'doc_type': $('input[name="doc_type"]').val(),
                },
                dataType: "json",
                success:function(data) {
                    $('select[name="storage_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="storage_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });

                    $('select[name="warehouse_id"]').removeAttr('disabled');
                    $('select[name="storage_id"]').removeAttr('disabled');
                    $('.btn-info').removeAttr('disabled');
                },
                error:function(error){
                    $('select[name="warehouse_id"]').removeAttr('disabled');
                    $('select[name="storage_id"]').removeAttr('disabled');
                    $('.btn-info').removeAttr('disabled');
                }
            });
        }else{
            $('select[name="storage_id"]').empty();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>