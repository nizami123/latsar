<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komoditas_model extends CI_Model {

    public function get_all()
    {
        return $this->db->get('master_komoditas')->result();
    }

    public function getKomoditasPopulasi()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }
    
    public function getKomoditasMasuk()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }
    
    public function getKomoditasKeluar()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }

    public function getKomoditasKelahiran()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }

    public function getKomoditasKematian()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }

     public function getKomoditasPemotongan()
    {
        $this->db->where_not_in('jenis', ['Komoditas', 'Hasil Ternak']);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_komoditas')->result();
    }

    public function getKecamatanIndexed() {
        $res = $this->getKecamatan();
        $arr = [];
        foreach($res as $r){
            $arr[$r->nama_wilayah] = $r->id_wilayah;
        }
        return $arr;
    }

    public function getDesaIndexed() {
        $res = $this->getDesa();
        $arr = [];
        foreach($res as $r){
            $arr[$r->nama_desa] = $r->kode_desa;
        }
        return $arr;
    }

    public function getWilayahIndexed() {
        $res = $this->getWilayah();
        $arr = [];
        foreach($res as $r){
            $arr[$r->nama_wilayah] = $r->id_wilayah;
        }
        return $arr;
    }

    public function getKomoditasIndexed() {
        $res = $this->db->get('master_komoditas')->result();
        $arr = [];
        foreach($res as $r){
            $arr[$r->nama_komoditas] = $r->id_komoditas;
        }
        return $arr;
    }

    public function getKecamatan() {
        $this->db->where('urut <', 100);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_wilayah')->result();
    }

    public function getDesa() {
        $this->db->where('kode', $this->session->userdata('kode'));
        return $this->db->get('master_desa')->result();
    }

    public function getWilayah() {
        $this->db->where('urut >', 100);
        $this->db->order_by('urut', 'ASC');
        return $this->db->get('master_wilayah')->result();
    }

    public function get_populasi()
    {
        $this->db->where_not_in('jenis', ['komoditas', 'hasil ternak']);
        return $this->db->get('master_komoditas')->result();
    }

    public function get_komoditas()
    {
        $this->db->where_in('jenis', ['komoditas']);
        return $this->db->get('master_komoditas')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('master_komoditas', ['id_komoditas' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('master_komoditas', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_komoditas', $id);
        return $this->db->update('master_komoditas', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('master_komoditas', ['id_komoditas' => $id]);
    }
}
