<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Loading Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
</head>
<style>
    body,
    html {
        height: 100%;
        font-family: Arial, Helvetica, sans-serif;
        /* -webkit-print-color-adjust: exact; */
    }
    
    @page {
        size: 21cm 29.7cm;
        margin: 0;
    }
    
    @media print {
        @page {
            size: landscape
        }
    }
    
    .wrapper {
        display: grid;
        grid-template-columns: repeat(10, 10%);
        grid-gap: 0px;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .wrapper div {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .col-2 {
        grid-column: span 2;
    }
    
    .col-3 {
        grid-column: span 3;
    }
    
    .col-4 {
        grid-column: span 4;
    }
    
    .col-5 {
        grid-column: span 5;
    }
    
    .col-6 {
        grid-column: span 6;
    }
    
    .col-7 {
        grid-column: span 7;
    }
    
    .col-8 {
        grid-column: span 8;
    }
    
    .col-10 {
        grid-column: span 10;
    }
    
    .col-10 ul li:before {
        content: "\2A";
    }
    
    .col-list {
        grid-column: span 7;
        display: grid;
        grid-template-columns: repeat(10, 10%);
        grid-gap: 0px;
        padding: 20px;
    }
    
    .col-list p {
        margin: 5px;
    }
    
    .col-type {
        grid-column: span 3;
    }
    
    .col-type div {
        background-color: #e84c3d;
        padding: 50px;
    }
    
    .col-type p {
        color: #fff;
    }
    
    body {
        margin: 0.5cm;
    }
    
    p,
    th,
    td {
        font-size: 10pt;
    }
    
    ul {
        list-style: none;
        margin-left: 0;
        padding-left: 1em;
        text-indent: -1em;
    }
    
    .attention p,
    .attention ol li,
    .attention ul li {
        font-size: 8pt;
    }
    
    table {
        border-collapse: collapse;
    }
    
    table th,
    table td {
        border: 1px solid #000;
        text-align: center;
        padding: 10px;
    }
    
    .text-center {
        text-align: center;
    }
    
    .nomargin {
        margin: 0;
    }
    
    .notopmargin {
        margin-top: 0;
    }
    
    .nobottommargin {
        margin-bottom: 0;
    }
    
    .automargin {
        margin: auto;
    }
    
    .smmargin {
        margin: 5px;
    }
    
    .mdmargin {
        margin: 10px;
    }
</style>

<body>

    <div class="wrapper">
        <div class="col-6">
            <h1>LOADING ORDER</h1>
        </div>
    </div>
    <div class="wrapper">
        <div class="col-2">
            <p>Loading Order #</p>
            <p>Delivered From</p>
            <p>Customer Reff</p>
            <p>Warehouse Officer</p>
        </div>

        <div class="col-3">
            <p>{{$collections->code}}</p>
            <p>{{$collections->stock_transport->origin->name}}</p>
            <p>{{$collections->ref_code}}</p>
            <p>{{$collections->employee_name}}</p>
        </div>

        <div class="col-2">
            <p>Date & Time</p>
            <p>Delivery To</p>
            <p>Start</p>
            <p>Finish</p>
        </div>

        <div class="col-3">
            <p>{{$collections->created_at}}</p>
            <p>{{$collections->stock_transport->destination->name}}</p>
            <p>{{$collections->forklift_start_time}}</p>
            <p>{{$collections->forklift_end_time}}</p> 
        </div>
    </div>

    <div class="wrapper">
          <div class="col-10" style="padding: 0;">
            <table width="100%">
                <thead>
                    <tr>
                        <th>SKU CODE</th>
                        <th style="width: 35%;">DESCRIPTIONS</th>
                        <th>GROUP REFF</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 10%;">UoM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections->details as $key => $value)
                    <tr>
                        <td>{{$value->item->sku}}</td>
                        <td>{{$value->item->description}}</td>
                        <td>{{$value->ref_code}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{$value->uom->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

     <div class="wrapper" style="margin-top: 25px;">
        <div class="col-10" style="padding: 0;">
            <p>PUTWAY LIST</p>
        </div>
    </div>

    <div class="wrapper">
         <div class="col-10" style="padding: 0;">
            <table width="100%">
                <thead>
                    <tr>
                        <th>SKU CODE</th>
                        <th style="width: 35%;">DESCRIPTIONS</th>
                        <th>GROUP REFF</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 10%;">UoM</th>
                        <th>Picking Locations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections->details as $key => $value)
                    <tr>
                        <td>{{$value->item->sku}}</td>
                        <td>{{$value->item->description}}</td>
                        <td>{{$value->ref_code}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{$value->uom->name}}</td>
                        <td>{{$value->storage->code}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="wrapper" style="margin-top: 50px;">
        <div class="col-2">
            <p>Admin</p>
        </div>
        <div class="col-2">
            <p>Checker</p>
        </div>
        <div class="col-2">
            <p>Handling</p>
            <p>Total Labor: {{$collections->total_labor}}</p>
            <p>Total Pallet: {{$collections->total_pallet}}</p>
            <p>Forklift</p>
        </div>
        <div class="col-1">
            <p>Type</p>
            <p>2 Ton</p>
        </div>
          <div class="col-1">
            <p>Start</p>
            <p>{{$collections->forklift_start_time}}</p>
        </div>
        <div class="col-1">
            <p>Finish</p>
            <p>{{$collections->forklift_end_time}}</p>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>