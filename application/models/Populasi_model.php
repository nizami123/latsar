<?php
class Populasi_model extends CI_Model {
    public function getAll() {
        $this->db->select('p.*, w.nama_wilayah,w.kode, k.nama_komoditas, d.nama_desa');
        $this->db->from('trx_populasi p');
        $this->db->join('master_wilayah w','w.id_wilayah=p.id_wilayah');
        $this->db->join('master_komoditas k','k.id_komoditas=p.id_komoditas');
        $this->db->join('master_desa d','d.kode_desa=p.kode_desa','left');
        if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $this->db->where('w.kode', $this->session->userdata('kode'));
        }
        return $this->db->get()->result();
    }

    public function insert($data) {
        $this->db->insert('trx_populasi',$data);
    }

    public function update($id,$data) {
        $this->db->where('id_populasi',$id);
        $this->db->update('trx_populasi',$data);
    }

    public function delete($id) {
        $this->db->where('id_populasi',$id);
        $this->db->delete('trx_populasi');
    }

    public function get_pivot_data($bulan, $tahun) {
        $this->db->select('
            w.nama_wilayah AS kecamatan,
            k.nama_komoditas,
            p.jenis_kelamin,
            p.umur,
            SUM(p.jumlah) AS jumlah,
            (SELECT COUNT(*) 
                FROM trx_masuk m
                WHERE m.bulan = '.$bulan.' 
                AND m.tahun = '.$tahun.'
                AND m.id_wilayah = p.id_wilayah
            ) AS hitung
        ');
        $this->db->from('trx_populasi p');
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

    public function get_pivot_data_new($bulan, $tahun) {
        $query = "SELECT
        d.nama_wilayah AS kecamatan,
        k.nama_komoditas,
        COALESCE(p.pop_jumlah,0) AS populasi,
        COALESCE(kb.kelahiran_jumlah,0) AS kelahiran,
        COALESCE(kt.kematian_jumlah,0) AS kematian,
        COALESCE(ms.masuk_jumlah,0) AS masuk,
        COALESCE(kl.keluar_jumlah,0) AS keluar,
        COALESCE(pm.pemotongan_jumlah,0) AS pemotongan,
        (COALESCE(p.pop_jumlah,0) + COALESCE(kb.kelahiran_jumlah,0) + COALESCE(ms.masuk_jumlah,0)
        - COALESCE(kl.keluar_jumlah,0) - COALESCE(kt.kematian_jumlah,0) - COALESCE(pm.pemotongan_jumlah,0)
        ) AS jumlah
        FROM master_wilayah d
        CROSS JOIN master_komoditas k
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS pop_jumlah
        FROM trx_populasi
        WHERE bulan = MONTH(DATE_SUB(CONCAT('".$tahun."','-','".$bulan."','-01'), INTERVAL 1 MONTH))
            AND tahun = YEAR(DATE_SUB(CONCAT('".$tahun."','-','".$bulan."','-01'), INTERVAL 1 MONTH))
        GROUP BY id_wilayah, id_komoditas
        ) p ON p.id_wilayah = d.id_wilayah AND p.id_komoditas = k.id_komoditas
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS kelahiran_jumlah
        FROM trx_kelahiran
        WHERE bulan='".$bulan."' AND tahun='".$tahun."'
        GROUP BY id_wilayah, id_komoditas
        ) kb ON kb.id_wilayah = d.id_wilayah AND kb.id_komoditas = k.id_komoditas
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS kematian_jumlah
        FROM trx_kematian
        WHERE bulan='".$bulan."' AND tahun='".$tahun."'
        GROUP BY id_wilayah, id_komoditas
        ) kt ON kt.id_wilayah = d.id_wilayah AND kt.id_komoditas = k.id_komoditas
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS keluar_jumlah
        FROM trx_keluar
        WHERE bulan='".$bulan."' AND tahun='".$tahun."'
        GROUP BY id_wilayah, id_komoditas
        ) kl ON kl.id_wilayah = d.id_wilayah AND kl.id_komoditas = k.id_komoditas
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS masuk_jumlah
        FROM trx_masuk
        WHERE bulan='".$bulan."' AND tahun='".$tahun."'
        GROUP BY id_wilayah, id_komoditas
        ) ms ON ms.id_wilayah = d.id_wilayah AND ms.id_komoditas = k.id_komoditas
        LEFT JOIN (
        SELECT id_wilayah, id_komoditas, SUM(jumlah) AS pemotongan_jumlah
        FROM trx_pemotongan
        WHERE bulan='".$bulan."' AND tahun='".$tahun."'
        GROUP BY id_wilayah, id_komoditas
        ) pm ON pm.id_wilayah = d.id_wilayah AND pm.id_komoditas = k.id_komoditas
        WHERE k.urut IS NOT NULL and d.urut < 100
        ORDER BY d.urut ASC, k.urut ASC";
        return $this->db->query($query)->result_array();
    }

    public function get_pivot_data_kecamatan($bulan, $tahun) {
        $this->db->select('
            d.nama_desa AS kecamatan,
            k.nama_komoditas,
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_populasi p');
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
        ->from('trx_populasi tp')
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
            ->from('trx_populasi')
            ->order_by('tahun', 'DESC')
            ->order_by('bulan', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }
}
