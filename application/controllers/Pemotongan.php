<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemotongan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pemotongan_model','Komoditas_model']);
    }

    public function index() {
        $data['pemotongan'] = $this->Pemotongan_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getWilayah();
        $data['komoditas'] = $this->Komoditas_model->getKomoditasPemotongan();
        $data['title'] = "Data Pemotongan";
        $data['filename'] = "data_pemotongan";
        $data['js'] = 'pemotongan.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('pemotongan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function save() {
        $post = $this->input->post();
        $data = [
            'bulan' => $post['bulan'],
            'tahun' => $post['tahun'],
            'id_wilayah' => $post['id_wilayah'],
            'id_komoditas' => $post['id_komoditas'],
            'jumlah' => $post['jumlah'],
            'id_user' => $this->session->userdata('id_user')
        ];
        $this->Pemotongan_model->insert($data);
        redirect('pemotongan');
    }

    public function update() {
        $post = $this->input->post();
        $data = [
            'bulan' => $post['bulan'],
            'tahun' => $post['tahun'],
            'id_wilayah' => $post['id_wilayah'],
            'id_komoditas' => $post['id_komoditas'],
            'jumlah' => $post['jumlah'],
            'id_user' => $this->session->userdata('id_user')
        ];
        $this->Pemotongan_model->update($post['id_pemotongan'], $data);
        redirect('pemotongan');
    }

    public function delete($id) {
        $this->Pemotongan_model->delete($id);
        redirect('pemotongan');
    }

    public function download_template() {
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $kec = $this->db->get_where('master_wilayah', [
                'kode' => $this->session->userdata('kode')
            ])->row();
            $wilayah = $this->Komoditas_model->getDesa();
            $header = 'DATA PEMOTONGAN KECAMATAN ' . strtoupper($kec->nama_wilayah);
        } else {
            $wilayah = $this->Komoditas_model->getWilayah();
            $header = 'DATA PEMOTONGAN KABUPATEN LAMONGAN';
        }
        $komoditas = $this->Komoditas_model->getKomoditasPemotongan();

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Pemotongan');

        // ===== JUDUL UTAMA =====
        $sheet->setCellValue('A1', $header);
        $sheet->mergeCells('A1:U1');
        $sheet->getRowDimension('1')->setRowHeight(30);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 16],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '305496']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ]);

        // ===== HEADER UTAMA (mulai baris ke-2) =====
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $sheet->setCellValue('A2', 'Desa');
        } else {
            $sheet->setCellValue('A2', 'Kecamatan');
        }
        $sheet->mergeCells('A2:A3'); // merge ke baris 3 karena baris 4 dihapus

        $col = 'B';
        foreach ($komoditas as $k) {
            $nama = $k->nama_komoditas;

            if ($nama == 'Sapi Potong' || $nama == 'Sapi Perah') {
                $sheet->setCellValue($col.'2', $nama);
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 1).'2'); // merge 2 kolom

                $sheet->setCellValue($col.'3', 'Jantan');
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Betina');

                $col = $this->nextCol($col, 2);
            }
            elseif ($nama == 'Kerbau' || $nama == 'Kuda') {
                $sheet->setCellValue($col.'2', $nama);
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 1).'2');

                $sheet->setCellValue($col.'3', 'Jantan');
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Betina');

                $col = $this->nextCol($col, 2);
            }
            else {
                $sheet->setCellValue($col.'2', $nama);
                $sheet->mergeCells($col.'2:'.$col.'3'); // merge baris 2-3
                $col = $this->nextCol($col, 1);
            }
        }

        // Update merge judul sesuai kolom terakhir
        $lastCol = $sheet->getHighestColumn();
        $sheet->mergeCells('A1:'.$lastCol.'1');

        // ===== ISI DATA =====
        $row = 4; // mulai dari baris 4 karena header hanya sampai baris 3
        foreach ($wilayah as $w) {
            if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
                $sheet->setCellValue('A'.$row, $w->nama_desa);
            } else {
                $sheet->setCellValue('A'.$row, $w->nama_wilayah);
            }
            $row++;
        }

        // ===== STYLING HEADER =====
        $headerRange = 'A2:' . $sheet->getHighestColumn() . '3';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '1F497D']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ]);

        // ===== LEBAR KOLOM =====
        $sheet->getColumnDimension('A')->setWidth(20);
        for ($c = 'B'; $c <= $sheet->getHighestColumn(); $c++) {
            $sheet->getColumnDimension($c)->setWidth(10);
        }

        // ===== FREEZE HEADER =====
        $sheet->freezePane('B4');

        // ===== ZEBRA STRIPE =====
        $lastColumn = $sheet->getHighestColumn();
        $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn) - 1;

        for ($i = 4; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $endColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex);
                $sheet->getStyle('A'.$i.':'.$endColumn.$i)
                    ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // ===== OUTPUT FILE =====
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_pemotongan.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

     private function nextCol($col, $n = 1) {
        for ($i = 0; $i < $n; $i++) {
            $col++;
        }
        return $col;
    }


    public function upload_excel() {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $file = $_FILES['file_excel']['tmp_name'];

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        // Load file Excel
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        // Ambil data master
        $komoditas = $this->Komoditas_model->getKomoditasIndexed(); // nama => id
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $wilayah_master = $this->Komoditas_model->getDesaIndexed(); // nama => id
        } else {
            $wilayah_master = $this->Komoditas_model->getKecamatanIndexed(); // nama => id
        }

        // Ambil header
        $headers = $sheet[2]; // nama komoditas
        $jkel    = $sheet[3]; 

        // Ambil data mulai baris ke-4, reindex array
        $sheet_data = array_slice($sheet, 3);

        foreach ($sheet_data as $row) {
            $nama_wilayah = trim($row['A']);
            if (empty($nama_wilayah)) continue;

            $id_wilayah = $wilayah_master[$nama_wilayah] ?? null;
            if (!$id_wilayah) continue;

            foreach ($headers as $col => $komod) {
                if ($col == 'A') continue;

                $id_komoditas = $komoditas[$komod] ?? null;
                $jenis_kelamin = null;
                $umur_value = null;

                // logika kolom khusus
                if (in_array($col, ['B','C'])) {
                    $id_komoditas = $komoditas['Sapi Potong'] ?? null;
                    $jenis_kelamin = $jkel[$col] ?? null;
                } elseif (in_array($col, ['D','E'])) {
                    $id_komoditas = $komoditas['Sapi Perah'] ?? null;
                    $jenis_kelamin = $jkel[$col] ?? null;
                } elseif (in_array($col, ['F','G'])) {
                    $id_komoditas = $komoditas['Kerbau'] ?? null;
                    $jenis_kelamin = $jkel[$col] ?? null;
                } elseif (in_array($col, ['H','I'])) {
                    $id_komoditas = $komoditas['Kuda'] ?? null;
                    $jenis_kelamin = $jkel[$col] ?? null;
                }

                if (!$id_komoditas) continue;

                // Logika Admin Kecamatan vs Admin Pusat
                if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
                    $kecamatan = $this->db->get_where('master_wilayah', [
                        'kode' => $this->session->userdata('kode')
                    ])->row();
                    $wilayah = $kecamatan->id_wilayah;
                    $kode    = $id_wilayah; // kode desa
                } else {
                    $wilayah = $id_wilayah;
                    $kode    = null;
                }

                // Ambil jumlah
                $jumlah = isset($row[$col]) ? (int) preg_replace('/[^\d]/', '', $row[$col]) : 0;
                if ($jumlah <= 0) continue;

                // Siapkan data untuk insert
                $data_insert = [
                    'bulan'         => $bulan,
                    'tahun'         => $tahun,
                    'id_komoditas'  => $id_komoditas,
                    'jumlah'        => $jumlah,
                    'id_wilayah'    => $wilayah,
                    'id_user'       => $this->session->userdata('id_user'),
                    'jenis_kelamin' => $jenis_kelamin,
                    'kode_desa'     => $kode,
                ];
                $this->Pemotongan_model->insert($data_insert);
            }
        }
        $this->db->query("CALL sp_rekap_populasi('".$bulan."', '".$tahun."')");
        redirect('pemotongan');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_pemotongan');
        if($ids) {
            $this->db->where_in('id_pemotongan', $ids);
            $this->db->delete('trx_pemotongan');
        }
        redirect('pemotongan');
    }
}
