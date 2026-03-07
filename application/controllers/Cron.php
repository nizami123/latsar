<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function hitung_populasi($bulan,$tahun)
    {
        $this->db->query("CALL sp_hitung_populasi_bulan('$bulan','$tahun')");
    }

}