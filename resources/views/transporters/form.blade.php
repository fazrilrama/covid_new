<!-- form start -->

<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group required">
				<label for="container" class="col-sm-3 control-label">Kode GI / Ref Code</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="container_detail" id="container_detail" required>
						<option value="" selected disabled>-- Kode GI / Ref Code --</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="divre-wrapper">
	</div>
</div>



<template id="section_container_detail">
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<form method="POST" action="{{ route('update_status') }}" class="form-horizontal"  enctype="multipart/form-data">
			@csrf
			@{{ #dataDivre}}
			<input type="hidden" name="id_transaction" value="@{{ id }}">
			<div class="box-body">
					<div class="row">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="transport_type" class="col-sm-3 control-label">Dokumen Referensi #</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ code }}</p>
										</div>
									</div>

									<div class="form-group">
										<label for="transport_type" class="col-sm-3 control-label">Tanggal Dibuat</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{  created_at }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="transport_type" class="col-sm-3 control-label">Jenis Transportasi</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ transport_type.name }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="ref_code" class="col-sm-3 control-label">Penerima</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ consignee.name }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="employee_name" class="col-sm-3 control-label">Alamat Penerima</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ consignee.address  }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="ref_code" class="col-sm-3 control-label">Pengirim</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ shipper.name }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="employee_name" class="col-sm-3 control-label">Alamat Pengirim</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ shipper.address  }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="employee_name" class="col-sm-3 control-label">Nomor Referensi Pelanggan</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ ref_code }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Status</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ status }}</p>
										</div>
									</div>
								</div>
								<input type="hidden" value="@{{ created_at }}" class="min_date">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Kota Asal</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ origin.name }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Kota Tujuan</label>
										<div class="col-sm-9">
											<p class="form-control-static">@{{ destination.name }}</p>
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Bukti Perhitungan Barang</label>
										<div class="col-sm-9">
											<input type="file" name="photo" id="photo" required>
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Bukti Penerimaan Barang</label>
										<div class="col-sm-9">
											<input type="file" name="photo_unboxing" id="photo_unboxing" required accept="image/*">
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Bukti Tanda Tangan Surat Jalan</label>
										<div class="col-sm-9">
											<input type="file" name="photo_signature" id="photo_signature" required accept="image/*">
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Penerima</label>
										<div class="col-sm-9">
											<input type="text" name="penerima" id="penerima" placeHolder="Penerima" required>
										</div>
									</div>
									<div class="form-group">
										<label for="project_id" class="col-sm-3 control-label">Tanggal Sampai</label>
										<div class="col-sm-9">
												<div class='input-group date'>
													<input type='text' class="form-control datetimepicker1" name="date_arrival" placeHolter="Tanggal" required/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
										</div>
									</div>
									<hr>
									<br>
									<div class="form-group pull-right">
										<div class="col-md-12">
											<button class="btn btn-info btn-completed" type="submit">Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			@{{ /dataDivre }}
			<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Informasi Barang</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
						<!-- /.box-tools -->
					</div>
					<div class="box-body">
                    <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>

		</div>
	</div>
</div>
</template>


<input type="hidden" value="{{route('api.gicomplete')}}" id="list_expedition">
<div id="detail-url" url="#" data-url="{{ route('api_stock_delivery_detail', ':id') }}"></div>

<template id="detail_container_render">
@{{#dataDetail}}

<tr>
<td>@{{ item.sku }}</td>
<td>@{{ item.name }}</td>
<td>@{{ ref_code }}</td>
<td>@{{ qty }}</td>

</tr>

@{{/dataDetail}}
</template>

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA02F-JsJQhEeQDcd37uk2IN9nvubiqQr0&libraries=places" ></script>
	<script src='{{ asset('vendor/mustache/js/mustache.min.js') }}'></script>
    <script src="{{ asset('vendor/replaceSymbol/replaceSymbol.js') }}"> </script>

    <script src="{{ asset('js/locationpicker.js') }}"></script>
    <script>
		$.ajax({
			method: 'GET',
			url: $('#list_expedition').val(),
			dataType: "json",
			success: function(data){
				// $("#warehouses").empty();
				$.each(data,function(i, value){
					$("#container_detail").append("<option value='"+value.id+"'>"+value.code + '('+value.ref_code+')' +"</option>");
				});
			},
			error:function(){
				console.log('error '+ data);
			}
		});

		$('#container_detail').on('change', function(){
			// let url_aon = replaceSymbol
			let url = replaceSymbol('#detail-url', 'data-url', $('#container_detail').val());
			$.get(url, function (data, textStatus, xhr) {
				$('.divre-wrapper').html('');
				$('.divre-wrapper').append(Mustache.render($('#section_container_detail').html(), {
					dataDivre : data,
				}));
				$('.detail_container_wrapper').html('');
                $('.detail_container_wrapper').append(Mustache.render($('#detail_container_render').html(), {
					dataDetail : data.details
                }));
				var setMin = function( currentDateTime ){
				this.setOptions({
					minDate:'-1970/01/02'
				});
				this.setOptions({
					minTime:'11:00'
				});
				};

				$('.datetimepicker1').datetimepicker({
					minDate: new Date($('.min_date').val()),
					maxDate: new Date()

				});
				// $('.datetimepicker1')
				// .datetimepicker('option', 'minDateTime', new Date());
			});
		});
    </script>
@endsection