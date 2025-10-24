<?php
class Masuk_model extends CI_Model {
    public function getAll() {
        $this->db->select('p.*, w.nama_wilayah,w.kode, k.nama_komoditas, d.nama_desa');
        $this->db->from('trx_masuk p');
        $this->db->join('master_wilayah w','w.id_wilayah=p.id_wilayah');
        $this->db->join('master_komoditas k','k.id_komoditas=p.id_komoditas');
        $this->db->join('master_desa d','d.kode_desa=p.kode_desa','left');
        if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $this->db->where('w.kode', $this->session->userdata('kode'));
        }
        return $this->db->get()->result();
    }

    public function insert($data) {
        $this->db->insert('trx_masuk',$data);
    }

    public function update($id,$data) {
        $this->db->where('id_masuk',$id);
        $this->db->update('trx_masuk',$data);
    }

    public function delete($id) {
        $this->db->where('id_masuk',$id);
        $this->db->delete('trx_masuk');
    }

    public function get_pivot_data($bulan, $tahun) {
        $this->db->select('
            w.nama_wilayah AS kecamatan,
            k.nama_komoditas,
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_masuk p');
        $this->db->join('master_wilayah w', 'p.id_wilayah = w.id_wilayah');
        $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');
        $this->db->where('p.bulan', $bulan);
        $this->db->where('p.tahun', $tahun);
        $this->db->group_by(['p.id_wilayah', 'p.id_komoditas', 'w.nama_wilayah', 'k.nama_komoditas']);
        $this->db->order_by('w.nama_wilayah');
        $this->db->order_by('k.urut'); // agar komoditas urut sesuai master
        return $this->db->get()->result_array();
    }

    public function get_pivot_data_kecamatan($bulan, $tahun) {
        $this->db->select('
            d.nama_desa AS kecamatan,
            k.nama_komoditas,
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_masuk p');
        $this->db->join('master_desa d', 'p.kode_desa = d.kode_desa');
        $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');
        $this->db->where('p.bulan', $bulan);
        $this->db->where('p.tahun', $tahun);
        $this->db->where('d.kode', $this->session->userdata('kode'));
        $this->db->group_by(['p.id_wilayah', 'p.id_komoditas', 'p.kode_desa']);
        return $this->db->get()->result_array();
    }


    public function get_total_per_komoditas($bulan, $tahun){
    return $this->db->select('mk.id_komoditas as id_komoditas, mk.nama_komoditas, SUM(tp.jumlah) as total')
        ->from('trx_masuk tp')
        ->join('master_komoditas mk', 'tp.id_komoditas = mk.id_komoditas')
        ->where('tp.bulan', $bulan)
        ->where('tp.tahun', $tahun)
        ->group_by(['mk.id_komoditas', 'mk.nama_komoditas'])
        ->order_by('mk.id_komoditas', 'ASC')
        ->get()
        ->result();
    }

    public function get_latest_period(){
        return $this->db->select('tahun, bulan')
            ->from('trx_masuk')
            ->order_by('tahun', 'DESC')
            ->order_by('bulan', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }
}
