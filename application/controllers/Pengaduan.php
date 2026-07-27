<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaduan extends CI_Controller{

    public function __construct(){

        parent::__construct();

        $this->load->model('Pengaduan_model');
        $this->load->library('form_validation');

    }

    public function index(){

        $this->load->view('pengaduan/index');

    }
    
    public function simpan()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'required');
        $this->form_validation->set_rules('pengaduan', 'Pengaduan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('pengaduan/index');
            return;
        }

        // Folder upload
        if (!is_dir('./uploads/pengaduan/')) {
            mkdir('./uploads/pengaduan/', 0777, true);
        }

        // Konfigurasi upload
        $config['upload_path']      = './uploads/pengaduan/';
        $config['allowed_types']    = 'jpg|jpeg|png|webp';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = 5120;
        $config['remove_spaces']    = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        $foto = '';

        if ($this->upload->do_upload('foto')) {

            $upload = $this->upload->data();
            $foto = $upload['file_name'];

            // Resize + Kompres
            $resize['image_library']  = 'gd2';
            $resize['source_image']   = $upload['full_path'];
            $resize['new_image']      = $upload['full_path'];
            $resize['maintain_ratio'] = TRUE;
            $resize['width']          = 1024;
            $resize['height']         = 1024;
            $resize['quality']        = '60%';

            $this->load->library('image_lib');
            $this->image_lib->clear();
            $this->image_lib->initialize($resize);

            if (!$this->image_lib->resize()) {
                log_message('error', $this->image_lib->display_errors());
            }

            $this->image_lib->clear();

        } else {

            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('pengaduan');
            return;

        }

        $nomor = 'PGD' . date('YmdHis');

        $data = array(
            'nomor_pengaduan' => $nomor,
            'tanggal'         => date('Y-m-d H:i:s'),
            'nama'            => $this->input->post('nama', TRUE),
            'no_hp'           => $this->input->post('no_hp', TRUE),
            'lokasi'          => $this->input->post('lokasi', TRUE),
            'pengaduan'       => $this->input->post('pengaduan', TRUE),
            'foto'            => $foto,
            'status'          => 'Menunggu'
        );

        $this->Pengaduan_model->simpan($data);

        redirect('pengaduan/sukses');
    }

    public function sukses(){

        $this->load->view('pengaduan/sukses');

    }

}