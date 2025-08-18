<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Disposisi_model extends CI_Model
{

    private $_table = "t_disposisi";
    private $_table2 = "t_surat_masuk";
    private $_table3 = "t_surat_keluar";

    public function rules()
    {
        return [
            [
                'field' => 'id_surat_masuk',
                'label' => 'Surat Masuk',
                'rules' => 'required'
            ],

            [
                'field' => 'instruksi',
                'label' => 'Instruksi',
                'rules' => 'required'
            ]
        ];
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'IP tidak dikenali';
        return $ipaddress;
    }

    public function getJumlah()
    {
        return $this->db->get($this->_table)->num_rows();
    }
    public function getTerbaruBagian($id_user)
    {
        // $this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        // $this->db->limit(5);
        // return $this->db->get_where($this->_table, array('ID_PENERIMA' => $id_user))->result();

        $where = array(
            'a.ID_PENERIMA' => $id_user
        );
        $this->db->select([
            'a.NOMOR_SURAT',
            'a.ASAL',
            'a.PERIHAL',
            'a.ID_SURAT_MASUK',
            'a.ID_DISPOSISI',
            'a.ID_PEMBERI',
            'a.ID_PENERIMA',
            'a.TANGGAL_DISPOSISI',
            'a.TANGGAL_SELESAI',
            'a.INSTRUKSI',
            'a.CATATAN',
            'a.STATUS',
            'b.ID_KATEGORI_SRT_MASUK',
            'b.ID_PENGIRIM',
            'b.ISI_SURAT',
            'b.TUJUAN'
        ]);
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        $this->db->where($where);
        $this->db->order_by('a.TANGGAL_DISPOSISI', 'DESC');
        $this->db->limit(5);
        // $query_string = $this->db->get_compiled_select();

        // echo $query_string;
        // exit();
        return $this->db->get()->result();
    }

    public function getAllBagian($id_user)
    {
        #$this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, array('ID_PENERIMA' => $id_user))->result();
        // $where = array(
        //     'a.ID_PENERIMA' => $id_user
        // );
        // $this->db->select('a.NOMOR_SURAT,a.ASAL,a.PERIHAL,a.ID_SURAT_MASUK,a.ID_DISPOSISI, a.ID_PEMBERI,a.ID_PENERIMA,a.TANGGAL_DISPOSISI,a.TANGGAL_SELESAI,a.INSTRUKSI,a.CATATAN,a.STATUS');
        $where = array(
            'a.ID_PENERIMA' => $id_user
        );
        $this->db->select([
            'a.NOMOR_SURAT',
            'a.ASAL',
            'a.PERIHAL',
            'a.ID_SURAT_MASUK',
            'a.ID_DISPOSISI',
            'a.ID_PEMBERI',
            'a.ID_PENERIMA',
            'a.TANGGAL_DISPOSISI',
            'a.TANGGAL_SELESAI',
            'a.INSTRUKSI',
            'a.CATATAN',
            'a.STATUS',
            'b.ID_PENGIRIM',
            'b.ISI_SURAT',
            'b.TUJUAN',
        ]);
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        $this->db->where($where);
        $this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;
        #exit();
        return $this->db->get()->result();
    }

    public function getAllDisposisiUser($id_user)
    {
        #$this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, array('ID_PENERIMA' => $id_user))->result();
        $where = array(
            'a.ID_PENERIMA' => $id_user
        );
        $this->db->select([
            'a.NOMOR_SURAT',
            'a.ASAL',
            'a.PERIHAL',
            'a.ID_SURAT_MASUK',
            'a.ID_DISPOSISI',
            'a.ID_PEMBERI',
            'a.ID_PENERIMA',
            'a.TANGGAL_DISPOSISI',
            'a.TANGGAL_SELESAI',
            'a.INSTRUKSI',
            'a.CATATAN',
            'a.STATUS',
            'b.ID_PENGIRIM',
            'b.ISI_SURAT',
            'b.TUJUAN'
        ]);
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        $this->db->where($where);
        $this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;
        #exit();
        return $this->db->get()->result();
    }

    public function getAll()
    {
        #$this->db->order_by('TANGGAL_DISPOSISI', 'DESC');
        #return $this->db->get($this->_table)->result();
        $this->db->select('a.NOMOR_SURAT,a.ASAL,a.PERIHAL,a.ID_SURAT_MASUK,a.ID_DISPOSISI, a.ID_PEMBERI,a.ID_PENERIMA,a.TANGGAL_DISPOSISI,a.TANGGAL_SELESAI,a.INSTRUKSI,a.CATATAN,a.STATUS');
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        #$this->db->order_by('ID_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;
        #exit();
        return $this->db->get()->result();
    }

    public function getById($id, $id_user)
    {
        $where = array(
            'a.ID_SURAT_MASUK' => $id,
            'a.ID_PENERIMA' => $id_user
        );
        $this->db->select([
            'a.ID_DISPOSISI',
            'a.ID_SURAT_MASUK',
            'a.STATUS AS STATUS_DISPOSISI',
            'a.NOMOR_SURAT AS NO_SURAT',
            'a.ID_PEMBERI',
            'a.ID_PENERIMA',
            'a.TANGGAL_DISPOSISI',
            'a.TANGGAL_SELESAI',
            'a.INSTRUKSI',
            'a.CATATAN',
            'b.STATUS',
            'b.FILE_SURAT',
            'b.JENIS_SURAT',
	    'b.ISI_SURAT',
	    'b.ID_PENGIRIM',

        ]);
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        $this->db->where($where);
        #$this->db->order_by('ID_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        // $query_string = $this->db->get_compiled_select();

        // echo $query_string;
        // exit();
        return $this->db->get()->row();
        #return $this->db->get_where($this->_table, array('ID_DISPOSISI' => $id))->row();
    }

    public function getBySurat($id_surat)
    {
        $where = array(
            'a.ID_SURAT_MASUK' => $id_surat
        );
        $this->db->select('a.ID_SURAT_MASUK,a.ID_DISPOSISI, a.ID_PEMBERI,a.ID_PENERIMA,a.TANGGAL_DISPOSISI,' .
            'a.TANGGAL_SELESAI,a.INSTRUKSI,a.CATATAN,b.STATUS,a.STATUS AS STATUS_DISPOSISI');
        $this->db->from('t_disposisi AS a');
        $this->db->join('t_surat_masuk AS b', 'b.ID_SURAT_MASUK = a.ID_SURAT_MASUK');
        $this->db->where($where);
        #$this->db->order_by('ID_DISPOSISI', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        // $query_string = $this->db->get_compiled_select();

        // echo $query_string;
        // exit();
        return $this->db->get()->result();
    }

    public function save()
    {
        $post = $this->input->post();
        #$dok = $this->getById($post["id_surat_masuk"]);
        #print_r($dok);
        #exit();

        $this->ID_SURAT_MASUK = $post["id_surat_masuk"];
        #$this->STATUS = $post["status"];
        $this->NOMOR_SURAT = $post["nomor_surat"];
        $this->TANGGAL_DISPOSISI = $post["tanggal_disposisi"];
        #$this->TANGGAL_SELESAI = $post["tanggal_selesai"];
        #$this->PERIHAL = $post["perihal"];
        #$this->ASAL = $post["asal"];
        $this->ID_PEMBERI = $post["id_pemberi"];
        $this->ID_PENERIMA = $post["id_penerima"];
        $this->INSTRUKSI = $post["instruksi"];
        $this->CATATAN = $post["catatan"];
        $this->IS_READ = $post["is_read"];
        $this->USER_ID = $post["user_id"];
        $this->STATUS = '0';
        $this->db->insert($this->_table, $this);
        if ($this->db->affected_rows() > 0) {
            //update status surat masuk
            $this->db->set('STATUS', '3');
            $this->db->where('ID_SURAT_MASUK', $this->ID_SURAT_MASUK);
            $this->db->update($this->_table2);
            if ($this->db->affected_rows() >= 0) {
                //update status surat keluar
                $this->db->set('STATUS', '3');
                $this->db->where('ID_SURAT_KELUAR', $this->ID_SURAT_MASUK);
                $this->db->update($this->_table3);
                if ($this->db->affected_rows() >= 0) {
                    //simpan log
                    $log_data = [
                        'USERNAME' => $this->referensi->nmPegawai($post["id_pemberi"]),
                        'IP_ADDRESS' => $this->get_client_ip(),
                        'ID_SURAT' => $post["id_surat_masuk"],
                        'CATATAN' => 'Disposisi surat masuk ke ' . $this->referensi->nmPegawai($post["id_penerima"])
                    ];

                    #$this->db->set($log_data);
                    #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
                    #echo $query;
                    #exit();
                    $this->db->insert('t_log_surat', $log_data);
                }
            }
        }
    }

    public function update_status_surat()
    {
        $post = $this->input->post();


        $this->STATUS = $post["status"];
        $this->db->where(array('ID_SURAT_MASUK' => $post["id_surat_masuk"]));
        $this->db->set($this);
        // echo $this->db->get_compiled_update($this->_table);
        // exit();
        $this->db->update($this->_table);
        if ($this->db->affected_rows() > 0) {
            #if ($this->db->affected_rows() > 0) {
            //update status surat masuk
            $this->db->set('STATUS', $post["status"]);
            $this->db->where('ID_SURAT_MASUK', $this->ID_SURAT_MASUK);
            $this->db->update($this->_table2);
            if ($this->db->affected_rows() >= 0) {
                //update status surat keluar
                $this->db->set('STATUS', $post["status"]);
                $this->db->where('ID_SURAT_KELUAR', $this->ID_SURAT_MASUK);
                $this->db->update($this->_table3);
                if ($this->db->affected_rows() >= 0) {
                    //simpan log
                    $log_data = [
                        'USERNAME' => $this->referensi->nmPegawai($post["user_id"]),
                        'IP_ADDRESS' => $this->get_client_ip(),
                        'ID_SURAT' => $post["id_surat_masuk"],
                        'CATATAN' => 'Surat masuk ' . nm_status_surat_masuk($post["status"])
                    ];

                    $this->db->set($log_data);
                    #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
                    #echo $query;
                    #exit();
                    $this->db->insert('t_log_surat', $log_data);
                }
            }
            #}
        }
    }

    public function jmlDisposisi($tahun)
    {
        $rekap_query = "
        SELECT
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 1 FOR 4) AS TAHUN,
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 6 FOR 2) AS BULAN,
        r_bulan.NM_BULAN,
        COUNT(t_disposisi.ID_DISPOSISI) AS JUMLAH
        FROM 
        t_disposisi
        LEFT JOIN
        r_bulan ON SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 6 FOR 2) = r_bulan.KD_BULAN
        WHERE
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 1 FOR 4) = '" . $tahun . "'
        GROUP BY
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 1 FOR 4),
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 6 FOR 2)
        ORDER BY
        SUBSTR(t_disposisi.TANGGAL_DISPOSISI FROM 6 FOR 2) ASC
        ";
        $query = $this->db->query($rekap_query)->result();
        return $query;
    }

    public function delete($id_surat, $id, $id_user)
    {

        #$this->STATUS = $post["status"];
        $this->db->where(array('ID_DISPOSISI' => $id));
        #echo $this->db->get_compiled_delete($this->_table);
        #exit();
        $this->db->delete($this->_table);
        if ($this->db->affected_rows() > 0) {
            //update status surat masuk
            $this->db->set('STATUS', '1');
            $this->db->where('ID_SURAT_MASUK', $id_surat);
            $this->db->update($this->_table2);
            if ($this->db->affected_rows() >= 0) {
                //update status surat keluar
                $this->db->set('STATUS', '2');
                $this->db->where('ID_SURAT_KELUAR', $id_surat);
                $this->db->update($this->_table3);
                if ($this->db->affected_rows() >= 0) {
                    //simpan log
                    $log_data = [
                        'USERNAME' => $this->referensi->nmPegawai($id_user),
                        'IP_ADDRESS' => $this->get_client_ip(),
                        'ID_SURAT' => $id_surat,
                        'CATATAN' => 'Hapus Disposisi Surat masuk '
                    ];

                    $this->db->set($log_data);
                    #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
                    #echo $query;
                    #exit();
                    $this->db->insert('t_log_surat', $log_data);
                }
            }
        }
        #return $this->db->delete($this->_table, array('ID_DISPOSISI' => $id));
    }

    public function cekPakai($id)
    {
        #return $this->db->get_where('t_disposisi', array('ID_SURAT_MASUK' => $id))->num_rows();
        $this->db->where('ID_SURAT_MASUK', $id);
        $compiledSelect = $this->db->get_compiled_select('t_disposisi');

        $this->db->join('t_pegawai_login', 't_pegawai_login.ID_PEGAWAI = t_surat_masuk.ID_TUJUAN');
        $this->db->where(array('ID_BAGIAN' => $id_bagian));
        $this->db->order_by('TGL_DITERIMA', 'DESC');
        $query = $this->db->get($this->_table);
        #echo $this->db->last_query();exit();
        return $query->result();
    }
}
