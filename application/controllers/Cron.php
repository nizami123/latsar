<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function insert_populasi()
{
    set_time_limit(0); 
    try {

        $bulan = date('m', strtotime('-1 month'));
        $tahun = date('Y', strtotime('-1 month'));

        $this->db->query("CALL sp_rekap_populasi(?, ?)", [$bulan, $tahun]);

        $error = $this->db->error();
        if ($error['code'] != 0) {
            throw new Exception($error['message']);
        }

        echo "Berhasil insert populasi";

    } catch (Exception $e) {

        echo "Error: " . $e->getMessage();
    }
}
}