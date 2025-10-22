<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Harga_model');
        $this->load->model('Populasi_model');
        $this->load->model('Pemotongan_model');
        $this->load->model('Komoditas_model');
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
    }

    public function index()
    {
        $populasi = $this->getDataPopulasi();
        $pemotongan = $this->getDataPemotongan();
        $data['kecamatan'] = $this->db->get_where('master_wilayah', ['kode' => $this->session->userdata('kode')])->row_array();

        $data['populasiPivot'] = $populasi['pivot'];
        $data['populasiKomoditas'] = $populasi['komoditas'];
        $data['populasiBulan'] = $populasi['bulan'];
        $data['populasiTahun'] = $populasi['tahun'];

        $data['pemotonganPivot'] = $pemotongan['pivot'];
        $data['pemotonganKomoditas'] = $pemotongan['komoditas'];
        $data['pemotonganBulan'] = $pemotongan['bulan'];
        $data['pemotonganTahun'] = $pemotongan['tahun'];
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('kecamatan', $data);
    }

    private function getDataPopulasi() {
        // Ambil bulan dan tahun terbaru dari tabel trx_populasi
        $this->db->select('tahun, bulan');
        $this->db->from('trx_populasi');
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('bulan', 'DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();

        if (!$latest) {
            // Kalau data kosong, kembalikan array kosong
            return [
                'pivot' => [],
                'komoditas' => [],
                'bulan' => null,
                'tahun' => null
            ];
        }

        $bulan = $latest['bulan'];
        $tahun = $latest['tahun'];
        // Ambil data populasi untuk bulan dan tahun tersebut
        $raw_data = $this->Populasi_model->get_pivot_data_kecamatan($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];

        foreach ($raw_data as $row) {
            $kecamatan = $row['kecamatan'];
            $komoditas = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            $pivot[$kecamatan][$komoditas] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    public function get_data_populasi(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Ambil data pivot dari fungsi private
        $data = $this->getDataPopulasiByMonthYear($bulan, $tahun);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'bulan'      => $data['bulan'],
                'tahun'      => $data['tahun'],
                'nama_bulan' => nama_bulan($data['bulan']),
                'komoditas'  => $data['komoditas'],  // array nama komoditas
                'populasi'   => $data['pivot']       // array pivot [kecamatan][komoditas] => jumlah
        ]));
    }

    private function getDataPopulasiByMonthYear($bulan, $tahun)
    {
        $raw_data = $this->Populasi_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $kecamatanList = [];

        // Pertama: kumpulkan semua komoditas & kecamatan
        foreach ($raw_data as $row) {
            $kecamatan = $row['kecamatan'];
            $komoditas = $row['nama_komoditas'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            if (!in_array($kecamatan, $kecamatanList)) {
                $kecamatanList[] = $kecamatan;
            }
        }

        // Inisialisasi semua pivot dengan 0
        foreach ($kecamatanList as $kec) {
            foreach ($komoditasList as $kom) {
                $pivot[$kec][$kom] = 0;
            }
        }

        // Masukkan data yang ada
        foreach ($raw_data as $row) {
            $kec = $row['kecamatan'];
            $kom = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];
            $pivot[$kec][$kom] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }


     private function getDataPemotongan() {
        // Ambil bulan dan tahun terbaru dari tabel trx_populasi
        $this->db->select('tahun, bulan');
        $this->db->from('trx_populasi');
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('bulan', 'DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();

        if (!$latest) {
            // Kalau data kosong, kembalikan array kosong
            return [
                'pivot' => [],
                'komoditas' => [],
                'bulan' => null,
                'tahun' => null
            ];
        }

        $bulan = $latest['bulan'];
        $tahun = $latest['tahun'];
        // Ambil data populasi untuk bulan dan tahun tersebut
        $raw_data = $this->Pemotongan_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];

        foreach ($raw_data as $row) {
            $wilayah = $row['wilayah'];
            $komoditas = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            $pivot[$wilayah][$komoditas] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    public function get_data_pemotongan(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Ambil data pivot dari fungsi private
        $data = $this->getDataPemotonganByMonthYear($bulan, $tahun);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'bulan'      => $data['bulan'],
                'tahun'      => $data['tahun'],
                'nama_bulan' => nama_bulan($data['bulan']),
                'komoditas'  => $data['komoditas'],  // array nama komoditas
                'pemotongan'   => $data['pivot']       // array pivot [wilayah][komoditas] => jumlah
        ]));
    }

    private function getDataPemotonganByMonthYear($bulan, $tahun)
    {
        $raw_data = $this->Pemotongan_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $wilayahList = [];

        // Pertama: kumpulkan semua komoditas & wilayah
        foreach ($raw_data as $row) {
            $wilayah = $row['wilayah'];
            $komoditas = $row['nama_komoditas'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            if (!in_array($wilayah, $wilayahList)) {
                $wilayahList[] = $wilayah;
            }
        }

        // Inisialisasi semua pivot dengan 0
        foreach ($wilayahList as $wil) {
            foreach ($komoditasList as $kom) {
                $pivot[$wil][$kom] = 0;
            }
        }

        // Masukkan data yang ada
        foreach ($raw_data as $row) {
            $wil = $row['wilayah'];
            $kom = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];
            $pivot[$wil][$kom] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

}
