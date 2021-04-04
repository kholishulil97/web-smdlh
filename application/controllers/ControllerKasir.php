<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControllerKasir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        @session_start();
        if ($this->session->userdata('nip') == null || ($this->session->userdata('role_id') != 2)) {
            redirect('auth/blocked');
        }
    }


    public function index()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Halaman Utama - Kasir';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();

        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kasir/index', $data);
        $this->load->view('templates/footer');
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
        $this->load->view('kasir/lihatdaftartrayek', $data);
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
                "aksi" => '<a href="#" class="badge badge-info" tittle="Edit" onclick="edit_trayek(' . "'" . $record->id . "'" . ')">Edit</a><br><a href="' . site_url("controllerkasir/unduhtabeltarif/")  . $record->id . '" class="badge badge-secondary" tittle="Detail">Cetak</a>'
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

    public function tampil_editTrayek($id) //menampilkan data yang sudah tersimpan ke dalam modal bootstrap untuk diedit
    {
        if (isset($id)) {
            $result = $this->ModelTrayek->get_by_id_array($id); // mengambil seluruh data dari tabel trayek
            $tarif = $this->ModelTrayek->get_tarif_array($id); // mengambil kolom tarif dari tabel tarif
            $pos = $this->ModelTrayek->get_pos_array($id); // mengambil kolom posnaik dari tabel tarif untuk melihat pos apa saja yang dilalui dalam trayek pergi

            $single_tarif = $this->ModelTrayek->get_pos_by_id($id); // mengambil data tarif trayek dari tabel tarif untuk kemudian ditampilkan satu per satu menggunakan foreach
            $posturun = array_reverse($pos); // membalik urutan array $pos untuk menampilkan trayek pulang

            $n_pos = count($pos); // jumlah pos yang dilalui dalam trayek

            $poslewat_jadi = '';
            foreach ($result as $row) {
                $kode = $row["kode"];
                $posawal = $row["posawal"];
                $posakhir = $row["posakhir"];
                $kelas = $row["kelas"];

                $id_tarif = 1; //indeks permulaan untuk mengisi trayek pergi

                // menyusun html
                // awal tabel trayek pergi
                $poslewat_jadi .= '<tbody><tr>'; // awal tabel trayek pergi 

                // looping segi tiga untuk menampilkan data trayek pergi
                for ($i = 0; $i < $n_pos; $i++) {
                    for ($j = 1; $j <= $i; $j++) {
                        $single_tarif = $this->ModelTrayek->get_single_tarif($id, $id_tarif); // mengambil satu sel dari kolom tarif pada tabel tarif

                        $poslewat_jadi .= '<td class="table_data" data-row_id="' . $single_tarif->id_tarif  . '" contenteditable="true">' . $single_tarif->tarif . '</td>'; // isi tabel ke samping

                        $id_tarif++;
                    }
                    $shift = array_shift($pos); // mengambil elemen pertama dari array, elemen pertama tersebut kemudian dihapus dari array
                    $poslewat_jadi .= '<td><strong>' . $shift["posnaik"] . '</strong></td></tr>'; // pos yang dilalui | membuat baris baru
                }
                $poslewat_jadi .= '</tbody>'; // akhir tabel trayek pergi

                $poslewat_jadi .= '<tbody><tr>'; // awal tabel trayek pulang

                // looping segi tiga untuk menampilkan data trayek pulang
                for ($i = 0; $i < $n_pos; $i++) {
                    for ($j = 1; $j <= $i; $j++) {
                        $single_tarif = $this->ModelTrayek->get_single_tarif($id, $id_tarif); // mengambil satu sel dari kolom tarif pada tabel tarif

                        $poslewat_jadi .= '<td class="table_data" data-row_id="' . $single_tarif->id_tarif  . '" contenteditable="true">' . $single_tarif->tarif . '</td>'; // isi tabel ke samping

                        $id_tarif++;
                    }
                    $shift = array_shift($posturun); // mengambil elemen pertama dari array, elemen pertama tersebut kemudian dihapus dari array
                    $poslewat_jadi .= '<td><strong>' . $shift["posnaik"] . '</strong></td></tr>'; // pos yang dilalui | membuat baris baru
                }
                $poslewat_jadi .= '</tbody>'; // akhir tabel trayek pulang
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

    public function editTarifTrayek()
    {
        $where = array(
            'id_tarif' => $this->input->post('id_tarif'),
            'trayek_id' => $this->input->post('trayek_id')
        );

        $data = array(
            'tarif' => $this->input->post('value', true)
        );
        $this->ModelTrayek->updateSusunanPos($where, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function unduhTabelTarif($id)
    {
        $data['id'] = array(
            'id' => $id
        );
        $this->load->view('user/unduhtabeltarif', $data);
    }

    public function lihatDaftarLaporanHarian()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Pembayaran';
        $data['title_dr'] = '&nbsp; Laporan Harian';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kasir/lihatdaftarlaporanharian', $data);
        $this->load->view('templates/footer');
    }

    public function daftarLaporanHarian()
    {
        $list = $this->ModelLaporanHarian->get_datatables();
        $data = array();

        foreach ($list as $record) {
            $row = array();
            //bus
            $row[] = '<div>' . $record->nopol . '<br><small><strong> ' . $record->kelas . '</strong></small></div><div> ' . $record->posawal . ' - ' . $record->posakhir . ' <br><small><strong>(' . $record->kode . ')</strong></small></div>';
            //krujalan
            $kru_dinas_array = explode(",", $record->kru_dinas);
            $n_kru = count($kru_dinas_array);
            if ($n_kru == 2) {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . ' <br><small><strong> Kondektur : </strong></small><br>' . $kondektur->nama . ' <br><small><strong> Kernet :<br>  --</strong></small>';
            } else {
                $sopir = $this->ModelUser->get_by_id($kru_dinas_array[0]);
                $kondektur = $this->ModelUser->get_by_id($kru_dinas_array[1]);
                $kernet = $this->ModelUser->get_by_id($kru_dinas_array[2]);
                $row[] = '<small><strong>Sopir : </strong></small><br>' . $sopir->nama . ' <br><small><strong> Kondektur : </strong></small><br>' . $kondektur->nama . ' <br><small><strong> Kernet : </strong></small><br>' . $kernet->nama;
            }
            //tanggalselesai
            if ($record->status_selesai == 0) {
                $row[] = "Dinas belum selesai";
            } else {
                $row[] = date_format(date_create($record->tanggal_selesai), 'H:i') . '<br>' . longdate_indo($record->tanggal_selesai);
            }

            $pendapatan = $this->_hitungPendapatan($record->id);
            $premi_atas = $pendapatan * 9 / 100;
            $pendapatan = $pendapatan - $premi_atas;

            if ($pendapatan > 0)
                $row[] = '<div style="text-align: right;"><strong>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</strong>';
            else
                $row[] = '<div style="text-align: right;"><strong>Rp. ' . number_format($pendapatan, 2, ",", ".") . '</strong>';


            //html aksi
            if ($record->status_setor == 0) {
                if ($record->status_selesai == 0) {
                    $row[] = '<span class="badge badge-secondary"><i class="fas fa-fw fa-minus"></i> Dinas Blm Selesai</span>';
                    $row[] = "";
                } else {
                    $row[] = '<span class="badge badge-warning"><i class="fas fa-fw fa-exclamation"></i> Belum Disetor</span>';
                    $row[] = '<a href="#" class="badge badge-success" tittle="Detail" onclick="setor(' . "'" . $record->id . "' , '" . number_format($pendapatan, 2, ",", ".") . "'" . ')">Setor</a>';
                }
            } else {
                $row[] = '<span class="badge badge-success"><i class="fas fa-fw fa-check"></i> Sudah Disetor </span><br>' . date_format(date_create($record->tanggal_setor), 'H:i') . ' <br> ' . longdate_indo($record->tanggal_setor);
                $pemb = $this->ModelPembayaran->get_by_ref_id(0, $record->id);
                $row[] = '<a href="' . site_url("controllerkasir/lihatdetailpembayaran/")  . $pemb->id . '" class="badge badge-info" tittle="Detail">Detail</a>';
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

    private function _hitungPendapatan($id)
    {
        //pendapatan (total pendapatan - total pengeluaran)
        $karcis = $this->ModelLaporanHarian->get_karcis_by_id($id);
        $pendapatan = 0;
        foreach ($karcis as $k) {
            $pendapatan += $k->tarif;
        }
        $pengeluaran_array = $this->ModelLaporanHarian->get_pengeluaran_by_id($id);
        $pengeluaran = 0;
        foreach ($pengeluaran_array as $p) {
            $pengeluaran += $p->nominal;
        }
        $pendapatan = $pendapatan - $pengeluaran;
        // $pendapatan = $pengeluaran - $pendapatan;

        return $pendapatan;
    }

    public function setorLaporanHarian($id)
    {
        $kasir = $this->ModelUser->getSessionUserLogin();

        $laporan = $this->ModelLaporanHarian->get_by_id($id);

        $kru_dinas_array = explode(",", $laporan->kru_dinas);
        $n_kru = count($kru_dinas_array);

        $pendapatan = $this->_hitungPendapatan($id);

        //hitung premi dan setoran kas 
        if ($n_kru == 2) { //bus patas
            $premi_sopir = $pendapatan * 12 / 100;
            $premi_kondektur = $pendapatan * 9 / 100;
            $premi = (array) $premi_sopir;
            array_push($premi, $premi_kondektur);
            $premi_atas = $pendapatan * 9 / 100;
            $setoran = $pendapatan - $premi_atas;
        } else { //bus ekonomi
            $premi_sopir = $pendapatan * 10 / 100;
            $premi_kondektur = $pendapatan * 7 / 100;
            $premi_kernet = $pendapatan * 4 / 100;
            $premi = (array) $premi_sopir;
            array_push($premi, $premi_kondektur, $premi_kernet);
            $premi_atas = $pendapatan * 9 / 100;
            $setoran = $pendapatan - $premi_atas;
        }

        //insert laporan harian
        $data = array(
            'status_setor' => 1,
            'kasir_id' => $kasir['id'],
            'tanggal_setor' => date('Y-m-d H:i:s'),
            'setoran_kas' => $setoran
        );
        $where = array(
            'id' => $id
        );
        $this->ModelLaporanHarian->updateLaporanHarian($where, $data);

        //insert pembayaran
        $data = array(
            'tipe_pembayaran' => 0,
            'pembayar_id' => $kru_dinas_array[1],
            'penerima_id' => $kasir['id'],
            'tanggal_pembayaran' => date('Y-m-d H:i:s'),
            'nominal' => $setoran,
            'id_lh' => $id
        );
        $this->ModelPembayaran->tambahPembayaran($data);

        //insert premi
        $i = 0;
        foreach ($kru_dinas_array as $kru) {
            $data = array(
                'kru_id' => $kru,
                'laporan_harian_id' => $id,
                'status_ambil' => 0,
                'kasir_id' => $kasir['id'],
                'nominal_premi' => $premi[$i]
            );
            $this->ModelPembayaranPremi->tambahPremi($data);
            $i++;
        }

        echo json_encode(array("status" => TRUE));
    }

    public function bayarPremi()
    {
        $id = $this->input->post('id');
        $setor = $this->input->post('setor');
        $nominal = $this->input->post('nominal');
        $waktu_setor = new DateTime($setor);
        $bulan_setor = $waktu_setor->format('Y-m-d H:i:s');

        $kasir = $this->ModelUser->getSessionUserLogin();

        //update premi setelah bayar
        $data = array(
            'status_ambil' => 1,
            'kasir_id' => $kasir['id'],
            'tanggal_ambil' => date('Y-m-d H:i:s')
        );
        $where = array(
            'kru_id' => $id,
            'setor' => $setor,
        );
        $this->ModelPembayaranPremi->updatePremi($where, $data);

        //insert pembayaran
        $data = array(
            'tipe_pembayaran' => 1,
            'pembayar_id' => $kasir['id'],
            'penerima_id' => $id,
            'tanggal_pembayaran' => date('Y-m-d H.i.s'),
            'bulan_setor' => $bulan_setor,
            'nominal' => $nominal
        );
        $this->ModelPembayaran->tambahPembayaran($data);

        echo json_encode(array("status" => TRUE));
    }

    public function lihatDaftarPembayaranPremi()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Pembayaran';
        $data['title_dr'] = '&nbsp; Premi';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kasir/lihatdaftarpembayaranpremi', $data);
        $this->load->view('templates/footer');
    }

    public function daftarPembayaranPremi()
    {
        $kru = $this->ModelPembayaranPremi->get_id_kru();
        $idd = 0;

        $data = array();
        foreach ($kru as $k) {
            $premi = $this->ModelPembayaranPremi->get_by_kru_id($k->kru_id, 0);
            $bulan = $this->ModelPembayaranPremi->get_bulan($k->kru_id);

            foreach ($bulan as $b) {
                $nominal = 0;
                foreach ($premi as $p) {
                    $waktu_setor = new DateTime($p->tanggal_setor);
                    $t = $waktu_setor->format('Y-m');
                    if ($t == $b["DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`, '%Y-%m')"]) {
                        $nominal += $p->nominal_premi;
                    } else {
                        continue;
                    }
                }
                $row = array();

                $kru_atr = $this->ModelUser->get_by_id($k->kru_id);
                //kondektur
                $row[] = '<div style="text-align: left;"><span>' . $kru_atr->nama . '</span><br><small> <strong>(' . $kru_atr->nip . ')</strong></small></div>';

                //tanggalsetor
                $row[] = cust_date_indo($b["DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`, '%Y-%m')"]);

                //nominal
                $row[] = '<div style="text-align: right;"><strong>Rp. ' . number_format($nominal, 2, ",", ".") . '</strong>';

                //html aksi
                $row[] = '<span class="badge badge-warning"><i class="fas fa-fw fa-exclamation"></i> Belum Diambil</span>';
                $row[] = '<a href="' . base_url('assets/img/profile/' . $kru_atr->fotoprofil) . '" target="_blank" class="badge badge-success" tittle="Detail" onclick="setor(event, ' . $idd . ')" id="foto' . $idd . '">Bayar</a><input type="hidden" value="' . number_format($nominal, 2, ",", ".") . '" name="sweet_nominal' . $idd . '" /><input type="hidden" value="' . $b["DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`, '%Y-%m')"] . '" name="sweet_setor' . $idd . '" /><input type="hidden" value="' . $nominal . '" name="sweet_nominal_simpan' . $idd . '" /><input type="hidden" value="' . $k->kru_id . '" name="sweet_kru_id' . $idd . '" />';
                $idd++;
                $data[] = $row;
            }
        }

        $list = $this->ModelPembayaran->get();

        $idd = 0;
        foreach ($list as $k) {
            if ($k->tipe_pembayaran == 1) {
                $row = array();

                $kru_atr = $this->ModelUser->get_by_id($k->penerima_id);
                //kondektur
                $row[] = '<div style="text-align: left;"><span>' . $kru_atr->nama . '</span><br><small> <strong>(' . $kru_atr->nip . ')</strong></small></div>';

                //tanggalsetor
                $row[] = cust_date_indo($k->bulan_setor);

                //nominal
                $row[] = '<div style="text-align: right;"><strong>Rp. ' . number_format($k->nominal, 2, ",", ".") . '</strong>';

                //html aksi
                $row[] = '<span class="badge badge-success"><i class="fas fa-fw fa-check"></i> Sudah Diambil </span><br>' . date_format(date_create($k->tanggal_pembayaran), 'H:i') . ' <br> ' . longdate_indo($k->tanggal_pembayaran);

                $row[] = '<a href="' . site_url("controllerkasir/lihatdetailpembayaran/")  . $k->id . '" class="badge badge-info" tittle="Detail">Detail</a>';
                $data[] = $row;
            }
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelPembayaranPremi->count_all(),
            "recordsFiltered" => $this->ModelPembayaranPremi->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function lihatRiwayatPembayaran()
    {
        $data['ip'] = $_SERVER['HTTP_HOST'];
        //memberi judul halaman
        $data['title'] = 'Pembayaran';
        $data['title_dr'] = '&nbsp; Riwayat Pembayaran';
        //mengambil seluruh data user yang login
        $data['user'] = $this->ModelUser->getSessionUserLogin();
        //penyusunan view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kasir/lihatriwayatpembayaran', $data);
        $this->load->view('templates/footer');
    }

    public function daftarRiwayatPembayaran()
    {
        $list = $this->ModelPembayaran->get_datatables();
        $data = array();

        foreach ($list as $record) {

            $row = array();
            $penerima = $this->ModelUser->get_by_id($record->penerima_id);

            if ($record->role_id == 31)
                $posisi = 'Sopir';
            else if ($record->role_id == 32)
                $posisi = 'Kondektur';
            else if ($record->role_id == 33)
                $posisi = 'Kernet';
            else
                $posisi = 'Kasir';

            if ($penerima->role_id == 31)
                $posisi2 = 'Sopir';
            else if ($penerima->role_id == 32)
                $posisi2 = 'Kondektur';
            else if ($penerima->role_id == 33)
                $posisi2 = 'Kernet';
            else
                $posisi2 = 'Kasir';

            //html jenis pembayaran
            if ($record->tipe_pembayaran == 0)
                $row[] = '<span class="badge badge-success">Setoran Kas</span>';
            else
                $row[] = '<span class="badge badge-warning">Premi</span>';

            //tanggalselesai
            $row[] = date_format(date_create($record->tanggal_pembayaran), 'H:i') . ' <br> ' . longdate_indo($record->tanggal_pembayaran);

            //penyetor
            $row[] = '<div style="text-align: left;"><span>' . $record->nama . '</span><br><small> <strong>' . $record->nip . ' - ' . $posisi . '</strong></small></div>';

            //penerima
            $row[] = '<div style="text-align: left;"><span>' . $penerima->nama . '</span><br><small> <strong>' . $penerima->nip . ' - ' . $posisi2 . '</strong></small></div>';

            //nominal
            $row[] = '<div style="text-align: right;"><strong>Rp. ' . number_format($record->nominal, 2, ",", ".") . '</strong>';

            //html aksi
            $row[] = '<a href="' . site_url("controllerkasir/lihatdetailpembayaran/")  . $record->id . '" class="badge badge-info" tittle="Detail">Detail</a>';


            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ModelPembayaran->count_all(),
            "recordsFiltered" => $this->ModelPembayaran->count_filtered(),
            "data" => $data,
        );
        //menampilkan dalam format json
        echo json_encode($output);
    }

    public function lihatDetailPembayaran($id)
    {
        function penyebut($nilai)
        {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                $temp = penyebut($nilai - 10) . " belas";
            } else if ($nilai < 100) {
                $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
            } else if ($nilai < 1000000000000000) {
                $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
            }
            return $temp;
        }

        function terbilang($nilai)
        {
            if ($nilai < 0) {
                $hasil = "minus " . trim(penyebut($nilai));
            } else {
                $hasil = trim(penyebut($nilai));
            }
            return $hasil;
        }
        $pemb = $this->ModelPembayaran->get_by_id($id);
        $data['t'] = terbilang($pemb->nominal);

        $data['p'] = $this->ModelPembayaran->get_by_id($id);

        $this->load->view('user/unduhdetailpembayaran', $data);
    }
}
