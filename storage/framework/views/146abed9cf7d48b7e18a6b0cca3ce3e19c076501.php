<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Project Gudang</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startSection('additional_field'); ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="branch" class="col-sm-4 col-form-label">Cabang</label>
            <div class="col-sm-8">
                <select name="branch" id="branch" class="form-control select2">
                    <option value="semua" selected>--Semua Divre--</option>
                </select>
            </div>
        </div>
        <input type="hidden" id="selected_branch" value="<?php echo e(!empty($data['branch']) ? $data['branch'] : ''); ?>">
        <input type="hidden" id="selected_status" value="<?php echo e(!empty($data['status']) ? $data['status'] : ''); ?>">
    
    </div>
    <input type="hidden" id="report_branch" value="<?php echo e(route('report_brach')); ?>">
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
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-download"></i> Export ke Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <?php if($search): ?>
    <div class="table-responsive">
        <table id="warehouse-project" class="data-table table table-bordered table-hover no-margin" width="100%">
            <thead>
                <tr>
                    <th>Kode Gudang</th>
                    <th>Nama Gudang</th>
                    <th>Region</th>
                    <th>Branch</th>
                    <th>Project</th>
                    <th>Status
                    <select name="status" id="status" class="form-control select2">
                        <option value="" selected>--Semua Status--</option>
                        <option value="milik">Milik</option>
                        <option value="sewa">Sewa</option>
                        <option value="manajemen">Manajemen</option>
                    </select>
                    </th>
                </tr>
            </thead>
        
            <tbody> 
                
            </tbody>
        </table>
    </div>
    <input type="hidden" value="<?php echo e(route('api.project_management')); ?>" id="project_management">
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
    </script>
    <script>
        var table = $('#warehouse-project').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#project_management').val(),
                "data": function (d) {
                    d.id="";
                    d.branch = $("#selected_branch").val();
                    d.status = $("#selected_status").val();
                }
            }
            ,"order": [[ 0, "desc" ]]
            ,'columns': [
                {
                    render: function (data, type, full, meta) {
                        return full.code;

                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.warehouse_name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.province_name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.branch_name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.project_name;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        return full.ownership;
                    }
                },  
            ]
        });

        $('#status').on('change', function(){
            table.column(5).search(this.value).draw();   
            $('.input-sm').val('');
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>