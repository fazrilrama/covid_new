<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
	<h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-import"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">AIN</span>
					<span class="info-box-number">
	          	<a href="<?php echo e(url('advance_notices/inbound')); ?>"><?php echo e($advance_notices->where('type','inbound')->count()); ?></a>
	          </span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<!-- /.col -->
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="glyphicon glyphicon-export"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">AON</span>
					<span class="info-box-number">
	          	<a href="<?php echo e(url('advance_notices/outbound')); ?>"><?php echo e($advance_notices->where('type','outbound')->count()); ?></a></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<!-- /.col -->

		<!-- fix for small devices only -->
		<div class="clearfix visible-sm-block"></div>

		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="glyphicon glyphicon-transfer"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Goods Receiving</span>
					<span class="info-box-number">
	          	<a href="<?php echo e(url('stock_transports/inbound')); ?>"><?php echo e($stock_transports->where('type','inbound')->count()); ?></a></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<!-- /.col -->
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-send"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Delivery Plan</span>
					<span class="info-box-number"><a href="<?php echo e(url('stock_transports/outbound')); ?>"><?php echo e($stock_transports->where('type','outbound')->count()); ?></a></span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div id="columnChartContainer" style="height: 300px; width: 100%;"></div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="storageChartContainer" style="height: 300px; width: 100%;"></div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
        </div>
    </div> -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_script'); ?>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Jenis Komoditi"
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    yValueFormatString: "##0.00\"%\"",
                    indexLabel: "{label} {y}",
                    dataPoints: <?php echo json_encode($comodityChart); ?>

                }]
            });
            chart.render();

            var columnChart = new CanvasJS.Chart("columnChartContainer", {
                animationEnabled: true,
                title:{
                    text: "Handling In & Out"
                },
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
                    name: "Handling In",
                    legendText: "Handling In",
                    showInLegend: true,
                    dataPoints:<?php echo json_encode($handlingIns); ?>

                },
                    {
                        type: "column",
                        name: "Handling Out",
                        legendText: "Handling Out",
                        showInLegend: true,
                        dataPoints:<?php echo json_encode($handlingOuts); ?>

                    }]
            });
            columnChart.render();

            var storageChart = new CanvasJS.Chart("storageChartContainer", {
                animationEnabled: true,
                title:{
                    text: "Average Storage Usage",
                    horizontalAlign: "left"
                },
                data: [{
                    type: "doughnut",
                    startAngle: 60,
                    indexLabelFontSize: 17,
                    indexLabel: "{label} - #percent%",
                    toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                    dataPoints: <?php echo json_encode($storage); ?>

                }]
            });
            storageChart.render();

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>