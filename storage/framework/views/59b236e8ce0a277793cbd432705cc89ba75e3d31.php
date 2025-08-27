<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>
<?php $__env->startSection('content_header'); ?>
    <h1>Laporan Stock On Location</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('adminlte_css'); ?>
    <link rel='stylesheet' href="<?php echo e(asset('vendor/datatables/css/jquery.dataTables.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/datatables/css/buttons.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/select2/css/select2-bootstrap.min.css')); ?>">
    <style>
        div.dt-buttons {
            float: right;
        }

        .select2 {
            width: 100% !important;
        }
        #table-stockonlocation_filter {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
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
            <form action="<?php echo e(route('report.stock_on_location')); ?>" method="POST" class="form-horizontal">
                <?php echo e(csrf_field()); ?>

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sku" class="col-sm-4 col-form-label">Item SKU</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="sku" name="sku" required>
                                        <option value="" selected disabled>--Pilih SKU--</option>
                                        <option value="">All</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ref" class="col-sm-4 col-form-label">Group Ref</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="ref" name="ref" required>
                                        <option value="" selected disabled>--Pilih Group Ref--</option>
                                        <option value="">All</option> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="storage_id" class="col-sm-4 col-form-label">Storage ID</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="storage_id" name="storage_id" required>
                                        <option value="" selected disabled>--Pilih Storage--</option>
                                        <option value="">All</option>
                                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <optgroup label="<?php echo e($warehouse->warehouse->name); ?>">
                                                <?php $__currentLoopData = $warehouse->storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($storage->id); ?>" <?php echo e($request->storage_id == $storage->id ? 'selected' : ''); ?>><?php echo e($storage->code); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>                                       
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="control_date" class="col-sm-4 col-form-label">Control Date</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" id="control_date" name="control_date" required>
                                        <option value="" selected disabled>--Pilih Control Date--</option>
                                        <option value="">All</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-sm btn-primary" name="submit" id="obutn" value="0">
                        <i class="fa fa-search"></i> Cari
                    </button>
                    <button class="btn btn-sm btn-success" name="submit" id="oEbutn" value="1">
                        <i class="fa fa-download"></i> Export ke Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive">
	<?php echo $dataTables->table(['id'=> 'table-stockonlocation', 'class' => 'table table-bordered table-hover no-margin','style' => 'width:100%', 'cellspacing' => '0']); ?>

