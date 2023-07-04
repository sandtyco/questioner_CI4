<?php $uri = service('uri') ?>
<div class="sidebar">
    <nav class="mt-2">
        <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false">

            <li class="nav-item">
                <a
                    href="<?= base_url('pertanyaan') ?>"
                    class="nav-link <?= $uri->getSegment(1) == 'pertanyaan' ? 'active' : ''  ?>">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Pertanyaan
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                    Evaluasi
                        <i class="fas fa-angle-left right"></i>
     
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="pages/layout/top-nav.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/layout/top-nav.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pertanyaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/layout/top-nav.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Hasil</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item" >
                <a
                    href="<?= base_url('jawaban') ?>"
                    class="nav-link <?= $uri->getSegment(1) == 'jawaban' ? 'active' : ''  ?>">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Jawaban User
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a
                    href="<?= base_url('user') ?>"
                    class="nav-link <?= $uri->getSegment(1) == 'user' ? 'active' : ''  ?>">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        User
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('logout') ?>" class="nav-link ">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>
                        Logout
                    </p>
                </a>
            </li>
        </ul>
    </nav>

</div>