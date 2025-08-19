<?php
defined('BASEPATH') or exit('No direct script access allowed');
print_r($user);
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
                <!-- Start Notification -->
                <?php if ($this->session->flashdata('sukses')) : ?>
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('sukses') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('gagal')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('gagal') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <!-- End Notification -->
                 <!-- resna -->

                <!--<a href="<?= site_url() . "/" . $this->uri->segment('1') . "/disposisi/download/" . $surat->FILE_SURAT ?>"><button type="button" class="btn btn-info btn-sm"><i class="bi bi-arrow-down-square"></i> Download File </button></a>
                <a href="<?= site_url() . "/" . $this->uri->segment('1') . "/disposisi/rekap" ?>"><button type="button" class="btn btn-warning btn-sm"><i class="bi bi-folder-check"></i> Rekap Disposisi Surat Masuk</button></a>
                -->
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#UpdateStatusSurat"><i class="bi bi-folder-check"></i> Update Status Surat </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#tampilLog"><i class="bi bi-card-list"></i> Log Surat </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Nomor Surat : <?= $surat_disposisi->NO_SURAT ?>
                            <br><br>
                            File Lampiran : <a href="<?php echo base_url() . 'upload/surat_keluar/' . $surat_disposisi->FILE_SURAT; ?>" target="_blank"><?= $surat_disposisi->FILE_SURAT ?></a>
                            <br><br>
                            Jenis Surat :
                            <?php
                            if ($surat_disposisi->JENIS_SURAT == '1') {
                                echo "Biasa";
                            } else if ($surat_disposisi->JENIS_SURAT == '2') {
                                echo "Penting";
                            } else {
                                echo "Rahasia";
                            }
                            ?>
                        </h5>
                        <iframe src="<?= base_url() ?>index.php/pdf_generator/preview_surat_permohonan/<?= $surat_disposisi->ID_SURAT_MASUK ?>" width="100%" height="400"></iframe>

                    </div>
                </div>


            </div>
        </div>
    </section>

</main>
<!-- End #main -->
<!-- Start Update Status Modal-->
<div class="modal fade" id="UpdateStatusSurat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= site_url($this->uri->segment(1) . '/disposisi/update_status') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Disposisi Surat Masuk </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" name="nomor_surat" class="form-control" value="<?= $surat_disposisi->NO_SURAT ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Isi / Hal </label>
                        <div class="col-sm-10">
                            <input type="text" name="perihal" class="form-control" value="<?= $surat_disposisi->ISI_SURAT ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Status Surat</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="status">
                                <option value="4">Dikerjakan</option>
                                <option value="5">Ditangguhkan</option>
                                <option value="6">Dibatalkan</option>
                                <option value="7">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id_surat_masuk" value="<?= $surat_disposisi->ID_SURAT_MASUK ?>" class="form-control">
                    <input type="hidden" name="is_read" value="0" class="form-control">
                    <?php if ($this->uri->segment(1) == "admin") : ?>
                        <input type="hidden" name="user_id" value="<?= $user->USER_ID ?>" class="form-control">
                    <?php endif; ?>
                    <?php if ($this->uri->segment(1) != "admin") : ?>
                        <input type="hidden" name="user_id" value="<?= $user->ID_PEGAWAI ?>" class="form-control">
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left-square"></i> Close</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Update Status Surat Modal-->

<!-- Start Tampil Log Surat Masuk Modal-->
<div class="modal fade" id="tampilLog" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Disposisi Surat Masuk</h5>
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