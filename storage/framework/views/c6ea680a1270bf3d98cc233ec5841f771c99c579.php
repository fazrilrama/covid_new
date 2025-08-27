<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<style>
.activity {
    height: 310px;
    display: block;
    overflow-y: scroll;
}
    .red {
        background-color: #D7445C !important;
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
<?php $__env->startSection('content_header'); ?>
    <h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Inbound Activities</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div id="inboundChart" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Outbound Activities</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div id="outboundChart" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Handling</h3>
            </div>
            <div class="box-body">
                <div id="progressChart" style="display: none;"></div>
            </div>
            <div class="box-body">
                <div id="handlingChart" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Movement Inbound Monitoring</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover no-margin activity">
                    <thead>
                    <tr>
                        <th rowspan="2">Warehouse</th>
                        <th rowspan="2">Activity</th>
                        <th rowspan="2">Start</th>
                        <th rowspan="2">Finish</th>
                        <th colspan="3">Time Consume</th>
                    </tr>
                    <tr>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Min</th>
                    </tr>
                    </thead>
                    <tbody style="height: 300px;overflow-x: hidden;">
                    <?php $__currentLoopData = $movpa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $startTime_transport = Carbon\Carbon::parse($item->stock_transport->created_at) ?>
                    <?php $endTime_transport = Carbon\Carbon::parse($item->stock_transport->updated_at) ?>  
                    <?php $startTime_advance = Carbon\Carbon::parse($item->stock_transport->advance_notice->created_at) ?>
                    <?php $endTime_advance = Carbon\Carbon::parse($item->stock_transport->advance_notice->updated_at) ?>
                    <tr>
                        <tr>
                            <td rowspan="3" style="text-align: center; vertical-align: middle"><?php echo e($item->warehouse_name); ?></td>
                            <td><?php echo e($item->code); ?></td>
                            <td><?php echo e($item->created_at); ?></td>
                            <td><?php echo e($item->updated_at); ?></td>
                            <td><?php echo e(floor ($item->times_spent/1440)); ?></td>
                            <td><?php echo e(floor (($item->times_spent/60)%24)); ?></td>
                            <td><?php echo e(floor ($item->times_spent%60)); ?></td>

                            
                        </tr>
                        <tr>
                        <td><?php echo e($item->stock_transport->code); ?></td>
                        <td><?php echo e($item->stock_transport->created_at); ?></td>
                        <td><?php echo e($item->stock_transport->updated_at); ?></td>
                        <td><?php echo e($startTime_transport->diffInDays($endTime_transport)); ?></td>
                        <td><?php echo e(floor($startTime_transport->diffInHours($endTime_transport) %24)); ?></td>
                        <td><?php echo e(floor($startTime_transport->diffInMinutes($endTime_transport) %60)); ?></td>

                        

                        </tr>
                        <tr>
                        <td><?php echo e($item->stock_transport->advance_notice->code); ?></td>
                        <td><?php echo e($item->stock_transport->advance_notice->created_at); ?></td>
                        <td><?php echo e($item->stock_transport->advance_notice->updated_at); ?></td>
                        <td><?php echo e($startTime_advance->diffInDays($endTime_advance)); ?></td>
                        <td><?php echo e(floor($startTime_advance->diffInHours($endTime_advance) %24)); ?></td>
                        <td><?php echo e(floor($startTime_advance->diffInMinutes($endTime_advance) %60)); ?></td>
                        </tr>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
    <div class="box">
            <div class="box-header">
                <h3 class="box-title">Movement Outbound Monitoring</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover no-margin activity">
                    <thead>
                    <tr>
                        <th rowspan="3">Warehouse</th>
                    </tr>
                    <tr>
                        <th rowspan="2"  style="text-align: center;">Activity</th>
                        <th rowspan="2">Start</th>
                        <th rowspan="2">Finish</th>
                        <th colspan="3">Time Consume</th>
                    </tr>
                    <tr>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Min</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $stockDeliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php $startTime_transport = Carbon\Carbon::parse($item->stock_entry->stock_transport->created_at) ?>
                    <?php $endTime_transport = Carbon\Carbon::parse($item->stock_entry->stock_transport->updated_at) ?>  
                    <?php $startTime_advance = Carbon\Carbon::parse($item->stock_entry->stock_transport->advance_notice->created_at) ?>
                    <?php $endTime_advance = Carbon\Carbon::parse($item->stock_entry->stock_transport->advance_notice->updated_at) ?>
                    <?php $startTime_entries = Carbon\Carbon::parse($item->stock_entry->created_at) ?>
                    <?php $endTime_entries = Carbon\Carbon::parse($item->stock_entry->updated_at) ?>
                    <tr>
                        <tr>
                            <td rowspan="4" style="text-align: center; vertical-align: middle"><?php echo e($item->warehouse_name); ?></td>
                            <td><?php echo e($item->code); ?></td>
                            <td><?php echo e($item->created_at); ?></td>
                            <td><?php echo e($item->updated_at); ?></td>
                            <td><?php echo e(floor ($item->times_spent/1440)); ?></td>
                            <td><?php echo e(floor (($item->times_spent/60)%24)); ?></td>
                            <td><?php echo e(floor ($item->times_spent%60)); ?></td>
                        </tr>
                        <tr>
                        <td><?php echo e($item->stock_entry->code); ?></td>
                        <td><?php echo e($item->stock_entry->created_at); ?></td>
                        <td><?php echo e($item->stock_entry->updated_at); ?></td>
                        <td><?php echo e($startTime_entries->diffInDays($endTime_entries)); ?></td>
                        <td><?php echo e(floor($startTime_entries->diffInHours($endTime_entries)%24)); ?></td>
                        <td><?php echo e(floor($startTime_entries->diffInMinutes($endTime_entries) %60)); ?></td>

                        

                        </tr>
                        <tr>
                        <td><?php echo e($item->stock_entry->stock_transport->code); ?></td>
                        <td><?php echo e($item->stock_entry->stock_transport->created_at); ?></td>
                        <td><?php echo e($item->stock_entry->stock_transport->updated_at); ?></td>
                        <td><?php echo e($startTime_transport->diffInDays($endTime_transport)); ?></td>
                        <td><?php echo e(floor($startTime_transport->diffInHours($endTime_transport)%24)); ?></td>
                        <td><?php echo e(floor($startTime_transport->diffInMinutes($endTime_transport) %60)); ?></td>

                        

                        </tr>
                        <tr>
                        <td><?php echo e($item->stock_entry->stock_transport->advance_notice->code); ?></td>
                        <td><?php echo e($item->stock_entry->stock_transport->advance_notice->created_at); ?></td>
                        <td><?php echo e($item->stock_entry->stock_transport->advance_notice->updated_at); ?></td>
                        <td><?php echo e($startTime_advance->diffInDays($endTime_advance)); ?></td>
                        <td><?php echo e(floor($startTime_advance->diffInHours($endTime_advance)%24)); ?></td>
                        <td><?php echo e(floor($startTime_advance->diffInMinutes($endTime_advance) %60)); ?></td>
                        </tr>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<?php if(Auth::user()->hasRole('CommandCenter')): ?>
<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">On the Way</h3>
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
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="box" style="3px solid red">
				<div class="box-header">
					<h3 class="box-title">In Delivery Process</h3>
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
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
        
            <tbody> 
                
            </tbody>
        </table>
    </div>
    </div>
    <input type="hidden" value="<?php echo e(route('api.good_issue_management_monitoring')); ?>" id="good_issue_management">
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
    <input type="hidden" value="<?php echo e(session()->get('current_project')->id); ?>" id="project_id">
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
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Bukti Penerimaan Barang</label>
                                            <div class="col-sm-9">
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="project_id" class="col-sm-3 control-label">Bukti Tanda Tangan Surat Jalan</label>
                                            <div class="col-sm-9">
                                               
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
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function() {

            var columnChart = new CanvasJS.Chart("inboundChart", {
                animationEnabled: true,
                axisY: {
                    title: "",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                axisY2: {
                    title: "",
                    titleFontColor: "#C0504E",
                    lineColor: "#C0504E",
                    labelFontColor: "#C0504E",
                    tickColor: "#C0504E"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor:"pointer",
                    itemclick: toggleDataSeries
                },
                data: [
                    {
                        type: "column",
                        name: "AIN",
                        legendText: "AIN",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($ain); ?>

                    },
                    {
                        type: "column",
                        name: "GR",
                        legendText: "GR",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($gr); ?>

                    },
                    {
                        type: "column",
                        name: "PA",
                        legendText: "PA",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($pa); ?>

                    // },
                    // {
                    //     type: "column",
                    //     name: "Pending",
                    //     legendText: "Pending",
                    //     showInLegend: true,
                    //     dataPoints:<?php echo json_encode($pendingInbound); ?>

                    }]
            });
            columnChart.render();

            var columnChart = new CanvasJS.Chart("outboundChart", {
                animationEnabled: true,
                axisY: {
                    title: "",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                axisY2: {
                    title: "",
                    titleFontColor: "#C0504E",
                    lineColor: "#C0504E",
                    labelFontColor: "#C0504E",
                    tickColor: "#C0504E"
                },
                axisY3: {
                    title: "",
                    titleFontColor: "#C05FFF",
                    lineColor: "#C05FFF",
                    labelFontColor: "#C05FFF",
                    tickColor: "#C05FFF"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor:"pointer",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "AON",
                    legendText: "AON",
                    showInLegend: true,
                    dataPoints:<?php echo json_encode($aon); ?>

                },
                    {
                        type: "column",
                        name: "DP",
                        legendText: "DP",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($dp); ?>

                    },
                    {
                        type: "column",
                        name: "PP",
                        legendText: "PP",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($pp); ?>

                    },
                    {
                        type: "column",
                        name: "GI",
                        legendText: "GI",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($gi); ?>

                    // },
                    // {
                    //     type: "column",
                    //     name: "Pending",
                    //     legendText: "Pending",
                    //     showInLegend: true,
                    //     dataPoints:<?php echo json_encode($pendingOutbound); ?>

                    }]
            });
            columnChart.render();

            var progressChart = new CanvasJS.Chart("progressChart", {
                animationEnabled: true,
                data: [{
                    type: "doughnut",
                    startAngle: 60,
                    indexLabelFontSize: 12,
                    indexLabel: "{label} - #percent%",
                    toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                    dataPoints: <?php echo json_encode($progress); ?>

                }]
            });
            progressChart.render();

            var columnChart = new CanvasJS.Chart("handlingChart", {
                animationEnabled: true,
                axisY: {
                    title: "",
                    titleFontColor: "#4F81BC",
                    lineColor: "#4F81BC",
                    labelFontColor: "#4F81BC",
                    tickColor: "#4F81BC"
                },
                axisY2: {
                    title: "",
                    titleFontColor: "#C0504E",
                    lineColor: "#C0504E",
                    labelFontColor: "#C0504E",
                    tickColor: "#C0504E"
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor:"pointer",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "In",
                    legendText: "In",
                    showInLegend: true,
                    dataPoints:<?php echo json_encode($handlingUnit); ?>

                },
                    {
                        type: "column",
                        name: "Out",
                        legendText: "Out",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($handlingWeight); ?>

                    }]
            });
            columnChart.render();

            function toggleDataSeries(e) {
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else {
                    e.dataSeries.visible = true;
                }
                columnChart.render();
            }

        }
    </script>
    <script src='<?php echo e(asset('vendor/mustache/js/mustache.min.js')); ?>'></script>
    <script src="<?php echo e(asset('vendor/replaceSymbol/replaceSymbol.js')); ?>"> </script>
    <script>
        var count = 0;
        var count_aktif_kurang =0;
        var count_aktif_bulan = 0;
        var table = $('#warehouse-contract').DataTable({
            "retrieve": true,
            "processing": true,
            'ajax': {
                "type": "GET",
                "url": $('#good_issue_management').val(),
                "data": function (d) {
                    d.id="";
                    d.project_id = $("#project_id").val();
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
                        var status = full.status == 'Processed'  ? 'Loading' : (full.status == 'Completed' ? 'On Delivery' : 'Received');
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
                console.log(data.selisih)
                if(data.status == 'Received'){
                    $(row).addClass('green');
                } else if(data.status == 'Completed' && data.selisih == 0)
                {
                    $(row).addClass('orange');
                }else if(data.status == 'Completed' && data.selisih > 0) {
                    $(row).addClass('red');
                }
            },
            "initComplete":function( settings, json){
                    for(var i = 0; i < json.data.length; i++){
                        if (json.data[i].status == 'Completed' && json.data[i].selisih == 0){
                            count++;
                        };
                        if (json.data[i].status == 'Completed'  && json.data[i].selisih > 0){
                            count_aktif_bulan++;
                        };
                        if (json.data[i].status == 'Completed' && json.data[i].selisih < 0){
                            count_aktif_kurang++;
                        };
                    }
                $('#aktif').text(count);
                $('#end_of_month').text(count_aktif_bulan);
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