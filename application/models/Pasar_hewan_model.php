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

    /**
     * Rekap per kecamatan + desa.
     * - Data dari Admin Kecamatan punya kode_desa  -> 1 baris per desa (hanya yang ada datanya)
     * - Data dari Admin Dinas tanpa kode_desa      -> 1 baris per kecamatan, desa "-"
     */
    public function get_pivot_data($bulan, $tahun, $kecamatan = null)
    {
        $this->db->select('
            w.nama_wilayah AS kecamatan,
            w.urut,
            p.kode_desa,
            d.nama_desa AS desa,
            k.nama_komoditas,
            k.urut AS urut_komoditas,
            p.status_pasar,
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_pasar_hewan p');
        $this->db->join('master_wilayah w', 'p.id_wilayah = w.id_wilayah');
        $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');
        $this->db->join('master_desa d', 'p.kode_desa = d.kode_desa', 'left');
        $this->db->where('p.bulan', $bulan);
        $this->db->where('p.tahun', $tahun);
        if (!empty($kecamatan)) {
            $this->db->where('w.kode', $kecamatan);
        }
        $this->db->group_by(['w.nama_wilayah', 'w.urut', 'p.kode_desa', 'd.nama_desa', 'k.nama_komoditas', 'k.urut', 'p.status_pasar']);
        $this->db->order_by('w.urut', 'ASC');
        $this->db->order_by('p.kode_desa', 'ASC');
        $this->db->order_by('k.urut', 'ASC');

        $rows = $this->db->get()->result_array();

        // =====================
        // PIVOT: 1 baris = kecamatan + desa
        // =====================
        $pivot = [];
        $komoditas = [];

        foreach ($rows as $r) {
            $komoditas[$r['nama_komoditas']] = (int)$r['urut_komoditas'];

            $key = $r['kecamatan'] . '|' . $r['kode_desa'];
            if (!isset($pivot[$key])) {
                $pivot[$key] = [
                    'kecamatan' => $r['kecamatan'],
                    'desa'      => !empty($r['desa']) ? $r['desa'] : '-',
                    'kode_desa' => $r['kode_desa'],
                    'total'     => 0,
                    'data'      => []
                ];
            }
            $pivot[$key]['data'][$r['nama_komoditas']][$r['status_pasar']] = (int)$r['jumlah'];
            $pivot[$key]['total'] += (int)$r['jumlah'];
        }

        // desa yang semua angkanya 0 tidak ditampilkan (hanya yang ada datanya)
        foreach ($pivot as $key => $v) {
            if (!empty($v['kode_desa']) && $v['total'] == 0) {
                unset($pivot[$key]);
            }
        }

        asort($komoditas);

        return [
            'komoditas' => array_keys($komoditas),
            'data'      => array_values($pivot)
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
