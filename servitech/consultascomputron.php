
<?php
//***********************************codigos PHP de ingreso de novedades *********************************************
if (isset($_POST['buscarfactura']))
  {
    //recibo la variable con el dato a buscar
    $busqueda= $_POST['txt_busqueda'];
    //como solo puedo devolver un campo creo un array para llenar los datos q trae la consulta
    $valores = array();
    $valores[0][existe]="0";
    //Esto deberia estar en un archivo de conexion aparte ***********************************************
    $sql_serverName = "tcp:10.5.1.3,1433";
    $sql_database = "COMPUTRONSA";
    $sql_user = "jairo";
    $sql_pwd = "qwertys3gur0";
    //require_once('../conexion_mssql.php');
    $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
    $resultado = $pdo->prepare("LOG_Detalle_Facturas_Traking_Novedades @Secuencia=:codigo");
    $resultado->bindParam(':codigo',$busqueda,PDO::PARAM_STR);
    $resultado->execute();

    $x=0;
    while ($row = $resultado->fetch(PDO::FETCH_ASSOC))
      {
            $valores[$x][existe]="1";
            $valores[$x][Secuencia]=$row['Secuencia'];
            $valores[$x][Ruc]=$row['Cedula'];
            $valores[$x][Direccion]=$row['Direccion'];
            $valores[$x][Telefono]=$row['Telefono'];
            $valores[$x][Correo]=$row['Correo'];
            $valores[$x][Vendedor]=$row['Vendedor'];
            $valores[$x][Section]=$row['Section'];
            $valores[$x][Codigo]=$row['Codigo'];
            $valores[$x][Nombre]=$row['Nombre'];
            $valores[$x][Fecha]=$row['Fecha'];
            $valores[$x][Preparado]=$row['PREPARADOPOR'];
            $valores[$x][FPreparado]=$row['FECHAYHORA'];
            $valores[$x][Verificado]=$row['VERIFICADO'];
            $valores[$x][FVerificado]=$row['FECHAVERIFICADO'];
            $valores[$x][Guiapor]=$row['GUIAPOR'];
            $valores[$x][Fguia]=$row['FECHAGUIA'];
            $valores[$x][Guianum]=$row['GUIA'];
            $valores[$x][Bultos]=$row['bultos'];
            $valores[$x][Transporte]=$row['transporte'];
            $valores[$x][Despachado]=$row['DESPACHADO'];
            $valores[$x][FDespachado]=$row['FechaEntregado'];
            $valores[$x][Comentario]=$row['comentario'];
            $valores[$x][Cantidad]=number_format($row['Cantidad'],0);
            $valores[$x][Subtotal]=number_format($row['SubTotal'],2);
            $valores[$x][Descuento]=number_format($row['Descuento'],2);
            $valores[$x][Impuesto]=number_format($row['Impuesto'],2);
            $valores[$x][Financiamiento]=number_format($row['Financiamiento'],2);
            $valores[$x][Total]=number_format($row['Total'],2);
            $valores[$x][Ruta]=$row['rutaFactura'];
            $valores[$x][FacturaId]=$row['ID'];
            $valores[$x][Bodega]=$row['Bodega'];
            $valores[$x][Sucursal]=$row['Sucursal'];
        $x++;
      }
    $valores= json_encode($valores);
   echo $valores;
  }
