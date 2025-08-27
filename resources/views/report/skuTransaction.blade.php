@extends('adminlte::page')

@section('title', 'Integrated Logistics Solution')

@section('content_header')
    <h1>Laporan Transaksi per Item</h1>
@stop

@section('content')

    @include('report.info')
    @section('additional_field')
        <div class="col-sm-6">

            <div class="form-group">
                <label for="date_to" class="col-sm-4 col-form-label">Item</label>
                <div class="col-sm-8">
                    <select name="item" id="item" class="form-control select2">
                        @foreach($items as $itemOption)
                            <option value="{{ $itemOption->id }}" {{ !empty($data['item']) ? ($data['item'] == $itemOption->id ? 'selected' : '') : '' }}>
                                {{ $itemOption->sku . ' - ' . $itemOption->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endsection

    @include('report.searchDateForm')

    @if($search)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm" width="100%" id="dtBasicExample">

            <thead>
                <tr>
                    <th class="no-sort">No:</th>
                    <th>SKU:</th>
                    <th>Warehouse:</th>
                    <th>Transaction Number:</th>
                    <th>Date:</th>
                    <th>Qty:</th>
                    <th>Weight:</th>
                    <th>Volume:</th>
                    <th>UOM:</th>
                    <th>Status:</th>
                    <th>Type:</th>

                </tr>
            </thead>

            <tbody>
                @foreach($collections as $collection)
                    <tr>
                        <td>{{ $collection->item_index }}</td>                        
                        <td>{{ $collection->item_sku }}</td>
                        <td>{{ $collection->item_warehouse }}</td>
                        <td>{{ $collection->item_code }}</td>
                        <td>{{ \Carbon\Carbon::parse($collection->item_control_date)->format('Y-m-d') }}</td>
                        <td>{{ number_format($collection->item_qty, 2, ',', '.') }}</td>
                        <td>{{ number_format($collection->item_weight, 2, ',', '.') }}</td>
                        <td>{{ number_format($collection->item_volume, 2, ',', '.') }}</td>
                        <td>{{ $collection->item_uom_name }}</td>
                        <td>{{ $collection->status }}</td>
                        <td>{{ $collection->type }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total Barang : {{ number_format($stock, 2, ',', '.') }} </b></td>
                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

@endsection
@section('custom_script')
<script>
    var table = $('#dtBasicExample').DataTable({
        "order": [[ 4, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,5,6,7,8,9,10] }
        ]
    });

    // var count = 0;
	// var count_aktif_kurang =0;
	// count_aktif_bulan = 0;
	// var filterAllDataAktifLebih = table.rows().data().each(function(val, i) {
	// 	// console.log(val[5], val[5] == '.Aktif');
	// 	if (val[10] == 'inbound'){
    //         // console.log(val[6])
    //         text = val[5].substring(0, val[5].indexOf(','));
    //         text = text.split('.').join("");
	// 		count = count + parseFloat(text) 
    //         console.log(count);
	// 	};
	// 	if (val[10] == 'outbound'){
    //         text = val[5].substring(0, val[5].indexOf(','));
    //         text = text.split('.').join("");
	// 		count_aktif_kurang += parseFloat(text) 
	// 	};
	// })
	// // $('#aktif_mati').text(count.toFixed(2));
	// $('#end_of_month').text(count_aktif_kurang.toFixed(2));
    // $('#sisa').text(($('#aktif_mati').text() - count_aktif_kurang))
</script>

@endsection