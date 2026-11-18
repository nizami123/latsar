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
            SELECT h.id_komoditas, m.nama_komoditas, h.harga, h.tanggal, m.satuan
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

    // Daftar kolom komoditas harga (urutan = urutan kolom di tabel)
    public function get_kolom_harga()
    {
        $nama = ['Sapi','Daging Sapi','Ayam Broiler','Karkas Ayam Broiler','Telur Ayam Ras (P)','Telur Ayam Ras (K)'];
        $rows = $this->db->where_in('nama_komoditas', $nama)
                         ->where('jenis', 'Komoditas')
                         ->where('created_by', 0)
                         ->get('master_komoditas')->result();
        $by_name = [];
        foreach ($rows as $r) { $by_name[$r->nama_komoditas] = $r; }

        $kolom = [];
        foreach ($nama as $n) {
            if (isset($by_name[$n])) { $kolom[] = $by_name[$n]; }
        }
        return $kolom;
    }

    // Pivot: 1 baris per tanggal, 1 kolom per komoditas
    public function get_pivot($kolom)
    {
        if (empty($kolom)) { return []; }
        $ids = array_map(function($k){ return $k->id_komoditas; }, $kolom);

        $rows = $this->db->select('tanggal, id_komoditas, harga')
                         ->where_in('id_komoditas', $ids)
                         ->order_by('tanggal', 'DESC')
                         ->get('trx_harga')->result();

        $pivot = [];
        foreach ($rows as $r) {
            if (!isset($pivot[$r->tanggal])) {
                $pivot[$r->tanggal] = ['tanggal' => $r->tanggal, 'harga' => []];
            }
            $pivot[$r->tanggal]['harga'][$r->id_komoditas] = $r->harga;
        }
        return array_values($pivot);
    }

    public function delete_by_tanggal($tanggal)
    {
        return $this->db->delete('trx_harga', ['tanggal' => $tanggal]);
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
