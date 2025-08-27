<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="project_id" class="col-sm-3 control-label">Referensi</label>
					<div class="col-sm-9">
						<input id="ref_code" type="text" name="code_reference" class="form-control" placeholder="Code Reference" value="{{old('note', $stock_internal_movement->code_reference)}}" {{ $method != 'PUT' ?'': 'disabled' }} required>
					</div>
				</div>
				<div class="form-group required">
					<label for="created_at" class="col-sm-3 control-label">Alasan</label>
					<div class="col-sm-9">
						<input id="ref_code" type="text" name="note" class="form-control" placeholder="Note" value="{{old('note', $stock_internal_movement->note)}}" {{ $method != 'PUT' ?'': 'disabled' }} required>
					</div>
				</div>
				<div class="form-group">
					<label for="created_at" class="col-sm-3 control-label">Dokumen</label>
					<div class="col-sm-9">
						@if($method != 'PUT')
		                	<input type="file" name="an_doc" class="form-control" placeholder="an_doc" value="">
		                @endif
		                @if(!empty($stock_internal_movement->document))
		                	<a href="{{ \Storage::disk('public')->url($stock_internal_movement->document) }}" target="_blank">See Doc</a>
		                @endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	
	@if(!empty($method))
	@if($method == 'POST')
	<div class="box-footer">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="disclaimer" required="required"> Saya menjamin bahwa data yang saya masukan adalah benar
			</label>
		</div>
		<br>
		<button type="submit" class="btn btn-info">Simpan</button>
	</div>
	@elseif($method == 'PUT')
	@endif
	@endif
	<!-- /.box-footer -->
</form>

@section('js')
<script>
    $(document).ready(function () {
        $('select[id="inputcontract"]').change(function(){
            var id = $(this).val();
            console.log('id', id);
            $.ajax({
                type: "GET",
                url: "/stock_transfer_order/getspk/" + id,
                success: function (data) {
                    $('select[id="spk_number"]').empty();
                    console.log('data', data);
                    $('select[id="spk_number"]').append('<option value="" selected>-- Pilih No. SPK --</option>');
                    $.each(data, function (key, value) {
                        $('select[id="spk_number"]').append('<option value="' + value.number_spk + '">' + value.number_spk + '</option>');
                    });
                }
            });
        });
	        //ain destination hanya divre
	        $('select[name="destination_id"]').on('change', function(){
	            var city_id = $(this).val();
	            var party_type = 'branch';

	            $.ajax({
	                type: 'GET',
	                url: '/parties/get-list',
	                data: {
	                    city_id: city_id,
	                    party_type: party_type,
	                    name: 'consignee_id',
	                    id: 'consignee_id',
	                },
	                success:function(responseHtml) {
	                    $('select[name="consignee_id"]').html(responseHtml);
	                }
	            })
	        });
	    

	    // 	//ain origin hanya divre
	    //     $('select[name="origin_id"]').on('change', function(){
	    //         var city_id = $(this).val();
	    //         var party_type = 'branch';

	    //         $.ajax({
	    //             type: 'GET',
	    //             url: '/parties/get-list',
	    //             data: {
	    //                 city_id: city_id,
	    //                 party_type: party_type,
	    //                 name: 'shipper_id',
	    //                 id: 'shipper_id',
	    //             },
	    //             success:function(responseHtml) {
	    //                 $('select[name="shipper_id"]').html(responseHtml);
	    //             }
	    //         })
	    //     });
	    // <?php
	    // 	}
	   	// ?>
    });


</script>
@endsection