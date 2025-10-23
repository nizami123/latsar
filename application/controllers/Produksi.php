<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Produksi_model');
    }

    public function index(){
        $data['produksi'] = $this->Produksi_model->getAll();
        $data['komoditas'] = $this->db->get('master_komoditas')->result();
        $data['users'] = $this->db->get('master_user')->result();
        $data['title'] = "Data Produksi";
        $data['filename'] = "data_produksi";
        $data['js'] = 'produksi.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('produksi', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save(){
        $id = $this->input->post('id_produksi');
        $data = [
            'bulan' => $this->input->post('bulan'),
            'tahun' => $this->input->post('tahun'),
            'id_komoditas' => $this->input->post('id_komoditas'),
            'jumlah' => $this->input->post('jumlah'),
            'jenis' => $this->input->post('jenis'),
            'id_user' => $this->session->userdata('id_user')
        ];

        if($id){
            $this->Produksi_model->update($id, $data);
        } else {
            $this->Produksi_model->insert($data);
        }

        redirect('produksi');
    }

    public function delete($id){
        $this->Produksi_model->delete($id);
        redirect('produksi');
    }

    public function download_template(){
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nama Komoditas')
            ->setCellValue('B1', 'Jumlah')
            ->setCellValue('C1', 'Jenis (Susu/Telur/Daging)');

        $filename = "template_produksi.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function upload_excel(){
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $id_user = $this->session->userdata('id_user');

        $file = $_FILES['file_excel']['tmp_name'];
        if($file){
            require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

            $objPHPExcel = new PHPExcel();
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $sheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            foreach($sheet as $i => $row){
                if($i == 1) continue; // skip header
                $nama_komoditas = trim($row['A']);
                $jumlah = (int)$row['B'];
                $jenis = ucwords(strtolower(trim($row['C'])));

                if($nama_komoditas != ''){
                    $komoditas = $this->db->get_where('master_komoditas', ['nama_komoditas' => $nama_komoditas])->row();
                    if($komoditas){
                        $data = [
                            'bulan' => $bulan,
                            'tahun' => $tahun,
                            'id_komoditas' => $komoditas->id_komoditas,
                            'jumlah' => $jumlah,
                            'id_user' => $id_user,
                            'jenis' => $jenis
                        ];
                        $this->db->insert('trx_produksi', $data);
                    }
                }
            }
        }
        redirect('produksi');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_produksi');
        if($ids) {
            $this->db->where_in('id_produksi', $ids);
            $this->db->delete('trx_produksi');
        }
        redirect('produksi');
    }
}
