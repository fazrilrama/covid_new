<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal" onsubmit="changeAttributePutaway()">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
	{{ method_field($method) }}
	@endif
	@csrf
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="type" value="{{$type}}">
				<input type="hidden" name="get_method_form_stock_entries" id="method_form_stock_entries" value="{{$method}}">
				<div class="form-group required">
					<label for="stock_transport_id" class="col-sm-3 control-label">{{ __('lang.document_reference') }} #</label>
					<div class="col-sm-9">
						<select {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} class="form-control" name="stock_transport_id" id="stock_transport_id">
                            <option value="" selected disabled>-- {{ __('lang.choose') }} {{ __('lang.document_reference') }} --</option>
							@foreach($stock_transports as $id => $value)
								<option value="{{$id}}" @if($stockEntry->stock_transport_id == $id){{'selected'}}@endif>
									{{$value}}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group required">
					<label for="ref_code" class="col-sm-3 control-label">{{ __('lang.customer_reference') }}</label>
					<div class="col-sm-9">
						<input id="ref_code" type="text" name="ref_code" class="form-control" placeholder="{{ __('lang.customer_reference') }}" value="{{old('ref_code', $stockEntry->ref_code)}}" readonly>
						<p class="help-block">{{ __('lang.customer_reference_desc') }}</p>
					</div>
				</div>
				@if($method == 'POST')
					<div class="form-group required">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
						<div class="col-sm-9">
	                        <select required name="employee_name" class="form-control">
	                            <option value="">-- {{ __('lang.choose') }} {{ __('lang.warehouse_checker') }} --</option>
	                        </select>
						</div>
					</div>
					@if($type == 'outbound')
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.consignee') }}</label>
						<div class="col-sm-9">
							<input id="consignee_entries" type="text" name="consignee_entries" class="form-control" placeholder="{{ __('lang.consignee') }}" value="" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.consignee_address') }}</label>
						<div class="col-sm-9">
							<input id="consignee_address_entries" type="text" name="consignee_address_entries" class="form-control" placeholder="Consingee Address" value="" disabled>
						</div>
					</div>
					@endif
				@else
					<div class="form-group required">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.warehouse_checker') }}</label>
						<div class="col-sm-9">
	                        <input type="text" id="employee_name" class="form-control" value="{{$stockEntry->employee_name}}" readonly>
						</div>
					</div>
					@if($type == 'outbound')
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.consignee') }}</label>
						<div class="col-sm-9">
							<input id="consignee_entries" type="text" name="consignee_entries" class="form-control" placeholder="{{ __('lang.consignee') }}" value="{{$stockEntry->stock_transport->consignee->name}}" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="employee_name" class="col-sm-3 control-label">{{ __('lang.consignee_address') }}</label>
						<div class="col-sm-9">
							<input id="consignee_address_entries" type="text" name="consignee_address_entries" class="form-control" placeholder="{{ __('lang.consignee_address') }}" value="{{$stockEntry->stock_transport->consignee->address}}" disabled>
						</div>
					</div>
					@endif
				@endif
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="total_pallet" class="col-sm-3 control-label">Total {{ __('lang.pallet') }}</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_pallet" class="form-control" required placeholder="Total Pallet" value="{{old('total_pallet', !empty($stockEntry->total_pallet) ? $stockEntry->total_pallet : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_labor" class="col-sm-3 control-label">Total {{ __('lang.labor') }}</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_labor" class="form-control" required placeholder="Total Labor" value="{{old('total_labor', !empty($stockEntry->total_labor) ? $stockEntry->total_labor : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_forklift" class="col-sm-3 control-label">Total Forklift</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_forklift" class="form-control" required placeholder="Total Forklift" value="{{old('total_forklift', !empty($stockEntry->total_forklift) ? $stockEntry->total_forklift : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_koli" class="col-sm-3 control-label">Total {{ __('lang.colly') }}</label>
					<div class="col-sm-9">
						<input type="number" step="1" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_koli" class="form-control" required placeholder="Total Koli" value="{{old('total_koli', !empty($stockEntry->total_koli) ? $stockEntry->total_koli : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_volume" class="col-sm-3 control-label">Total Volume</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_volume" class="form-control" required placeholder="Total Volume" value="{{old('total_volume', !empty($stockEntry->total_volume) ? $stockEntry->total_volume : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="total_berat" class="col-sm-3 control-label">Total Berat</label>
					<div class="col-sm-9">
						<input type="number" step="0.01" {{ ($stockEntry->status == 'Completed' ? 'disabled' : '') }} type="text" name="total_berat" class="form-control" required placeholder="Total Forklift" value="{{old('total_berat', !empty($stockEntry->total_berat) ? $stockEntry->total_berat : 0)}}">
					</div>
				</div>
				<div class="form-group">
					<label for="forklift_start_time" class="col-sm-3 control-label">Forklift {{ __('lang.start_time') }}</label>
					<div class="col-sm-9">
						<input type="text" name="forklift_start_time" class="form-control datetimepickerdisable" id="datetimepickerdisable" placeholder="Forklift Start Time" value="{{old('forklift_start_time', $stockEntry->forklift_start_time)}}" disabled>
					</div>
				</div>
				<div class="form-group">
					<label for="forklift_end_time" class="col-sm-3 control-label">Forklift {{ __('lang.end_time') }}</label>
					<div class="col-sm-9">
						<input type="text" name="forklift_end_time" class="form-control datetimepickerptend" id="datetimepickerptend" placeholder="Forklift End Time" value="{{old('forklift_end_time', $stockEntry->forklift_end_time)}}" disabled>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		<div class="btn-toolbar pull-right">
            @if($stockEntry->status !== 'Completed')
			<div class="btn-group" role="group">
				<button type="submit" class="btn btn-info">{{ __('lang.save') }}</button>
			</div>
            @endif
		</div>
	</div>
	<!-- /.box-footer -->
</form>
@section('js')
    <script>
        $(document).ready(function () {
            $('input[name="total_forklift"]').keyup(function(){
                var inputLength = $(this).val();
                console.log(inputLength);
                if(inputLength != ""){
                    if (inputLength != '0') {
                        $('input[name="forklift_start_time"]').removeAttr('disabled');
                        $('input[name="forklift_end_time"]').removeAttr('disabled');
                        return
                    }
                }
                $('input[name="forklift_start_time"]').attr('disabled', 'true');
                $('input[name="forklift_end_time"]').attr('disabled', 'true');
            })
            // $('.btn-completed').click(function() {
            //     $('form#otp').attr('action', "{{ route('stock_entries.status', ['stock_entry' => $stockEntry->id, 'status' => 'Completed']) }}");
            //     $('#modal-otp').modal('show');
            // })
        });
    </script>
@endsection