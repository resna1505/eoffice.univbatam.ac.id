<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Pengambilan KRS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 0;
        }

        /* QR Code Styling - PINDAH KE POJOK KANAN ATAS FOOTER */
        .qr-container {
            position: fixed;
            bottom: 60px; /* Di atas footer */
            right: 20px;
            text-align: center;
        }

        .qr-code {
            width: 110px;
            height: 110px;
            border: 1px solid #ddd;
            margin-bottom: 3px;
        }

        .qr-text {
            font-size: 7px;
            color: #666;
            max-width: 60px;
            word-wrap: break-word;
        }

        /* Header */
        .header {
            position: relative;
            margin-bottom: 30px;
            border-bottom: 3px solid #0066cc;
            padding: 10px 0 15px 0;
            min-height: 120px;
            display: flex;
            align-items: center;
        }

        .logo-uniba {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            margin-left: 20px;
            margin-right: 20px;
        }

        .logo-kampus-merdeka {
            width: 120px;
            height: auto;
            right: 20px;
            margin-top: 30px;
        }

        .yayasan-name {
            font-size: 22px;
            color: #666;
            margin-bottom: -10px;
            font-weight: normal;
        }

        .university-name {
            font-size: 39px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: -8px;
            letter-spacing: 1px;
        }

        .contact-info {
            font-size: 10px;
            color: #666;
            line-height: 1.4;
        }

        /* Document Info */
        .document-info {
            margin: 30px 0;
        }

        .document-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .document-info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .date-right {
            text-align: right;
            font-weight: normal;
        }

        /* Address */
        .address {
            margin: 25px 0;
            line-height: 1.4;
        }

        .address p {
            margin: 2px 0;
        }

        /* Content */
        .content {
            text-align: justify;
            line-height: 1.6;
        }

        .content p {
            text-indent: 30px;
        }

        .content p:first-child {
            text-indent: 0;
        }

        /* Utilities */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <!-- QR Code Container - HANYA MUNCUL JIKA SUDAH DISETUJUI -->
    <?php if (isset($approval_status) && $approval_status == 1): ?>
    <div class="qr-container">
        <?php if (isset($qr_code_base64) && !empty($qr_code_base64)): ?>
            <img src="<?= $qr_code_base64 ?>" class="qr-code" alt="QR Code" />
        <?php else: ?>
            <div class="qr-code" style="display:flex;align-items:center;justify-content:center;font-size:8px;background:#f0f0f0;border:1px solid #ddd;">
                QR
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="container">
        <div style="float: left; width: 20%;">
            <?php if (isset($logo_path) && file_exists($logo_path)): ?>
                <img src="<?= $logo_path ?>" class="logo-uniba" alt="Logo UNIBA">
            <?php endif; ?>
        </div>
        <div style="float: left; width: 70%;">
            <div class="yayasan-name">YAYASAN GRIYA HUSADA</div>
            <div class="university-name">UNIVERSITAS BATAM</div>
            <div class="contact-info">
                Jl. Uniba No.5 Batam Centre | T: 0778 7485055 | F: 0778 7485048<br>
                P.O. BOX : 335 | Batam Centre | info@univbatam.ac.id | www.uniba.ac.id
            </div>
        </div>
        <div style="float: left; width: 10%;">
            <?php if (isset($kampus_merdeka) && file_exists($kampus_merdeka)): ?>
                <img src="<?= $kampus_merdeka ?>" class="logo-kampus-merdeka" alt="Logo Kampus Merdeka">
            <?php endif; ?>
        </div>

        <!-- Document Info -->
        <div class="document-info">
            <table>
                <tr>
                    <td style="width: 200px;"></td>
                    <td style="width: 200px;"></td>
                    <td class="date-right"><?= isset($tanggal) ? $tanggal : 'Batam, ' . date('d F Y') ?></td>
                </tr>
            </table>
        </div>

        <table>
            <tr>
                <td style="width: 70px;">Nomor</td>
                <td>:</td>
                <td><?= isset($nomor_surat) ? $nomor_surat : '001/UNIBA/2025' ?></td>
            </tr>
            <tr>
                <td style="width: 70px;">Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td style="width: 70px;">Hal</td>
                <td>:</td>
                <td><?= isset($perihal) ? $perihal : 'Permohonan Pengambilan KRS' ?></td>
            </tr>
        </table>

        <!-- Address -->
        <div class="address">
            <p>Kepada Yth</p>
            <p><?= isset($kepada) ? $kepada : 'Kepala Bagian Akademik' ?></p>
            <p>Di</p>
            <p></p>
            <p>Tempat</p>
        </div>

        <!-- Content -->
        <?= isset($content) ? $content : '-' ?>
    </div>

    <div style="position: fixed; bottom: 0; left: 0; right: 0; background-color: blue; color: white; text-align: center; padding: 10px; font-size: 10px; margin: 0;">
        <div style="display: flex; justify-content: space-between;">
            <div style="float: left; width: 25%;">
                <?php if (isset($ig) && file_exists($ig)): ?>
                    <img src="<?= $ig ?>" height="12px" alt="Logo UNIBA">
                <?php endif; ?>
                universitasbatam.uniba
            </div>
            <div style="float: left; width: 25%;">
                <?php if (isset($fb) && file_exists($fb)): ?>
                    <img src="<?= $fb ?>" height="12px" alt="Logo UNIBA">
                <?php endif; ?>
                universitasbatamofficial
            </div>
            <div style="float: left; width: 25%;">
                <?php if (isset($yt) && file_exists($yt)): ?>
                    <img src="<?= $yt ?>" height="12px" alt="Logo UNIBA">
                <?php endif; ?>
                universitasbatam999
            </div>
            <div style="float: left; width: 25%;">
                <?php if (isset($wa) && file_exists($wa)): ?>
                    <img src="<?= $wa ?>" height="12px" alt="Logo UNIBA">
                <?php endif; ?> 0822 6111 2225
            </div>
        </div>
    </div>

</body>
</html>