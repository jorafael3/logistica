<?php
$secu = '001-008-000096472';
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
	
//echo "Forma de Pago ".$Fpago; 
//die(); 

require_once('fpdf/PDF_MC_Table.php');
//require('fpdf/barcode.php');

$pdf = new  PDF_MC_Table( );
$pdf->AddPage();
$y_axis_initial = 25;

//Dibujamos recuadro Datos del cliente 
$pdf->Line(10,40,190,40); //horizontal 1 
$pdf->Line(10,76,10,40); // izquierda
$pdf->Line(10,76,190,76); // abajo
$pdf->Line(190,40,190,76); // derecha
 
//Seteamos el tiupo de letra y creamos el titulo de la pagina. No es un encabezado no se repetira
$pdf->SetFont('Arial','',12);
//inserto la cabecera poniendo una imagen dentro de una celda
$pdf->Cell(100,6,$pdf->Image('https://www.cartimex.com/assets/img/logo200.png',10,12,90),0,0,'C');
$pdf->Cell(80,10,"RUC - 0991400427001" ,0,2,'C');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(232,232,232);
$pdf->Cell(80,7,"Factura" ,0,2,'C',1); //(ancho, alto, texto,borde, 0derecha1izquierda,2abajo,align,fill,link) 
$pdf->Cell(80,8,"No .".$Secuencia ,0,2,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',13);
$pdf->Cell(30,6,"Cliente:" ,0,0,'L');	
$pdf->SetFont('Arial','',12);
$pdf->Cell(150,6, utf8_decode($Nombre),0,2,'L');
$pdf->SetFont('Arial','B',13);
$pdf->Cell(50,0,'',0,1);
$pdf->Cell(30,6,"Ruc :",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(150,6,$Ruc ,0,2,'L');
$pdf->Cell(50,0,'',0,1);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(30,6,"Fecha :",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(150,6,$Fecha ,0,2,'L');
$pdf->Cell(50,0,'',0,1);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(30,6,"Ambiente:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(150,6,utf8_decode("Producción"),0,2,'L');
$pdf->Cell(50,0,'',0,1);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(30,6,utf8_decode("Autorización:"),0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(150,6,$Sri_ack,0,2,'L');
$pdf->Ln();
$pdf->SetFillColor(232,232,232);

//Tabla 
$pdf->SetWidths(Array(30,95,15,20,20));
$pdf->SetAligns(Array('L','L','C','R','R'));
$pdf->SetLineHeight(7);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,6,"Codigo",1,0,'L',1);
$pdf->Cell(95,6,"Descripcion",1,0,'C',1);
$pdf->Cell(15,6,"Cant.",1,0,'C',1);
$pdf->Cell(20,6,"Precio",1,0,'R',1);
$pdf->Cell(20,6,"P.Total",1,0,'R',1);
//add a new line
$pdf->Ln();
//reset font
$pdf->SetFont('Arial','',8);
$bodega= '0000000006'; $acceso = '1';
$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result1 = $pdo1->prepare('LOG_Detalle_Facturas_Traking @secu=:secu, @bodega=:bodega, @acceso=:acceso');		 
$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
$result1->execute();
$usuario= $_SESSION['usuario'];
$arreglo  = array();
$x=0; 
while($row1 = $result1->fetch(PDO::FETCH_ASSOC))
	{
		 $serie= '';
		 //write data using Row() method containing array of values.
		 $cantidad = number_format($row1['Cantidad'],0);
		 $precio = number_format(number_format($row1['SubTotal'],2)/number_format($row1['Cantidad'],0),2);
		 $subtotal = number_format($row1['SubTotal'],2);
		 
		 if ($row1['registroSeries']=='1') 
			{
				//echo $row1['registroSeries'];
				
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);		
				$result2 = $pdo2->prepare('Select serie from Rma_facturas f 
											inner join rma_facturas_dt dt on dt.facturaid = f.id 
											where f.facturaid =:facturaid and dt.productoid =:PID');	
				$result2->bindParam(':facturaid',$Id,PDO::PARAM_STR);
				$result2->bindParam(':PID',$row1['PID'],PDO::PARAM_STR);
				$result2->execute();
				$enter= ' ';
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
						$serie = $serie ."/".$row2['serie'].$enter;
					}		
			} 
		 $pdf->Row	(
						Array
							(
								$row1['PRODUCTO'],
								$row1['PNOMBRE']. "\n". $serie ,
								$cantidad, 
								$precio, 
								$subtotal,  
								 
							)
					);
		$Subtotal = number_format($row1['TSUBTOTAL'],2);
		$Descuento = number_format($row1['TDESCUENTO'],2);
		$Impuesto = number_format($row1['TIMPUESTOS'],2);
		$Total = number_format($row1['TTOTAL'],2);			
	}
$pdf->SetFont('Arial','B',10);
$pdf->Cell(125,24,"  ",1,0,'L');
$pdf->Cell(35,6,"Subtotal $ ",1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,6, $Subtotal,1,1,'R');
$pdf->Cell(125,6,"  ",0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,6,"Descuento $ ",1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,6, $Descuento,1,1,'R');
$pdf->Cell(125,6,"  ",0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,6,"Impuesto $ ",1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,6, $Impuesto,1,1,'R');
$pdf->Cell(125,6,"  ",0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,6,"Total $ ",1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,6, $Total,1,1,'R');
$pdf->SetFont('Arial','',10);

 
$pdf->Output();
?>

