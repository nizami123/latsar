<?php
class Produksi_model extends CI_Model {

    public function getAll(){
        $this->db->select('p.*, k.nama_komoditas, u.nama_user');
        $this->db->from('trx_produksi p');
        $this->db->join('master_komoditas k', 'p.id_komoditas=k.id_komoditas','left');
        $this->db->join('master_user u', 'p.id_user=u.id_user','left');
        return $this->db->get()->result();
    }

    public function insert($data){
        return $this->db->insert('trx_produksi', $data);
    }

    public function update($id, $data){
        $this->db->where('id_produksi', $id);
        return $this->db->update('trx_produksi', $data);
    }

    public function delete($id){
        return $this->db->delete('trx_produksi', ['id_produksi' => $id]);
    }
}
