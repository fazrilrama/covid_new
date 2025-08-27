<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Delivery Note Print</title>
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
    
    @page  {
        size: 21cm 29.7cm;
        margin: 0;
    }
    
    @media  print {
        @page  {
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
            <h1>DELIVERY NOTICE (SURAT JALAN)</h1>
        </div>
    </div>
    <div class="wrapper">
        <div class="col-2">
            <p>DN #</p>
            <p>DN Date</p>
            <p>Customer Reff</p>
            <p>Origin</p>
            <p>ETD</p>
            <p>Destination</p>
            <p>ETA</p>
            <p>Total Collie</p>
            <p>Total Weight</p>
            <p>Total Volume</p>
        </div>

        <div class="col-3">
            <p><?php echo e($collections->code); ?></p>
            <p><?php echo e($collections->created_at); ?></p>
            <p><?php echo e($collections->ref_code); ?></p>
            <p><?php echo e($collections->origin->name); ?></p>
            <p><?php echo e($collections->etd); ?></p>
            <p><?php echo e($collections->destination->name); ?></p>
            <p><?php echo e($collections->eta); ?></p>
            <p><?php echo e($collections->total_collie); ?></p>
            <p><?php echo e($collections->total_weight); ?></p>
            <p><?php echo e($collections->total_volume); ?></p>
        </div>

        <div class="col-2">
            <p>Shipper</p>
            <p>Adress</p>
            <p>Post Code</p>
            <p>Consignee</p>
            <p>Address</p>
            <p>Post Code</p>
            <p>Moda Transport</p>
            <p>Shipper</p>
        </div>

        <div class="col-3">
            <p><?php echo e($collections->shipper->name); ?></p>
            <p><?php echo e($collections->shipper_address); ?></p>
            <p><?php echo e($collections->shipper_postal_code); ?></p>
            <p><?php echo e($collections->consignee->name); ?></p>
            <p><?php echo e($collections->shipper_address); ?></p>
            <p><?php echo e($collections->shipper_postal_code); ?></p>
            <p><?php echo e($collections->transport_type->name); ?></p>
            <p><?php echo e($collections->vehicle_code_num); ?></p>

        </div>
    </div>

    <div class="wrapper">
        <div class="col-10" style="padding: 0;">
            <table width="100%">
                <thead>
                    <tr>
                        <th>SKU CODE</th>
                        <th>DESCRIPTIONS</th>
                        <th>GROUP CODE</th>
                        <th>Qty</th>
                        <th>UoM</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $collections->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value->item->sku); ?>.</td>
                        <td><?php echo e($value->item->description); ?></td>
                        <td><?php echo e($value->commodity->code); ?></td>
                        <td><?php echo e($value->commodity->qty); ?></td>
                        <td><?php echo e($value->uom->name); ?></td>
                        <td><?php echo e($value->ref_code); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="wrapper">
        <div class="col-2">
            <p>Admin</p>
        </div>
        <div class="col-2">
            <p>Checker</p>
        </div>
        <div class="col-2">
            <p>Driver</p>
        </div>
        <div class="col-2">
            <p>Received By</p>
        </div>
        <div class="col-2">
            <p>Date & Time</p>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>