<?php
require('../conexion_mssql.php');
 
// Creamos la consulta que va a compartir la visualización en PHP y en CSV
 
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$acceso = $_POST['acceso'];
$bodega = $_POST['bodega'];
//$tipofecha= 'FechaEntregado';

//echo "Se supone que viene de la otra pagina".$desde; 
 
$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("LOG_FACTURAS_ANULADAS_SGL @bodega=:bodega , @acceso=:acceso, @desde=:desde, @hasta=:hasta" );		 
$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
$result->bindParam(':desde', $desde,PDO::PARAM_STR);
$result->bindParam(':hasta',$hasta,PDO::PARAM_STR);
$result->execute();

//$consulta=$c->query("SELECT idtienda,nombre,direccion FROM tiendas order by idtienda desc limit 3 ");
 
//Si hemos pulsado al botón de Exportar a Excel (CSV)...
if(isset($_POST["exportarCSV"])) {
    if(!empty($result)) {
        //El nombre del fichero tendrá el nombre de "usuarios_dia-mes-anio hora_minutos_segundos.csv"
        $ficheroExcel="facturasanuladas".date("d-m-Y H_i_s").".csv";
        
        //Indicamos que vamos a tratar con un fichero CSV
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        
        // Vamos a mostrar en las celdas las columnas que queremos que aparezcan en la primera fila, separadas por ; 
          echo "Bodega;Cliente;Factura;Vendedor;Fecha Factura;Fecha Anulacion;Tipo Pedido"."\n";
            
        // Recorremos la consulta SQL y lo mostramos
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
                echo $row['bodegsuc'].";";
                echo $row['Detalle'].";";
				echo $row['secuencia'].";";
				echo $row['vendedor'].";";
				echo $row['fecha'].";";
				echo $row['fanulada'].";";
				echo $row['TipoP']."\n";
               
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
?>
