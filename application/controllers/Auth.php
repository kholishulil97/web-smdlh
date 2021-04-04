<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        //memanggil library CI utk validasi form login
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        //aturan inputan login
        $this->form_validation->set_rules('nip', 'NIP', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == true) {
            //jika validasi berhasil
            $this->_login();
        } else {
            //jika validasi gagal
            $this->load->view('auth/login');
        }
    }
    private function _login()
    {
        $nip = $this->input->post('nip', true);
        $password = $this->input->post('password', true);

        $user = $this->ModelUser->getUserLogin($nip); //mengambil data pengguna dari database

        if ($user) {
            //jika usernya ada
            //cek password
            if ($user['role_id'] == 1 || $user['role_id'] == 2 || $user['role_id'] == 5) {
                //jika role sesuai
                if ($password == $user['password']) {
                    //jika password inputan sama dengan di database
                    //menyimpan data dalam session
                    $data = [
                        'nip' => $user['nip'],
                        'role_id' => $user['role_id']
                    ];
                    $this->ModelUser->setSessionUser($data);
                    //mengarahkan ke landing page -> dashboard
                    redirect('controlleruser/dashboard');
                } else {
                    //jika password tidak sama
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password salah!</div>');
                    redirect('auth');
                }
            } else {
                //jika role tidak sesuai
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akses jabatan dilarang!</div>');
                redirect('auth');
            }
        } else {
            //jika usernya tidak ada
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">NIP tidak terdaftar!</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        //membersihkan data session
        $this->session->unset_userdata('nip');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Anda telah Logout dari sistem.</div>');
        redirect('auth');
    }

    public function blocked()
    {
        if ($this->session->userdata('nip') != null) {
            $data['link'] = base_url('controlleruser/dashboard');
        } else {
            $data['link'] = base_url();
        }
        $this->load->view('auth/blocked', $data);
    }

    public function blocked_404()
    {
        if ($this->session->userdata('nip') != null) {
            $data['link'] = base_url('controlleruser/dashboard');
        } else {
            $data['link'] = base_url();
        }
        $this->load->view('auth/blocked_404', $data);
    }

    public function getPassword()
    {
        $data = $this->ModelUser->getPasswordUserLogin();
        echo json_encode($data);
    }
}
