<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log_surat_model extends CI_Model
{
    private $_table = "t_log_surat";
    private $_view = "v_log_surat";

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
        $this->db->order_by('ID_LOG', 'DESC');
        return $this->db->get($this->_view)->result();
    }

    public function getByIdSurat($id)
    {
        // $this->db->order_by('WAKTU', 'ASC');
        // return $this->db->get_where($this->_table, array('ID_SURAT' => $id))->result();

        $this->db->select([
            'ID_LOG',
            'USERNAME',
            'CATATAN',
            'WAKTU'
        ]);
        $this->db->from($this->_table);
        $this->db->where(array('ID_SURAT' => $id));
        $this->db->order_by('WAKTU', 'ASC');
        // $query_string = $this->db->get_compiled_select();
        // echo $query_string;
        // exit();
        return $this->db->get()->result();
    }

    public function add_log($username, $id_surat_keluar, $pesan)
    {
        // $this->USERNAME = $username;
        // $this->IP_ADDRESS = $this->get_client_ip();
        // $this->ID_SURAT = $id_surat_keluar;
        // $this->CATATAN = $pesan;
        // Persiapan data
        $data = array(
            'USERNAME'    => $username,
            'IP_ADDRESS'  => $this->get_client_ip(),
            'ID_SURAT'    => $id_surat_keluar,
            'CATATAN'     => $pesan
        );
        // Dapatkan kueri SQL yang sudah dikompilasi
        #$sql_query = $this->db->set($data)->get_compiled_insert($this->_table);

        // Tampilkan kueri SQL untuk debugging
        #echo $sql_query;

        // Hentikan eksekusi setelah menampilkan kueri (opsional, untuk debugging)
        #die();
        $this->db->insert($this->_table, $data);
    }
}
