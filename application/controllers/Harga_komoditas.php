<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga_komoditas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Harga_komoditas_model');
    }

    public function index()
    {
        $data['harga'] = $this->Harga_komoditas_model->get_all();
        $data['title'] = "Harga Komoditas";
        $data['filename'] = "harga_komoditas";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('harga_komoditas', $data);
        $this->load->view('templates/footer', $data);
    }

        /* ===============================
       IMPORT EXCEL
    =============================== */
    public function import()
    {
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';

        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');
        $id_user = $this->session->userdata('id_user');

        if (!$bulan || !$tahun) {
            show_error('Bulan dan Tahun wajib diisi');
        }

        if (!isset($_FILES['file_excel']['tmp_name'])) {
            show_error('File Excel tidak ditemukan');
        }

        $fileTmp = $_FILES['file_excel']['tmp_name'];

        $excel = PHPExcel_IOFactory::load($fileTmp);
        $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);

        foreach ($sheet as $i => $row) {

            // skip judul & header
            if ($i <= 2) continue;

            $no    = trim($row['A']);
            $nama  = trim($row['B']);
            $harga = trim($row['D']);

            // skip A / B / C / D
            if (in_array($no, ['A','B','C','D'])) continue;

            if ($nama == '') continue;

            // harga kosong → 0
            if ($harga === '') {
                $harga = 0;
            } else {
                $harga = str_replace(',', '', $harga);
            }

            // ambil komoditas
            $komoditas = $this->db->get_where(
                'master_komoditas',
                ['nama_komoditas' => $nama]
            )->row();

            if (!$komoditas) continue;
            // print_r($komoditas);
            // print_r($harga);
            // cek data existing
            $cek = $this->db->get_where('trx_harga_komoditas', [
                'id_komoditas' => $komoditas->id_komoditas,
                'bulan'        => $bulan,
                'tahun'        => $tahun,
                'id_wilayah'   => $this->db->get_where('master_wilayah', [
                                    'kode' => $this->session->userdata('kode')
                                ])->row()->id_wilayah
            ])->row();

            if ($cek) {
                // UPDATE
                $this->db->where('id_harga', $cek->id_harga)
                         ->update('trx_harga_komoditas', [
                            'harga'   => $harga,
                            'id_user' => $id_user
                         ]);
            } else {
                // INSERT
                $wilayah = $this->db->get_where('master_wilayah', [
                        'kode' => $this->session->userdata('kode')
                    ])->row();
                $this->db->insert('trx_harga_komoditas', [
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
                    'id_komoditas' => $komoditas->id_komoditas,
                    'harga'        => $harga,
                    'id_user'      => $id_user,
                    'id_wilayah'   => $wilayah->id_wilayah
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Import harga berhasil');
        redirect('harga_komoditas');
    }


    public function download_template()
    {
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $data = $this->db
            ->where('klasifikasi IS NOT NULL', null, false)
            ->order_by('klasifikasi, urut', 'ASC')
            ->get('master_komoditas')
            ->result();

        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);

        /* ===============================
           JUDUL
        =============================== */
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'TEMPLATE HARGA KOMODITAS PETERNAKAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /* ===============================
           HEADER
        =============================== */
        $sheet->fromArray(
            ['NO','JENIS KOMODITAS','SATUAN','HARGA'],
            NULL,
            'A2'
        );

        $sheet->getStyle('A2:D2')->getFont()->setBold(true);
        $sheet->getStyle('A2:D2')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('D9E1F2');

        $row = 3;
        $current = '';
        $no = 1;

        foreach ($data as $d) {

            // Header klasifikasi A / B / C / D
            if ($current != $d->klasifikasi) {
                $sheet->setCellValue('A'.$row, substr($d->klasifikasi,0,1));
                $sheet->setCellValue('B'.$row, $d->klasifikasi);
                $sheet->getStyle('A'.$row.':D'.$row)->getFont()->setBold(true);
                $row++;
                $current = $d->klasifikasi;
                $no = 1;
            }

            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $d->nama_komoditas);
            $sheet->setCellValue('C'.$row, $d->satuan);
            $sheet->setCellValue('D'.$row, 0); // default harga 0

            // unlock kolom harga
            $sheet->getStyle('D'.$row)->getProtection()
                  ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

            $row++;
        }

        // auto width
        foreach (range('A','D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        /* ===============================
           PROTECT SHEET
        =============================== */
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('harga');

        /* ===============================
           DOWNLOAD
        =============================== */
        $filename = 'Template_Harga_Komoditas.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    public function rekap_harga()
    {
        $row = $this->db
            ->select('bulan, tahun')
            ->from('trx_harga_komoditas')
            ->order_by('tanggal_input', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $bulan = $row->bulan ?? date('m');
        $tahun = $row->tahun ?? date('Y');

        $data['tabel'] = 'Harga Komoditas';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        // Komoditas (horizontal)
        $data['komoditas'] = $this->db
            ->where('klasifikasi IS NOT NULL', null, false)
            ->order_by('klasifikasi, urut', 'ASC')
            ->get('master_komoditas')
            ->result();

        // Kecamatan (vertical)
        $data['kecamatan'] = $this->db
            ->where('kode IS NOT NULL', null, false)
            ->get('master_wilayah')
            ->result();

        // Pivot awal
        $data['pivot'] = $this->_getPivotHarga($bulan, $tahun);
        $data['title'] = "Rekap Harga Komoditas";
        $data['filename'] = "rekap_harga_komoditas";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('rekap_komoditas', $data);
        $this->load->view('templates/footer', $data);
    }

    public function get_data_harga()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $pivot = $this->_getPivotHarga($bulan, $tahun);

        $komoditas = $this->db
            ->where('klasifikasi IS NOT NULL', null, false)
            ->order_by('klasifikasi, urut', 'ASC')
            ->get('master_komoditas')
            ->result();

        $komoditasArr = [];
        foreach ($komoditas as $k) {
            $komoditasArr[] = $k->nama_komoditas;
        }

        echo json_encode([
            'komoditas' => $komoditasArr,
            'data' => $pivot
        ]);
    }


    private function _getPivotHarga($bulan, $tahun)
    {
        $this->db->select('
            w.nama_wilayah,
            k.nama_komoditas,
            h.harga
        ');
        $this->db->from('trx_harga_komoditas h');
        $this->db->join('master_wilayah w', 'w.id_wilayah = h.id_wilayah');
        $this->db->join('master_komoditas k', 'k.id_komoditas = h.id_komoditas');
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);

        $query = $this->db->get()->result();

        $pivot = [];
        foreach ($query as $row) {
            $pivot[$row->nama_wilayah][$row->nama_komoditas] = (int)$row->harga;
        }

        return $pivot;
    }

    public function export() {
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // =========================
        // DATA KOMODITAS
        // =========================
        $komoditas = $this->db
            ->where('klasifikasi IS NOT NULL', null, false)
            ->order_by('klasifikasi, urut', 'ASC')
            ->get('master_komoditas')
            ->result();

        // =========================
        // DATA HARGA
        // =========================
        $harga = $this->db
            ->select('w.nama_wilayah, k.nama_komoditas, h.harga')
            ->from('trx_harga_komoditas h')
            ->join('master_wilayah w', 'w.id_wilayah = h.id_wilayah')
            ->join('master_komoditas k', 'k.id_komoditas = h.id_komoditas')
            ->where('h.bulan', $bulan)
            ->where('h.tahun', $tahun)
            ->get()
            ->result();

        // Pivot data
        $pivot = [];
        foreach ($harga as $r) {
            $pivot[$r->nama_wilayah][$r->nama_komoditas] = $r->harga;
        }

        // Group klasifikasi
        $group = [];
        foreach ($komoditas as $k) {
            $group[$k->klasifikasi][] = $k;
        }

        // =========================
        // EXCEL
        // =========================
        $excel = new PHPExcel();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle("Harga Komoditas");

        // ===== HEADER =====
        $rowNum = 1;
        $col = 1;

        // Header klasifikasi
        foreach ($group as $klasifikasi => $items) {
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $klasifikasi);
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + count($items) - 1, $rowNum);
            $col += count($items);
        }

        // Sub-header komoditas
        $rowNum++;
        $col = 1;
        foreach ($group as $items) {
            foreach ($items as $k) {
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $k->nama_komoditas);
                $col++;
            }
        }

        // Header Kecamatan
        $sheet->setCellValue('A1', 'Kecamatan');
        $sheet->mergeCells('A1:A2');

        // Styling header
        $lastCol = PHPExcel_Cell::stringFromColumnIndex($col-1);
        $sheet->getStyle("A1:{$lastCol}{$rowNum}")->applyFromArray([
            'font' => ['bold'=>true, 'color'=>['rgb'=>'FFFFFF']],
            'fill' => ['type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>['rgb'=>'1F497D']],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ],
            'borders' => ['allborders'=>['style'=>PHPExcel_Style_Border::BORDER_THIN]]
        ]);
        $sheet->freezePane('B3');

        // ===== DATA =====
        $rowNum++;
        $colHarga = []; // untuk menyimpan semua harga tiap kolom untuk rata-rata

        foreach ($pivot as $kec => $data) {
            $sheet->setCellValue('A'.$rowNum, $kec);
            $col = 1;

            foreach ($group as $items) {
                foreach ($items as $k) {
                    $val = $data[$k->nama_komoditas] ?? 0;
                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);

                    // Simpan untuk rata-rata
                    $colHarga[$col][] = $val;

                    $col++;
                }
            }

            // Zebra stripe
            if ($rowNum % 2 == 0) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }

            $rowNum++;
        }

        // ===== RATA-RATA =====
        $sheet->setCellValue('A'.$rowNum, 'Rata-rata');
        foreach ($colHarga as $colIdx => $vals) {
            $avg = count($vals) > 0 ? round(array_sum($vals) / count($vals), 2) : 0;
            $sheet->setCellValueByColumnAndRow($colIdx, $rowNum, $avg);
        }
        $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
            'font'=>['bold'=>true],
            'fill'=>['type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>['rgb'=>'D9D9D9']]
        ]);

        // ===== FORMAT ANGKA =====
        $sheet->getStyle("B3:{$lastCol}{$rowNum}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // ===== BORDER SEMUA SEL =====
        $sheet->getStyle("A1:{$lastCol}{$rowNum}")->getBorders()->getAllBorders()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // ===== AUTO SIZE KOLUM =====
        foreach (range('A', $lastCol) as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }

        // ===== DOWNLOAD =====
        $filename = "Rekap_Harga_{$bulan}_{$tahun}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

}
