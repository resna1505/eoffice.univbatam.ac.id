<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_keluar_model extends CI_Model
{

    private $_table = "t_surat_keluar";

    // GANTI method rules() dengan ini (sesuai form Anda):
    public function rules()
    {
        return [
            [
                'field' => 'id_kategori_srt_keluar',
                'label' => 'Jenis Surat Keluar',
                'rules' => 'required'
            ],
            [
                'field' => 'no_surat',          // TAMBAH INI
                'label' => 'No Surat',
                'rules' => 'required'
            ],
            [
                'field' => 'tgl_surat',         // TAMBAH INI
                'label' => 'Tanggal Surat',
                'rules' => 'required'
            ],
            [
                'field' => 'id_tujuan',       // GANTI dari 'id_tujuan' ke 'id_approval'
                'label' => 'Approval',
                'rules' => 'required'
            ],
            [
                'field' => 'tujuan',            // TETAP 'tujuan' sesuai form
                'label' => 'Tujuan',
                'rules' => 'required'
            ],
            [
                'field' => 'isi_surat',         // TAMBAH INI
                'label' => 'Isi / Hal',
                'rules' => 'required'
            ],
            [
                'field' => 'body',              // TETAP 'body' sesuai form
                'label' => 'Body Surat',
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

    public function getJumlah($id)
    {
        #return $this->db->get($this->_table)->num_rows();
        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
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
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)
        if ($id != 'admin') {
            $this->db->where('a.USER_ID', $id);
        }

        return $this->db->get()->num_rows();
    }

    public function getTerbaru($id)
    {
        // $this->db->order_by('TGL_SURAT', 'DESC');
        // $this->db->limit(5);
        // return $this->db->get_where($this->_table, array('STATUS' => '2'))->result();

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
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
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)
        if ($id != 'admin') {
            $this->db->where('a.USER_ID', $id);
        }
        $this->db->order_by('a.TGL_SURAT', 'desc');
        $this->db->limit(5);

        return $this->db->get()->result();
    }


    public function getAll()
    {
        /*$this->db->select('t_surat_keluar.ID_SURAT_KELUAR');
        $this->db->select('t_surat_keluar.ID_KATEGORI_SRT_KELUAR');
        $this->db->select('r_kategori_srt_keluar.NM_KATEGORI_SRT_KELUAR');
        $this->db->select('t_surat_keluar.NO_SURAT');
        $this->db->select('t_surat_keluar.NO_AGENDA');
        $this->db->select('t_surat_keluar.KODE');
        $this->db->select('t_surat_keluar.TGL_SURAT');
        $this->db->select('t_surat_keluar.ISI_SURAT');
        $this->db->select('t_surat_keluar.TUJUAN');
        $this->db->select('t_surat_keluar.FILE_SURAT');
        $this->db->select('t_surat_keluar.FILE_TTD');
        $this->db->select('t_surat_keluar.STATUS');
        $this->db->select('t_surat_keluar.CATATAN');
        $this->db->join('r_kategori_srt_keluar', 't_surat_keluar.ID_KATEGORI_SRT_KELUAR = r_kategori_srt_keluar.ID_KATEGORI_SRT_KELUAR', 'LEFT');
        $this->db->order_by('t_surat_keluar.ID_SURAT_KELUAR', 'DESC');
        return $this->db->get_where($this->_table, array('t_surat_keluar.STATUS ' => '2'))->result();*/

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'd.NM_PEGAWAI',
            'a.TUJUAN'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)

        #	$this->db->where('a.USER_ID', $id);
        $this->db->order_by('a.TGL_SURAT', 'desc');

        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();
    }

    public function getAllBagian($id)
    {
        /*$this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_SURAT',
			'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.TUJUAN',
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
			'a.ID_APPROVAL',
			'a.ID_TUJUAN',
			'c.NM_BAGIAN AS TUJUAN',
			'd.NM_PEGAWAI',
        ]);
		$this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
		$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_TUJUAN');
		$this->db->join('t_pegawai AS d', 'a.ID_APPROVAL = d.ID_PEGAWAI');
        $this->db->where(array('a.STATUS ' => '2','c.ID_BAGIAN'=>$id));
        $this->db->order_by('a.TGL_SURAT', 'desc');
        return $this->db->get()->result();*/

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_SURAT',
            'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_APPROVAL',
            'a.ID_TUJUAN',
            'e.NM_BAGIAN AS TUJUAN',
            'd_approval.NM_PEGAWAI',
            'c_user.ID_BAGIAN',
            'a.USER_ID'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('t_pegawai_login AS c_user', 'a.USER_ID = c_user.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d_user', 'd_user.ID_PEGAWAI = c_user.ID_PEGAWAI');
        $this->db->join('t_pegawai_login AS c_approval', 'a.ID_APPROVAL = c_approval.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d_approval', 'c_approval.ID_PEGAWAI = d_approval.ID_PEGAWAI');

        $this->db->join('r_bagian AS e', 'e.ID_BAGIAN = a.ID_TUJUAN');

        // Mulai grouping untuk OR
        $this->db->group_start();
        $this->db->where('c_user.ID_BAGIAN', $id);
        $this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)
        $this->db->group_end();
        // Akhir grouping untuk OR

        $this->db->where('a.STATUS =', '2');
        $this->db->order_by('a.TGL_SURAT', 'desc');

        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();
    }

    public function getAllDraft()
    {

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            /*'a.ID_APPROVAL',*/
            'a.ID_TUJUAN',
            'c.NM_BAGIAN AS TUJUAN',
            'd.NM_PEGAWAI',
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_TUJUAN');
        #$this->db->join('t_pegawai AS d', 'a.ID_APPROVAL = d.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d', 'a.ID_TUJUAN = d.ID_PEGAWAI');
        $this->db->where('a.STATUS !=', '2');
        $this->db->order_by('a.TGL_SURAT', 'desc');
        return $this->db->get()->result();

        #$this->db->join('r_kategori_srt_keluar', 't_surat_keluar.ID_KATEGORI_SRT_KELUAR = r_kategori_srt_keluar.ID_KATEGORI_SRT_KELUAR', 'LEFT');
        #$this->db->order_by('t_surat_keluar.ID_SURAT_KELUAR', 'DESC');
        #return $this->db->get_where($this->_table, array('t_surat_keluar.STATUS !=' => '2'))->result();

    }

    public function getAllDraftByBagian($id)
    {

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            /*'a.TUJUAN',*/
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            /*'a.ID_APPROVAL',*/
            'a.ID_TUJUAN',
            'e.NM_BAGIAN AS TUJUAN',
            /*'d_approval.NM_PEGAWAI',*/
            'c_user.ID_BAGIAN',
            'a.USER_ID'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('t_pegawai_login AS c_user', 'a.USER_ID = c_user.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d_user', 'd_user.ID_PEGAWAI = c_user.ID_PEGAWAI');
        #$this->db->join('t_pegawai_login AS c_approval', 'a.ID_APPROVAL = c_approval.ID_PEGAWAI'); 
        #$this->db->join('t_pegawai AS d_approval', 'c_approval.ID_PEGAWAI = d_approval.ID_PEGAWAI'); 

        $this->db->join('r_bagian AS e', 'e.ID_BAGIAN = a.ID_TUJUAN');

        $this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)

        $this->db->where('a.STATUS !=', '2');
        $this->db->order_by('a.TGL_SURAT', 'desc');

        ##$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();

        #$this->db->join('r_kategori_srt_keluar', 't_surat_keluar.ID_KATEGORI_SRT_KELUAR = r_kategori_srt_keluar.ID_KATEGORI_SRT_KELUAR', 'LEFT');
        #$this->db->order_by('t_surat_keluar.ID_SURAT_KELUAR', 'DESC');
        #return $this->db->get_where($this->_table, array('t_surat_keluar.STATUS !=' => '2'))->result();

    }

    public function getAllSuratByBagianv1($id)
    {

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.TUJUAN',
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_APPROVAL',
            'a.ID_TUJUAN',
            'e.NM_BAGIAN AS TUJUAN',
            'd_approval.NM_PEGAWAI',
            'c_user.ID_BAGIAN',
            'a.USER_ID'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('t_pegawai_login AS c_user', 'a.USER_ID = c_user.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d_user', 'd_user.ID_PEGAWAI = c_user.ID_PEGAWAI');
        $this->db->join('t_pegawai_login AS c_approval', 'a.ID_APPROVAL = c_approval.ID_PEGAWAI');
        $this->db->join('t_pegawai AS d_approval', 'c_approval.ID_PEGAWAI = d_approval.ID_PEGAWAI');

        $this->db->join('r_bagian AS e', 'e.ID_BAGIAN = a.ID_TUJUAN');

        $this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)

        #$this->db->where('a.STATUS !=', '2');
        $this->db->order_by('a.TGL_SURAT', 'desc');

        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();
    }

    public function getAllSuratByBagian($id)
    {

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'd.NM_PEGAWAI'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)

        #$this->db->where('a.STATUS !=', '2');
        $this->db->order_by('a.TGL_SURAT', 'desc');

        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();
    }

    public function getAllSuratByUser($id)
    {

        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.NO_SURAT',
            'a.TUJUAN',
            'a.BODY',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'a.USER_ID',
            'a.JENIS_SURAT',
            'd.NM_PEGAWAI'
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');

        #$this->db->where('c_user.ID_BAGIAN', $id);
        #$this->db->or_where('a.ID_TUJUAN', $id); // ID_TUJUAN dari tabel surat keluar (a)

        $this->db->where('a.USER_ID', $id);
        $this->db->order_by('a.TGL_SURAT', 'desc');

        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();

        return $this->db->get()->result();
    }

    public function getAllDraftByApproval($id)
    {
        $where = array('a.STATUS !=' => '2', 'a.USER_ID' => $id);
        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            /*'a.TUJUAN',*/
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            /*'a.ID_APPROVAL',*/
            'a.ID_TUJUAN',
            'c.NM_BAGIAN AS TUJUAN',
            'd.NM_PEGAWAI',
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_TUJUAN');
        $this->db->join('t_pegawai AS d', 'a.ID_APPROVAL = d.ID_PEGAWAI');
        $this->db->where($where);
        $this->db->order_by('a.TGL_SURAT', 'desc');

        // $query_string = $this->db->get_compiled_select();

        // echo $query_string;
        // exit();
        return $this->db->get()->result();
    }

    public function getAllDraftByPimpinan($id)
    {
        $where = array('a.STATUS !=' => '2', 'a.ID_APPROVAL' => $id);
        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.KODE',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            /*'a.TUJUAN',*/
            'a.FILE_SURAT',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            /*'a.ID_APPROVAL',*/
            'a.ID_TUJUAN',
            'c.NM_BAGIAN AS TUJUAN',
            'd.NM_PEGAWAI',
        ]);
        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        $this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_TUJUAN');
        $this->db->join('t_pegawai AS d', 'a.ID_APPROVAL = d.ID_PEGAWAI');
        $this->db->where($where);
        $this->db->order_by('a.TGL_SURAT', 'desc');

        // $query_string = $this->db->get_compiled_select();

        // echo $query_string;
        // exit();
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        #return $this->db->get_where($this->_table, array('ID_SURAT_KELUAR' => $id))->row();
        $this->db->select([
            'a.ID_SURAT_KELUAR',
            'a.ID_KATEGORI_SRT_KELUAR',
            'b.NM_KATEGORI_SRT_KELUAR',
            'a.NO_AGENDA',
            'a.KODE',
            'a.BODY',
            'a.TUJUAN',
            'a.TGL_SURAT',
            'a.ISI_SURAT',
            'a.NO_SURAT',
            'a.FILE_SURAT',
            'a.FILE_LAMPIRAN',
            'a.FILE_TTD',
            'a.STATUS',
            'a.CATATAN',
            'a.ID_TUJUAN',
            'd.NM_PEGAWAI',
            'a.USER_ID',
            'a.JENIS_SURAT'
        ]);

        $this->db->from('t_surat_keluar AS a'); // Menggunakan properti _table
        $this->db->join('r_kategori_srt_keluar AS b', 'a.ID_KATEGORI_SRT_KELUAR = b.ID_KATEGORI_SRT_KELUAR', 'left');
        #$this->db->join('r_bagian AS c', 'c.ID_BAGIAN = a.ID_BAGIAN');
        $this->db->join('t_pegawai AS d', 'd.ID_PEGAWAI = a.ID_TUJUAN');
        $this->db->where('a.ID_SURAT_KELUAR', $id);
        #$query_string = $this->db->get_compiled_select();
        #echo $query_string;
        #exit();
        return $this->db->get()->row();
    }

    public function getByFile($file)
    {
        return $this->db->get_where($this->_table, array('FILE_SURAT' => $file))->row();
    }

    public function getRelease($id)
    {
        return $this->db->get_where($this->_table, array('ID_SURAT_KELUAR' => $id, 'STATUS' => '2'))->row();
    }

    public function save_draf()
    {
        $post = $this->input->post();
        $this->FILE_SURAT = $this->_uploadFile();
        if ($this->FILE_SURAT[0] == 'error') {
            #return $this->FILE_SURAT[1];
            return array('error', $this->FILE_SURAT[1]);
            exit();
        }

        $this->ID_SURAT_KELUAR = uniqid();
        $this->ID_KATEGORI_SRT_KELUAR = $post["id_kategori_srt_keluar"];
        $this->TGL_DITERIMA = NULL;
        $this->NO_AGENDA = $post["no_agenda"];
        $this->KODE = $post["kode"];
        $this->NO_SURAT = NULL;
        $this->TGL_SURAT = $post["tgl_surat"];
        $this->ISI_SURAT = $post["isi_surat"];
        #$this->TUJUAN = $post["tujuan"];
        $this->ID_TUJUAN = $post["id_tujuan"];
        $this->FILE_SURAT = $this->FILE_SURAT[1];
        $this->FILE_TTD = NULL;
        $this->STATUS = '0';
        $this->TGL_TTD = NULL;
        $this->NM_PIMPINAN = NULL;
        $this->CATATAN = NULL;
        $this->USER_ID = $post["user_id"];
        $this->ID_APPROVAL = $post["id_approval"];
        $this->db->insert($this->_table, $this);
    }

    public function update_draf()
    {
        $post = $this->input->post();
        /*if (!empty($_FILES["file_surat"]["name"])) {
            $this->FILE_SURAT = $this->_uploadFile();
        } else {
            $this->FILE_SURAT = $post["old_file_surat"];
        }
        #print_r($this->FILE_SURAT[0]);exit();
        if ($this->FILE_SURAT[0] == 'error') {
            #return $this->FILE_SURAT[1];
            return array('error', $this->FILE_SURAT[1]);
            exit();
        }*/
        $this->ID_SURAT_KELUAR = $post["id_surat_keluar"];
        $this->ID_KATEGORI_SRT_KELUAR = $post["id_kategori_srt_keluar"];
        $this->TGL_DITERIMA = NULL;
        $this->NO_AGENDA = $post["no_agenda"];
        $this->KODE = $post["kode"];
        $this->NO_SURAT = NULL;
        $this->TGL_SURAT = $post["tgl_surat"];
        $this->ISI_SURAT = $post["isi_surat"];
        #$this->TUJUAN = $post["id_tujuan"];
        $this->ID_TUJUAN = $post["id_tujuan"];
        if (!empty($_FILES["file_surat"]["name"])) {
            #$this->_deleteFile($post["id_surat_keluar"]);
            #echo base_url() . '/upload/surat_keluar/' . $post["old_file_surat"];exit();
            #exit();
            $this->FILE_SURAT = $this->_uploadFile();
            if ($this->FILE_SURAT[0] == 'error') {
                #return $this->FILE_SURAT[1];
                return array('error', $this->FILE_SURAT[1]);
                exit();
            } else {
                unlink(base_url() . '/upload/surat_keluar/' . $post["old_file_surat"]);
            }
        } else {
            $this->FILE_SURAT = $post["old_file_surat"];
        }
        $this->FILE_SURAT = $this->FILE_SURAT[1];
        $this->FILE_TTD = NULL;
        $this->STATUS = '0';
        $this->TGL_TTD = NULL;
        $this->NM_PIMPINAN = NULL;
        $this->CATATAN = NULL;
        $this->USER_ID = $post["user_id"];
        $this->ID_APPROVAL = $post["id_approval"];
        #if (!empty($post["file_surat"])) {
        #echo base_url().'/upload/surat_keluar/'.$post["old_file_surat"];exit();
        #    unlink(FCPATH . '/upload/surat_keluar/' . $post["old_file_surat"]);
        #}
        $this->db->where(array('ID_SURAT_KELUAR' => $post["id_surat_keluar"]));

        $this->db->set($this);
        #echo $this->db->get_compiled_update($this->_table);
        #exit();
        $this->db->update($this->_table);
        return 'success';
        #$this->db->update($this->_table, $this, array('ID_SURAT_KELUAR' => $post["id_surat_keluar"]));
    }

    public function save()
    {
        $post = $this->input->post();
        /*$this->FILE_SURAT = $this->_uploadFile();
        if ($this->FILE_SURAT[0] == 'error') {
            #return $this->FILE_SURAT[1];
            return array('error', $this->FILE_SURAT[1]);
            exit();
        }*/

        // Cek apakah ada file yang diupload dengan nama 'file_pdf_surat'
        if (!empty($_FILES['file_pdf_surat']['name'])) {
            // Handle PDF file upload
            $pdfUploadResult = $this->_uploadFile('file_pdf_surat');
            if ($pdfUploadResult[0] === 'error') {
                return ['error', $pdfUploadResult[1]];
                exit();
            }

            $this->FILE_SURAT = $pdfUploadResult[1]; // Store the PDF file name
        }

        $this->ID_SURAT_KELUAR = uniqid();
        $this->ID_KATEGORI_SRT_KELUAR = $post["id_kategori_srt_keluar"];
        $this->JENIS_SURAT = $post["jenis_surat"];

        // Encrypt password jika jenis surat rahasia
        if ($post["jenis_surat"] == '3' && !empty($post["password"])) {
            $this->PASSWORD = password_hash($post["password"], PASSWORD_DEFAULT);
        } else {
            $this->PASSWORD = NULL;
        }

        $this->TGL_DITERIMA = NULL;
        $this->NO_SURAT = $post["no_surat"];
        $this->TGL_SURAT = $post["tgl_surat"];
        $this->ISI_SURAT = $post["isi_surat"];
        $this->TUJUAN = $post["tujuan"];
        $this->ID_TUJUAN = $post["id_tujuan"];
        $this->BODY = $post["body"];
        #$this->FILE_SURAT = $this->FILE_SURAT[1];
        #$this->FILE_LAMPIRAN = $this->FILE_LAMPIRAN[1];
        $this->FILE_TTD = NULL;
        $this->STATUS = '0';
        $this->TGL_TTD = NULL;
        $this->NM_PIMPINAN = NULL;
        $this->CATATAN = NULL;
        $this->USER_ID = $post["user_id"];
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();

        // Handle PDF file update
        $new_pdf_filename = '';
        if (!empty($_FILES["file_pdf_surat"]["name"])) {
            $pdfUploadResult = $this->_uploadFile('file_pdf_surat');
            if ($pdfUploadResult[0] === 'error') {
                return ['error', $pdfUploadResult[1]];
            } else {
                $new_pdf_filename = $pdfUploadResult[1];
                if (!empty($post["old_file_pdf"])) {
                    $old_pdf_path = FCPATH . 'upload/surat_keluar/' . $post["old_file_pdf"];
                    if (file_exists($old_pdf_path)) {
                        unlink($old_pdf_path);
                    }
                }
            }
        } else {
            $new_pdf_filename = $post["old_file_pdf"] ?? NULL;
        }

        // Handle password untuk surat rahasia
        $password_value = NULL;
        if ($post["jenis_surat"] == '3') {
            if (!empty($post["password"])) {
                // Password baru dimasukkan, encrypt
                $password_value = password_hash($post["password"], PASSWORD_DEFAULT);
            } else {
                // Ambil password lama dari database
                $existing_data = $this->getById($post["id_surat_keluar"]);
                $password_value = $existing_data->PASSWORD;
            }
        }

        $data_to_update = [
            'ID_KATEGORI_SRT_KELUAR' => $post["id_kategori_srt_keluar"],
            'TGL_DITERIMA'        => NULL,
            'NO_SURAT'            => $post["no_surat"],
            'TGL_SURAT'           => $post["tgl_surat"],
            'ISI_SURAT'           => $post["isi_surat"],
            'JENIS_SURAT'         => $post["jenis_surat"],
            'PASSWORD'            => $password_value, // Tambahkan ini
            'BODY'                => $post["body"],
            'TUJUAN'              => $post["tujuan"],
            'ID_TUJUAN'           => $post["id_tujuan"],
            'FILE_SURAT'          => $new_pdf_filename,
            'FILE_TTD'            => NULL,
            'STATUS'              => '0',
            'TGL_TTD'             => NULL,
            'NM_PIMPINAN'         => NULL,
            'CATATAN'             => NULL,
            'USER_ID'             => $post["user_id"]
        ];

        $this->db->where('ID_SURAT_KELUAR', $post["id_surat_keluar"]);
        $this->db->update($this->_table, $data_to_update);

        if ($this->db->affected_rows() > 0) {
            return ['success', 'Data updated successfully.'];
        } else {
            return ['info', 'No changes made or record not found.'];
        }
    }

    public function verifyPassword($id_surat, $input_password)
    {
        $surat = $this->getById($id_surat);
        
        if ($surat && $surat->JENIS_SURAT == '3' && !empty($surat->PASSWORD)) {
            return password_verify($input_password, $surat->PASSWORD);
        }
        
        return true; // Jika bukan surat rahasia, return true
    }

    public function send($id)
    {
        $this->load->library('referensi');
        //insert into tr_surat_masuk

        $dok = $this->getById($id);
        #print_r($dok);
        #echo "<br>";
        #exit();
        $this->ID_SURAT_MASUK = $id;
        $this->NO_SURAT = $dok->NO_SURAT;
        $this->ID_KATEGORI_SRT_MASUK = $dok->ID_KATEGORI_SRT_KELUAR;
        $this->JENIS_SURAT = $dok->JENIS_SURAT;
        $this->TGL_SURAT = $dok->TGL_SURAT;
        $this->TUJUAN = $dok->TUJUAN;
        $this->BODY = $dok->BODY;
        $this->ISI_SURAT = $dok->ISI_SURAT;
        $this->FILE_SURAT = $dok->FILE_SURAT;
        $this->FILE_LAMPIRAN = $dok->FILE_LAMPIRAN;
        $this->USER_ID = $dok->ID_TUJUAN;
        $this->ID_TUJUAN = $dok->ID_TUJUAN;
        $this->ID_PENGIRIM = $dok->USER_ID;
        $this->STATUS = 0;
        #$this->db->set($this);
        #$query=$this->db->get_compiled_insert('t_surat_masuk');
        #echo $query;
        #exit();
        $this->db->insert('t_surat_masuk', $this);
        if ($this->db->affected_rows() > 0) {
            $this->ID_SURAT_KELUAR = $id;
            #$this->STATUS = '1';
            //update status tr_surat_keluar
            $this->db->where(array('ID_SURAT_KELUAR' => $this->ID_SURAT_KELUAR));
            $this->db->set('STATUS', '1');
            #echo $this->db->get_compiled_update($this->_table);
            #exit();
            $this->db->update($this->_table);
            if ($this->db->affected_rows() > 0) {
                //simpan log
                $log_data = [
                    'USERNAME' => $this->referensi->nmPegawai($dok->USER_ID),
                    'IP_ADDRESS' => $this->get_client_ip(),
                    'ID_SURAT' => $id,
                    'CATATAN' => 'Mengirim surat keluar ke ' . $this->referensi->nmPegawai($dok->ID_TUJUAN)
                ];

                #$this2->db->set($log_data);
                #$query=$this->db->get_compiled_insert('t_log_surat_keluar');
                #echo $query;
                #exit();
                $this->db->insert('t_log_surat', $log_data);
            }
        }
    }

    public function save_ttd($id_surat, $image_name, $tgl, $nama)
    {
        $this->STATUS = '1';
        $this->TGL_TTD = $tgl;
        $this->NM_PIMPINAN = $nama;
        $this->FILE_TTD = $image_name;
        $this->db->update($this->_table, $this, array('ID_SURAT_KELUAR ' => $id_surat));
    }

    public function save_note()
    {
        $post = $this->input->post();
        $this->ID_SURAT_KELUAR = $post["id_surat_keluar"];
        $this->CATATAN = $post["catatan"];
        $this->db->update($this->_table, $this, array('ID_SURAT_KELUAR ' => $post["id_surat_keluar"]));
    }

    private function _uploadFile($input_name)
    {
        $config['upload_path']          = './upload/surat_keluar/';
        $config['allowed_types']        = 'pdf';
        $config['overwrite']            = true;
        $config['max_size']             = 1024; // 1 MB
        $config['encrypt_name']            = TRUE;
        //$config['file_name']            = $this->ID_USER;

        /*$this->load->library('upload', $config);

        #if ($this->upload->do_upload('file_surat')) {
		if ($this->upload->do_upload($input_name)) {	
            return array('success', $this->upload->data("file_name"));
        }*/

        $this->load->library('upload', $config);
        $this->upload->initialize($config); // Make sure to initialize if you're calling it separately

        if (!$this->upload->do_upload($input_name)) {
            return ['error', $this->upload->display_errors()];
        } else {
            $data = $this->upload->data();
            return ['success', $data['file_name']];
        }

        #return array('error', $this->upload->display_errors());
    }

    private function _uploadZipRar($input_name) {}

    private function _deleteFile($id)
    {
        $dok = $this->Surat_keluar_model->getById($id);
        $filename = explode(".", $dok->FILE_SURAT)[0];

        return array_map('unlink', glob(FCPATH . "upload/surat_keluar/$filename.*"));
    }

    private function _deleteFileTTD($id)
    {
        $dok = $this->Surat_keluar_model->getById($id);
        $filename = explode(".", $dok->FILE_TTD)[0];
        return array_map('unlink', glob(FCPATH . "upload/ttd/$filename.*"));
    }

    public function delete($id)
    {
        $this->_deleteFile($id);
        $this->_deleteFileTTD($id);
        return $this->db->delete($this->_table, array('ID_SURAT_KELUAR' => $id));
    }

    public function jmlSurat($tahun)
    {
        $rekap_query = "
        SELECT
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 1 FOR 4) AS TAHUN,
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 6 FOR 2) AS BULAN,
        r_bulan.NM_BULAN,
        COUNT(t_surat_keluar.ID_SURAT_KELUAR) AS JUMLAH
        FROM 
        t_surat_keluar
        LEFT JOIN
        r_bulan ON SUBSTR(t_surat_keluar.TGL_SURAT FROM 6 FOR 2) = r_bulan.KD_BULAN
        WHERE
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 1 FOR 4) = '" . $tahun . "' AND 
        t_surat_keluar.`STATUS` = '2'
        GROUP BY
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 1 FOR 4),
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 6 FOR 2)
        ORDER BY
        SUBSTR(t_surat_keluar.TGL_SURAT FROM 6 FOR 2) ASC
        ";
        $query = $this->db->query($rekap_query)->result();
        return $query;
    }
}
