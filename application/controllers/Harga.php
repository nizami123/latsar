<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Harga_model');
        $this->load->model('Komoditas_model'); // untuk dropdown komoditas
        $this->load->model('User_model');      // untuk dropdown user
    }

    public function index()
    {
        $data['harga'] = $this->Harga_model->get_all();
        $data['title'] = "Data Komoditas";
        $data['js'] = 'harga.js';
        $data['filename'] = "data_komoditas";
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('harga/list', $data);
        $this->load->view('templates/footer', $data);
    }

    public function add()
    {
        $data['komoditas'] = $this->Komoditas_model->get_komoditas();

        if ($this->input->post()) {
            $insert = [
                'tanggal'       => $this->input->post('tanggal'),
                'id_komoditas'  => $this->input->post('id_komoditas'),
                'harga'         => $this->input->post('harga'),
                'id_user'       => $this->session->userdata('id_user')
            ];
            $this->Harga_model->insert($insert);
            redirect('harga');
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('harga/form', $data);
        $this->load->view('templates/footer', $data);
    }

    public function edit($id)
    {
        $data['row'] = $this->Harga_model->get_by_id($id);
        $data['komoditas'] = $this->Komoditas_model->get_komoditas();

        if ($this->input->post()) {
            $update = [
                'tanggal'       => $this->input->post('tanggal'),
                'id_komoditas'  => $this->input->post('id_komoditas'),
                'harga'         => $this->input->post('harga'),
                'id_user'       => $this->session->userdata('id_user')
            ];
            $this->Harga_model->update($id, $update);
            redirect('harga');
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('harga/form', $data);
        $this->load->view('templates/footer', $data);
    }

    public function delete($id)
    {
        $this->Harga_model->delete($id);
        redirect('harga');
    }

    public function upload()
    {
        if ($_FILES['file_excel']['name']) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'xls|xlsx';
            $config['file_name']     = 'harga_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file_excel')) {
                $file = $this->upload->data('full_path');

                // Load PHPExcel
                require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

                $inputFileType = PHPExcel_IOFactory::identify($file);
                $reader = PHPExcel_IOFactory::createReader($inputFileType);
                $excel = $reader->load($file);

                $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);

                $id_user = $this->session->userdata('id_user');

                $row_num = 0;
                $all_data = []; // kumpulkan semua data dulu
                foreach ($sheet as $row) {
                    $row_num++;
                    if ($row_num == 1) continue; // skip header

                    $tanggal = date('Y-m-d', strtotime($row['A']));

                    $data_insert = [
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Sapi'),                'harga' => $row['B'], 'id_user' => $id_user],
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Daging Sapi'),         'harga' => $row['C'], 'id_user' => $id_user],
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Ayam Broiler'),        'harga' => $row['D'], 'id_user' => $id_user],
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Karkas Ayam Broiler'), 'harga' => $row['E'], 'id_user' => $id_user],
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Telur Ayam Ras (P)'),  'harga' => $row['F'], 'id_user' => $id_user],
                        ['tanggal' => $tanggal, 'id_komoditas' => $this->getKomoditasId('Telur Ayam Ras (K)'),  'harga' => $row['G'], 'id_user' => $id_user],
                    ];
                    foreach ($data_insert as $d) {
                        if (!empty($d['harga'])) {
                            $all_data[] = $d; // simpan ke array untuk ditampilkan
                            $this->db->insert('trx_harga', $d); // matikan dulu kalau cuma mau lihat data
                        }
                    }
                }

                unlink($file); // hapus file setelah diproses
                redirect('harga');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }else{
            echo "No file selected.";
        }
    }


    private function getKomoditasId($nama)
    {
        $row = $this->db->get_where('master_komoditas', ['nama_komoditas' => $nama])->row();
        return $row ? $row->id_komoditas : null;
    }


    public function download_template()
    {
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

        $excel = new PHPExcel();

        // Set sheet aktif
        $excel->setActiveSheetIndex(0);

        // Header kolom
        $excel->getActiveSheet()
            ->setCellValue('A1', 'Tanggal')
            ->setCellValue('B1', 'Sapi')
            ->setCellValue('C1', 'Daging Sapi')
            ->setCellValue('D1', 'Ayam Broiler')
            ->setCellValue('E1', 'Karkas Ayam Broiler')
            ->setCellValue('F1', 'Telur Ayam Ras (P)')
            ->setCellValue('G1', 'Telur Ayam Ras (K)');

        // Nama file
        $filename = "template_harga.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    public function delete_multiple()
    {
        $ids = $this->input->post('id_harga');
        if($ids) {
            $this->db->where_in('id_harga', $ids);
            $this->db->delete('trx_harga');
        }
        redirect('harga');
    }

   public function generate() {
    // 1️⃣ Ambil semua tanggal unik dari tabel harga
        $dates = $this->db->select('DISTINCT(tanggal) as tgl')->order_by('tanggal', 'ASC')->get('trx_harga')->result_array();

        // 2️⃣ Mapping ID komoditas ke nama
        $komoditas = [
            7  => 'SAPI',
            8  => 'DAGING SAPI',
            9  => 'AYAM BROILER',
            10 => 'KARKAS AYAM BROILER',
            11 => 'TELUR AYAM RAS (P)',
            12 => 'TELUR AYAM RAS (K)'
        ];

        // 3️⃣ Lokasi file template PNG & font OTF
        $template_path = FCPATH . 'assets/img/template.png';
        $font_path     = FCPATH . 'assets/font/Garet-Book.otf'; 
        $font_tgl      = FCPATH . 'assets/font/radley.ttf'; 

        if (!file_exists($template_path)) {
            show_error('File template tidak ditemukan di: ' . $template_path);
        }

        if (!file_exists($font_path)) {
            show_error('File font tidak ditemukan di: ' . $font_path);
        }

        $upload_path = FCPATH . 'uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $positions = [500, 610, 720, 830, 940, 1050]; // posisi Y tiap baris harga

        foreach ($dates as $dateRow) {
            $tanggal = $dateRow['tgl'];

            // 4️⃣ Ambil semua harga untuk tanggal ini
            $result = $this->db->select('id_komoditas, harga')
                            ->where('tanggal', $tanggal)
                            ->get('trx_harga')
                            ->result_array();

            // 5️⃣ Buka template PNG
            $image = imagecreatefrompng($template_path);

            // 6️⃣ Siapkan warna teks (hitam)
            $black = imagecolorallocate($image, 0, 0, 0);

            // 7️⃣ Tambahkan teks tanggal update
            $tanggal_text = 'Update Data : ' . date('j F Y', strtotime($tanggal));
            imagettftext($image, 30, 0, 260, 378, $black, $font_tgl, $tanggal_text);

            // 8️⃣ Tulis harga ke gambar
            $i = 0;
            foreach ($result as $row) {
                $harga = 'Rp. ' . number_format($row['harga'], 0, ',', '.');
                if (isset($positions[$i])) {
                    imagettftext($image, 30, 0, 690, $positions[$i], $black, $font_path, $harga);
                }
                $i++;
            }

            // 9️⃣ Buat nama file
            $filename = 'harga-' . date('Y-m-d', strtotime($tanggal)) . '.jpg';
            $fullpath = $upload_path . $filename;

            // 10️⃣ Hapus file lama jika ada
            if (file_exists($fullpath)) {
                unlink($fullpath);
            }

            // 11️⃣ Simpan gambar
            imagejpeg($image, $fullpath, 100);
            imagedestroy($image);

            echo '<p>✅ Gambar untuk tanggal ' . $tanggal . ' berhasil dibuat! <a href="' . base_url('uploads/' . $filename) . '" target="_blank">Lihat</a></p>';
        }
    }
}
