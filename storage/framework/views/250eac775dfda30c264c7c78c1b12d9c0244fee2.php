<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Stock Per Lokasi Detail</h1>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php if(!empty($additionalMessage)): ?>
<div class="alert alert-<?php echo e($additionalError ? 'danger' : 'info'); ?>">
    <?php echo e($additionalMessage); ?>

</div>
<?php endif; ?>
<?php $__env->startSection('additional_field'); ?>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse_id" id="warehouses" class="form-control select2">
                </select>
                <input type="hidden" id="selected_warehouse" value="<?php echo e($data['warehouse_id']); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Item</label>
            <div class="col-sm-8">
                <select name="item" id="item" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_item" value="<?php echo e(!empty($data['item']) ? $data['item'] : ''); ?>">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="storage" class="col-sm-4 col-form-label">Group By</label>
            <div class="col-sm-8">
                <select name="group_by" id="group_by" class="form-control select2" required>
                    <option value="" selected disabled>-Pilih Group-</option>
                    <option value="storage" <?php echo e(!empty($data['group_by']) && $data['group_by'] == 'storage' ? 'selected' : ''); ?>>Storage</option>
                    <option value="control_date" <?php echo e(!empty($data['group_by']) && $data['group_by'] == 'control_date' ? 'selected' : ''); ?>>Control Date</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="storage" class="col-sm-4 col-form-label">Detail</label>
            <div class="col-sm-8">
                <select name="result" class="form-control select2" id="result" required>
                </select>
                <input type="hidden" id="selected_group_by" value="<?php echo e(!empty($data['group_by']) ? $data['group_by'] : ''); ?>">
                <input type="hidden" id="selected_result" value="<?php echo e(!empty($data['result']) ? $data['result'] : ''); ?>">

            </div>
        </div>
    </div>
    
    <input type="hidden" id="report_warehouse" value="<?php echo e(route('report_warehouse')); ?>">
    <input type="hidden" id="warehouse-stock-management" value="<?php echo e(route('api.storage_location')); ?>">
