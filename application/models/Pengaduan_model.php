<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaduan_model extends CI_Model{

    public function simpan($data){

        return $this->db->insert('pengaduan',$data);

    }

}