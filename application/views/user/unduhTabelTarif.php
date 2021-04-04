<!DOCTYPE html>
<html>

<head>
    <title>cetak tabel tarif - web smdlh</title>
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/bootstrap.css" rel="stylesheet">
    <style type="text/css" media="print">
        @media print {
            @page {
                size: landscape
            }
        }
    </style>
</head>

<body class="bg-gradient-login100" style="background-image: url('<?php echo base_url(); ?>assets/img/bg/bgtabeltarif.png'); background-size: 25%; background-repeat: no-repeat; background-position: 95% 20%; ">



    <?php
    $result = $this->ModelTrayek->get_by_id_array($id["id"]); // mengambil seluruh data dari tabel trayek
    $tarif = $this->ModelTrayek->get_tarif_array($id["id"]); // mengambil kolom tarif dari tabel tarif
    $pos = $this->ModelTrayek->get_pos_array($id["id"]); // mengambil kolom posnaik dari tabel tarif untuk melihat pos apa saja yang dilalui dalam trayek pergi

    $single_tarif = $this->ModelTrayek->get_pos_by_id($id["id"]); // mengambil data tarif trayek dari tabel tarif untuk kemudian ditampilkan satu per satu menggunakan foreach
    $posturun = array_reverse($pos); // membalik urutan array $pos untuk menampilkan trayek pulang

    $n_pos = count($pos); // jumlah pos yang dilalui dalam trayek
    $poslewat_jadi = '';
    foreach ($result as $row) {
        $id_tarif = 1; //indeks permulaan untuk mengisi trayek pergi

        // menyusun html
        // awal tabel trayek pergi
        $poslewat_jadi .= '<tr>'; // awal tabel trayek pergi 

        // looping segi tiga untuk menampilkan data trayek pergi
        for ($i = 0; $i < $n_pos; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                $single_tarif = $this->ModelTrayek->get_single_tarif($id["id"], $id_tarif); // mengambil satu sel dari kolom tarif pada tabel tarif

                $poslewat_jadi .= '<td class="text-dark"><h4>' . number_format($single_tarif->tarif, 0, ",", ".")  . '<h4></td>'; // isi tabel ke samping

                $id_tarif++;
            }
            $shift = array_shift($pos); // mengambil elemen pertama dari array, elemen pertama tersebut kemudian dihapus dari array
            $poslewat_jadi .= '<td class="text-dark"><h4><strong>' . $shift["posnaik"] . '</strong></h4></td></tr>'; // pos yang dilalui | membuat baris baru
        }
        $poslewat_jadi .= ''; // akhir tabel trayek pergi

        // $poslewat_jadi .= '<tr>'; // awal tabel trayek pulang

        // // looping segi tiga untuk menampilkan data trayek pulang
        // for ($i = 0; $i < $n_pos; $i++) {
        //     for ($j = 1; $j <= $i; $j++) {
        //         $single_tarif = $this->ModelTrayek->get_single_tarif($id["id"], $id_tarif); // mengambil satu sel dari kolom tarif pada tabel tarif

        //         $poslewat_jadi .= '<td>' . $single_tarif->tarif . '</td>'; // isi tabel ke samping

        //         $id_tarif++;
        //     }
        //     $shift = array_shift($posturun); // mengambil elemen pertama dari array, elemen pertama tersebut kemudian dihapus dari array
        //     $poslewat_jadi .= '<td><strong>' . $shift["posnaik"] . '</strong></td></tr><br />'; // pos yang dilalui | membuat baris baru
        // }
        // $poslewat_jadi .= '</tbody></table>'; // akhir tabel trayek pulang
    }
    ?>

    <?php
    $trayek = $this->ModelTrayek->get_by_id($id["id"]);
    ?>

    <center>


        <h2 class="pt-5"><strong>TABEL TARIF PT. AKAS MILA SEJAHTERA</strong></h2>
        <h4><strong><?= $trayek->posawal ?> - <?= $trayek->posakhir ?> PP.</strong></h4>
        <h4><strong><?= $trayek->kelas ?></strong></h4>

    </center>
    <div class="table-responsive">
        <table class="table table-bordered mb-2 mt-2" id="dynamic_field">

            <?php echo $poslewat_jadi ?>
        </table>
    </div>
    <center>
        <h4><strong></strong></h4>
        <h5>SARAN & PENGADUAN</h5>
        <h3><strong>082-234-909090</strong></h3>
        <h5>(SMS/WA)</h5>
    </center>
    <script>
        window.print();
    </script>

    <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>
</body>

</html>