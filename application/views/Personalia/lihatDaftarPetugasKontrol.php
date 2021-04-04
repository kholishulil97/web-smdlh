<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <!-- Tabel -->
    <div class="card shadow mb-3">
        <div class="card-header">
            <a href="" class="btn btn-primary" data-toggle="modal" onclick="tambah_kontrol()"><i class="fas fa-fw fa-plus"></i>&nbsp; Tambah Petugas Kontrol</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabelPetugasKontrol" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Nomor HP</th>
                                    <th scope="col">Foto</th>
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
                                <input name="tambahPetugasKontrolNip" placeholder="NIP" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Nama</strong></label>
                            <div class="col-md-9">
                                <input name="tambahPetugasKontrolNama" placeholder="Nama" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Alamat</strong></label>
                            <div class="col-md-9">
                                <input name="tambahPetugasKontrolAlamat" placeholder="Alamat" class="form-control" type="text">
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-form-label text-right"><strong>Nomor HP</strong></label>
                            <div class="col-md-9">
                                <input name="tambahPetugasKontrolNomorHp" placeholder="Nomor HP" class="form-control" type="text">
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
        $('#tabelPetugasKontrol').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'autoWidth': false,
            'order': [
                [1, "asc"]
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
                'url': '<?= base_url() ?>controllerpersonalia/daftarpetugaskontrol'
            },
            'dom': "<'row'<'col-auto mr-auto'l><'col-auto'f>><'row mb-2'<'col-auto mr-auto'B>>" + "<'row'<'col-sm-12'tr>><'row mt-2'<'col-auto mr-auto'i><'col-auto searchStyle'p>>",
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: 'Daftar Petugas Kontrol PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'pdfHtml5',
                    download: 'open',
                    exportOptions: {
                        columns: ':visible',

                    },
                    title: 'Daftar Petugas Kontrol PT. Akas Mila Sejahtera',

                },
                {
                    extend: 'print',
                    messageTop: 'Daftar keseluruhan Petugas Kontrol PT. Akas Mila Sejahtera, Probolinggo.',
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

    function tambah_kontrol() {
        save_method = 'add';
        $('#form')[0].reset(); // mereset form pada modal
        $('.form-control').removeClass('is-invalid'); // menghilangkan class error
        $('.help-block').empty(); // menghilangkan string error
        $('#modal_form').modal('show'); // menampilkan modal bootstrap
        $('.modal-title').text('Tambah Petugas Kontrol'); // memberi judul modal bootstrap
        $('[name="tambahPetugasKontrolNip"]').attr('readonly', false); //menjadikan field nopol aktif kembali
        $('[name="action"]').val("add");
        $('#photo-preview').hide(); // menyembunyikan photo preview
        $('#label-photo').text('Unggah Foto'); // label unggah photo
    }

    function save() {
        $('#btnSave').text('Menyimpan...'); //mengganti teks tombol
        $('#btnSave').attr('disabled', true); //menon-aktifkan tombol
        var url;
        var teksberhasil;

        if (save_method == 'add') { //seleksi string save method dari modal bootstrap
            url = "<?php echo site_url('controllerpersonalia/tambahpetugaskontrol') ?>";
            teksberhasil = "Berhasil menambah data petugas kontrol baru";
        } else {
            url = "<?php echo site_url('controllerpersonalia/editpetugaskontrol') ?>";
            teksberhasil = "Berhasil mengedit data petugas kontrol";
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

    function edit_kontrol(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';
        $('#form')[0].reset(); // mereset form modal
        $('.form-control').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error


        //mengambil data menggunakan ajax
        $.ajax({
            url: "<?php echo site_url('controllerpersonalia/tampil_editpetugaskontrol') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="action"]').val("update");
                $('[name="tambahPetugasKontrolNip"]').attr('readonly', true);
                $('[name="tambahPetugasKontrolNip"]').val(data.nip);
                $('[name="tambahPetugasKontrolNama"]').val(data.nama);
                $('[name="tambahPetugasKontrolAlamat"]').val(data.alamat);
                $('[name="tambahPetugasKontrolNomorHp"]').val(data.nomor_hp);
                $('#modal_form').modal('show'); // menampilkan modal bootstrap ketika selesai mengambil data
                $('.modal-title').text('Edit Data Petugas Kontrol'); // memberikan judul pada modal bootstrap
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

    // menghapus data dari database menggunakan ajax
    function hapus_kontrol(id, nama) { //ketika menekan tombol hapus maka akan muncul modal sweetalert
        Swal.fire({
            title: 'Apakah anda yakin?',
            html: "Menghapus data petugas kontrol dgn nama <b></b>",
            icon: 'warning',
            scrollbarPadding: false,
            showCancelButton: true,
            confirmButtonColor: '#30d646',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
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
                $.ajax({
                    url: "<?php echo site_url('controllerpersonalia/hapuspetugaskontrol') ?>/" + id,
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

    function reload_table() {
        $('#tabelPetugasKontrol').DataTable().ajax.reload(); //reload datatable ajax 
    }
</script>