<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    public function checkRememberMe()
    {
        $CI =& get_instance();
        $CI->load->helper('cookie');

        // Jika belum login
        if (!$CI->session->userdata('logged_in')) {
            $token = get_cookie('remember_token');
            
            if ($token) {
                $user = $CI->db->get_where('master_user', ['remember_token' => $token])->row();

                if ($user) {
                    // Auto login
                    $CI->session->set_userdata([
                        'id_user'   => $user->id_user,
                        'nama_user' => $user->nama_user,
                        'jabatan'   => $user->jabatan,
                        'kode'      => $user->kode,
                        'logged_in' => TRUE
                    ]);
                }
            }
        }
    }
}
