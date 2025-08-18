<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdf_generator extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load mPDF dari third_party folder
        require_once FCPATH . 'vendor/autoload.php';
        $this->load->helper('url');
        $this->load->database(); // Load database
    }

    /**
     * Download PDF Surat Permohonan
     */
    public function download_surat_permohonan($nomor_surat = null)
    {
        $data = $this->get_surat_data_from_db($nomor_surat);
        if ($data) {
            $this->generate_pdf($data, 'D'); // D = Download
        } else {
            show_error('Data surat dengan nomor "' . $nomor_surat . '" tidak ditemukan');
        }
    }

    /**
     * Preview PDF Surat Permohonan
     */
    public function preview_surat_permohonan($nomor_surat = null)
    {
        $data = $this->get_surat_data_from_db($nomor_surat);
        if ($data) {
            $this->generate_pdf($data, 'I'); // I = Inline/Preview
        } else {
            show_error('Data surat dengan nomor "' . $nomor_surat . '" tidak ditemukan');
        }
    }

    /**
     * Get data surat dari database (DINAMIS)
     */
    private function get_surat_data_from_db($nomor_surat = null)
    {
        if (!$nomor_surat) {
            return false;
        }

        try {
            // Query dengan JOIN ke t_surat_masuk untuk ambil data approval
            $this->db->select('
                tu.NM_PEGAWAI,
                tsk.ID_SURAT_KELUAR,
                jsk.NM_KATEGORI_SRT_KELUAR,
                tsk.NO_SURAT,
                tsk.TGL_SURAT,
                tsk.ISI_SURAT,
                tsk.BODY,
                tsk.TUJUAN,
                tsm.APPROVAL_STATUS,
                tsm.APPROVED_BY,
                tsm.APPROVAL_DATE
            ');
            $this->db->from('t_surat_keluar AS tsk');
            $this->db->join('t_pegawai AS tu', 'tu.ID_PEGAWAI = tsk.ID_TUJUAN', 'INNER');
            $this->db->join('r_kategori_srt_keluar AS jsk', 'jsk.ID_KATEGORI_SRT_KELUAR = tsk.ID_KATEGORI_SRT_KELUAR', 'INNER');
            
            // JOIN dengan t_surat_masuk untuk ambil data approval
            $this->db->join('t_surat_masuk AS tsm', 'tsm.ID_SURAT_MASUK = tsk.ID_SURAT_KELUAR', 'LEFT');
            
            $this->db->where('tsk.ID_SURAT_KELUAR', $nomor_surat);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $surat = $query->row();

                // Format data untuk PDF template
                $data = [
                    'id_surat' => $surat->ID_SURAT_KELUAR,  // TAMBAH INI
                    'nomor_surat' => $surat->NO_SURAT,
                    'tanggal' => $this->format_tanggal_indonesia($surat->TGL_SURAT),
                    'kepada' => $surat->TUJUAN ?: 'Kepala Puskom Universitas Batam',
                    'perihal' => $surat->ISI_SURAT ?: 'Surat Permohonan Pengambilan KRS',

                    // Data approval dari t_surat_masuk - TAMBAH INI
                    'approval_status' => $surat->APPROVAL_STATUS,
                    'approved_by' => $surat->APPROVED_BY,
                    'approval_date' => $surat->APPROVAL_DATE,

                    // Path gambar tetap sama
                    'logo_path' => FCPATH . 'assets/assets/img/logo-uniba.png',
                    'kampus_merdeka' => FCPATH . 'assets/assets/img/kampus-merdeka.png',
                    'wa' => FCPATH . 'assets/assets/img/wa.png',
                    'ig' => FCPATH . 'assets/assets/img/ig.png',
                    'fb' => FCPATH . 'assets/assets/img/fb.png',
                    'yt' => FCPATH . 'assets/assets/img/yt.png',

                    // Content dari database
                    'content' => $surat->BODY,

                    // Data tambahan untuk debugging
                    'raw_data' => $surat
                ];

                return $data;
            }

            return false;
        } catch (Exception $e) {
            log_message('error', 'Error getting surat data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format tanggal ke bahasa Indonesia
     */
    private function format_tanggal_indonesia($date)
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $timestamp = strtotime($date);
        $day = date('d', $timestamp);
        $month = $bulan[date('n', $timestamp)];
        $year = date('Y', $timestamp);

        return "Batam, $day $month $year";
    }

    /**
     * Get jabatan berdasarkan nama pegawai
     */
    private function get_jabatan_pegawai($nama_pegawai)
    {
        // Mapping jabatan berdasarkan nama
        $jabatan_mapping = [
            'Admin Prodi FK' => 'Ka.Prodi Profesi Dokter',
            'dr. Elvita Nora Susana, Sp.THT' => 'Ka.Prodi Profesi Dokter',
            // Tambahkan mapping lainnya sesuai kebutuhan
        ];

        return $jabatan_mapping[$nama_pegawai] ?? 'Ka.Prodi Profesi Dokter';
    }

    /**
     * Parse content dari BODY HTML
     */
    private function parse_body_content($body, $paragraph = 1)
    {
        if (empty($body)) {
            return '';
        }

        // Remove HTML tags
        $clean_body = strip_tags($body);

        // Split by paragraphs or sentences
        $paragraphs = explode('.', $clean_body);

        if ($paragraph == 1 && isset($paragraphs[0])) {
            return trim($paragraphs[0]) . '.';
        } elseif ($paragraph == 2 && isset($paragraphs[1])) {
            return trim($paragraphs[1]) . '.';
        }

        return $clean_body;
    }

    /**
     * Get data surat (fallback ke static jika database gagal)
     */
    private function get_surat_data($nomor_surat = null)
    {
        // Coba ambil dari database dulu
        $db_data = $this->get_surat_data_from_db($nomor_surat);

        if ($db_data) {
            return $db_data;
        }

        // Fallback ke data static jika database gagal
        $data = [
            'nomor_surat' => $nomor_surat ? $nomor_surat : '509/DK-FK/UNIBA/VI/2025',
            'tanggal' => 'Batam, 10 Juni 2025',
            'kepada' => 'Kepala Puskom Universitas Batam',
            'perihal' => 'Surat Permohonan Pengambilan KRS',
            'penandatangan' => 'dr. Elvita Nora Susana, Sp.THT',
            'jabatan' => 'Ka.Prodi Profesi Dokter',
            'logo_path' => FCPATH . 'assets/assets/img/logo-uniba.png',
            'kampus_merdeka' => FCPATH . 'assets/assets/img/kampus-merdeka.png',
            'wa' => FCPATH . 'assets/assets/img/wa.png',
            'ig' => FCPATH . 'assets/assets/img/ig.png',
            'fb' => FCPATH . 'assets/assets/img/fb.png',
            'yt' => FCPATH . 'assets/assets/img/yt.png',
            'content' => [
                'pembuka' => 'Dengan Hormat,',
                'salam' => 'Salam sejahtera semoga Tuhan YME senantiasa melindungi Bapak dan keluarga.',
                'isi_1' => 'Sehubungan dengan pelaksanaan Kartu Rencana Studi (KRS) mahasiswa Program Studi Dokter Fakultas Kedokteran Universitas Batam. Bersama ini kami mengajukan permohonan kepada Puskom untuk dapat membuka pengambilan KRS bagi mahasiswa yang memperoleh nilai "D" pada mata kuliah tertentu.',
                'isi_2' => 'Hal ini kami ajukan karena berdasarkan hasil evaluasi akademik dan temuan di lapangan, terdapat cukup banyak mahasiswa yang memperoleh nilai "D". Dengan dibukanya akses pemilihan KRS untuk nilai "D", kami berharap proses perbaikan nilai dapat berjalan lebih optimal dan mendukung peningkatan kualitas akademik mahasiswa secara menyeluruh.',
                'penutup' => 'Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.'
            ]
        ];

        return $data;
    }

    /**
     * Generate PDF menggunakan mPDF
     */
    // private function generate_pdf($data, $output_mode = 'I')
    // {
    //     try {
    //         // Konfigurasi mPDF
    //         $mpdf = new \Mpdf\Mpdf([
    //             'mode' => 'utf-8',
    //             'format' => 'A4',
    //             'orientation' => 'P',
    //             'margin_left' => 20,
    //             'margin_right' => 20,
    //             'margin_top' => 15,
    //             'margin_bottom' => 15,
    //             'tempDir' => APPPATH . 'cache/' // Folder untuk temporary files
    //         ]);

    //         // Set properties PDF
    //         $mpdf->SetTitle('Surat Permohonan Pengambilan KRS');
    //         $mpdf->SetAuthor('Universitas Batam');
    //         $mpdf->SetSubject('Surat Permohonan KRS');
    //         $mpdf->SetKeywords('surat, permohonan, KRS, universitas batam');

    //         // Load template HTML
    //         $html = $this->load->view('pdf/surat_permohonan_template', $data, true);

    //         // Write HTML ke PDF
    //         $mpdf->WriteHTML($html);

    //         // Output PDF
    //         $filename = 'Surat_Permohonan_' . str_replace('/', '_', $data['nomor_surat']) . '.pdf';
    //         $mpdf->Output($filename, $output_mode);
    //     } catch (\Mpdf\MpdfException $e) {
    //         show_error('Error mPDF: ' . $e->getMessage());
    //     } catch (Exception $e) {
    //         show_error('Error generating PDF: ' . $e->getMessage());
    //     }
    // }

    /**
     * Generate QR Code dengan data approval
     */
    private function generate_qr_code_base64($data)
    {
        // Data untuk QR Code - Info approval
        $qr_content = "Status: Disetujui\n";
        $qr_content .= "Oleh: " . (isset($data['approved_by_name']) ? $data['approved_by_name'] : 'N/A') . "\n";
        $qr_content .= "Tanggal: " . (isset($data['approval_date']) ? date('d/m/Y H:i', strtotime($data['approval_date'])) : 'N/A') . "\n";
        
        try {
            // QR Server API
            $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=" . urlencode($qr_content);
            
            // Download image dengan curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $qr_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP QR Generator)');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $qr_image = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($qr_image !== false && $http_code == 200) {
                // Convert to base64
                $base64 = base64_encode($qr_image);
                return 'data:image/png;base64,' . $base64;
            }
            
        } catch (Exception $e) {
            log_message('error', 'QR Code generation failed: ' . $e->getMessage());
        }
        
        // Fallback: Generate simple QR manually jika API gagal
        return $this->generate_simple_qr_fallback($qr_content);
    }

   private function generate_pdf($data, $output_mode = 'I')
    {
        try {
            // Data approval sudah ada dari JOIN di get_surat_data_from_db()
            // Tidak perlu query lagi jika sudah ada
            
            // Get nama penyetuju jika ada
            if (isset($data['approved_by']) && !empty($data['approved_by'])) {
                $approved_user = $this->db->get_where('t_pegawai_login', 
                    array('ID_PEGAWAI' => $data['approved_by']))->row();
                $data['approved_by_name'] = $approved_user ? $approved_user->USERNAME : 'Unknown';
            }

            // Generate QR Code HANYA jika sudah disetujui
            if (isset($data['approval_status']) && $data['approval_status'] == 1) {
                $qr_base64 = $this->generate_qr_code_base64($data);
                $data['qr_code_base64'] = $qr_base64;
            }

            // Konfigurasi mPDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'tempDir' => APPPATH . 'cache/'
            ]);

            // Set properties PDF
            $mpdf->SetTitle('Surat Permohonan Pengambilan KRS');
            $mpdf->SetAuthor('Universitas Batam');
            $mpdf->SetSubject('Surat Permohonan KRS');
            $mpdf->SetKeywords('surat, permohonan, KRS, universitas batam');

            // Load template HTML
            $html = $this->load->view('pdf/surat_permohonan_template', $data, true);

            // Write HTML ke PDF
            $mpdf->WriteHTML($html);

            // Output PDF
            $filename = 'Surat_Permohonan_' . str_replace('/', '_', $data['nomor_surat']) . '.pdf';
            $mpdf->Output($filename, $output_mode);
            
        } catch (\Mpdf\MpdfException $e) {
            show_error('Error mPDF: ' . $e->getMessage());
        } catch (Exception $e) {
            show_error('Error generating PDF: ' . $e->getMessage());
        }
    }

    // Method fallback QR tetap sama seperti sebelumnya...
    private function generate_simple_qr_fallback($data)
    {
        // Create simple 8x8 QR-like pattern
        $size = 60;
        $image = imagecreate($size, $size);
        
        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background
        imagefill($image, 0, 0, $white);
        
        // Draw simple QR pattern
        $this->draw_qr_corner($image, $black, 0, 0, 15);        // Top-left
        $this->draw_qr_corner($image, $black, 45, 0, 15);       // Top-right  
        $this->draw_qr_corner($image, $black, 0, 45, 15);       // Bottom-left
        
        // Draw some data pattern
        for ($i = 20; $i < 40; $i += 5) {
            for ($j = 20; $j < 40; $j += 5) {
                if (($i + $j) % 10 == 0) {
                    imagefilledrectangle($image, $i, $j, $i+3, $j+3, $black);
                }
            }
        }
        
        // Convert to base64
        ob_start();
        imagepng($image);
        $image_data = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        
        return 'data:image/png;base64,' . base64_encode($image_data);
    }

    private function draw_qr_corner($image, $color, $x, $y, $size)
    {
        // Outer rectangle
        imagefilledrectangle($image, $x, $y, $x+$size, $y+$size, $color);
        
        // Inner white square
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, $x+2, $y+2, $x+$size-2, $y+$size-2, $white);
        
        // Center black square
        imagefilledrectangle($image, $x+6, $y+6, $x+$size-6, $y+$size-6, $color);
    }

    /**
     * Test method dengan data dari database
     */
    public function test()
    {
        // Test koneksi database
        $db_connected = $this->db->conn_id ? true : false;

        // Test ambil data terbaru
        $this->db->select('NO_SURAT, TGL_SURAT, TUJUAN');
        $this->db->from('t_surat_keluar');
        $this->db->order_by('TGL_SURAT', 'DESC');
        $this->db->limit(3);
        $latest_surat = $this->db->get()->result();

        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>PDF Generator Test - Database Version</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .container { max-width: 800px; margin: 0 auto; }
                .btn { display: inline-block; padding: 12px 24px; margin: 10px; text-decoration: none; border-radius: 5px; font-weight: bold; }
                .btn-primary { background: #007bff; color: white; }
                .btn-success { background: #28a745; color: white; }
                .btn-warning { background: #ffc107; color: black; }
                .status { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff; }
                .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .data-preview { background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 12px; }
                .error { background: #ffebee; border-left: 4px solid #f44336; }
                .success { background: #e8f5e8; border-left: 4px solid #4caf50; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🚀 PDF Generator Test - Database Version</h1>
                
                <div class="status ' . ($db_connected ? 'success' : 'error') . '">
                    <h3>Database Status:</h3>
                    <p>✅ Database connected: ' . ($db_connected ? 'YES' : 'NO') . '</p>
                    <p>✅ Table t_surat_keluar: ' . ($this->db->table_exists('t_surat_keluar') ? 'EXISTS' : 'NOT EXISTS') . '</p>
                    <p>✅ Table t_pegawai: ' . ($this->db->table_exists('t_pegawai') ? 'EXISTS' : 'NOT EXISTS') . '</p>
                    <p>✅ Table r_kategori_srt_keluar: ' . ($this->db->table_exists('r_kategori_srt_keluar') ? 'EXISTS' : 'NOT EXISTS') . '</p>
                </div>';

        if (!empty($latest_surat)) {
            echo '<div class="data-preview">
                    <h4>📊 Data Surat Terbaru:</h4>
                    <table border="1" style="width:100%; border-collapse: collapse;">
                        <tr>
                            <th>No Surat</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th>Action</th>
                        </tr>';

            foreach ($latest_surat as $surat) {
                echo '<tr>
                        <td>' . $surat->NO_SURAT . '</td>
                        <td>' . $surat->TGL_SURAT . '</td>
                        <td>' . $surat->TUJUAN . '</td>
                        <td>
                            <a href="' . site_url('pdf_generator/preview_surat_permohonan/' . $surat->NO_SURAT) . '" target="_blank" style="color: blue;">Preview</a> |
                            <a href="' . site_url('pdf_generator/download_surat_permohonan/' . $surat->NO_SURAT) . '" target="_blank" style="color: green;">Download</a>
                        </td>
                      </tr>';
            }

            echo '</table></div>';

            $test_nomor = $latest_surat[0]->NO_SURAT;
            echo '<div class="info">
                    <h3>🧪 Test dengan Data Real:</h3>
                    <a href="' . site_url('pdf_generator/preview_surat_permohonan/' . $test_nomor) . '" target="_blank" class="btn btn-primary">
                        📄 Preview PDF (' . $test_nomor . ')
                    </a>
                    <a href="' . site_url('pdf_generator/download_surat_permohonan/' . $test_nomor) . '" target="_blank" class="btn btn-success">
                        💾 Download PDF (' . $test_nomor . ')
                    </a>
                  </div>';
        } else {
            echo '<div class="status error">
                    <h3>⚠️ Tidak ada data surat ditemukan</h3>
                    <p>Pastikan tabel t_surat_keluar memiliki data</p>
                  </div>';
        }

        echo '<div class="info">
                <h3>🔧 Test Manual:</h3>
                <p>Test dengan nomor surat manual (contoh: 1234):</p>
                <a href="' . site_url('pdf_generator/preview_surat_permohonan/1234') . '" target="_blank" class="btn btn-warning">
                    📄 Test Preview Manual
                </a>
              </div>
              
              <div class="info">
                <h3>📋 Mapping Data:</h3>
                <ul>
                    <li><strong>nomor_surat:</strong> tsk.NO_SURAT</li>
                    <li><strong>tanggal:</strong> tsk.TGL_SURAT (formatted)</li>
                    <li><strong>kepada:</strong> tsk.TUJUAN</li>
                    <li><strong>perihal:</strong> jsk.NM_KATEGORI_SRT_KELUAR</li>
                    <li><strong>penandatangan:</strong> tu.NM_PEGAWAI</li>
                    <li><strong>isi_surat:</strong> tsk.ISI_SURAT + tsk.BODY</li>
                </ul>
              </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Debug method untuk cek data specific
     */
    public function debug($nomor_surat)
    {
        $data = $this->get_surat_data_from_db($nomor_surat);

        echo "<h2>Debug Data untuk Nomor: $nomor_surat</h2>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}