//Busqueda de facturas por cedula
if (isset($_POST['buscarcedula']))
    {
      //recibo la variable con el dato a buscar
      $busqueda= $_POST['txt_busqueda'];
      //como solo puedo devolver un campo creo un array para llenar los datos q trae la consulta
      $valores = array();
      $valores[0][existe]="0";
      //Esto deberia estar en un archivo de conexion aparte ***********************************************
      $sql_serverName = "tcp:10.5.1.3,1433";
      $sql_database = "COMPUTRONSA";
      $sql_user = "jairo";
      $sql_pwd = "qwertys3gur0";
      //require_once('../conexion_mssql.php');
      $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
      $resultado = $pdo->prepare("select top 5 fecha=convert(char(10),fa.fecha,103),fa.SucursalID,fa.secuencia,fa.ruc, fa.detalle, fa.Subtotal,fa.Descuento,fa.Impuesto,fa.Total,pago=p.nombre
                                  from VEN_FACTURAS fa
                                  inner join CLI_CLIENTES cl on cl.id=fa.clienteid
                                  inner join SIS_PARAMETROS p on p.id=fa.términoid
                                  where cl.código=:codigo");
      $resultado->bindParam(':codigo',$busqueda,PDO::PARAM_STR);
      $resultado->execute();
      $x=0;
      while ($row = $resultado->fetch(PDO::FETCH_ASSOC))
        {
              $valores[$x][existe]="1";
              $valores[$x][Fecha]=$row['fecha'];
              $valores[$x][Secuencia]=$row['secuencia'];
              $valores[$x][Ruc]=$row['ruc'];
              $valores[$x][Detalle]=$row['detalle'];
              $valores[$x][Subtotal]=number_format($row['Subtotal'],2);
              $valores[$x][Descuento]=number_format($row['Descuento'],2);
              $valores[$x][Impuesto]=number_format($row['Impuesto'],2);
              $valores[$x][Total]=number_format($row['Total'],2);
              $valores[$x][Pago]=$row['pago'];
              $valores[$x][SucursalId]=$row['SucursalID'];
          $x++;
        }
      $valores= json_encode($valores);
     echo $valores;
    }

    //Busqueda de facturas por Nombre
if (isset($_POST['buscarnombre']))
        {
          //recibo la variable con el dato a buscar
          $busqueda= $_POST['txt_busqueda'];
          //como solo puedo devolver un campo creo un array para llenar los datos q trae la consulta
          $valores = array();
          $valores[0][existe]="0";
          //Esto deberia estar en un archivo de conexion aparte ***********************************************
          $sql_serverName = "tcp:10.5.1.3,1433";
          $sql_database = "COMPUTRONSA";
          $sql_user = "jairo";
          $sql_pwd = "qwertys3gur0";
          //require_once('../conexion_mssql.php');
          $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
          $resultado = $pdo->prepare("LOG_Detalle_Facturas_Traking_Nombre @nombre=:nombre");
          $resultado->bindParam(':nombre',$busqueda,PDO::PARAM_STR);
          $resultado->execute();
          $x=0;
          while ($row = $resultado->fetch(PDO::FETCH_ASSOC))
            {
                  $valores[$x][existe]="1";
                  $valores[$x][Fecha]=$row['fecha'];
                  $valores[$x][Secuencia]=$row['secuencia'];
                  $valores[$x][Ruc]=$row['ruc'];
                  $valores[$x][Detalle]=$row['detalle'];
                  $valores[$x][Subtotal]=number_format($row['Subtotal'],2);
                  $valores[$x][Descuento]=number_format($row['Descuento'],2);
                  $valores[$x][Impuesto]=number_format($row['Impuesto'],2);
                  $valores[$x][Total]=number_format($row['Total'],2);
                  $valores[$x][Pago]=$row['pago'];
                  $valores[$x][SucursalId]=$row['SucursalID'];
              $x++;
            }
          $valores= json_encode($valores);
         echo $valores;
        }

if (isset($_POST['guardarnovedad']))
        {
          date_default_timezone_set('America/Guayaquil');
          $fecha = date("Ymd", time());
          $hora = date("H:i:s", time());
          $fh = $fecha . " " . $hora;
          $estado = "Reportada";
          $facturaid= $_POST['facturaid'];
          $novedad= $_POST['novedad'];
          $comentarionov= $_POST['comentarionov'];
          $IngresadoPor= $_POST['IngresadoPor'];
          $sql_serverName = "tcp:10.5.1.3,1433";
          $sql_database = "COMPUTRONSA";
          $sql_user = "jairo";
          $sql_pwd = "qwertys3gur0";
          //require_once('../conexion_mssql.php');
          $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
          $result = $pdo->prepare("Insert into VEN_FACTURAS_NOVEDADES (FacturaId,Novedad,Comentario,Estado, ReportadoPor, FechaReportado)
                                  values (:FacturaId,:Novedad,:Comentario,:Estado,:ReportadoPor,:FechaReportado)");
          $result->bindParam(':FacturaId',$facturaid,PDO::PARAM_STR);
          $result->bindParam(':Novedad',$novedad,PDO::PARAM_STR);
          $result->bindParam(':Comentario',$comentarionov,PDO::PARAM_STR);
          $result->bindParam(':Estado',$estado,PDO::PARAM_STR);
          $result->bindParam(':ReportadoPor',$IngresadoPor,PDO::PARAM_STR);
          $result->bindParam(':FechaReportado',$fh,PDO::PARAM_STR);
          $result->execute();

        }
  if (isset($_POST['buscartipo']))
   {
      $seleccion= $_POST['seleccion'];
      $treclamos= array();
      $sql_serverName = "tcp:10.5.1.3,1433";
      $sql_database = "COMPUTRONSA";
      $sql_user = "jairo";
      $sql_pwd = "qwertys3gur0";
      //require_once('../conexion_mssql.php');
      $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
      $result = $pdo->prepare("select IdReclamo, Reclamo from ven_tipo_reclamos_facturas_novedades where Dpto=:Dpto" );
      $result->bindParam(':Dpto',$seleccion,PDO::PARAM_STR);
      $result->execute();
      $x=0;
      while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
          $treclamos[$x][IdReclamo]=$row['IdReclamo'];
          $treclamos[$x][Reclamo]=$row['Reclamo'];
          $x++;
        }
      $treclamos = json_encode($treclamos);
      echo $treclamos;
  }

  if (isset($_POST['buscarnovedad']))
   {
      $seleccion2= $_POST['seleccion2'];
      $listanovedades= array();
      $sql_serverName = "tcp:10.5.1.3,1433";
      $sql_database = "COMPUTRONSA";
      $sql_user = "jairo";
      $sql_pwd = "qwertys3gur0";
      //require_once('../conexion_mssql.php');
      $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
      $result = $pdo->prepare("select IdReclamoDet, Detalle from VEN_TIPO_RECLAMOS_FACTURAS_NOVEDADES_DET  where IdReclamo=:IdReclamo" );
      $result->bindParam(':IdReclamo',$seleccion2,PDO::PARAM_STR);
      $result->execute();
      $x=0;
      while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
          $listanovedades[$x][IdReclamoDet]=$row['IdReclamoDet'];
          $listanovedades[$x][Detalle]=$row['Detalle'];
          $x++;
        }
      $listanovedades = json_encode($listanovedades);
      echo $listanovedades;
  }

