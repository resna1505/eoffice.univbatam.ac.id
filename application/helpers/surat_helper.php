<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('nm_status_surat_masuk')) {
    function nm_status_surat_masuk($id)
    {
        if ($id != NULL) {
            if ($id == '0') {
                $status = "Ditujukan";
            } elseif ($id == '1') {
                $status = "Diubah";
            } elseif ($id == '2') {
                $status = "Dibaca";
            } elseif ($id == '3') {
                $status = "Disposisi";
            } elseif ($id == '4') {
                $status = "Dikerjakan";
            } elseif ($id == '5') {
                $status = "Ditangguhkan";
            } elseif ($id == '6') {
                $status = "Dibatalkan";
            } elseif ($id == '7') {
                $status = "Selesai";
            } else {
                $status = "";
            }
        }

        return $status;
    }
}
if (!function_exists('nm_status_surat_keluar')) {
    function nm_status_surat_keluar($id)
    {
        if ($id != NULL) {
            if ($id == '0') {
                $status = "";
            } elseif ($id == '1') {
                $status = "Dikirimkan";
            } elseif ($id == '2') {
                $status = "Diterima";
            } elseif ($id == '3') {
                $status = "Disposisi";
            } elseif ($id == '4') {
                $status = "Dikerjakan";
            } elseif ($id == '5') {
                $status = "Ditangguhkan";
            } elseif ($id == '6') {
                $status = "Dibatalkan";
            } elseif ($id == '7') {
                $status = "Selesai";
            } else {
                $status = "";
            }
        }
        return $status;
    }
}

if (!function_exists('nm_jenis_surat')) {
    function nm_jenis_surat($id)
    {
        if ($id != NULL) {
            if ($id == '1') {
                echo "Biasa";
            } elseif ($id == '2') {
                echo "Penting";
            } elseif ($id == '3') {
                echo "Rahasia";
            } else {
                echo "";
            }
        } else {
            echo "";
        }
    }
}

if (!function_exists('nm_status_surat_disposisi')) {
    function nm_status_surat_disposisi($id)
    {
        if ($id != NULL) {
            if ($id == '0') {
                $status = "Diterima";
            } elseif ($id == '1') {
                $status = "Diubah";
            } elseif ($id == '2') {
                $status = "Dibaca";
            } elseif ($id == '3') {
                $status = "Disposisi";
            } elseif ($id == '4') {
                $status = "Dikerjakan";
            } elseif ($id == '5') {
                $status = "Ditangguhkan";
            } elseif ($id == '6') {
                $status = "Dibatalkan";
            } elseif ($id == '7') {
                $status = "Selesai";
            } else {
                $status = "";
            }
        }

        return $status;
    }
}
