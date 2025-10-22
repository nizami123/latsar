<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyakit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
        $this->load->model('Penyakit_model');
    }

    public function index()
    {
        $data['penyakit'] = $this->Penyakit_model->get_all();
        $data['title'] = "Data Penyakit";
        $data['filename'] = "data_penyakit";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('master/penyakit', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save()
    {
        $data = [
            'nama_penyakit' => $this->input->post('nama_penyakit')
        ];
        $this->Penyakit_model->insert($data);
        redirect('penyakit');
    }

    public function update()
    {
        $id = $this->input->post('id_penyakit');
        $data = [
            'nama_penyakit' => $this->input->post('nama_penyakit')
        ];
        $this->Penyakit_model->update($id, $data);
        redirect('penyakit');
    }

    public function delete($id)
    {
        $this->Penyakit_model->delete($id);
        redirect('penyakit');
    }
}
