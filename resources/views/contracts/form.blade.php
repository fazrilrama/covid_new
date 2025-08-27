<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Project</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="project_id">
					@foreach($projects as $projectValue)
					<option value="{{$projectValue->id}}" @if($contract->project_id == $projectValue->id){{'selected'}}@endif>{{$projectValue->project_id}} - {{$projectValue->name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Charge Method</label>
			<div class="col-sm-9">
				<select class="form-control" name="charge_method">
					<option value="" selected disabled>
						Select Charge Method
					</option>
					<option value="Weight Based" @if($contract->charge_method == 'Weight Based'){{'selected'}}@endif>
						Weight Based
					</option>
					<option value="Volume Based" @if($contract->charge_method == 'Volume Based'){{'selected'}}@endif>
						Volume Based
					</option>
					<option value="Unit Based" @if($contract->charge_method == 'Unit Based'){{'selected'}}@endif>
						Unit Based
					</option>
					<option value="CW Based" @if($contract->charge_method == 'CW Based'){{'selected'}}@endif>
						CW Based
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="company_id" class="col-sm-3 control-label">Charge Space</label>
			<div class="col-sm-9">
				<select class="form-control" name="charge_space">
					<option value="" selected disabled>
						Select Charge Space
					</option>
					<option value="Fix" @if($contract->charge_space == 'Fix'){{'selected'}}@endif>
						Fix
					</option>
					<option value="Variable" @if($contract->charge_space == 'Variable'){{'selected'}}@endif>
						Variable
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="number_contract" class="col-sm-3 control-label">No. Contract</label>
			<div class="col-sm-9">
				<input type="text" name="number_contract" class="form-control" placeholder="No. Contract" value="{{old('number_contract', $contract->number_contract)}}">
			</div>
		</div>
		<div class="form-group">
            <label for="do_attachment" class="col-sm-3 control-label">Contract Document</label>
            <div class="col-sm-9">
                <input type="file" name="contract_doc" class="form-control" placeholder="SPTB" value="{{old('contract_doc', $contract->contract_doc)}}">
                @if(!empty($contract->contract_doc))
                	<a href="{{ \Storage::disk('public')->url($contract->contract_doc) }}" target="_blank">See Doc</a>
                @endif
            </div>
        </div>
		<div class="form-group required">
			<label for="start_date" class="col-sm-3 control-label">Start Date</label>
			<div class="col-sm-9">
				<input type="text" name="start_date" class="datepicker-normal form-control" placeholder="Start Date" value="{{old('start_date', $contract->start_date)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="end_date" class="col-sm-3 control-label">End Date</label>
			<div class="col-sm-9">
				<input type="text" name="end_date" class="end-datepicker-normal form-control" placeholder="End Date" value="{{old('end_date', $contract->end_date)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="space_allocated" class="col-sm-3 control-label">Space</label>
			<div class="col-sm-9">
				<input type="number" name="space_allocated" class="form-control" placeholder="Space (m2)" value="{{old('space_allocated', $contract->space_allocated)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_space" class="col-sm-3 control-label">Unit Space</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_space">
					<option value="" selected disabled>
						Select Unit Space
					</option>
					<option value="(m2)" @if($contract->unit_space == '(m2)'){{'selected'}}@endif>
						(m2)
					</option>
					<option value="(m3)" @if($contract->unit_space == '(m3)'){{'selected'}}@endif>
						(m3)
					</option>
					<option value="Kg" @if($contract->unit_space == 'Kg'){{'selected'}}@endif>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_space" class="col-sm-3 control-label">Tariff Space</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_space" class="form-control" placeholder="Tariff Space" value="{{old('tariff_space', $contract->tariff_space)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_handling_in" class="col-sm-3 control-label">Unit Handling In</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_handling_in">
					<option value="" selected disabled>
						Select Unit Handling In
					</option>
					<option value="(m3)" @if($contract->unit_handling_in == '(m3)'){{'selected'}}@endif>
						(m3)
					</option>
					<option value="Kg" @if($contract->unit_handling_in == 'Kg'){{'selected'}}@endif>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_handling_in" class="col-sm-3 control-label">Tariff Handling In</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_handling_in" class="form-control" placeholder="Tariff Handling In" value="{{old('tariff_handling_in', $contract->tariff_handling_in)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="unit_handling_out" class="col-sm-3 control-label">Unit Handling Out</label>
			<div class="col-sm-9">
				<select class="form-control" name="unit_handling_out">
					<option value="" selected disabled>
						Select Unit Handling Out
					</option>
					<option value="(m3)" @if($contract->unit_handling_out == '(m3)'){{'selected'}}@endif>
						(m3)
					</option>
					<option value="Kg" @if($contract->unit_handling_out == 'Kg'){{'selected'}}@endif>
						Kg
					</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="tariff_handling_out" class="col-sm-3 control-label">Tariff Handling Out</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="tariff_handling_out" class="form-control" placeholder="Tariff Handling Out" value="{{old('tariff_handling_out', $contract->tariff_handling_out)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="is_active" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
				<select class="form-control" name="is_active">
					<option value="1" @if($contract->is_active == 1){{'selected'}}@endif>Aktif</option>
					<option value="0" @if($contract->is_active == 0){{'selected'}}@endif>Tidak Aktif</option>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Commodity</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id" required>
                    <option value="" selected disabled>-- Pilih Commodity --</option>
					@foreach($commodities as $commodity)
					<option value="{{$commodity->id}}" @if(old('commodity_id', $contract->commodity_id) == $commodity->id){{'selected'}}@endif>{{$commodity->code}} - {{ $commodity->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>