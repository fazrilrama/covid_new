
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>Delivery Notes</title>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
</head>
<style>
    body, html {
		height: 2000px;
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        position: relative; 
		overflow: hidden;
        /* -webkit-print-color-adjust: exact; */
    }

    p {
        margin-top: 8px;
        margin-bottom: 8px;
    }

    h5 {
        font-weight: normal;
    }

    .wrapper {
        display: grid;
        grid-template-columns: repeat(12, 8.33333%);
        grid-gap: 0px;
        /*padding-left: 1rem;*/
        /*padding-right: 1rem;*/
    }

    .wrapper div {
        /*padding-left: 1rem;*/
        /*padding-right: 1rem;*/
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

    .col-9 {
        grid-column: span 9;
    }

    .col-10 {
        grid-column: span 10;
    }

    .col-11 {
        grid-column: span 11;
    }

    .col-12 {
        grid-column: span 12;
    }

    .top-wrapper {
        margin-top: 50px;
    }

    .text-center {
        text-align: center;
    }

    .text-justify {
        text-align: justify;
    }

    .bolder p {
        font-weight: 900;
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

    .lgtopmargin {
        margin-top: 40px;
    }

    .mdtopmargin {
        margin-top: 20px;
    }

    .smtopmargin {
        margin-top: 10px;
    }

    .xstopmargin {
        margin-top: 5px;
    }

    .smbottommargin {
        margin-bottom: 10px;
    }

    .lgbottommargin {
        margin-bottom: 40px;
    }

    .nopadding {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .mdpadding {
        padding-left: 2rem;
        padding-right: 2rem;
    }

    .smtoppadding {
        padding-top: 10px;
    }

    p, ul li, ol li {
        font-size: 9pt;
    }

    table {
        border-collapse:collapse;
    }

    table th, table td {
        border: 1px solid #000;
        text-align: center;
        font-size: 9pt;
        padding: 5px;

    }

    .sub-eng {
        font-size: 8pt;
        font-style: italic;
        font-weight: normal;
    }

    ul {
        list-style-type: none;
    }

    .with-border {
        border: solid 2px #000000;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
        font-weight: bold;
    }

    .allborder {
        border: solid 1px #000000;
    }

    .bottomborder {
        border-bottom: solid 1px #000000;
    }

    .keterangan:before {
        content : "";
        position: absolute;
        left    : 2rem;
        height  : 1px;
        width   : 15%;  /* or 100px */
        border-top:3px solid black;
    }

    .keterangan p{
        font-size: 7pt;
        margin: 0;
    }

    ol.nomargin {
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }

    .print:last-child {
        page-break-after: auto;
    }

    p.tab>span {
        display: inline-block;
        min-width: 20px;
    }
    p.form>span {
        display: inline-block;
        min-width: 275px;
    }

    p.subform:after{
        content : "";
        position: absolute;
        right    : 5rem;
        height  : 1px;
        width   : 15%;  /* or 100px */
        border-top:1px solid black;
    }

    .box {
        border: solid 1px #000000;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    ul.dash li{
        padding-left: 1em;
        position: relative;
    }

    ul.dash li:before {
        content: '-';
        position: absolute;
        left: 0;
    }
    #delivery-note {
        table-layout: fixed ;
        width: 100% ;
    }
    /* td {
        width: 20% ;
    } */
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg .tg-s268{text-align:left}
    .tg .tg-0lax{text-align:left;vertical-align:top}
</style>
<body onload=" setTimeout( function(){ window.scrollTo(0, 1) }, 0); ">
<div class="print">
    
    <div class="wrapper">
        <div class="col-6">
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Destination</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->destination->name}}</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>ETA</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->eta}}</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Consignee</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->consignee->name}}</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Address</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->consignee_address}}</p>
                </div>
            </div>
		</div>
		<div class="col-6">
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Nama Driver</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->driver_name }}</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>No.Telp Driver</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->driver_phone }}</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>No.Polisi Truk</strong></p>
                </div>
                <div class="col-8">
                    <p>: {{$stockDelivery->police_number }}</p>
                </div>
            </div>
        </div>
    </div>
    <hr style="border-width: 0">
    <table width="100%">
        <thead>
        <tr>
            <th>Item SKU</th>
            <th>Name</th>
            <th>Group Ref</th>
            <th>Qty</th>
            <th>UoM</th>
            <!-- <th>Control Date:</th> -->
        </tr>
        </thead>
        <tbody>
        @foreach($stockDelivery->details as $detail)
        <tr>
            <td>{{$detail->item->sku}}</td>
            <td>{{$detail->item->name}}</td>
            <td>{{$detail->ref_code}}</td>
            <td>{{ number_format($detail->qty, 2, ',', '.') }}</td>
            <td>{{$detail->uom->name}}</td>
            <!-- <td>{{$detail->control_date}}</td> -->
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
	window.scrollTo(0, 1);
    // if( !window.location.hash && window.addEventListener ){
    //     window.addEventListener("load", function() {
    //         setTimeout(function(){
    //             window.scrollTo(0, 1);
    //         }, 0);
    //     });
    //     window.addEventListener( "orientationchange",function() {
    //         setTimeout(function(){
    //             window.scrollTo(0, 1);
    //         }, 0);
    //     });
    //     window.addEventListener( "touchstart",function() {
    //         setTimeout(function(){
    //             window.scrollTo(0, 1);
    //         }, 0);
    //     });
    // }
</script>
</body>
</html>