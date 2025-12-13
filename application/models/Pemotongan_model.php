<?php
class Pemotongan_model extends CI_Model {
    public function getAll() {
        $this->db->select('p.*, w.nama_wilayah, k.nama_komoditas');
        $this->db->from('trx_pemotongan p');
        $this->db->join('master_wilayah w','w.id_wilayah=p.id_wilayah');
        $this->db->join('master_komoditas k','k.id_komoditas=p.id_komoditas');
        $this->db->join('master_desa d','d.kode_desa=p.kode_desa','left');
        if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $this->db->where('w.kode', $this->session->userdata('kode'));
        }
        return $this->db->get()->result();
    }

    public function insert($data) {
        $this->db->insert('trx_pemotongan',$data);
    }

    public function update($id,$data) {
        $this->db->where('id_pemotongan',$id);
        $this->db->update('trx_pemotongan',$data);
    }

    public function delete($id) {
        $this->db->where('id_pemotongan',$id);
        $this->db->delete('trx_pemotongan');
    }

    public function get_pivot_data($bulan, $tahun) {
        $this->db->select('
            w.nama_wilayah AS kecamatan,
            k.nama_komoditas,
            p.jenis_kelamin,
            SUM(p.jumlah) AS jumlah,
            w.urut AS urut_wilayah,
            CASE 
                WHEN (' . $tahun . ' < 2025) 
                    OR (' . $tahun . ' = 2025 AND ' . $bulan . ' < 10)
                THEN 1
                ELSE (
                    SELECT COUNT(*) 
                    FROM trx_masuk m
                    WHERE m.bulan = ' . $bulan . '
                    AND m.tahun = ' . $tahun . '
                    AND m.id_wilayah = p.id_wilayah
                )
            END AS hitung
        ');

        // GANTI TABEL
        $this->db->from('trx_pemotongan p');

        $this->db->join('master_wilayah w', 'p.id_wilayah = w.id_wilayah');
        $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');

        $this->db->where('p.bulan', $bulan);
        $this->db->where('p.tahun', $tahun);

        $this->db->group_by([
            'p.id_wilayah',
            'p.id_komoditas',
            'w.nama_wilayah',
            'k.nama_komoditas',
            'p.jenis_kelamin',
        ]);

        $this->db->order_by('k.urut', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_pivot_data_kecamatan($bulan, $tahun) {
        $this->db->select('
            d.nama_desa AS kecamatan,
            k.nama_komoditas,
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_pemotongan p');
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
        ->from('trx_pemotongan tp')
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
            ->from('trx_pemotongan')
            ->order_by('tahun', 'DESC')
            ->order_by('bulan', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }
}
