<!DOCTYPE html>
<html>
<head>
    <h1 style="text-align: center;">Estimasi jadwal pengiriman barang</h1>
</head>
<style type="text/css">
	@media print {
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
        @foreach($collections as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->etd}}</td>
                <td>{{$item->code}}</td>
                <td>{{$item->shipper->name}}</td>
                <td>{{$item->consignee->name}}</td>
                <td>{{$item->origin->name}}</td>
                <td>{{$item->destination->name}}</td>
                <td>{{$item->qty}}</td>
                <td>{{$item->weight}}</td>
                <td>{{$item->volume}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>

</html>