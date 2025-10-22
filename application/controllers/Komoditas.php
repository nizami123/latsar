<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komoditas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
        $this->load->model('Komoditas_model');
    }

    public function index()
    {
        $data['komoditas'] = $this->Komoditas_model->get_all();
        $data['title'] = "Data Komoditas";
        $data['filename'] = "data_komoditas";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('master/komoditas/list', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('master/komoditas/add');
        $this->load->view('templates/footer');
    }

    public function save()
    {
        $data = [
            'nama_komoditas' => $this->input->post('nama_komoditas'),
            'jenis' => $this->input->post('jenis'),
            'satuan' => $this->input->post('satuan'),
            'created_by' => $this->session->userdata('id_user')
        ];
        $this->Komoditas_model->insert($data);
        redirect('komoditas');
    }

    public function edit($id)
    {
        $data['komoditas'] = $this->Komoditas_model->get_by_id($id);
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('master/komoditas/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $data = [
            'nama_komoditas' => $this->input->post('nama_komoditas'),
            'jenis' => $this->input->post('jenis'),
            'satuan' => $this->input->post('satuan')
        ];
        $this->Komoditas_model->update($id, $data);
        redirect('komoditas');
    }

    public function delete($id)
    {
        $this->Komoditas_model->delete($id);
        redirect('komoditas');
    }

  
}
