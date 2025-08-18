<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>SIM Surat Masuk & Keluar </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?= $judul ?></a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">




                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Surat Masuk <span>| Terbaru</span></h5>

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">No Surat</th>
                                            <th scope="col">Tgl Surat</th>
                                            <th scope="col">Pengirim</th>
                                            <th scope="col">Approval</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Isi / Hal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($surat_masuk as $row) { ?>
                                            <tr>
                                                <th scope="row"><?= $no++ . '.' ?></th>
                                                <td><?= $row->NO_SURAT ?></td>
                                                <td><?= date_indo($row->TGL_SURAT) ?></td>
                                                <td><?= $this->referensi->nmPegawai($row->ID_PENGIRIM) ?></td>
                                                <td><?= $this->referensi->nmPegawai($row->ID_TUJUAN) ?></td>
                                                <td><?= $row->TUJUAN ?></td>
                                                <td><?= $row->ISI_SURAT ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->



                    <div class="col-12">
                        <div class="card top-selling overflow-auto">


                            <div class="card-body pb-0">
                                <h5 class="card-title">Surat Disposisi <span>| Terbaru</span></h5>

                                <table class="table table-striped ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">No Surat</th>
                                            <th scope="col">Tgl Disposisi</th>
                                            <th scope="col">Pemberi</th>
                                            <th scope="col">Penerima</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Isi / Hal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($surat_disposisi as $row) { ?>
                                            <tr>
                                                <th scope="row"><?= $no++ . '.' ?></th>
                                                <td><?= $row->NOMOR_SURAT ?></td>
                                                <td><?= date_indo($row->TANGGAL_DISPOSISI) ?></td>
                                                <td><?= $this->referensi->nmPegawai($row->ID_PEMBERI) ?></td>
                                                <td><?= $this->referensi->nmPegawai($row->ID_PENERIMA) ?></td>
                                                <td><?= $row->TUJUAN ?></td>
                                                <td><?= $row->ISI_SURAT ?></td>


                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Top Selling -->

                </div>
            </div><!-- End Left side columns -->



        </div>
    </section>

</main><!-- End #main -->