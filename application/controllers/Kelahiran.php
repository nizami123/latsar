<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelahiran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Kelahiran_model','Komoditas_model']);
    }

    public function index() {
        $data['kelahiran'] = $this->Kelahiran_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getKecamatan();
        $data['komoditas'] = $this->Komoditas_model->getKomoditasKelahiran();
        $data['title'] = "Data Kelahiran";
        $data['filename'] = "data_kelahiran";
        $data['js'] = 'kelahiran.js';
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('kelahiran', $data);
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
        $this->Kelahiran_model->insert($data);
        redirect('kelahiran');
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

        $this->Kelahiran_model->update($post['id_kelahiran'], $data);
        redirect('kelahiran');
    }


    public function delete($id) {
        $this->Kelahiran_model->delete($id);
        redirect('kelahiran');
    }

    public function download_template() {
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $wilayah = $this->Komoditas_model->getDesa();
        } else {
            $wilayah = $this->Komoditas_model->getKecamatan();
        }
        $komoditas = $this->Komoditas_model->getKomoditasKelahiran();

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Kelahiran');

        // Header utama
        if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $sheet->setCellValue('A1', 'Desa');
        } else {
            $sheet->setCellValue('A1', 'Kecamatan');
        }
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
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);

                $sheet->setCellValue($col.'2', 'Betina');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 2).'2');
                $sheet->setCellValue($col.'3', 'Anak');
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Sapi Perah') {
                $sheet->setCellValue($col.'1', 'Sapi Perah');
                $sheet->mergeCells($col.'1:'.$this->nextCol($col, 2).'1');

                $sheet->setCellValue($col.'2', 'Betina');
                $sheet->mergeCells($col.'2:'.$this->nextCol($col, 2).'2');
                $sheet->setCellValue($col.'3', 'Anak');
                $sheet->setCellValue($this->nextCol($col, 1).'3', 'Muda');
                $sheet->setCellValue($this->nextCol($col, 2).'3', 'Dewasa');

                $col = $this->nextCol($col, 3);
            }
            elseif ($nama == 'Kerbau' || $nama == 'Kuda') {
                $sheet->setCellValue($col.'1', $nama);
                $sheet->mergeCells($col.'1:'.$this->nextCol($col, 1).'1');

                $sheet->setCellValue($col.'2', 'Jantan');
                $sheet->setCellValue($this->nextCol($col, 1).'2', 'Betina');
                $sheet->mergeCells($col.'3:'.$this->nextCol($col, 1).'3');

                $col = $this->nextCol($col, 2);
            }
            else {
                $sheet->setCellValue($col.'1', $nama);
                $sheet->mergeCells($col.'1:'.$col.'3');
                $col = $this->nextCol($col, 1);
            }
        }

        // Isi data kecamatan
        $row = 4;
        foreach ($wilayah as $w) {
            if($this->session->userdata('jabatan') == 'Admin Kecamatan') {
                $sheet->setCellValue('A'.$row, $w->nama_desa);
            } else {
                $sheet->setCellValue('A'.$row, $w->nama_wilayah);
            }
            $row++;
        }

        // Styling header
        $headerRange = 'A1:' . $sheet->getHighestColumn() . '3';
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

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(20);
        for ($c = 'B'; $c <= $sheet->getHighestColumn(); $c++) {
            $sheet->getColumnDimension($c)->setWidth(10);
        }

        // Freeze header
        $sheet->freezePane('B4');

       // Zebra style
        $lastColumn = $sheet->getHighestColumn(); // kolom terakhir
        $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastColumn) - 1; // index kolom terakhir sebenarnya

        for ($i = 4; $i < $row; $i++) {
            if ($i % 2 == 0) {
                // Konversi index kembali ke huruf kolom
                $endColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex - 1); 
                $sheet->getStyle('A'.$i.':'.$endColumn.$i)
                    ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // Output file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_kelahiran.xlsx"');
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

        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $komoditas = $this->Komoditas_model->getKomoditasIndexed(); // nama => id
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $wilayah = $this->Komoditas_model->getDesaIndexed();
        } else {
            $wilayah = $this->Komoditas_model->getKecamatanIndexed();   // nama => id
        }
        
        $headers = $sheet[1];
        $jkel = $sheet[2];
        $umur = $sheet[3];
        unset($sheet[1]);
        unset($sheet[2]);
        unset($sheet[3]);
        
        foreach ($sheet as $row) {

            $nama_wilayah = $row['A'];
            if (empty($nama_wilayah)) continue;

            $id_wilayah = $wilayah[$nama_wilayah] ?? null;
            if (!$id_wilayah) continue;

            foreach ($headers as $col => $komod) {

                if ($col == 'A') continue;
                $id_komoditas = $komoditas[$komod] ?? null;
                
                if ($col == 'B' || $col == 'C' || $col == 'D' || $col == 'E' || $col == 'F' || $col == 'G') {
                    $id_komoditas = $komoditas['Sapi Potong'] ?? null;
                    $jenis_kelamin = ($col <= 'D') ? 'Jantan' : 'Betina';
                    $umur_value    = $umur[$col];
                } elseif ($col == 'H' || $col == 'I' || $col == 'J') {
                    $id_komoditas = $komoditas['Sapi Perah'] ?? null;
                    $jenis_kelamin = 'Betina';
                    $umur_value    = $umur[$col];
                } elseif ($col == 'K' || $col == 'L') {
                    $id_komoditas = $komoditas['Kerbau'] ?? null;
                    $jenis_kelamin = $jkel[$col];
                    $umur_value    = null;
                } elseif ($col == 'M' || $col == 'N') {
                    $id_komoditas = $komoditas['Kuda'] ?? null;
                    $jenis_kelamin = $jkel[$col];
                    $umur_value    = null;
                } else {
                    $id_komoditas = $komoditas[$komod] ?? null;
                    $jenis_kelamin = null;
                    $umur_value    = null;
                }

                if (!$id_komoditas) continue;

                if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
                    $kecamatan = $this->db->get_where('master_wilayah', ['kode' => $this->session->userdata('kode')])->row();
                    $wilayah = $kecamatan->id_wilayah;
                    $kode = $id_wilayah;
                } else {
                    $wilayah = $id_wilayah;
                    $kode = NULL;
                }

                $jumlah = (int) preg_replace('/[^\d]/', '', $row[$col]);
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
                // Simpan ke database
                $this->Kelahiran_model->insert($data_insert);
            }
        }

        

        redirect('kelahiran');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_kelahiran');
        if($ids) {
            $this->db->where_in('id_kelahiran', $ids);
            $this->db->delete('trx_kelahiran');
        }
        redirect('kelahiran');
    }

}
