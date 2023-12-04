<?php

session_start();	

require('conexion_mssql.php');
 
// Creamos la consulta que va a compartir la visualización en PHP y en CSV
 
$acceso = $_POST['acceso'];
$bodega = $_POST['bodega'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$tipofecha = $_POST['tipofecha'];
$base = $_SESSION['base'];
//$tipofecha= 'FechaEntregado';

//echo "Se supone que viene de la otra pagina".$desde.$base; 

$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("LOG_FACTURAS_DESPACHADAS_SGL @bodega=:bodega , @acceso=:acceso, @desde=:desde, @hasta=:hasta, @tipofecha=:tipofecha" );		 
$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
$result->bindParam(':desde', $desde,PDO::PARAM_STR);
$result->bindParam(':hasta',$hasta,PDO::PARAM_STR);
$result->bindParam(':tipofecha',$tipofecha,PDO::PARAM_STR);
$result->execute();

//$consulta=$c->query("SELECT idtienda,nombre,direccion FROM tiendas order by idtienda desc limit 3 ");
 
//Si hemos pulsado al botón de Exportar a Excel (CSV)...
if(isset($_POST["exportarCSV"])) {
    if(!empty($result)) {
        //El nombre del fichero tendrá el nombre de "usuarios_dia-mes-anio hora_minutos_segundos.csv"
        $ficheroExcel="facturasdespachadas".date("d-m-Y H_i_s").".csv";
        
        //Indicamos que vamos a tratar con un fichero CSV
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        
        // Vamos a mostrar en las celdas las columnas que queremos que aparezcan en la primera fila, separadas por ; 
          echo "SId;Cliente;F.Orden;F.Aprobacion;Factura;Fecha Factura; Bodega.Fact ; F.Envio ; Bod.Retiro ;F.Pago; Fecha Prepa; Fecha Verif ; Fecha Guia ;Guia # ;Transporte; Fecha Despacho; Fecha E.Vehiculo"."\n";
            
        // Recorremos la consulta SQL y lo mostramos
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$numfac= $row['secuencia'];	
				include("conexion.php");
				$sql1 = "SELECT a.*, p.bodega as bodegaret, a.fecha as forden,
						a.fechafact as faprob,
						a.paymentez as fpayme,
						c.sucursalid as sucursal  FROM covidsales a
						left outer join covidpickup p on p.orden= a.secuencia
						left outer join sisco.covidciudades c on p.bodega= c.almacen
						where a.factura = '$numfac' and a.anulada<> '1'  ";
				$result1 = mysqli_query($con, $sql1);
				$row1 = mysqli_fetch_array($result1);
				//$forden = date("yy-m-d", $row1['fecha']);
				$forden = date ($row1['forden']);
				$formapago = $row1['formapago'];
				$bodegaretiro = $row1['bodegaret'];
				$sucuret = $row1['sucursal'];
				if ($row1['pickup']==1){ $tenvio = 'Pickup';} else { $tenvio = 'Envio';}
				if ($row1['formapago'] == 'Tarjeta' or $row1['formapago'] == 'Tienda' or $row1['formapago'] == 'Directo'
				    or $row1['formapago'] == 'LinkToPay') { $Verpago= $row1['faprob']; }
				else { $Verpago= $row1['fpayme']; }
				
                echo $row['Sucursal'].";";
                echo $row['Detalle'].";";
				echo $forden.";"; 
				echo $Verpago.";";
				echo $row['secuencia'].";";
				echo $row['fecha'].";";
				echo $row['nombodega'].";";
				echo $tenvio.";"; 
				echo $bodegaretiro.";"; 
				echo $formapago .";";
				echo $row['fprepa'].";";
				echo $row['fverif'].";";
				echo $row['fguia'].";";
				echo $row['guia'].";";
				echo $row['trans'].";";
				echo $row['fdesp'].";";
				echo $row['fvehi']."\n";
               
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
?>