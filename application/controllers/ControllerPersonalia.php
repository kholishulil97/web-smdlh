<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControllerPersonalia extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        @session_start();
        if ($this->session->userdata('nip') == null || ($this->session->userdata('role_id') != 5)) {
            redirect('auth/blocked');
        }
    }

    public function lihatDaftarKruJalan()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Data Karyawan';
        $data['title_dr'] = '&nbsp; Kru Jalan';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatdaftarkrujalan', $data);
        $this->load->view('templates/footer');
    }

    public function daftarKruJalan()
    {
        $ambil = 'kru';
        $list = $this->ModelUser->get_datatables($ambil);
        $data = array();

        foreach ($list as $record) {
            $row = array();
            $row[] = $record->nip;
            $row[] = $record->nama;
            $row[] = $record->alamat;
            $row[] = $record->nomor_hp;

            //seleksi posisi kru jalan
            if ($record->role_id == 31) {
                $row[] = 'Sopir';
                $posisi = 'Sopir';
            } else if ($record->role_id == 32) {
                $row[] = 'Kondektur';
                $posisi = 'Kondektur';
            } else {
                $row[] = 'Kernet';
                $posisi = 'Kernet';
            }

            //menambahkan html untuk kolom foto
            if ($record->fotoprofil) {
                //$sweet = array("id" => $record->id, "fotoprofil" => $record->fotoprofil, "nama" => $record->nama, "nip" => $record->nip, "role_id" => $record->role_id);
                $row[] = '<a href="' . base_url('assets/img/profile/' . $record->fotoprofil) . '" target="_blank" onclick="lihat_foto(event,'  . $record->id . ')" id="foto' . $record->id . '"><img src="' . base_url('assets/img/profile/' . $record->fotoprofil) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><input type="hidden" value="' . $record->nama . '" name="sweet_nama' . $record->id . '" /><input type="hidden" value="' . $posisi . '" name="sweet_posisi' . $record->id . '" />';
            } else {
                $row[] = '<span class="badge badge-light">Belum ada</span>';
            }

            //menambahkan html untuk kolom status
            if ($record->status_dinas == 1) {
                $row[] = '<span class="badge badge-success">Aktif</span>';

                //menambahkan html untuk kolom aksi
                $row[] = '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_krujalan(' . "'" . $record->id . "'" . ')">Edit</a>';
            } else if ($record->status_dinas == 0) {
                $row[] = '<span class="badge badge-warning">Idle</span>';

                //menambahkan html untuk kolom aksi
                $row[] = '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_krujalan(' . "'" . $record->id . "'" . ')">Edit</a>';
            } else {
                $row[] = '<span class="badge badge-danger">Putus Mitra</span>';
                $row[] = '';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelUser->count_all($ambil),
            "recordsFiltered" => $this->ModelUser->count_filtered($ambil),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function tambahKruJalan()
    {
        $this->_validate_krujalan();

        $data = array(
            'nip'  => $this->input->post('tambahKruJalanNip', true),
            'nama'  => $this->input->post('tambahKruJalanNama', true),
            'alamat'  => $this->input->post('tambahKruJalanAlamat', true),
            'nomor_hp' => $this->input->post('tambahKruJalanNomorHp', true),
            'role_id' => $this->input->post('tambahKruJalanPosisi', true),
            'password' => "akasmila",
            'date_created' => round(microtime(true))
        );

        if (!empty($_FILES['photo']['name'])) { // jika ada berkas foto, maka lakukan upload
            $upload = $this->_do_upload();
            $data['fotoprofil'] = $upload;
        }

        if (empty($_FILES['photo']['name'])) { // jika tidak ada berkas foto, maka berkas foto akan diatur menjadi foto default.png
            $data['fotoprofil'] = "default.png";
        }

        $insert = $this->ModelUser->tambahDataUser($data);

        // mengambil data id user yang baru ditambahkan
        $kru = $this->ModelUser->getLastUserbyTipe();

        // menerbitkan surat pemutusan mitra (0 = perjanjian/masuk, 1 = putus mitra/keluar)
        $tipe_surat = 0; // perjanjian/masuk
        $this->_buatSuratMitraBaru($kru->id, $tipe_surat);

        echo json_encode(array("status" => TRUE));
    }

    private function _validate_krujalan() //fungsi untuk melakukan validasi formulir  
    {
        $nip = $this->ModelUser->get_by_nip_array($this->input->post('tambahKruJalanNip'));
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('action') == 'add') {
            if ($this->input->post('tambahKruJalanNip') == '') {
                $data['inputerror'][] = 'tambahKruJalanNip';
                $data['error_string'][] = 'NIP wajib diisi';
                $data['status'] = FALSE;
            } else if ($this->input->post('tambahKruJalanNip') == $nip) {
                $data['inputerror'][] = 'tambahKruJalanNip';
                $data['error_string'][] = 'NIP sudah tersimpan di database';
                $data['status'] = FALSE;
            } else {
                $data['status'] = TRUE;
            }
        } else {
            if ($this->input->post('tambahKruJalanNip') == '') {
                $data['inputerror'][] = 'tambahKruJalanNip';
                $data['error_string'][] = 'NIP wajib diisi';
                $data['status'] = FALSE;
            }
        }

        if ($this->input->post('tambahKruJalanNama') == '') {
            $data['inputerror'][] = 'tambahKruJalanNama';
            $data['error_string'][] = 'Nama wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahKruJalanAlamat') == '') {
            $data['inputerror'][] = 'tambahKruJalanAlamat';
            $data['error_string'][] = 'Alamat wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahKruJalanNomorHp') == '') {
            $data['inputerror'][] = 'tambahKruJalanNomorHp';
            $data['error_string'][] = 'Nomor HP wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahKruJalanPosisi') == '') {
            $data['inputerror'][] = 'tambahKruJalanPosisi';
            $data['error_string'][] = 'Silahkan pilih posisi terlebih dahulu';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    private function _do_upload()
    {
        $config['upload_path']          = 'assets/img/profile/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 300; //Kilobyte
        $config['max_width']            = 1500; // pixel
        $config['max_height']           = 1500; // pixel
        $config['file_name']            = round(microtime(true) * 1000); //memberi nama berkas dari timestamp milidetik agar setiap nama berkas menjadi unik

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) //mengunggah berkas dan memvalidasi 
        {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = 'Unggah error: ' . $this->upload->display_errors('', ''); //menampilkan error ajax. redaksional pesan error ada pada application/language/english/upload_lang.php
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }

        return $this->upload->data('file_name');
    }

    public function tampil_editKruJalan($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        $data = $this->ModelUser->get_by_id($id);
        //$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        echo json_encode($data);
    }

    public function editKruJalan()
    {
        $this->_validate_krujalan();

        $data = array(
            'nip'  => $this->input->post('tambahKruJalanNip', true),
            'nama'  => $this->input->post('tambahKruJalanNama', true),
            'alamat'  => $this->input->post('tambahKruJalanAlamat', true),
            'nomor_hp' => $this->input->post('tambahKruJalanNomorHp', true),
            'role_id' => $this->input->post('tambahKruJalanPosisi', true)
        );

        if (!empty($_FILES['photo']['name'])) { // mengganti foto
            $upload = $this->_do_upload();

            //hapus berkas yang sudah ada
            $person = $this->ModelUser->get_by_id($this->input->post('id'));
            if (file_exists('assets/img/profile/' . $person->fotoprofil) && $person->fotoprofil != "default.png")
                unlink('assets/img/profile/' . $person->fotoprofil);

            $data['fotoprofil'] = $upload;
        }

        $this->ModelUser->editDataUser(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function putusmitraKruJalan()
    {

        // bila satus dinas kru aktif, maka personalia akan mencarikan pengganti dan menerbitkan sp baru
        if ($this->input->post('status_dinas') == 1) {
            // GANTI SATU KRU
            if ($this->input->post('role_id') == 31)
                $pergantian = array(0 => $this->input->post('tambahDinasBaru'));
            else if ($this->input->post('role_id') == 32)
                $pergantian = array(1 => $this->input->post('tambahDinasBaru'));
            else if ($this->input->post('role_id') == 33)
                $pergantian = array(2 => $this->input->post('tambahDinasBaru'));

            // mengambil id dinas kru lama 
            $kru = $this->ModelUser->get_by_id($this->input->post('id'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($kru->dinas_id, $pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('id'));
            $kru_ganti = array(0 => $this->input->post('tambahDinasBaru'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        }

        // menerbitkan surat pemutusan mitra (0 = perjanjian/masuk, 1 = putus mitra/keluar)
        $tipe_surat = 1; // putus mitra/keluar
        $this->_buatSuratMitraBaru($this->input->post('id'), $tipe_surat);

        echo json_encode(array("status" => TRUE));
    }

    private function _buatDinasBaru($dinas_id, $pergantian)
    {
        //pengambilan array yang berisi kru yang berdinas
        $kru_dinas_array = $this->ModelDinas->get_single_by_id($dinas_id);
        $kru_dinas_array = explode(",", $kru_dinas_array->kru_dinas);

        //penyusunan array untuk membuat dinas baru
        $new_kru_dinas_array = array_replace($kru_dinas_array, $pergantian);
        $new_kru_dinas = implode(",", $new_kru_dinas_array);

        $dinas = $this->ModelDinas->get_single_by_id($dinas_id);

        $data = array(
            'bus_id'  => $dinas->bus_id,
            'trayek_id'  => $dinas->trayek_id,
            'kru_dinas' => $new_kru_dinas,
            'status_jalan' => 1
        );
        // memasukkan data dinas ke dalam tabel dinas
        $insert = $this->ModelDinas->tambahDinas($data);

        //mengubah status dinas lama
        $data = array(
            'status_jalan' => 0
        );
        $this->ModelDinas->updateStatusJalan($dinas_id, $data);
    }

    private function _buatSPbaru($kru_ganti, $kru_lama)
    {
        //mengeluarkan SP baru 
        $dinas_id = $this->ModelDinas->get_id_dinas();
        $tanggal_sp = date('Y-m-d H:i:s');
        $pengatur = $this->ModelUser->getSessionUserLogin();

        //mengubah status dinas kru yang diganti/kru lama
        foreach ($kru_lama as $key => $kru) {
            $data = array(
                'status_dinas' => 3,
                'dinas_id' => 0
            );
            $this->ModelUser->updateStatusKru($kru, $data);
        }

        //mengubah status dinas kru baru
        foreach ($kru_ganti as $key => $kru) {
            $data = array(
                'status_dinas' => 1,
                'dinas_id' => $dinas_id["id"]
            );
            $this->ModelUser->updateStatusKru($kru, $data);
        }

        //menerbitkan SP untuk masing-masing kru yang diganti
        foreach ($kru_ganti as $key => $kru) {
            $data = array(
                'dinas_id'  => $dinas_id["id"],
                'kru_id'  => $kru,
                'nomor_sp' => random_string('alnum', 6),
                'tanggal_sp' => $tanggal_sp,
                'pengatur_id' => $pengatur['id'],
                'mengganti_id' => $kru_lama[$key]
            );
            $this->ModelSP->tambahSP($data);
        }
    }


    private function _buatSuratMitraBaru($id, $tipe_surat)
    {
        // mengambil nomor surat terakhir berdasarkan tipe surat
        $surat = $this->ModelSuratMitra->getLastSuratbyTipe($tipe_surat);

        // mengambil data user yang sedang login
        $personalia = $this->ModelUser->getSessionUserLogin();

        $tanggal_sm = date('Y-m-d H:i:s');

        $data = array(
            'kru_id'  => $id,
            'personalia_id' => $personalia['id'],
            'tanggal_sm' => $tanggal_sm,
            'nomor_sm' => $surat->nomor_sm + 1,
            'tipe_surat' => $tipe_surat
        );
        $this->ModelSuratMitra->tambahSuratMitra($data);
    }

    public function lihatDaftarPetugasKontrol()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Data Karyawan';
        $data['title_dr'] = '&nbsp; Petugas Kontrol';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatdaftarpetugaskontrol', $data);
        $this->load->view('templates/footer');
    }

    public function daftarPetugasKontrol()
    {
        $ambil = 'kontrol';
        $list = $this->ModelUser->get_datatables($ambil);
        $data = array();

        foreach ($list as $record) {
            $row = array();
            $row[] = $record->nip;
            $row[] = $record->nama;
            $row[] = $record->alamat;
            $row[] = $record->nomor_hp;
            $posisi = 'Petugas Kontrol';

            //menambahkan html untuk kolom foto
            if ($record->fotoprofil)
                $row[] = '<a href="' . base_url('assets/img/profile/' . $record->fotoprofil) . '" target="_blank" onclick="lihat_foto(event, ' . $record->id . ')" id="foto' . $record->id . '"><img src="' . base_url('assets/img/profile/' . $record->fotoprofil) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><input type="hidden" value="' . $record->nama . '" name="sweet_nama' . $record->id . '" /><input type="hidden" value="' . $posisi . '" name="sweet_posisi' . $record->id . '" />';
            else
                $row[] = '<span class="badge badge-light">Belum ada</span>';

            //menambahkan html untuk kolom aksi
            $row[] = '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_kontrol(' . "'" . $record->id . "'" . ')">Edit</a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelUser->count_all($ambil),
            "recordsFiltered" => $this->ModelUser->count_filtered($ambil),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function tambahPetugasKontrol()
    {
        $this->_validate_petugaskontrol();

        $data = array(
            'nip'  => $this->input->post('tambahPetugasKontrolNip', true),
            'nama'  => $this->input->post('tambahPetugasKontrolNama', true),
            'alamat'  => $this->input->post('tambahPetugasKontrolAlamat', true),
            'nomor_hp' => $this->input->post('tambahPetugasKontrolNomorHp', true),
            'role_id' => 4,
            'password' => "akasmila",
            'date_created' => round(microtime(true) * 1000),
        );

        if (!empty($_FILES['photo']['name'])) { // jika ada berkas foto, maka lakukan upload
            $upload = $this->_do_upload();
            $data['fotoprofil'] = $upload;
        }

        if (empty($_FILES['photo']['name'])) { // jika tidak ada berkas foto, maka berkas foto akan diatur menjadi foto default.png
            $data['fotoprofil'] = "default.png";
        }

        $insert = $this->ModelUser->tambahDataUser($data);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate_petugaskontrol() //fungsi untuk melakukan validasi formulir  
    {
        $nip = $this->ModelUser->get_by_nip_array($this->input->post('tambahPetugasKontrolNip'));
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('action') == 'add') {
            if ($this->input->post('tambahPetugasKontrolNip') == '') {
                $data['inputerror'][] = 'tambahPetugasKontrolNip';
                $data['error_string'][] = 'NIP wajib diisi';
                $data['status'] = FALSE;
            } else if ($this->input->post('tambahPetugasKontrolNip') == $nip) {
                $data['inputerror'][] = 'tambahPetugasKontrolNip';
                $data['error_string'][] = 'NIP sudah tersimpan di database';
                $data['status'] = FALSE;
            } else {
                $data['status'] = TRUE;
            }
        } else {
            if ($this->input->post('tambahPetugasKontrolNip') == '') {
                $data['inputerror'][] = 'tambahPetugasKontrolNip';
                $data['error_string'][] = 'NIP wajib diisi';
                $data['status'] = FALSE;
            }
        }

        if ($this->input->post('tambahPetugasKontrolNama') == '') {
            $data['inputerror'][] = 'tambahPetugasKontrolNama';
            $data['error_string'][] = 'Nama wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahPetugasKontrolAlamat') == '') {
            $data['inputerror'][] = 'tambahPetugasKontrolAlamat';
            $data['error_string'][] = 'Alamat wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahPetugasKontrolNomorHp') == '') {
            $data['inputerror'][] = 'tambahPetugasKontrolNomorHp';
            $data['error_string'][] = 'Nomor HP wajib diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function tampil_editPetugasKontrol($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        $data = $this->ModelUser->get_by_id($id);
        //$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        echo json_encode($data);
    }

    public function editPetugasKontrol()
    {
        $this->_validate_petugaskontrol();

        $data = array(
            'nip'  => $this->input->post('tambahPetugasKontrolNip', true),
            'nama'  => $this->input->post('tambahPetugasKontrolNama', true),
            'alamat'  => $this->input->post('tambahPetugasKontrolAlamat', true),
            'nomor_hp' => $this->input->post('tambahPetugasKontrolNomorHp', true),
        );

        if (!empty($_FILES['photo']['name'])) { // mengganti foto
            $upload = $this->_do_upload();

            //hapus berkas yang sudah ada
            $person = $this->ModelUser->get_by_id($this->input->post('id'));
            if (file_exists('assets/img/profile/' . $person->fotoprofil) && $person->fotoprofil != "default.png")
                unlink('assets/img/profile/' . $person->fotoprofil);

            $data['fotoprofil'] = $upload;
        }

        $this->ModelUser->editDataUser(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function hapusPetugasKontrol($id)
    {
        //menghapus berkas
        $person = $this->ModelUser->get_by_id($id);
        if (file_exists('assets/img/profile/' . $person->fotoprofil) && $person->fotoprofil != "default.png")
            unlink('assets/img/profile/' . $person->fotoprofil);

        //menghapus di database
        $this->ModelUser->hapusDataUser($id);
        echo json_encode(array("status" => TRUE));
    }

    public function lihatDaftarDinas()
    {

        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Dinas';
        $data['title_dr'] = '&nbsp; Daftar Dinasan';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatdaftardinas', $data);
        $this->load->view('templates/footer');
    }

    public function daftarDinas()
    {
        $list = $this->ModelDinas->get_datatables();

        $data = array();

        foreach ($list as $record) {

            $row = array();
            //menambahkan html untuk kolom foto
            if ($record->url) {
                if ($record->status_jalan == 1)
                    $row[] = '<div style="text-align: center;"><a href="' . base_url('assets/img/bus/' . $record->url) . '" target="_blank" onclick="lihat_foto(event, ' . $record->id . ')" id="foto' . $record->id . '"><img src="' . base_url('assets/img/bus/' . $record->url) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><br><strong>' . $record->nopol . '</strong><br><small><strong> ' . $record->kelas . '</strong></small></div><input type="hidden" value="' . $record->nopol . '" name="sweet_nama' . $record->id . '" /><input type="hidden" value="' . $record->kelas . '" name="sweet_posisi' . $record->id . '" />';
                else
                    $row[] = '<div style="text-align: center;"><strong>' . $record->nopol . '</strong><br><small><strong> ' . $record->kelas . '</strong></small></div>';
            } else {
                if ($record->status_jalan == 1)
                    $row[] = '<div style="text-align: center;"><span class="badge badge-light">Foto belum ada</span><br><strong>' . $record->nopol . '</strong><br><small><strong> ' . $record->kelas . '</strong></small></div>';
                else
                    $row[] = '<div style="text-align: center;"><strong>' . $record->nopol . '</strong><br><small><strong> ' . $record->kelas . '</strong></small></div>';
            }

            $row[] = '<div style="text-align: center;">' . $record->posawal . ' - ' . $record->posakhir . ' <br><small><strong>(' . $record->kode . ')</strong></small></div>';

            $id_kru = explode(",", $record->kru_dinas); // mengambil data id kru yang bertugas
            $n_id = count($id_kru); // menghitung kru yang bertugas

            if ($n_id == 2) { // jika kru berjumlah 2, maka
                foreach ($id_kru as $id) {
                    $kru = $this->ModelUser->get_by_id($id);
                    if ($kru->role_id == 31) {
                        $posisi = 'Sopir';
                    } else if ($kru->role_id == 32) {
                        $posisi = 'Kondektur';
                    } else {
                        $posisi = 'Kernet';
                    }

                    if ($record->status_jalan == 1)
                        $row[] = '<div style="text-align: center;"><span>' . $kru->nama . '</span><br><a href="' . base_url('assets/img/profile/' . $kru->fotoprofil) . '" target="_blank" onclick="lihat_foto(event, ' . $kru->id . ')" id="foto' . $kru->id . '"><img src="' . base_url('assets/img/profile/' . $kru->fotoprofil) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><br><small> (' . $kru->nip . ')</small></div><input type="hidden" value="' . $kru->nama . '" name="sweet_nama' . $kru->id . '" /><input type="hidden" value="' . $posisi . '" name="sweet_posisi' . $kru->id . '" />';
                    else
                        $row[] = '<div style="text-align: center;"><span>' . $kru->nama . '</span><br><small> (' . $kru->nip . ')</small></div>';
                }
                $row[] = '<div style="text-align: center;"><span><strong>-</strong></span></div>';
            } else {
                foreach ($id_kru as $id) {
                    $kru = $this->ModelUser->get_by_id($id);
                    if ($kru->role_id == 31) {
                        $posisi = 'Sopir';
                    } else if ($kru->role_id == 32) {
                        $posisi = 'Kondektur';
                    } else {
                        $posisi = 'Kernet';
                    }

                    if ($record->status_jalan == 1)
                        $row[] = '<div style="text-align: center;"><span>' . $kru->nama . '</span><br><a href="' . base_url('assets/img/profile/' . $kru->fotoprofil) . '" target="_blank" onclick="lihat_foto(event, ' . $kru->id . ')" id="foto' . $kru->id . '"><img src="' . base_url('assets/img/profile/' . $kru->fotoprofil) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><br><small> (' . $kru->nip . ')</small></div><input type="hidden" value="' . $kru->nama . '" name="sweet_nama' . $kru->id . '" /><input type="hidden" value="' . $posisi . '" name="sweet_posisi' . $kru->id . '" />';
                    else
                        $row[] = '<div style="text-align: center;"><span>' . $kru->nama . '</span><br><small> (' . $kru->nip . ')</small></div>';
                }
            }

            //menambahkan html untuk kolom status
            if ($record->status_jalan == 1) {
                $row[] = '<div class="float-center" style="text-align: center;"><span class="badge badge-success">Aktif</span></div>';
            } else {
                $row[] = '<div class="float-center" style="text-align: center;"><span class="badge badge-danger">Tidak Aktif</span></div>';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelDinas->count_all(),
            "recordsFiltered" => $this->ModelDinas->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function getBusbyKelas()
    {

        $kelas = $this->input->post('tambahDinasKelas');

        $data = $this->ModelBus->get_by_kelas($kelas);
        echo json_encode($data);
    }
    public function getTrayek()
    {

        $kelas = $this->input->post('tambahDinasKelas');

        $data = $this->ModelTrayek->get_trayek($kelas);
        echo json_encode($data);
    }

    public function getKrubyStatus()
    {
        //$kelas = $this->input->post('tambahDinasKelas');
        $data = $this->ModelUser->get_by_status();
        echo json_encode($data);
    }

    public function lihatLogSP()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Dinas';
        $data['title_dr'] = '&nbsp; Log SP';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatlogsp', $data);
        $this->load->view('templates/footer');
    }

    public function logSP()
    {
        $list = $this->ModelSP->get_datatables();
        $data = array();

        foreach ($list as $record) {

            $row = array();
            $row[] = '<div style="text-align: center;"><span>' . $record->nama . '</span><br><small> <strong>(' . $record->nip . ')</strong></small></div>';
            $row[] = '<div style="text-align: center;">' . $record->nopol . '<br><small><strong> ' . $record->kelas . '</strong></small></div>';
            $row[] = '<div style="text-align: center;">' . $record->posawal . ' - ' . $record->posakhir . ' <br><small><strong>(' . $record->kode . ')</strong></small></div>';

            if ($record->mengganti_id != 0) {
                $kru = $this->ModelUser->get_by_id($record->mengganti_id);
                $row[] = '<div style="text-align: center;"><span>' . $kru->nama . '</span><br><small> <strong>(' . $kru->nip . ')</strong></small></div>';
            } else {
                $row[] = '<div style="text-align: center;"><strong>-</strong></div>';
            }
            $pengatur = $this->ModelUser->get_by_id($record->pengatur_id);
            if ($pengatur->role_id == 1)
                $row[] = '<div style="text-align: center;"><span>' . $pengatur->nama . '</span><br><small><strong>(' . $pengatur->nip . ')</strong></small></div>';
            else
                $row[] = '<div style="text-align: center;"><span>' . $pengatur->nama . '</span><br><small><strong>(Personalia)</strong></small></div>';
            $row[] = longdate_indo($record->tanggal_sp);

            //menambahkan html untuk kolom aksi
            $row[] = '<a href="' . site_url("controllerpersonalia/unduhsp/")  . $record->id . '" class="badge badge-info" tittle="Detail">Detail</a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelSP->count_all(),
            "recordsFiltered" => $this->ModelSP->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function unduhSP($id)
    {
        $data['sp'] = $this->ModelSP->get_by_id($id);
        $this->load->view('user/unduhsp', $data);
    }

    public function lihatLogSuratMitra()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Data Karyawan';
        $data['title_dr'] = '&nbsp; Log Surat Mitra';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatlogsuratmitra', $data);
        $this->load->view('templates/footer');
    }

    public function logSuratMitra()
    {
        $list = $this->ModelSuratMitra->get_datatables();
        $data = array();

        foreach ($list as $record) {

            $row = array();
            $row[] = $record->nomor_sm;
            $row[] = longdate_indo($record->tanggal_sm);
            $row[] = '<span>' . $record->nama . '</span><br><small> <strong>(' . $record->nip . ')</strong></small>';

            $personalia = $this->ModelUser->get_by_id($record->personalia_id);
            $row[] = '<span>' . $personalia->nama . '</span><br><small> <strong>(' . $personalia->nip . ')</strong></small>';

            // menambahkan html untuk tipe surat (0 = perjanjian/masuk, 1 = putus mitra/keluar)
            if ($record->tipe_surat == 0)
                $row[] = '<span class="badge badge-success"><i class="fas fa-fw fa-check"></i> Perjanjian</span>';
            else
                $row[] = '<span class="badge badge-danger"><i class="fas fa-fw fa-times"></i> Putus Mitra</span>';

            //menambahkan html untuk kolom aksi
            $row[] = '<a href="' . site_url("controllerpersonalia/unduhsuratmitra/")  . $record->id . '" class="badge badge-info" tittle="Detail">Detail</a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelSuratMitra->count_all(),
            "recordsFiltered" => $this->ModelSuratMitra->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }
    public function unduhSuratMitra($id)
    {
        $cek = $this->ModelSuratMitra->get_by_id($id);
        $data['sm'] = $this->ModelSuratMitra->get_by_id($id);
        if ($cek->tipe_surat == 1)
            $this->load->view('user/unduhskputusmitra', $data);
        else
            $this->load->view('user/unduhskperjanjian', $data);
    }

    public function lihatDaftarLaporanKontrol()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Laporan';
        $data['title_dr'] = '&nbsp; Laporan Kontrol';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatdaftarlaporankontrol', $data);
        $this->load->view('templates/footer');
    }

    public function daftarLaporanKontrol()
    {
        $list = $this->ModelLaporanKontrol->get_datatables();
        $data = array();

        foreach ($list as $record) {

            $row = array();
            //nama petugas
            $row[] = '<span>' . $record->nama . '</span><br><small> <strong>(' . $record->nip . ')</strong></small>';
            //bus
            $row[] = '<div>' . $record->nopol . '<br><small><strong> ' . $record->kelas . '</strong></small></div><div>' . $record->posawal . ' - ' . $record->posakhir . ' <br><small><strong>(' . $record->kode . ')</strong></small></div>';
            //kru jalan
            $kru_dinas_array = explode(",", $record->kru_dinas);
            $n_kru = count($kru_dinas_array);
            if ($n_kru == 2) {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . '<br><small><strong>Kondektur : </strong></small><br>' . $kondektur->nama . '<br><small><strong>Kernet :<br>  --</strong></small>';
            } else {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $kernet = $this->ModelUser->get_by_id($kru_dinas_array[2]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . '<br><small><strong>Kondektur : </strong></small><br>' . $kondektur->nama . '<br><small><strong>Kernet : </strong></small><br>' . $kernet->nama;
            }
            if ($record->status_turun == 0) {
                $jenis_pelanggaran = "";
                $keterangan = "";
                $naik_kontrol = "";
                $turun_kontrol = "";
                $tanggal_naik_kontrol = "";
                $tanggal_turun_kontrol = "";
                $jumlah_penumpang = "";
                $pendapatan_kontrol = "";
                $jam_naik_kontrol = "";
                $tanggal_naik_kontrol =  "";
                $jam_turun_kontrol = "";
                $tanggal_turun_kontrol = "";
            } else {
                $jenis_pelanggaran = $record->jenis_pelanggaran;
                $keterangan = $record->keterangan;
                $naik_kontrol = $record->naik_kontrol;
                $turun_kontrol = $record->turun_kontrol;
                $jam_naik_kontrol = date_format(date_create($record->tanggal_naik_kontrol), 'H:i');
                $tanggal_naik_kontrol =  longdate_indo($record->tanggal_naik_kontrol);
                $jam_turun_kontrol = date_format(date_create($record->tanggal_turun_kontrol), 'H:i');
                $tanggal_turun_kontrol = longdate_indo($record->tanggal_turun_kontrol);
                $jumlah_penumpang = $record->jumlah_penumpang;
                $pendapatan_kontrol = number_format($record->pendapatan_kontrol, 2, ",", ".");
            }
            //posnaik
            $row[] = '<div>' . $naik_kontrol . '<br><small><strong> ' . $jam_naik_kontrol . '</strong> <br>' . $tanggal_naik_kontrol . '</div>';
            //posturun
            $row[] = '<div>' . $turun_kontrol . '<br><small><strong> ' . $jam_turun_kontrol . '</strong> <br>' . $tanggal_turun_kontrol . '</div>';
            //jumlah penumpang
            $row[] = '<div style="text-align: right;"><strong>' . $jumlah_penumpang . '</strong></div>';
            //pendapatan
            $row[] = '<div style="text-align: right;"><span><h6>Rp. <strong>' . $pendapatan_kontrol . '</strong></h6></span></div>';
            //jenis pelanggaran
            $row[] = $jenis_pelanggaran;
            //keterangan
            $row[] = $keterangan;
            //menambahkan html untuk kolom aksi
            $row[] = '<a href="#" class="badge badge-info" tittle="Detail" onclick="detail_sp(' . "'" . $record->id . "'" . ')">Detail</a>';

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelLaporanKontrol->count_all(),
            "recordsFiltered" => $this->ModelLaporanKontrol->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function lihatOperasional()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Laporan';
        $data['title_dr'] = '&nbsp; Operasional';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('personalia/lihatoperasional', $data);
        $this->load->view('templates/footer');
    }

    public function laporanOperasional()
    {
        $list = $this->ModelLaporanHarian->get_datatables();
        $data = array();

        foreach ($list as $record) {

            $row = array();
            //bus
            $row[] = '<div>' . $record->nopol . '<br><small><strong> ' . $record->kelas . '</strong></small></div><div>' . $record->posawal . ' - ' . $record->posakhir . ' <br><small><strong>(' . $record->kode . ')</strong></small></div>';
            //krujalan
            $kru_dinas_array = explode(",", $record->kru_dinas);
            $n_kru = count($kru_dinas_array);
            if ($n_kru == 2) {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . '<br><small><strong>Kondektur : </strong></small><br>' . $kondektur->nama . '<br><small><strong>Kernet :<br>  --</strong></small>';
            } else {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $kernet = $this->ModelUser->get_by_id($kru_dinas_array[2]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . '<br><small><strong>Kondektur : </strong></small><br>' . $kondektur->nama . '<br><small><strong>Kernet : </strong></small><br>' . $kernet->nama;
            }

            if ($record->status_selesai == 0) {
                $tanggal_selesai = "Dinas belum selesai";
            } else {
                $tanggal_selesai = date_format(date_create($record->tanggal_selesai), 'H:i') . '<br>' . longdate_indo($record->tanggal_selesai);
            }
            //tanggaljalan
            $row[] = date_format(date_create($record->tanggal_jalan), 'H:i') . '<br>' . longdate_indo($record->tanggal_jalan);
            //tanggalselesai
            $row[] = $tanggal_selesai;
            //jumlah penumpang
            $row[] = '<div style="text-align: right;"><strong>' . $this->ModelLaporanHarian->get_jumlah_penumpang_by_id($record->id) . '</strong></div>';
            //pendapatan (total pendapatan - total pengeluaran)
            $karcis = $this->ModelLaporanHarian->get_karcis_by_id($record->id);
            $pendapatan = 0;
            foreach ($karcis as $k) {
                $pendapatan += $k->tarif;
            }
            $pengeluaran_array = $this->ModelLaporanHarian->get_pengeluaran_by_id($record->id);
            $pengeluaran = 0;
            foreach ($pengeluaran_array as $p) {
                $pengeluaran += $p->nominal;
            }
            $pendapatan = $pendapatan - $pengeluaran;
            // $pendapatan = $pengeluaran - $pendapatan;
            //html pendapatan

            //hitung premi
            if ($n_kru == 2) { //bus patas
                $premi_sopir = $pendapatan * 17 / 100;
                $premi_kondektur = $pendapatan * 13 / 100;
                if ($pendapatan > 0)
                    $row[] = '<div style="text-align: right;"><span class="badge badge-success"><h6>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</h6></span><br><small>Sopir : Rp. ' . number_format($premi_sopir, 2, ",", ".") . '<br>Kondektur : Rp. ' . number_format($premi_kondektur, 2, ",", ".") . '</small></div>';
                else
                    $row[] = '<div style="text-align: right;"><span class="badge badge-danger"><h6>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</h6></span><br><small>Sopir : Rp. ' . number_format($premi_sopir, 2, ",", ".") . '<br>Kondektur : Rp. ' . number_format($premi_kondektur, 2, ",", ".") . '</small></div>';
            } else { //bus ekonomi
                $premi_sopir = $pendapatan * 14 / 100;
                $premi_kondektur = $pendapatan * 10 / 100;
                $premi_kernet = $pendapatan * 6 / 100;
                if ($pendapatan > 0)
                    $row[] = '<div style="text-align: right;"><span class="badge badge-success"><h6>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</h6></span><br><small>Sopir : Rp. ' . number_format($premi_sopir, 2, ",", ".") . '<br>Kondektur : Rp. ' . number_format($premi_kondektur, 2, ",", ".") . '<br>Kernet : Rp. ' . number_format($premi_kernet, 2, ",", ".") . '</small></div>';
                else
                    $row[] = '<div style="text-align: right;"><span class="badge badge-danger"><h6>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</h6></span><br><small>Sopir : Rp. ' . number_format($premi_sopir, 2, ",", ".") . '<br>Kondektur : Rp. ' . number_format($premi_kondektur, 2, ",", ".") . '<br>Kernet : Rp. ' . number_format($premi_kernet, 2, ",", ".") . '</small></div>';
            }

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelLaporanHarian->count_all(),
            "recordsFiltered" => $this->ModelLaporanHarian->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }
}
