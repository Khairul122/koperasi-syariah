<?php
// Disable error output to prevent interfering with PDF
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

// Require TCPDF
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';

// Extend TCPDF class untuk custom header/footer
class KwitansiPDF extends TCPDF {
    private $angsuranData;

    public function __construct($angsuranData, $orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->angsuranData = $angsuranData;
    }

    // Page header
    public function Header() {
        // Logo dan header
        $this->SetY(10);
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 8, 'KOPERASI SYARIAH', 0, 1, 'C');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'Jl. Contoh No. 123, Kota - Indonesia', 0, 1, 'C');
        $this->Cell(0, 5, 'Telp: (021) 1234567 | Email: info@koperasisyariah.id', 0, 1, 'C');

        // Garis pemisah
        $this->SetLineStyle(array('width' => 0.5, 'color' => array(0, 0, 0)));
        $this->Line(15, $this->GetY() + 3, 195, $this->GetY() + 3);

        // Judul kwitansi
        $this->SetY($this->GetY() + 8);
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 8, 'KWITANSI ANGSURAN', 0, 1, 'C');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'No: ' . $this->angsuranData['no_kwitansi'], 0, 1, 'C');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);

        // Garis pemisah
        $this->SetLineStyle(array('width' => 0.3, 'color' => array(150, 150, 150)));
        $this->Line(15, $this->GetY(), 195, $this->GetY());

        // Footer text
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Kwitansi ini sebagai bukti pembayaran yang sah', 0, 0, 'C');
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}

// Create new PDF document
$pdf = new KwitansiPDF($angsuran, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Koperasi Syariah System');
$pdf->SetAuthor('Koperasi Syariah');
$pdf->SetTitle('Kwitansi Angsuran - ' . $angsuran['no_kwitansi']);
$pdf->SetSubject('Kwitansi Pembayaran Angsuran');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(array('helvetica', '', 10));
$pdf->setFooterFont(array('helvetica', 'I', 8));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(15, 45, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);

// Set auto page breaks
$pdf->SetAutoPageBreak(true, 25);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Informasi Anggota
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'INFORMASI ANGGOTA', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->SetY($pdf->GetY() + 2);

// Data anggota dalam tabel
$pdf->Cell(30, 6, 'No. Anggota', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['no_anggota'], 0, 0, 'L');

$pdf->Cell(30, 6, 'Tanggal Bayar', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatTanggalIndo($angsuran['tanggal_bayar']), 0, 1, 'L');

$pdf->Cell(30, 6, 'Nama Anggota', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['nama_anggota'], 0, 0, 'L');

$pdf->Cell(30, 6, 'No. Akad', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['no_akad'], 0, 1, 'L');

$pdf->Cell(30, 6, 'Alamat', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(145, 6, $angsuran['alamat'], 0, 1, 'L');

// Detail Pembiayaan
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'DETAIL PEMBIAYAAN', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->SetY($pdf->GetY() + 2);

$pdf->Cell(30, 6, 'Jenis Akad', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['jenis_akad'], 0, 0, 'L');

$pdf->Cell(30, 6, 'Tenor', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['tenor_bulan'] . ' Bulan', 0, 1, 'L');

$pdf->Cell(30, 6, 'Cicilan/Bulan', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatRupiah($angsuran['cicilan_per_bulan']), 0, 0, 'L');

$pdf->Cell(30, 6, 'Total Pembiayaan', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatRupiah($angsuran['total_bayar']), 0, 1, 'L');

// Detail Pembayaran
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'DETAIL PEMBAYARAN', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$pdf->SetY($pdf->GetY() + 2);

$pdf->Cell(30, 6, 'Angsuran Ke', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, $angsuran['angsuran_ke'] . ' dari ' . $angsuran['tenor_bulan'], 0, 0, 'L');

$pdf->Cell(30, 6, 'Jumlah Dibayar', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatRupiah($angsuran['jumlah_bayar']), 0, 1, 'L');

$pdf->Cell(30, 6, 'Denda', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatRupiah($angsuran['denda']), 0, 0, 'L');

$pdf->Cell(30, 6, 'Sisa Tagihan', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'L');
$pdf->Cell(60, 6, AngsuranModel::formatRupiah($angsuran['sisa_tagihan']), 0, 1, 'L');

// Total Bayar dalam box
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'TOTAL BAYAR', 0, 1, 'L');

$pdf->SetY($pdf->GetY() + 2);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(0, 10, AngsuranModel::formatRupiah($angsuran['jumlah_bayar'] + $angsuran['denda']), 1, 1, 'C', true);

// Terbilang
$pdf->SetY($pdf->GetY() + 3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'Terbilang:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 6, '"' . ucfirst(AngsuranModel::terbilang($angsuran['jumlah_bayar'] + $angsuran['denda'])) . ' Rupiah"', 0, 'L', false);

// Tanda Tangan
$pdf->SetY($pdf->GetY() + 8);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'TANDA TANGAN', 0, 1, 'C');

$pdf->SetY($pdf->GetY() + 2);

// Pembuat
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 6, 'Dibuat Oleh,', 0, 0, 'C');
$pdf->Cell(60, 6, '', 0, 0, 'C');
$pdf->Cell(60, 6, 'Mengetahui,', 0, 1, 'C');

// Space for tanda tangan
$pdf->SetY($pdf->GetY() + 25);
$pdf->Cell(60, 6, $angsuran['nama_petugas'] ?? '-', 0, 0, 'C');
$pdf->Cell(60, 6, '', 0, 0, 'C');
$pdf->Cell(60, 6, 'Koperasi Syariah', 0, 1, 'C');

$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(60, 4, '(Petugas)', 0, 0, 'C');
$pdf->Cell(60, 6, '', 0, 0, 'C');
$pdf->Cell(60, 4, '(Manager)', 0, 1, 'C');

// Catatan
$pdf->SetY($pdf->GetY() + 10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 5, 'Catatan:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(0, 5, '1. Kwitansi ini adalah bukti pembayaran yang sah.' . "\n" .
                  '2. Harap simpan kwitansi ini sebagai dokumen referensi.' . "\n" .
                  '3. Segala kendala dapat menghubungi kantor koperasi.', 0, 'L', false);

// Tanggal cetak
$pdf->SetY(-30);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 5, 'Dicetak pada: ' . date('d-m-Y H:i:s'), 0, 1, 'R');

// Clean output buffer
ob_end_clean();

// Output PDF
$pdf->Output('kwitansi_' . $angsuran['no_kwitansi'] . '.pdf', 'I');
exit;
