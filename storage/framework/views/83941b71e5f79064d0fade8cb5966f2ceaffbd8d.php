<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Stock On Location</h1>
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
            <label for="storage" class="col-sm-4 col-form-label">Storage</label>
            <div class="col-sm-8">
                <select name="storage" id="storage" class="form-control select2">
                </select>
                <input type="hidden" id="selected_storage" value="<?php echo e(!empty($data['storage']) ? $data['storage'] : ''); ?>">
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
                    <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export Excel</button>
                    <button class="btn btn-sm btn-warning" name="submit" value="2"><i class="fa fa-download"></i> Export ke PDF</button>
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
    <table class=table no-margin" width="100%">
        <thead>
            <tr>
                <th rowspan="3" style="text-align: center"><p><h4>Branch: <?php echo e($warehouse->branch_name); ?></h4><th>
                <th rowspan="3" style="text-align: center"><p><h4>Warehouse: <?php echo e($warehouse->name); ?></h4></p><th>
                <th rowspan="3" style="text-align: center"><p><h4>Project: <?php echo e($warehouse->project_name); ?></h4></p><th>
            </tr>
        </thead>
    </table>

    
    <table class=table table-bordered table-hover no-margin" width="100%" id="warehouse-stock">
        <thead>
            <tr>
                <th colspan="8" style="text-align: center">Storage Gudang Saat Ini</th>
            </tr>
            <tr>
                <th>SKU:</th>
                <th>Nama SKU:</th>
                <th>Storage</th>
                <th>Stock</th>
                <th>UoM</th>
            </tr>
        </thead>
            
           
        <tbody>

      </tbody>
      <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th colspan="2" id="total_stock"></th>
        </tr>
      </tfoot>
    </table>

</div>
<?php endif; ?>
    <input type="hidden" id="report_item_project" value="<?php echo e(route('warehouse_item_project')); ?>">
    <input type="hidden" id="report_storage_warehouse" value="<?php echo e(route('api.storage')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script>
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
            $.ajax({
                url: $('#report_storage_warehouse').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val(),
                },
                success: function(data) { 
                    $.each(data,function(i, value){
                        $("#storage").append("<option value='"+value.id+"'>"+value.code+"</option>");
                    });
                }
            })
        });
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
        if($('#selected_storage').val() != '') {
            $.ajax({
                url: $('#report_storage_warehouse').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#selected_warehouse").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#storage").append("<option value='all' selected>Semua Storage</option>");
                    $.each(data,function(i, value){
                        if($('#selected_storage').val() == value.id) {
                            $("#storage").append("<option value='"+value.id+"' selected>"+value.code+"</option>");
                        } else {
                            $("#storage").append("<option value='"+value.id+"'>"+value.code+"</option>");
                        }
                    });
                }
            });
        }
    </script>
    <script>
        var sum = 0;
        number_format = function (number, decimals, dec_point, thousands_sep) {
            number = number.toFixed(decimals);

            var nstr = number.toString();
            nstr += '';
            x = nstr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? dec_point + x[1] : '';
            var rgx = /(\d+)(\d{3})/;

            while (rgx.test(x1))
                x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

            return x1 + x2;
        }
        var table = $('#warehouse-stock').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#warehouse-stock-management').val(),
                "data": function (d) {
                    d.id="";
                    d.warehouse_id = $("#selected_warehouse").val();
                    d.date_to = $("#date_to").val();
                    d.date_from = $("#date_from").val();
                    d.item = $('#selected_item').val();
                    d.storage = $('#selected_storage').val();
                }
            }
            ,"order": [[ 0, "asc" ]]
            ,'columns': [
                {
                    render: function (data, type, full, meta) {
                        return full.sku;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.sku_name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.storages;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return number_format(full.stock,2,',','.');
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.uom_name;
                    }
                },
                
            ],
            "initComplete":function( settings, json){
                for(var i = 0; i < json.data.length; i++){
                    sum += parseFloat(json.data[i].stock)
                }
                $('#total_stock').text(number_format(sum,2,',','.'));
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>