<?php $__env->stopSection(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Pencarian Data</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <form method="GET" class="form-horizontal">
                <div class="box-body">
                    <div class="row">
                        <?php echo $__env->yieldContent('additional_field'); ?>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                    <!-- <?php if(isset($print_this)): ?>
                        <a href="JavaScript:poptastic('/report/stock_mutation/print')" type="button" class="btn btn-sm btn-warning pull-right" style="margin-right: 10px" title="Cetak Tally Sheet">
                            <i class="fa fa-print"></i> Cetak Mutasi Hari Ini
                        </a>
                    <?php endif; ?> -->
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($search): ?>
<div class="table-responsive">
    <?php if($data['warehouse_id'] != 'all'): ?>
    <table class="table no-margin" width="100%">
        <thead>
            <tr>
                <td colspan="2" style="vertical-align : middle;text-align:center;" width="100%"><h4>Laporan Stock Detail Per Lokasi</h4><br><b><?php echo e($warehouse ? $warehouse->name : ''); ?></b></td>
               
            </tr>
        </thead>
    </table>
    <?php endif; ?>

    
    <table class="table table-bordered table-hover no-margin warehouse-stock" width="100%">
        <thead>
            <tr>
                <th style="vertical-align : middle;text-align:center;"><?php echo e($data['group_by'] == 'storage' ? 'Storage' : 'Control Date'); ?></th>
                <th style="vertical-align : middle;text-align:center;"><?php echo e($data['group_by'] == 'storage' ? 'Control Date' : 'Storage'); ?></th>
                <th style="vertical-align : middle;text-align:center;">Age</th>
                <th style="vertical-align : middle;text-align:center;">SKU:</th>
                <th style="vertical-align : middle;text-align:center;">Nama SKU:</th>
                <th style="vertical-align : middle;text-align:center;">Ref Code:</th>
                <th style="vertical-align : middle;text-align:center;">Stock:</th>
                <th style="vertical-align : middle;text-align:center;">UoM:</th>
            </tr>
            
        </thead>
            
           
        <tbody>
            <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key); ?></td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($data['group_by'] == 'storage' ? $item['control_date'] : $item['storage']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e(\Carbon\Carbon::now()->diffInDays($item['control_date'])); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($item['sku']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($item['sku_name']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($item['ref_code']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($item['stock']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                    <td>
                        <table class="table" width="100%">
                        <?php $__currentLoopData = $collection['item']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e($item['uom_name']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>

</div>
<?php endif; ?>
    <input type="hidden" id="report_item_project" value="<?php echo e(route('warehouse_item_project')); ?>">
    <input type="hidden" id="report_storage_warehouse" value="<?php echo e(route('api.storage_locations')); ?>">
    <input type="hidden" id="report_control_date_warehouse" value="<?php echo e(route('api.control_date')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script>
        var table = $('.warehouse-stock').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'print',
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL' 
                }
            ],
            "order": [[ 0, "asc" ]],
            "pagingType": "full_numbers"
        });
        $.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $("#warehouses").append("<option value='' selected disabled>Pilih Warehouse</option>");
                $.each(data,function(i, value){
                    if($('#selected_warehouse').val() != 'all' && $('#selected_warehouse').val() == value.id){
                        $("#warehouses").append("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#warehouses").append("<option value='"+value.id+"'>"+value.name+"</option>");   
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
        
        $('#warehouses').on('change',function() {
            // alert($('#report_item_project').val());
            $("#item").empty();
            $("#item").append("<option value='all' selected>Semua Item</option>");
            $("#storage").empty();
            $("#storage").append("<option value='all' selected>Semua Storage</option>");
            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val(),
                },
                success: function(data) { 
                    $.each(data,function(i, value){
                        $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                    });
                }
            });
            if($('#group_by').val() == 'storage') {
                $('#result').empty();
                $.ajax({
                    url: $('#report_storage_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Storage-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value.id+"'>"+value.code+"</option>");
                        });
                    }
                })
            } else if($('#group_by').val() == 'control_date') {
                $('#result').empty();
                $.ajax({
                    url: $('#report_control_date_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Control Date-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value+"'>"+value+"</option>");
                        });
                    }
                })
            }
        });
        $('#item').on('change', function(){
            if($('#group_by').val() == 'storage') {
                $('#result').empty();
                $.ajax({
                    url: $('#report_storage_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                        item_id : $("#item").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Storage-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value.id+"'>"+value.code+"</option>");
                        });
                    }
                })
            } else if($('#group_by').val() == 'control_date') {
                $('#result').empty();
                $.ajax({
                    url: $('#report_control_date_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                        item_id : $("#item").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Control Date-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value+"'>"+value+"</option>");
                        });
                    }
                })
            }
        })
        $('#group_by').on('change', function(){
            if($('#group_by').val() == 'storage') {
                $('#result').empty();
                $.ajax({
                    url: $('#report_storage_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Storage-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value.id+"'>"+value.code+"</option>");
                        });
                    }
                })
            } else {
                $('#result').empty();
                $.ajax({
                    url: $('#report_control_date_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#warehouses").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Control Date-</option>");
                        $.each(data,function(i, value){
                            $("#result").append("<option value='"+value+"'>"+value+"</option>");
                        });
                    }
                })
            }
        })
        if($('#selected_item').val() != '') {
            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#selected_warehouse").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#item").append("<option value='all' selected>Semua Item</option>");
                    $.each(data,function(i, value){
                        if($('#selected_item').val() == value.id) {
                            $("#item").append("<option value='"+value.id+"' selected>"+value.sku +' - ' +value.name+"</option>");
                        } else {
                            $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        if($('#selected_group_by').val() != '') {
            if($('#selected_group_by').val() == 'storage') {
                $.ajax({
                    url: $('#report_storage_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#selected_warehouse").val(),
                        item_id : $("#selected_item").val(),
                    },
                    success: function(data) { 
                        // alert();
                        $("#result").append("<option value='all' selected>-Semua Storage-</option>");
                        $.each(data,function(i, value){
                            if($('#selected_result').val() == value.id) {
                                $("#result").append("<option value='"+value.id+"' selected>"+value.code+"</option>");
                            } else {
                                $("#result").append("<option value='"+value.id+"'>"+value.code+"</option>");
                            }
                        });
                    }
                })
            } else {
                $.ajax({
                    url: $('#report_control_date_warehouse').val(),
                    method: "GET",
                    data : {
                        warehouse_id : $("#selected_warehouse").val(),
                        item_id : $("#selected_item").val(),
                    },
                    success: function(data) { 
                        $("#result").append("<option value='all' selected>-Semua Control Date-</option>");
                        $.each(data,function(i, value){
                            if($('#selected_result').val() == value) {
                                $("#result").append("<option value='"+value+"' selected>"+value+"</option>");   
                            } else {
                                $("#result").append("<option value='"+value+"'>"+value+"</option>");
                            }
                        });
                    }
                })
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>