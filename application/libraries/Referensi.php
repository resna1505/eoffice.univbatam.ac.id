<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi
{
    function nmPegawai($id)
    {
        $this->ci = &get_instance();
        $where = array(
            'ID_PEGAWAI' => $id
        );
        $hasil = $this->ci->db->get_where('t_pegawai', $where)->row();
        if ($hasil != NULL) {
            return $hasil->NM_PEGAWAI;
        } else {
            $hasil = '';
        }
    }


    function nmBagian($id)
    {
        $this->ci = &get_instance();
        $where = array(
            'ID_BAGIAN' => $id
        );
        $hasil = $this->ci->db->get_where('r_bagian', $where)->row();
        #echo "aaa";
        #print_r($hasil);exit();
        if (!empty($hasil)) {
            $hasil = $hasil->NM_BAGIAN;
        } else {
            $hasil = 'Semua';
        }
        return $hasil;
    }

    function nmKategoriSuratKeluar($id)
    {
        $this->ci = &get_instance();
        $where = array(
            'ID_KATEGORI_SRT_KELUAR' => $id
        );
        $hasil = $this->ci->db->get_where('r_kategori_srt_keluar', $where)->row();
        if ($hasil != NULL) {
            return $hasil->NM_KATEGORI_SRT_KELUAR;
        } else {
            $hasil = '';
        }
    }

    function nmKategoriSuratMASUK($id)
    {
        $this->ci = &get_instance();
        $where = array(
            'ID_KATEGORI_SRT_MASUK' => $id
        );
        $hasil = $this->ci->db->get_where('r_kategori_srt_masuk', $where)->row();
        if ($hasil != NULL) {
            return $hasil->NM_KATEGORI_SRT_MASUK;
        } else {
            $hasil = '';
        }
    }

    function jmlSuratMasuk($bulan, $tahun)
    {
        $this->ci = &get_instance();
        $tanggal = $tahun . "-" . $bulan . "-%";
        $where = array(
            'TGL_SURAT LIKE' => $tanggal
        );
        $hasil = $this->ci->db->get_where('t_surat_masuk', $where)->num_rows();
        if ($hasil != NULL) {
            return $hasil;
        } else {
            $hasil = '0';
        }
    }

    function jmlSuratDisposisi($bulan, $tahun)
    {
        $this->ci = &get_instance();
        $tanggal = $tahun . "-" . $bulan . "-%";
        $where = array(
            'TANGGAL_DISPOSISI LIKE' => $tanggal
        );
        $hasil = $this->ci->db->get_where('t_disposisi', $where)->num_rows();
        if ($hasil != NULL) {
            return $hasil;
        } else {
            $hasil = '0';
        }
    }

    function jmlSuratKeluar($bulan, $tahun)
    {
        $this->ci = &get_instance();
        $tanggal = $tahun . "-" . $bulan . "-%";
        $where = array(
            'TGL_SURAT LIKE' => $tanggal,
            'STATUS' => '2'
        );
        $hasil = $this->ci->db->get_where('t_surat_keluar', $where)->num_rows();
        if ($hasil != NULL) {
            return $hasil;
        } else {
            $hasil = '0';
        }
    }
}
