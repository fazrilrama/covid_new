<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Warehouse Activity</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12" style="margin-bottom:20px;">
            <div id="map" style="height: 100vh">
            </div>
        </div>
        <div class="col-md-12">
            <table id="warehouse-activity" class="table table-striped table-bordered table-hover no-margin" width="100%">
                <thead>
                <tr>
                    <th>WH ID</th>
					<th>Divre</th>
                    <th>WH Name</th>
                    <th>Last Activity</th>
                    <th>Last Activity Date</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
	<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="modal-title"></h4>
        </div>
        <div class="modal-body" id="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
   </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYumU-dYmR86u0jRZU8rJu6Ahy--UdRZ8"></script>
    <script>
		$(document).ready(()=>{
			
        setTimeout("location.reload();",1500000);
		var map;
		function initMap(locations) {
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 5,
				center: new google.maps.LatLng(-2.600029, 118.015776),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			var infowindow = new google.maps.InfoWindow();

			var marker, i,markers=[];

			var customIcons = {
				green: {
					icon: "<?php echo e(url('1.png')); ?>"
				},
				red: {
					icon: "<?php echo e(url('2.png')); ?>"
				}
			};

			
			for (i = 0; i < locations.length; i++) {
				var utility_space = locations[i].utility_space;

				//final position for marker, could be updated if another marker already exists in same position
				var latlng=new google.maps.LatLng(locations[i].latitude,locations[i].longitude);
				var finalLatLng = latlng;

				//check to see if any of the existing markers match the latlng of the new marker
				if (markers.length != 0) {
					for (j=0; j < markers.length; j++) {
						var existingMarker = markers[j];
						var pos = existingMarker.getPosition();

						//if a marker already exists in the same position as this marker
						if (latlng.equals(pos)) {
							//update the position of the coincident marker by applying a small multipler to its coordinates
							var newLat = latlng.lat() + (Math.random() -.5) / 1500;// * (Math.random() * (max - min) + min);
							var newLng = latlng.lng() + (Math.random() -.5) / 1500;// * (Math.random() * (max - min) + min);
							finalLatLng = new google.maps.LatLng(newLat,newLng);
						}
					}
				}
				
				marker = new google.maps.Marker({
					position: finalLatLng,
					map: map
				});
				markers.push(marker);

				if (utility_space > 0) {
					marker.setIcon("<?php echo e(url('1.png')); ?>");
				} else {
					marker.setIcon("<?php echo e(url('2.png')); ?>");
				}


				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						var last_activity='-';
						var last_activity_date='-';
						
						$.ajax({
							async:false,
							url:'/warehouses/maps_api/activity'
							,dataType:'json'
							,data:{
								id:locations[i].id
							}
							,success:(res)=>{
								last_activity=res.last_activity;
								last_activity_date=res.last_activity_date;
							}
						});
						infowindow.setContent('<table>' +
							'<tr>' +
							'<td>Gudang</td>' +
							'<td>:</td>' +
							'<td>' + locations[i].code + ' - ' + locations[i].name + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Divre</td>' +
							'<td>:</td>' +
							'<td>' + locations[i].divre + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Area</td>' +
							'<td>:</td>' +
							'<td>' + locations[i].area + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Region</td>' +
							'<td>:</td>' +
							'<td>' + locations[i].region + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Space</td>' +
							'<td>:</td>' +
							'<td>' + (locations[i].length * locations[i].width) + ' m<sup>2</sup></td>' +
							'</tr>' +
							'<tr>' +
							'<td>Utility Space</td>' +
							'<td>:</td>' +
							'<td>' + (locations[i].utility_space) + ' %</td>' +
							'</tr>' +
							// '<tr>'+
							//     '<td>Terpakai</td>'+
							//     '<td>:</td>'+
							//     '<td>' + (locations[i].terpakai == null ? '0' : locations[i].terpakai) + ' Ton</td>'+
							// '</tr>'+
							// '<tr>'+
							//     '<td>Iddle</td>'+
							//     '<td>:</td>'+
							//     '<td>' + (locations[i].iddle == null ? '0' : locations[i].iddle) + ' Ton</td>'+
							// '</tr>'+
							'<tr>' +
							'<td>Last Activity</td>' +
							'<td>:</td>' +
							'<td>' + last_activity + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Tanggal</td>' +
							'<td>:</td>' +
							'<td>' + last_activity_date + '</td>' +
							'</tr>' +
							'</table>');
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
			
			// Add a marker clusterer to manage the markers.
			var markerCluster = new MarkerClusterer(map, markers,
				{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
			}
		
		$.ajax({
				url:'/warehouses/maps_api'
				,type:'GET'
				,data:{
					region:""
				}
				,dataType:'json'
				,async:false
				,success:(res)=>{
					initMap(res.whl);
					
					
					$('#warehouse-activity').DataTable({
						"retrieve": true,
						"processing": true,
						'ajax': {
							"type": "GET",
							"url": '/warehouses/maps_api/activity',
							"data": function (d) {
								d.id="";
							},
							"dataSrc": "wh"
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
									console.log(full);
									return full.branch.name;
								}
							},
							{
								render: function (data, type, full, meta) {
									return full.name;
								}
							},
							{
								render: function (data, type, full, meta) {
									return '<span id="act_'+full.id+'" data-id="'+full.id+'" data-title="'+full.name+' ('+full.code+')" class="look_last_act"></span>';
								}
							}
							,{
								render: function (data, type, full, meta) {
									return '<span id="act_date_'+full.id+'" data-id="'+full.id+'" data-title="'+full.name+' ('+full.code+')" class="look_last_act_date"></span>';
								}
							}
						]
					}).on('draw.dt', function() {
						var ids=[];
						$('.look_last_act').each(function(){
							id=$(this).attr('data-id');
							ids.push(id);
						});
						
						var param_ids='';
						var i=0;
						ids.forEach((e)=>{
							if(i!=0) param_ids+=',';
							param_ids+=e;
							i++;
						});
						
						console.log(param_ids);
						$.ajax({
							async:true,
								url:'/warehouses/maps_api/activity'
								,dataType:'json'
								,data:{
								id:param_ids
								,is_array:true
							}
							,success:(res)=>{
								res.forEach((e)=>{
									$('#act_'+e.id).html(e.last_activity.toUpperCase());
									$('#act_date_'+e.id).html(e.last_activity_date.toUpperCase());
								});
							}
						});
					});
					/*$.ajax({
						async:true,
						url:'/warehouses/maps_api/activity'
						,dataType:'json'
						,data:{
							id:""
						}
						,success:(res1)=>{
							var rows="";
							res.whl.forEach((e)=>{
								var last_activity='-';
								var last_activity_date='-';
								for(var i=0;i<res1.last_activity.length;i++)
								{
									var f=res1.last_activity[i];
									if(f.warehouse_id==e.id){
										last_activity=f.type;
										break;
									} 
								};
								for(var i=0;i<res1.last_activity_date.length;i++)
								{
									var f=res1.last_activity_date[i];
									if(f.warehouse_id==e.id)
									{ 
										last_activity_date=f.created_at;
										break;
									}
								};
								rows+="<tr> <td>"+e.code+"</td> <td>"+e.name+"</td> <td>"+last_activity+"</td><td>"+last_activity_date+"</td> </tr>";
							});
							
							$('#dtableData').html(rows);
							//$('#warehouse-activity').DataTable();
						}
					});*/

				}
			});	
	});
	
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>