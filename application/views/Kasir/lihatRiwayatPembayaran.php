<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Setoran Kas <?= date('Y') ?></h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow mb-3">

        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabelSP" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col">Jenis Pembayaran</th>
                                    <th scope="col">Tanggal Pembayaran</th>
                                    <th scope="col">Penyetor</th>
                                    <th scope="col">Penerima</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Aksi</th>
                                    </th>
                            </thead>
                            <tbody id="show_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir Tabel -->
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Formulir Bus</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <input type="hidden" value="" name="action" />
                    <div class="form-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>NIP</strong></label>
                            <div class="col-md-9">
                                <input name="tambahKruJalanNip" placeholder="NIP" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Nama</strong></label>
                            <div class="col-md-9">
                                <input name="tambahKruJalanNama" placeholder="Nama" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Nomor HP</strong></label>
                            <div class="col-md-9">
                                <input name="tambahKruJalanNomorHp" placeholder="Nomor HP" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Posisi</strong></label>
                            <div class="col-md-9">
                                <select name="tambahKruJalanPosisi" class="form-control">
                                    <option value="">--Pilih Posisi--</option>
                                    <option value=31>Sopir</option>
                                    <option value=32>Kondektur</option>
                                    <option value=33>Kernet</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row" id="photo-preview">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Foto</strong></label>
                            <div class="col-md-9">
                                (Belum ada foto)
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right" id="label-photo"><strong>Unggah Foto</strong></label>
                            <div class="col-md-9">
                                <input name="photo" type="file">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><i class="fas fa-download"></i>&nbsp; Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Script -->

<script type="text/javascript">
    var save_method; //untuk sting save method
    var table;
    var base_url = '<?php echo base_url(); ?>';

    //menampilkan data ke dalam tabel
    $(document).ready(function() {
        $('#tabelSP').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'autoWidth': false,
            'order': [
                [1, "desc"]
            ],
            //mengganti teks standar pada datatabel
            'language': {
                "processing": 'Sedang memproses data...',
                "search": "Telusuri",
                "lengthMenu": "Menampilkan _MENU_ baris per halaman",
                "zeroRecords": "Mohon maaf, data tidak ditemukan.",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada baris data yang tersedia.",
                "infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya"
                }
            },
            'ajax': {
                'url': '<?= base_url() ?>controllerkasir/daftarriwayatpembayaran'
            },
            'dom': "<'row'<'col-auto mr-auto'l><'col-auto'f>><'row mb-2'<'col-auto mr-auto'B>>" + "<'row'<'col-sm-12'tr>><'row mt-2'<'col-auto mr-auto'i><'col-auto searchStyle'p>>",
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: 'Riwayat Pembayaran Keuangan Kru Jalan PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'pdfHtml5',
                    download: 'open',
                    exportOptions: {
                        columns: ':visible',

                    },
                    title: 'Riwayat Pembayaran Keuangan Kru Jalan PT. Akas Mila Sejahtera',

                },
                {
                    extend: 'print',
                    messageTop: 'Riwayat Pembayaran Keuangan Kru Jalan PT. Akas Mila Sejahtera, Probolinggo.',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            scrollX: true,
            scrollCollapse: true,
            autoWidth: true,
            paging: true,
            columnDefs: [{
                    "width": "150px",
                    "targets": [0, 1, 2, 3, 4]
                },
                {
                    "width": "40px",
                    "targets": [5]
                }, {
                    visible: false
                }
            ]
        });
        //datepicker
        // $('.datepicker').datepicker({
        //     autoclose: true,
        //     todayHighlight: true,
        //     todayBtn: true,
        //     todayHighlight: true,
        //     format: 'mm/dd/yyyy'
        // });

        //menginisiasi event input/textarea/select ketika berubah nilai, menghapus class is-invalid dan menghapus teks pada help-block
        $("input").change(function() {
            $(this).removeClass('is-invalid');
            $(this).next().empty();
        });
        $("textarea").change(function() {
            $(this).removeClass('is-invalid');
            $(this).next().empty();
        });
        $("select").change(function() {
            $(this).removeClass('is-invalid');
            $(this).next().empty();
        });
    });

    function detail_lh(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';
        $('#form')[0].reset(); // mereset form modal
        $('.form-group').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error


        //mengambil data menggunakan ajax
        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/tampil_sp') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="action"]').val("update");
                $('[name="tambahKruJalanNip"]').attr('readonly', true);
                $('[name="tambahKruJalanNip"]').val(data.nip);
                $('[name="tambahKruJalanNama"]').val(data.nama);
                $('[name="tambahKruJalanNomorHp"]').val(data.nomor_hp);
                $('[name="tambahKruJalanPosisi"]').val(data.role_id);
                $('#modal_form').modal('show'); // menampilkan modal bootstrap ketika selesai mengambil data
                $('.modal-title').text('Edit Data Kru Jalan'); // memberikan judul pada modal bootstrap
                $('#photo-preview').show(); // menampilkan foto yang sudah diunggah

                if (data.fotoprofil) {
                    $('#label-photo').text('Ganti Foto'); // label unggah foto
                    $('#photo-preview div').html('<img src="' + base_url + '/assets/img/profile/' + data.fotoprofil + '" class="img-responsive mr-3 d-inline-block" style="max-width: 300px; max-length: 300px;">'); // menampilkan foto
                    //$('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="' + data.fotoprofil + '" /> Hapus foto'); // menghapus foto
                } else {
                    $('#label-photo').text('Upload Foto'); // label unggah foto
                    $('#photo-preview div').text('(Belum ada foto)');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { //jika terjadi kesalahan maka akan menampilkan modal sweetalert
                Swal.fire({
                    title: "Ooops...",
                    text: "Terjadi kesalahan dalam mengambil data",
                    icon: 'error',
                    scrollbarPadding: false
                });
            }
        });
    }

    function setor(id, nominal) { //ketika menekan tombol hapus maka akan muncul modal sweetalert

        Swal.fire({
            title: 'Peringatan!',
            html: "Pastikan anda akan menyerahkan uang sebesar <br> Rp. <b></b>",
            icon: 'warning',
            scrollbarPadding: false,
            showCancelButton: true,
            confirmButtonColor: '#19b538',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, bayar!',
            cancelButtonText: 'Kembali',
            onBeforeOpen: () => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = nominal
                    }
                }
            }
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('controllerkasir/setorlaporanharian') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        //jika sukses maka reload tabel dan menampilkan modal sweetalert

                        reload_table();
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Berhasil menghapus data petugas kontrol",
                            icon: 'success',
                            scrollbarPadding: false
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) { //jika terjadi error maka akan menampilkan modal sweetalert
                        Swal.fire({
                            title: "Ooops...",
                            text: "Terjadi kesalahan dalam menghapus data",
                            icon: 'error',
                            scrollbarPadding: false
                        });
                    }
                });
            }
        })
    }

    // mereload datatable
    function reload_table() {
        $('#tabelSP').DataTable().ajax.reload(); //reload datatable ajax 
    }
