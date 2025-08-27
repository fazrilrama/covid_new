<div class="table-responsive">
<table class="data-table table table-bordered table-hover no-margin" width="100%">
    <thead>
        <tr>
            <th>Date:</th>
            <th>Put Away#:</th>
            <th>Qty:</th>
            <th>Weight:</th>
            <th>Volume</th>
        </tr>
    </thead>
    
    <tbody>
    @php
        $totalQty = 0;
        $totalWeight = 0;
        $totalVolume = 0;
    @endphp
    @foreach($collections as $item)
        @php
            $itemQty = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('qty');
            $itemWeight = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('weight');
            $itemVolume = \App\StockEntryDetail::where('stock_entry_id', $item->id)->sum('volume');
            $totalQty += $itemQty;
            $totalWeight += $itemWeight;
            $totalVolume += $itemVolume;
        @endphp
        <tr>
            <td>{{ Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $itemQty }}</td>
            <td>{{ $itemWeight }}</td>
            <td>{{ $itemVolume }}</td>
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <td colspan="2" align="right">Total: </td>
        <td>{{ $totalQty }}</td>
        <td>{{ $totalWeight }}</td>
        <td>{{ $totalVolume }}</td>
    </tr>
</tfoot>
</table>
</div>
<script type="text/javascript">
    window.print();
</script>