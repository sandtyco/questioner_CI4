<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $title ?></title>

        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <link
            rel="stylesheet"
            href="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">

        <link
            rel="stylesheet"
            href="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/dist/css/adminlte.min.css?v=3.2.0">
        <link
            href="<?= base_url() ?>/assets/vendor/DataTables/datatables.min.css"
            rel="stylesheet"/>
        <script src="<?= base_url('assets/js/jQuery.js') ?>"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="<?= base_url() ?>/assets/vendor/DataTables/datatables.min.js"></script>
    </head>

    <body class="hold-transition sidebar-mini">

        <div class="wrapper">

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto"></ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4">

                <a
                    href="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/index3.html"
                    class="brand-link">
                    <img
                        src="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/dist/img/AdminLTELogo.png"
                        alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">KUESIONER</span>
                </a>

                <?= $this->include('layout/sidebar') ?>

            </aside>

            <div class="content-wrapper">

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?= $page ?></h1>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                    </div>
                </section>

                <section class="content">

                    <?= $this->renderSection('content') ?>

                </section>

            </div>

            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b>
                    3.2.0
                </div>
                <strong>Copyright &copy;
                    <?= date('Y') ?>
                    Kuesioner.</strong>
                All rights reserved.
            </footer>
        </div>

        <script
            src="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <script
            src="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/dist/js/adminlte.min.js?v=3.2.0"></script>

    </body>
</html>