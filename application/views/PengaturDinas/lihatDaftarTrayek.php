<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <!-- Tabel -->
    <div class="card shadow mb-3">
        <div class="card-header">
            <a href="" class="btn btn-primary" data-toggle="modal" onclick="tambah_trayek()"><i class="fas fa-fw fa-plus"></i>&nbsp; Tambah Trayek</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tabelTrayek" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col">KODE</th>
                                    <th scope="col">Pos Awal</th>
                                    <th scope="col">Pos Akhir</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Bus Aktif</th>
                                    <th scope="col">Aksi</th>
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

<!-- MODAL TAMBAH TRAYEK -->
<div class="modal fade" id="dynamic_field_modal" tabindex="-1" role="dialog" aria-labelledby="dynamic_field_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dynamic_field_modalLabel">Tambah Trayek</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="tambahTrayek">
                <input type="hidden" value="" name="id" />
                <input type="hidden" value="" name="action" />
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-form-label text-right" for="tambahTrayekKode"><strong>KODE</strong></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="tambahTrayekKode" name="tambahTrayekKode" placeholder="KODE" required="" oninvalid="this.setCustomValidity('Kolom ini harus diisi')" oninput="setCustomValidity('')">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-form-label text-right" for="tambahTrayekPosAwal"><strong>Pos Awal</strong></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="tambahTrayekPosAwal" name="tambahTrayekPosAwal" placeholder="Pos Awal" required="" oninvalid="this.setCustomValidity('Kolom ini harus diisi')" oninput="setCustomValidity('')">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-form-label"></label>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table mb-0" id="dynamic_field">

                                </table>
                                <small>*Tekan <strong><span class="fas fa-plus"></span></strong> untuk menambahkan pos yang dilewati</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3 row">
                        <label class="control-label col-md-3 col-form-label text-right" for="tambahTrayekPosAkhir"><strong>Pos Akhir</strong></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="tambahTrayekPosAkhir" placeholder="Pos Akhir" name="tambahTrayekPosAkhir" required="" oninvalid="this.setCustomValidity('Kolom ini harus diisi')" oninput="setCustomValidity('')">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-form-label text-right" for="tambahTrayekKelas"><strong>Kelas</strong></label>
                        <div class="col-md-9">
                            <select name="tambahTrayekKelas" id="tambahTrayekKelas" class="form-control" required="" oninvalid="this.setCustomValidity('Pilih salah satu kelas')" oninput="setCustomValidity('')">
                                <option value="" selected disabled>-- Pilih Kelas --</option>
                                <option value="Ekonomi">Ekonomi</option>
                                <option value="Patas">Patas</option>
                            </select>
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><i class="fas fa-download"></i>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- BATAS AKHIR MODAL TAMBAH TRAYEK -->

<!-- Script -->

