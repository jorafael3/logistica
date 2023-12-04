<?php
require('../conexion_mssql.php');
 
// Creamos la consulta que va a compartir la visualización en PHP y en CSV
 
$acceso = $_POST['acceso'];
$bodega = $_POST['bodega'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$tipofecha = $_POST['tipofecha'];
//$tipofecha= 'FechaEntregado';

//echo "Se supone que viene de la otra pagina".$desde; 
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
          echo "Bodega;Cliente;Factura;Fecha Factura;TP Original; Tipo Pedido; Bodega; Prepa. Por; Fecha Prep ; Verif Por; Fecha Verif ;Guia Por; Fecha Guia; #Guia; Bultos; Transporte ; Embar.Por; Fecha Despacho; Fecha E.Vehiculo ; Ciudad"."\n";
            
        // Recorremos la consulta SQL y lo mostramos
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
                echo $row['codbodega'].";";
                echo $row['Detalle'].";";
				echo $row['secuencia'].";";
				echo $row['fecha'].";";
				echo $row['tporiginal'].";";
				echo $row['tpedido'].";";
				echo $row['nombodega'].";";
				echo $row['prepapor'].";";
				echo $row['fprepa'].";";
				echo $row['verifpor'].";";
				echo $row['fverif'].";";
				echo $row['guiapor'].";";
				echo $row['fguia'].";";
				echo $row['guia'].";";
				echo $row['BULTOS'].";";
				echo $row['trans'].";";
				echo $row['entrepor'].";";
				echo $row['fdesp'].";";
				echo $row['fvehi'].";";
				echo $row['ciudad']."\n";
               
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
?>