<?php

$secu = '001-006-000169077';
require('../conexion_mssql.php');
$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare('LOG_BUSQUEDA_FACTURA @secuencia=:secu');		 
$result->bindParam(':secu',$secu,PDO::PARAM_STR);
$result->execute();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
	{
		$Codbodega=$row['Codbodega'];
		$Secuencia=$row['Secuencia'];
		$Numero=$row['Numero'];
		$Id=$row['Id'];
		$ClienteId=$row['ClienteId'];
		$Ruc = $row['Ruc'];
		$Nombre=$row['Nombre'];
		$TipoCLi=$row['TipoCLi'];	
		$Fecha=$row['Fecha'];
		$Direccion=$row['Direccion'];	
		$Ciudad=$row['Ciudad'];
		$Telefono=$row['Telefono'];
		$Mail=$row['Email'];
		$Contacto=$row['Contacto'];	
		$Bloqueado=$row['Bloqueado'];
		$Nota=$row['Nota'];
		$BodegaId=$row['BodegaId'];	
		$Bodeganame=$row['Bodeganame'];
		$Observacion = $row['Observacion'];
		$Medio = $row['Medio'];
		$Empmail = $row['Empmail'];
		$Fpago= $row['FPago'];
		$Sri_ack= $row['SRI_ACK'];
	}
	
// Include the main TCPDF library (search for installation path).
require('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('helvetica', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
	
//LOGO
//imagen, x, y, ancho, alto , tipo, 
//$pdf->Image('https://www.cartimex.com/assets/img/logo200.png', 10, 5, 70, 30, 'png','','', true, 300, '', false, false, 1, false, false, false);
// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);
// set cell margins
$pdf->setCellMargins(1, 1, 1, 1);
// set color for background
$pdf->SetFillColor(255, 255, 127);




$pdf->Cell(100,10,$pdf->Image('https://www.cartimex.com/assets/img/logo200.png',10,12,90),0,0,'C');
//$pdf->Cell(80,10,"Factura" ,1,1,'C',1);

// set some text for example
$txt = 'RUC - 0991400427001'."\n".'Factura'."\n".$Secuencia;
// Multicell test
// MultiCell(   $w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->MultiCell(70, 5,  $txt,     0,       'C',          0,       1,   120 ,  10,  true);

$pdf->Ln(4);


// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+