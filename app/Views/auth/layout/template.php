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
            href="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <link
            rel="stylesheet"
            href="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/dist/css/adminlte.min.css?v=3.2.0">
        <script src="<?= base_url() ?>/assets/js/jQuery.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <?= $this->renderSection('additionalCSS') ?>

    </head>
    <body class="hold-transition login-page">

            <?= $this->renderSection('content') ?>

        <script
            src="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/bootstraps/js/bootstrap.bundle.min.js"></script>

        <script src="<?= base_url() ?>/assets/vendor/AdminLTE-3.2.0/dist/js/adminlte.min.js?v=3.2.0"></script>

        <?= $this->renderSection('additionalJS') ?>
    </body>
</html>