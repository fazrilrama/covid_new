<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container">
      <ul class="progressbar">
          <li class="active">Buat Good Issues</li>
          <li>Complete</li>
      </ul>
    </div>
    <h1>Create Goods Issue</h1>
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
			<?php echo $__env->make('form_confirmation', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
    $(document).ready(function () {
        $('select[name="stock_entry_id"]').change(function() {
            var id = $(this).val();
            console.log(id);
            $.ajax({
                type: "GET",
                contentType: "application/json; charset=utf-8",
                dataType: "",
                url: "/stock_transports/" + id + "/json",
                success: function (response) {
                    console.log(response);
                    if (!response.error) {
                        $('select[name="transport_type_id"]').val(response.stock_transport_id);
                        $('input[name="ref_code"]').val(response.ref_code);
                        $('input[name="vehicle_code_num"]').val(response.vehicle_code_num);
                        $('input[name="vehicle_plate_num"]').val(response.vehicle_plate_num);
                        $('select[name="origin_id"]').val(response.origin_id);
                        $('input[name="etd"]').val(response.etd);
                        $('input[name="eta"]').val(response.eta);
                        $('select[name="shipper_id"]').val(response.shipper_id);
                        $('input[name="shipper_address"]').val(response.shipper_address);
                        $('select[name="destination_id"]').val(response.destination_id);
                        $('select[name="consignee_id"]').val(response.consignee_id);
                        $('input[name="consignee_address"]').val(response.consignee_address);
                        $('input[name="employee_name"]').val(response.employee_name);
                        /* $('select[name="ref_code"]').val(response.ref_code);
                        $('select[name="ref_code"]').val(response.ref_code); */
                    }
                }
            });
        });
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>