<script type="text/javascript">
    //menampilkan data ke dalam tabel
    $(document).ready(function() {
        $('#tabelTrayek').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'autoWidth': false,
            'order': [
                [5, "desc"]
            ],
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
                'url': '<?= base_url() ?>controllerpengaturdinas/daftartrayek'
            },
            'columns': [{
                    data: 'kode'
                },
                {
                    data: 'posawal'
                },
                {
                    data: 'posakhir'
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'jumlah_bus'
                },
                {
                    data: 'aksi'
                },
            ],
            'dom': "<'row'<'col-auto mr-auto'l><'col-auto'f>><'row mb-2'<'col-auto mr-auto'B>>" + "<'row'<'col-sm-12'tr>><'row mt-2'<'col-auto mr-auto'i><'col-auto searchStyle'p>>",
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    },
                    title: 'Daftar Trayek PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'pdfHtml5',
                    download: 'open',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: 'Daftar Trayek PT. Akas Mila Sejahtera'
                },
                {
                    extend: 'print',
                    messageTop: 'Daftar keseluruhan Trayek PT. Akas Mila Sejahtera, Probolinggo.',
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
    });

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

    var save_method; //untuk sting save method
    var table;
    var base_url = '<?php echo base_url(); ?>';

    var count = 1;

    function add_dynamic_input_field(count) {
        var button = '';
        if (count > 1) {
            button = '<button type="button" name="remove" id="' + count + '" class="btn btn-danger btn-xs remove"><span class="fas fa-minus"></span></button>';
        } else {
            button = '<button type="button" name="tambah_pos_lewat" id="tambah_pos_lewat" class="btn btn-success btn-xs"><span class="fas fa-plus"></span></button>';
        }
        output = '<div class="entry input-group col-md-6 mb-3" id="row' + count + '">';
        output += '<input type="text" name="tambahTrayekPosLewat[]" placeholder="lewat" class="form-control name_list" />';
        output += button + '</div>';
        $('#dynamic_field').append(output);
    }

    $(document).on('click', '#tambah_pos_lewat', function() {
        count = count + 1;
        add_dynamic_input_field(count);
    });

    $(document).on('click', '.remove', function() {
        var row_id = $(this).attr("id");
        $('#row' + row_id).remove();
    });

    function tambah_trayek() {
        save_method = 'add';
        $('#dynamic_field').html('');
        add_dynamic_input_field(1);
        $('#tambahTrayek')[0].reset(); // mereset form pada modal
        $('.form-control').removeClass('is-invalid'); // menghilangkan class error
        $('.help-block').empty(); // menghilangkan string error
        $('#dynamic_field_modal').modal('show'); // menampilkan modal bootstrap
        $('.modal-title').text('Tambah Trayek Baru'); // memberi judul modal bootstrap
        $('[name="tambahTrayekKode"]').attr('readonly', false); // menjadikan field kode aktif kembali
        $('[name="action"]').val("add"); // memberi nilai modal untuk keperluan validasi form
        $('#photo-preview').hide(); // menyembunyikan photo preview
        $('#label-photo').text('Unggah Foto'); // label unggah photo
    }

    function save() {
        $('#btnSave').text('Menyimpan...'); //mengganti teks tombol
        $('#btnSave').attr('disabled', true); //menon-aktifkan tombol
        var url;
        var teksberhasil;

        if (save_method == 'add') { //seleksi string save method dari modal bootstrap
            url = "<?php echo base_url('controllerpengaturdinas/tambahtrayek') ?>";
            teksberhasil = "Berhasil menambah data trayek baru";
        } else {
            url = "<?php echo base_url('controllerpengaturdinas/edittrayek') ?>";
            teksberhasil = "Berhasil mengedit data trayek";
        }


        // menambahkan data ke dalam database menggunakan ajax
        var formData = new FormData($('#tambahTrayek')[0]);
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

                    $('#dynamic_field_modal').modal('hide');
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

    function edit_trayek(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';
        $('#tambahTrayek')[0].reset(); // mereset form modal
        $('.form-group').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error

        //mengambil data menggunakan ajax
        $.ajax({
            url: "<?php echo site_url('controllerpengaturdinas/tampil_edittrayek') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="action"]').val("update");
                $('[name="tambahTrayekKode"]').attr('readonly', true);
                $('[name="tambahTrayekKode"]').val(data.kode);
                $('[name="tambahTrayekPosAwal"]').val(data.posawal);
                $('[name="tambahTrayekPosAkhir"]').val(data.posakhir);
                $('#dynamic_field').html(data.poslewat);
                $('[name="tambahTrayekKelas"]').val(data.kelas);
                $('#dynamic_field_modal').modal('show'); // menampilkan modal bootstrap ketika selesai mengambil data
                $('.modal-title').text('Edit Data Trayek'); // memberikan judul pada modal bootstrap
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

    function reload_table() {
        $('#tabelTrayek').DataTable().ajax.reload(); //reload datatable ajax 
    }
</script>