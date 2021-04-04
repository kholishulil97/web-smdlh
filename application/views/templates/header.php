<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SMDLH -<?= $title_dr; ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/'); ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/bootstrap-datepicker.standalone.css" rel="stylesheet">

    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-editable.css') ?>" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/animate.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/toastr.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/select2.min.css" rel="stylesheet">

    <script src="<?= base_url('assets/'); ?>js/jquery-3.3.1.js"></script>
    <script src="<?= base_url('assets/'); ?>js/select2.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>

    <script src="<?= base_url('assets/'); ?>js/demo/chart-area-demo.js"></script>
    <script src="<?= base_url('assets/'); ?>vendor/chart.js/Chart.min.js"></script>

    <script src="<?= base_url('assets/'); ?>js/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/socket.io.js"></script>
    <script>
        let ipAddress = "<?= $ip ?>";

        if (ipAddress == "::1") {
            ipAddress = "localhost";
        }

        const port = "3000";
        const socketIoAddress = `http://${ipAddress}:${port}`;
        const socket = io(socketIoAddress);
    </script>
</head>

<body id="page-top" class style="border-collapse: collapse">

    <!-- Page Wrapper -->
    <div id="wrapper">