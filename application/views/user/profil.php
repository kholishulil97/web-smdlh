<body onload="konfirmPassword()">

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

        <?php
        if ($user['role_id'] == 1) {
            $jabatan = "Pengatur Dinas";
        } else if ($user['role_id'] == 2) {
            $jabatan = "Kasir";
        } else if ($user['role_id'] == 5) {
            $jabatan = "Personalia";
        }
        ?>

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block"><img src="<?= base_url('assets/img/profile/') . $user['fotoprofil']; ?>" class="card-img"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <form class="user">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>Nama</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= $user['nama'];  ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>NIP</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= $user['nip'];  ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>Jabatan</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= $jabatan; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>Alamat</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= $user['alamat']; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>Nomor HP</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= $user['nomor_hp']; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-form-label text-right"><strong>Aktif sejak</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" value="<?= date('d F Y', $user['date_created']); ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
</body>
<script>
    var base_url = '<?php echo base_url(); ?>';
    var user = '<?= $user['nip'] ?>'

    function konfirmPassword() {
        Swal.fire({
            title: 'Masukkan password anda',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: false,
            confirmButtonText: 'Konfirmasi',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return fetch(`http://localhost/rest-api/smdlh-rest-server/api/user?nip=${user}&password=${login}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            'Password salah!'
                        )
                    })
            },
            allowOutsideClick: false,
            footer: '<a href="' + base_url + 'controlleruser/dashboard">&larr; Kembali ke Dashboard</a>'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password benar',
                    showConfirmButton: false,
                    timer: 1100
                })
            }
        })
    }
</script>