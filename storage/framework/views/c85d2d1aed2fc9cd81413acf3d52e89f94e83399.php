<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Notes</title>
</head>
<style>
    body, html {
        height: 100%;
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        /* -webkit-print-color-adjust: exact; */
    }

    @page  {
        size: 210mm 297mm;
        /*size: 8.5in 13in;*/
        margin: 0;
    }

    p {
        margin-top: 8px;
        margin-bottom: 8px;
    }

    h5 {
        font-weight: normal;
    }

    @media  print {
        @page  {
            size: portrait;
            margin: 1cm;
            size: 210mm 297mm;
        }

        html {
            height: auto;
        }

        .header {
            page-break-before: always;
        }

        .footer {
            page-break-after: always;
        }
    }

    body {
        margin: 0;
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
    .amazonLink {
        position: relative;
    }

    .amazonLink__text {
        display: inline-block;
        line-height: 40px;
        float: left;
        position: relative;
        left: 40%;
    }

    .amazonLink__image {
        display: inline-block;
        float: left;
        position: relative;
        left: 50%;  
    }
</style>
<body>
<div class="print">
    <div class="wrapper">
        <div class="col-12">
            <div class="amazonLink">
                <div style="text-align:center;">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo BGR" width="100" height="20">
                    
                </div>
           
                <h3 class="amazonLink__text">Delivery Notes
                </h3>
                <div class="amazonLink__image" >
                    <img src="<?php echo e(\Storage::disk('public')->url($stockDelivery->qr_code)); ?>" alt="" srcset="">
                </div> 
            </div>
           
          
        </div>
    </div>
    <div class="wrapper">
        <div class="col-6">
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>DN #</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->code); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Company</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->project->company->name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Customer Reff.</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->ref_code); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Origin</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->origin->name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>ETD</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->etd); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Shipper</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->shipper->name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Address</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->shipper_address); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Post Code</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->shipper->postal_code); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Total Collie</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->total_collie); ?> Box</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Total Weight</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->total_weight); ?> Kg</p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Total Volume</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->total_volume); ?> m<sup>3</sup></p>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Date</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->created_at); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Destination</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->destination->name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>ETA</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->eta); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Consignee</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->consignee->name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Nomor Hp</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->consignee->phone_number); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Address</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->consignee_address); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Post Code</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->consignee->postal_code); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Moda Transport</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->transport_type->name); ?></p>
                </div>
            </div>
            <?php if($stockDelivery->project_id != 333): ?>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Pengangkut</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->shipper->name); ?></p>
                </div>
            </div>
            <?php endif; ?>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Tanggal Berangkat</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->etd); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>Nama Driver</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->driver_name); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>No.Telp Driver</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->driver_phone); ?></p>
                </div>
            </div>
            <div class="wrapper">
                <div class="col-4">
                    <p><strong>No.Polisi Truk</strong></p>
                </div>
                <div class="col-8">
                    <p>: <?php echo e($stockDelivery->police_number); ?></p>
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
        <?php $__currentLoopData = $stockDelivery->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($detail->item->sku); ?></td>
            <td><?php echo e($detail->item->name); ?></td>
            <td><?php echo e($detail->ref_code); ?></td>
            <td><?php echo e(number_format($detail->qty, 2, ',', '.')); ?></td>
            <td><?php echo e($detail->uom->name); ?></td>
            <!-- <td><?php echo e($detail->control_date); ?></td> -->
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <br>
    <?php if($stockDelivery->type_payment != null): ?>
        <table>
            <tr>
                <td colspan="4"><b><?php echo e(strtoupper($stockDelivery->type_payment)); ?></b></td>
                <?php if($stockDelivery->type_payment == 'cod'): ?>
                <td >Rp. <?php echo e(number_format($stockDelivery->remaining_total,2,',','.')); ?></td>
                <?php endif; ?>
            </tr>
        </table>
        <?php endif; ?>
    <hr style="border-width: 0">
</div>
<table class="tg" id="delivery-note">
    <tr>
        <th class="tg-s268"><center><strong>Kepala Gudang</strong></center></th>
        <th class="tg-s268"><center><strong>Checker</strong></center></th>
        <th class="tg-s268"><center><strong>Driver</strong></center></th>
        <th class="tg-0lax"><center><strong>Received By</strong></center></th>
        <th class="tg-0lax"><center><strong>Date &amp; Time</strong></center></th>
    </tr>
    <tr>
        <td class="tg-s268" style="padding:3%;">&nbsp;</td>
        <td class="tg-s268" style="padding:3%;">&nbsp;</td>
        <td class="tg-s268" style="padding:3%;">&nbsp;</td>
        <td class="tg-s268" style="padding:3%;">&nbsp;</td>
        <td class="tg-s268" style="padding:3%;">&nbsp;</td>
    </tr>
    <tr>
        <td class="tg-s268" style="border-top-width:0px;text-align: center">
            <?php if(auth()->user()->full_name): ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php else: ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endif; ?>
        </td>
        <td class="tg-s268" style="border-top-width:0px;text-align: center">      
            <?php if($stockDelivery->employee_name): ?>
                (<?php echo e($stockDelivery->employee_name); ?>)
            <?php else: ?>
                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
            <?php endif; ?>
        </td>
        <td class="tg-s268" style="border-top-width:0px;text-align: center">
            <?php if($stockDelivery->driver_name): ?>
                (<?php echo e($stockDelivery->driver_name); ?>)
            <?php else: ?>
                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
            <?php endif; ?>
        </td>
        <td class="tg-0lax" style="border-top-width:0px;">
        </td>
        <td class="tg-0lax" style="border-top-width:0px;text-align: center">
            
        </td>
    </tr>
    </table>
</body>
<script type="text/javascript">
    window.print();
</script>
</html>