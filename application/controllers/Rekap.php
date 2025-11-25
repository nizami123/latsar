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
        // var_dump($raw_data); exit;

        foreach ($raw_data as $row) {
            $kecamatan     = $row['kecamatan'];
            $komoditas     = $row['nama_komoditas'];
            $jenis_kelamin = $row['jenis_kelamin'] ?? '';
            $umur          = $row['umur'] ?? '';
            $jumlah        = abs((int)$row['jumlah']); // pastikan integer
            $hitung        = (int)$row['hitung'];

            // List komoditas dan kecamatan
            if (!in_array($komoditas, $komoditasList)) $komoditasList[] = $komoditas;
            if (!in_array($kecamatan, $kecamatanList)) $kecamatanList[] = $kecamatan;

            // Status
            $pivot[$kecamatan]['status'] = $hitung ? 1 : 0;

            // --------- CASE 1: jenis_kelamin & umur NULL → langsung merge ke komoditas
            if ($jenis_kelamin === '' && $umur === '') {

                // Jika sebelumnya tipe array, sum harus di level paling luar
                if (isset($pivot[$kecamatan][$komoditas]) && is_array($pivot[$kecamatan][$komoditas])) {
                    // Jika ingin memaksa sum ke "total", bisa buat key khusus
                    $existingArray = $pivot[$kecamatan][$komoditas];
                    $pivot[$kecamatan][$komoditas] = (isset($existingArray['total']) ? $existingArray['total'] : 0) + $jumlah;
                } else {
                    // Jika belum ada atau integer
                    $pivot[$kecamatan][$komoditas] = ($pivot[$kecamatan][$komoditas] ?? 0) + $jumlah;
                }

                continue;
            }

            // --------- CASE 2: Data normal (bertier: komoditas → jenis_kelamin → umur)

            // Jika sebelumnya integer, ubah jadi array
            if (isset($pivot[$kecamatan][$komoditas]) && !is_array($pivot[$kecamatan][$komoditas])) {
                $pivot[$kecamatan][$komoditas] = [
                    'total' => $pivot[$kecamatan][$komoditas]   // pindahkan angka ke total
                ];
            }

            // Inisialisasi bertingkat
            if (!isset($pivot[$kecamatan][$komoditas])) {
                $pivot[$kecamatan][$komoditas] = [];
            }
            if (!isset($pivot[$kecamatan][$komoditas][$jenis_kelamin])) {
                $pivot[$kecamatan][$komoditas][$jenis_kelamin] = [];
            }

            // Simpan jumlah
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

    private function getDataPemotongan($bulan = null, $tahun = null)
    {
        // Jika bulan/tahun tidak diberikan, ambil dari data trx_pemotongan terbaru
        if (is_null($bulan) || is_null($tahun)) {
            $this->db->select('tahun, bulan');
            $this->db->from('trx_pemotongan'); // ← ganti kalau tabel berbeda
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

            if ($bulan > 12) {
                $bulan = 1;
                $tahun++;
            }
        }

        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // Ambil data pivot pemotongan
        $raw_data = $this->Pemotongan_model->get_pivot_data($bulan, $tahun);

        $pivot = [];
        $komoditasList = [];
        $kecamatanList = [];

        foreach ($raw_data as $row) {
            $kecamatan     = $row['kecamatan'];
            $komoditas     = $row['nama_komoditas'];
            $jenis_kelamin = $row['jenis_kelamin'] ?? '';
            $umur          = $row['umur'] ?? '';
            $jumlah        = abs((int)$row['jumlah']);
            $hitung        = (int)$row['hitung'];

            if (!in_array($komoditas, $komoditasList)) $komoditasList[] = $komoditas;
            if (!in_array($kecamatan, $kecamatanList)) $kecamatanList[] = $kecamatan;

            $pivot[$kecamatan]['status'] = $hitung ? 1 : 0;

            if ($jenis_kelamin === '' && $umur === '') {
                if (isset($pivot[$kecamatan][$komoditas]) && is_array($pivot[$kecamatan][$komoditas])) {
                    $existingArray = $pivot[$kecamatan][$komoditas];
                    $pivot[$kecamatan][$komoditas] =
                        (isset($existingArray['total']) ? $existingArray['total'] : 0) + $jumlah;
                } else {
                    $pivot[$kecamatan][$komoditas] =
                        ($pivot[$kecamatan][$komoditas] ?? 0) + $jumlah;
                }
                continue;
            }

            if (isset($pivot[$kecamatan][$komoditas]) && !is_array($pivot[$kecamatan][$komoditas])) {
                $pivot[$kecamatan][$komoditas] = [
                    'total' => $pivot[$kecamatan][$komoditas]
                ];
            }

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

    public function export_pemotongan()
{
    require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

    $bulan = $this->input->get('bulan') ?: date('m');
    $tahun = $this->input->get('tahun') ?: date('Y');

    $data = $this->getDataPemotongan($bulan, $tahun);
    $PemotonganPivot = $data['pivot'];
    $PemotonganKomoditas = $data['komoditas'];

    // Komoditas yang memiliki Jantan/Betina
    $khususJk = ['Kerbau','Kuda','Sapi Potong','Sapi Perah'];
    $paksaUmur = [];

    $komoditasBertingkat = [];

    foreach ($PemotonganKomoditas as $kom) {
        if (in_array($kom, $paksaUmur) || in_array($kom, $khususJk)) {
            $komoditasBertingkat[] = $kom;
        }
    }

    $excel = new PHPExcel();
    $sheet = $excel->getActiveSheet();
    $bulanNama = nama_bulan((int)$bulan);
    $sheet->setTitle($bulanNama . ' ' . $tahun);

    // ================= HEADER =================
    $rowNum = 1;
    $col = 0;

    // Kolom Kecamatan
    $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Kecamatan');
    $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 1);
    $sheet->getColumnDimensionByColumn($col)->setWidth(20);
    $col++;

    // Header komoditas
    foreach ($PemotonganKomoditas as $kom) {

        if (in_array($kom, $komoditasBertingkat)) {
            // Jantan + Betina
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 1, $rowNum);
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom);
            $col += 2;

            // Total
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 1);
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom . ' Total');
            $col++;

        } else {
            // Komoditas tidak bertingkat
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col, $rowNum + 1);
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $kom);
            $col++;
        }
    }

    // Subheader Jantan / Betina
    $rowNum++;
    $col = 1;

    foreach ($PemotonganKomoditas as $kom) {
        if (in_array($kom, $komoditasBertingkat)) {
            $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Jantan');
            $col++;

            $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Betina');
            $col++;

            // skip total column
            $col++;
        }
    }

    // ================= STYLING HEADER =================
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

    // Freeze Header
    $sheet->freezePane('B'.($rowNum+1));

    // ================= BODY =================
    $rowNum++;
    $totalPerKomoditas = [];

    foreach ($PemotonganPivot as $kec => $row) {

        $col = 0;
        $sheet->setCellValueByColumnAndRow($col, $rowNum, $kec);
        $col++;

        foreach ($PemotonganKomoditas as $kom) {

            $totalKomoditasRow = 0;

            if (in_array($kom, $komoditasBertingkat)) {

                foreach (['Jantan','Betina'] as $jk) {

                    $val = 0;

                    if (isset($row[$kom][$jk])) {

                        // Data lama: array kosong umur → ambil nilai pertama
                        if (is_array($row[$kom][$jk])) {
                            $first = reset($row[$kom][$jk]); 
                            $val = (int)$first;
                        } else {
                            $val = (int)$row[$kom][$jk];
                        }
                    }

                    $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                    $totalKomoditasRow += $val;

                    $totalPerKomoditas[$kom][$jk] =
                        ($totalPerKomoditas[$kom][$jk] ?? 0) + $val;

                    $col++;
                }

                // Kolom total
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $totalKomoditasRow);
                $col++;

            } else {
                // Komoditas biasa
                $val = isset($row[$kom]) ? (int)$row[$kom] : 0;
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);

                $totalPerKomoditas[$kom] = ($totalPerKomoditas[$kom] ?? 0) + $val;
                $col++;
            }
        }

        // Warna selang-seling
        if ($rowNum % 2 == 0) {
            $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")
                ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F2F2F2');
        }

        $rowNum++;
    }

    // ================= TOTAL ROW =================
    $col = 0;
    $sheet->setCellValueByColumnAndRow($col, $rowNum, 'Total');
    $col++;

    foreach ($PemotonganKomoditas as $kom) {

        $totalAll = 0;

        if (in_array($kom, $komoditasBertingkat)) {

            foreach (['Jantan','Betina'] as $jk) {
                $val = (int)($totalPerKomoditas[$kom][$jk] ?? 0);
                $sheet->setCellValueByColumnAndRow($col, $rowNum, $val);
                $totalAll += $val;
                $col++;
            }

            $sheet->setCellValueByColumnAndRow($col, $rowNum, $totalAll);
            $col++;

        } else {
            $sheet->setCellValueByColumnAndRow($col, $rowNum, (int)($totalPerKomoditas[$kom] ?? 0));
            $col++;
        }
    }

    $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => ['type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color'=>['rgb'=>'D9D9D9']]
    ]);

    // ================= DOWNLOAD =================
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Pemotongan '.$bulanNama.' '.$tahun.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}



    public function get_data_pemotongan()
    {
        $bulan = $this->input->get('bulan') ?: date('n');
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->getDataPemotongan($bulan, $tahun);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'bulan'       => $data['bulan'],
                'tahun'       => $data['tahun'],
                'nama_bulan'  => nama_bulan($data['bulan']),
                'komoditas'   => $data['komoditas'],
                'pemotongan'  => $data['pivot']  // ← ganti dari populasi
            ]));
    }


}

