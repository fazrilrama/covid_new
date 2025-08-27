<?php $__env->startSection('title', 'Integrated Logistics Solution'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Gudang Location</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="form-group">
    <select name="region" id="filter_region" class="form-control select2">
        <option readonly>-- Pilih Cabang/Subcabang</option>
        <option value="">Semua Cabang/Subcabang</option>
        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($region->id); ?>" <?php echo e($selectedRegion == $region->id ? 'selected' : ''); ?>><?php echo e($region->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div id="map" style="height: 500px">

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
 <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYumU-dYmR86u0jRZU8rJu6Ahy--UdRZ8" async defer></script>

<script>

	$(document).ready(()=>{
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
		
		$('#filter_region').change(function(){
			$.ajax({
				url:'/warehouses/maps_api'
				,type:'GET'
				,data:{
					region:$(this).val()
				}
				,dataType:'json'
				,success:(res)=>{
					initMap(res.whl);
				}
			});	
		});
		
		$('#filter_region').val("").trigger('change');
		
		
	});
	
	

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>