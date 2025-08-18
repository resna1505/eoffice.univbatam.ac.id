<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_masuk_model extends CI_Model
{

    private $_table = "t_surat_masuk";
    private $_table2 = "t_surat_keluar";

    public function rules()
    {
        return [
            [
                'field' => 'id_kategori_srt_masuk',
                'label' => 'Jenis Surat Masuk',
                'rules' => 'required'
            ],

            [
                'field' => 'nm_pengirim',
                'label' => 'Nama Pengirim',
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

    public function getAll()
    {
        #$this->db->order_by('TGL_DITERIMA', 'DESC');
        #return $this->db->get($this->_table)->result();
        $this->db->select('a.*,b.NM_PEGAWAI');
        $this->db->from('t_surat_masuk AS a');
        $this->db->join('t_pegawai AS b', 'b.ID_PEGAWAI = a.ID_TUJUAN');
        $this->db->order_by('TGL_DITERIMA', 'DESC');

        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;exit();
        return $this->db->get()->result();
    }

    public function getAllSuratByUser($id)
    {
        $this->db->select([
            'a.ID_SURAT_MASUK',
            'a.ID_KATEGORI_SRT_MASUK',
            'b.NM_KATEGORI_SRT_MASUK',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.STATUS',
            'a.ID_TUJUAN',
            'a.TUJUAN',
            'a.USER_ID',
            'a.ID_PENGIRIM',
            'a.JENIS_SURAT',
            // Ambil password dari t_surat_keluar
            'sk.PASSWORD',
            'sk.JENIS_SURAT as JENIS_SURAT_KELUAR',
            'd.NM_PEGAWAI'
        ]);
        $this->db->from('t_surat_masuk AS a');
        $this->db->join('r_kategori_srt_masuk AS b', 'a.ID_KATEGORI_SRT_MASUK = b.ID_KATEGORI_SRT_MASUK', 'left');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.USER_ID');
        
        // JOIN dengan t_surat_keluar berdasarkan ID_SURAT_MASUK = ID_SURAT_KELUAR
        $this->db->join('t_surat_keluar AS sk', 'a.ID_SURAT_MASUK = sk.ID_SURAT_KELUAR', 'left');

        $this->db->where('a.USER_ID', $id);
        $this->db->order_by('a.TGL_SURAT desc,a.NO_SURAT desc');

        return $this->db->get()->result();
    }

    public function receive($id)
    {

        $this->ID_SURAT_MASUK = $id;
        #$this->STATUS = '1';
        //update status tr_surat_keluar
        $this->db->where(array('ID_SURAT_MASUK' => $this->ID_SURAT_MASUK));
        $this->db->set('STATUS', '1');
        #echo $this->db->get_compiled_update($this->_table);
        #exit();
        $this->db->update($this->_table);
        if ($this->db->affected_rows() > 0) {
            $this->db->set('STATUS', '2');
            $this->db->where('ID_SURAT_KELUAR', $this->ID_SURAT_MASUK);
            $this->db->update($this->_table2);
            if ($this->db->affected_rows() > 0) {
                $dok = $this->getById($id);
                //simpan log
                $log_data = [
                    'USERNAME' => $this->referensi->nmPegawai($dok->USER_ID),
                    'IP_ADDRESS' => $this->get_client_ip(),
                    'ID_SURAT' => $id,
                    'CATATAN' => 'Menerima surat masuk'
                ];

                #$this->db->set($log_data);
                #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
                #echo $query;
                #exit();
                $this->db->insert('t_log_surat', $log_data);
            }
        }
    }

    public function getAllByBagian($id_bagian)
    {
        #$this->db->join('t_pegawai_login', 't_pegawai_login.ID_PEGAWAI = t_surat_masuk.ID_TUJUAN');  
        #$this->db->where(array('ID_BAGIAN' => $id_bagian));
        #$this->db->order_by('TGL_DITERIMA', 'DESC');
        #$query=$this->db->get($this->_table);
        #echo $this->db->last_query();exit();
        $where = array('b.ID_BAGIAN' => $id_bagian);
        $this->db->select('a.*,c.NM_PEGAWAI');
        $this->db->from('t_surat_masuk AS a');
        $this->db->join('t_pegawai_login AS b', 'b.ID_BAGIAN = a.ID_TUJUAN');
        $this->db->join('t_pegawai AS c', 'c.ID_PEGAWAI = b.ID_PEGAWAI');
        $this->db->where($where);
        $this->db->order_by('TGL_DITERIMA', 'DESC');

        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;
        #exit();
        return $this->db->get()->result();
    }

    public function getJumlah($id)
    {
        //return $this->db->get($this->_table)->num_rows();
        $this->db->select([
            'a.ID_SURAT_MASUK',
            'a.ID_KATEGORI_SRT_MASUK',
            'b.NM_KATEGORI_SRT_MASUK',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.TUJUAN',
            'a.BODY',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'd.NM_PEGAWAI'

        ]);
        $this->db->from('t_surat_masuk AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_masuk AS b', 'a.ID_KATEGORI_SRT_MASUK = b.ID_KATEGORI_SRT_MASUK', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)
        if ($id != 'admin') {
            $this->db->where('a.USER_ID', $id);
        }

        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;exit();

        return $this->db->get()->num_rows();
    }

    public function getTerbaru($id)
    {
        $this->db->select([
            'a.ID_SURAT_MASUK',
            'a.ID_KATEGORI_SRT_MASUK',
            'b.NM_KATEGORI_SRT_MASUK',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.TUJUAN',
            'a.BODY',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'd.NM_PEGAWAI',
            'a.ID_PENGIRIM'

        ]);
        $this->db->from('t_surat_masuk AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_masuk AS b', 'a.ID_KATEGORI_SRT_MASUK = b.ID_KATEGORI_SRT_MASUK', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)
        if ($id != 'admin') {
            $this->db->where('a.USER_ID', $id);
        }
        $this->db->order_by('a.TGL_SURAT', 'desc');
        $this->db->limit(5);
        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;exit();
        return $this->db->get()->result();
    }


    public function getById($id)
    {
        $this->db->select([
            'a.ID_SURAT_MASUK',
            'a.ID_KATEGORI_SRT_MASUK',
            'a.JENIS_SURAT',           
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.TUJUAN',
            'a.BODY',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'a.APPROVAL_STATUS',
            'a.APPROVED_BY',
            'a.APPROVAL_DATE',
            'a.ID_PENGIRIM',
            // Ambil password dari t_surat_keluar
            'sk.PASSWORD',
            'sk.JENIS_SURAT as JENIS_SURAT_KELUAR',
            'b.STATUS AS STATUS_DISPOSISI'
        ]);
        $this->db->from('t_surat_masuk AS a');
        $this->db->join('t_disposisi AS b', 'a.ID_SURAT_MASUK = b.ID_SURAT_MASUK', 'left');
        
        // JOIN dengan t_surat_keluar berdasarkan ID_SURAT_MASUK = ID_SURAT_KELUAR
        $this->db->join('t_surat_keluar AS sk', 'a.ID_SURAT_MASUK = sk.ID_SURAT_KELUAR', 'left');
        
        $this->db->where('a.ID_SURAT_MASUK', $id);
        
        return $this->db->get()->row();
    }

    public function getByFile($file)
    {
        return $this->db->get_where($this->_table, array('FILE_SURAT' => $file))->row();
    }

    public function getByIdAkses($id)
    {
        $where = array('ID_BAGIAN' => $id);
        $this->db->order_by('ID_SURAT_MASUK', 'DESC');
        return $this->db->get_where($this->_table, $where)->result();
    }

    public function getByIdTujuan($id)
    {

        #$where = array('ID_TUJUAN' => $id);
        #$this->db->order_by('ID_SURAT_MASUK', 'DESC');
        #return $this->db->get_where($this->_table, $where)->result();
        $where = array('a.ID_TUJUAN' => $id);
        $this->db->select('a.ID_SURAT_MASUK, a.NO_SURAT,a.TGL_SURAT,a.NM_PENGIRIM,' .
            'a.ISI_SURAT,a.STATUS,a.ID_TUJUAN,a.USER_ID,b.NM_PEGAWAI');
        $this->db->from('t_surat_masuk AS a');
        $this->db->join('t_pegawai AS b', 'b.ID_PEGAWAI = a.ID_TUJUAN');
        $this->db->where($where);
        $this->db->order_by('ID_SURAT_MASUK', 'DESC');

        #$query_string = $this->db->get_compiled_select();

        #echo $query_string;exit();
        return $this->db->get()->result();
    }

    public function cekPakai($id)
    {
        return $this->db->get_where('t_disposisi', array('ID_SURAT_MASUK' => $id))->num_rows();
    }

    public function getAllBelumDisposisi()
    {
        $query_rekap = "
        SELECT * FROM t_surat_masuk WHERE NOT EXISTS (SELECT * FROM t_disposisi WHERE t_surat_masuk.ID_SURAT_MASUK = t_disposisi.ID_SURAT_MASUK) AND STATUS = '1' ORDER BY ID_SURAT_MASUK DESC;
        ";
        $query = $this->db->query($query_rekap);
        return $query->result();
    }

    public function getByAksesStatus($id, $sts) //rekap surat berdasarkan id bgian dan status surat
    {
        $where = array(
            'ID_BAGIAN' => $id,
            'STATUS' => $sts
        );
        $this->db->order_by('TGL_DITERIMA', 'DESC');
        return $this->db->get_where($this->_table, $where)->result();
    }

    public function getTglAkses($id, $tgl)
    {
        $where = array(
            'ID_BAGIAN' => $id,
            'TGL_DITERIMA' => $tgl
        );
        $this->db->order_by('ID_SURAT_MASUK', 'DESC');
        return $this->db->get_where($this->_table, $where)->result();
    }

    public function getByTanggal($tgl)
    {
        $where = array(
            'TGL_DITERIMA' => $tgl
        );
        $this->db->order_by('ID_SURAT_MASUK', 'DESC');
        return $this->db->get_where($this->_table, $where)->result();
    }


    public function save()
    {
        $post = $this->input->post();
        $this->FILE_SURAT = $this->_uploadFile();
        if ($this->FILE_SURAT[0] == 'error') {
            #return $this->FILE_SURAT[1];
            return array('error', $this->FILE_SURAT[1]);
            exit();
        }

        $this->ID_SURAT_MASUK = uniqid();
        $this->ID_KATEGORI_SRT_MASUK = $post["id_kategori_srt_masuk"];
        $this->KODE = $post["kode"];
        $this->NO_AGENDA = $post["no_agenda"];
        $this->NM_PENGIRIM = $post["nm_pengirim"];
        $this->NO_SURAT = $post["no_surat"];
        $this->TGL_SURAT = $post["tgl_surat"];
        $this->TGL_DITERIMA = $post["tgl_diterima"];
        $this->ISI_SURAT = $post["isi_surat"];
        $this->STATUS = $post["STATUS"];
        $this->FILE_SURAT = $this->FILE_SURAT[1];
        $this->ID_TUJUAN = $post["id_tujuan"];
        $this->USER_ID = $post["user_id"];
        #$this->db->set($this);
        #echo $this->db->get_compiled_insert($this->_table);
        #exit();
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        if (!empty($_FILES["file_surat"]["name"])) {
            $this->FILE_SURAT = $this->_uploadFile();
            if ($this->FILE_SURAT[0] == 'error') {
                #return $this->FILE_SURAT[1];
                return array('error', $this->FILE_SURAT[1]);
                exit();
            } else {
                if (!empty($post["old_file_surat"])) {
                    #echo base_url().'/upload/surat_keluar/'.$post["old_file_surat"];exit();
                    unlink(FCPATH . '/upload/surat_masuk/' . $post["old_file_surat"]);
                }
                $this->FILE_SURAT = $this->FILE_SURAT[1];
            }
        } else {
            $this->FILE_SURAT = $post["old_file_surat"];
        }

        $this->ID_SURAT_MASUK = $post["id_surat_masuk"];
        $this->ID_KATEGORI_SRT_MASUK = $post["id_kategori_srt_masuk"];
        $this->KODE = $post["kode"];
        $this->NO_AGENDA = $post["no_agenda"];
        $this->NM_PENGIRIM = $post["nm_pengirim"];
        $this->NO_SURAT = $post["no_surat"];
        $this->TGL_SURAT = $post["tgl_surat"];
        $this->TGL_DITERIMA = $post["tgl_diterima"];
        $this->ISI_SURAT = $post["isi_surat"];
        $this->STATUS = $post["STATUS"];
        $this->FILE_SURAT = $this->FILE_SURAT;

        $this->ID_TUJUAN = $post["id_tujuan"];
        $this->USER_ID = $post["user_id"];

        $this->db->where(array('ID_SURAT_MASUK' => $post["id_surat_masuk"]));
        $this->db->set($this);
        #echo $this->db->get_compiled_update($this->_table);
        #exit();
        $this->db->update($this->_table);
        return 'success';
    }

    public function update_status_surat()
    {
        $post = $this->input->post();



        $this->STATUS = $post["status"];

        $this->db->where(array('ID_SURAT_MASUK' => $post["id_surat_masuk"]));
        $this->db->set($this);
        #echo $this->db->get_compiled_update($this->_table);
        #exit();
        $this->db->update($this->_table);
        if ($this->db->affected_rows() > 0) {
            $dok = $this->getById($post["id_surat_masuk"]);
            //simpan log
            $log_data = [
                'USERNAME' => $this->referensi->nmPegawai($dok->USER_ID),
                'IP_ADDRESS' => $this->get_client_ip(),
                'ID_SURAT' => $post["id_surat_masuk"],
                'CATATAN' => 'Surat masuk ' . nm_status_surat_masuk($post["status"])
            ];

            #$this->db->set($log_data);
            #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
            #echo $query;
            #exit();
            $this->db->insert('t_log_surat', $log_data);
        }
        #return 'success';
    }

    private function _uploadFile()
    {
        $config['upload_path']          = './upload/surat_masuk/';
        $config['allowed_types']        = 'pdf';
        $config['overwrite']            = true;
        $config['max_size']             = 1024; // 1MB
        $config['encrypt_name']            = TRUE;
        //$config['file_name']            = $this->ID_USER;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file_surat')) {
            return array('success', $this->upload->data("file_name"));
        }
        #else{
        return array('error', $this->upload->display_errors());
        #}

    }

    private function _deleteFile($id)
    {
        $dok = $this->Surat_masuk_model->getById($id);
        $filename = explode(".", $dok->FILE_SURAT)[0];
        return array_map('unlink', glob(FCPATH . "upload/surat_masuk/$filename.*"));
    }

    public function delete($id)
    {
        $this->_deleteFile($id);
        return $this->db->delete($this->_table, array('ID_SURAT_MASUK' => $id));
    }

    public function deleteDisposisi($id)
    {
        #$this->_deleteFile($id);
        return $this->db->delete($this->_table, array('ID_SURAT_MASUK' => $id));
    }

    public function jmlSurat($tahun)
    {
        $rekap_query = "
        SELECT
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 1 FOR 4) AS TAHUN,
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 6 FOR 2) AS BULAN,
        r_bulan.NM_BULAN,
        COUNT(t_surat_masuk.ID_SURAT_MASUK) AS JUMLAH
        FROM 
        t_surat_masuk
        LEFT JOIN
        r_bulan ON SUBSTR(t_surat_masuk.TGL_SURAT FROM 6 FOR 2) = r_bulan.KD_BULAN
        WHERE
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 1 FOR 4) = '" . $tahun . "'
        GROUP BY
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 1 FOR 4),
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 6 FOR 2)
        ORDER BY
        SUBSTR(t_surat_masuk.TGL_SURAT FROM 6 FOR 2) ASC
        ";
        $query = $this->db->query($rekap_query)->result();
        return $query;
    }

    public function canApprove($user_id)
    {
        $this->db->select('PIMPINAN, APPROVE');
        $this->db->from('t_pegawai_login');
        $this->db->where('ID_PEGAWAI', $user_id);
        $result = $this->db->get()->row();
        
        if ($result) {
            // Handle string dan integer
            $is_pimpinan = ($result->PIMPINAN == 1 || $result->PIMPINAN == '1');
            $can_approve = ($result->APPROVE == 1 || $result->APPROVE == '1');
            return ($is_pimpinan && $can_approve);
        }
        
        return false;
    }

    public function verifyPassword($surat_id, $input_password)
    {
        $surat = $this->getById($surat_id);
        
        // Cek JENIS_SURAT_KELUAR dan PASSWORD dari t_surat_keluar
        if ($surat && $surat->JENIS_SURAT_KELUAR == 3 && !empty($surat->PASSWORD)) {
            return password_verify($input_password, $surat->PASSWORD);
        }
        
        return true; // Jika bukan surat rahasia, return true
    }
}
