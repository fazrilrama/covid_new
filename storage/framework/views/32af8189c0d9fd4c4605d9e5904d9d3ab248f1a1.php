<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">


<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Mutasi Stok Per Item</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startSection('additional_field'); ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="warehouse" class="col-sm-4 col-form-label">Warehouse</label>
            <div class="col-sm-8">
                <select name="warehouse" id="warehouses" class="form-control select2" required="">
                </select>
                <input type="hidden" id="selected_warehouse" value="<?php echo e(!empty($data['warehouse']) ? $data['warehouse'] : ''); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="item" class="col-sm-4 col-form-label">Item</label>
            <div class="col-sm-8">
                <select name="item" id="item" class="form-control select2" required="">
                <option value="all">--Semua Item--</option>
                </select>
                <input type="hidden" id="selected_item" value="<?php echo e(!empty($data['item']) ? $data['item'] : ''); ?>">
            </div>
        </div>
    </div>
    <input type="hidden" id="report_warehouse" value="<?php echo e(route('warehouse_list')); ?>">
    <input type="hidden" id="report_item_project" value="<?php echo e(route('warehouse_item_project')); ?>">
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
                                    <label for="date_from" class="col-sm-4 col-form-label">Tanggal Mulai</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_from" id="date_from" class="datepicker-normal form-control" placeholder="Tanggal mulai" value="<?php echo e($data['date_from']); ?>" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_to" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date_to" id="date_to" class="end-datepicker-normal form-control" placeholder="Tanggal akhir" value="<?php echo e($data['date_to']); ?>" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-sm btn-primary" name="submit" value="0"><i class="fa fa-search"></i> Cari</button>
                        <?php if($data['item'] != 'all'): ?>
                        <button class="btn btn-sm btn-success" name="submit" value="1" id="export_excel"><i class="fa fa-download"></i> Export ke Excel</button>
                        <?php endif; ?>
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
        <table class="table table-bordered table-hover no-margin" width="100%" id="data_stock">
            <thead>
                <tr>
                    <th>Date:</th>
                    <th>Item:</th>
                    <th>UOM</th>
                    <th>Begining:</th>
                    <th>Receiving:</th>
                    <th>In Standing</th>
                    <th>Delivery:</th>
                    <th>Out Standing</th>
                    <th>Closing:</th>
                </tr>
            </thead>
        
            <tbody> 
                <?php if($data['item'] != 'all'): ?>
                    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($collection['date']); ?></td>
                            <td><?php echo e($collection['item']); ?></td>
                            <td><?php echo e($collection['uom_name']); ?></td>
                            <td><?php echo e(number_format($collection['begining'], 2, ',', '.')); ?></td>
                            <td><?php echo e(number_format($collection['receiving'], 2, ',', '.')); ?></td>
                            <?php if($loop->last): ?>
                            <td><?php echo e(number_format($jumlah_inhandling, 2, ',', '.')); ?></td>
                            <?php else: ?>
                            <td>0,00</td>
                            <?php endif; ?>
                            <td><?php echo e(number_format($collection['delivery'], 2, ',', '.')); ?></td>
                            <?php if($loop->last): ?>
                            <td><?php echo e(number_format($jumlah_outhandling, 2, ',', '.')); ?></td>
                            <?php else: ?> 
                            <td>0,00</td>
                            <?php endif; ?>
                            
                            <td><?php echo e(number_format($collection['closing'], 2, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td width="25%"><?php echo e($key); ?></td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e($c['date']); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e($c['uom_name']); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['begining'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['receiving'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['instanding'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['delivery'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['outstanding'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                            <td>
                                <table class="table" width="100%">
                                <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e(number_format($c['closing'], 2, ',', '.')); ?></td>   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_warehouse').val(),
            dataType: "json",
            success: function(data){
                $('#warehouses').append("<option value='0' disabled selected>Pilih Warehouse</option>");
                $.each(data,function(i, value){
                    if($('#selected_warehouse').val() != '' && $('#selected_warehouse').val() == i){
                        $("#warehouses").append("<option value='"+i+"' selected>"+value+"</option>");
                    } else {
                        $("#warehouses").append("<option value='"+i+"'>"+value+"</option>");   
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
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
        $('#warehouses').change(function() {
            $("#item").empty();
            $.ajax({
                url: $('#report_item_project').val(),
                method: "GET",
                data : {
                    warehouse_id : $("#warehouses").val()
                },
                dataType: 'json',
                success: function(data) { 
                    $("#item").append("<option value='all' selected>--Semua Item--</option>");
                    if($('#item').val() == 'all') {
                        $('#export_excel').hide();
                    } else {
                        $('#export_excel').show();
                    }
                    $.each(data,function(i, value){
                        $("#item").append("<option value='"+value.id+"'>"+value.sku +' - ' +value.name+"</option>");
                    });
                }
            });
        });
        $('#item').change(function(){
            console.log('sini '+$('#item').val());
            if($('#item').val() == 'all') {
                $('#export_excel').hide();
            } else {
                $('#export_excel').show();
            }
        })

    </script>

    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#data_stock').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'print',
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL' 
                }
            ],
            "order": [[ 0, "desc" ]],
            "pagingType": "full_numbers"
        } );
    } );
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>