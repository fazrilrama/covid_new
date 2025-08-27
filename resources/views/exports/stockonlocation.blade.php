<div class="content-loader">

<table>
    <thead>
    
    <tr>
        <th><b>Laporan Stock Per Lokasi</b></th>
    </tr>
       <tr> 
        <th><b>{{ $project }}</b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th>{{ $warehouse ? $warehouse->name : '' }}</th>
        </tr>
        <tr>
        @if($date == null || $date == '')
        <th><b>Sampai dengan: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</b></th>
        @endif
        </tr>

    </thead>
</table>
<table border="1" width="100%">
    <thead>
        <tr>
            <th >Storage</th>
            <th >SKU:</th>
            <th >Nama SKU:</th>
            <th >Stock</th>
            <th >UoM:</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d) 
        <tr>
            <td>{{ $d['storages'] }}</td>
            <td>{{ $d['sku'] }}</td>
            <td>{{ $d['sku_name'] }}</td>
            <td>{{ $d['stock'] }}</td>
            <td>{{ $d['uom_name'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>