<?php


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$imgurl = base_url() . "assets/img/logo2.png";


// set document information
$pdf->SetCreator('PT. AKAS MILA SEJAHTERA');
$pdf->SetAuthor('PT. AKAS MILA SEJAHTERA');
$pdf->SetTitle('SURAT KESEPAKATAN BERSAMA');
$pdf->SetSubject('SURAT KESEPAKATAN BERSAMA');
$pdf->SetKeywords('SURAT KESEPAKATAN BERSAMA');

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
$waktu = longdate_indo($sm->tanggal_sm);;
$waktu_surat = date_indo($sm->tanggal_sm);;
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 5, 'PERJANJIAN KEMITRAAN', 0, 1, 'C');
$pdf->Cell(0, 5, 'ANTARA PT. AKAS MILA SEJAHTERA', 0, 1, 'C');
$pdf->Cell(0, 5, 'DENGAN CREW BUS', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 5, 'NOMOR : ' . $sm->nomor_sm . ' / SPJ / PT.AMS / ' . date_format(date_create($sm->tanggal_sm), 'm') . ' / ' . date_format(date_create($sm->tanggal_sm), 'Y'), 0, 1, 'C');
$pdf->Ln(30);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, 'Yang bertanda tangan di bawah ini:');
$pdf->Ln(8);
$pdf->Cell(50, 5, '1.  Nama');
$pdf->Cell(150, 5, ':  ZENDY HARDIANTO, SE.');
$pdf->Ln(6);
$pdf->Cell(50, 5, '2.  Alamat');
$pdf->Cell(150, 5, ':  Jl. Raya Panglima Besar Sudirman No. 237, Kota Probolinggo');
$pdf->Ln(6);
$pdf->Cell(50, 5, '3.  Jabatan');
$pdf->Cell(150, 5, ':  Pimpinan di PT. AKAS MILA SEJAHTERA');
$pdf->Ln(8);
$pdf->MultiCell(180, 4, 'Bertindak untuk dan atas nama Perusahaan PT. AKAS MILA SEJAHTERA, dan selanjutnya dalam perjanjian kerja ini disebut sebagai Pihak I ( Pertama ).', 0, 'L', 0, 0, '', '', true);
$pdf->Ln(18);
$pdf->Cell(50, 5, '1.  Nama');
$pdf->Cell(150, 5, ':  ' . $sm->nama);
$pdf->Ln(6);
if ($sm->role_id == 31) {
    $jabatan = 'Sopir';
} else if ($sm->role_id == 32) {
    $jabatan = 'Kondektur';
} else {
    $jabatan = 'Kernet';
}
$pdf->Cell(50, 5, '2.  NIP');
$pdf->Cell(150, 5, ':  ' . $sm->nip);
$pdf->Ln(6);
$pdf->Cell(50, 5, '3.  Jabatan');
$pdf->Cell(150, 5, ':  ' . $jabatan . ' di PT. AKAS MILA SEJAHTERA Probolinggo');
$pdf->Ln(6);
$pdf->Cell(50, 5, '4.  Alamat');
$pdf->Cell(4, 5, ':  ');
$pdf->MultiCell(120, 5, $sm->alamat, 0, 'L', 0, 0, '', '', true);
$pdf->Ln(12);
$pdf->MultiCell(180, 4, 'Bertindak untuk dan atas nama sendiri selanjutnya dalam perjanjian kerja ini disebut sebagai Pihak II ( Kedua ).', 0, 'L', 0, 0, '', '', true);
$pdf->Ln(18);
$pdf->MultiCell(180, 4, 'Bahwa dalam rangka untuk menciptakan hubungan kerja yang harmonis dan menghindari kesalahpahaman serta untuk menjamin kepastian hukum antara Pihak I dengan Pihak II, maka kedua belah Pihak telah sepakat untuk mengatur secara jelas hak dan kewajiban para Pihak dalam rangka untuk meningkatkan Produktifitas Perusahaan yang dituangkan dalam sebuah Perjanjian Bersama yang disebut Perjanjian Kerja Kemitraan dengan ketentuan sebagai berikut:', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(14);

$pdf->write2DBarcode($sm->nomor_sm . ' / SPJ / PT.AMS / ' . date_format(date_create($sm->tanggal_sm), 'm') . ' / ' . date_format(date_create($sm->tanggal_sm), 'Y'), 'QRCODE,H', 160, 30, 50, 50, $style, 'N');

// add a page
$pdf->AddPage();

$pdf->MultiCell(180, 4, 'Demikian Perjanjian Kerja Kemitraan ini dibuat dalam keadaan sadar tanpa adanya paksaan atau tekanan dari Pihak manapun serta dengan itikad baik dan penuh tanggung jawab. Setelah para Pihak membaca, memahami dan menanda tangani nama – nama dibawah ini, maka masing – masing Pihak saling terikat dan memiliki kepastian atau kekuatan hukum yang sama.     ', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(36);
$personalia = $this->ModelUser->get_by_id($sm->personalia_id);
$pdf->MultiCell(70, 5, '', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Probolinggo, ' . $waktu_surat, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(70, 5, 'Pihak II, ', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Pihak I', 0, 'C', 0, 0, '', '', true);
$pdf->Ln(36);
$pdf->SetFont('helvetica', 'BU', 12);
$pdf->MultiCell(70, 5, $sm->nama, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'ZENDY HARDIANTO, SE', 0, 'C', 0, 0, '', '', true);
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('SPJ_' . $sm->nama . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
