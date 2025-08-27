<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('adminlte_css'); ?>
    <style>
    .red {
        background-color: #cc0000 !important;
    }
    .green {
        background-color: #4BB543 !important;
    }
    .orange {
        background-color: #FFC107 !important;
    }
    .table-parent {
        overflow-x: scroll;
    }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Kontrak Gudang</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startSection('additional_field'); ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="branch" class="col-sm-4 col-form-label">Cabang</label>
            <div class="col-sm-8">
                <select name="branch" id="branch" class="form-control select2">
                    <option value="all" selected>--Semua Divre--</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Status Kepemilikan</label>
            <div class="col-sm-8">
                <select name="status" id="status" class="form-control select2" required="">
                    <option value="all" <?php echo e(!empty($data['status'])  && $data['status'] ==  'all'? 'selected' : ''); ?>>--Semua Status--</option>
                    <option value="milik" <?php echo e(!empty($data['status'])  && $data['status'] ==  'milik'? 'selected' : ''); ?>>Milik</option>
                    <option value="sewa" <?php echo e(!empty($data['status'])  && $data['status'] ==  'sewa'? 'selected' : ''); ?>>Sewa</option>
                    <option value="manajemen" <?php echo e(!empty($data['status'])  && $data['status'] ==  'manajemen'? 'selected' : ''); ?>>Manajemen</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Status Operasi</label>
            <div class="col-sm-8">
                <select name="operation" id="operation" class="form-control select2" required="">
                    <option value="all"  <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'all'? 'selected' : ''); ?>>--Semua Status Operasi--</option>
                    <option value="Sewa Lepas" <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'Sewa Lepas'? 'selected' : ''); ?>>Sewa Lepas</option>
                    <option value="Idle" <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'Idle'? 'selected' : ''); ?>>Idle</option>
                    <option value="Tutup" <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'Tutup'? 'selected' : ''); ?>>Tutup</option>
                    <option value="Rusak" <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'Rusak'? 'selected' : ''); ?>>Rusak</option>
                    <option value="Beroperasi" <?php echo e(!empty($data['operation'])  && $data['operation'] ==  'Beroperasi'? 'selected' : ''); ?>>Beroperasi</option>
                    <option value="Kosong" <?php echo e(!empty($data['operation']) && $data['operation'] ==  'Kosong' ? 'selected' : ''); ?>>Belum Memilih</option>
                </select>
            </div>
        </div>
        <input type="hidden" id="selected_branch" value="<?php echo e(!empty($data['branch']) ? $data['branch'] : ''); ?>">
        <input type="hidden" id="selected_status" value="<?php echo e(!empty($data['status']) ? $data['status'] : ''); ?>">
        <input type="hidden" id="selected_operation" value="<?php echo e(!empty($data['operation']) ? $data['operation'] : ''); ?>">

    
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
    <div id="table-parent">
    <div class="table-responsive">
        <table id="warehouse-contract" class="data-table table table-bordered table-hover no-margin" width="125%">
            <thead>
                <tr>
                    <th>Kode Gudang</th>
                    <th>Nama Gudang</th>
                    <th>Region</th>
                    <th>Branch</th>
                    <th>Project</th>
                    <th>Status Gudang</th>
                    <th>Status Operasi</th>
                    <th>No Kontrak </th>
                    <th>Space Allocated </th>
                    <th>Komoditi</th>
                    <th>Masa Kontrak</th>
                    <th>Masa Kontrak (Hari)</th>
                </tr>
            </thead>
        
            <tbody> 
                
            </tbody>
        </table>
    </div>
    </div>
    <input type="hidden" value="<?php echo e(route('api.contract_management')); ?>" id="contract_management">
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
    </script>
    <script>
        var table = $('#warehouse-contract').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#contract_management').val(),
                "data": function (d) {
                    d.id="";
                    d.branch = $("#selected_branch").val();
                    d.status = $("#selected_status").val();
                    d.operation = $("#selected_operation").val();
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
                {
                    render: function (data, type, full, meta) {
                        full.status = full.status != null ? full.status : 'Belum Memilih';
                        return full.status;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        return full.number_contract;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        return full.rented_space + ' m2'
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.commodity_name
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.start_date +' - '+ full.end_date;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        var now = moment(new Date()); //todays date
                        var end = moment(full.end_date); // another date
                        // var duration = moment.duration(now.diff(end));
                        var duration =  end.diff(now, 'days')
                        var log = duration > 0 ? 'Sisa ' + duration + ' Hari': 'Berakhir pada '+ Math.abs(duration) +' Hari Lalu';
                        return log;
                    }
                },  
            ],
            'rowCallback': function(row, data, index){
                // console.log(data)
                if(data.end_of_contract > 30){
                    $(row).addClass('green');
                } else if(data.end_of_contract <= 30 && data.end_of_contract >= 0)
                {
                    $(row).addClass('orange');
                }else {
                    $(row).addClass('red');
                }
            }
        });

        $('#status').on('change', function(){
            table.column(5).search(this.value).draw();   
            $('.input-sm').val('');
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>