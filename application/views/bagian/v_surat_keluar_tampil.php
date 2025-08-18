<?php
#echo "lll";exit();
#print_r($surat);
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

    <section class="section">

        <div class="row">
            <div class="col-lg-12">

                <!--<a href="<?= site_url($this->uri->segment(1) . '/surat_keluar/download/' . $surat->FILE_SURAT) ?>"><button type="button" class="btn btn-primary btn-sm"><i class="bi bi-arrow-down-square"></i> Download File </button></a>
                <a href="<?= site_url($this->uri->segment(1) . '/surat_keluar') ?>"><button type="button" class="btn btn-warning btn-sm"><i class="bi bi-folder-check"></i> Rekap Surat Keluar</button></a>
                -->
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#tampilLog"><i class="bi bi-card-list"></i> Log Akses </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title" style="font-size:15px;">
                            Nomor Surat : <?= $surat->NO_SURAT ?>
                            <br><br>
                            File Lampiran : <a href="<?php echo base_url() . 'upload/surat_keluar/' . $surat->FILE_SURAT; ?>" target="_blank"><?= $surat->FILE_SURAT ?></a>
                            <br><br>
                            Jenis Surat :
                            <?php
                            if ($surat->JENIS_SURAT == '1') {
                                echo "Biasa";
                            } else if ($surat->JENIS_SURAT == '2') {
                                echo "Penting";
                            } else {
                                echo "Rahasia";
                            }
                            ?>
                            <br><br>
                            Status Surat :
                            <?php if ($surat->STATUS == '0') : ?>
                                <span class="badge bg-warning">Belum dikirim</span>
                            <?php endif; ?>
                            <?php if ($surat->STATUS == '1') : ?>
                                <span class="badge bg-success">Terkirim</span>
                            <?php endif; ?>

                        </h5>

                        <!-- GANTI BAGIAN INI DI VIEW EXISTING ANDA -->
                        <!-- Dari: <iframe src="<?= base_url() ?>upload/surat_keluar/<?= $surat->FILE_SURAT ?>" width="100%" height="400"></iframe> -->
                        <!-- Menjadi: -->

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="bi bi-file-earmark-pdf"></i> Generate PDF
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 5rem;"></i>
                                        </div>
                                        <h5><?= $surat->ISI_SURAT ?></h5>
                                        <p class="text-muted">Nomor: <?= $surat->NO_SURAT ?></p>
                                        <p class="text-muted small">Template: Universitas Batam</p>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                            <!-- TOMBOL DOWNLOAD PDF -->
                                            <a href="<?= site_url('pdf_generator/download_surat_permohonan/' . $surat->ID_SURAT_KELUAR) ?>"
                                                class="btn btn-danger btn-lg me-md-2"
                                                target="_blank">
                                                <i class="bi bi-download"></i> Download PDF
                                            </a>

                                            <!-- TOMBOL PREVIEW PDF -->
                                            <a href="<?= site_url('pdf_generator/preview_surat_permohonan/' . $surat->ID_SURAT_KELUAR) ?>"
                                                class="btn btn-outline-primary btn-lg"
                                                target="_blank">
                                                <i class="bi bi-eye"></i> Preview PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="bi bi-info-circle"></i> Info PDF
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <strong>Format:</strong> PDF A4<br>
                                            <strong>Layout:</strong> Portrait<br>
                                            <strong>Font:</strong> Arial<br>
                                            <strong>Size:</strong> ~200KB<br>
                                            <strong>Template:</strong> Resmi UNIBA
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

</main><!-- End #main -->
<!-- Start Tampil Log Surat Masuk Modal-->
<div class="modal fade" id="tampilLog" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Disposisi Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($log_surat as $row) { ?>
                            <tr>
                                <td><?= $no++ . '.' ?></td>
                                <td><?= $row->USERNAME ?></td>
                                <td><?= $row->CATATAN ?></td>
                                <td><?= $row->WAKTU ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Tampil Log Surat Masuk Modal-->