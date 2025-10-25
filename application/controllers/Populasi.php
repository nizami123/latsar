<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Populasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Populasi_model','Komoditas_model']);
    }

    public function index() {
        $data['populasi'] = $this->Populasi_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getKecamatan();
        $data['komoditas'] = $this->Komoditas_model->getKomoditasPopulasi();
        $data['title'] = "Data Populasi";
        $data['filename'] = "data_populasi";
        $data['js'] = 'populasi.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('populasi', $data);
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
        $this->Populasi_model->insert($data);
        redirect('populasi');
    }

    public function tes()
    {
        require_once APPPATH . 'third_party/PhpSpreadsheet/autoloader.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello dari CodeIgniter 3!');
        $sheet->setCellValue('B1', 'PhpSpreadsheet v1.23.0 (PHP 7.3)');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'contoh_export.xlsx';
        $writer->save(FCPATH . $filename);

        echo "File berhasil dibuat: <a href='" . base_url($filename) . "'>$filename</a>";
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

        $this->Populasi_model->update($post['id_populasi'], $data);
        redirect('populasi');
    }


    public function delete($id) {
        $this->Populasi_model->delete($id);
        redirect('populasi');
    }

    public function download_template() {
    // Mulai output buffering untuk mencegah error headers already sent
        ob_start();

        // Ambil data wilayah sesuai jabatan
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $wilayah = $this->Komoditas_model->getDesa();
        } else {
            $wilayah = $this->Komoditas_model->getKecamatan();
        }

        // Ambil daftar komoditas
        $komoditas = $this->Komoditas_model->getKomoditasPopulasi();

        // Load PHPExcel
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Populasi');

        // Header utama
        $sheet->setCellValue('A1', ($this->session->userdata('jabatan') == 'Admin Kecamatan') ? 'Desa' : 'Kecamatan');
        $sheet->mergeCells('A1:A3');

        $col = 'B';
        foreach ($komoditas as $k) {
            $nama = $k->nama_komoditas;

            if ($nama == 'Sapi Potong') {
                $sheet->setCellValue($col.'1', 'Sapi Potong');
                $sheet->mergeCells($col.'1:'.$this->nextCol($col, 5).'1');

                $sheet->setCellValue($col.'2', 'Jantan');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 2).'2');
                $sheet->setCellValue($col.'3', 'Anak');
                $sheet->setCellValue($this->nextCol($col,1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col,2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);

                $sheet->setCellValue($col.'2', 'Betina');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col,2).'2');
                $sheet->setCellValue($col.'3', 'Anak');
                $sheet->setCellValue($this->nextCol($col,1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col,2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Sapi Perah') {
                $sheet->setCellValue($col.'1', 'Sapi Perah');
                $sheet->mergeCells($col.'1:'.$this->nextCol($col,2).'1');

                $sheet->setCellValue($col.'2', 'Betina');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col,2).'2');
                $sheet->setCellValue($col.'3', 'Anak');
                $sheet->setCellValue($this->nextCol($col,1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col,2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Kerbau' || $nama == 'Kuda') {
                $sheet->setCellValue($col.'1', $nama);
                $sheet->mergeCells($col.'1:'.$this->nextCol($col,1).'1');

                $sheet->setCellValue($col.'2', 'Jantan');
                $sheet->setCellValue($this->nextCol($col,1).'2', 'Betina');
                $sheet->mergeCells($col.'3:'.$this->nextCol($col,1).'3');

                $col = $this->nextCol($col, 2);
            }
            else {
                $sheet->setCellValue($col.'1', $nama);
                $sheet->mergeCells($col.'1:'.$col.'3');
                $col = $this->nextCol($col,1);
            }
        }

        // Isi data wilayah
        $row = 4;
        foreach ($wilayah as $w) {
            $sheet->setCellValue('A'.$row, ($this->session->userdata('jabatan') == 'Admin Kecamatan') ? $w->nama_desa : $w->nama_wilayah);
            $row++;
        }

        // Styling header
        $headerRange = 'A1:'.$sheet->getHighestColumn().'3';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold'=>true,'color'=>['rgb'=>'FFFFFF'],'size'=>11],
            'fill' => ['type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>['rgb'=>'1F497D']],
            'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'wrap'=>true],
            'borders'=>['allborders'=>['style'=>PHPExcel_Style_Border::BORDER_THIN]]
        ]);

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(20);
        for ($c = 'B'; $c <= $sheet->getHighestColumn(); $c++) {
            $sheet->getColumnDimension($c)->setWidth(10);
        }

        // Freeze header
        $sheet->freezePane('B4');

        // Zebra style
        $lastColumn = $sheet->getHighestColumn();
        $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn)-1;
        for ($i=4; $i<$row; $i++) {
            if ($i%2==0) {
                $endColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex-1);
                $sheet->getStyle('A'.$i.':'.$endColumn.$i)
                    ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // Hapus semua output buffer sebelumnya supaya aman
        ob_end_clean();

        // Kirim header dan file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_populasi.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $objWriter->save('php://output');
        exit;
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
        $headers = $sheet[1]; // nama komoditas
        $jkel    = $sheet[2]; // jenis kelamin
        $umur    = $sheet[3]; // umur

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
                $this->Populasi_model->insert($data_insert);
            }
        }
        redirect('populasi');
    }


    public function delete_multiple()
    {
        $ids = $this->input->post('id_populasi');
        if($ids) {
            $this->db->where_in('id_populasi', $ids);
            $this->db->delete('trx_populasi');
        }
        redirect('populasi');
    }

}
