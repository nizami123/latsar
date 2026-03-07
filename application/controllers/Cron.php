<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function hitung_populasi($bulan,$tahun)
{
    $this->db->insert('log_cron',[
        'proses'=>'START hitung populasi',
        'bulan'=>$bulan,
        'tahun'=>$tahun,
        'waktu'=>date('Y-m-d H:i:s')
    ]);

    try{

        $this->db->query("CALL sp_hitung_populasi_bulan('$bulan','$tahun')");

        $this->db->insert('log_cron',[
            'proses'=>'END hitung populasi',
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'waktu'=>date('Y-m-d H:i:s')
        ]);

    }catch(Exception $e){

        $this->db->insert('log_cron',[
            'proses'=>'ERROR '.$e->getMessage(),
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'waktu'=>date('Y-m-d H:i:s')
        ]);

    }
}

}