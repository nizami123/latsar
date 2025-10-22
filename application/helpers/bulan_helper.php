<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('nama_bulan'))
{
    function nama_bulan($angka){
        $bulan = [
            1 => "Januari", 2 => "Februari", 3 => "Maret",
            4 => "April", 5 => "Mei", 6 => "Juni",
            7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember"
        ];
        return isset($bulan[(int)$angka]) ? $bulan[(int)$angka] : "-";
    }
}

if ( ! function_exists('bulan_dropdown'))
{
    function bulan_dropdown($name, $selected = null, $extra = ''){
        $bulan = [
            1 => "Januari", 2 => "Februari", 3 => "Maret",
            4 => "April", 5 => "Mei", 6 => "Juni",
            7 => "Juli", 8 => "Agustus", 9 => "September",
            10 => "Oktober", 11 => "November", 12 => "Desember"
        ];

        $html = "<select name='{$name}' id='{$name}' class='form-control' {$extra}>";
        $html .= "<option value=''>-- Pilih Bulan --</option>";
        foreach($bulan as $key => $val){
            $sel = ($selected == $key) ? "selected" : "";
            $html .= "<option value='{$key}' {$sel}>{$val}</option>";
        }
        $html .= "</select>";
        return $html;
    }
}
