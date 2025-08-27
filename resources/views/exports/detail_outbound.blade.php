<table>
    <thead>
    <tr>
        <th><b>OUTBOUND REPORT</b></th>
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
        <th>From</th>
        <th>SKU</th>
        <th>Description</th>
        <th>Date</th>
        <th>Destination</th>
        <th>Doc No</th>
        <th>PIC</th>
        <th>Driver</th>
        <th>Plat No</th>
        <th>Qty</th>
        <th>UoM</th>
        <th>Ref Code</th>
    </tr>
    </thead>
    <tbody>
        @foreach($stocks as $user)
        <tr>
            <td><b>{{ $user->parties_name }}</b></td>
            <td><b>{{ $user->sku }}</b></td>
            <td><b>{{ $user->item_name }}</b></td>
            <td><b>{{ \Carbon\Carbon::parse($user->control_date )->formatLocalized('%d %B %Y')}}</b></td>
            <td><b>{{ $user->consignee_name }}</b></td>
            <td><b>{{ $user->ref_code }}</b></td>
            <td><b>{{ $user->pic }}</b></td>
            <td><b>{{ $user->driver_name }}</b></td>
            <td><b>{{ $user->police_number }}</b></td>
            <td><b>{{ number_format($user->qty, 0, ',', '') }}</b></td>
            <td><b>{{ $user->uom }}</b></td>
            <td><b>{{ $user->item_ref_code }}</b></td>
        </tr>
        @endforeach
    </tbody>
</table>