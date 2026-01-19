<?php

require_once __DIR__ . '/../models/LaporanModel.php';

// Include TCPDF
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';

class LaporanController
{
    private $model;
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->model = new LaporanModel($pdo);
    }

    /**
     * Halaman index laporan
     */
    public function index(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Load view
        require_once __DIR__ . '/../views/laporan/index.php';
    }

    /**
     * Laporan Simpanan Harian
     */
    public function simpanHarian(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $data = $this->model->getLaporanSimpanHarian($tanggal);

        // Generate PDF
        $this->generatePdfSimpanHarian($data, $tanggal);
    }

    /**
     * Laporan Simpanan Bulanan
     */
    public function simpanBulanan(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $data = $this->model->getLaporanSimpanBulanan($bulan, $tahun);

        // Generate PDF
        $this->generatePdfSimpanBulanan($data, $bulan, $tahun);
    }

    /**
     * Laporan Simpanan Tahunan
     */
    public function simpanTahunan(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $tahun = $_GET['tahun'] ?? date('Y');
        $data = $this->model->getLaporanSimpanTahunan($tahun);

        // Generate PDF
        $this->generatePdfSimpanTahunan($data, $tahun);
    }

    /**
     * Laporan Pembiayaan Harian
     */
    public function pinjamHarian(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $data = $this->model->getLaporanPinjamHarian($tanggal);

        // Generate PDF
        $this->generatePdfPinjamHarian($data, $tanggal);
    }

    /**
     * Laporan Pembiayaan Bulanan
     */
    public function pinjamBulanan(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $data = $this->model->getLaporanPinjamBulanan($bulan, $tahun);

        // Generate PDF
        $this->generatePdfPinjamBulanan($data, $bulan, $tahun);
    }

    /**
     * Laporan Pembiayaan Tahunan
     */
    public function pinjamTahunan(): void
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $tahun = $_GET['tahun'] ?? date('Y');
        $data = $this->model->getLaporanPinjamTahunan($tahun);

        // Generate PDF
        $this->generatePdfPinjamTahunan($data, $tahun);
    }

    /**
     * Get Nama Bendahara dari Database
     */
    private function getNamaBendahara(): string
    {
        try {
            $query = "SELECT username, nama_lengkap FROM tb_petugas WHERE level = 'Bendahara' LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Prioritaskan nama_lengkap, jika kosong gunakan username
                return !empty($result['nama_lengkap']) ? $result['nama_lengkap'] : $result['username'];
            }

            return 'Bendahara';
        } catch (Exception $e) {
            return 'Bendahara';
        }
    }

    /**
     * Generate PDF: Laporan Simpanan Harian
     */
    private function generatePdfSimpanHarian($data, $tanggal): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Simpanan Harian');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Simpanan Harian');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN SIMPANAN HARIAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Tanggal: ' . LaporanModel::formatTanggalIndo($tanggal) . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data transaksi pada tanggal ini.</p>';
        } else {
            // Calculate totals
            $total_setor = 0;
            $total_tarik = 0;

            $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th style="text-align: center;">No</th>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Jenis</th>
                    <th>Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>No. Rekening</th>
                    <th style="text-align: right;">Jumlah (Rp)</th>
                    <th>Petugas</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $jumlah = floatval($row['jumlah']);

                if ($row['jenis_transaksi'] === 'Setor') {
                    $total_setor += $jumlah;
                } elseif ($row['jenis_transaksi'] === 'Tarik') {
                    $total_tarik += $jumlah;
                }

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_transaksi']) . '</td>';
                $html .= '<td>' . LaporanModel::formatDateTime($row['tanggal_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_simpanan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_rekening']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($jumlah, 0, ',', '.') . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_petugas']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Setoran:</td>
                    <td style="text-align: right;">' . number_format($total_setor, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Penarikan:</td>
                    <td style="text-align: right;">' . number_format($total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Selisih:</td>
                    <td style="text-align: right;">' . number_format($total_setor - $total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 100%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_simpanan_harian_' . $tanggal . '.pdf', 'I');
    }

    /**
     * Generate PDF: Laporan Simpanan Bulanan
     */
    private function generatePdfSimpanBulanan($data, $bulan, $tahun): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Simpanan Bulanan');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Simpanan Bulanan');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 9);

        // Nama bulan
        $nama_bulan = [
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

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN SIMPANAN BULANAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Periode: ' . $nama_bulan[(int)$bulan] . ' ' . $tahun . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data transaksi pada periode ini.</p>';
        } else {
            // Calculate totals
            $total_setor = 0;
            $total_tarik = 0;

            $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 9px;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th style="text-align: center;">No</th>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Jenis</th>
                    <th>Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>No. Rekening</th>
                    <th style="text-align: right;">Jumlah (Rp)</th>
                    <th>Petugas</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $jumlah = floatval($row['jumlah']);

                if ($row['jenis_transaksi'] === 'Setor') {
                    $total_setor += $jumlah;
                } elseif ($row['jenis_transaksi'] === 'Tarik') {
                    $total_tarik += $jumlah;
                }

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_transaksi']) . '</td>';
                $html .= '<td>' . LaporanModel::formatDateTime($row['tanggal_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_simpanan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_rekening']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($jumlah, 0, ',', '.') . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_petugas']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Setoran:</td>
                    <td style="text-align: right;">' . number_format($total_setor, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Penarikan:</td>
                    <td style="text-align: right;">' . number_format($total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Selisih:</td>
                    <td style="text-align: right;">' . number_format($total_setor - $total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $tanggal_ttd = date('Y-m-t', mktime(0, 0, 0, $bulan, 1, $tahun));
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 90%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal_ttd) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_simpanan_bulanan_' . $bulan . '_' . $tahun . '.pdf', 'I');
    }

    /**
     * Generate PDF: Laporan Simpanan Tahunan
     */
    private function generatePdfSimpanTahunan($data, $tahun): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Simpanan Tahunan');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Simpanan Tahunan');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 8);

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN SIMPANAN TAHUNAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Tahun: ' . $tahun . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data transaksi pada tahun ini.</p>';
        } else {
            // Calculate totals
            $total_setor = 0;
            $total_tarik = 0;

            $html .= '<table border="1" cellpadding="3" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 8px;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th>No</th>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Jenis</th>
                    <th>Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>No. Rekening</th>
                    <th>Jumlah (Rp)</th>
                    <th>Petugas</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $jumlah = floatval($row['jumlah']);

                if ($row['jenis_transaksi'] === 'Setor') {
                    $total_setor += $jumlah;
                } elseif ($row['jenis_transaksi'] === 'Tarik') {
                    $total_tarik += $jumlah;
                }

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_transaksi']) . '</td>';
                $html .= '<td>' . LaporanModel::formatDateTime($row['tanggal_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_transaksi']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_simpanan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_rekening']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($jumlah, 0, ',', '.') . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_petugas']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Setoran:</td>
                    <td style="text-align: right;">' . number_format($total_setor, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Penarikan:</td>
                    <td style="text-align: right;">' . number_format($total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Selisih:</td>
                    <td style="text-align: right;">' . number_format($total_setor - $total_tarik, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 100%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_simpanan_tahunan_' . $tahun . '.pdf', 'I');
    }

    /**
     * Generate PDF: Laporan Pembiayaan Harian
     */
    private function generatePdfPinjamHarian($data, $tanggal): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Pembiayaan Harian');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Pembiayaan Harian');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 9);

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN PEMBIAYAAN HARIAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Tanggal: ' . LaporanModel::formatTanggalIndo($tanggal) . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data pembiayaan pada tanggal ini.</p>';
        } else {
            // Calculate totals
            $total_pokok = 0;
            $total_margin = 0;
            $total_bayar = 0;

            $html .= '<table border="1" cellpadding="4" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 9px;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th style="text-align: center;">No</th>
                    <th>No. Akad</th>
                    <th>Tanggal</th>
                    <th>Anggota</th>
                    <th>Keperluan</th>
                    <th>Jenis Akad</th>
                    <th style="text-align: right;">Pokok (Rp)</th>
                    <th style="text-align: right;">Margin (Rp)</th>
                    <th style="text-align: right;">Total (Rp)</th>
                    <th>Tenor</th>
                    <th>Status</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $pokok = floatval($row['jumlah_pokok']);
                $margin = floatval($row['margin_koperasi']);
                $total = floatval($row['total_bayar']);

                $total_pokok += $pokok;
                $total_margin += $margin;
                $total_bayar += $total;

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_akad']) . '</td>';
                $html .= '<td>' . LaporanModel::formatTanggalIndo($row['tanggal_pengajuan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['keperluan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_akad']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($pokok, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($margin, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($total, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['tenor_bulan']) . ' bln</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['status']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total:</td>
                    <td style="text-align: right;">' . number_format($total_pokok, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_margin, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_bayar, 0, ',', '.') . '</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 100%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_pembiayaan_harian_' . $tanggal . '.pdf', 'I');
    }

    /**
     * Generate PDF: Laporan Pembiayaan Bulanan
     */
    private function generatePdfPinjamBulanan($data, $bulan, $tahun): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Pembiayaan Bulanan');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Pembiayaan Bulanan');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 8);

        // Nama bulan
        $nama_bulan = [
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

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN PEMBIAYAAN BULANAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Periode: ' . $nama_bulan[(int)$bulan] . ' ' . $tahun . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data pembiayaan pada periode ini.</p>';
        } else {
            // Calculate totals
            $total_pokok = 0;
            $total_margin = 0;
            $total_bayar = 0;

            $html .= '<table border="1" cellpadding="3" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 8px;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th style="text-align: center;">No</th>
                    <th>No. Akad</th>
                    <th>Tanggal</th>
                    <th>Anggota</th>
                    <th>Keperluan</th>
                    <th>Jenis Akad</th>
                    <th style="text-align: right;">Pokok (Rp)</th>
                    <th style="text-align: right;">Margin (Rp)</th>
                    <th style="text-align: right;">Total (Rp)</th>
                    <th>Tenor</th>
                    <th>Status</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $pokok = floatval($row['jumlah_pokok']);
                $margin = floatval($row['margin_koperasi']);
                $total = floatval($row['total_bayar']);

                $total_pokok += $pokok;
                $total_margin += $margin;
                $total_bayar += $total;

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_akad']) . '</td>';
                $html .= '<td>' . LaporanModel::formatTanggalIndo($row['tanggal_pengajuan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['keperluan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_akad']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($pokok, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($margin, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($total, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['tenor_bulan']) . ' bln</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['status']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total:</td>
                    <td style="text-align: right;">' . number_format($total_pokok, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_margin, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_bayar, 0, ',', '.') . '</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 100%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_pembiayaan_bulanan_' . $bulan . '_' . $tahun . '.pdf', 'I');
    }

    /**
     * Generate PDF: Laporan Pembiayaan Tahunan
     */
    private function generatePdfPinjamTahunan($data, $tahun): void
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('Koperasi Syariah');
        $pdf->SetAuthor('Koperasi Syariah');
        $pdf->SetTitle('Laporan Pembiayaan Tahunan');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Koperasi Syariah', 'Laporan Pembiayaan Tahunan');

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 7);

        // Content
        $html = '<h2 style="text-align: center;">LAPORAN PEMBIAYAAN TAHUNAN</h2>';
        $html .= '<h3 style="text-align: center;">Koperasi Syariah</h3>';
        $html .= '<p style="text-align: center;">Tahun: ' . $tahun . '</p>';
        $html .= '<hr><br>';

        if (empty($data)) {
            $html .= '<p style="text-align: center;">Tidak ada data pembiayaan pada tahun ini.</p>';
        } else {
            // Calculate totals
            $total_pokok = 0;
            $total_margin = 0;
            $total_bayar = 0;

            $html .= '<table border="1" cellpadding="2" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 7px;">';
            $html .= '<thead>
                <tr style="background-color: #059669; color: white;">
                    <th style="text-align: center;">No</th>
                    <th>No. Akad</th>
                    <th>Tanggal</th>
                    <th>Anggota</th>
                    <th>Keperluan</th>
                    <th>Jenis Akad</th>
                    <th style="text-align: right;">Pokok (Rp)</th>
                    <th style="text-align: right;">Margin (Rp)</th>
                    <th style="text-align: right;">Total (Rp)</th>
                    <th>Tenor</th>
                    <th>Status</th>
                </tr>
            </thead>';
            $html .= '<tbody>';

            $no = 1;
            foreach ($data as $row) {
                $pokok = floatval($row['jumlah_pokok']);
                $margin = floatval($row['margin_koperasi']);
                $total = floatval($row['total_bayar']);

                $total_pokok += $pokok;
                $total_margin += $margin;
                $total_bayar += $total;

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($row['no_akad']) . '</td>';
                $html .= '<td>' . LaporanModel::formatTanggalIndo($row['tanggal_pengajuan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['nama_lengkap']) . ' (' . htmlspecialchars($row['no_anggota']) . ')</td>';
                $html .= '<td>' . htmlspecialchars($row['keperluan']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['jenis_akad']) . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($pokok, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($margin, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: right;">' . number_format($total, 0, ',', '.') . '</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['tenor_bulan']) . ' bln</td>';
                $html .= '<td style="text-align: center;">' . htmlspecialchars($row['status']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '<tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="6" style="text-align: right;">Total:</td>
                    <td style="text-align: right;">' . number_format($total_pokok, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_margin, 0, ',', '.') . '</td>
                    <td style="text-align: right;">' . number_format($total_bayar, 0, ',', '.') . '</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>';
            $html .= '</table>';
        }

        // Print text
        $pdf->writeHTML($html, true, false, true, false, '');

        // TTD Section
        $nama_bendahara = $this->getNamaBendahara();
        $ttd_html = '<br><br>';
        $ttd_html .= '<table style="width: 100%; margin-top: 50px;">';
        $ttd_html .= '<tr>';
        $ttd_html .= '<td style="width: 70%;"></td>';
        $ttd_html .= '<td style="text-align: center;">';
        $ttd_html .= 'Sumatera Barat, ' . LaporanModel::formatTanggalIndo($tanggal) . '<br>';
        $ttd_html .= 'Bendahara<br><br><br><br>';
        $ttd_html .= '<strong><u>' . htmlspecialchars($nama_bendahara) . '</u></strong><br>';
        $ttd_html .= 'Bendahara';
        $ttd_html .= '</td>';
        $ttd_html .= '</tr>';
        $ttd_html .= '</table>';

        $pdf->writeHTML($ttd_html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('laporan_pembiayaan_tahunan_' . $tahun . '.pdf', 'I');
    }
}
