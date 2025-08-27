<?php $__env->startSection('content'); ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRINT OUT</title>
</head>

<body>
    <table width="100%">
        <tr>
            <td colspan="6" align="center">
                <img src="logo.png" alt="Logo BGR" width="100" height="20">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <th rowspan="3">Kepada<br><span style="font-weight:bold">Kepala Gudang</span> GBB BGR<br>di
                Tempat</th>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?php echo e($advanceNotice->created_at->format('d F Y')); ?></td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td>: <?php echo e(number_format($advanceNotice->details->sum('qty'))); ?></td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" align="center">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" align="center">&nbsp;</td>
        </tr>
        <tr>
            <?php if($advanceNotice->type == 'inbound'): ?>
            <td colspan="6" align="center">SURAT PERINTAH BARANG MASUK (SPBM)</td>
            <?php else: ?>
            <td colspan="6" align="center">SURAT PERINTAH BARANG KELUAR (SPBK)</td>
            <?php endif; ?>
        </tr>
        <tr>
            <?php if($advanceNotice->type == 'inbound'): ?>
            <td colspan="6" align="center">Nomor S P B M: <?php echo e($advanceNotice->code); ?></td>
            <?php else: ?>
            <td colspan="6" align="center">Nomor S P B K: <?php echo e($advanceNotice->code); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6" align="center">&nbsp;</td>
        </tr>
        <tr>
            <?php if($advanceNotice->type == 'inbound'): ?>
            <td colspan="6">1. Harap diterima dan disimpan dengan baik <?php echo e(strtoupper($advanceNotice->details->first()->item->name)); ?> di Gudang Saudara</td>
            <?php else: ?>
            <td colspan="6">1. Harap dikeluarkan barang dengan baik <?php echo e(strtoupper($advanceNotice->details->first()->item->name)); ?> dari Gudang Saudara</td>
            <?php endif; ?>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jenis</td>
            <td colspan="6">: <?php echo e($advanceNotice->details->first()->item->type->name); ?></td>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No. Ref</td>
            <td colspan="6">: <?php echo e($advanceNotice->ref_code); ?></td>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Koli</td>
            <td colspan="6">: <?php echo e($advanceNotice->details->sum('qty')); ?></td>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jumlah</td>
            <td colspan="6">: <?php echo e(number_format($advanceNotice->details->sum('weight'))); ?> KG</td>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Keterangan</td>
            <td colspan="6">: <?php echo e($advanceNotice->annotation ?? '-'); ?></td>
        </tr>
        <tr>
            <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kontraktor</td>
            <td colspan="6">: <?php echo e($advanceNotice->contractor ?? '-'); ?></td>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6">2. Dengan ketentuan-ketentuan sebagai berikut</td>
        </tr>
        <tr>
            <td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e($advanceNotice->type == 'inbound' ? 'Penerimaan' : 'Pengeluaran'); ?> barang dengan ketentuan berat Natto
                dan Netto</td>
        </tr>
    </table>
    <br><br>
    <table align="right">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="vertical-align: top;">Dikeluarkan di</td>
                <td style="max-width:150px;"><?php echo e($advanceNotice->origin->name); ?></td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="vertical-align: top;">Pada Tanggal</td>
                <td><?php echo e($advanceNotice->created_at->format('d F Y')); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="vertical-align: top;"></td>
                <td style="max-width:150px;"><?php echo e($advanceNotice->shipper->name); ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="center">Kepala</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="center"><?php echo e(strtoupper($advanceNotice->head_ds)); ?></td>
            </tr>
        </table>
</body>

</html>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.print', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>