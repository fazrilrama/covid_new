<!-- form start -->
<form action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body">
		<div class="form-group required">
			<label for="contract_id" class="col-sm-3 control-label">Contract</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="contract_id">
					@foreach($contracts as $id => $value)
					<option value="{{$id}}" @if($spk->contract_id == $id){{'selected'}}@endif>{{$value}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label for="number_spk" class="col-sm-3 control-label">No. SPK</label>
			<div class="col-sm-9">
				<input type="text" name="number_spk" class="form-control" placeholder="No. SPK" value="{{old('number_spk', $spk->number_spk)}}">
			</div>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
	<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	<!-- /.box-footer -->
</form>