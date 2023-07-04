<?= $this->extend('auth/layout/template') ?>

<?= $this->section('additionalCSS')  ?>

<?= $this->endSection() ?>

<?= $this->section('content')  ?>
<div class="login-box">
    <div class="login-logo">
        Quesioneer
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?php if(session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger text-center">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif ?>
            <?php if(session()->getFlashdata('success')) : ?>
            <div class="alert alert-success text-center">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif ?>
            <form action="<?= base_url('login') ?>" method="post" id="form-login">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btn-submit">Masuk</button>
                    </div>

                </div>
            </form>
            <!-- <p class="mb-1"> <a href="forgot-password.html">I forgot my password</a>
            </p> -->
            <p class="mb-0">
                <a
                    href="<?= base_url('register') ?>"
                    class="text-center btn btn-danger btn-block mt-2">Daftar</a>
            </p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('additionalJS')  ?>

<?= $this->endSection() ?>