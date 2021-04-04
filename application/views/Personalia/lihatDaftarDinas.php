<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <!-- Tabel -->
    <div class="card shadow mb-3">

        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabelBus" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col" align="center">BUS</th>
                                    <th scope="col" align="center">Trayek</th>
                                    <th scope="col" align="center">Sopir</th>
                                    <th scope="col" align="center">Kondektur</th>
                                    <th scope="col" align="center">Kernet</th>
                                    <th scope="col" align="center">Status</th>
                                </tr>
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
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Kelas</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasKelas" id="tambahDinasKelas" class="form-control">
                                    <option value="">--Pilih Kelas--</option>
                                    <option value="Patas">Patas</option>
                                    <option value="Ekonomi">Ekonomi</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Nopol</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasNopol" id="tambahDinasNopol" class="form-control">
                                    <option value="">--Pilih Nopol--</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Trayek</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasTrayek" id="tambahDinasTrayek" class="form-control">
                                    <option value="">--Pilih Trayek--</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label"></label>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table mb-0" id="dynamic_field">

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Sopir</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasSopir" id="tambahDinasSopir" class="form-control">
                                    <option value="">--Pilih Sopir--</option>

                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Kondektur</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasKondektur" id="tambahDinasKondektur" class="form-control">
                                    <option value="">--Pilih Kondektur--</option>

                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Kernet</strong></label>
                            <div class="col-md-9">
                                <select name="tambahDinasKernet" id="tambahDinasKernet" class="form-control">
                                    <option value="">--Pilih Kernet--</option>

                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label"></label>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table mb-0" id="dynamic_field">

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body" id="form_body">
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

        $('#tabelBus').DataTable({

            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'autoWidth': false,
            'order': [
                [5, "desc"]
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
                'url': '<?= base_url() ?>controllerpersonalia/daftardinas',
                "type": "POST"
            },
            'dom': "<'row'<'col-auto mr-auto'l><'col-auto'f>><'row mb-2'<'col-auto mr-auto'B>>" + "<'row'<'col-sm-12'tr>><'row mt-2'<'col-auto mr-auto'i><'col-auto searchStyle'p>>",
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: 'Daftar Dinas PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'pdfHtml5',
                    download: 'open',
                    exportOptions: {
                        columns: ':visible',

                    },
                    title: 'Daftar Dinas PT. Akas Mila Sejahtera',

                },
                {
                    extend: 'print',
                    messageTop: 'Daftar dinas bus dan kru jalan PT. Akas Mila Sejahtera, Probolinggo.',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            columnDefs: [{
                visible: false
            }]
        });

        //datepicker
        // $('.datepicker').datepicker({
        //     autoclose: true,
        //     format: "yyyy-mm-dd",
        // todayHighlight: true,
        // orientation: "top auto",
        // todayBtn: true,
        // todayHighlight: true,
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

    //menampilkan foto dari datatable ke dalam modal sweetalert dengan resolusi asli
    function lihat_foto(event, id) {
        event.preventDefault();
        var url = document.getElementById("foto" + id).getAttribute("href");
        var nama = $('[name="sweet_nama' + id + '"]').val();
        var posisi = $('[name="sweet_posisi' + id + '"]').val();
        Swal.fire({
            title: nama,
            text: posisi,
            imageUrl: url,
            imageAlt: 'Custom image',
            showClass: {
                popup: 'animated fadeInUp faster'
            },
            hideClass: {
                popup: 'animated fadeOutDown faster'
            }
        })
    }


    function ambilData(tambahDinasKelas, save_method) {
        $('#btnGanti').attr('disabled', true); //menon-aktifkan tombol

        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/getKrubyStatus'); ?>",
            method: "POST",
            data: {},
            async: true,
            dataType: 'json',
            success: function(data) {
                var htmlSopir = '';
                var htmlKondektur = '';
                var htmlKernet = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    if (parseInt(data[i].role_id) == 31) {
                        htmlSopir += '<option value=' + data[i].id + '>' + data[i].nama + '</option>';
                    } else if (parseInt(data[i].role_id) == 32) {
                        htmlKondektur += '<option value=' + data[i].id + '>' + data[i].nama + '</option>';
                    } else {
                        htmlKernet += '<option value=' + data[i].id + '>' + data[i].nama + '</option>';
                    }
                }

                if (save_method == 'add') {
                    $('#tambahDinasSopir').html(htmlSopir);
                    $('#tambahDinasKondektur').html(htmlKondektur);
                    $('#tambahDinasKernet').html(htmlKernet);
                } else {
                    $('#editDinasSopir').append(htmlSopir);
                    $('#editDinasKondektur').append(htmlKondektur);
                    $('#editDinasKernet').append(htmlKernet);
                }
            }
        });

        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/getBusbyKelas'); ?>",
            method: "POST",
            data: {
                tambahDinasKelas: tambahDinasKelas
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    if (data[i].trayek_id == 0) {
                        html += '<option value=' + data[i].id + '>' + data[i].nopol + ' - ' + data[i].mesin + ' - ' + data[i].tahun + '</option>';
                    }
                }

                if (save_method == 'add') {
                    $('#tambahDinasNopol').html(html);

                    if (data[0].kelas == 'Patas') {
                        $('[name="tambahDinasKernet"]').attr('disabled', true);

                    } else {
                        $('[name="tambahDinasKernet"]').attr('disabled', false);
                        $('#tambahDinasKernet').html(htmlKernet);
                    }
                } else {
                    $('#editDinasNopol').append(html);

                    if (data[0].kelas == 'Patas') {
                        $('[name="editDinasKernet"]').attr('disabled', true);

                    } else {
                        $('[name="editDinasKernet"]').attr('disabled', false);
                        $('#editDinasKernet').append(htmlKernet);
                    }
                }
            }
        });

        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/getTrayek'); ?>",
            method: "POST",
            data: {
                tambahDinasKelas: tambahDinasKelas
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].id + '>' + data[i].posawal + ' - ' + data[i].posakhir + ' <strong>(' + data[i].kode + ')</strong></option>';
                }

                if (save_method == 'add') {
                    $('#tambahDinasTrayek').html(html);
                } else {
                    $('#editDinasTrayek').append(html);
                }

            }
        });
        return false;
    }

    function save() {
        $('#btnSave').text('Menyimpan...'); //mengganti teks tombol
        $('#btnSave').attr('disabled', true); //menon-aktifkan tombol
        $('#btnGanti').attr('disabled', true); //menon-aktifkan tombol
        var url;
        var teksberhasil;

        if (save_method == 'add') { //seleksi string save method dari modal bootstrap
            url = "<?php echo site_url('controllerpersonalia/tambahdinas') ?>";
            teksberhasil = "Berhasil menambah data dinas baru";
        } else {
            url = "<?php echo site_url('controllerpersonalia/editdinas') ?>";
            teksberhasil = "Berhasil mengedit data dinas";
        }

        // menambahkan data ke dalam database menggunakan ajax
        var formData = new FormData($('#form')[0]);
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(data) {
                if (data.status) //jika sukses maka sembunyikan modal, reload tabel, dan menampilkan modal sweetalert
                {

                    $('#modal_form').modal('hide');
                    reload_table();
                    Swal.fire({
                        title: "Berhasil!",
                        text: teksberhasil,
                        icon: 'success',
                        scrollbarPadding: false
                    });
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid'); //menambahkan kelas is-invalid
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //memilih kelas span help-block class untuk memasukkan string error
                    }
                }
                $('#btnSave').text('Simpan'); //menganti teks tombol
                $('#btnSave').attr('disabled', false); //mengaktifkan kembali tombol
            },
            error: function(jqXHR, textStatus, errorThrown) { //jika terjadi error maka akan menampilkan modal sweetalert
                Swal.fire({
                    title: "Ooops...",
                    text: "Terjadi kesalahan dalam menyimpan data",
                    icon: 'error',
                    scrollbarPadding: false
                });
                $('#btnSave').text('Simpan'); //mengganti teks tombol
                $('#btnSave').attr('disabled', false); //mengaktifkan tombol
            }
        });
    }

    function edit_dinas(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';
        $('#form')[0].reset(); // mereset form modal
        $('.form-group').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error
        $('#tambahDinasSopir').html('<option value="">--Pilih Sopir--</option>');
        $('#tambahDinasKondektur').html('<option value="">--Pilih Kondektur--</option>');
        $('#tambahDinasKernet').html('<option value="">--Pilih Kernet--</option>');
        $('#tambahDinasNopol').html('<option value="">--Pilih Bus--</option>');
        $('#tambahDinasTrayek').html('<option value="">--Pilih Trayek--</option>');
        $('[name="tambahDinasKernet"]').attr('disabled', false);
        $('[name="tambahDinasKelas"]').attr('readonly', true);
        // mereset field form ketika terjadi pembatalan pengisian
        $('#editDinasSopir').html('<option value="">--Pilih Sopir--</option>');
        $('#editDinasKondektur').html('<option value="">--Pilih Kondektur--</option>');
        $('#editDinasKernet').html('<option value="">--Pilih Kernet--</option>');
        $('#editDinasNopol').html('<option value="">--Pilih Bus--</option>');
        $('#editDinasTrayek').html('<option value="">--Pilih Trayek--</option>');
        //$('[name="editDinasKernet"]').attr('disabled', false);
        $('[name="editDinasKelas"]').attr('disabled', false);
        $('#btnGanti').attr('disabled', false); //menon-aktifkan tombol
        $('#form_body').html('');
        $('#btnSave').attr('disabled', true); //menon-aktifkan tombol


        $("#btnGanti").click(function() {
            // mereset field form ketika terjadi pembatalan pengisian
            $('#editDinasSopir').html('<option value="">--Pilih Sopir--</option>');
            $('#editDinasKondektur').html('<option value="">--Pilih Kondektur--</option>');
            $('#editDinasKernet').html('<option value="">--Pilih Kernet--</option>');
            $('#editDinasNopol').html('<option value="">--Pilih Bus--</option>');
            $('#editDinasTrayek').html('<option value="">--Pilih Trayek--</option>');
            //$('[name="editDinasKernet"]').attr('disabled', false);
            $('[name="editDinasKelas"]').attr('disabled', false);
            $('#btnSave').attr('disabled', false); //menon-aktifkan tombol

            var save_method = 'get';
            var tambahDinasKelas = $('[name="tambahDinasKelas"]').val();
            ambilData(tambahDinasKelas, save_method);
        });


        //mengambil data menggunakan ajax 
        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/tampil_editdinas') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('[name="id"]').val(data[i].id);
                    $('[name="action"]').val("update");
                    $('[name="tambahDinasKelas"]').val(data[i].kelas);

                    html = '<option value=' + data[i].bus_id + '>' + data[i].nopol + ' - ' + data[i].mesin + ' - ' + data[i].tahun + '</option>';
                    $('#tambahDinasNopol').html(html);

                    html = '<option value=' + data[i].trayek_id + '>' + data[i].posawal + ' - ' + data[i].posakhir + ' <strong>(' + data[i].kode + ')</strong></option>';
                    $('#tambahDinasTrayek').html(html);


                    if (data[i].kelas == 'Ekonomi') {
                        if (parseInt(data[i].role_id) == 31) {
                            htmlSopir = '<option value=' + data[i].id_kru + ' >' + data[i].nama + '</option>';
                            $('#tambahDinasSopir').html(htmlSopir);

                        } else if (parseInt(data[i].role_id) == 32) {
                            htmlKondektur = '<option value=' + data[i].id_kru + ' >' + data[i].nama + '</option>';
                            $('#tambahDinasKondektur').html(htmlKondektur);

                        } else {
                            htmlKernet = '<option value=' + data[i].id_kru + ' >' + data[i].nama + '</option>';
                            $('#tambahDinasKernet').html(htmlKernet);
                        }

                    } else {
                        if (parseInt(data[i].role_id) == 31) {
                            htmlSopir = '<option value=' + data[i].id_kru + ' >' + data[i].nama + '</option>';
                            $('#tambahDinasSopir').html(htmlSopir);

                        } else if (parseInt(data[i].role_id) == 32) {
                            htmlKondektur = '<option value=' + data[i].id_kru + ' >' + data[i].nama + '</option>';
                            $('#tambahDinasKondektur').html(htmlKondektur);

                        } else {
                            $('#tambahDinasKernet').attr('disabled', true);
                        }

                    }
                }
                $('#modal_form').modal('show'); // menampilkan modal bootstrap ketika selesai mengambil data
                $('.modal-title').text('Edit Data Dinas'); // memberikan judul pada modal bootstrap

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

        return false;
    }

    function reload_table() {
        $('#tabelBus').DataTable().ajax.reload(); //reload datatable ajax 
    }
</script>