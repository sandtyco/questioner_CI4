<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="custom-control custom-switch">
                <input
                    type="checkbox"
                    class="custom-control-input"
                    id="lock"
                    data-url="<?= base_url('jawaban/lock') ?>"
                    data-method="post"
                    <?= $lock == '0' ? 'checked' : '' ?>>
                <label class="custom-control-label" for="lock">Open Submition</label>
            </div>
        </div>
        <div class="card-tools">
            <ul class="nav">
                <li>
                    <button
                        class="btn btn-outline-success"
                        id="btnExport"
                        data-url="<?= base_url('jawaban/export-excel') ?>"
                        data-method="post">
                        <i class="fas fa-file-excel mr-1"></i>
                        Export Excel</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <table
            id="table_id"
            class="table table-hover table-bordered"
            data-url="<?= base_url('jawaban/reload-datatables') ?>"
            data-method="post">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Institusi</th>
                    <th>Wilayah</th>
                    <th>Status</th>
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
<script src="<?= base_url() ?>/script/jawaban.js"></script>
<?= $this->endSection() ?>