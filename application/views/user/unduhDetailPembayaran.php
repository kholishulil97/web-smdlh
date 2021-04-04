<?php


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$imgurl = base_url() . "assets/img/logo2.png";


// set document information
$pdf->SetCreator('PT. AKAS MILA SEJAHTERA');
$pdf->SetAuthor('PT. AKAS MILA SEJAHTERA');
$pdf->SetTitle('BUKTI PEMBAYARAN');
$pdf->SetSubject('BUKTI PEMBAYARAN');
$pdf->SetKeywords('BUKTI PEMBAYARAN');

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
$pdf->AddPage('L', 'A5');

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
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 5, 'BUKTI PEMBAYARAN', 0, 1, 'C');
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(50, 5, 'Telah diterima dari ');
$pdf->SetFont('helvetica', 'B', 12);
$penerima = $this->ModelUser->get_by_id($p->penerima_id);
$pembayar = $this->ModelUser->get_by_id($p->pembayar_id);
$pdf->Cell(55, 5, $pembayar->nama);
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(8);
$pdf->MultiCell(50, 5, 'Jumlah uang sebesar', 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(150, 5, 'Rp. ' . number_format($p->nominal, 2, ",", "."), 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(8);
$pdf->MultiCell(50, 5, 'Terbilang', 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('helvetica', 'I', 12);
$pdf->MultiCell(150, 5, ucwords($t) . " Rupiah", 0, 'L', 0, 0, '', '', true);
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(50, 5, 'Untuk keperluan', 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('helvetica', 'B', 12);
if ($p->tipe_pembayaran == 0) {
    $lh = $this->ModelLaporanHarian->get_by_id($p->id_lh);
    $pdf->MultiCell(150, 5, 'Setoran Kas untuk Lyn ' . longdate_indo($lh->tanggal_selesai), 0, 'L', 0, 0, '', '', true);
} else {

    $pdf->MultiCell(175, 5, 'Pembayaran Premi untuk Lyn ' . cust_date_indo($p->bulan_setor), 0, 'L', 0, 0, '', '', true);
}

$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(14);
$pdf->MultiCell(70, 5, '', 0, 'C', 0, 0, '', '', true);
$waktu_surat = date_indo($p->tanggal_pembayaran);
$pdf->MultiCell(150, 5, 'Probolinggo, ' . $waktu_surat, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->MultiCell(70, 5, 'Penerima, ', 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, 'Yang Menyerahkan', 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'BU', 12);
$pdf->MultiCell(70, 5, $penerima->nama, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $pembayar->nama, 0, 'C', 0, 0, '', '', true);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(70, 5, $penerima->nip, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(150, 5, $pembayar->nip, 0, 'C', 0, 0, '', '', true);

$pdf->write2DBarcode($p->id, 'QRCODE,H', 170, 27, 25, 25, $style, 'N');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Nota_' . $pembayar->nama . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
