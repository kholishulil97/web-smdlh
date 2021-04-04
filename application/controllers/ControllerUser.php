<?php
defined('BASEPATH') or exit('No direct script access allowed');


class ControllerUser extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        @session_start();
        if ($this->session->userdata('nip') == null) {
            redirect('auth/blocked');
        }
    }

    public function dashboard()
    {
        setlocale(LC_ALL, 'IND');
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman

        if (($this->session->userdata('role_id') == 1)) {
            $data['title'] = 'Pengatur Dinas';
        } else if (($this->session->userdata('role_id') == 2)) {
            $data['title'] = 'Kasir';
        } else if (($this->session->userdata('role_id') == 5)) {
            $data['title'] = 'Personalia';
        }
        $data['title_dr'] = '&nbsp; Dashboard';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/index', $data);
        $this->load->view('templates/footer');
    }

    public function profil()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Pengaturan';
        $data['title_dr'] = '&nbsp; Profil';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/profil', $data);
        $this->load->view('templates/footer');
    }
}
