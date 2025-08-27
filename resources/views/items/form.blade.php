<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		@if($status == 'create')
			<div class="form-group required">
				<label for="company_id" class="col-sm-3 control-label">Project</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="project_id[]" multiple>
						<option value="" disabled>-- Pilih Project --</option>
						@foreach($projects as $project)
							<option value="{{$project->id}}">
								{{$project->project_id}} - {{$project->name}}
							</option>
						@endforeach
					</select>
				</div>
			</div>
		@else
			
			<div class="form-group required">
				<label for="warehouse_id" class="col-sm-3 control-label">Project</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="project_id[]" multiple required>
	                    <option value="" disabled>-- Pilih Project --</option>
						@foreach($projects as $project)
							<?php 
                                $project_dipilih = '';
                                
                                foreach($item_project[$item->id] as $data){
                                    $project_dipilih .= $data->project->project_id.' , ';
                                }
                            ?> 

                            @if(stristr($project_dipilih,$project->project_id))
                                <option value="{{ $project->id }}" selected>
                                	{{$project->project_id}} - {{ $project->name }}
                                </option>
                            @else
                                <option value="{{ $project->id }}">
                                	{{$project->project_id}} - {{ $project->name }}
                                </option>
                            @endif
						@endforeach
					</select>
				</div>
			</div>
		@endif
		<div class="form-group required">
			<label for="sku" class="col-sm-3 control-label">SKU</label>
			<div class="col-sm-9">
				<input type="text" name="sku" class="form-control" placeholder="Sku" value="{{old('sku', $item->sku)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="name" class="col-sm-3 control-label">Nama</label>
			<div class="col-sm-9">
				<input type="text" name="name" class="form-control" placeholder="Nama" value="{{old('name', $item->name)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="additional_reference" class="col-sm-3 control-label">Additional Reference</label>
			<div class="col-sm-9">
				<input type="text" name="additional_reference" class="form-control" placeholder="Additional Reference" value="{{old('additional_reference', $item->additional_reference)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-3 control-label">Description</label>
			<div class="col-sm-9">
				<textarea name="description" rows="10" class="form-control">{{old('description', $item->description)}}</textarea>
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Handling Tarif</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="handling_tarif" class="form-control" placeholder="Handling Tarif" value="{{old('handling_tarif', $item->handling_tarif)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="length" class="col-sm-3 control-label">Length (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="length" class="form-control" placeholder="Length" value="{{old('length', $item->length)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="width" class="col-sm-3 control-label">Width (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="width" class="form-control" placeholder="Width" value="{{old('width', $item->width)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="height" class="col-sm-3 control-label">Height (m)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="height" class="form-control" placeholder="Height" value="{{old('height', $item->height)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="volume" class="col-sm-3 control-label">Volume (m3)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="volume" class="form-control" placeholder="Volume" value="{{old('volume', $item->volume)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="weight" class="col-sm-3 control-label">Weight (kg)</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="weight" class="form-control" placeholder="Weight" value="{{old('weight', $item->weight)}}">
			</div>
		</div>
		<div class="form-group">
			<label for="min_qty" class="col-sm-3 control-label">Min Qty</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" name="min_qty" class="form-control" placeholder="Min Qty" value="{{old('min_qty', $item->min_qty)}}">
			</div>
		</div>
		<div class="form-group required">
			<label for="default_uom_id" class="col-sm-3 control-label">Default UOM</label>
			<div class="col-sm-9">
				<select class="form-control" name="default_uom_id">
					@foreach($uoms as $id => $value)
					<option value="{{$id}}" @if($item->default_uom_id == $id){{'selected'}}@endif>{{$value}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="commodity_id" class="col-sm-3 control-label">Komoditas</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="commodity_id">
					@foreach($commodities as $commodity)
					<option value="{{$commodity->id}}" @if($item->commodity_id == $commodity->id){{'selected'}}@endif>{{$commodity->code}} - {{ $commodity->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="type_id" class="col-sm-3 control-label">Jenis</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="type_id">
					@foreach($types as $type)
					<option value="{{$type->id}}" @if($item->type_id == $type->id){{'selected'}}@endif>{{ $type->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="packing_id" class="col-sm-3 control-label">Packing</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="packing_id">
					@foreach($packings as $packing)
					<option value="{{$packing->id}}" @if($item->packing_id == $packing->id){{'selected'}}@endif>{{ $packing->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="control_method_id" class="col-sm-3 control-label">Control Method</label>
			<div class="col-sm-9">
				<select class="form-control" name="control_method_id">
					@foreach($control_methods as $id => $value)
					<option value="{{$id}}" @if($item->control_method_id == $id){{'selected'}}@endif>{{$value}}</option>
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

@section('custom_script')
<script>
    $('input[name="length"]').keyup(() => { setVolume() });
    $('input[name="width"]').keyup(() => { setVolume() });
    $('input[name="height"]').keyup(() => { setVolume() });

    function setVolume(){
        var volume = $('input[name="length"]').val() * $('input[name="width"]').val() * $('input[name="height"]').val();
        $('input[name="volume"]').val(volume);
    }
</script>
@endsection