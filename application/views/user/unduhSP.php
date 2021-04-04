<?php


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('PT. AKAS MILA SEJAHTERA');
$pdf->SetAuthor('PT. AKAS MILA SEJAHTERA');
$pdf->SetTitle('Surat Perintah Dinas');
$pdf->SetSubject('Surat Perintah Dinas');
$pdf->SetKeywords('Surat Perintah Dinas');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'PT. AKAS MILA SEJAHTERA', "Jl. Raya Panglima Sudirman No. 237 Telp. (0335) 421510 Fax. 428503\nKOTA PROBOLINGGO - JAWA TIMUR");

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', 12));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// set style for barcode
$style = array(
    'border' => 2,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$pdf->setFormDefaultProp(array('lineWidth' => 1, 'borderStyle' => 'solid', 'fillColor' => array(255, 255, 200), 'strokeColor' => array(255, 128, 128)));

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 5, 'SURAT PERINTAH DINAS', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('helvetica', '', 12);



$pdf->Cell(50, 5, '1.  Nama');
$pdf->Cell(150, 5, ':  ' . $sp->nama . ' - ' . $sp->nip);
$pdf->Ln(8);

if ($sp->role_id == 31)
    $jabatan = 'Sopir';
else if ($sp->role_id == 32)
    $jabatan = 'Kondektur';
else
    $jabatan = 'Kernet';

$pdf->Cell(50, 5, '2.  Jabatan');
$pdf->Cell(150, 5, ':  ' . $jabatan);
$pdf->Ln(8);


$pdf->Cell(50, 5, '3.  Keperluan');
$pdf->Cell(150, 5, ':  Dinas di ' . $sp->nopol . ' - ' . $sp->kelas);
$pdf->Ln(8);


$kru = $this->ModelUser->get_by_id($sp->mengganti_id);
$pdf->Cell(50, 5, '4.  Mengganti');
if ($sp->mengganti_id != 0)
    $pdf->Cell(150, 5, ':  ' . $kru->nama . ' - ' . $kru->nip);
else
    $pdf->Cell(150, 5, ':  --');
$pdf->Ln(8);

$waktu = longdate_indo($sp->tanggal_sp);;
$waktu_surat = date_indo($sp->tanggal_sp);;

$pdf->Cell(50, 5, '5.  Mulai Tanggal');
$pdf->Cell(150, 5, ':  ' . $waktu);
$pdf->Ln(8);


$pdf->Cell(50, 5, '6.  Jurusan');
$pdf->Cell(150, 5, ':  ' . $sp->posawal . ' - ' . $sp->posakhir);
$pdf->Ln(12);

$pdf->Cell(30, 5, 'Tugas dan Tanggung Jawab yang harus DIPERHATIKAN adalah :');
$pdf->Ln(10);
$pdf->Cell(10, 5, '1. ');
$pdf->Cell(50, 5, 'Siap dinas dalam keadaan sehat jasmani dan rohani.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '2. ');
$pdf->Cell(50, 5, 'Berpakaian rapi dan memakai sepatu.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '3. ');
$pdf->Cell(50, 5, 'Dilarang mengkonsumsi narkoba dan obat-obatan terlarang lainnya.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '4. ');
$pdf->Cell(50, 5, 'Dilarang membawa senjata tajam dan bahan peledak.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '5. ');
$pdf->Cell(50, 5, 'Dilarang membebaskan ongkos penumpang dan/atau memungut ongkos penumpang');
$pdf->Ln(6);
$pdf->Cell(10, 5, '');
$pdf->Cell(50, 5, 'tanpa disertai bukti pembayaran yang sah berupa Karcis.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '6. ');
$pdf->Cell(50, 5, 'Dilarang mengoperkan penumpang ke P.O. lain.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '7. ');
$pdf->Cell(50, 5, 'Dilarang bermain judi dalam bentuk apapun dan dimanapun.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '8. ');
$pdf->Cell(50, 5, 'Patuhilah peraturan lalu lintas dan jangan ugal-ugalan di jalan raya.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '9. ');
$pdf->Cell(50, 5, 'Menghantar penumpang sampai ke tempat tujuan dengan keadaan selamat, ');
$pdf->Ln(6);
$pdf->Cell(10, 5, '');
$pdf->Cell(50, 5, 'aman, dan nyaman sesuai dengan trayek.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '10. ');
$pdf->Cell(50, 5, 'Dilarang menggunakan handphone (HP) pada saat mengemudi.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '11. ');
$pdf->Cell(50, 5, 'Bilamana terjadi kecelakaan harus melaporkan ke pos polisi terdekat dan jangan');
$pdf->Ln(6);
$pdf->Cell(10, 5, '');
$pdf->Cell(50, 5, 'melarikan diri.');
$pdf->Ln(6);
$pdf->Cell(10, 5, '12. ');
$pdf->Cell(50, 5, 'Turut menjaga kebersihan bus dan keamanan barang bawaan penumpang.');
$pdf->Ln(10);
$pdf->Cell(30, 5, 'Demikian untuk Diperhatikan dan Dilaksanakan dengan penuh tanggung jawab.');
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);

$pengatur = $this->ModelUser->get_by_id($sp->pengatur_id);
if ($pengatur->role_id != 5)
    $jabatan = 'Pengatur Dinas';
else
    $jabatan = 'Personalia';
$pdf->MultiCell(70, 5, '', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Probolinggo, ' . $waktu_surat, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(70, 5, 'Yang menerima perintah, ', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $jabatan, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'BU', 12);
$pdf->MultiCell(70, 5, $sp->nama, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $pengatur->nama, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(70, 5, $sp->nip, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $pengatur->nip, 0, 'C', 0, 0, '', '', true);

$pdf->write2DBarcode($sp->nomor_sp, 'QRCODE,H', 160, 40, 50, 50, $style, 'N');


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('SP_' . $sp->nama . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
