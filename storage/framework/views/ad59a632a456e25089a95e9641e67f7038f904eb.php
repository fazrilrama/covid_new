<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Stock Opnames</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startSection('additional_field'); ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="branch" class="col-sm-4 col-form-label">Cabang</label>
            <div class="col-sm-8">
                <select name="branch" id="branch" class="form-control select2" required="">
                    <option value="" selected disabled>--Pilih Cabang--</option>
                </select>
                <input type="hidden" id="selected_branch" value="<?php echo e(!empty($data['branch_id']) ? $data['branch_id'] : ''); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouses" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_warehouse" value="<?php echo e(!empty($data['warehouse']) ? $data['warehouse'] : ''); ?>">
            </div>
        </div>
       
    </div>
    <input type="hidden" id="report_warehouse" value="<?php echo e(route('report.warehouse_stockOpname')); ?>">
    <?php $__env->stopSection(); ?>

    <?php echo $__env->make('report.searchDateForm', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    

    <?php if($search): ?>
    <div class="table-responsive">
        <table class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Tanggal SO:</th>
                    <th>Divre:</th>
                    <th>Gudang:</th>
                    <th>Anggota:</th>
					<th>Status:</th>
					<th>Detail:</th>
                </tr>
            </thead>
        
            <tbody>
            <?php $__currentLoopData = $stock_opnames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock_opname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
            <td><?php echo e($stock_opname->date); ?></td>
            <td><?php echo e($stock_opname->warehouse ? $stock_opname->warehouse->branch->name : ''); ?></td>
            <td><?php echo e($stock_opname->warehouse ? $stock_opname->warehouse->name : ''); ?></td>
            <td>
            <ul>
                <?php if($stock_opname->calculated_by != null): ?> 
                <?php $__currentLoopData = json_decode($stock_opname->calculated_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calculated): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($calculated); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
            </td>
            <td>
            <?php echo e($stock_opname->status); ?>

            </td>
            <td>
            <a href="<?php echo e(route('stock_opnames.edit', $stock_opname->id)); ?>" type="button" class="btn btn-primary detail_barang"><i class="fa fa-eye"></i></a>
            </td>
            </tr>        
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <input type="hidden" id="report_branch" value="<?php echo e(route('report_brach')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_branch').val(),
            dataType: "json",
            success: function(data){
                // $("#warehouses").empty();
                $.each(data,function(i, value){
                    if($('#selected_branch').val() == value.id) {
                        $("#branch").append("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#branch").append("<option value='"+value.id+"'>"+value.name+"</option>");
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
        if($('#selected_branch').val() != '') {
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#selected_branch").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#warehouses").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        if($('#selected_warehouse').val() == value.id && $('#selected_warehouse').val() != 0) {
                            $("#warehouses").append("<option value='"+value.id+"' selected>"+value.code +' - ' +value.name+"</option>");
                        } else {
                            $("#warehouseS").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        $('#branch').change(function() {
            $("#warehouses").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#branch").val()
                },
                dataType: 'json',
                success: function(data) {
                    $("#warehouses").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        $("#warehouses").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>