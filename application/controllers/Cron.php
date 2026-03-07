<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function insert_populasi()
    {
        $bulan = date('m', strtotime('-1 month'));
        $tahun = date('Y', strtotime('-1 month'));
        $this->db->query("CALL sp_rekap_populasi('".$bulan."', '".$tahun."')"); 
    }
}