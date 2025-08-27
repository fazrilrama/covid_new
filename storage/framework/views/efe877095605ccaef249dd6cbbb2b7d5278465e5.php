<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan AIN AON</h1>
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
            </div>
        </div>
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouse" class="form-control select2" required="">
                    <option value="0" selected disabled>--Pilih Warehouse--</option>
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" id="selected_branch" value="<?php echo e(!empty($data['branch_id']) ? $data['branch_id'] : ''); ?>">
    <input type="hidden" id="selected_warehouse" value="<?php echo e(!empty($data['warehouse_id']) ? $data['warehouse_id'] : ''); ?>">

    <input type="hidden" id="report_branch" value="<?php echo e(route('report_brach')); ?>">
    <input type="hidden" id="report_warehouse" value="<?php echo e(route('report_warehouse')); ?>">
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="date_to" class="col-sm-4 col-form-label">Type</label>
                                    <div class="col-sm-8">
                                    <select name="type" id="type" class="form-control select2" required="">
                                        <option value="inbound" <?php if($data['type'] == 'inbound'): ?> <?php echo e('selected'); ?> <?php endif; ?> >Inbound</option>
                                        <option value="outbound" <?php if($data['type'] == 'outbound'): ?> <?php echo e('selected'); ?> <?php endif; ?>>Outbound</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <?php if($search): ?>
    <div class="table-responsive">
	    <table class="data-table table table-bordered table-hover no-margin" width="100%">
	    
	        <thead>
	            <tr>
                    <th>ID#:</th>
	                <th><?php if($data['type']=='inbound'): ?> AIN <?php else: ?> AON <?php endif; ?>:</th>
                    <th>Storage Area:</th>
	                <th>Origin:</th>
                    <th>Destination:</th>
	                <th>Outstanding:</th>
	                <th>Status:</th>
                    <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
	                <th>Receiving Status:</th>
                    <?php endif; ?>
                    
	                <th></th>
	            </tr>
	        </thead>
	        
	        <tbody>
	        <?php if(isset($collections)): ?>
            <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($item->item_outstanding > 0): ?>
	            <tr>
                    <td><?php echo e($item->id); ?></td>
	                <td><?php echo e($item->item_code); ?></td>
                    <td>
                        <?php echo e((isset($item->item_storage_area)) ? $item->item_storage_area : '-'); ?>

                    </td>
	                <td><?php echo e(!empty($item->item_origin) ? $item->item_origin : ''); ?></td>
	                <td><?php echo e(!empty($item->item_destination) ? $item->item_destination : ''); ?></td>
	                <td align="center">
                        <?php echo e(number_format($item->item_outstanding, 2, ',', '.')); ?>

                        <p>
                            <select>
                                <?php $__currentLoopData = $detail_advance_notices[$item->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option>
                                        <?php echo e($detail->item->name); ?> | <?php echo e($detail->ref_code); ?> | ots: <?php echo e($detail->detail_outstanding); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </p>
                    </td>
	                <td>
                        <?php if(Auth::user()->hasRole('CargoOwner')): ?>
                            <?php echo e(($item->item_status == 'Pending' ? 'Planning' : ($item->item_status == 'Completed' ? 'Submitted' : $item->item_status))); ?>

                        <?php else: ?>
                            <?php echo e(($item->item_status == 'Pending' ? 'Planning' : $item->item_status )); ?>

                        <?php endif; ?>
                    </td>
                    <?php if(!Auth::user()->hasRole('CargoOwner')): ?>
    	                <td width="15%">
                            <?php echo e(($item->item_outstanding == 0  && ($item->item_status == 'Completed' || $item->item_status == 'Closed') ? 'Full' : 'Partial')); ?>

                        </td>
                    <?php endif; ?>
                    
	                <td>
	                	<div class="btn-toolbar">
	                		
	                    </div>
	                </td>
	            </tr>
                <?php endif; ?>
	        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
	      </tbody>
	    </table>
    <?php endif; ?>
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
                    $("#warehouse").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        if($('#selected_warehouse').val() == value.id) {
                            $("#warehouse").append("<option value='"+value.id+"' selected>"+value.code +' - ' +value.name+"</option>");
                        } else {
                            $("#warehouse").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                        }
                    });
                }
            });
        }
        $('#branch').change(function() {
            $("#warehouse").empty();
            $.ajax({
                url: $('#report_warehouse').val(),
                method: "GET",
                data : {
                    branch_id : $("#branch").val()
                },
                dataType: 'json',
                success: function(data) {
                    $("#warehouse").append("<option value='0' selected>--Semua Warehouse--</option>");
                    $.each(data,function(i, value){
                        $("#warehouse").append("<option value='"+value.id+"'>"+value.code +' - ' +value.name+"</option>");
                    });
                }
            });
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>