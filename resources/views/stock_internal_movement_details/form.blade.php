<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body col-sm-7">
		<div class="form-group">
			<label for="stock_advance_notice_id" class="col-sm-3 control-label">
				Internal Movement
			</label>
			<div class="col-sm-9">
				<p class="form-control-static">{{$stock_internal->code}}</p>
				<input type="hidden" name="internal_movement_id" value="{{$stock_internal->id}}">
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Storage</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="storages" id="storages" style="width: 100% !important;"  required>
					<option value="" disabled selected>-- Pilih Storage --</option>
					@if($method == 'POST')
                    @foreach($storages as $storage)
					<option value="{{ $storage->id }} ">{{$storage->code}}</option>
					@endforeach
					@else
					@foreach($storages as $storage)
					<option value="{{ $storage->id }}" {{ $stock_internal_movement_detail->origin_storage_id == $storage->id ? 'selected' : '' }}>{{$storage->code}}</option>
					@endforeach
					@endif
				</select>
                <input type="hidden" name="stock">
			</div>
		</div>
		<div class="detail_container_wrapper">
		@if($method == 'PUT')
			<div class="form-group">
				<label for="item_id" class="col-sm-3 control-label">Item</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="item" id="item" style="width: 100% !important;"  required>
						@if(!$detail)
						<option value="{{ $stock_internal_movement_detail->item_id }}"  data-code="{{ $stock_internal_movement_detail->ref_code }}" selected>{{ $stock_internal_movement_detail->item->sku }} - {{ $stock_internal_movement_detail->item->name }} | {{ $stock_internal_movement_detail->ref_code }} | 0 </option>
						@foreach($origin_storage as $storage)
						<option value="{{ $storage['item'] }}" data-code="{{ $storage['ref_code'] }}" {{ $storage['ref_code'] == $stock_internal_movement_detail->ref_code && $storage['item'] == $stock_internal_movement_detail->item_id ? 'selected' : '' }} >{{ $storage['sku'] }} | {{ $storage['ref_code'] }} | {{ $storage['stock'] }}</option>
						@endforeach
						@else
						@foreach($origin_storage as $storage)
						<option value="{{ $storage['item'] }}" data-code="{{ $storage['ref_code'] }}" {{ $storage['ref_code'] == $stock_internal_movement_detail->ref_code && $storage['item'] == $stock_internal_movement_detail->item_id ? 'selected' : '' }} >{{ $storage['sku'] }} - {{ $storage['sku_name'] }} | {{ $storage['ref_code'] }} | {{ $storage['stock'] }}</option>
						@endforeach
						@endif
					</select>
						
				</div>
			</div>
		@endif
		</div>
		<!-- <input type="hidden" name="storage_id" value="{{ old('storage_id') }}" /> -->
		<div class="content_wrapper">
		@if($method == 'PUT')
			<div class="form-group required">
				<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
				<div class="col-sm-9">  
					<input type="text" name="ref_code" value="{{ $stock_internal_movement_detail->ref_code }}" class="form-control" placeholder="Ref Code" required readonly>
				</div>
			</div>
			<div class="form-group required">
				<label for="group_reference" class="col-sm-3 control-label">Stock Saat Ini</label>
				<div class="col-sm-9">  
					<input type="text" name="stock" value="{{ $detail ? $detail[0]['stock'] : '0' }}" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group required">
				<label for="qty" class="col-sm-3 control-label">Qty</label>
				<div class="col-sm-9">
				<input type="number" step="0.01" id="qty_movement" name="qty_movement" class="form-control" placeholder="qty"  value="{{ $stock_detial }}" required>
				</div>
				@if($method == 'PUT')
					
					<input type="hidden" step="0.01" name="qty_old" value="">
				@endif
			</div>
			<div class="form-group">
				<label for="destination_storage" class="col-sm-3 control-label">Destination Storage</label>
				<div class="col-sm-9">
					<select class="form-control select2" name="dest_storage" id="dest_storage" style="width: 100% !important;"  required>
						<option value="" selected disabled>-- Pilih Destination Storage --</option>
						@foreach($storages_exeption as $storage)
						<option value="{{ $storage->id }}" {{$storage->id == $stock_internal_movement_detail->dest_storage_id ? 'selected' : '' }}>{{ $storage->code }}</option>
						@endforeach
					</select>
						
				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-info pull-right">Simpan</button>
			</div>
		</div>
		@endif
	</div>
	<!-- /.box-body -->
	
	<!-- /.box-footer -->
