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
$pdf->SetFont('helvetica', 'BU', 16);
$pdf->Cell(0, 5, 'SURAT KESEPAKATAN BERSAMA', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 5, 'NOMOR : ' . $sm->nomor_sm . ' / SKB / PT.AMS / ' . date_format(date_create($sm->tanggal_sm), 'm') . ' / ' . date_format(date_create($sm->tanggal_sm), 'Y'), 0, 1, 'C');
$pdf->Ln(4);
$pdf->Cell(0, 5, 'Tentang', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 5, 'PEMUTUSAN KERJA KEMITRAAN', 0, 1, 'C');
$pdf->Ln(8);
$pdf->Cell(10, 5, 'Memperhatikan');
$pdf->Ln(8);
$pdf->Cell(10, 5, '');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, 'Persetujuan Pimpinan PT. AKAS MILA SEJAHTERA Probolinggo Perihal:');
$pdf->Ln(6);
$pdf->Cell(10, 5, '');
$pdf->Cell(50, 5, 'Pelaksanaan Peraturan dan Prosedur Kerja dalam Lingkungan Perusahaan.');
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(10, 5, 'Menimbang');
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, 'Surat Pengunduran Diri Pekerja pada tanggal ' . $waktu_surat . '. Data pekerja');
$pdf->Ln(6);
$pdf->Cell(50, 5, 'yang dimaksud adalah');
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, '1.  Nama');
$pdf->Cell(150, 5, ':  ' . $sm->nama);
$pdf->Ln(6);
if ($sm->role_id == 31) {
    $jabatan = 'Sopir';
    $uang = number_format("3000000", 2, ",", ".");
} else if ($sm->role_id == 32) {
    $jabatan = 'Kondektur';
    $uang = number_format("2000000", 2, ",", ".");
} else {
    $jabatan = 'Kernet';
    $uang = number_format("1000000", 2, ",", ".");
}

$pdf->Cell(50, 5, '2.  NIP');
$pdf->Cell(150, 5, ':  ' . $sm->nip);
$pdf->Ln(6);
$pdf->Cell(50, 5, '3.  Jabatan');
$pdf->Cell(150, 5, ':  ' . $jabatan . ' di PT. AKAS MILA SEJAHTERA Probolinggo');
$pdf->Ln(6);
$pdf->Cell(50, 5, '4.  Mulai Aktif');
$pdf->Cell(150, 5, ':  ' . date('d F Y', $sm->date_created));
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(10, 5, 'Menetapkan');
$pdf->Ln(8);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 5, 'PEMUTUSAN KERJA KEMITRAAN', 0, 1, 'C');
$pdf->Ln(6);
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(10, 4, "1. ", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(170, 4, 'Bahwa Pengusaha mengakhiri Mitra Kerja yang bersangkutan sesuai dengan jabatannya sejak Surat Kesepakatan Bersama ini ditandatangani.', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(12);
$pdf->MultiCell(10, 4, '2. ', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(170, 4, 'Pekerja tidak menuntut uang pesangon / uang tali asih.', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(10, 4, "3. ", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(170, 4, 'Bahwa setelah kedua belah Pihak membaca, memahami dan menandatangani persetujuan bersama ini, maka kedua belah Pihak menyatakan permasalahan ini telah selesai dan masing-masing Pihak tidak akan mengadakan tuntutan lagi dalam bentuk apapun.', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(22);
$pdf->MultiCell(10, 4, "4. ", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(170, 4, 'Bahwa apabila di kemudian hari salah satu Pihak mengingkari isi kesepakatan ini, maka pihak tersebut siap dituntut sesuai ketentuan hukum yang berlaku.', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(14);
$pdf->MultiCell(180, 4, 'Demikian Kesepakatan Bersama antara Pengusaha dan Pekerja ini dibuat atas pikiran yang sadar tanpa paksaan dari pihak manapun, dan kedua belah pihak akan melaksanakan isi Kesepakatan Bersama ini dengan sebaik-baiknya dan tidak akan mengadakan tuntutan apapun lagi di kemudian hari.', 0, 'J', 0, 0, '', '', true);
$pdf->Ln(24);
$personalia = $this->ModelUser->get_by_id($sm->personalia_id);
$pdf->MultiCell(70, 5, '', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Probolinggo, ' . $waktu_surat, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(70, 5, 'Pihak Pekerja, ', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Pihak Pengusaha', 0, 'C', 0, 0, '', '', true);
$pdf->Ln(22);
$pdf->SetFont('helvetica', 'BU', 12);
$pdf->MultiCell(70, 5, $sm->nama, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'ZENDY HARDIANTO, SE', 0, 'C', 0, 0, '', '', true);
$pdf->write2DBarcode($sm->nomor_sm . ' / SKB / PT.AMS / ' . date_format(date_create($sm->tanggal_sm), 'm') . ' / ' . date_format(date_create($sm->tanggal_sm), 'Y'), 'QRCODE,H', 160, 30, 50, 50, $style, 'N');

// add a page
$pdf->AddPage();
$pdf->SetFont('helvetica', 'BU', 16);
$pdf->Cell(0, 5, 'PENGEMBALIAN UANG JAMINAN', 0, 1, 'C');
$pdf->Ln(18);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, 'Diberikan kepada saudara:');
$pdf->Ln(10);
$pdf->Cell(50, 5, '1.  Nama');
$pdf->Cell(150, 5, ':  ' . $sm->nama);
$pdf->Ln(8);
$pdf->Cell(50, 5, '2.  NIP');
$pdf->Cell(150, 5, ':  ' . $sm->nip);
$pdf->Ln(8);
$pdf->Cell(50, 5, '3.  Jabatan');
$pdf->Cell(150, 5, ':  ' . $jabatan . ' di PT. AKAS MILA SEJAHTERA Probolinggo');
$pdf->Ln(8);
$pdf->Cell(50, 5, '4.  Alamat');
$pdf->Cell(4, 5, ':  ');
$pdf->MultiCell(120, 5, $sm->alamat, 0, 'L', 0, 0, '', '', true);
$pdf->Ln(12);
$pdf->Cell(50, 5, '5.  Mulai Aktif');
$pdf->Cell(150, 5, ':  ' . date('d F Y', $sm->date_created));
$pdf->Ln(10);
$pdf->MultiCell(85, 5, 'Pengembalian uang jaminan sebesar', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, ': Rp. ' . $uang, 0, 'L', 0, 0, '', '', true);
$pdf->Ln(26);
$pdf->MultiCell(70, 5, '', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Probolinggo, ' . $waktu_surat, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(70, 5, 'Yang bersangkutan, ', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Personalia', 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'BU', 12);
$pdf->MultiCell(70, 5, $sm->nama, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $personalia->nama, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(70, 5, $sm->nip, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $personalia->nip, 0, 'C', 0, 0, '', '', true);
$pdf->write2DBarcode($sm->nomor_sm . ' / SKB / PT.AMS / ' . date_format(date_create($sm->tanggal_sm), 'm') . ' / ' . date_format(date_create($sm->tanggal_sm), 'Y'), 'QRCODE,H', 160, 30, 50, 50, $style, 'N');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('SKB_' . $sm->nama . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
