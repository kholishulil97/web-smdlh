<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <!-- Tabel -->
    <div class="card shadow mb-3">
        <div class="card-header">
            <a href="" class="btn btn-primary" data-toggle="modal" onclick="tambah_krujalan()"><i class="fas fa-fw fa-plus"></i>&nbsp; Tambah Kru Jalan</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabelKruJalan" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Nomor HP</th>
                                    <th scope="col">Posisi</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Status</th>
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
                    <input type="hidden" value="" name="status_dinas" />
                    <input type="hidden" value="" name="id_kru" />
                    <input type="hidden" value="" name="role_id" />
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
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Alamat</strong></label>
                            <div class="col-md-9">
                                <input name="tambahKruJalanAlamat" placeholder="Alamat" class="form-control" type="text">
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

                        <div class="float-right">
                            <button type="button" id="btnGantiBus" class="btn btn-outline-secondary">&nbsp;<i class="fas fa-angle-double-down"></i>&nbsp;</button>
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
        $('#tabelKruJalan').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'autoWidth': false,
            'order': [
                [4, "asc"]
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
                'url': '<?= base_url() ?>controllerpersonalia/daftarkrujalan'
            },
            'dom': "<'row'<'col-auto mr-auto'l><'col-auto'f>><'row mb-2'<'col-auto mr-auto'B>>" + "<'row'<'col-sm-12'tr>><'row mt-2'<'col-auto mr-auto'i><'col-auto searchStyle'p>>",
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: 'Daftar Kru Jalan PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'pdfHtml5',
                    download: 'open',
                    exportOptions: {
                        columns: ':visible',

                    },
                    title: 'Daftar Kru Jalan PT. Akas Mila Sejahtera',

                },
                {
                    extend: 'print',
                    messageTop: 'Daftar keseluruhan kru jalan PT. Akas Mila Sejahtera, Probolinggo.',
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

    function tambah_krujalan() {
        save_method = 'add';
        $('#form')[0].reset(); // mereset form pada modal
        $('.form-control').removeClass('is-invalid'); // menghilangkan class error
        $('.help-block').empty(); // menghilangkan string error
        $('#modal_form').modal('show'); // menampilkan modal bootstrap
        $('.modal-title').text('Tambah Kru Jalan'); // memberi judul modal bootstrap
        $('[name="tambahKruJalanNip"]').attr('readonly', false); //menjadikan field nopol aktif kembali
        $('[name="action"]').val("add");
        $('#photo-preview').hide(); // menyembunyikan photo preview
        $('#label-photo').text('Unggah Foto'); // label unggah photo
        $('#btnGantiBus').attr('disabled', true); //menon-aktifkan tombol
    }

    function save() {
        $('#btnSave').text('Menyimpan...'); //mengganti teks tombol
        $('#btnSave').attr('disabled', true); //menon-aktifkan tombol
        var url;
        var teksberhasil;

        if (save_method == 'add') { //seleksi string save method dari modal bootstrap
            url = "<?php echo site_url('controllerpersonalia/tambahkrujalan') ?>";
            teksberhasil = "Berhasil menambah data kru jalan baru";
        } else {
            url = "<?php echo site_url('controllerpersonalia/editkrujalan') ?>";
            teksberhasil = "Berhasil mengedit data kru jalan";
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

    function edit_krujalan(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';

        $('#form')[0].reset(); // mereset form modal
        $('.form-control').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error
        $('#btnGantiBus').attr('disabled', false); //menon-aktifkan tombol
        $('#form_body').html('');

        //mengambil data menggunakan ajax
        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/tampil_editkrujalan') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="status_dinas"]').val(data.status_dinas);
                $('[name="role_id"]').val(data.role_id);
                $('[name="action"]').val("update");
                $('[name="tambahKruJalanNip"]').attr('readonly', true);
                $('[name="tambahKruJalanNip"]').val(data.nip);
                $('[name="tambahKruJalanNama"]').val(data.nama);
                $('[name="tambahKruJalanAlamat"]').val(data.alamat);
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

        $("#btnGantiBus").click(function() {
            nama = $('[name="tambahKruJalanNama"]').val();
            status_dinas = $('[name="status_dinas"]').val();
            role_id = $('[name="role_id"]').val();
            $('#btnSave').attr('disabled', false); //menon-aktifkan tombol
            $('#btnGantiBus').attr('disabled', true); //menon-aktifkan tombol
            $('#btnGanti').attr('disabled', true); //menon-aktifkan tombol

            if (status_dinas == 1) {
                htmlBus =
                    '<div class="form-group row">' +
                    '<label class="control-label col-md-3 col-form-label"></label>' +
                    ' <div class="col-md-9">' +
                    '<div class="table-responsive">' +
                    ' <table class="table mb-0" id="dynamic_field">' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="form-group row">' +
                    '<label class="control-label col-md-3 col-form-label"></label>' +
                    ' <div class="col-md-9">' +
                    '<div class="table-responsive">' +
                    ' <table class="table mb-0" id="dynamic_field">' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +


                    '<div class="form-group row">' +
                    '<label class="control-label col-md-3 col-form-label text-right"><strong>Digantikan oleh</strong></label>' +
                    '<div class="col-md-9">' +
                    '<select name="tambahDinasBaru" id="tambahDinasBaru" class="form-control">' +
                    '<option value="">--Pilih Kru Jalan--</option>' +
                    '</select>' +
                    '<small>untuk kru jalan yang memiliki status dinas aktif, maka sebelum memutus kemitraan wajib dicarikan kru jalan pengganti</small>' +
                    '</div>' +
                    '</div>' +

                    '<div class="pb-2" style="text-align:center;">' +
                    '<button type="button" id="btnGanti" class="btn btn-danger" onclick="hapus_krujalan(\'' + id + '\', \'' + nama + '\')"><i class="fas fa-times"></i>&nbsp; Putus Mitra</button>' +
                    '</div>' +

                    '<div style="text-align:center;">' +
                    '<small>*Dengan memutus mitra kru jalan maka yang bersangkutan tidak dapat melakukan dinas lagi</small>' +
                    '</div>';
            } else {
                htmlBus =
                    '<div class="form-group row">' +
                    '<label class="control-label col-md-3 col-form-label"></label>' +
                    ' <div class="col-md-9">' +
                    '<div class="table-responsive">' +
                    ' <table class="table mb-0" id="dynamic_field">' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="form-group row">' +
                    '<label class="control-label col-md-3 col-form-label"></label>' +
                    ' <div class="col-md-9">' +
                    '<div class="table-responsive">' +
                    ' <table class="table mb-0" id="dynamic_field">' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +

                    '<div class="pb-2" style="text-align:center;">' +
                    '<button type="button" id="btnGanti" class="btn btn-danger" onclick="hapus_krujalan(\'' + id + '\', \'' + nama + '\')"><i class="fas fa-times"></i>&nbsp; Putus Mitra</button>' +
                    '</div>' +

                    '<div style="text-align:center;">' +
                    '<small>*Dengan memutus mitra kru jalan maka yang bersangkutan tidak dapat melakukan dinas lagi</small>' +
                    '</div>';
            }
            $('#form_body').html(htmlBus);

            ambilData(role_id);
        });
    }

    function ambilData(role_id_lama) {
        //$('#btnGanti').attr('disabled', true); //menon-aktifkan tombol

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

                if (role_id_lama == 31)
                    $('#tambahDinasBaru').html(htmlSopir);
                else if (role_id_lama == 32)
                    $('#tambahDinasBaru').html(htmlKondektur);
                else
                    $('#tambahDinasBaru').html(htmlKernet);
            }
        });
        return false;
    }

    // menghapus data dari database menggunakan ajax
    function hapus_krujalan(id, nama) { //ketika menekan tombol hapus maka akan muncul modal sweetalert
        Swal.fire({
            title: 'Apakah anda yakin?',
            html: "MEMUTUS MITRA kru jalan dgn nama <b></b>",
            icon: 'warning',
            scrollbarPadding: false,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, lakukan!',
            cancelButtonText: 'Kembali',
            onBeforeOpen: () => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = nama
                    }
                }
            }
        }).then((result) => {
            if (result.value) {
                // menambahkan data ke dalam database menggunakan ajax
                var formData = new FormData($('#form')[0]);
                $.ajax({
                    url: "<?php echo site_url('controllerpersonalia/putusmitrakrujalan') ?>",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function(data) {
                        //jika sukses maka reload tabel dan menampilkan modal sweetalert

                        $('#modal_form').modal('hide');
                        reload_table();
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Berhasil memutus mitra dengan kru jalan",
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

    function reload_table() {
        $('#tabelKruJalan').DataTable().ajax.reload(); //reload datatable ajax 
    }
</script>