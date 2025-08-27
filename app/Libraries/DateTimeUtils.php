<?php 
namespace App\Libraries;

/**
 * Created by Irsal Firdaus
 */
class DateTimeUtils
{

    public static function parseDate($time) {
        $date = date_create($time);
        $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
        $bulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        return $hari[date_format($date, 'w')].", ".date_format($date, 'j')." ".$bulan[date_format($date, 'n')]." ".date_format($date, 'Y');
    }

    public static function parseDateTime($time) {
        $date = date_create($time);
        $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
        $bulan = array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        return $hari[date_format($date, 'w')].", ".date_format($date, 'j')." ".$bulan[date_format($date, 'n')]." ".date_format($date, 'Y'). " ".date_format($date, 'H:i:s');
    }
}