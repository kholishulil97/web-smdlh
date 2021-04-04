<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Hak Cipta &copy; PT. Akas Mila Sejahtera <?= date('Y') ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>



<!-- Page level plugin JavaScript-->
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-table-editable.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-editable.js') ?>"></script>


<!-- Demo scripts for this page-->
<script src="<?php echo base_url('assets/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.flash.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jszip.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/pdfmake.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/vfs_fonts.js') ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.html5.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.print.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.colVis.min.js') ?>"></script>


<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/sweetalert/sweetalert2.all.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/sweetalert/sweetalertscript.js"></script>
<script src="<?= base_url('assets/'); ?>js/toastr/toastr.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/notifikasi.js"></script>
<script>
    socket.on('reload', () => {
        toastr.info('User lain telah menambahkan data bus baru.', 'Pemberitahuan');
    });
</script>

</body>

</html>