<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
        $this->load->model('Layanan_model');
    }

    public function index()
    {
        $data['layanan'] = $this->Layanan_model->get_all();
        $data['title'] = "Data Layanan";
        $data['filename'] = "data_layanan";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('master/layanan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save()
    {
        $data = [
            'nama_layanan' => $this->input->post('nama_layanan')
        ];
        $this->Layanan_model->insert($data);
        redirect('layanan');
    }

    public function update()
    {
        $id = $this->input->post('id_layanan');
        $data = [
            'nama_layanan' => $this->input->post('nama_layanan')
        ];
        $this->Layanan_model->update($id, $data);
        redirect('layanan');
    }

    public function delete($id)
    {
        $this->Layanan_model->delete($id);
        redirect('layanan');
    }
}
