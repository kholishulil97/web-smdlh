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
                        <table class="table table-hover table-striped " id="tabelSP" width="100%" cellspacing="0">
                            <thead id="table_head">
                                <tr>
                                    <th scope="col">Nomor Surat</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Kru Jalan</th>
                                    <th scope="col">Personalia</th>
                                    <th scope="col">Tipe Surat</th>
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
                'url': '<?= base_url() ?>controllerpersonalia/logsuratmitra'
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

    function detail_sp(id) { //menampilkan data yang akan diedit ke dalam modal bootstrap
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
</script>