</div>
<input id="filter_data" type="hidden" value="<?php echo e(route('report.masterfilter')); ?>">
<input type="hidden" id="selected_sku" value="<?php echo e($request->sku); ?>">
<input type="hidden" id="selected_ref" value="<?php echo e($request->ref); ?>">
<input type="hidden" id="selected_control_date" value="<?php echo e($request->control_date); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_script'); ?>
    <?php echo $__env->yieldContent('js'); ?>
    <script src="<?php echo e(asset('vendor/datatables/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/datatables/js/input.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/datatables/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/select2/js/select2.min.js')); ?>"></script>
    
    <script>
    $.ajax({
        type: 'GET',
        url: $('#filter_data').val(),
        success: function (data) {
            var sku = $('#sku');
            var formSku = "";
            for (var i = 0; i < data.skus.length; i++) {
                if($('#selected_sku').val() == data.skus[i].id) {
                    sku.append('<option id=' + data.skus[i].id + ' value=' + data.skus[i].id + ' selected >' + data.skus[i].name + '</option>');
                } else {
                    sku.append('<option id=' + data.skus[i].id + ' value=' + data.skus[i].id + '>' + data.skus[i].name + '</option>');
                }
            }
            
            var control_dates = $('#control_date');
            for (var i = 0; i < data.control_dates.length; i++) {
                //console.log($('#selected_control_date').val() == data.control_dates[i]);
                if($('#selected_control_date').val() == data.control_dates[i]) {
                    control_dates.append('<option id=' + data.control_dates[i] + ' value=' + data.control_dates[i] + ' selected >' + data.control_dates[i] + '</option>');
                } else {
                    control_dates.append('<option id=' + data.control_dates[i] + ' value=' + data.control_dates[i] + '>' + data.control_dates[i] + '</option>');
                }
            }

            var ref_codes = $('#ref');
            for (var i = 0; i < data.ref_codes.length; i++) {
                //console.log($('#selected_ref').val(), data.ref_codes[i],$('#selected_ref').val() == data.ref_codes[i]);
		if($('#selected_ref').val() == data.ref_codes[i]) {
                    ref_codes.append('<option id=' + data.ref_codes[i] + ' selected>' + data.ref_codes[i] + '</option>');
                } else {
                    ref_codes.append('<option id=' + data.ref_codes[i] + '>' + data.ref_codes[i] + '</option>');
                }

            }

            // //manually trigger a change event for the contry so that the change handler will get triggered
            sku.change();
            ref_codes.change();
            control_dates.change();
        }
    });
    </script>
    <script>
        // $(document).ready(function() {
        //     $('#table-stockonlocation_wrapper').DataTable( {
        //         "footerCallback": function ( row, data, start, end, display ) {
        //             var api = this.api(), data;
        //             console.log(api);
        //             // Remove the formatting to get integer data for summation
        //             var intVal = function ( i ) {
        //                 return typeof i === 'string' ?
        //                     i.replace(/[\$,]/g, '')*1 :
        //                     typeof i === 'number' ?
        //                         i : 0;
        //             };
        
        //             // Total over all pages
        //             total = api
        //                 .column( 8 )
        //                 .data()
        //                 .reduce( function (a, b) {
        //                     return intVal(a) + intVal(b);
        //                 }, 0 );
        
        //             // Total over this page
        //             pageTotal = api
        //                 .column( 8, { page: 'current'} )
        //                 .data()
        //                 .reduce( function (a, b) {
        //                     return intVal(a) + intVal(b);
        //                 }, 0 );
        
        //             // Update footer
        //             $( api.column( 8 ).footer() ).html(
        //                 '$'+pageTotal +' ( $'+ total +' total)'
        //             );
        //         }
        //     } );
        // } );
    </script>

    <script type = "text/javascript" >
        jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
            return this.flatten().reduce( function ( a, b ) {
                if ( typeof a === 'string' ) {
                    a = a.replace(/[^\d.-]/g, '') * 1;
                }
                if ( typeof b === 'string' ) {
                    b = b.replace(/[^\d.-]/g, '') * 1;
                }

                return a + b;
            }, 0 );
        } );

    </script>
    <?php echo $dataTables->scripts(); ?>

    <script>
        $(function() {
            var table = $('#table-stockonlocation').DataTable();
            table.on( 'draw.dt', function () {
                //var tablesum = table.column().data().sum();
                //$(".dataTables_info").append('. <b> Total Outbound' + table.ajax.json().outbound + '</b>');
                //$("#total_outbond").append(table.ajax.json().outbound);  
                // $(table.column(4).footer() ).html(
                //     table.ajax.json().outbound
                // );
                
                // var response = settings.json;
                // console.log(response);
                $('tfoot').html('');
                $("#table-stockonlocation").append(
                    $('<tfoot style="border-top: 0px"/>').append(
                        '<th colspan="8"><strong>TOTAL TERSEDIA</strong></th>'
                        +'<th colspan="1"><strong>'+ table.ajax.json().total_result +'</strong></th>'
                    )
                );
                $("#table-stockonlocation").append(
                    $('<tfoot style="border-top: 0px"/>').append(
                        '<th colspan="7"><strong>TOTAL</strong></th>'+
                        '<th colspan="1"><strong>'+ table.ajax.json().inbound +'</strong></th>'
                        +'<th colspan="1"><strong>'+ table.ajax.json().outbound +'</strong></th>'
                    )
                );
            } );
            // total = tableinfo.recordsTotal
        });
        $("#obutn").click(function() {
            $(this).closest("form").attr("action", "<?php echo e(route('report.stock-on-location')); ?>").attr('method','get');     
        });
        $("#oEbutn").click(function() {
            $(this).closest("form").attr("action", "<?php echo e(route('report.stock_on_location')); ?>").attr('method', 'post');       
        });
    </script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>