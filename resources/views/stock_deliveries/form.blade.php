<!-- form start -->
<form id="changeAttributeDelivery" action="{{ $action }}" method="{{$method}}" class="form-horizontal" enctype="multipart/form-data">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="{{$type}}">
				<input type="hidden" name="method_form_stock_deliveries" id="method_form_stock_deliveries" value="{{$method}}">
				<div class="form-group required">
					<label for="stock_entry_id" class="col-sm-3 control-label">{{ __('lang.document_reference') }} #</label>
					<div class="col-sm-9">
						<select class="form-control" name="stock_entry_id" id="stock_entry_id">
							<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.document_reference') }} --</option>
							@foreach($stock_entries as $id => $value)
							<option value="{{$id}}" @if(old('stock_entry_id',$stockDelivery->stock_entry_id) == $id){{'selected'}}@endif>
								{{$value}}
							</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="transport_type_id" class="col-sm-3 control-label">{{ __('lang.type_transpotation') }}</label>
					<div class="col-sm-9">
						<select class="form-control" name="transport_type_id" id="transport_type_id" readonly>
							<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.type_transpotation') }} --</option>
							@foreach($transport_types as $id => $value)
							<option value="{{$id}}" @if(old('transport_type_id',$stockDelivery->transport_type_id) == $id){{'selected'}}@endif>{{$value}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
					<div class="col-sm-9">
						<input type="text" id="ref_code" name="ref_code" readonly class="form-control" placeholder="{{ __('lang.customer_reference') }}" value="{{old('ref_code', $stockDelivery->ref_code)}}">
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="vehicle_code_num" class="col-sm-3 control-label">Vehicle Code Number</label>
					<div class="col-sm-9">
						<input type="text" name="vehicle_code_num"  readonly id="vehicle_code_num" class="form-control" placeholder="Vehicle Code Number" value="{{old('vehicle_code_num', $stockDelivery->vehicle_code_num)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="vehicle_plate_num" class="col-sm-3 control-label">Vehicle Plate Number</label>
					<div class="col-sm-9">
						<input type="text" name="vehicle_plate_num" readonly id="vehicle_plate_num" class="form-control" placeholder="Vehicle Plate Number" value="{{old('vehicle_plate_num', $stockDelivery->vehicle_plate_num)}}">
					</div>
				</div> -->
				<div class="form-group">
					<label for="total_collie" class="col-sm-3 control-label">Total {{ __('lang.colly') }}</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_collie" id="total_collie" class="form-control" placeholder="Total Collie" value="{{old('total_collie', $stockDelivery->total_collie)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_weight" class="col-sm-3 control-label">Total Weight</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_weight" id="total_weight" class="form-control" placeholder="Total Weight" value="{{old('total_weight', $stockDelivery->total_weight)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" name="total_volume" id="total_volume" class="form-control" placeholder="Total Volume" value="{{old('total_volume', $stockDelivery->total_volume)}}">
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label">{{ __('lang.pickup_time') }}</label>
					<div class="col-sm-9">
						<input type="text" name="pickup_order" id="datetimepickerdisable" class="form-control" placeholder="Pick Up Order" value="{{old('pickup_order', $stockDelivery->pickup_order)}}">
					</div>
				</div>
				@if(!empty($stockDelivery->status))
				<div class="form-group required">
					<label for="ref_code" class="col-sm-3 control-label">Status</label>
					<div class="col-sm-9">	
						<p class="form-control-static">
							{{ ($stockDelivery->status == 'Pending') ? 'Planning' : $stockDelivery->status }}
						</p>
					</div>
				</div>
                @endif
			</div>
			<div class="col-sm-6">
				<div class="form-group required">
					<label for="origin_id" class="col-sm-3 control-label">{{ __('lang.origi') }}</label>
					<div class="col-sm-9">
						<select class="form-control" name="origin_id" readonly>
							<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.origin') }} --</option>
							@foreach($cities as $id => $value)
								<option value="{{$id}}" @if( old('origin_id',$stockDelivery->origin_id) == $id){{'selected'}}@endif>
									{{$value}}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="etd" class="col-sm-3 control-label" >{{ __('lang.etd') }}</label>
					<div class="col-sm-9">
						<input type="text" name="etd" id="etd" readonly class="form-control datepicker" placeholder="Est. Time Delivery" value="{{old('etd', $stockDelivery->etd)}}">
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_id" class="col-sm-3 control-label" >{{ __('lang.shipper') }}</label>
					<div class="col-sm-9">
						<select class="form-control" name="shipper_id" id="shipper_id" readonly>
							<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.shipper') }} --</option>
							@foreach($shippers as $party)
							<option value="{{$party->id}}" @if( old('shipper_id', $stockDelivery->shipper_id) == $party->id){{'selected'}}@endif>
								{{ $party->name}}
							</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="shipper_address" class="col-sm-3 control-label" >{{ __('lang.shipper_address') }}</label>
					<div class="col-sm-9">
						<input type="text" name="shipper_address" id="shipper_address" readonly class="form-control" placeholder="{{ __('lang.shipper_address') }}" value="{{old('shipper_address', $stockDelivery->shipper_address)}}">
					</div>
				</div>
				<div class="form-group required">
					<label for="destination_id" class="col-sm-3 control-label">{{ __('lang.destination') }}</label>
					<div class="col-sm-9">
						<select class="form-control" name="destination_id" readonly>
							<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.destination') }} --</option>
							@foreach($cities as $id => $value)
								<option value="{{$id}}" @if( old('destination_id',$stockDelivery->destination_id) == $id){{'selected'}}@endif>
									{{$value}}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="eta" class="col-sm-3 control-label" >{{ __('lang.eta') }}</label>
					<div class="col-sm-9">
						<input type="text" name="eta" id="eta" class="form-control end-datepicker" readonly placeholder="ETA" value="{{old('eta', $stockDelivery->eta)}}">
					</div>
				</div>
				{{-- @if($type == 'inbound') --}}
					<div class="form-group required">
						<label for="consignee_id" class="col-sm-3 control-label" >{{ __('lang.consignee') }}</label>
						<div class="col-sm-9">
							<select class="form-control" name="consignee_id" id="consignee_id" readonly>
								<option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.consignee') }}  --</option>
								@foreach($consignees as $party)
									<option value="{{$party->id}}" @if(old('consignee_id',$stockDelivery->consignee_id) == $party->id){{'selected'}}@endif>
										{{$party->name}}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label for="consignee_address" class="col-sm-3 control-label">{{ __('lang.consignee_address') }}</label>
						<div class="col-sm-9">
							<input type="text" name="consignee_address" id="consignee_address" readonly class="form-control" placeholder="Address" value="{{old('consignee_address', $stockDelivery->consignee_address)}}">
						</div>
					</div>
				{{-- @endif --}}
				<div class="form-group required">
					<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
					<div class="col-sm-9">
						@if($method == 'POST')
							<select required name="employee_name" id="employee_name" class="form-control" {{ ($stockDelivery->status == 'Completed' ? 'disabled' : '') }}>
	                            <option value="">-- {{ __('lang.choose') }} {{ __('lang.warehouse_checker') }} --</option>
	                            @foreach($warehouseOfficers as $warehouseOfficer)
	                                @php
	                                    $whofficerName = ($warehouseOfficer->last_name) ? $warehouseOfficer->first_name. ' ' . $warehouseOfficer->last_name : $warehouseOfficer->first_name;
	                                @endphp
	                                <option value="{{ $whofficerName }}" {{ old('employee_name', $stockDelivery->employee_name) == $whofficerName ? 'selected' : '' }}>
	                                	{{ $warehouseOfficer->user_id }}
	                                </option>
	                            @endforeach
	                        </select>
	                    @else
	                    	<input type="text" id="employee_name" name="employee_name" readonly class="form-control" placeholder="Warehouse Officer" value="{{old('employee_name', $stockDelivery->employee_name )}}">
	                    @endif
					</div>
				</div>

				<input type="hidden" name="status" value="Processed">
			</div>
		</div>
		@if(!empty($method))
			@if($method == 'POST')
				<div class="box-footer">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="disclaimer" required> {{ __('lang.disclaimer') }}
						</label>
					</div>
					<br>
					<button type="submit" class="btn btn-info"> {{ __('lang.save') }}</button>
				</div>
			@endif
		@endif
	</div>
	<!-- /.box-body -->
	<!-- <div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div> -->
	
	
	<!-- /.box-footer -->
</form>