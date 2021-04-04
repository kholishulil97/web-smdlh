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

                    <div class="form-group mt-3 row">
                        <label class="control-label col-md-3 col-form-label text-right" for="tambahTrayekPosAkhir"><strong>Pos Akhir</strong></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="tambahTrayekPosAkhir" placeholder="Pos Akhir" name="tambahTrayekPosAkhir" required="" oninvalid="this.setCustomValidity('Kolom ini harus diisi')" oninput="setCustomValidity('')">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-form-label text-right"><strong>Tarif</strong></label>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-2 mt-2" id="dynamic_field">

                                </table>
                                <small>*Tekan pada kolom angka untuk mengedit harga</small>
                            </div>
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Kembali</button>
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
                'url': '<?= base_url() ?>controllerkasir/daftartrayek'
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

    function edit_trayek(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
        save_method = 'update';
        $('#tambahTrayek')[0].reset(); // mereset form modal
        $('.form-group').removeClass('is-invalid'); // menghilangkan class is-invalid
        $('.help-block').empty(); // menghilangkan string error

        //mengambil data menggunakan ajax
        $.ajax({
            url: "<?php echo site_url('controllerkasir/tampil_edittrayek') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="action"]').val("update");
                $('[name="tambahTrayekKode"]').attr('readonly', true);
                $('[name="tambahTrayekKode"]').val(data.kode);
                $('[name="tambahTrayekPosAwal"]').attr('readonly', true);
                $('[name="tambahTrayekPosAwal"]').val(data.posawal);
                $('[name="tambahTrayekPosAkhir"]').attr('readonly', true);
                $('[name="tambahTrayekPosAkhir"]').val(data.posakhir);
                $('#dynamic_field').html(data.poslewat);
                $('[name="tambahTrayekKelas"]').attr('readonly', true);
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

    // mereload datatable
    function reload_table() {
        $('#tabelTrayek').DataTable().ajax.reload(); //reload datatable ajax 
    }

    $(document).on('blur', '.table_data', function() { // menjalankan fungsi menggunakan event on-blur
        var id_tarif = $(this).data('row_id'); // memasukkan id tarif
        var trayek_id = $('[name="id"]').val(); // memasukkan id trayek
        var value = $(this).text(); // menangkap nilai yang baru saja dimasukkan
        $.ajax({
            url: "<?php echo base_url(); ?>controllerkasir/edittariftrayek",
            method: "POST",
            data: {
                id_tarif: id_tarif,
                trayek_id: trayek_id,
                value: value
            },
            success: function(data) {
                edit_trayek(trayek_id);
                toastr.success('Tarif berhasil diperbarui.', 'Berhasil', {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "2000",
                    "hideDuration": "500",
                    "timeOut": "2000",
                    "extendedTimeOut": "500",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            }
        })
    });
</script>