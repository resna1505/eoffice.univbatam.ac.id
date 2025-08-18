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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="bi bi-save"></i> Tambah </button>

                <div class="modal fade" id="tambahModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="<?= site_url('bagian/surat_keluar/add') ?>" id="formTambah" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Form Surat Keluar </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Kategori Surat </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="id_kategori_srt_keluar" required>
                                                <?php
                                                foreach ($kategori_srt_keluar as $row) { ?>
                                                    <option value="<?= $row->ID_KATEGORI_SRT_KELUAR ?>"><?= $row->NM_KATEGORI_SRT_KELUAR ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Jenis Surat</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio1" value="1" checked>
                                                <label class="form-check-label" for="inlineRadio1">Biasa</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio2" value="2">
                                                <label class="form-check-label" for="inlineRadio2">Penting</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio3" value="3">
                                                <label class="form-check-label" for="inlineRadio3">Rahasia</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">No Surat</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="no_surat" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-2 col-form-label">Tgl Surat</label>
                                        <div class="col-sm-10">
                                            <input name="tgl_surat" class="form-control" id="datePicker" placeholder="YYYY-MM-DD" autocomplete="off" readonly required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Approval</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="id_tujuan" required>
                                                <?php foreach ($pegawai as $peg) { ?>
                                                    <option value="<?= $peg->ID_PEGAWAI ?>"><?= $peg->NM_PEGAWAI ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Tujuan</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="tujuan" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Ubah nama field dari isi_surat menjadi hal_surat -->
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Isi / Hal</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="isi_surat" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-- Body surat menggunakan CKEDITOR -->
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Body</label>
                                        <div class="col-sm-10">
                                            <textarea name="body" class="form-control" id="ckeditor_tambah"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputNumber" class="col-sm-2 col-form-label">Lampiran Surat (PDF) </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" type="file" name="file_pdf_surat" id="file_pdf_surat" accept="application/pdf">
                                            <small class="text-danger">Max Size 1 MB</small>
                                        </div>
                                    </div>
                                    <?php if ($this->uri->segment(1) == "admin") : ?>
                                        <input type="hidden" name="user_id" value="<?= $user->USER_ID ?>" class="form-control">
                                    <?php endif; ?>
                                    <?php if ($this->uri->segment(1) != "admin") : ?>
                                        <input type="hidden" name="user_id" value="<?= $user->ID_PEGAWAI ?>" class="form-control">
                                    <?php endif; ?>

                                    <!-- Tambahkan setelah div jenis surat -->
                                    <div class="row mb-3" id="passwordField" style="display: none;">
                                        <label for="inputText" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" name="password" class="form-control" placeholder="Masukkan password untuk surat rahasia">
                                            <small class="text-info">Password diperlukan untuk membuka dokumen surat rahasia</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left-square"></i> Close</button>
                                    <button type="submit" class="btn btn-success" id="btnSimpan"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Basic Modal-->

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $judul ?></h5>
                        <!-- Table with stripped rows -->
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">No Surat</th>
                                    <th scope="col">Isi / Hal</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Approval</th>
                                    <th scope="col">Tujuan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($surat_keluar as $row) { ?>
                                    <tr>
                                        <th scope="row"><?= $no++ . '.' ?></th>
                                        <td><?= $row->NO_SURAT ?></td>
                                        <td>
                                            <?= $row->ISI_SURAT ?><br>
                                            <?php if ($row->CATATAN != NULL) : ?>
                                                <small class="text-danger text-sm-end">(ada catatan pimpinan)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date_indo($row->TGL_SURAT) ?></td>
                                        <td><?= $row->NM_PEGAWAI ?></td>
                                        <td><?= $row->TUJUAN ?></td>
                                        <td>
                                            <?php if ($row->STATUS != '1') { ?>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editModal<?= $row->ID_SURAT_KELUAR ?>" class="btn btn-outline-success btn-sm" title="Edit Data">
                                                    <i class="bi bi-check2-square"></i>
                                                </button>
                                                <a href="<?= site_url('bagian/surat_keluar/hapus/' . $row->ID_SURAT_KELUAR) ?>" onclick="return confirm('Anda yakin mau menghapus data ini ?')"><button type="button" class="btn btn-outline-danger btn-sm" title="Hapus Data">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button></a>
                                                <a href="<?= site_url($this->uri->segment(1) . '/surat_keluar/tampil/' . $row->ID_SURAT_KELUAR) ?>">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" title="View Data">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </a>
                                                <a href="<?= site_url('bagian/surat_keluar/kirim_data/' . $row->ID_SURAT_KELUAR) ?>" onclick="return confirm('Anda yakin kirim data ini ?')"><button type="button" class="btn btn-outline-info btn-sm" title="Kirim Data">
                                                        <i class="bi bi-arrow-right-circle"></i>
                                                    </button></a>
                                                <a href="<?= site_url('pdf_generator/download_surat_permohonan/' . $row->ID_SURAT_KELUAR) ?>" target="_blank">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Download Data">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </a>
                                                <a href="<?= site_url('pdf_generator/preview_surat_permohonan/' . $row->ID_SURAT_KELUAR) ?>" target="_blank">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Preview Data">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?= site_url($this->uri->segment(1) . '/surat_keluar/tampil/' . $row->ID_SURAT_KELUAR) ?>">
                                                    <button class="btn btn-success btn-sm">Terkirim</button>
                                                </a>
                                                <a href="<?= site_url('pdf_generator/download_surat_permohonan/' . $row->ID_SURAT_KELUAR) ?>" target="_blank">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Download Data">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </a>
                                                <a href="<?= site_url('pdf_generator/preview_surat_permohonan/' . $row->ID_SURAT_KELUAR) ?>" target="_blank">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Preview Data">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php foreach ($surat_keluar as $surat) { ?>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal<?= $surat->ID_SURAT_KELUAR ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="<?= site_url('bagian/surat_keluar/edit') ?>" enctype="multipart/form-data" id="formEdit<?= $surat->ID_SURAT_KELUAR ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Edit Surat Keluar </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Jenis Surat </label>
                            <div class="col-sm-10">
                                <select class="form-control" name="id_kategori_srt_keluar">
                                    <?php foreach ($kategori_srt_keluar as $row) { ?>
                                        <option value="<?= $row->ID_KATEGORI_SRT_KELUAR ?>" <?php if ($row->ID_KATEGORI_SRT_KELUAR == $surat->ID_KATEGORI_SRT_KELUAR) {
                                                                                                echo "selected";
                                                                                            } ?>><?= $row->NM_KATEGORI_SRT_KELUAR ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Jenis Surat</label>
                            <div class="col-sm-10">
                                <?php
                                $checked1 = $checked2 = $checked3 = '';
                                if ($surat->JENIS_SURAT == 1) {
                                    $checked1 = 'checked';
                                } elseif ($surat->JENIS_SURAT == 2) {
                                    $checked2 = 'checked';
                                } elseif ($surat->JENIS_SURAT == 3) {
                                    $checked3 = 'checked';
                                }
                                ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio1" value="1" <?php echo $checked1; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Biasa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio2" value="2" <?php echo $checked2; ?>>
                                    <label class="form-check-label" for="inlineRadio2">Penting</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_surat" id="inlineRadio3" value="3" <?php echo $checked3; ?>>
                                    <label class="form-check-label" for="inlineRadio2">Rahasia</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">No Surat</label>
                            <div class="col-sm-10">
                                <input type="text" name="no_surat" class="form-control" value="<?php echo $surat->NO_SURAT; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Approval</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="id_tujuan">
                                    <?php
                                    foreach ($pegawai as $data_r) {
                                        $id_pegawai = $data_r->ID_PEGAWAI;
                                        if ($id_pegawai == $surat->ID_TUJUAN) {
                                            $txt1 = "selected";
                                        } else {
                                            $txt1 = "";
                                        }
                                        echo '<option value=' . $id_pegawai . ' ' . $txt1 . '>' . $data_r->NM_PEGAWAI . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Tujuan</label>
                            <div class="col-sm-10">
                                <input type="text" name="tujuan" class="form-control" value="<?php echo $surat->TUJUAN; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDate" class="col-sm-2 col-form-label">Tgl Surat</label>
                            <div class="col-sm-10">
                                <input name="tgl_surat" class="form-control" value="<?= $surat->TGL_SURAT ?>" id="datePicker" placeholder="YYYY-MM-DD" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Isi / Hal</label>
                            <div class="col-sm-10">
                                <input type="text" name="isi_surat" class="form-control" value="<?= $surat->ISI_SURAT ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Body</label>
                            <div class="col-sm-10">
                                <textarea name="body" class="form-control" id="ckeditor_edit<?= $surat->ID_SURAT_KELUAR ?>" required><?= $surat->BODY ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputNumber" class="col-sm-2 col-form-label">File Lampiran (PDF) </label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" name="file_pdf_surat" id="file_pdf_surat" accept="application/pdf">
                                <small class="text-danger">Max Size 1 MB</small>
                            </div>
                        </div>

                        <?php if ($this->uri->segment(1) == "admin") : ?>
                            <input type="hidden" name="user_id" value="<?= $user->USER_ID ?>" class="form-control">
                        <?php endif; ?>
                        <?php if ($this->uri->segment(1) != "admin") : ?>
                            <input type="hidden" name="user_id" value="<?= $user->ID_PEGAWAI ?>" class="form-control">
                        <?php endif; ?>
                        <input type="hidden" name="old_file_pdf" value="<?= $surat->FILE_SURAT ?>" class="form-control">
                        <input type="hidden" name="id_surat_keluar" value="<?= $surat->ID_SURAT_KELUAR ?>" class="form-control">

                        <!-- Tambahkan setelah div jenis surat di modal edit -->
                        <div class="row mb-3" id="passwordFieldEdit<?= $surat->ID_SURAT_KELUAR ?>" style="<?= ($surat->JENIS_SURAT == 3) ? 'display: block;' : 'display: none;' ?>">
                            <label for="inputText" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" class="form-control" value="<?= $surat->PASSWORD ?>" placeholder="Masukkan password untuk surat rahasia">
                                <small class="text-info">Password diperlukan untuk membuka dokumen surat rahasia</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left-square"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Load CKEditor -->
<script src="<?= base_url('assets/assets/ckeditor/ckeditor.js') ?>"></script>

<script>
    // test data
    CKEDITOR.replace('ckeditor_tambah');

    // Initialize CKEditor untuk form edit
    <?php foreach ($surat_keluar as $surat) { ?>
        CKEDITOR.replace('ckeditor_edit<?= $surat->ID_SURAT_KELUAR ?>');
    <?php } ?>

    // Handle form submit untuk form tambah
    document.getElementById('formTambah').addEventListener('submit', function(e) {
        // Update semua instance CKEditor sebelum submit
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    });

    // Handle form submit untuk form edit
    <?php foreach ($surat_keluar as $surat) { ?>
        document.getElementById('formEdit<?= $surat->ID_SURAT_KELUAR ?>').addEventListener('submit', function(e) {
            // Update semua instance CKEditor sebelum submit
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        });
    <?php } ?>
</script>
<script>
// Show/hide password field based on jenis surat selection - Form Tambah
document.querySelectorAll('input[name="jenis_surat"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const passwordField = document.getElementById('passwordField');
        if (this.value == '3') { // Rahasia
            passwordField.style.display = 'block';
            passwordField.querySelector('input[name="password"]').required = true;
        } else {
            passwordField.style.display = 'none';
            passwordField.querySelector('input[name="password"]').required = false;
        }
    });
});

// Show/hide password field for each edit modal
<?php foreach ($surat_keluar as $surat) { ?>
document.querySelectorAll('#editModal<?= $surat->ID_SURAT_KELUAR ?> input[name="jenis_surat"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const passwordField = document.getElementById('passwordFieldEdit<?= $surat->ID_SURAT_KELUAR ?>');
        if (this.value == '3') { // Rahasia
            passwordField.style.display = 'block';
            passwordField.querySelector('input[name="password"]').required = true;
        } else {
            passwordField.style.display = 'none';
            passwordField.querySelector('input[name="password"]').required = false;
        }
    });
});
<?php } ?>
</script>