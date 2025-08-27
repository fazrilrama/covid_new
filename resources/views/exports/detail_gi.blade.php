<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>GOOD ISSUE REPORT</b></th>
    </tr>
       <tr> 
        <th><b>{{ $project->name }}</b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th><b>s/d {{ \Carbon\Carbon::now()->format('d-m-Y') }}</b></th>
        </tr>

    </thead>
</table>
<table>
    <thead>
    <tr>
        <th rowspan="2">Kode GI</th>
        <th rowspan="2">Dibuat Pada</th>
        <th rowspan="2">Origin</th>
        <th rowspan="2">Destination</th>
        <th rowspan="2">Shipper</th>
        <th rowspan="2">Consignee</th>
        <th rowspan="2">ETA</th>
        <th rowspan="2">Received Date</th>
        <th rowspan="2">Received By</th>
        <th colspan="3">Detail Barang</th>
        <th rowspan="2">Status</th>

    </tr>
    <tr>
        <th>Item:</th>
        <th>Qty:</th>
        <th>UoM:</th>
        
    </tr>
    </thead>
    <tbody>
        @foreach($deliveries as $delivery)
        <tr>
            <td style="text-align: center;"><b>{{ $delivery->code }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->created_at }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->origin->name }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->destination->name }}</b></td>
            <td style="word-wrap:break-word"> <b>{{ $delivery->shipper->name }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->consignee->name }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->eta }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->updated_at }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $delivery->received_by }}</b></td>
            <td>
                {{ $delivery->item_name }}
            </td>
            <td>
                {{number_format($delivery->qty, 0, ',', '') }}
            </td>
            <td>
                    {{$delivery->uom_name }}
            </td>

            <td style="word-wrap:break-word"><b>{{ $delivery->status == 'Processed'  ? 'Loading' : ($delivery->status == 'Completed' ? 'Siap Untuk Diantarkan' : 'Received')}}</b></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>