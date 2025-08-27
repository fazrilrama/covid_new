@extends('layouts.print')
@section('content')
        <h3>Loading Order</h3>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'>Loading Order#</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockTransport->code }}</td>
                </tr>
                <tr>
                    <td>Delivered From</td>
                    <td>:</td>
                    <td>{{ $stockTransport->origin->name }}</td>
                </tr>
                <tr>
                    <td>Customer Reference</td>
                    <td>:</td>
                    <td>{{ $stockTransport->code }}</td>
                </tr>
                <tr>
                    <td>Warehouse Officer</td>
                    <td>:</td>
                    <td>{{ $stockTransport->origin->name }}</td>
                </tr>
            </table>
        </div>
        
        <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
            <table width="100%">
                <tr>
                    <td width='45%'>Date and Time</td>
                    <td width='5%'>:</td>
                    <td width='50%'>{{ $stockTransport->created_at }}</td>
                </tr>
                <tr>
                    <td>Delivery To</td>
                    <td>:</td>
                    <td>{{ $stockTransport->destination->name }}</td>
                </tr>
                <tr>
                    <td>Start</td>
                    <td>:</td>
                    <td>{{ $stockTransport->destination->name }}</td>
                </tr>
                <tr>
                    <td>Finish</td>
                    <td>:</td>
                    <td>{{ $stockTransport->destination->name }}</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Descriptions</th>
                        <th>Group Reff.</th>
                        <th>Qty</th>
                        <th>Unit</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($stockTransport->details as $detail)
                    @if($detail->status <> 'canceled')
                    <tr>
                        <td>{{$detail->item->sku}}</td>
                        <td>{{$detail->item->name}}</td>
                        <td>{{$detail->qty}}</td>
                        <td>{{$detail->uom->name}}</td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>

        <div class="col-sm-12 space-top">
            <h5><strong>PUTAWAY LIST</strong></h5>
            <table width="100%" border="1">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Descriptions</th>
                        <th>Group Reff.</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Picking Loc</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($stockTransport->details as $detail)
                    @if($detail->status <> 'canceled')
                    <tr>
                        <td>{{$detail->item->sku}}</td>
                        <td>{{$detail->item->name}}</td>
                        <td>{{$detail->qty}}</td>
                        <td>{{$detail->uom->name}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 space-top">
            <table width="60%" border="1">
                <thead>
                    <tr align="center">
                        <th>Admin</th>
                        <th>Checker</th>
                        <th colspan="3">Handling</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="5"></td>
                        <td rowspan="5"></td>
                        <td colspan="3">Total Labor : 2</td>
                    </tr>
                    <tr>
                        <td colspan="3">Total Pallet : 8</td>
                    </tr>
                    <tr>
                        <td colspan="3">Forklift</td>
                    </tr>
                    <tr>
                        <td align="center">Type</td>
                        <td align="center">Start</td>
                        <td align="center">Finish</td>
                    </tr>
                    <tr>
                        <td align="center">2 Ton</td>
                        <td align="center">12:00</td>
                        <td align="center">16:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>  
@endsection