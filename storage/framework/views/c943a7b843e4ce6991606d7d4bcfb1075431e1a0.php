<!DOCTYPE html>
<html>
<head>
    <h1 style="text-align: center;">Estimasi jadwal pengiriman barang</h1>
</head>
<style type="text/css">
	@media  print {
		thead{
			bgcolor:grey;
		}
		table{
			border:"0.5";
		}

        table, th, td {
           border: 1px solid black;
        }

        table, th, td {
           padding: 5px;
        }

        th {
            background-color: #d0cecf;
        }
  }
</style>
<body>
    <table width="100%">
	    
        <thead>
            <tr>
                <th>No:</th>
                <th>ETD:</th>
                <th>DN#:</th>
                <th>SHIPPER:</th>
                <th>CONSIGNEE:</th>
                <th>ASAL:</th>
                <th>TUJUAN:</th>
                <th>Weight Kg:</th>
                <th>Vol M3:</th>
                <th>Collie Pcs:</th>
            </tr>
        </thead>
        
        <tbody>
        <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($key+1); ?></td>
                <td><?php echo e($item->etd); ?></td>
                <td><?php echo e($item->code); ?></td>
                <td><?php echo e($item->shipper->name); ?></td>
                <td><?php echo e($item->consignee->name); ?></td>
                <td><?php echo e($item->origin->name); ?></td>
                <td><?php echo e($item->destination->name); ?></td>
                <td><?php echo e($item->qty); ?></td>
                <td><?php echo e($item->weight); ?></td>
                <td><?php echo e($item->volume); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>

</html>