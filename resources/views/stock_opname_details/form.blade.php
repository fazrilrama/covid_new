<!-- form start -->
<form id="changeStock" action="{{ $action }}" method="POST" class="form-horizontal">
	@if( !empty($method) && in_array($method, ["PUT", "PATCH", "DELETE"]) )
        {{ method_field($method) }}
    @endif
    @csrf
	<div class="box-body col-sm-7">
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Item</label>
			<div class="col-sm-9">
				<select class="form-control select2" name="item" id="item" style="width: 100% !important;" {{$method == 'POST' ? '' : 'disabled' }}>
				<option value="" disabled selected>-- Pilih Item --</option>
				@if($method == 'POST')
				@foreach($warehouse_item as $item)
				<option value="{{ $item->id }}" data-project="{{ $item->project_id }}" data-uom="{{ $item->default_uom_id }}">{{ $item->sku }} - {{ $item->name }}</option>
				@endforeach
				@else
				@foreach($warehouse_item as $item)
				<option value="{{ $item->id }}" {{ $stock_opname_detail->item_id == $item->id ? 'selected' : '' }}>{{ $item->sku }} - {{ $item->name }}</option>
				@endforeach
				@endif
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Storage</label>
			<div class="col-sm-9">
			<select class="form-control select2" name="storage_id" id="storage_id" style="width: 100% !important;" required>
				<option value="" disabled selected>-- Pilih Storage --</option>
				@foreach($storages as $storage)
				<option value="{{ $storage->id }}" {{ $stock_opname_detail->storage_id == $storage->id ? 'selected' : '' }}>{{ $storage->code }}</option>
				@endforeach
			</select>
			</div>
		</div>

		<input type="hidden" value="" id="project_id" name="project_id">
		<input type="hidden" value="" id="uom_id" name="uom_id">

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Wina</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" id="stock_wina" name="stock_wina" class="form-control" placeholder="Stock Wina"  value="{{old('stock_wina', $stock_opname_detail->wina_stock)}}" required>
				
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">PO/STO Awal</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" id="sto_awal" name="sto_awal" class="form-control" placeholder="PO/STO Awal"  value="{{old('sto_awal', $stock_opname_detail->sto_awal)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">SO Awal</label>
			<div class="col-sm-9">
				<input type="number" step="0.001" id="so_awal" name="so_awal" class="form-control" placeholder="SO Awal"  value="{{old('so_awal', $stock_opname_detail->so_awal)}}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Menurut Kartu ADM Awal</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="stock_awal_adm" name="stock_awal_adm" class="form-control" placeholder="Stock Menurut Kartu ADM Awal"  value="{{old('stock_awal_adm', $stock_opname_detail->stock_awal_adm)}}" required>
				
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Menurut Kartu Phisik</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="phisik_awal" name="phisik_awal" class="form-control" placeholder="Stock Menurut Kartu ADM Awal"  value="{{old('phisik_awal', $stock_opname_detail->phisik_awal)}}" readonly>	
			</div>
		</div>
	
		<hr>Penerimaan <hr>			

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Realisasi ADM</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="realisasi_adm_penerimaan" name="realisasi_adm_penerimaan" class="form-control" placeholder="Realisasi ADM Penerimaan"  value="{{old('realisasi_adm_penerimaan', $stock_opname_detail->realisasi_adm_penerimaan)}}" required>
				
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Realisasi Phisik</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="realisasi_phisik_penerimaan" name="realisasi_phisik_penerimaan" class="form-control" placeholder="Realisasi Phisik"  value="{{old('realisasi_phisik_penerimaan', $stock_opname_detail->realisasi_phisik_penerimaan)}}" required>
				
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Susut Bar</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="susut_bar" name="susut_bar" class="form-control" placeholder="Susut Bar"  value="{{old('susut_bar', $stock_opname_detail->susut_bar)}}" required>
				
			</div>
		</div>

		<hr>Pengeluaran <hr>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Realisasi ADM</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="realisasi_adm_pengeluaran" name="realisasi_adm_pengeluaran" class="form-control" placeholder="Realisasi ADM Pengeluaran"  value="{{old('realisasi_adm_pengeluaran', $stock_opname_detail->realisasi_adm_pengeluaran)}}" required>
				
			</div>
		</div>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Realisasi Phisik</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="realisasi_phisik_pengeluaran" name="realisasi_phisik_pengeluaran" class="form-control" placeholder="Realisasi Phisik Pengeluaran"  value="{{old('realisasi_phisik_pengeluaran', $stock_opname_detail->realisasi_phisik_pengeluaran)}}" required>
				
			</div>
		</div>
		<hr>Stock Adm./Phisik Setelah Rekonsiliasi<hr>
		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Adm</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="stock_akhir_adm" name="stock_akhir_adm" class="form-control" placeholder="Stock Adm"  value="{{old('stock_akhir_adm', $stock_opname_detail->stock_akhir_adm)}}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">PO/STO</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="po_sto_akhir" name="po_sto_akhir" class="form-control" placeholder="PO/STO Akhir"  value="{{old('po_sto_akhir', $stock_opname_detail->po_sto_akhir)}}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">SO</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="so_akhir" name="so_akhir" class="form-control" placeholder="SO Akhir"  value="{{old('so_akhir', $stock_opname_detail->so_akhir)}}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Phisik Akhir</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="stock_phisik_akhir" name="stock_phisik_akhir" class="form-control" placeholder="Stock Phisik"  value="{{old('po_sto_akhir', $stock_opname_detail->stock_phisik_akhir)}}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="item_id" class="col-sm-3 control-label">Stock Taking</label>
			<div class="col-sm-9">
			<input type="number" step="0.001" id="stock_taking_akhir" name="stock_taking_akhir" class="form-control" placeholder="Stock Phisik"  value="{{old('stock_taking_akhir', $stock_opname_detail->stock_taking_akhir)}}" required>
			</div>
		</div>
		
		<div class="box-footer">
			<button type="submit" class="btn btn-info pull-right">Simpan</button>
		</div>
	</div>
