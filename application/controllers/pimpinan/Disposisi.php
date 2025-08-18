<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Load library phpspreadsheet
require('./excel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Disposisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Disposisi_model');
        $this->load->model('Pegawai_login_model');
        if (!$this->Pegawai_login_model->current_user() or $this->Pegawai_login_model->current_user()->PIMPINAN != 1) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        #print_r($this->data['user']);
        #if ($this->data['user']->ID_BAGIAN != 0) {
        #    $this->data['disposisi'] = $this->Disposisi_model->getAllBagian($this->data['user']->ID_PEGAWAI);
        #} else {
        $this->data['disposisi'] = $this->Disposisi_model->getAllDisposisiUser($this->data['user']->ID_PEGAWAI);
        #}
        #$this->data['disposisi'] = $this->Disposisi_model->getAll();
        $this->data['judul'] = 'Rekapitulasi Disposisi Surat Masuk';
        $this->data['menu'] = 'pimpinan/v_menu';
        $this->data['contents'] = 'pimpinan/v_disposisi';
        $this->load->view('pimpinan/v_home', $this->data);
    }

    public function tampil($id = null)
    {
        $this->load->model('Disposisi_model');
        $this->load->model('Log_surat_model');
        $this->load->model('Auth_model');
        $this->load->model('Surat_masuk_model');
        $this->load->model('Kategori_srt_masuk_model');
        $this->load->model('Pegawai_login_model');
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        #$this->Log_surat_model->add_log($this->Pegawai_login_model->current_user()->USERNAME, $id, "Menampilkan Disposisi surat masuk");
        $this->data['log_surat'] = $this->Log_surat_model->getByIdSurat($id);
        #$this->data['surat'] = $this->Surat_masuk_model->getById($id);
        $this->data['surat_disposisi'] = $this->Disposisi_model->getById($id, $this->data['user']->ID_PEGAWAI);
        $this->data['pimpinan'] = $this->Pegawai_login_model->getPimpinan();
        $this->data['pegawai'] = $this->Pegawai_login_model->getAllPegawai();
        $this->data['bagian'] = $this->Pegawai_login_model->getPegawai();
        $this->data['disposisi'] = $this->Disposisi_model->getBySurat($id);

        $this->data['judul'] = 'Halaman untuk menampilkan disposisi surat';
        $this->data['menu'] = 'pimpinan/v_menu';
        $this->data['contents'] = 'pimpinan/v_disposisi_surat_masuk_tampil';

        $this->load->view('pimpinan/v_home', $this->data);
    }

    public function update_status()
    {
        $this->load->model('Log_surat_model');
        $Data = $this->Disposisi_model;
        $hasil = $Data->update_status_surat();

        $status_surat = nm_status_surat_masuk($this->input->post("status")) . '<br>';
        if ($hasil[0] != 'error') {
            # $this->Log_surat_masuk_model->add_log($this->Pegawai_login_model->current_user()->USERNAME, $this->input->post("id_surat_masuk"),  "Disposisi Surat Masuk " . $status_surat);
            $this->session->set_flashdata('sukses', 'Data Status Disposisi Surat Masuk Berhasil di update');
        } else {
            $this->session->set_flashdata('gagal', 'Data Status Disposisi Surat Masuk Gagal' . $hasil[1]);
        }

        redirect(site_url('pimpinan/disposisi'));
    }

    public function rekap()
    {
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        $this->data['judul'] = 'Rekapitulasi Jumlah Disposisi Surat Masuk Tahun ' . date('Y');
        $this->data['jumlah_disposisi'] = $this->Disposisi_model->jmlDisposisi(date('Y'));
        $this->data['menu'] = 'pimpinan/v_menu';
        $this->data['contents'] = 'disposisi/v_disposisi_rekap';
        $this->load->view('pimpinan/v_home', $this->data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        // TAMBAH load model Surat_masuk_model
        $this->load->model('Surat_masuk_model');

        $Data = $this->Disposisi_model;
        $validation = $this->form_validation;
        $validation->set_rules($Data->rules());

        if ($validation->run()) {
            
            // CEK CHECKBOX APPROVAL - TAMBAH INI
            if ($this->input->post('approval_checkbox') == 1) {
                // Update approval di t_surat_masuk
                $approval_data = array(
                    'APPROVAL_STATUS' => 1,
                    'APPROVED_BY' => $this->input->post('user_id'),
                    'APPROVAL_DATE' => date('Y-m-d H:i:s')
                );
                
                $this->db->where('ID_SURAT_MASUK', $this->input->post('id_surat_masuk'));
                $this->db->update('t_surat_masuk', $approval_data);
                
                // Simpan log approval
                if ($this->db->affected_rows() > 0) {
                    $log_data = array(
                        'USERNAME' => $this->Pegawai_login_model->current_user()->USERNAME,
                        'IP_ADDRESS' => $this->input->ip_address(),
                        'ID_SURAT' => $this->input->post('id_surat_masuk'),
                        'CATATAN' => 'Surat masuk disetujui oleh pimpinan'
                    );
                    $this->db->insert('t_log_surat', $log_data);
                }
            }
            
            // Simpan disposisi seperti biasa
            $Data->save();
            
            $this->session->set_flashdata('sukses', 'Data Disposisi Surat Masuk Berhasil disimpan');
        } else {
            $this->session->set_flashdata('gagal', 'Data Disposisi Surat Masuk Gagal disimpan');
        }
        redirect(site_url('pimpinan/surat_masuk/tampil/' . $this->input->post("id_surat_masuk")));
    }

    public function cetak($id)
    {
        $this->load->model('Instansi_model');
        $this->load->model('Log_surat_masuk_model');
        $diposisi = $this->Disposisi_model->getById($id);
        $this->Log_surat_masuk_model->add_log($this->Pegawai_login_model->current_user()->USERNAME, $diposisi->ID_SURAT_MASUK, "Mencetak Lembar disposisi surat masuk");
        $this->data['instansi'] = $this->Instansi_model->getInstansi();
        $this->data['disposisi'] = $this->Disposisi_model->getById($id);
        $this->load->view('disposisi/v_disposisi_cetak', $this->data);
    }

    public function hapus($id_surat = null, $id_disposisi = null)
    {
        $this->load->model('Pegawai_login_model');
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        $id_user = $this->data['user']->ID_PEGAWAI;
        if (!isset($id_surat)) show_404();

        if ($this->Disposisi_model->delete($id_surat, $id_disposisi, $id_user)) {
            $this->session->set_flashdata('gagal', 'Data Disposisi Surat Masuk Berhasil di hapus');
            redirect(site_url('pimpinan/surat_masuk/tampil/' . $id_surat));
        }
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        $sheet->setCellValue('A1', "JUMLAH DISPOSISI SURAT MASUK TAHUN " . date('Y')); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "No"); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B3', "Nama Bulan"); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', "Jumlah"); // Set kolom C3 dengan tulisan "NAMA"



        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);



        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya

        $Surat = $this->Disposisi_model->jmlDisposisi(date('Y'));
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

        foreach ($Surat as $data) { // Lakukan looping pada variabel siswa
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->NM_BULAN);
            $sheet->setCellValue('C' . $numrow, $data->JUMLAH);



            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Rekap Jumlah Disposisi");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Jumlah Disposisi.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
