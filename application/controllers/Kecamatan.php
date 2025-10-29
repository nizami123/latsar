<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Harga_model');
        $this->load->model('Populasi_model');
        $this->load->model('Komoditas_model');
        $this->load->model('Masuk_model');
        $this->load->model('Keluar_model');
        $this->load->model('Kelahiran_model');
        $this->load->model('Kematian_model');
        $this->load->model('Pemotongan_model'); // ✅ Tambah model Pemotongan

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
    }

    public function index()
    {
        $populasi  = $this->getDataPopulasi();
        $masuk     = $this->getDataMasuk();
        $keluar    = $this->getDataKeluar();
        $kelahiran = $this->getDataKelahiran();
        $kematian  = $this->getDataKematian();
        $pemotongan = $this->getDataPemotongan(); // ✅ Tambah pemotongan

        $data['kecamatan'] = $this->db
            ->get_where('master_wilayah', ['kode' => $this->session->userdata('kode')])
            ->row_array();

        // POPULASI
        $data['populasiPivot']     = $populasi['pivot'];
        $data['populasiKomoditas'] = $populasi['komoditas'];
        $data['populasiBulan']     = $populasi['bulan'];
        $data['populasiTahun']     = $populasi['tahun'];

        // MASUK
        $data['masukPivot']     = $masuk['pivot'];
        $data['masukKomoditas'] = $masuk['komoditas'];
        $data['masukBulan']     = $masuk['bulan'];
        $data['masukTahun']     = $masuk['tahun'];

        // KELUAR
        $data['keluarPivot']     = $keluar['pivot'];
        $data['keluarKomoditas'] = $keluar['komoditas'];
        $data['keluarBulan']     = $keluar['bulan'];
        $data['keluarTahun']     = $keluar['tahun'];

        // KELAHIRAN
        $data['kelahiranPivot']     = $kelahiran['pivot'];
        $data['kelahiranKomoditas'] = $kelahiran['komoditas'];
        $data['kelahiranBulan']     = $kelahiran['bulan'];
        $data['kelahiranTahun']     = $kelahiran['tahun'];

        // KEMATIAN
        $data['kematianPivot']     = $kematian['pivot'];
        $data['kematianKomoditas'] = $kematian['komoditas'];
        $data['kematianBulan']     = $kematian['bulan'];
        $data['kematianTahun']     = $kematian['tahun'];

        // ✅ PEMOTONGAN
        $data['pemotonganPivot']     = $pemotongan['pivot'];
        $data['pemotonganKomoditas'] = $pemotongan['komoditas'];
        $data['pemotonganBulan']     = $pemotongan['bulan'];
        $data['pemotonganTahun']     = $pemotongan['tahun'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('kecamatan', $data);
    }

    /* ===================== POPULASI ===================== */
    private function getDataPopulasi() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_populasi');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataPopulasiByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataPopulasiByMonthYear($bulan,$tahun) {
        $raw_data = $this->Populasi_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_populasi(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataPopulasiByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== MASUK ===================== */
    private function getDataMasuk() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_masuk');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataMasukByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataMasukByMonthYear($bulan,$tahun) {
        $raw_data = $this->Masuk_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_masuk(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataMasukByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== KELUAR ===================== */
    private function getDataKeluar() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_keluar');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataKeluarByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataKeluarByMonthYear($bulan,$tahun) {
        $raw_data = $this->Keluar_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_keluar(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataKeluarByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== KELAHIRAN ===================== */
    private function getDataKelahiran() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_kelahiran');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataKelahiranByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataKelahiranByMonthYear($bulan,$tahun) {
        $raw_data = $this->Kelahiran_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_kelahiran(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataKelahiranByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== KEMATIAN ===================== */
    private function getDataKematian() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_kematian');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataKematianByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataKematianByMonthYear($bulan,$tahun) {
        $raw_data = $this->Kematian_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_kematian(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataKematianByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== ✅ PEMOTONGAN ===================== */
    private function getDataPemotongan() {
        $this->db->select('tahun, bulan');
        $this->db->from('trx_pemotongan');
        $this->db->order_by('tahun','DESC')->order_by('bulan','DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();
        if(!$latest) return ['pivot'=>[],'komoditas'=>[],'bulan'=>null,'tahun'=>null];
        return $this->getDataPemotonganByMonthYear($latest['bulan'], $latest['tahun']);
    }

    private function getDataPemotonganByMonthYear($bulan,$tahun) {
        $raw_data = $this->Pemotongan_model->get_pivot_data_kecamatan($bulan,$tahun);
        return $this->preparePivot($raw_data, $bulan, $tahun);
    }

    public function get_data_pemotongan(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $data = $this->getDataPemotonganByMonthYear($bulan,$tahun);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'bulan'=>$data['bulan'],
            'tahun'=>$data['tahun'],
            'nama_bulan'=>nama_bulan($data['bulan']),
            'komoditas'=>$data['komoditas'],
            'data'=>$data['pivot']
        ]));
    }

    /* ===================== UTILITY ===================== */
    private function preparePivot($raw_data, $bulan, $tahun) {
        $pivot = [];
        $komoditasList = [];
        $kecamatanList = [];

        foreach($raw_data as $row){
            $kec = $row['kecamatan'];
            $kom = $row['nama_komoditas'];
            if(!in_array($kom,$komoditasList)) $komoditasList[] = $kom;
            if(!in_array($kec,$kecamatanList)) $kecamatanList[] = $kec;
        }

        // inisialisasi 0
        foreach($kecamatanList as $kec){
            foreach($komoditasList as $kom){
                $pivot[$kec][$kom] = 0;
            }
        }

        foreach($raw_data as $row){
            $pivot[$row['kecamatan']][$row['nama_komoditas']] = $row['jumlah'];
        }

        return [
            'pivot'=>$pivot,
            'komoditas'=>$komoditasList,
            'bulan'=>isset($bulan)?$bulan:null,
            'tahun'=>isset($tahun)?$tahun:null
        ];
    }
}
