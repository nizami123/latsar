<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga_model extends CI_Model {

    public function get_all()
    {
        $this->db->select('trx_harga.*, master_komoditas.nama_komoditas, master_user.nama_user');
        $this->db->from('trx_harga');
        $this->db->join('master_komoditas', 'trx_harga.id_komoditas = master_komoditas.id_komoditas');
        $this->db->join('master_user', 'trx_harga.id_user = master_user.id_user');
        return $this->db->get()->result();
    }

     public function getLatestHarga()
    {
        $sql = "
            SELECT h.id_komoditas, m.nama_komoditas, h.harga, h.tanggal
            FROM trx_harga h
            JOIN master_komoditas m ON h.id_komoditas = m.id_komoditas
            WHERE h.tanggal = (
                SELECT MAX(h2.tanggal)
                FROM trx_harga h2
                WHERE h2.id_komoditas = h.id_komoditas
            )
            ORDER BY m.id_komoditas ASC
        ";
        return $this->db->query($sql)->result();
    }

    public function getLastTanggal()
    {
        $sql = "SELECT MAX(tanggal) as last_date FROM trx_harga";
        $row = $this->db->query($sql)->row();
        return $row ? $row->last_date : null;
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('trx_harga', ['id_harga' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('trx_harga', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_harga', $id);
        return $this->db->update('trx_harga', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('trx_harga', ['id_harga' => $id]);
    }
}