</script>

<!-- Area Chart -->
<script type="text/javascript">
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    <?php
    $query = "SELECT DISTINCT DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`,'%Y-%m') FROM `t_laporan_harian` 
                    WHERE `status_setor` = 1
                    ORDER BY `t_laporan_harian`.`tanggal_setor` ASC";
    $bulan_setor = $this->db->query($query)->result_array();

    $query = "SELECT * FROM `t_laporan_harian` 
                    WHERE `status_setor` = 1
                    ORDER BY `t_laporan_harian`.`tanggal_setor` ASC";
    $laporan_harian = $this->db->query($query)->result_array();

    $bulan_array = array();
    $nominal_array = array();
    foreach ($bulan_setor as $b) {
        $nominal = 0;
        $bulan = "";
        foreach ($laporan_harian as $l) {
            $waktu_setor = new DateTime($l['tanggal_setor']);
            $t = $waktu_setor->format('Y-m');
            $tahun = $waktu_setor->format('Y');
            if ($tahun == date('Y')) {
                if ($t == $b["DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`,'%Y-%m')"]) {
                    $nominal += $l['setoran_kas'];
                    $bulan = konversi($l['tanggal_setor']);
                } else {
                    continue;
                }
            }
        }
        array_push($bulan_array, $bulan);
        array_push($nominal_array, $nominal);
    }

    function konversi($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $bulan = bln($pecah[1]);
        return  $bulan;
    }
    function bln($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Ags";
                break;
            case 9:
                return "Sep";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }
    }

    ?>

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($bulan_array) ?>,
            datasets: [{
                label: "Setoran Kas",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: <?= json_encode($nominal_array) ?>,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return 'Rp ' + number_format(value);
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
</script>