<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('adminlte_css'); ?>
    <style>
    .red {
        background-color: #da413e !important;
    }
    .green {
        background-color: #4BB543 !important;
    }
    .orange {
        background-color: #FFC107 !important;
    }
    .blue {
        background-color: #add8e6 !important;
    }
    .table-parent {
        overflow-x: scroll;
    }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Report Good Issue</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startSection('additional_field'); ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="branch" class="col-sm-4 col-form-label">Project</label>
            <div class="col-sm-8">
                <select name="project" id="project" class="form-control select2">
                    <option value="all" selected>--Semua Project--</option>
                </select>
                <input type="hidden" id="selected_project" value="<?php echo e(!empty($data['project']) ? $data['project'] : ''); ?>">
                
            </div>
        </div>
    
    </div>
    <input type="hidden" id="report_branch" value="<?php echo e(route('project_list')); ?>">
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
                        <button class="btn btn-sm btn-success" name="submit" value="1"><i class="fa fa-save"></i> Export Excel</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <?php if($search): ?>
    <div class="row">
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header" style="border-top: 3px solid #add8e6;">
					<h3 class="box-title">Loading</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="aktif">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="box" style="border-top: 3px solid #FFC107;">
				<div class="box-header">
					<h3 class="box-title">On Delivery (In Schedule)</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="end_of_month">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="col-md-3 col-sm-6 col-xs-12">
			<div class="box" style="border-top: 3px solid #da413e;">
				<div class="box-header">
					<h3 class="box-title">On Delivery (Waiting Document)</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="telat">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="box" style="border-top: 3px solid #4bb543;">
				<div class="box-header">
					<h3 class="box-title">Received</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="widget navy-bg">
						<div class="row-fluid">
							<div class="span8 text-right">
								<h1 class="font-bold" id="aktif_mati">
								0
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div id="table-parent">
    <div class="table-responsive">
        <table id="warehouse-contract" class="data-table table table-bordered table-hover no-margin" width="125%">
            <thead>
                <tr>
                    <th>Kode GI</th>
                    <th>Dibuat Pada</th>
                    <th>Origin</th>
                    <th>Asal Gudang</th>
                    <th>Destination</th>
                    <th>Shipper</th>
                    <th>Consignee</th>
                    <th>ETA</th>
                    <th>Received Date</th>
                    <th>Received By</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
        
            <tbody> 
                
            </tbody>
        </table>
    </div>
    </div>
    <input type="hidden" value="<?php echo e(route('api.good_issue_management')); ?>" id="good_issue_management">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Pengantaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="divre-wrapper">
            </div>
            <table class="table table-striped no-margin" width="100%">
                <thead>
                <tr>
                    <th>Item SKU:</th>
                    <th>Item Name</th>
                    <th>Group Ref</th>
                    <th>Qty:</th>
                </tr>
                </thead>

                <tbody class="detail_container_wrapper">
                

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    <div id="detail-url" url="#" data-url="<?php echo e(route('api_stock_delivery_detail', ':id')); ?>"></div>
    <template id="section_container_detail">
        <div class="row">
            <div class="col-md-12">
                {{ #dataDivre}}
                <div class="box box-default">
                    <form class="form-horizontal"  enctype="multipart/form-data">
                    <input type="hidden" name="id_transaction" value="{{ id }}">
                    <div class="box-body">
                        <div class="row">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="transport_type" class="col-sm-3 control-label">Jenis Transportasi</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ transport_type.name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label">Consignee</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ consignee.name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label">Consignee Address</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ consignee.address  }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ref_code" class="col-sm-3 control-label">Shipper</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ shipper.name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="employee_name" class="col-sm-3 control-label">Shipper Address</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ shipper.address  }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Origin</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ origin.name }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Destination</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ destination.name }}</p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Bukti Perhitungan Barang</label>
                                            <div class="col-sm-9">
                                                <a href="/storage/{{ photo }}" target="_blank">Dokument Link</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Bukti Penerimaan Barang</label>
                                            <div class="col-sm-9">
                                                <a href="/storage/{{ photo_box }}" target="_blank">Dokument Link</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Bukti Tanda Tangan Surat Jalan</label>
                                            <div class="col-sm-9">
                                                <a href="/storage/{{ photo_signature }}" target="_blank">Dokument Link</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Penerima</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ received_by }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Arrived Date</label>
                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ date_received }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </di>
                        </div>
                    </div>
                    {{/dataDivre}}
                    </form>
                </div>
            </div>
        </div>
    </template>
    <template id="detail_container_render">
    {{#dataDetail}}

    <tr>
    <td>{{ item.sku }}</td>
    <td>{{ item.name }}</td>
    <td>{{ ref_code }}</td>
    <td>{{ qty }}</td>

    </tr>

    {{/dataDetail}}
    </template>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script src='<?php echo e(asset('vendor/mustache/js/mustache.min.js')); ?>'></script>
    <script src="<?php echo e(asset('vendor/replaceSymbol/replaceSymbol.js')); ?>"> </script>
    <script>
        $.ajax({
            method: 'GET',
            url: $('#report_branch').val(),
            dataType: "json",
            success: function(data){
                // $("#warehouses").empty();
                $.each(data,function(i, value){
                    if($('#selected_project').val() == value.project_id) {
                        $("#project").append("<option value='"+value.project_id+"' selected>"+value.name+"</option>");
                    } else {
                        $("#project").append("<option value='"+value.project_id+"'>"+value.name+"</option>");
                    }
                });
            },
            error:function(){
                console.log('error '+ data);
            }
        });
    </script>
    <script>
        var count = 0;
        var count_aktif_kurang =0;
        var count_aktif_bulan = 0;
        var count_late_bulan = 0;
        var table = $('#warehouse-contract').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#good_issue_management').val(),
                "data": function (d) {
                    d.id="";
                    d.project_id = $("#selected_project").val();
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
                        return full.created_at;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.origin.name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.stock_entry.warehouse.name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.destination.name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.shipper.name;
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.consignee.name;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        return full.eta
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.updated_at
                    }
                },
                {
                    render: function (data, type, full, meta) {
                        return full.received_by;
                    }
                },  
                {
                    render: function (data, type, full, meta) {
                        var status = full.status == 'Processed'  ? 'Loading' : (full.status == 'Completed' && full.selisih <= 0 ? 'On Delivery' : ( full.status == 'Completed' && full.selisih > 0 ? 'Waiting Document': 'Received'));
                        return status;
                    }
                },
                    {
                        render: function (data, type, full, meta) {
                        return '<button type="button" class="btn btn-primary detail_barang" data-id="'+ full.id +'"><i class="fa fa-eye"></i></button>';
                    }
                }
               
            ],
            'rowCallback': function(row, data, index){
                // console.log(data)
                if(data.status == 'Received'){
                    $(row).addClass('green');
                } else if(data.status == 'Completed' && data.selisih <= 0)
                {
                    $(row).addClass('orange');
                }else if(data.status == 'Completed' && data.selisih > 0) {
                    $(row).addClass('red');
                } else if(data.status == 'Processed'){
                    $(row).addClass('blue');

                }
            },
            "initComplete":function( settings, json){
                    for(var i = 0; i < json.data.length; i++){
                        if (json.data[i].status == 'Processed' ){
                            count++;
                        };
                        if (json.data[i].status == 'Completed' && json.data[i].selisih <= 0){
                            count_aktif_bulan++;
                        };
                        if (json.data[i].status == 'Completed' && json.data[i].selisih > 0){
                            count_late_bulan++;
                        };
                        if (json.data[i].status == 'Received'){
                            count_aktif_kurang++;
                        };
                    }
                $('#aktif').text(count);
                $('#end_of_month').text(count_aktif_bulan);
                $('#telat').text(count_late_bulan);
                $('#aktif_mati').text(count_aktif_kurang);
                $('.detail_barang').click(function(){
                    var url = replaceSymbol('#detail-url', 'data-url', $(this).attr("data-id"));
                    $.get(url, function (data, textStatus, xhr) {
                        $('.detail_container_wrapper').html('');
                        $('.detail_container_wrapper').append(Mustache.render($('#detail_container_render').html(), {
                            dataDetail : data.details
                        }));
                        $('.divre-wrapper').html('');
                        $('.divre-wrapper').append(Mustache.render($('#section_container_detail').html(), {
                            dataDivre : data,
                        }));
                        
                    });
                    $('#exampleModal').modal('show')
                });
            }
        });
        
        table.on('click', 'button.detail_barang', function () {
            var url = replaceSymbol('#detail-url', 'data-url', $(this).attr("data-id"));
            $.get(url, function (data, textStatus, xhr) {
                $('.detail_container_wrapper').html('');
                $('.detail_container_wrapper').append(Mustache.render($('#detail_container_render').html(), {
                    dataDetail : data.details
                }));
                $('.divre-wrapper').html('');
                $('.divre-wrapper').append(Mustache.render($('#section_container_detail').html(), {
                    dataDivre : data,
                }));
                
            });
            $('#exampleModal').modal('show')
        });
        //     //todo
            
        // });
    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>