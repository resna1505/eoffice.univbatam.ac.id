<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="<?= site_url('bagian/home') ?>">
                <i class="bi bi-bank"></i>
                <span>Home</span>
            </a>
        </li><!-- End Dashboard Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Surat Masuk</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?= site_url('bagian/surat_masuk') ?>">
                        <i class="bi bi-circle"></i><span>Surat Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('bagian/disposisi') ?>">
                        <i class="bi bi-circle"></i><span>Disposisi</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Forms Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav2" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Surat Keluar</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav2" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <!--<li>
                    <a href="<?= site_url('bagian/surat_keluar/draft') ?>">
                        <i class="bi bi-circle"></i><span>Draf Surat Keluar</span>
                    </a>
                </li>-->
                <li>
                    <a href="<?= site_url('bagian/surat_keluar') ?>">
                        <i class="bi bi-circle"></i><span>Surat Keluar</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->



        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= site_url('bagian/profile') ?>">
                <i class="bi bi-gear"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Contact Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="<?= site_url('auth/logout') ?>">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Keluar</span>
            </a>
        </li><!-- End Login Page Nav -->

    </ul>
</aside><!-- End Sidebar-->