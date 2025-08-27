<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Mutasi Stock</b></th>
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
        <th>Date:</th>
        <th>Item:</th>
        <th>UOM</th>
        <th>Begining:</th>
        <th>Receiving:</th>
        <th>In Standing</th>
        <th>Delivery:</th>
        <th>Out Standing</th>
        <th>Closing:</th>
    </tr>
    </thead>
    <tbody>
        @foreach($stocks as $collection)
        <tr>
        <td>{{ $collection['date'] }}</td>
        <td>{{ $collection['item'] }}</td>
        <td>{{ $collection['uom_name'] }}</td>
        <td>{{ number_format($collection['begining'], 2, ',', '.') }}</td>
        <td>{{ number_format($collection['receiving'], 2, ',', '.') }}</td>
        @if ($loop->last)
        <td>{{ number_format($jumlah_inhandling, 2, ',', '.') }}</td>
        @else
        <td>0,00</td>
        @endif
        <td>{{ number_format($collection['delivery'], 2, ',', '.') }}</td>
        @if ($loop->last)
        <td>{{ number_format($jumlah_outhandling, 2, ',', '.') }}</td>
        @else 
        <td>0,00</td>
        @endif
        
        <td>{{ number_format($collection['closing'], 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>