<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasar_hewan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pasar_hewan_model','Komoditas_model']);
    }

    public function index()
    {
        $data['pasar'] = $this->Pasar_hewan_model->getAll();
        $data['wilayah']  = $this->Komoditas_model->getKecamatan();
        $data['title'] = "Data Pasar Hewan";
        $data['filename'] = "pasar_hewan";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('pasar_hewan', $data);
        $this->load->view('templates/footer', $data);
    }

    public function download_template() {

        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $kec = $this->db->get_where('master_wilayah', [
                'kode' => $this->session->userdata('kode')
            ])->row();
            $wilayah = $this->Komoditas_model->getDesa();
            $header  = 'DATA PASAR HEWAN KECAMATAN ' . strtoupper($kec->nama_wilayah);
            $label   = 'Desa';
        } else {
            $wilayah = $this->Komoditas_model->getKecamatan();
            $header  = 'DATA PASAR HEWAN KABUPATEN LAMONGAN';
            $label   = 'Kecamatan';
        }

        $komoditas = ['Kuda','Sapi','Kerbau','Kambing','Domba','Babi'];

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Pasar Hewan');

        /* ================= JUDUL UTAMA ================= */
        $sheet->setCellValue('A1', $header);
        $sheet->mergeCells('A1:N1');
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 16],
            'fill' => [
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
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

        /* ================= HEADER ================= */
        $sheet->setCellValue('A2', 'No');
        $sheet->mergeCells('A2:A3');

        $sheet->setCellValue('B2', $label);
        $sheet->mergeCells('B2:B3');

        $col = 'C';
        foreach ($komoditas as $k) {
            $sheet->setCellValue($col.'2', $k);
            $sheet->mergeCells($col.'2:'.$this->nextCol($col,1).'2');

            $sheet->setCellValue($col.'3', 'Masuk');
            $sheet->setCellValue($this->nextCol($col,1).'3', 'Laku');

            $col = $this->nextCol($col,2);
        }

        // merge judul sesuai kolom terakhir
        $lastCol = $sheet->getHighestColumn();
        $sheet->mergeCells('A1:'.$lastCol.'1');

        /* ================= ISI DATA ================= */
        $row = 4;
        $no  = 1;
        foreach ($wilayah as $w) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue(
                'B'.$row,
                ($this->session->userdata('jabatan') == 'Admin Kecamatan')
                    ? $w->nama_desa
                    : $w->nama_wilayah
            );
            $row++;
        }

        /* ================= STYLE HEADER ================= */
        $headerRange = 'A2:' . $lastCol . '3';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
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

        /* ================= LEBAR KOLOM ================= */
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        for ($c = 'C'; $c <= $lastCol; $c++) {
            $sheet->getColumnDimension($c)->setWidth(10);
        }

        /* ================= FREEZE ================= */
        $sheet->freezePane('C4');

        /* ================= ZEBRA ================= */
        $lastColumnIndex = PHPExcel_Cell::columnIndexFromString($lastCol) - 1;
        for ($i = 4; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $endCol = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex);
                $sheet->getStyle('A'.$i.':'.$endCol.$i)
                    ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        /* ================= OUTPUT ================= */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_pasar_hewan.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
    }


    public function upload_excel()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        require APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['file_excel']['tmp_name']);
        $sheet = $objPHPExcel->getActiveSheet();

        $komoditas = $this->Komoditas_model->getKomoditasIndexed(); // nama => id
        if ($this->session->userdata('jabatan') == 'Admin Kecamatan') {
            $wilayah_master = $this->Komoditas_model->getDesaIndexed(); // nama => id
        } else {
            $wilayah_master = $this->Komoditas_model->getKecamatanIndexed(); // nama => id
        }

        $highestRow = $sheet->getHighestRow();

        $map = [
            'Kuda'     => ['C','D'],
            'Sapi'     => ['E','F'],
            'Kerbau'   => ['G','H'],
            'Kambing'  => ['I','J'],
            'Domba'    => ['K','L'],
            'Babi'     => ['M','N']
        ];

        for ($row = 4; $row <= $highestRow; $row++) {

            $nama_desa = trim($sheet->getCell('B'.$row)->getValue());
            $id_wilayah = $wilayah_master[$nama_desa] ?? null;
            if (!$id_wilayah) continue;


            foreach ($map as $komoditas => $cols) {

                $mk = $this->db
                    ->where('nama_komoditas', $komoditas)
                    ->not_like('jenis', 'komoditas')
                    ->get('master_komoditas')
                    ->row();

                if (!$mk) continue;

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
                // MASUK
                $masuk = (int)$sheet->getCell($cols[0].$row)->getValue();

                $this->db->insert('trx_pasar_hewan', [
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
                    'id_komoditas' => $mk->id_komoditas,
                    'jumlah'       => $masuk,
                    'status_pasar' => 'Masuk',
                    'kode_desa'    => $kode,
                    'id_wilayah'   => $wilayah,
                    'id_user'      => $this->session->userdata('id_user')
                ]);

                // LAKU
                $laku = (int)$sheet->getCell($cols[1].$row)->getValue();

                $this->db->insert('trx_pasar_hewan', [
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
                    'id_komoditas' => $mk->id_komoditas,
                    'jumlah'       => $laku,
                    'status_pasar' => 'Laku',
                    'kode_desa'    => $kode,
                    'id_wilayah'   => $wilayah,
                    'id_user'      => $this->session->userdata('id_user')
                ]);
            }
        }

        $this->session->set_flashdata('success','Data Pasar Hewan berhasil diupload');
        redirect('pasar_hewan');
    }


    private function nextCol($col, $n = 1) {
        for ($i=0;$i<$n;$i++) $col++;
        return $col;
    }

    public function rekap()
    {
        $row = $this->db
            ->select('bulan, tahun')
            ->from('trx_pasar_hewan')
            ->order_by('tanggal_input', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        $bulan = $row->bulan ?? date('m');
        $tahun = $row->tahun ?? date('Y');
        $kecamatan = $this->input->get('kecamatan'); // boleh kosong

        $result = $this->Pasar_hewan_model->get_pivot_data($bulan, $tahun, $kecamatan);
        $data = [
            'kecamatan' => $this->Komoditas_model->getKecamatan(),
            'title'     => "Rekap Pasar Hewan",
            'filename'  => "data_rekap_pasar_hewan",
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'komoditas' => $result['komoditas'],
            'pivot'     => $result['data']
        ];
       
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('pasar_hewan_rekap', $data);
        $this->load->view('templates/footer',$data);
    }

    public function rekap_data()
    {
        $bulan     = $this->input->get('bulan');
        $tahun     = $this->input->get('tahun');
        $kecamatan = $this->input->get('kecamatan'); // boleh kosong

        $result = $this->Pasar_hewan_model->get_pivot_data($bulan, $tahun, $kecamatan);
        $data = [
            'komoditas' => $result['komoditas'],
            'pivot'     => $result['data']
        ];
       
        echo json_encode($data);
    }
}
