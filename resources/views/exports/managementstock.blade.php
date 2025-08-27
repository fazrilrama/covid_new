<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Management Stock</b></th>
    </tr>
       <tr> 
        <th><b>{{ $project }}</b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        @if($date == null || $date == '')
        <th><b>s/d {{ \Carbon\Carbon::now()->format('d-m-Y') }}</b></th>
        @else 
        <th><b>{{$date}}</b></th>
        @endif
        </tr>

    </thead>
</table>
<table>
    <thead>
    <tr>
        <th>SKU</th>
        <th>Nama SKU</th>
        <th>UoM</th>
        <th>Stock Awal</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Stock Akhir</th>
    </tr>
    </thead>
    <tbody>
        @foreach($stocks as $user)
        <tr>
            <td>{{ $user['sku'] }}</td>
            <td>{{ $user['sku_name'] }}</td>
            <td>{{ $user['uom_name'] }}</td>
            <td>{{ $user['begining'] }}</td>
            <td>{{ $user['after_begining_in'] }}</td>
            <td>{{ $user['after_begining_out'] }}</td>
            <td>{{ $user['stock'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>