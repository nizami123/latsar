<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('cookie');
    }

    public function index()
    {
        if($this->session->userdata('logged_in')){
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $user = $this->User_model->check_login($username, $password);

        if ($user) {
            // Simpan session
            $this->session->set_userdata([
                'id_user'   => $user->id_user,
                'nama_user' => $user->nama_user,
                'jabatan'   => $user->jabatan,
                'kode'      => $user->kode,
                'logged_in' => TRUE
            ]);

            // Token remember me
            if($this->input->post('remember_me')){
                $token = bin2hex(random_bytes(32));
                $this->db->update('master_user', ['remember_token' => $token], ['id_user' => $user->id_user]);

                // Set cookie
                set_cookie([
                    'name'     => 'remember_token',
                    'value'    => $token,
                    'expire'   => 86400*30,
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => FALSE,
                    'httponly' => TRUE,
                    'samesite' => 'Lax'
                ]);
            }

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth');
        }
    }

    public function logout()
    {
        // Hapus session
        $this->session->sess_destroy();

        // Hapus cookie
        delete_cookie('remember_token');

        redirect('auth');
    }

     public function sync()
    {
        // 1️⃣ Ambil semua kecamatan yang punya kode
        $kecamatan_list = $this->db->get_where('master_wilayah', "kode IS NOT NULL")->result();

        foreach ($kecamatan_list as $kec) {
            $code = trim($kec->kode);

            // 2️⃣ URL API wilayah.id per kecamatan
            $url = "https://wilayah.id/api/villages/{$code}.json";

            // 3️⃣ Ambil data dari API
            $response = @file_get_contents($url);
            if (!$response) {
                echo "❌ Gagal ambil data dari: {$url}<br>";
                continue;
            }

            $data = json_decode($response, true);

            if (!isset($data['data'])) {
                echo "⚠️ Data tidak ditemukan untuk {$kec->name} ({$code})<br>";
                continue;
            }

            // 4️⃣ Simpan ke tabel master_kecamatan
            foreach ($data['data'] as $desa) {
                $insert = [
                    'kode_desa' => $desa['code'],
                    'nama_desa' => $desa['name'],
                    'kode' => $code
                ];

                // Cek apakah sudah ada
                $exists = $this->db->get_where('master_kecamatan', ['kode_desa' => $desa['code']])->row();
                if ($exists) {
                    // Update jika sudah ada
                    $this->db->where('kode_desa', $desa['code']);
                    $this->db->update('master_kecamatan', $insert);
                } else {
                    // Insert baru
                    $this->db->insert('master_kecamatan', $insert);
                }
            }

            echo "✅ Selesai: {$kec->name} ({$code}) - " . count($data['data']) . " desa<br>";
            flush();
            ob_flush();
            sleep(1); // beri jeda biar tidak overload API
        }

        echo "<br>🎉 Sinkronisasi selesai!";
    }
}
