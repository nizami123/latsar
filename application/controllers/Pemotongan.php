<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemotongan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pemotongan_model','Komoditas_model']);
    }

    public function index() {
        $data['pemotongan'] = $this->Pemotongan_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getWilayah();
        $data['komoditas'] = $this->Komoditas_model->getKomoditasPemotongan();
        $data['title'] = "Data Pemotongan";
        $data['filename'] = "data_pemotongan";
        $data['js'] = 'pemotongan.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('pemotongan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save() {
        $post = $this->input->post();
        $data = [
            'bulan' => $post['bulan'],
            'tahun' => $post['tahun'],
            'id_wilayah' => $post['id_wilayah'],
            'id_komoditas' => $post['id_komoditas'],
            'jumlah' => $post['jumlah'],
            'id_user' => $this->session->userdata('id_user')
        ];
        $this->Pemotongan_model->insert($data);
        redirect('pemotongan');
    }

    public function update() {
        $post = $this->input->post();
        $data = [
            'bulan' => $post['bulan'],
            'tahun' => $post['tahun'],
            'id_wilayah' => $post['id_wilayah'],
            'id_komoditas' => $post['id_komoditas'],
            'jumlah' => $post['jumlah'],
            'id_user' => $this->session->userdata('id_user')
        ];
        $this->Pemotongan_model->update($post['id_pemotongan'], $data);
        redirect('pemotongan');
    }

    public function delete($id) {
        $this->Pemotongan_model->delete($id);
        redirect('pemotongan');
    }

    public function download_template() {
        $wilayah   = $this->Komoditas_model->getWilayah();
        $komoditas = $this->Komoditas_model->getKomoditasPemotongan();

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();

        // header
        $sheet->setCellValue('A1', 'Wilayah');
        $col = 'B';
        foreach ($komoditas as $k) {
            $sheet->setCellValue($col.'1', $k->nama_komoditas);
            $col++;
        }

        // isi kecamatan
        $row = 2;
        foreach ($wilayah as $w) {
            $sheet->setCellValue('A'.$row, $w->nama_wilayah);
            $row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="template_pemotongan.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function upload_excel() {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $file = $_FILES['file_excel']['tmp_name'];

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $komoditas = $this->Komoditas_model->getKomoditasIndexed(); // nama => id
        $wilayah   = $this->Komoditas_model->getWilayahIndexed();   // nama => id

        $headers = $sheet[1];
        unset($sheet[1]);

       foreach ($sheet as $row) {
            $nama_wilayah = $row['A'];
            if (empty($nama_wilayah)) continue;

            $id_wilayah = $wilayah[$nama_wilayah] ?? null;
            if (!$id_wilayah) continue;

            foreach ($headers as $col => $komod) {
                if ($col == 'A') continue;

                $id_komoditas = $komoditas[$komod] ?? null;
                if (!$id_komoditas) continue;

                // jika kosong isi 0
                $jumlah = isset($row[$col]) && trim($row[$col]) !== '' 
                    ? (int) preg_replace('/[^\d]/', '', $row[$col]) 
                    : 0;

                $this->Pemotongan_model->insert([
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
                    'id_komoditas' => $id_komoditas,
                    'jumlah'       => $jumlah,
                    'id_wilayah'   => $id_wilayah,
                    'id_user'      => $this->session->userdata('id_user'),
                ]);
            }
        }
        redirect('pemotongan');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_pemotongan');
        if($ids) {
            $this->db->where_in('id_pemotongan', $ids);
            $this->db->delete('trx_pemotongan');
        }
        redirect('pemotongan');
    }
}
