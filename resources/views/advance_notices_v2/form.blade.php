<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="{{$type}}" required="required">
				@if(!empty($advanceNotice->created_at))
				<div class="form-group">
					<label for="project_id" class="col-sm-3 control-label">Project</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{$advanceNotice->project->name}}</p>
					</div>
				</div>
				<div class="form-group">
					<label for="created_at" class="col-sm-3 control-label">{{__('lang.created_at')}}</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{$advanceNotice->created_at}}</p>
					</div>
				</div>
				@endif
				<div class="form-group required">
					<label for="contract_number" class="col-sm-3 control-label">{{__('lang.contract')}}</label>
					<div class="col-sm-9">
						@if($method == 'PUT')
							<p class="form-control-static">{{$advanceNotice->contract_number}}</p>
							<input type="hidden" name="contract_number" value="{{$advanceNotice->contract_number}}" required="required">
						@else
							<select name="contract_number" id="inputcontract" class="form-control select2" required="required">
	                            <option value="">-- {{__('lang.choose')}} Kontrak --</option>
	                            @foreach($contracts as $contract)
	                            <option value="{{ $contract->id }}" {{ old('contract_number', $advanceNotice->contract_number) == $contract->number_contract ? 'selected' : '' }}>{{ $contract->number_contract }}</option>
	                            @endforeach
	                        </select>
						@endif
					</div>
				</div>
				<div class="form-group required">
					<label for="spmp_number" class="col-sm-3 control-label">{{__('lang.spk')}}</label>
					<div class="col-sm-9">
						@if($method == 'PUT')
							<p class="form-control-static">{{$advanceNotice->spmp_number}}</p>
							<input type="hidden" name="spmp_number" value="{{$advanceNotice->spmp_number}}" required="required">
						@else
							<select name="spmp_number" id="spk_number" class="form-control" required="required">
	                    		<option value="">-- {{__('lang.choose')}} No. SPK --</option>
	                    	</select>
						@endif
					</div>
				</div>
				<div class="form-group">
		            <label for="an_doc" class="col-sm-3 control-label">
		            	@if($type == 'inbound') 
		            		PO {{__('lang.document')}}
		            	@else
		            		DO {{__('lang.document')}}
		            	@endif
		            </label>
		            <div class="col-sm-9">
		            	@if($method != 'PUT')
		                	<input type="file" name="an_doc" class="form-control" placeholder="an_doc" value="{{old('an_doc', $advanceNotice->an_doc)}}">
		                @endif
		                @if(!empty($advanceNotice->an_doc))
		                	<a href="{{ \Storage::disk('public')->url($advanceNotice->an_doc) }}" target="_blank">{{__('lang.see_doc')}}</a>
		                @endif
		            </div>
		        </div>
				<div class="form-group required">
					<label for="advance_notice_activity_id" class="col-sm-3 control-label">{{__('lang.activity')}}</label>
					<div class="col-sm-9">
						<select class="form-control" name="advance_notice_activity_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{__('lang.choose')}} {{__('lang.activity')}} --</option>
							@foreach($activities as $id => $value)
							<option value="{{$id}}" @if($advanceNotice->advance_notice_activity_id == $id){{'selected'}}@endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				
				<div class="form-group required">
					<label for="transport_type_id" class="col-sm-3 control-label">{{__('lang.type_transpotation')}}</label>
					<div class="col-sm-9">
						<select class="form-control" name="transport_type_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{__('lang.choose')}} {{__('lang.type_transpotation')}} --</option>
							@foreach($transport_types as $id => $value)
							<option value="{{$id}}" @if($advanceNotice->transport_type_id == $id){{'selected'}}@endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="owner" class="col-sm-3 control-label">{{ __('lang.ownership')}} </label>
					<div class="col-sm-9">
						<select class="form-control select2" name="owner" {{ $method != 'PUT' ?'': 'disabled' }}>
							<option value="" disabled selected>-- {{ __('lang.choose')}}  {{ __('lang.ownership')}}  --</option>
							@foreach($parties as $key => $value)
							<option value="{{$value->id}}" @if($advanceNotice->owner == $value->id){{'selected'}}@endif>{{$value->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="pic" class="col-sm-3 control-label">PIC</label>
					<div class="col-sm-9">
						@if($method != 'PUT')
							<select id="selectpic" class="form-control select2" name="pic">
								<option value="" selected disabled>--{{ __('lang.choose')}}  PIC / {{ __('lang.manual')}}  --</option>
								@foreach($pic as $value)
								<option value="{{ $value }}">{{ $value }}</option>
								@endforeach
							</select>
						@else
						<input {{ $method != 'PUT' ?'': 'readonly' }} type="text" name="pic" class="form-control" placeholder="PIC" value="{{old('pic', $advanceNotice->pic)}}">
						@endif
					</div>
				</div>
				
				<div class="form-group required">
					<label for="sptb_num" class="col-sm-3 control-label">{{ __('lang.customer_reference')}} </label>
					<div class="col-sm-9">
						<input {{ $method != 'PUT' ?'': 'readonly' }} type="text" name="ref_code" class="form-control" placeholder="{{ __('lang.customer_reference')}}" value="{{old('ref_code', $advanceNotice->ref_code)}}" required="required">
					</div>
				</div>
			
                @if(!empty($advanceNotice->status))
					<div class="form-group required">
						<label for="status" class="col-sm-3 control-label">Status</label>
						<div class="col-sm-9">
							<p class="form-control-static">
								@if(!Auth::user()->hasRole('CargoOwner'))
								{{ ($advanceNotice->status == 'Pending') ? 'Planning' : $advanceNotice->status }}
								@else
								{{ ($advanceNotice->status == 'Pending') ? 'Planning' : ($advanceNotice->status == 'Completed' ? 'Submitted' : $advanceNotice->status) }}
								@endif
							</p>
						</div>
					</div>
                @endif
			</div>
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="origin_id" class="col-sm-3 control-label">{{ __('lang.origin')}}</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="origin_id" id="origin_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{ __('lang.choose')}} {{ __('lang.origin')}} --</option>
							@foreach($cities as $id => $value)
							<option value="{{$id}}" @if($advanceNotice->origin_id == $id){{'selected'}}@endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label">{{ __('lang.etd')}}</label>
					<div class="col-sm-9">
						<input {{ $method != 'PUT' ?'': 'disabled' }} type="text" name="etd" class="datepicker form-control" placeholder="{{ __('lang.etd')}}" value="{{old('etd', $advanceNotice->etd)}}" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_id" class="col-sm-3 control-label">
						{{ __('lang.shipper')}}
					</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="shipper_id" id="shipper_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{ __('lang.choose')}} {{ __('lang.shipper')}}</option>
							@foreach($shippers as $party)
							<option value="{{$party->id}}" @if($advanceNotice->shipper_id == $party->id){{'selected'}}@endif>{{$party->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label">
						{{ __('lang.shipper_address')}} 
					</label>
					<div class="col-sm-9">
						<input {{ $method != 'PUT' ?'': 'readonly' }} type="text" id="shipper_address" name="shipper_address" class="form-control" placeholder="{{ __('lang.shipper_address')}}" value="{{old('shipper_address', $advanceNotice->shipper_address)}}" required="required">
					</div>
				</div>
				@if((isset($advanceNotice) && $advanceNotice->type == 'outbound') || $type == 'outbound')
					<div id="warehouse">
						<div class="form-group required">
							<label for="warehouse_id" class="col-sm-3 control-label">
								{{ __('lang.warehouse')}}
							</label>
							<div class="col-sm-9">
								<select class="form-control select2" name="warehouse_id" id="warehouse_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
									<option value="" disabled selected>-- {{ __('lang.choose')}} {{ __('lang.warehouse')}}</option>
									@foreach($warehouses as $wh)
									<option value="{{ $wh->id }}" {{ $wh->id == $advanceNotice->warehouse_id ? 'selected="selected"' : '' }}>{{ $wh->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				@endif
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label">{{ __('lang.destination')}}</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="destination_id" id="destination_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{ __('lang.choose')}} {{ __('lang.destination')}} --</option>
							@foreach($cities as $id => $value)
							<option value="{{$id}}" @if($advanceNotice->destination_id == $id){{'selected'}}@endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label">{{ __('lang.eta')}}</label>
					<div class="col-sm-9">
						<input {{ $method != 'PUT' ?'': 'disabled' }} type="text" name="eta" class="end-datepicker form-control" placeholder="Est. Time Arrival" value="{{old('eta', $advanceNotice->eta)}}" required="required">
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_id" class="col-sm-3 control-label">
						@if($type == 'inbound') {{ __('lang.branch')}} @else {{ __('lang.consignee')}} @endif
					</label>
					<div class="col-sm-9">
						<select class="form-control select2" name="consignee_id" id="consignee_id" {{ $method != 'PUT' ?'': 'disabled' }} required="required">
							<option value="" disabled selected>-- {{ __('lang.choose')}} --</option>
							@foreach($consignees as $party)
							<option value="{{$party->id}}" @if($advanceNotice->consignee_id == $party->id){{'selected'}}@endif>{{$party->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="consignee_address" class="col-sm-3 control-label">
						@if($type == 'inbound') {{ __('lang.branch_address')}} @else {{ __('lang.consignee_address')}} @endif
					</label>
					<div class="col-sm-9">
						<input {{ $method != 'PUT' ?'': 'readonly' }} type="text" id="consignee_address" name="consignee_address" class="form-control" placeholder="Address" value="{{old('consignee_address', $advanceNotice->consignee_address)}}" required="required">
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
				<input type="checkbox" name="disclaimer" required="required"> {{ __('lang.disclaimer') }}
			</label>
		</div>
		<br>
		<button type="submit" class="btn btn-info">{{ __('lang.save') }}</button>
	</div>
	@elseif($method == 'PUT')
	@endif
	@endif
	<!-- /.box-footer -->
</form>

@section('js')
<script>
    $(document).ready(function () {
        $('#selectpic').select2({
			tags: true
		});
		$('select[id="inputcontract"]').change(function(){
            var id = $(this).val();
            console.log('id', id);
            $.ajax({
                type: "GET",
                url: "/advance_notices/getspk/" + id,
                success: function (data) {
                    $('select[id="spk_number"]').empty();
                    console.log('data', data);
                    $('select[id="spk_number"]').append('<option value="">-- Pilih No. SPK --</option>');
                    $.each(data, function (key, value) {
                    	if(key == 0){
                    		$('select[id="spk_number"]').append('<option value="' + value.number_spk + '" selected>' + value.number_spk + '</option>');
                    	}
                    	else{
                    		$('select[id="spk_number"]').append('<option value="' + value.number_spk + '">' + value.number_spk + '</option>');
                    	}
                        
                    });
                }
            });
        });
        <?php
        	if($type == 'inbound'){
        ?>
	        //ain destination hanya divre
	        $('select[name="destination_id"]').on('change', function(){
	            var city_id = $(this).val();
	            var party_type = 'consignee,branch';

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
	    <?php
			}
	    	else{
	   	?>

	    	//ain origin hanya divre
	        $('select[name="origin_id"]').on('change', function(){
	            var city_id = $(this).val();
	            var party_type = 'shipper,branch';

	            $.ajax({
	                type: 'GET',
	                url: '/parties/get-list',
	                data: {
	                    city_id: city_id,
	                    party_type: party_type,
	                    name: 'shipper_id',
	                    id: 'shipper_id',
	                },
	                success:function(responseHtml) {
	                    $('select[name="shipper_id"]').html(responseHtml);
	                }
	            })
	        });


			$('select[name="shipper_id"]').on('change', function(){
				var contract_id = $('#inputcontract').val();
				var shipper_id = $(this).val();

	            $.ajax({
	                type: 'GET',
	                url: '/warehouses/get-list',
	                data: {
	                    shipper_id: shipper_id,
						contract_id: contract_id
	                },
	                success:function(responseHtml) {
	                    $('#warehouse').html(responseHtml);
	                }
	            })
			});

	    <?php
	    	}
	   	?>
    });


</script>
@endsection