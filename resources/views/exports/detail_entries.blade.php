<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>INBOUND REPORT</b></th>
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
        <th>Code</th>
        <th>SKU:</th>
        <th>Nama SKU:</th>
        <th>Qty</th>
        <th>UoM</th>
        <th>Tanggal dibuat </th>
    </tr>
    </thead>
    <tbody>
        @foreach($stocks as $user)
        <tr>
            <td style="word-wrap:break-word"><b>{{ $user->code }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $user->sku }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $user->item_name }}</b></td>
            <td style="word-wrap:break-word"><b>{{ number_format($user->qty, 0, ',', '') }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $user->uom }}</b></td>
            <td style="word-wrap:break-word"><b>{{ $user->created_at }}</b></td>

        </tr>
        @endforeach
    </tbody>
</table>
</div>