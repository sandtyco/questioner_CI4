<?= $this->extend('auth/layout/template') ?>

<?= $this->section('additionalCSS')  ?>
<style>
    .login-box {
        width: 50%;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content')  ?>
<div class="login-box">
    <div class="login-logo">
        Quesioneer Registrasi
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <?php if(session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif ?>
            <?php if(session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif ?>
            <form action="<?= base_url('register') ?>" method="post" id="form-login">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_mahasiswa">Status Mahasiswa</label>
                            <select name="status_mahasiswa" id="status_mahasiswa" class="form-control">
                                <option value="Aktif">Aktif</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="institusi">Institusi</label>
                            <input type="text" name="institusi" id="institusi" class="form-control">
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prodi">Program Studi</label>
                            <input type="text" name="prodi" id="prodi" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="wilayah">Wilayah</label>
                            <input type="text" name="wilayah" id="wilayah" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Email</label>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" name="email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Password</label>
                            <div class="input-group mb-3">
                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Password"
                                    name="password"
                                    id="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Password Confirm</label>
                            <div class="input-group mb-3">
                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Password Confirm"
                                    name="password_confirm"
                                    id="password_confirm">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btn-submit">Daftar</button>
                    </div>

                </div>
            </form>
            <!-- <p class="mb-1"> <a href="forgot-password.html">I forgot my password</a>
            </p> -->
            <p class="mb-0">
                <a
                    href="<?= base_url('login') ?>"
                    class="text-center btn btn-danger btn-block mt-2">Login</a>
            </p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('additionalJS')  ?>

<?= $this->endSection() ?>