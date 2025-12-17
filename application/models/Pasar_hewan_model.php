<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasar_hewan_model extends CI_Model {

    /**
     * Ambil seluruh data pasar hewan (untuk tabel view)
     */
    public function getAll() {
        $this->db->select('
            p.*,
            w.nama_wilayah,
            w.kode,
            k.nama_komoditas,
            d.nama_desa
        ');
        $this->db->from('trx_pasar_hewan p');
        $this->db->join('master_wilayah w', 'w.id_wilayah = p.id_wilayah');
        $this->db->join('master_komoditas k', 'k.id_komoditas = p.id_komoditas');
        $this->db->join('master_desa d', 'd.kode_desa = p.kode_desa', 'left');

        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $this->db->where('w.kode', $this->session->userdata('kode'));
        }

        $this->db->order_by('p.tahun', 'DESC');
        $this->db->order_by('p.bulan', 'DESC');
        $this->db->order_by('d.nama_desa', 'ASC');
        $this->db->order_by('k.urut', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Insert dipakai INTERNAL (upload excel)
     * ❗ Tidak dipakai dari UI manual
     */
    public function insert($data) {
        $this->db->insert('trx_pasar_hewan', $data);
    }

    public function get_pivot_data($bulan, $tahun, $kecamatan = null)
    {
        if (!empty($kecamatan)) {

            $this->db->select('
                d.nama_desa AS kecamatan,
                k.nama_komoditas,
                p.status_pasar,
                SUM(p.jumlah) AS jumlah,
                d.kode_desa AS urut
            ');
            $this->db->from('trx_pasar_hewan p');
            $this->db->join('master_desa d','p.kode_desa=d.kode_desa');
            $this->db->join('master_komoditas k','p.id_komoditas=k.id_komoditas');
            $this->db->where('p.bulan', $bulan);
            $this->db->where('p.tahun', $tahun);
            $this->db->where('d.kode', $kecamatan);
            $this->db->group_by(['d.nama_desa','k.nama_komoditas','p.status_pasar','d.kode_desa']);
            $this->db->order_by('d.kode_desa','ASC');
            $this->db->order_by('k.urut','ASC');

        } else {

            $this->db->select('
                w.nama_wilayah AS kecamatan,
                k.nama_komoditas,
                p.status_pasar,
                SUM(p.jumlah) AS jumlah,
                w.urut
            ');
            $this->db->from('trx_pasar_hewan p');
            $this->db->join('master_wilayah w','p.id_wilayah=w.id_wilayah');
            $this->db->join('master_komoditas k','p.id_komoditas=k.id_komoditas');
            $this->db->where('p.bulan', $bulan);
            $this->db->where('p.tahun', $tahun);
            $this->db->group_by(['w.nama_wilayah','k.nama_komoditas','p.status_pasar','w.urut']);
            $this->db->order_by('w.urut','ASC');
            $this->db->order_by('k.urut','ASC');
        }

        $rows = $this->db->get()->result_array();

        // =====================
        // PIVOT SESUAI VIEW
        // =====================
        $pivot = [];
        $komoditas = [];

        foreach ($rows as $r) {
            if (!in_array($r['nama_komoditas'], $komoditas)) {
                $komoditas[] = $r['nama_komoditas'];
            }

            $pivot[$r['kecamatan']][$r['nama_komoditas']][$r['status_pasar']] = (int)$r['jumlah'];
            $pivot[$r['kecamatan']]['urut'] = $r['urut'];
        }

        uasort($pivot, function($a, $b){
            return ($a['urut'] ?? 0) <=> ($b['urut'] ?? 0);
        });

        return [
            'komoditas' => $komoditas,
            'data'      => $pivot
        ];
    }


    /**
     * Total per komoditas (Masuk / Laku)
     */
    public function get_total_per_komoditas($bulan, $tahun) {
        return $this->db->select('
                mk.id_komoditas,
                mk.nama_komoditas,
                tp.status_pasar,
                SUM(tp.jumlah) AS total
            ')
            ->from('trx_pasar_hewan tp')
            ->join('master_komoditas mk', 'tp.id_komoditas = mk.id_komoditas')
            ->where('tp.bulan', $bulan)
            ->where('tp.tahun', $tahun)
            ->group_by([
                'mk.id_komoditas',
                'tp.status_pasar'
            ])
            ->order_by('mk.urut', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Ambil periode terakhir
     */
    public function get_latest_period() {
        return $this->db->select('tahun, bulan')
            ->from('trx_pasar_hewan')
            ->order_by('tahun', 'DESC')
            ->order_by('bulan', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function getKecamatan()
    {
        return $this->db->get('master_wilayah')->result();
    }
}