</form>
<input type="hidden" id="method" value="{{ $method }}">
<div id="detail-url" url="#" data-url="{{ route('stock_internal_movement_details.storage', ':id') }}"></div>
<template id="detail_container_render">
<div class="form-group">
	<label for="item_id" class="col-sm-3 control-label">Item</label>
	<div class="col-sm-9">
		<select class="form-control select2" name="item" id="item" style="width: 100% !important;"  required>
			<option value="" selected disabled>-- Pilih Item/Ref Code --</option>
			@{{ #dataDetail}}
			<option value="@{{ item }}" data-code="@{{ ref_code }}">@{{ sku }} - @{{ sku_name  }} | @{{ ref_code }} | @{{ stock }}</option>
			@{{/dataDetail}}
		</select>
			
	</div>
</div>
</template>
<template id="ref_code_detail_render">
	@{{ #dataDetail}}
	<div class="form-group required">
		<label for="group_reference" class="col-sm-3 control-label">Group Ref</label>
		<div class="col-sm-9">  
			<input type="text" name="ref_code" value="@{{ ref_code }}" class="form-control" placeholder="Ref Code" readonly>
		</div>
	</div>
	<div class="form-group required">
		<label for="group_reference" class="col-sm-3 control-label">Stock Saat Ini</label>
		<div class="col-sm-9">  
			<input type="text" name="stock" value="@{{ stock }}" class="form-control" readonly>
		</div>
	</div>

	<div class="form-group required">
		<label for="qty" class="col-sm-3 control-label">Qty</label>
		<div class="col-sm-9">
			<input type="number" step="0.01" id="qty_movement" name="qty_movement" class="form-control" placeholder="qty"  value="@{{ stock }}" required>
		</div>
		@if($method == 'PUT')
			<input type="hidden" step="0.01" name="old_qty" value="" max="@{{ stock }}" required>
		@endif
	</div>
	<div class="form-group">
		<label for="destination_storage" class="col-sm-3 control-label">Destination Storage</label>
		<div class="col-sm-9">
			<select class="form-control select2" name="dest_storage" id="dest_storage" style="width: 100% !important;"  required>
				<option value="" selected disabled>-- Pilih Destination Storage --</option>
				@{{ #storage}}
				<option value="@{{ id }}">@{{ code }}</option>
				@{{/storage}}
			</select>
				
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-info pull-right">Simpan</button>
	</div>
	@{{/dataDetail}}
</template>


@section('custom_script')
	<script src='{{ asset('vendor/mustache/js/mustache.min.js') }}'></script>
    <script src="{{ asset('vendor/replaceSymbol/replaceSymbol.js') }}"> </script>
	
    <script>
		$('#storages').on('change', function(){
			$('.select2').select2();
			var url = replaceSymbol('#detail-url', 'data-url', $(this).val());
            $.get(url, function (data, textStatus, xhr) {
                $('.detail_container_wrapper').html('');
                $('.detail_container_wrapper').append(Mustache.render($('#detail_container_render').html(), {
                    dataDetail : data
                }));
				$('.content_wrapper').html('');	
				$('#item').on('change', function(){
					$('.select2').select2();
					// alert($(this).find(':selected').data('code'));
					optional = '?item_id='+$(this).val() +'&ref_code='+$(this).find(':selected').data('code')
					$.get(url+''+optional, function (data, textStatus, xhr) {
						$('.content_wrapper').html('');	
						$('.content_wrapper').append(Mustache.render($('#ref_code_detail_render').html(), {
							dataDetail : data
						}));
					});
				});
            });
			
		});
		$('#item').on('change', function(){
			$('.select2').select2();
			// alert($(this).find(':selected').data('code'));
			var url = replaceSymbol('#detail-url', 'data-url', $(this).val());
			optional = '?item_id='+$(this).val() +'&ref_code='+$(this).find(':selected').data('code')

			$('.content_wrapper').html('');	
			$.get(url+''+optional, function (data, textStatus, xhr) {
				$('.content_wrapper').html('');	
				$('.content_wrapper').append(Mustache.render($('#ref_code_detail_render').html(), {
					dataDetail : data
				}));
			});
		});
		
	</script>

@endsection