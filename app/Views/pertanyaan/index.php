<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <ul class="nav">
                <li>
                    <button
                        class="btn btn-primary"
                        id="btnTambah"
                        data-url="<?= base_url('pertanyaan/modal-tambah') ?>"
                        data-method="post">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Data</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            id="table_id"
            class="table table-hover table-bordered"
            data-url="<?= base_url('pertanyaan/reload-datatables') ?>"
            data-method="post">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pertanyaan</th>
                    <th>Pertanyaan</th>
                    <th>Tipe Jawaban</th>
                    <th>Opsi Jawaban</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="viewmodal"></div>
<script src="<?= base_url() ?>/script/pertanyaan.js"></script>
<?= $this->endSection() ?>