//Cargar listado de novedades de facturas
  if (isset($_POST['cargarnove']))
    {
      $novedades = array();
      $novedades[0][existe]="0";
      //Esto deberia estar en un archivo de conexion aparte ***********************************************
      $sql_serverName = "tcp:10.5.1.3,1433";
      $sql_database = "COMPUTRONSA";
      $sql_user = "jairo";
      $sql_pwd = "qwertys3gur0";
      //require_once('../conexion_mssql.php');
      $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
      $resultado = $pdo->prepare("select Fecha=CONVERT(CHAR(10), F.FECHA, 103), Factura=F.Secuencia, F.Detalle, Ciudad= 'Ciudad',
                                  Tienda= SUBSTRING(S.Nombre,11,20),Vendedor= E.Nombre,FPago= p.nombre ,TipoReclamo= nov.Reclamo, nov.Dpto,
                                  Novedad=det.Detalle, n.Estado, F.ID
                                  from VEN_FACTURAS_NOVEDADES N
                                  INNER JOIN VEN_FACTURAS F WITH (NOLOCK) ON F.ID= n.FacturaId
                                  INNER JOIN SIS_SUCURSALES S ON S.Código=  F.SucursalID
                                  INNER JOIN EMP_EMPLEADOS E ON E.ID = F.VendedorID
                                  inner join SIS_PARAMETROS p on p.id = f.TérminoID
                                  inner join VEN_TIPO_RECLAMOS_FACTURAS_NOVEDADES_DET det on det.IdReclamoDet=n.Novedad
                                  inner join VEN_TIPO_RECLAMOS_FACTURAS_NOVEDADES nov on nov.IdReclamo=det.IdReclamo");
      $resultado->execute();
      $x=0;
      while ($row = $resultado->fetch(PDO::FETCH_ASSOC))
        {
              $novedades[$x][existe]="1";
              $novedades[$x][Fecha]=$row['Fecha'];
              $novedades[$x][Secuencia]=$row['Factura'] ;
              $novedades[$x][Detalle]=$row['Detalle'];
              $novedades[$x][Ciudad]=$row['Ciudad'];
              $novedades[$x][Tienda]=$row['Tienda'];
              $novedades[$x][Vendedor]=$row['Vendedor'];
              $novedades[$x][FPago]=$row['FPago'];
              $novedades[$x][TipoReclamo]=$row['TipoReclamo'];
              $novedades[$x][Dpto]=$row['Dpto'];
              $novedades[$x][Novedad]=$row['Novedad'];
              $novedades[$x][Estado]=$row['Estado'];
              $novedades[$x][FacturaId]=$row['ID'];
              $novedades[$x][Datos]= "'".$row['ID']."||".$row['Factura']."||".$row['Detalle']."||".$row['Estado']."'";
          $x++;
        }
      $novedades= json_encode($novedades);
     echo $novedades;
    }

    if (isset($_POST['actualizaestado']))
            {
              $facturaid=$_POST['facturaid'];
              $estado=$_POST['estado'];
              $sql_serverName = "tcp:10.5.1.3,1433";
              $sql_database = "COMPUTRONSA";
              $sql_user = "jairo";
              $sql_pwd = "qwertys3gur0";
              //require_once('../conexion_mssql.php');
              $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
              $result = $pdo->prepare("Update VEN_FACTURAS_NOVEDADES set Estado=:estado where FacturaId=:facturaid");
              $result->bindParam(':estado',$estado,PDO::PARAM_STR);
              $result->bindParam(':facturaid',$facturaid,PDO::PARAM_STR);
              $result->execute();
            }
?>
