<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kematian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Kematian_model','Komoditas_model']);
    }

    public function index() {
        $data['kematian'] = $this->Kematian_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getKecamatan();
        $data['komoditas'] = $this->Komoditas_model->getKomoditasKematian();
        $data['title'] = "Data Kematian";
        $data['filename'] = "data_kematian";
        $data['js'] = 'kematian.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('kematian', $data);
        $this->load->view('templates/footer', $data);
    }

    public function get_desa_by_kecamatan($kode)
    {
        $this->db->where('kode', $kode);
        $desa = $this->db->get('master_desa')->result();

        echo json_encode($desa);
    }

    public function getDesaByKecamatan()
    {
        $kode = $this->input->post('kode');
        $desa = $this->db->get_where('master_desa', ['kode' => $kode])->result();
        echo json_encode($desa);
    }


    public function save() {
        $post = $this->input->post();
        $this->db->where('kode', $post['id_wilayah']);
        $wilayah = $this->db->get('master_wilayah')->row();
        $data = [
            'bulan' => $post['bulan'],
            'tahun' => $post['tahun'],
            'id_wilayah' => $wilayah->id_wilayah, 
            'id_komoditas' => $post['id_komoditas'],
            'jumlah' => $post['jumlah'],
            'kode_desa' => $post['kode_desa'],
            'id_user' => $this->session->userdata('id_user')
        ];
        $this->Kematian_model->insert($data);
        redirect('kematian');
    }

    public function update() {
        $post = $this->input->post();

        // Ambil id_wilayah dari master_wilayah berdasarkan kode (bukan id langsung)
        $this->db->where('kode', $post['id_wilayah']);
        $wilayah = $this->db->get('master_wilayah')->row();
        
        $data = [
            'bulan'        => $post['bulan'],
            'tahun'        => $post['tahun'],
            'id_wilayah'   => $wilayah->id_wilayah,
            'id_komoditas' => $post['id_komoditas'],
            'jumlah'       => $post['jumlah'],
            'kode_desa'    => $post['kode_desa'],
            'id_user'      => $this->session->userdata('id_user')
        ];

        $this->Kematian_model->update($post['id_kematian'], $data);
        redirect('kematian');
    }


    public function delete($id) {
        $this->Kematian_model->delete($id);
        redirect('kematian');
    }

    public function download_template() {
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $kec = $this->db->get_where('master_wilayah', [
                'kode' => $this->session->userdata('kode')
            ])->row();
            $wilayah = $this->Komoditas_model->getDesa();
            $header = 'DATA KEMATIAN KECAMATAN ' . strtoupper($kec->nama_wilayah);
        } else {
            $wilayah = $this->Komoditas_model->getKecamatan();
            $header = 'DATA KEMATIAN KABUPATEN LAMONGAN';
        }
        $komoditas = $this->Komoditas_model->getKomoditasKematian();

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Kematian');

        // ===== JUDUL UTAMA =====
        $sheet->setCellValue('A1', $header);
        // Sementara merge ke kolom lebar, nanti disesuaikan di bawah
        $sheet->mergeCells('A1:Z1');
        $sheet->getRowDimension('1')->setRowHeight(30);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 16],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '305496'] // Biru tua elegan
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
        $sheet->mergeCells('A2:A4');

        $col = 'B';
        foreach ($komoditas as $k) {
            $nama = $k->nama_komoditas;

            if ($nama == 'Sapi Potong') {
                $sheet->setCellValue($col.'2', 'Sapi Potong');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 5).'2');

                $sheet->setCellValue($col.'3', 'Jantan');
                $sheet->mergeCells($col.'3:'.$this->nextCol($col, 2).'3');
                $sheet->setCellValue($col.'4', 'Anak');
                $sheet->setCellValue($this->nextCol($col, 1).'4', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'4', 'Dewasa');

                $col = $this->nextCol($col, 3);

                $sheet->setCellValue($col.'3', 'Betina');
                $sheet->mergeCells($col.'3:'.$this->nextCol($col, 2).'3');
                $sheet->setCellValue($col.'4', 'Anak');
                $sheet->setCellValue($this->nextCol($col, 1).'4', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'4', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Sapi Perah') {
                $sheet->setCellValue($col.'2', 'Sapi Perah');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 2).'2');

                $sheet->setCellValue($col.'3', 'Betina');
                $sheet->mergeCells($col.'3:'.$this->nextCol($col, 2).'3');
                $sheet->setCellValue($col.'4', 'Anak');
                $sheet->setCellValue($this->nextCol($col, 1).'4', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'4', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Kerbau' || $nama == 'Kuda') {
                $sheet->setCellValue($col.'2', $nama);
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 1).'2');

                $sheet->setCellValue($col.'3', 'Jantan');
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Betina');
                $sheet->mergeCells($col.'4:'.$this->nextCol($col, 1).'4');

                $col = $this->nextCol($col, 2);
            }
            else {
                $sheet->setCellValue($col.'2', $nama);
                $sheet->mergeCells($col.'2:'.$col.'4');
                $col = $this->nextCol($col, 1);
            }
        }

        // Update merge judul sesuai kolom terakhir
        $lastCol = $sheet->getHighestColumn();
        $sheet->mergeCells('A1:'.$lastCol.'1');

        // ===== ISI DATA =====
        $row = 5;
        foreach ($wilayah as $w) {
            if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
                $sheet->setCellValue('A'.$row, $w->nama_desa);
            } else {
                $sheet->setCellValue('A'.$row, $w->nama_wilayah);
            }
            $row++;
        }

        // ===== STYLING HEADER =====
        $headerRange = 'A2:' . $sheet->getHighestColumn() . '4';
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
        $sheet->freezePane('B5');

        // ===== ZEBRA STRIPE =====
        $lastColumn = $sheet->getHighestColumn();
        $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn) - 1;

        for ($i = 5; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $endColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex - 1);
                $sheet->getStyle('A'.$i.':'.$endColumn.$i)
                    ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // ===== OUTPUT FILE =====
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_kematian.xlsx"');
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
        $jkel    = $sheet[3]; // jenis kelamin
        $umur    = $sheet[4]; // umur

        // Ambil data mulai baris ke-4, reindex array
        $sheet_data = array_slice($sheet, 4);

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
                if (in_array($col, ['B','C','D','E','F','G'])) {
                    $id_komoditas = $komoditas['Sapi Potong'] ?? null;
                    $jenis_kelamin = ($col <= 'D') ? 'Jantan' : 'Betina';
                    $umur_value    = $umur[$col] ?? null;
                } elseif (in_array($col, ['H','I','J'])) {
                    $id_komoditas = $komoditas['Sapi Perah'] ?? null;
                    $jenis_kelamin = 'Betina';
                    $umur_value    = $umur[$col] ?? null;
                } elseif (in_array($col, ['K','L'])) {
                    $id_komoditas = $komoditas['Kerbau'] ?? null;
                    $jenis_kelamin = $jkel[$col] ?? null;
                } elseif (in_array($col, ['M','N'])) {
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
                    'umur'          => $umur_value,
                ];
                $this->Kematian_model->insert($data_insert);
            }
        }
        $this->db->query("CALL sp_rekap_populasi('".$bulan."', '".$tahun."')");
        redirect('kematian');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_kematian');
        if($ids) {
            $this->db->where_in('id_kematian', $ids);
            $this->db->delete('trx_kematian');
        }
        redirect('kematian');
    }

}
