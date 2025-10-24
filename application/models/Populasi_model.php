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
            SUM(p.jumlah) AS jumlah
        ');
        $this->db->from('trx_populasi p');
        $this->db->join('master_wilayah w', 'p.id_wilayah = w.id_wilayah');
        $this->db->join('master_komoditas k', 'p.id_komoditas = k.id_komoditas');
        $this->db->where('p.bulan', $bulan);
        $this->db->where('p.tahun', $tahun);
        $this->db->group_by(['p.id_wilayah', 'p.id_komoditas', 'w.nama_wilayah', 'k.nama_komoditas']);
        $this->db->order_by('k.urut'); // agar komoditas urut sesuai master
        return $this->db->get()->result_array();
    }

    public function get_pivot_data_new($bulan, $tahun) {
        $query = "SELECT 
            d.nama_wilayah  AS kecamatan,
            k.nama_komoditas,
            coalesce(SUM(p.jumlah),0) + coalesce(SUM(kb.jumlah),0) -coalesce(SUM(kl.jumlah),0) + coalesce(SUM(ms.jumlah),0) - coalesce(SUM(kt.jumlah),0) as jumlah
        FROM master_wilayah d
        JOIN master_komoditas k
        LEFT JOIN trx_populasi p ON p.id_wilayah = d.id_wilayah AND p.id_komoditas = k.id_komoditas 
            AND p.bulan = MONTH(DATE_SUB(CONCAT(".$tahun.", '-', LPAD(".$bulan.", 2, '0'), '-01'), INTERVAL 1 MONTH))
            AND p.tahun = YEAR(DATE_SUB(CONCAT(".$tahun.", '-', LPAD(".$bulan.", 2, '0'), '-01'), INTERVAL 1 MONTH))
        LEFT JOIN trx_kelahiran kb ON kb.id_wilayah = d.id_wilayah AND kb.id_komoditas = k.id_komoditas
            AND p.bulan = ".$bulan."
            AND p.tahun = ".$tahun."
        LEFT JOIN trx_kematian kt ON kt.id_wilayah = d.id_wilayah AND kt.id_komoditas = k.id_komoditas
            AND p.bulan = ".$bulan."
            AND p.tahun = ".$tahun."
        LEFT JOIN trx_keluar kl ON kl.id_wilayah = d.id_wilayah AND kl.id_komoditas = k.id_komoditas
            AND p.bulan = ".$bulan."
            AND p.tahun = ".$tahun."
        LEFT JOIN trx_masuk ms ON ms.id_wilayah = d.id_wilayah AND ms.id_komoditas = k.id_komoditas
            AND p.bulan = ".$bulan."
            AND p.tahun = ".$tahun."
        WHERE k.urut is not null
        GROUP BY d.id_wilayah, k.nama_komoditas
        ORDER BY k.urut ASC";
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
