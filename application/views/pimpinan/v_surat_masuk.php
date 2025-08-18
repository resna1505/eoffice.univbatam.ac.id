<?php
defined('BASEPATH') or exit('No direct script access allowed');
#print_r($this->data['user']->ID_PEGAWAI);
#exit();
echo $this->uri->segment(2);
#exit();
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
                <?php if ($this->uri->segment(1) != "pimpinan") : ?>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"><i class="bi bi-save"></i> Tambah </button>
                    <div class="modal fade" id="tambahModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="<?= site_url($this->uri->segment(1) . '/surat_masuk/add') ?>" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Surat Masuk </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Jenis Surat </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="id_kategori_srt_masuk">
                                                    <?php foreach ($kategori_srt_masuk as $row) { ?>
                                                        <option value="<?= $row->ID_KATEGORI_SRT_MASUK ?>"><?= $row->NM_KATEGORI_SRT_MASUK ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Nama Pengirim</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nm_pengirim" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">No Surat</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="no_surat" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-2 col-form-label">Tgl Surat</label>
                                            <div class="col-sm-10">
                                                <input name="tgl_surat" class="form-control" id="datePicker" placeholder="YYYY-MM-DD" required autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-2 col-form-label">Tgl Diterima</label>
                                            <div class="col-sm-10">
                                                <input name="tgl_diterima" class="form-control" id="datePicker" placeholder="YYYY-MM-DD" required autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Kode</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="kode" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">No Agenda</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="no_agenda" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Isi / Hal</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="isi_surat" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Tujuan / Pimpinan</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="id_tujuan">
                                                    <?php foreach ($tujuan as $row) { ?>
                                                        <option value="<?= $row->ID_PEGAWAI ?>"><?= $row->NM_PEGAWAI ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!--<div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Status Surat</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="status_surat">
                                                    <option value="1">Diteruskan</option>
                                                    <option value="0">Diarsipkan</option>

                                                </select>
                                            </div>
                                        </div>-->

                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-2 col-form-label">File Surat PDF (Max Size 2 MB) </label>
                                            <div class="col-sm-10">
                                                <input class="form-control" type="file" name="file_surat" id="file_surat">
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_id" value="<?= $user->USER_ID ?>" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="status_surat" value="0" class="form-control">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left-square"></i> Close</button>
                                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Basic Modal-->
                <?php endif; ?>
                <!--<a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/rekap') ?>">
                    <button type="button" class="btn btn-warning btn-sm"><i class="bi bi-card-list"></i> Rekap Jumlah </button>
                </a>
                <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/export') ?>">
                    <button type="button" class="btn btn-success btn-sm"><i class="bi bi-card-list"></i> Rekap Excel </button>
                </a>
                <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/cetak') ?>" target="new"><button type="button" class="btn btn-warning btn-sm" title="Print Data"><i class="bi bi-printer"></i> Print Data</button></a>
                <a href="<?= site_url($this->uri->segment(1) . '/grafik/masuk') ?>">
                    <button type="button" class="btn btn-info btn-sm"><i class="bi bi-bar-chart-line"></i> Grafik </button>
                </a>-->
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
                                    <th scope="col">Tgl Surat</th>
                                    <th scope="col">Pengirim</th>
                                    <th scope="col">Approval</th>
                                    <th scope="col">Tujuan</th>
                                    <th scope="col">Isi / Hal</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($rekap_surat as $row) { ?>
                                    <tr>
                                        <th scope="row"><?= $no++ . '.' ?></th>
                                        <td><?= $row->NO_SURAT ?></td>
                                        <td><?= date_indo($row->TGL_SURAT) ?></td>
                                        <td><?= $this->referensi->nmPegawai($row->ID_PENGIRIM) ?></td>
                                        <td><?= $this->referensi->nmPegawai($row->ID_TUJUAN) ?></td>
                                        <td><?= $row->TUJUAN ?></td>
                                        <td><?= $row->ISI_SURAT ?></td>
                                        <!-- <td>
                                            <?php
                                            if ($row->STATUS == 0) { ?>
                                                <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/terima_data/' . $row->ID_SURAT_MASUK) ?>" onclick="return confirm('Anda yakin terima surat ini ?')"><button class="btn btn-success btn-sm">Terima surat</button></a>
                                            <?php
                                            } else {
                                            ?>

                                                <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/tampil/' . $row->ID_SURAT_MASUK) ?>">
                                                    <button type="button" class="btn btn-outline-info btn-sm" title="View Data">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </a>
                                            <?php } ?>
                                        </td> -->
                                        <td>
                                            <?php if ($row->STATUS == 0) { ?>
                                                <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/terima_data/' . $row->ID_SURAT_MASUK) ?>" onclick="return confirm('Anda yakin terima surat ini ?')">
                                                    <button class="btn btn-success btn-sm">Terima surat</button>
                                                </a>
                                            <?php } else { ?>
                                                <!-- Cek JENIS_SURAT_KELUAR dan PASSWORD dari t_surat_keluar -->
                                                <?php if (isset($row->JENIS_SURAT_KELUAR) && $row->JENIS_SURAT_KELUAR == 3 && !empty($row->PASSWORD)) { ?>
                                                    <button type="button" class="btn btn-outline-info btn-sm" title="View Data (Surat Rahasia)" 
                                                            onclick="checkPassword('<?= $row->ID_SURAT_MASUK ?>', '<?= site_url($this->uri->segment(1) . '/surat_masuk/tampil/' . $row->ID_SURAT_MASUK) ?>')">
                                                        <i class="bi bi-shield-lock"></i> <i class="bi bi-search"></i>
                                                    </button>
                                                <?php } else { ?>
                                                    <a href="<?= site_url($this->uri->segment(1) . '/surat_masuk/tampil/' . $row->ID_SURAT_MASUK) ?>">
                                                        <button type="button" class="btn btn-outline-info btn-sm" title="View Data">
                                                            <i class="bi bi-search"></i>
                                                        </button>
                                                    </a>
                                                <?php } ?>
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

    <!-- Modal Password untuk Surat Rahasia -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Surat Rahasia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-lock"></i> Surat ini bersifat rahasia. Masukkan password untuk melanjutkan.
                    </div>
                    <form id="passwordForm">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <input type="hidden" id="suratId" name="surat_id">
                        <input type="hidden" id="targetUrl" name="target_url">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="verifyPassword()">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
</main><!-- End #main -->
<?php foreach ($rekap_surat as $row) { ?>
    <div class="modal fade" id="editModal<?= $row->ID_SURAT_MASUK ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="<?= site_url($this->uri->segment(1) . '/surat_masuk/edit') ?>" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Surat Masuk </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Jenis Surat </label>
                            <div class="col-sm-10">
                                <select class="form-control" name="id_kategori_srt_masuk">
                                    <?php foreach ($kategori_srt_masuk as $jenis) { ?>
                                        <option value="<?= $jenis->ID_KATEGORI_SRT_MASUK ?>" <?php if ($jenis->ID_KATEGORI_SRT_MASUK == $row->ID_KATEGORI_SRT_MASUK) {
                                                                                                    echo "selected";
                                                                                                } ?>><?= $jenis->NM_KATEGORI_SRT_MASUK ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Nama Pengirim</label>
                            <div class="col-sm-10">
                                <input type="text" name="nm_pengirim" class="form-control" value="<?= $row->NM_PENGIRIM ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">No Surat</label>
                            <div class="col-sm-10">
                                <input type="text" name="no_surat" class="form-control" value="<?= $row->NO_SURAT ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDate" class="col-sm-2 col-form-label">Tgl Surat</label>
                            <div class="col-sm-10">
                                <input name="tgl_surat" class="form-control" value="<?= $row->TGL_SURAT ?>" id="datePicker" placeholder="YYYY-MM-DD" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputDate" class="col-sm-2 col-form-label">Tgl Diterima</label>
                            <div class="col-sm-10">
                                <input name="tgl_diterima" class="form-control" value="<?= $row->TGL_DITERIMA ?>" id="datePicker" placeholder="YYYY-MM-DD" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Kode</label>
                            <div class="col-sm-10">
                                <input type="text" name="kode" class="form-control" value="<?= $row->KODE ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">No Agenda</label>
                            <div class="col-sm-10">
                                <input type="text" name="no_agenda" class="form-control" value="<?= $row->NO_AGENDA ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Isi / Hal</label>
                            <div class="col-sm-10">
                                <input type="text" name="isi_surat" class="form-control" value="<?= $row->ISI_SURAT ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Tujuan / Pimpinan</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="id_tujuan">
                                    <?php
                                    foreach ($tujuan as $peg) {
                                        $id_pegawai = $peg->ID_PEGAWAI;
                                        if ($id_pegawai == $row->ID_TUJUAN) {
                                            $txt1 = "selected";
                                        } else {
                                            $txt1 = "";
                                        }
                                    ?>
                                        <option value="<?= $peg->ID_PEGAWAI ?>" <?= $txt1 ?>><?= $peg->NM_PEGAWAI ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!--<div class="row mb-3">
                            <label for="inputText" class="col-sm-2 col-form-label">Status Surat</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="status_surat">
                                    <option value="1" <?php if ($row->STATUS_SURAT == '1') {
                                                            echo "selected";
                                                        } ?>>Diteruskan</option>
                                    <option value="0" <?php if ($row->STATUS_SURAT == '0') {
                                                            echo "selected";
                                                        } ?>>Diarsipkan</option>

                                </select>
                            </div>
                        </div>-->

                        <div class="row mb-3">
                            <label for="inputNumber" class="col-sm-2 col-form-label">File Surat PDF (Max Size 2 MB) </label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" name="file_surat" id="file_surat">
                            </div>
                        </div>
                        <?php if ($this->uri->segment(1) == "admin") : ?>
                            <input type="hidden" name="user_id" value="<?= $user->USER_ID ?>" class="form-control">
                        <?php endif; ?>
                        <?php if ($this->uri->segment(1) != "admin") : ?>
                            <input type="hidden" name="user_id" value="<?= $user->ID_PEGAWAI ?>" class="form-control">
                        <?php endif; ?>
                        <input type="hidden" name="id_surat_masuk" value="<?= $row->ID_SURAT_MASUK ?>" class="form-control">
                        <input type="hidden" name="old_file_surat" value="<?= $row->FILE_SURAT ?>" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="status_surat" value="1" class="form-control">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-left-square"></i> Close</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<script>
function checkPassword(suratId, targetUrl) {
    document.getElementById('suratId').value = suratId;
    document.getElementById('targetUrl').value = targetUrl;
    document.getElementById('password').value = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('password').classList.remove('is-invalid');
    
    var passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    passwordModal.show();
}

function verifyPassword() {
    const suratId = document.getElementById('suratId').value;
    const password = document.getElementById('password').value;
    const targetUrl = document.getElementById('targetUrl').value;
    
    if (!password) {
        document.getElementById('password').classList.add('is-invalid');
        document.getElementById('passwordError').textContent = 'Password harus diisi';
        return;
    }
    
    // AJAX request untuk verifikasi password
    fetch('<?= site_url($this->uri->segment(1) . '/surat_masuk/verify_password') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'surat_id=' + suratId + '&password=' + encodeURIComponent(password)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Password benar, tutup modal dan redirect
            var passwordModal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
            passwordModal.hide();
            window.location.href = targetUrl;
        } else {
            // Password salah
            document.getElementById('password').classList.add('is-invalid');
            document.getElementById('passwordError').textContent = data.message || 'Password salah';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('password').classList.add('is-invalid');
        document.getElementById('passwordError').textContent = 'Terjadi kesalahan sistem';
    });
}

// Handle Enter key pada input password
document.getElementById('password').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        verifyPassword();
    }
});
</script>
<!-- End Basic Modal-->