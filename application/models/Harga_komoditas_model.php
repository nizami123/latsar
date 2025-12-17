<?php
class Harga_komoditas_model extends CI_Model {

    public function get_all()
    {
        // ambil wilayah user login
        $wilayah = $this->db->get_where('master_wilayah', [
            'kode' => $this->session->userdata('kode')
        ])->row();

        if (!$wilayah) {
            return [];
        }

        return $this->db
            ->select('
                t.bulan,
                t.tahun,
                m.nama_komoditas,
                m.satuan,
                t.harga
            ')
            ->from('trx_harga_komoditas t')
            ->join('master_komoditas m', 'm.id_komoditas = t.id_komoditas')
            ->where('t.id_wilayah', $wilayah->id_wilayah)
            ->order_by('t.tahun DESC, t.bulan DESC, m.urut ASC')
            ->get()
            ->result();
    }
}
