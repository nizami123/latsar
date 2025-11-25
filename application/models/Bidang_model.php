<?php
class Bidang_model extends CI_Model {

    public function get_pivot_data($tabel, $bulan, $tahun ) {
        $this->db->select('
            w.nama_wilayah AS kecamatan,
            k.nama_komoditas,
            p.jenis_kelamin,
            p.umur,
            SUM(p.jumlah) AS jumlah,
            CASE 
                WHEN ('.$tahun.' < 2025) 
                    OR ('.$tahun.' = 2025 AND '.$bulan.' < 10)
                THEN 1
                ELSE (
                    SELECT COUNT(*) 
                    FROM trx_masuk m
                    WHERE m.bulan = '.$bulan.'
                    AND m.tahun = '.$tahun.'
                    AND m.id_wilayah = p.id_wilayah
                )
            END AS hitung
        ');
        $this->db->from('trx_'.$tabel.' p');
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
            'p.umur'
        ]);
        $this->db->order_by('k.urut', 'ASC');
        return $this->db->get()->result_array();
    }
}