</form>

@section('custom_script')
	<script>
		$('#item').on('change', function(){
			var value = $(this).find(':selected').data('project');
			var uom = $(this).find(':selected').data('uom');

			$('#project_id').val(value);
			$('#uom_id').val(uom);

		})
		function stock_adm(){
			var stock_awal_adm = parseFloat($('#stock_awal_adm').val()); 
			var realisasi_adm_penerimaan = parseFloat($('#realisasi_adm_penerimaan').val());
			var susut_bar = parseFloat($('#susut_bar').val());
			var realisasi_adm_pengeluaran = parseFloat($('#realisasi_adm_pengeluaran').val());

			var total = stock_awal_adm+realisasi_adm_penerimaan+susut_bar+realisasi_adm_pengeluaran;

			return total;

		}
		function sto_akhir()
		{
			var sto = $('#sto_awal').val();
			var realisasi_adm_penerimaan = parseFloat($('#realisasi_adm_penerimaan').val());
			var realisasi_phisik_penerimaan = parseFloat($('#realisasi_phisik_penerimaan').val());
			var susut_bar = parseFloat($('#susut_bar').val());

			var total =  sto+realisasi_adm_penerimaan-realisasi_phisik_penerimaan-susut_bar;
			return total;
		}

		function so_akhir()
		{
			var so = $('#so_awal').val();
			var realisasi_adm_pengeluaran = parseFloat($('#realisasi_adm_pengeluaran').val());
			var realisasi_phisik_pengeluaran = parseFloat($('#realisasi_phisik_pengeluaran').val());
			

			var total =  so+realisasi_adm_pengeluaran-realisasi_phisik_pengeluaran;
			return total;
		}

		function phisik_awal()
		{
			var sto = parseFloat($('#sto_awal').val());
			var so = parseFloat($('#so_awal').val());
			var stock_awal_adm = parseFloat($('#stock_awal_adm').val()); 
			var total =  stock_awal_adm - sto + so;
			return total;
		}

		function stock_fisik()
		{
			var realisasi_phisik_penerimaan = parseFloat($('#realisasi_phisik_penerimaan').val());
			var realisasi_phisik_pengeluaran = parseFloat($('#realisasi_phisik_pengeluaran').val());
			var total =phisik_awal() + realisasi_phisik_penerimaan - realisasi_phisik_pengeluaran
			return total;
		}
		function selisih()
		{
			var stock_taking_akhir = parseFloat($('#stock_taking_akhir').val());

			var total = stock_fisik() - stock_taking_akhir;
			return total;
		}


		$('#sto_awal').on('change', function(){
			
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())


		});

		$('#stock_taking_akhir').on('change', function(){
			
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())

		});

		$('#so_awal').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())

		});

		$('#stock_awal_adm').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())

		});

		$('#realisasi_adm_penerimaan').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())
		})

		$('#realisasi_phisik_penerimaan').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())
		})

		$('#susut_bar').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())
		})
		$('#realisasi_adm_pengeluaran').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())
		})

		$('realisasi_phisik_pengeluaran').on('change', function(){
			$('#phisik_awal').val(phisik_awal())
			$('#stock_akhir_adm').val(stock_adm())
			$('#po_sto_akhir').val(sto_akhir())
			$('#so_akhir').val(so_akhir())
			$('#selisih').val(selisih())
			$('#stock_phisik_akhir').val(stock_fisik())
		})

	</script>
@endsection