<div class="content-loader">
<table>
    <thead>
    <tr>
        <th><b>Report Mutasi Stock per Bulan</b></th>
    </tr>
        <tr>
        <th><b>PT. BHANDA GHARA REKSA (Persero)</b></th>
        </tr>
        <tr>
        <th><b>{{ $first_date }} s/d {{ $end_date }}</b></th>
        </tr>

    </thead>
</table>
<table>
    <thead>
        <tr>
            <th>SKU</th>
            <th>PRODUCT</th>
            <th>Komoditi</th>
            <th>UoM</th>
            <th>STOK AWAL</th>
            <th>MASUK</th>
            @for($i=1; $i<=$data['date_difference']; $i++)
            <th>{{$i}}</th>
            @endfor
            <th>Keluar By Monitoring</th>
            <th>Asumsi Barang Keluar</th>
            <th>Stock Akhir</th>
            <th>Rata Rata Barang Keluar</th>
            <th>Persediaan Rata Rata</th>
            <th>TOR Parsial</th>
            <th>Keterangan</th>
            <th>
            Persediaan Barang yang Harus di Penuhi
            </th>
            <th>Safety Stock</th>
            <th>Rekomendasi</th>
            <th>Nilai Barang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['report'] as $report)
        <tr>
            <td>{{ $report['sku'] }}</td>
            <td>{{ $report['sku_name'] }}</td>
            <td>{{ $report['commodity_name'] }}</td>
            <td>{{ $report['uom_name'] }}</td>
            <td>{{ $report['begining'] }}</td>
            <td>{{ $report['after_begining_in'] }}</td>
            @if( session()->get('current_project')->id == 337)
            @for($i=1; $i<=$data['date_difference']; $i++)
                <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? $data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i] : 0 }}</td>
            @endfor
            @else
            @for($i=1; $i<=$data['date_difference']; $i++)

                <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']][$i]) ? $data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']][$i] : 0 }}</td>
            @endfor
            @endif
            @if( session()->get('current_project')->id == 337)
            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]): 0 }}</td>
            @else
            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) : 0 }}</td>
            @endif
            @php
            if( session()->get('current_project')->id == 337){
                $keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) : 0 ;

                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3) : 0;

            }else {
                $keluar =isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) : 0;
                $rata_rata_barang_keluar = isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3) : 0;
            }
            $persediaan_rata_rata = ($report['begining'] + $report['stock']) / 2 ;

            if($persediaan_rata_rata > 0) {
                $tor = round(($keluar/$persediaan_rata_rata) / $data['pembagi'] * 100, 2);
            } elseif($persediaan_rata_rata == 0){
                $tor = '-';
            }
            else {
                $tor = 0;
            }   

            $asumsi = 10 * $rata_rata_barang_keluar;
            $safety_stock = ($report['begining'] + $report['after_begining_in'] )* ($report['percentage_buffer'] / 100);
            $persediaan_yg_harus_dipenuhi = $asumsi - $report['stock'] + $safety_stock;
            
            @endphp
            <td>{{ $asumsi  }}</td>
            <td>{{ $report['stock'] }}</td>
            @if( session()->get('current_project')->id == 337)
            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) / $data['pembagi'],3) : 0 }}</td>
            @else
            <td>{{ isset($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']."_".$report['damage']]) ? round(array_sum($data['outs'][$report['item_id']."_".$report['warehouse_id']."_".$report['project_id']]) / $data['pembagi'], 3) : 0 }}</td>
            @endif
            <td>{{ ($report['begining'] + $report['stock']) / 2 }}</td>
            
            <td>
            {{$tor}}
            </td>
            <td>
            @if($tor == '-')
                {{$tor}}
            @else
                @if($tor > 3) 
                {{  'FAST' }}
                @elseif($tor >= 1 && $tor <= 3)
                {{  'SLOW' }}
                @else 
                {{ 'NON' }}
                @endif
            @endif
            </td>
            <td>
                {{ $persediaan_yg_harus_dipenuhi }}
            </td>
            <td>
                {{ $safety_stock }}
            </td>
            <td>
                @if($report['stock'] <= $safety_stock)
                    {{ 'Beli' }}
                @else
                    {{ 'Tidak Perlu Beli' }}
                @endif
            </td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>