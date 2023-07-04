<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    body {
        background-color: #F5F6F7;
    }
    .card-pertanyaan p{
        font-size: 16px;
    }
</style>

<a href="<?= base_url('jawaban') ?>" class="btn btn-outline-secondary rounded-pill ml-3 mb-3">Kembali</a>

<section
    id="pertanyaan"
    data-url="<?= base_url('jawaban/'.$user['user_id'].'/reload-pertanyaan') ?>"
    data-method="post">
    <div class="container-fluid">
        
    </div>
</section>

<script src="<?= base_url('script/jawaban.js') ?>"></script>

<?= $this->endSection() ?>