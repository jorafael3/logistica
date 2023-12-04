
<?php
//Consultar las marcas de productos llenar array y enviarlo al select del formulario
if (isset($_POST['bmarcas']))
 {
    $marcas= array();
    require_once('../conexion_mssql.php');
  $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
  $result = $pdo->prepare("select ID, nombre from SIS_PARAMETROS where PadreID= '0000000026' order by 2" );
  $result->execute();
  $x=0;
  while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {
      $marcas[$x][ID]=$row['ID'];
      $marcas[$x][nombre]=$row['nombre'];
      $x++;
    }
  $marcas = json_encode($marcas);
  echo $marcas;

 }
//Consultar los tecnicos, llenar array y enviarlo al select del formulario
if (isset($_POST['btecnicos']))
 {
  $tecnicos= array();
  require_once('../conexion_mssql.php');
  $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
  $result = $pdo->prepare("select nombre, ID from EMP_EMPLEADOS where GrupoID= '0000000015' and FunciónID= '0000000047' and PrimerApellido not like '***%'" );
  $result->execute();
  $x=0;
  while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {
      $tecnicos[$x][ID]=$row['ID'];
      $tecnicos[$x][nombre]=$row['nombre'];
      $x++;
    }
  $tecnicos = json_encode($tecnicos);
  echo $tecnicos;

 }

