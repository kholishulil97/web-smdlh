<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControllerPengaturDinas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        @session_start();
        if ($this->session->userdata('nip') == null || ($this->session->userdata('role_id') != 1)) {
            redirect('auth/blocked');
        }
    }

    public function index()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Halaman Utama - Pengatur Dinas';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pengaturdinas/index', $data);
        $this->load->view('templates/footer');
    }

    public function lihatDaftarBus()
    {

        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Data Master';
        $data['title_dr'] = '&nbsp; Bus';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pengaturdinas/lihatdaftarbus', $data);
        $this->load->view('templates/footer');
    }

    public function daftarBus()
    {
        $list = $this->ModelBus->get_datatables();
        $data = array();

        foreach ($list as $record) {
            $row = array();
            $row[] = $record->nopol;
            $row[] = $record->mesin;
            $row[] = $record->tahun;
            $row[] = $record->kelas;
            $trayek = $this->ModelBus->get_by_id_join($record->trayek_id);
            if ($record->trayek_id) {
                foreach ($trayek as $t) {
                    $row[] = $t->posawal . ' - ' . $t->posakhir . ' <br><strong>(' . $t->kode . ')</strong>';
                    break;
                }
            } else {
                if ($record->status == 3)
                    $row[] = '<strong>-</strong>';
                else
                    $row[] = '<span class="badge badge-secondary">Belum diisi trayek</span>';
            }

            //menambahkan html untuk kolom foto
            if ($record->url)
                $row[] = '<a href="' . base_url('assets/img/bus/' . $record->url) . '" target="_blank" onclick="lihat_foto(event, ' . $record->id . ')" id="foto' . $record->id . '"><img src="' . base_url('assets/img/bus/' . $record->url) . '" class="img-thumbnail" style="max-width: 100px; max-length: 100px;"  /></a><input type="hidden" value="' . $record->nopol . '" name="sweet_nama' . $record->id . '" /><input type="hidden" value="' . $record->kelas . '" name="sweet_posisi' . $record->id . '" />';
            else
                $row[] = '<span class="badge badge-light">Belum ada</span>';

            //menambahkan html untuk kolom status
            if ($record->status == 1) {
                $row[] = '<span class="badge badge-success">Aktif</span>';
                //menambahkan html untuk kolom aksi
                $row[] = '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_bus(' . "'" . $record->id . "'" . ')">Edit</a>';
            } else if ($record->status == 0) {
                $row[] = '<span class="badge badge-warning">Idle</span>';
                //menambahkan html untuk kolom aksi
                $row[] = '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_bus(' . "'" . $record->id . "'" . ')">Edit</a>';
            } else {
                $row[] = '<span class="badge badge-danger">Tidak Aktif</span>';
                $row[] = '';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelBus->count_all(),
            "recordsFiltered" => $this->ModelBus->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function tambahBus()
    {
        $this->_validate();

        $data = array(
            'nopol'  => strtoupper($this->input->post('tambahBusNopol', true)),
            'mesin'  => $this->input->post('tambahBusMesin', true),
            'tahun' => $this->input->post('tambahBusTahun', true),
            'kelas' => $this->input->post('tambahBusKelas', true)
        );

        if (!empty($_FILES['photo']['name'])) { // jika ada berkas foto, maka lakukan upload
            $upload = $this->_do_upload();
            $data['url'] = $upload;
        }

        $insert = $this->ModelBus->tambahDataBus($data);
        echo json_encode(array("status" => TRUE));
    }

    public function tampil_editBus($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        $data = $this->ModelBus->get_by_id($id);
        //$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        echo json_encode($data);
    }

    public function editBus()
    {
        $this->_validate();

        $data = array(
            'nopol'  => $this->input->post('tambahBusNopol', true),
            'mesin'  => $this->input->post('tambahBusMesin', true),
            'tahun' => $this->input->post('tambahBusTahun', true),
            'kelas' => $this->input->post('tambahBusKelas', true)
        );

        if ($this->input->post('remove_photo')) // jika checkbox hapus foto dicentang
        {
            if (file_exists('assets/img/bus/' . $this->input->post('remove_photo')) && $this->input->post('remove_photo'))
                unlink('assets/img/bus/' . $this->input->post('remove_photo'));
            $data['url'] = '';
        }

        if (!empty($_FILES['photo']['name'])) { // mengganti foto
            $upload = $this->_do_upload();

            //hapus berkas yang sudah ada
            $person = $this->ModelBus->get_by_id($this->input->post('id'));
            if (file_exists('assets/img/bus/' . $person->url) && $person->url)
                unlink('assets/img/bus/' . $person->url);

            $data['url'] = $upload;
        }

        $this->ModelBus->editDataBus(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function nonaktifBus()
    {

        // bila satus dinas bus aktif, maka pengatur akan mencarikan pengganti dan menerbitkan sp baru
        if ($this->input->post('status') == 1) {
            // GANTI BUS DAN TRAYEK
            // mengambil data dinas dari bus yang akan dinonaktifkan
            $dinas = $this->ModelDinas->get_by_bus($this->input->post('id'));

            //mengubah status Bus Lama
            $this->ModelBus->updateTrayekNA($this->input->post('id'));

            //mengubah status Bus Baru
            $this->ModelBus->updateTrayekDinas($this->input->post('editDinasNopol'), $this->input->post('trayek_id'));

            // menjadikan array 
            if ($this->input->post('tambahBusKelas') == 'Ekonomi') {
                $kru_ganti = $kru_ganti = explode(",", $dinas->kru_dinas);
                $kru_lama = array(0 => 0, 1 => 0, 2 => 0);
            } else {
                $kru_ganti = $kru_ganti = explode(",", $dinas->kru_dinas);
                $kru_lama = array(0 => 0, 1 => 0);
            }

            //menambah dinas baru
            $data = array(
                'bus_id'  => $this->input->post('editDinasNopol'),
                'trayek_id'  => $this->input->post('trayek_id'),
                'kru_dinas' => $dinas->kru_dinas,
                'status_jalan' => 1
            );
            // memasukkan data dinas ke dalam tabel dinas
            $insert = $this->ModelDinas->tambahDinas($data);

            //mengubah status dinas lama
            $data = array(
                'status_jalan' => 0
            );
            $this->ModelDinas->updateStatusJalan($dinas->id, $data);

            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else {
            // GANTI BUS DAN TRAYEK
            //mengubah status Bus Lama
            $this->ModelBus->updateTrayekNA($this->input->post('id'));
        }

        echo json_encode(array("status" => TRUE));
    }

    private function _do_upload()
    {
        $config['upload_path']          = 'assets/img/bus/';
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

    private function _validate() //fungsi untuk melakukan validasi formulir  
    {
        $nopol = $this->ModelBus->get_by_nopol_array($this->input->post('tambahBusNopol'));
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('action') == 'add') {
            if ($this->input->post('tambahBusNopol') == '') {
                $data['inputerror'][] = 'tambahBusNopol';
                $data['error_string'][] = 'NOPOL wajib diisi';
                $data['status'] = FALSE;
            } else if ($this->input->post('tambahBusNopol') == $nopol) {
                $data['inputerror'][] = 'tambahBusNopol';
                $data['error_string'][] = 'NOPOL sudah tersimpan di database';
                $data['status'] = FALSE;
            } else {
                $data['status'] = TRUE;
            }
        } else {
            if ($this->input->post('tambahBusNopol') == '') {
                $data['inputerror'][] = 'tambahBusNopol';
                $data['error_string'][] = 'NOPOL wajib diisi';
                $data['status'] = FALSE;
            }
        }

        if ($this->input->post('tambahBusMesin') == '') {
            $data['inputerror'][] = 'tambahBusMesin';
            $data['error_string'][] = 'Mesin wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahBusTahun') == '') {
            $data['inputerror'][] = 'tambahBusTahun';
            $data['error_string'][] = 'Tahun wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahBusKelas') == '') {
            $data['inputerror'][] = 'tambahBusKelas';
            $data['error_string'][] = 'Silahkan pilih kelas terlebih dahulu';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }


    public function lihatDaftarTrayek()
    {

        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Data Master';
        $data['title_dr'] = '&nbsp; Trayek';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pengaturdinas/lihatdaftartrayek', $data);
        $this->load->view('templates/footer');
    }

    public function daftarTrayek()
    {
        $list = $this->ModelTrayek->get_datatables();

        $data = array();

        foreach ($list as $record) :
            $data[] = array(
                "kode" => $record->kode,
                "posawal" => $record->posawal,
                "posakhir" => $record->posakhir,
                "kelas" => $record->kelas,
                "jumlah_bus" => '<strong>' . $this->ModelTrayek->count_get_by_id_join($record->id) . '</strong>',
                "aksi" => '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_trayek(' . "'" . $record->id . "'" . ')">Edit</a><br><a href="' . site_url("controllerpengaturdinas/unduhtabeltarif/")  . $record->id . '" class="badge badge-secondary" tittle="Detail">Cetak</a>'
            );
        endforeach;

        ## Response
        $response = array(
            "draw" => $_POST['draw'],
            "iTotalRecords" => $this->ModelTrayek->count_all(),
            "iTotalDisplayRecords" => $this->ModelTrayek->count_all(),
            "aaData" => $data
        );
        //menampilkan dalam format json
        echo json_encode($response);
    }

    public function tambahTrayek()
    {
        $this->_validate_trayek();

        $tambahTrayekPosLewat = implode(",", $this->input->post('tambahTrayekPosLewat', true));
        $kode = $this->input->post('tambahTrayekKode', true);
        $posawal = $this->input->post('tambahTrayekPosAwal', true);
        $poslewat = $this->input->post('tambahTrayekPosLewat', true);
        $posakhir = $this->input->post('tambahTrayekPosAkhir', true);

        $data = array(
            'kode'  => $this->input->post('tambahTrayekKode', true),
            'posawal'  => $this->input->post('tambahTrayekPosAwal', true),
            'posakhir' => $this->input->post('tambahTrayekPosAkhir', true),
            'poslewat' => $tambahTrayekPosLewat,
            'kelas' => $this->input->post('tambahTrayekKelas', true)
        );

        // memasukkan data trayek ke dalam tabel trayek
        $insert = $this->ModelTrayek->tambahDataTrayek($data);

        // mengambil id trayek yang baru saja dimasukkan ke dalam database
        $id_trayek = $this->ModelTrayek->get_id_trayek($kode);

        // menyusun komposisi array
        array_unshift($poslewat, $posawal); // menaruh posawal di urutan pertama array
        array_push($poslewat, $posakhir); // menaruh posakhir di urutan akhir array         
        $posturun = array_reverse($poslewat); // membalik urutan array
        $n_pos = count($poslewat); // menghiitung jumlah elemen array
        $id = 1; // indeks permulaan untuk mengisi kolom posnaik pada tabel trayek 

        // looping dengan pola segi tiga | trayek pergi khusus untuk kolom posnaik
        for ($i = 0; $i < $n_pos; $i++) { // tinggi
            foreach ($poslewat as $key => $pos) { // lebar
                if ($key != $i) {
                    $data = array(
                        'id_tarif' => $id,
                        'trayek_id' => $id_trayek["id"],
                        'posnaik' => $pos // penginputannnya tanpa menyertakan indeks krn tipe data string
                    );
                    $insert = $this->ModelTrayek->tambahSusunanPos($data); // menambahkan data ke dalam tabel tarif
                    $id++;
                } else {
                    break; // keluar dari foreach untuk menambah baris baru
                }
            }
        }

        // looping dengan pola segi tiga | trayek pulang khusus untuk kolom posnaik
        for ($i = 0; $i < $n_pos; $i++) { // untuk mengisi index ke bawah
            foreach ($posturun as $key => $pos) { // untuk mengisi index ke samping
                if ($key != $i) {
                    $data = array(
                        'id_tarif' => $id,
                        'trayek_id' => $id_trayek["id"],
                        'posnaik' => $pos
                    );
                    $insert = $this->ModelTrayek->tambahSusunanPos($data); // menambahkan data ke dalam tabel tarif
                    $id++;
                } else {
                    break; // keluar dari foreach untuk menambah baris baru
                }
            }
        }

        $id = 1; // indeks permulaan untuk mengisi kolom posturun pada tabel tarif

        // looping dengan pola segi tiga | trayek pergi khusus untuk kolom posturun
        foreach ($poslewat as $key => $pos) { // semua elemen array yang akan dimasukkan bertipe string
            if ($key != $n_pos) {
                for ($j = 1; $j <= $key; $j++) {
                    $data_set = array(
                        'posturun' => $pos // maka penginputannya tanpa menggunakan indeks
                    );
                    $set = $this->ModelTrayek->updateSusunanPos(array('id_tarif' => $id, 'trayek_id' =>  $id_trayek["id"]), $data_set); // mengisi kolom posturun menggunakan fungsi update
                    $id++;
                }
            } else {
                break; // keluar dari foreach untuk menambah baris baru
            }
        }

        // looping dengan pola segi tiga | trayek pulang khusus untuk kolom posturun
        foreach ($posturun as $key => $pos) {
            if ($key != $n_pos) { // untuk mengisi index ke bawah
                for ($j = 1; $j <= $key; $j++) { // untuk mengisi indeks ke samping
                    $data_set = array(
                        'posturun' => $pos
                    );
                    $set = $this->ModelTrayek->updateSusunanPos(array('id_tarif' => $id, 'trayek_id' =>  $id_trayek["id"]), $data_set); // mengisi kolom posturun menggunakan fungsi update
                    $id++;
                }
            } else {
                break; // keluar dari perulangan foreach untuk membentuk baris baru
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    public function tampil_editTrayek($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        if (isset($id)) {
            $result = $this->ModelTrayek->get_by_id_array($id);
            $kode = '';
            $posawal = '';
            $posakhir = '';
            $kelas = '';
            $poslewat_jadi = '';
            foreach ($result as $row) {
                $kode = $row["kode"];
                $posawal = $row["posawal"];
                $posakhir = $row["posakhir"];
                $kelas = $row["kelas"];
                $poslewat_array = explode(",", $row["poslewat"]);
                $count = 1;
                foreach ($poslewat_array as $poslewat) {
                    $button = '';
                    if ($count > 1) {
                        $button = '<button type="button" name="remove" id="' . $count . '" class="btn btn-danger btn-xs remove"><span class="fas fa-minus"></span></button>';
                    } else {
                        $button = '<button type="button" name="tambah_pos_lewat" id="tambah_pos_lewat" class="btn btn-success btn-xs"><span class="fas fa-plus"></span></button>';
                    }
                    $poslewat_jadi .= '
            <div class="entry input-group col-md-6 mb-3" id="row' . $count . '">
            <input type="text" name="tambahTrayekPosLewat[]" placeholder="lewat" class="form-control name_list" value="' . $poslewat . '" />' . $button . '
            </div>
           ';
                    $count++;
                }
            }
            $output = array(
                'id' => $id,
                'kode'     => $kode,
                'posawal'     => $posawal,
                'posakhir'     => $posakhir,
                'kelas'     => $kelas,
                'poslewat' => $poslewat_jadi
            );
            echo json_encode($output);
        }
    }

    function editTrayek()
    {
        $this->_validate_trayek();

        $tambahTrayekPosLewat = implode(",", $_POST["tambahTrayekPosLewat"]);
        $trayek_id = $this->input->post('id');
        $kode = $this->input->post('tambahTrayekKode', true);
        $posawal = $this->input->post('tambahTrayekPosAwal', true);
        $poslewat = $this->input->post('tambahTrayekPosLewat', true);
        $posakhir = $this->input->post('tambahTrayekPosAkhir', true);

        $data = array(
            'kode'  => $this->input->post('tambahTrayekKode', true),
            'posawal'  => $this->input->post('tambahTrayekPosAwal', true),
            'posakhir' => $this->input->post('tambahTrayekPosAkhir', true),
            'poslewat' => $tambahTrayekPosLewat,
            'kelas' => $this->input->post('tambahTrayekKelas', true)
        );

        $insert = $this->ModelTrayek->editDataTrayek(array('id' => $trayek_id), $data);

        $delete = $this->ModelTrayek->deleteTarif($trayek_id);

        // menyusun komposisi array
        array_unshift($poslewat, $posawal); // menaruh posawal di urutan pertama array
        array_push($poslewat, $posakhir); // menaruh posakhir di urutan akhir array         
        $posturun = array_reverse($poslewat); // membalik urutan array
        $n_pos = count($poslewat); // menghiitung jumlah elemen array
        $id = 1; // indeks permulaan untuk mengisi kolom posnaik pada tabel trayek 

        // looping dengan pola segi tiga | trayek pergi khusus untuk kolom posnaik
        for ($i = 0; $i < $n_pos; $i++) { // tinggi
            foreach ($poslewat as $key => $pos) { // lebar
                if ($key != $i) {
                    $data = array(
                        'id_tarif' => $id,
                        'trayek_id' => $trayek_id,
                        'posnaik' => $pos // penginputannnya tanpa menyertakan indeks krn tipe data string
                    );
                    $insert = $this->ModelTrayek->tambahSusunanPos($data); // menambahkan data ke dalam tabel tarif
                    $id++;
                } else {
                    break; // keluar dari foreach untuk menambah baris baru
                }
            }
        }

        // looping dengan pola segi tiga | trayek pulang khusus untuk kolom posnaik
        for ($i = 0; $i < $n_pos; $i++) { // untuk mengisi index ke bawah
            foreach ($posturun as $key => $pos) { // untuk mengisi index ke samping
                if ($key != $i) {
                    $data = array(
                        'id_tarif' => $id,
                        'trayek_id' => $trayek_id,
                        'posnaik' => $pos
                    );
                    $insert = $this->ModelTrayek->tambahSusunanPos($data); // menambahkan data ke dalam tabel tarif
                    $id++;
                } else {
                    break; // keluar dari foreach untuk menambah baris baru
                }
            }
        }

        $id = 1; // indeks permulaan untuk mengisi kolom posturun pada tabel tarif

        // looping dengan pola segi tiga | trayek pergi khusus untuk kolom posturun
        foreach ($poslewat as $key => $pos) { // semua elemen array yang akan dimasukkan bertipe string
            if ($key != $n_pos) {
                for ($j = 1; $j <= $key; $j++) {
                    $data_set = array(
                        'posturun' => $pos // maka penginputannya tanpa menggunakan indeks
                    );
                    $set = $this->ModelTrayek->updateSusunanPos(array('id_tarif' => $id, 'trayek_id' =>  $trayek_id), $data_set); // mengisi kolom posturun menggunakan fungsi update
                    $id++;
                }
            } else {
                break; // keluar dari foreach untuk menambah baris baru
            }
        }

        // looping dengan pola segi tiga | trayek pulang khusus untuk kolom posturun
        foreach ($posturun as $key => $pos) {
            if ($key != $n_pos) { // untuk mengisi index ke bawah
                for ($j = 1; $j <= $key; $j++) { // untuk mengisi indeks ke samping
                    $data_set = array(
                        'posturun' => $pos
                    );
                    $set = $this->ModelTrayek->updateSusunanPos(array('id_tarif' => $id, 'trayek_id' =>  $trayek_id), $data_set); // mengisi kolom posturun menggunakan fungsi update
                    $id++;
                }
            } else {
                break; // keluar dari perulangan foreach untuk membentuk baris baru
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    public function unduhTabelTarif($id)
    {
        $data['id'] = array(
            'id' => $id
        );
        $this->load->view('user/unduhtabeltarif', $data);
    }

    private function _validate_trayek() //fungsi untuk melakukan validasi formulir  
    {
        $kode = $this->ModelTrayek->get_by_kode_array($this->input->post('tambahTrayekKode'));
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('action') == 'add') {
            if ($this->input->post('tambahTrayekKode') == '') {
                $data['inputerror'][] = 'tambahTrayekKode';
                $data['error_string'][] = 'Kode wajib diisi';
                $data['status'] = FALSE;
            } else if ($this->input->post('tambahTrayekKode') == $kode) {
                $data['inputerror'][] = 'tambahTrayekKode';
                $data['error_string'][] = 'KODE sudah tersimpan di database';
                $data['status'] = FALSE;
            } else {
                $data['status'] = TRUE;
            }
        } else {
            $data['status'] = TRUE;
        }

        if ($this->input->post('tambahTrayekPosAwal') == '') {
            $data['inputerror'][] = 'tambahTrayekPosAwal';
            $data['error_string'][] = 'Pos Awal wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahTrayekPosAkhir') == '') {
            $data['inputerror'][] = 'tambahTrayekPosAkhir';
            $data['error_string'][] = 'Pos Akhir wajib diisi';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahTrayekKelas') == '') {
            $data['inputerror'][] = 'tambahTrayekKelas';
            $data['error_string'][] = 'Silahkan pilih kelas terlebih dahulu';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
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
        $this->load->view('pengaturdinas/lihatdaftardinas', $data);
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
                //menambahkan html untuk kolom aksi
                $row[] = '<div class="float-center" style="text-align: center;"><a href="#" class="badge badge-info" tittle="Edit" onclick="edit_dinas(' . "'" . $record->id . "'" . ')">Edit</a></div>';
            } else {
                $row[] = '<div class="float-center" style="text-align: center;"><span class="badge badge-danger">Tidak Aktif</span></div>';
                $row[] = '';
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

    public function tambahDinas()
    {
        $this->_validate_dinas();

        $id = (array) $this->input->post('tambahDinasSopir');
        if ($this->input->post('tambahDinasKelas') == 'Patas') {
            array_push($id, $this->input->post('tambahDinasKondektur'));
        } else {
            array_push($id, $this->input->post('tambahDinasKondektur'), $this->input->post('tambahDinasKernet'));
        }

        $kru_dinas = implode(",", $id);

        $data = array(
            'bus_id'  => $this->input->post('tambahDinasNopol'),
            'trayek_id'  => $this->input->post('tambahDinasTrayek'),
            'kru_dinas' => $kru_dinas,
            'status_jalan' => 1
        );

        // memasukkan data dinas ke dalam tabel dinas
        $insert = $this->ModelDinas->tambahDinas($data);

        $this->ModelBus->updateTrayekDinas($this->input->post('tambahDinasNopol'), $this->input->post('tambahDinasTrayek'));

        $dinas_id = $this->ModelDinas->get_id_dinas();

        $pengatur = $this->ModelUser->getSessionUserLogin();

        $tanggal_sp = date('Y-m-d H:i:s');

        foreach ($id as $kru) {
            $data = array(
                'status_dinas' => 1,
                'dinas_id' => $dinas_id["id"]
            );
            $this->ModelUser->updateStatusKru($kru, $data);

            $dataSP = array(
                'dinas_id'  => $dinas_id["id"],
                'kru_id'  => $kru,
                'nomor_sp' => random_string('alnum', 6),
                'tanggal_sp' => $tanggal_sp,
                'pengatur_id' => $pengatur['id']
            );
            $this->ModelSP->tambahSP($dataSP);
        }

        echo json_encode(array("status" => TRUE));
    }

    private function _validate_dinas() //fungsi untuk melakukan validasi formulir  
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('tambahDinasKelas') == 'Ekonomi') {
            if ($this->input->post('tambahDinasSopir') == '') {
                $data['inputerror'][] = 'tambahDinasSopir';
                $data['error_string'][] = 'Silahkan memilih Sopir terlebih dahulu';
                $data['status'] = FALSE;
            }

            if ($this->input->post('tambahDinasKondektur') == '') {
                $data['inputerror'][] = 'tambahDinasKondektur';
                $data['error_string'][] = 'Silahkan memilih Kondektur terlebih dahulu';
                $data['status'] = FALSE;
            }

            if ($this->input->post('tambahDinasKernet') == '') {
                $data['inputerror'][] = 'tambahDinasKernet';
                $data['error_string'][] = 'Silahkan memilih Kernet terlebih dahulu';
                $data['status'] = FALSE;
            }
        } else {
            if ($this->input->post('tambahDinasSopir') == '') {
                $data['inputerror'][] = 'tambahDinasSopir';
                $data['error_string'][] = 'Silahkan memilih Sopir terlebih dahulu';
                $data['status'] = FALSE;
            }

            if ($this->input->post('tambahDinasKondektur') == '') {
                $data['inputerror'][] = 'tambahDinasKondektur';
                $data['error_string'][] = 'Silahkan memilih Kondektur terlebih dahulu';
                $data['status'] = FALSE;
            }
        }

        if ($this->input->post('tambahDinasKelas') == '') {
            $data['inputerror'][] = 'tambahDinasKelas';
            $data['error_string'][] = 'Silahkan memilih kelas bus terlebih dahulu';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahDinasNopol') == '') {
            $data['inputerror'][] = 'tambahDinasNopol';
            $data['error_string'][] = 'Silahkan memilih bus terlebih dahulu';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tambahDinasTrayek') == '') {
            $data['inputerror'][] = 'tambahDinasTrayek';
            $data['error_string'][] = 'Silahkan memilih trayek terlebih dahulu';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function tampil_editDinas($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        $dinasan = $this->ModelDinas->get_by_id($id);

        $kru_dinas_array = explode(",", $dinasan->kru_dinas);

        $data = array();

        foreach ($kru_dinas_array as $kru_dinas) {
            $kru = $this->ModelUser->get_by_id($kru_dinas);

            $data[] = array(
                "id" => $dinasan->id,
                "bus_id" => $dinasan->bus_id,
                "trayek_id" => $dinasan->trayek_id,
                "kru_dinas" => $dinasan->kru_dinas,
                "nopol" => $dinasan->nopol,
                "mesin" => $dinasan->mesin,
                "tahun" => $dinasan->tahun,
                "kelas" => $dinasan->kelas,
                "url" => $dinasan->url,
                "status" => $dinasan->status,
                "kode" => $dinasan->kode,
                "posawal" => $dinasan->posawal,
                "posakhir" => $dinasan->posakhir,
                "id_kru" => $kru->id,
                "nip" => $kru->nip,
                "nama" => $kru->nama,
                "role_id" => $kru->role_id

            );
        }
        //var_dump($data);
        //$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        echo json_encode($data);
    }

    public function editDinas()
    {
        $this->_validate_editdinas();

        if ($this->input->post('tambahDinasKelas') == 'Patas') {
            $v_kernet = NULL;
        } else {
            $v_kernet = '';
        }

        if ($this->input->post('editDinasSopir') == '' && $this->input->post('editDinasKondektur') == '' && $this->input->post('editDinasKernet') == '') {
            // kru kosong/tidak ada yang diganti

        } else if ($this->input->post('editDinasSopir') != '' && $this->input->post('editDinasKondektur') == '' && $this->input->post('editDinasKernet') == $v_kernet) {
            // GANTI SOPIR SAJA
            $pergantian = array(0 => $this->input->post('editDinasSopir'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasSopir'));
            $kru_ganti = array(0 => $this->input->post('editDinasSopir'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else if ($this->input->post('editDinasSopir') == '' && $this->input->post('editDinasKondektur') != '' && $this->input->post('editDinasKernet') == $v_kernet) {
            // GANTI KONDEKTUR SAJA
            $pergantian = array(1 => $this->input->post('editDinasKondektur'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasKondektur'));
            $kru_ganti = array(0 => $this->input->post('editDinasKondektur'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else if ($this->input->post('editDinasSopir') == '' && $this->input->post('editDinasKondektur') == '' && $this->input->post('editDinasKernet') != $v_kernet) {
            // GANTI KERNET SAJA
            $pergantian = array(2 => $this->input->post('editDinasKernet'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasKernet'));
            $kru_ganti = array(0 => $this->input->post('editDinasKernet'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else if ($this->input->post('editDinasSopir') != '' && $this->input->post('editDinasKondektur') != '' && $this->input->post('editDinasKernet') == $v_kernet) {
            // GANTI SOPIR DAN KONDEKTUR
            $pergantian = array(0 => $this->input->post('editDinasSopir'), 1 => $this->input->post('editDinasKondektur'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'));
            $kru_ganti = array(0 => $this->input->post('editDinasSopir'), 1 => $this->input->post('editDinasKondektur'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else if ($this->input->post('editDinasSopir') != '' && $this->input->post('editDinasKondektur') == '' && $this->input->post('editDinasKernet') != $v_kernet) {
            // GANTI SOPIR DAN KERNET
            $pergantian = array(0 => $this->input->post('editDinasSopir'), 2 => $this->input->post('editDinasKernet'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKernet'));
            $kru_ganti = array(0 => $this->input->post('editDinasSopir'), 1 => $this->input->post('editDinasKernet'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else if ($this->input->post('editDinasSopir') == '' && $this->input->post('editDinasKernet') != '' && $this->input->post('editDinasKernet') != $v_kernet) {
            // GANTI KONDEKTUR DAN KERNET
            $pergantian = array(1 => $this->input->post('editDinasKondektur'), 2 => $this->input->post('editDinasKernet'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasKondektur'), 1 => $this->input->post('tambahDinasKernet'));
            $kru_ganti = array(0 => $this->input->post('editDinasKondektur'), 1 => $this->input->post('editDinasKernet'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        } else { // kru isi semua/akan diganti semua
            // GANTI SEMUA SOPIR, KONDEKTUR, DAN KERNET
            $pergantian = array(0 => $this->input->post('editDinasSopir'), 1 => $this->input->post('editDinasKondektur'), 2 => $this->input->post('editDinasKernet'));

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // menjadikan array
            $kru_lama = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'), 2 => $this->input->post('tambahDinasKernet'));
            $kru_ganti = array(0 => $this->input->post('editDinasSopir'), 1 => $this->input->post('editDinasKondektur'), 2 => $this->input->post('editDinasKernet'));
            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        }

        if ($this->input->post('tambahDinasNopol') != '' && $this->input->post('tambahDinasTrayek') != '') {
            // GANTI BUS DAN TRAYEK
            //mengubah status Bus Lama
            $this->ModelBus->updateTrayekIdle($this->input->post('tambahDinasNopol'));

            //mengubah status Bus Baru
            $this->ModelBus->updateTrayekDinas($this->input->post('editDinasNopol'), $this->input->post('editDinasTrayek'));


            // menjadikan array 
            if ($this->input->post('tambahDinasKelas') == 'Ekonomi') {
                $kru_ganti = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'), 2 => $this->input->post('tambahDinasKernet'));
                $kru_lama = array(0 => 0, 1 => 0, 2 => 0);
                //TANPA MENGGANTI KRU JALAN
                $pergantian = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'), 2 => $this->input->post('tambahDinasKernet'));
            } else {
                $kru_ganti = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'));
                $kru_lama = array(0 => 0, 1 => 0);
                //TANPA MENGGANTI KRU JALAN
                $pergantian = array(0 => $this->input->post('tambahDinasSopir'), 1 => $this->input->post('tambahDinasKondektur'));
            }

            // memanggil fungsi untuk membuat dinas baru
            $this->_buatDinasBaru($pergantian);

            // memanggil fungsi untuk menerbitkan SP baru
            $this->_buatSPbaru($kru_ganti, $kru_lama);
        }

        echo json_encode(array("status" => TRUE));
    }

    private function _buatDinasBaru($pergantian)
    {
        //pengambilan array yang berisi kru yang berdinas
        $kru_dinas_array = $this->ModelDinas->get_kru_dinas_by_id($this->input->post('id'));
        $kru_dinas_array = explode(",", $kru_dinas_array->kru_dinas);

        //penyusunan array untuk membuat dinas baru
        $new_kru_dinas_array = array_replace($kru_dinas_array, $pergantian);
        $new_kru_dinas = implode(",", $new_kru_dinas_array);

        //membuat dinas baru
        if ($this->input->post('tambahDinasNopol') != '' && $this->input->post('tambahDinasTrayek') != '') {
            // ganti bus dan trayek saja
            $new_bus = $this->input->post('editDinasNopol');
            $new_trayek = $this->input->post('editDinasTrayek');
        } else {
            // ganti kru jalan
            $new_bus = $this->input->post('tambahDinasNopol');
            $new_trayek = $this->input->post('tambahDinasTrayek');
        }

        $data = array(
            'bus_id'  => $new_bus,
            'trayek_id'  => $new_trayek,
            'kru_dinas' => $new_kru_dinas,
            'status_jalan' => 1
        );
        // memasukkan data dinas ke dalam tabel dinas
        $insert = $this->ModelDinas->tambahDinas($data);

        //mengubah status dinas lama
        $data = array(
            'status_jalan' => 0
        );
        $this->ModelDinas->updateStatusJalan($this->input->post('id'), $data);
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
                'status_dinas' => 0,
                'dinas_id' => 0
            );
            $this->ModelUser->updateStatusKru($kru, $data);
        }

        $dinas = $this->ModelDinas->getLastDinas();

        //mengubah status dinas kru baru
        foreach ($kru_ganti as $key => $kru) {
            $data = array(
                'status_dinas' => 1,
                'dinas_id' => $dinas->id
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

    private function _validate_editdinas() //fungsi untuk melakukan validasi formulir  
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('tambahDinasKelas') == 'Patas') {
            $v_kernet = NULL;
        } else {
            $v_kernet = '';
        }

        if ($this->input->post('editDinasNopol') != '' && $this->input->post('editDinasTrayek') == '') {
            $data['inputerror'][] = 'editDinasTrayek';
            $data['error_string'][] = 'Silahkan memilih Trayek terlebih dahulu';
            $data['status'] = FALSE;
        }

        if ($this->input->post('editDinasNopol') == '' && $this->input->post('editDinasTrayek') != '') {
            $data['inputerror'][] = 'editDinasNopol';
            $data['error_string'][] = 'Silahkan memilih Bus terlebih dahulu';
            $data['status'] = FALSE;
        }

        if ($this->input->post('editDinasSopir') != '' || $this->input->post('editDinasKondektur') != '' || $this->input->post('editDinasKernet') != $v_kernet) {
            if ($this->input->post('editDinasNopol') != '' || $this->input->post('editDinasTrayek') != '') {
                $data['inputerror'][] = 'editDinasKernet';
                $data['error_string'][] = 'PERINGATAN! Pergantian Bus hanya dapat dilakukan dengan mengosongkan/tidak memilih SEMUA Kru Jalan Pengganti';
                $data['status'] = FALSE;
            }
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
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
        $this->load->view('pengaturdinas/lihatlogsp', $data);
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
            $row[] = '<a href="' . site_url("controllerpengaturdinas/unduhsp/")  . $record->id . '" class="badge badge-info" tittle="Detail">Detail</a>';

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
}
