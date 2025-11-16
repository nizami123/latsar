<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Harga_model');
        $this->load->model('Populasi_model');
        $this->load->model('Pemotongan_model');
        $this->load->model('Komoditas_model');
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
    }

    public function index()
    {
        $populasi = $this->getDataPopulasi();
        $pemotongan = $this->getDataPemotongan();
        $data['populasiPivot'] = $populasi['pivot'];
        $data['populasiKomoditas'] = $populasi['komoditas'];
        $data['populasiBulan'] = $populasi['bulan'];
        $data['populasiTahun'] = $populasi['tahun'];

        $data['pemotonganPivot'] = $pemotongan['pivot'];
        $data['pemotonganKomoditas'] = $pemotongan['komoditas'];
        $data['pemotonganBulan'] = $pemotongan['bulan'];
        $data['pemotonganTahun'] = $pemotongan['tahun'];
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('rekap', $data);
    }

    private function getDataPopulasi($bulan = null, $tahun = null)
    {
        // Jika bulan/tahun tidak diberikan, ambil dari data trx_populasi terbaru
        if (is_null($bulan) || is_null($tahun)) {
            $this->db->select('tahun, bulan');
            $this->db->from('trx_populasi');
            $this->db->order_by('tahun', 'DESC');
            $this->db->order_by('bulan', 'DESC');
            $this->db->limit(1);
            $latest = $this->db->get()->row_array();

            if (!$latest) {
                return [
                    'pivot' => [],
                    'komoditas' => [],
                    'bulan' => null,
                    'tahun' => null
                ];
            }

            $bulan = (int)$latest['bulan'];
            $tahun = (int)$latest['tahun'];

            // Jika bulan > 12, reset ke 1 dan tambah tahun
            if ($bulan > 12) {
                $bulan = 1;
                $tahun++;
            }
        }

        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // Ambil data pivot dari model
        $raw_data = $this->Populasi_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $kecamatanList = [];

        foreach ($raw_data as $row) {
            $kecamatan     = $row['kecamatan'];
            $komoditas     = $row['nama_komoditas'];
            $jenis_kelamin = $row['jenis_kelamin'];
            $umur          = $row['umur'];
            $jumlah        = abs($row['jumlah']);
            $hitung        = $row['hitung'];

            // Tambahkan ke list komoditas & kecamatan
            if (!in_array($komoditas, $komoditasList)) $komoditasList[] = $komoditas;
            if (!in_array($kecamatan, $kecamatanList)) $kecamatanList[] = $kecamatan;

            $pivot[$kecamatan]['status'] = $hitung ? 1 : 0;
            // Jika jenis_kelamin & umur kosong → langsung merge
            if (empty($jenis_kelamin) && empty($umur)) {
                $pivot[$kecamatan][$komoditas] = ($pivot[$kecamatan][$komoditas] ?? 0) + $jumlah;
                continue;
            }

            // Inisialisasi array bertingkat jika belum ada
            if (!isset($pivot[$kecamatan][$komoditas])) {
                $pivot[$kecamatan][$komoditas] = [];
            }
            if (!isset($pivot[$kecamatan][$komoditas][$jenis_kelamin])) {
                $pivot[$kecamatan][$komoditas][$jenis_kelamin] = [];
            }

            $pivot[$kecamatan][$komoditas][$jenis_kelamin][$umur] = $jumlah;
        }
        ksort($pivot);
        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }


    public function get_data_populasi()
    {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->getDataPopulasi($bulan, $tahun);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'bulan'      => $data['bulan'],
                'tahun'      => $data['tahun'],
                'nama_bulan' => nama_bulan($data['bulan']),
                'komoditas'  => $data['komoditas'],
                'populasi'   => $data['pivot']
            ]));
    }

    public function export() {
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->getDataPopulasi($bulan, $tahun);
        $populasiPivot = $data['pivot'];
        $populasiKomoditas = $data['komoditas'];

        $umurList = ['Anak', 'Muda', 'Dewasa'];
        $khususJk = ['Kerbau', 'Kuda'];
        $paksaUmur = ['Sapi Potong', 'Sapi Perah'];
        $komoditasBertingkat = [];
        $komoditasAdaUmur = [];

        foreach ($populasiKomoditas as $kom) {
            if (in_array($kom, $paksaUmur)) {
                $komoditasBertingkat[] = $kom;
                $komoditasAdaUmur[$kom] = true;
            } elseif (in_array($kom, $khususJk)) {
                $komoditasBertingkat[] = $kom;
                $komoditasAdaUmur[$kom] = false;
            }
        }

        $excel = new PHPExcel();
        $sheet = $excel->getActiveSheet();
        $bulanNama = nama_bulan((int)$bulan);
        $sheet->setTitle($bulanNama . ' ' . $tahun);

        // ===== HEADER =====
        $rowNum = 1;
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Kecamatan');
        $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 2);
        $sheet->getStyleByColumnAndRow($col, $rowNum)
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getColumnDimensionByColumn($col)->setWidth(20);
        $col++;

        // Baris 1 Header Komoditas
        foreach ($populasiKomoditas as $kom) {
            if (in_array($kom, $komoditasBertingkat)) {
                if (!empty($komoditasAdaUmur[$kom])) {
                    $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 5, $rowNum);
                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom);
                    $col += 6;
                } else {
                    $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 1, $rowNum);
                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom);
                    $col += 2;
                }
                // Tambahkan kolom total (1 kolom tambahan)
                $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 2);
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom . ' Total');
                $col++;
            } else {
                $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 2);
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom);
                $col++;
            }
        }

        // Sub-header: Jantan/Betina & umur
        $rowNum++;
        $col = 1;
        foreach ($populasiKomoditas as $kom) {
            if (in_array($kom, $komoditasBertingkat)) {
                if (!empty($komoditasAdaUmur[$kom])) {
                    foreach (['Jantan','Betina'] as $jk) {
                        $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 2, $rowNum);
                        $sheet->setCellValueByColumnAndRow($col, $rowNum, $jk);
                        $col += 3;
                    }
                    // Skip kolom total (tidak ada subheader)
                    $col++;
                } else {
                    foreach (['Jantan','Betina'] as $jk) {
                        $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 1);
                        $sheet->setCellValueByColumnAndRow($col, $rowNum, $jk);
                        $col++;
                    }
                    // Skip kolom total
                    $col++;
                }
            }
        }

        // Sub-sub-header: umur
        $rowNum++;
        $col = 1;
        foreach ($populasiKomoditas as $kom) {
            if (in_array($kom, $komoditasBertingkat) && !empty($komoditasAdaUmur[$kom])) {
                foreach (['Jantan','Betina'] as $jk) {
                    foreach ($umurList as $umur) {
                        $sheet->setCellValueByColumnAndRow($col, $rowNum, $umur);
                        $col++;
                    }
                }
                // Skip kolom total
                $col++;
            }
        }

        // ===== STYLING HEADER =====
        $lastCol = $sheet->getHighestColumn();
        $headerRange = "A1:{$lastCol}{$rowNum}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '1F497D']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ],
            'borders' => [
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]
            ]
        ]);
        $sheet->freezePane('B' . ($rowNum + 1));

        // ===== BODY =====
        $rowNum++;
        $totalPerKomoditas = [];

        foreach ($populasiPivot as $kec => $row) {
            $col = 0;
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $kec);
            $col++;

            foreach ($populasiKomoditas as $kom) {
                $totalKomoditasRow = 0;
                if (in_array($kom, $komoditasBertingkat)) {
                    if (!empty($komoditasAdaUmur[$kom])) {
                        foreach (['Jantan','Betina'] as $jk) {
                            foreach ($umurList as $umur) {
                                $val = isset($row[$kom][$jk][$umur]) ? (int)$row[$kom][$jk][$umur] : 0;
                                $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                                $totalKomoditasRow += $val;
                                $totalPerKomoditas[$kom][$jk][$umur] = ($totalPerKomoditas[$kom][$jk][$umur] ?? 0) + $val;
                                $col++;
                            }
                        }
                    } else {
                        foreach (['Jantan','Betina'] as $jk) {
                            $val = isset($row[$kom][$jk]) ? (int)$row[$kom][$jk] : 0;
                            $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                            $totalKomoditasRow += $val;
                            $totalPerKomoditas[$kom][$jk] = ($totalPerKomoditas[$kom][$jk] ?? 0) + $val;
                            $col++;
                        }
                    }
                    // Kolom total untuk komoditas ini
                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $totalKomoditasRow);
                    $col++;
                } else {
                    $val = isset($row[$kom]) ? (int)$row[$kom] : 0;
                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                    $totalPerKomoditas[$kom] = ($totalPerKomoditas[$kom] ?? 0) + $val;
                    $col++;
                }
            }

            if ($rowNum % 2 == 0) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
            $rowNum++;
        }

        // ===== TOTAL PER KOMODITAS =====
        $col = 0;
        $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Total');
        $col++;
        foreach ($populasiKomoditas as $kom) {
            $totalKomoditasAll = 0;
            if (in_array($kom, $komoditasBertingkat)) {
                if (!empty($komoditasAdaUmur[$kom])) {
                    foreach (['Jantan','Betina'] as $jk) {
                        foreach ($umurList as $umur) {
                            $val = $totalPerKomoditas[$kom][$jk][$umur] ?? 0;
                            $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                            $totalKomoditasAll += $val;
                            $col++;
                        }
                    }
                } else {
                    foreach (['Jantan','Betina'] as $jk) {
                        $val = $totalPerKomoditas[$kom][$jk] ?? 0;
                        $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                        $totalKomoditasAll += $val;
                        $col++;
                    }
                }
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $totalKomoditasAll);
                $col++;
            } else {
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $totalPerKomoditas[$kom] ?? 0);
                $col++;
            }
        }
        $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>['rgb'=>'D9D9D9']]
        ]);

        // ===== DOWNLOAD =====
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Populasi '.$bulanNama.' '.$tahun.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    private function getDataPopulasiByMonthYear($bulan, $tahun)
    {
        $raw_data = $this->Populasi_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $kecamatanList = [];

        foreach ($raw_data as $row) {
            $kecamatan     = $row['kecamatan'];
            $komoditas     = $row['nama_komoditas'];
            $jenis_kelamin = $row['jenis_kelamin'];
            $umur          = $row['umur'];
            $jumlah        = $row['jumlah'];

            // Tambahkan ke list komoditas & kecamatan
            if (!in_array($komoditas, $komoditasList)) $komoditasList[] = $komoditas;
            if (!in_array($kecamatan, $kecamatanList)) $kecamatanList[] = $kecamatan;

            // Jika jenis_kelamin & umur kosong → langsung merge
            if (empty($jenis_kelamin) && empty($umur)) {
                $pivot[$kecamatan][$komoditas] = ($pivot[$kecamatan][$komoditas] ?? 0) + $jumlah;
                continue;
            }

            // Inisialisasi array bertingkat jika belum ada
            if (!isset($pivot[$kecamatan][$komoditas])) {
                $pivot[$kecamatan][$komoditas] = [];
            }
            if (!isset($pivot[$kecamatan][$komoditas][$jenis_kelamin])) {
                $pivot[$kecamatan][$komoditas][$jenis_kelamin] = [];
            }

            $pivot[$kecamatan][$komoditas][$jenis_kelamin][$umur] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }



     private function getDataPemotongan() {
        // Ambil bulan dan tahun terbaru dari tabel trx_populasi
        $this->db->select('tahun, bulan');
        $this->db->from('trx_pemotongan');
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('bulan', 'DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row_array();

        if (!$latest) {
            // Kalau data kosong, kembalikan array kosong
            return [
                'pivot' => [],
                'komoditas' => [],
                'bulan' => null,
                'tahun' => null
            ];
        }

        $bulan = $latest['bulan'];
        $tahun = $latest['tahun'];
        // Ambil data populasi untuk bulan dan tahun tersebut
        $raw_data = $this->Pemotongan_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];

        foreach ($raw_data as $row) {
            $wilayah = $row['wilayah'];
            $komoditas = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            $pivot[$wilayah][$komoditas] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    public function get_data_pemotongan(){
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Ambil data pivot dari fungsi private
        $data = $this->getDataPemotonganByMonthYear($bulan, $tahun);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'bulan'      => $data['bulan'],
                'tahun'      => $data['tahun'],
                'nama_bulan' => nama_bulan($data['bulan']),
                'komoditas'  => $data['komoditas'],  // array nama komoditas
                'pemotongan'   => $data['pivot']       // array pivot [wilayah][komoditas] => jumlah
        ]));
    }

    private function getDataPemotonganByMonthYear($bulan, $tahun)
    {
        $raw_data = $this->Pemotongan_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $wilayahList = [];

        // Pertama: kumpulkan semua komoditas & wilayah
        foreach ($raw_data as $row) {
            $wilayah = $row['wilayah'];
            $komoditas = $row['nama_komoditas'];

            if (!in_array($komoditas, $komoditasList)) {
                $komoditasList[] = $komoditas;
            }

            if (!in_array($wilayah, $wilayahList)) {
                $wilayahList[] = $wilayah;
            }
        }

        // Inisialisasi semua pivot dengan 0
        foreach ($wilayahList as $wil) {
            foreach ($komoditasList as $kom) {
                $pivot[$wil][$kom] = 0;
            }
        }

        // Masukkan data yang ada
        foreach ($raw_data as $row) {
            $wil = $row['wilayah'];
            $kom = $row['nama_komoditas'];
            $jumlah = $row['jumlah'];
            $pivot[$wil][$kom] = $jumlah;
        }

        return [
            'pivot' => $pivot,
            'komoditas' => $komoditasList,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

}
