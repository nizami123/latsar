<?php
class Bidang_model extends CI_Model {

    public function get_pivot_data($tabel, $bulan, $tahun, $kecamatan = null) {

        if (!empty($kecamatan)) {
            $this->db->select('
                d.nama_desa AS kecamatan,   
                k.nama_komoditas,
                p.jenis_kelamin,
                p.umur,
                SUM(p.jumlah) AS jumlah,
                d.kode_desa AS urut_wilayah,
                CASE 
                    WHEN ('.$tahun.' < 2025) 
                        OR ('.$tahun.' = 2025 AND '.$bulan.' < 10)
                    THEN 1
                    ELSE (
                        SELECT COUNT(*) 
                        FROM trx_masuk m
                        WHERE m.bulan = '.$bulan.'
                        AND m.tahun = '.$tahun.'
                        AND m.kode_desa = p.kode_desa
                    )
                END AS hitung
            ');
            $this->db->from('trx_'.$tabel.' p');
            $this->db->join('master_desa d', 'p.kode_desa = d.kode_desa');
            $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');
            $this->db->where('p.bulan', $bulan);
            $this->db->where('p.tahun', $tahun);
            $this->db->where('d.kode', $kecamatan); // kode kecamatan yang dipilih
            $this->db->group_by([
                'p.kode_desa',
                'p.id_komoditas',
                'd.nama_desa',
                'k.nama_komoditas',
                'p.jenis_kelamin',
                'p.umur'
            ]);
            $this->db->order_by('k.urut', 'ASC');

        } else {

            // TIDAK pilih kecamatan → tampil per kecamatan seperti awal
            $this->db->select('
                w.nama_wilayah AS kecamatan,
                k.nama_komoditas,
                p.jenis_kelamin,
                p.umur,
                SUM(p.jumlah) AS jumlah,
                w.urut AS urut_wilayah,
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
        }

        return $this->db->get()->result_array();
    }
}
