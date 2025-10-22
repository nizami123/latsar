<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->model('Harga_model');
        $this->load->model('Populasi_model');
        $this->load->model('Pemotongan_model');
        $token = get_cookie('remember_token');
        if(!$this->session->userdata('logged_in') && $token){
            $user = $this->db->get_where('master_user', ['remember_token' => $token])->row();
            if($user){
                $this->session->set_userdata([
                    'id_user'   => $user->id_user,
                    'nama_user' => $user->nama_user,
                    'jabatan'   => $user->jabatan,
                    'logged_in' => TRUE
                ]);
            }
        }

        // Jika tetap tidak login, redirect ke auth
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
    }

    public function index()
    {
        $data['harga'] = $this->Harga_model->getLatestHarga();
        $data['last_date'] = $this->Harga_model->getLastTanggal();
        $data['chartData'] = $this->getDataProduksi();

        $populasi = $this->getTotalPopulasi();
        $data['populasiBulan'] = $populasi['bulan'];
        $data['populasiTahun'] = $populasi['tahun'];
        $data['populasiTotal'] = $populasi['data'];

        $pemotongan = $this->getTotalPemotongan();
        $data['pemotonganBulan'] = $pemotongan['bulan'];
        $data['pemotonganTahun'] = $pemotongan['tahun'];
        $data['pemotonganTotal'] = $pemotongan['data'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('dashboard', $data);
    }

    private function getDataProduksi() {
        $tahun = date('Y');

        $this->db->select('tahun, bulan, jenis, SUM(jumlah) as total');
        $this->db->from('trx_produksi');
        $this->db->where('tahun', $tahun);
        $this->db->group_by(['tahun', 'bulan', 'jenis']);
        $this->db->order_by('bulan', 'ASC');
        $result = $this->db->get()->result();

        $dataJenis = [
            'Daging' => array_fill(1, 12, 0),
            'Telur'  => array_fill(1, 12, 0)
        ];

        foreach ($result as $row) {
            $dataJenis[$row->jenis][(int)$row->bulan] = (int)$row->total;
        }

        return $dataJenis;
    }

    private function getTotalPopulasi(){
        $latest = $this->Populasi_model->get_latest_period();
        if (!$latest) {
            return [];
        }

        $bulan = $latest['bulan'];
        $tahun = $latest['tahun'];

        // ambil total per komoditas
        $data = $this->Populasi_model->get_total_per_komoditas($bulan, $tahun);

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data'  => $data
        ];
    }

    private function getTotalPemotongan(){
        $latest = $this->Pemotongan_model->get_latest_period();
        if (!$latest) {
            return [];
        }

        $bulan = $latest['bulan'];
        $tahun = $latest['tahun'];

        // ambil total per komoditas
        $data = $this->Pemotongan_model->get_total_per_komoditas($bulan, $tahun);

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'data'  => $data
        ];
    }
}