//Busqueda de cliente por cedula /Ruc y enviar datos al formulario
if (isset($_POST['buscar']))
  {
    //recibo la variable con el dato a buscar
    $cedula= $_POST['cedula'];
    //como solo puedo devolver un campo creo un array para llenar los datos q trae la consulta
    $valores = array();
    $valores['existe']="0";
    require_once('../conexion_mssql.php');
    $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
    $resultado = $pdo->prepare('Select nombre,telefono=teléfono1, direccion=dirección, sri_em1, tmovil, Id from cli_clientes where código=:codigo');
    $resultado->bindParam(':codigo',$cedula,PDO::PARAM_STR);
    $resultado->execute();
    while( $consulta= $resultado->fetch(PDO::FETCH_ASSOC))
    {
       $valores[existe] = "1";
       $valores[nombre] = $consulta['nombre'];
       $valores[tel]= $consulta['telefono'];
       $valores[direc] = $consulta['direccion'];
       $valores[email] = $consulta['sri_em1'];
       $valores[celu] = $consulta['tmovil'];
       $valores[clienteid] = $consulta['Id'];
    }
    $valores= json_encode($valores);
    echo $valores;
  }

  if (isset($_POST['guardarser']))
  {
    date_default_timezone_set('America/Guayaquil');
	  $fecha = date("Ymd", time());
	  $hora = date("H:i:s", time());
    $fh = $fecha . " " . $hora;
    $tipo = "ORD-SER";
    $estado = "RMA-001";
    $clienteid= $_POST['clienteid'];
    $producto= $_POST['producto'];
    $modelo= $_POST['modelo'];
    $marca= $_POST['marca'];
    $serie= $_POST['serie'];
    $problema= $_POST['problema'];
    $accesorios= $_POST['accesorios'];
    $observaciones= $_POST['observaciones'];
    $tecnico= $_POST['tecnico'];
    $IngresadoPor= $_POST['IngresadoPor'];
    require_once('../conexion_mssql.php');
    $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
  	$result = $pdo->prepare("Ser_Orden_Servicio_Insert @ClienteID=:ClienteID, @fecha=:fecha,@tipo=:tipo,
                            @producto=:producto, @modelo=:modelo, @serie=:serie,@marca=:marca,@problema=:problema,
                            @accesorios=:accesorios, @observaciones=:observaciones, @estado=:estado, @tecnico=:tecnico,@CreadoPor=:CreadoPor");
    $result->bindParam(':ClienteID',$clienteid,PDO::PARAM_STR);
    $result->bindParam(':fecha',$fh,PDO::PARAM_STR);
    $result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
    $result->bindParam(':producto',$producto,PDO::PARAM_STR);
    $result->bindParam(':modelo',$modelo,PDO::PARAM_STR);
    $result->bindParam(':serie',$serie,PDO::PARAM_STR);
    $result->bindParam(':marca',$marca,PDO::PARAM_STR);
    $result->bindParam(':problema',$problema,PDO::PARAM_STR);
    $result->bindParam(':accesorios',$accesorios,PDO::PARAM_STR);
    $result->bindParam(':observaciones',$observaciones,PDO::PARAM_STR);
    $result->bindParam(':estado',$estado,PDO::PARAM_STR);
    $result->bindParam(':tecnico',$tecnico,PDO::PARAM_STR);
    $result->bindParam(':CreadoPor',$IngresadoPor,PDO::PARAM_STR);
    $result->execute();
    echo "Orden Servicio Ingresada Correctamente";
  }



  //***********************************codigos PHP de ORDEN DE GARANTIA *********************************************
  //Busqueda de informacion del cliente /factura por campo de busqueda (serie)
  if (isset($_POST['buscarinfo']))
    {
      //recibo la variable con el dato a buscar
      $busqueda= $_POST['txt_busqueda'];
      //como solo puedo devolver un campo creo un array para llenar los datos q trae la consulta
      $info = array();
      //$arreglod = array();
      $info[existe]="0";//no existe
      require_once('../conexion_mssql.php');
      $info[existe]="0";
      $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
      $result = $pdo->prepare('Inv_buscar_series_productos_APPSERVITECH @SERIE=:serie');
      $result->bindParam(':serie',$busqueda,PDO::PARAM_STR);
      $result->execute();
      $xd=0;
            while( $row= $result->fetch(PDO::FETCH_ASSOC))
                {
                   $info[existe] = "1";
                   $info[cant] = $row['cant'];
                   $info[cedula] = $row['cedula'];
                   $info[nombre] = $row['nombre'];
                   $info[tel]= $row['telefono'];
                   $info[direc] = $row['direccion'];
                   $info[email] = $row['mail'];
                   $info[celu] = $row['celular'];
                   $info[clienteid] = $row['clienteid'];
                   $info[facturaid] = $row['facturaid'];
                   $info[secuencia] = $row['Secuencia'];
                   $info[fecha] = $row['Fecha'];
                   $info[TipoCli] = $row['TipoCli'];
                   $info[codigo] = $row['codigo'];
                   $info[producto] = $row['producto'];
                   $info[modelo] = $row['modelo'];
                   $info[serie] = $row['serie'];
                   $info[fechagar] = $row['FechaGar'];
                   $info[marca] = $row['Marca'];
                   $info[productoid] = $row['productoid'];
                }
        $info= json_encode($info);
        echo $info;
  }

  if (isset($_POST['guardargar']))
  {
    date_default_timezone_set('America/Guayaquil');
	  $fecha = date("Ymd", time());
	  $hora = date("H:i:s", time());
    $fh = $fecha . " " . $hora;
    $facturaid= $_POST['facturaid'];
    $clienteid= $_POST['clienteid'];
    $productoid= $_POST['productoid'];
    $fh = $fecha . " " . $hora;
    $tipo = 'ORD-GAR';
    $ffinal = $_POST["facturaufc"];
    $clientef = $_POST["clientef"];
    $codigo = $_POST["codigo"];
    $producto= $_POST['producto'];
    $modelo= $_POST['modelo'];
    $serie= $_POST['serie'];
    $marca= $_POST['marca'];
    $vencimiento= date($_POST['vencimiento']);
    $problema= $_POST['problema'];
    $accesorios= $_POST['accesorios'];
    $observaciones= $_POST['observaciones'];
    $estado = 'RMA-001';
    $tecnico= $_POST['tecnico'];
    $IngresadoPor= $_POST['IngresadoPor'];
    require_once('../conexion_mssql.php');
    $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
  	$result = $pdo->prepare("Ser_Orden_Garantia_Insert @FacturaID=:FacturaID, @ClienteID=:ClienteID, @ProductoId=:ProductoId,@fecha=:fecha,@tipo=:tipo,@ffinal=:ffinal,@cliente=:cliente,
                            @codigo=:codigo,@producto=:producto, @modelo=:modelo, @serie=:serie,@marca=:marca, @vencimiento=:vencimiento, @problema=:problema,
                            @accesorios=:accesorios, @observaciones=:observaciones, @estado=:estado, @tecnico=:tecnico,@CreadoPor=:CreadoPor");
    $result->bindParam(':FacturaID',$facturaid,PDO::PARAM_STR);
    $result->bindParam(':ClienteID',$clienteid,PDO::PARAM_STR);
    $result->bindParam(':ProductoId',$productoid,PDO::PARAM_STR);
    $result->bindParam(':fecha',$fh,PDO::PARAM_STR);
    $result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
    $result->bindParam(':ffinal',$ffinal,PDO::PARAM_STR);
    $result->bindParam(':cliente',$clientef,PDO::PARAM_STR);
    $result->bindParam(':codigo',$codigo,PDO::PARAM_STR);
    $result->bindParam(':producto',$producto,PDO::PARAM_STR);
    $result->bindParam(':modelo',$modelo,PDO::PARAM_STR);
    $result->bindParam(':serie',$serie,PDO::PARAM_STR);
    $result->bindParam(':marca',$marca,PDO::PARAM_STR);
    $result->bindParam(':vencimiento',date("Ymd", strtotime($vencimiento)), PDO::PARAM_STR);
    $result->bindParam(':problema',$problema,PDO::PARAM_STR);
    $result->bindParam(':accesorios',$accesorios,PDO::PARAM_STR);
    $result->bindParam(':observaciones',$observaciones,PDO::PARAM_STR);
    $result->bindParam(':estado',$estado,PDO::PARAM_STR);
    $result->bindParam(':tecnico',$tecnico,PDO::PARAM_STR);
    $result->bindParam(':CreadoPor',$IngresadoPor,PDO::PARAM_STR);
    $result->execute();
    
  }
?>
