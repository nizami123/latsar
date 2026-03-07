<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function index()
{
    set_time_limit(0); 
    try {

        $bulan = date('m', strtotime('-1 month'));
        $tahun = date('Y', strtotime('-1 month'));

        $this->db->insert('log_cron',[
            'proses'=>'START CRON INSERT POPULASI',
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'waktu' => date('Y-m-d H:i:s', strtotime('+7 hours'))
        ]);

        $this->db->query("CALL sp_rekap_populasi(?, ?)", [$bulan, $tahun]);

        $error = $this->db->error();
        if ($error['code'] != 0) {
            throw new Exception($error['message']);
        }

        $this->db->insert('log_cron',[
            'proses'=>'END CRON INSERT POPULASI',
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'waktu' => date('Y-m-d H:i:s', strtotime('+7 hours'))
        ]);

        echo "Berhasil insert populasi";

    } catch (Exception $e) {

        echo "Error: " . $e->getMessage();

        $this->db->insert('log_cron',[
            'proses'=>'ERROR CRON INSERT POPULASI: '.$e->getMessage(),
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'waktu' => date('Y-m-d H:i:s', strtotime('+7 hours'))
        ]);
    }
}
}