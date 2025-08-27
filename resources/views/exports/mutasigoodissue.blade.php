<div class="content-loader">

<table>
    <thead>
    
    <tr>
        <th><b>Report Mutasi Good Issue</b></th>
    </tr>
       <tr> 
        <th><b>{{ $project->name }}</b></th>
        </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        @if($tanggal_filter == null || $tanggal_filter == '')
        <th><b>s/d {{ \Carbon\Carbon::now()->format('d-m-Y') }}</b></th>
        @else 
        <th><b> Report Filter: {{$tanggal_filter}}</b></th>
        @endif
        </tr>

    </thead>
</table>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Tanggal:</th>
            <th>Sisa Pengiriman Awal:</th>
            <th>Pengiriman Dibuat:</th>
            <th>Total In Delivery</th> 
            <th>Sampai Tujuan:</th>
            <th>Outstanding</th>
            <th>Akumulasi:</th>
        </tr>

    </thead>

    <tbody> 
        @foreach($collections as $collection)
        <tr>
            <td>{{ $collection['date'] }}</td>
            <td>{{ $collection['begining'] }}</td>
            <td>{{ $collection['receiving'] }}</td>
            <td>{{ $collection['begining'] + $collection['receiving'] }}</td>
            <td>{{ $collection['delivery'] }}</td>
            <td>{{ $collection['begining'] + $collection['receiving'] - $collection['delivery'] }}</td>
            <td>{{ $collection['closing'] }}</td>
            <td></td>


        </tr>
        @endforeach
    </tbody>
</table>
</div>