<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat_masuk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Surat_masuk_model');
        $this->load->model('Pegawai_login_model');
        $this->load->model('Kategori_srt_masuk_model');
        $this->load->library('form_validation');
        $this->load->model('Auth_model');
        if (!$this->Pegawai_login_model->current_user() or $this->Pegawai_login_model->current_user()->PIMPINAN != 0) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->load->model('Bagian_model');
        #redirect('bagian/home');
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        #print_r($this->data['user']);
        #echo $this->data['user']->ID_BAGIAN;
        #exit();
        #echo $this->uri->segment(1);exit();
        $this->data['rekap_surat'] = $this->Surat_masuk_model->getAllSuratByUser($this->data['user']->ID_PEGAWAI);
        $this->data['jenis_srt_masuk'] = $this->Kategori_srt_masuk_model->getAll();
        #$this->data['tujuan'] = $this->Pegawai_login_model->getPimpinan();
        $this->data['bagian'] = $this->Bagian_model->getAll();
        $this->data['judul'] = 'Rekapitulasi Surat Masuk';
        $this->data['menu'] = 'bagian/v_menu';
        $this->data['contents'] = 'bagian/v_surat_masuk';
        #$this->data['link'] = 'bagian/surat_masuk';
        #print_r($this->data);
        $this->load->view('bagian/v_home', $this->data);
    }

    public function terima_data($id)
    {
        if (!isset($id)) show_404();
        if ($this->Surat_masuk_model->receive($id)) {
            $this->session->set_flashdata('gagal', 'Surat Masuk gagal diterima.');
            #redirect(site_url('pimpinan/surat_masuk'));
        }
        redirect(site_url('bagian/surat_masuk'));
    }

    public function tampil($id = null)
    {
        $this->load->model('Disposisi_model');
        $this->load->model('Bagian_model');
        $this->load->model('Log_surat_masuk_model');
        $this->load->model('Log_surat_keluar_model');

        #$this->Log_surat_masuk_model->add_log($this->Pegawai_login_model->current_user()->USERNAME, $id, "Menampilkan surat masuk");
        $this->data['log_surat'] = $this->Log_surat_keluar_model->getByIdSurat($id);
        $this->data['surat'] = $this->Surat_masuk_model->getById($id);
        $this->data['pimpinan'] = $this->Pegawai_login_model->getPimpinan();
        #$this->data['bagian'] = $this->Pegawai_login_model->getPegawai();
        $this->data['bagian'] = $this->Bagian_model->getAll();
        $this->data['disposisi'] = $this->Disposisi_model->getBySurat($id);
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        $this->data['judul'] = 'Halaman untuk menampilkan surat';
        $this->data['menu'] = 'bagian/v_menu';
        $this->data['contents'] = 'bagian/v_surat_masuk_tampil';
        #print_r($this->data);exit();
        $this->load->view('bagian/v_home', $this->data);
    }

    public function add()
    {
        $Data = $this->Surat_masuk_model;
        $validation = $this->form_validation;
        $validation->set_rules($Data->rules());

        if ($validation->run()) {
            $hasil = $Data->save();
            #print_r($hasil);exit();
            if ($hasil[0] != 'error') {
                $this->session->set_flashdata('sukses', 'Data Surat Masuk Berhasil di tambah');
            } else {
                $this->session->set_flashdata('gagal', 'Data Surat Masuk Gagal ditambah' . $hasil[1]);
            }
        } else {
            $this->session->set_flashdata('gagal', 'Data Surat Masuk Gagal disimpan');
        }
        redirect(site_url('bagian/surat_masuk'));
    }

    public function edit()
    {
        $Data = $this->Surat_masuk_model;
        $validation = $this->form_validation;
        $validation->set_rules($Data->rules());

        if ($validation->run()) {
            $hasil = $Data->update();
            #print_r($hasil);exit();
            if ($hasil[0] != 'error') {
                $this->session->set_flashdata('sukses', 'Data Surat Masuk Berhasil di ubah');
            } else {
                $this->session->set_flashdata('gagal', 'Data Surat Masuk Gagal' . $hasil[1]);
            }
        } else {
            $this->session->set_flashdata('gagal', 'Data Surat Masuk Gagal disimpan');
        }

        redirect(site_url('bagian/surat_masuk'));
    }

    public function update_status()
    {
        $this->load->model('Log_surat_masuk_model');
        $this->load->model('Disposisi_model');
        /*if($this->input->post("status_surat")==3){
			$Data = $this->Disposisi_model;	
			$hasil= $Data->save();
		}else{
		*/
        $Data = $this->Surat_masuk_model;
        $hasil = $Data->update_status_surat();
        #}        

        #$status_surat = nm_status_surat_masuk($this->input->post("status_surat")) . '<br>';
        if ($hasil[0] != 'error') {
            #$this->Log_surat_masuk_model->add_log($this->Pegawai_login_model->current_user()->USERNAME, $this->input->post("id_surat_masuk"),  "Status Surat Masuk " . $status_surat);
            $this->session->set_flashdata('sukses', 'Status Surat Masuk Berhasil di update');
        } else {
            $this->session->set_flashdata('gagal', 'Status Surat Masuk Gagal' . $hasil[1]);
        }

        redirect(site_url('bagian/surat_masuk'));
    }


    public function rekap()
    {
        $this->data['user'] = $this->Pegawai_login_model->current_user();
        $this->data['judul'] = 'Rekapitulasi Jumlah Surat Masuk Tahun ' . date('Y');
        $this->data['jumlah_surat'] = $this->Surat_masuk_model->jmlSurat(date('Y'));
        $this->data['menu'] = 'bagian/v_menu';
        $this->data['contents'] = 'surat_masuk/v_surat_masuk_rekap';
        $this->load->view('bagian/v_home', $this->data);
    }

    public function cetak()
    {
        $this->data['rekap_surat'] =  $this->Surat_masuk_model->getAll();
        $this->load->view('surat_masuk/v_surat_masuk_cetak', $this->data);
    }

    public function export_rekap()
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
        $sheet->setCellValue('A1', "JUMLAH SURAT MASUK TAHUN " . date('Y')); // Set kolom A1 dengan tulisan "DATA SISWA"
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

        $Surat = $this->Surat_masuk_model->jmlSurat(date('Y'));
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
        $sheet->setTitle("Rekap Jumlah Surat Masuk");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Jumlah Surat Masuk.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
