<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['loggedin']))
{
  $usuario = $_SESSION['usuario'];
  $base = $_SESSION['base'];
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Computronsa</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-image="assets/img/sidebar-4.jpg" data-color="orange">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

        Tip 2: you can also add an image using data-image tag
  -->
            <div class="sidebar-wrapper">
                <div class="logo">
                    <h3 class="text-primary">
                       COMPUTRONSA
                    </h3>
                </div>
                <ul class="nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.html">
                            <i class="nc-icon nc-chart-pie-35"></i>
                            <p> </p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./user.html">
                            <i class="nc-icon nc-circle-09"></i>
                            <p> </p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./table.html">
                            <i class="nc-icon nc-notes"></i>
                            <p> </p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./typography.html">
                            <i class="nc-icon nc-paper-2"></i>
                            <p> </p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./icons.html">
                            <i class="nc-icon nc-atom"></i>
                            <p> </p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./maps.html">
                            <i class="nc-icon nc-pin-3"></i>
                            <p></p>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="./notifications.html">
                            <i class="nc-icon nc-bell-55"></i>
                            <p> </p>
                        </a>
                    </li>
                    <!--
                    <li class="nav-item active active-pro">
                        <a class="nav-link active" href="upgrade.html">
                            <i class="nc-icon nc-alien-33"></i>
                            <p>Upgrade to PRO</p>
                        </a>
                    </li>
                      -->
                </ul>
            </div>

        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="nav navbar-nav mr-auto">
                          <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="no-icon">Ingresos</span>
                              </a>
                              <!--<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <a class="dropdown-item" href="newordenservicio.php">Orden de Servicio</a>
                                  <a class="dropdown-item" href="newordenordengarantia.php">Orden de Garantia</a>
                                  <a class="dropdown-item" href="#">Something</a>
                                  <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a>
                              </div>-->
                          </li>
                          <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="no-icon">Mantenimientos</span>
                              </a>
                                <!--<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="clientes.php">Crear Clientes</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <a class="dropdown-item" href="#">Something</a>
                                  <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a>
                              </div>-->
                          </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <i class="nc-icon nc-planet"></i>
                                    <span class="notification">5</span>
                                    <span class="d-lg-none">Notification</span>
                                </a>
                                <!--<ul class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Notification 1</a>
                                    <a class="dropdown-item" href="#">Notification 2</a>
                                    <a class="dropdown-item" href="#">Notification 3</a>
                                    <a class="dropdown-item" href="#">Notification 4</a>
                                    <a class="dropdown-item" href="#">Another notification</a>
                                </ul>-->
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nc-icon nc-zoom-split"></i>
                                    <span class="d-lg-block">&nbsp;Search</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="http://10.5.2.62/logistica/menu.php" class="nav-link">
                                    <!--<i class="nc-icon nc-zoom-split"></i>-->
                                    <span class="d-lg-block" >&nbsp;Regresar</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link">
                                    <span  class="nc-icon nc-circle-09">
                                      <?php
                                       echo $base?>
                                      </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://10.5.2.62/logistica/logout.php">
                                    <span class="nc-icon nc-button-power">  </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-header ">
                                    <h6 class="text-primary">Ingreso Novedades Facturas</h6>
                                </div>
                                <div class="card-body ">
                                  <div class="row">
                                    <div class="col-md-3 pr-1">
                                        <div class="form-group">
                                            <label>Busqueda Por</label>
                                            <select id="buscapor" name="buscapor" class="form-control">
                                                  <option value='0'> Factura </option>
                                                  <option value='1'> Cedula/Ruc </option>
                                                  <option value='2'> Nombre </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 pr-1">
                                        <div class="form-group">
                                            <label>  </label>
                                            <input id="busqueda" name="busqueda" type="text" class="form-control" placeholder="Buscar" value="">
                                        </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div id="novedad" class="col-md-12" style="display:none;">
                          <div class="card ">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-3 pr-1">
                                    <div class="form-group">
                                        <label>Departamento</label>
                                        <select id="departamento" name="departamento" class="form-control" >
                                              <option value='Ninguno'> (Ninguno) </option>
                                              <option value='CALLCENTER'> CALLCENTER </option>
                                              <option value='CREDITO'> CREDITO </option>
                                              <option value='DESPACHO'> DESPACHO </option>
                                              <option value='FACTURACION'> FACTURACION </option>
                                              <option value='SAC'> SAC </option>
                                              <option value='TIENDA'> TIENDA </option>
                                              <option value='WHATSAPP'> WHATSAPP </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 pr-1">
                                    <div class="form-group">
                                        <label>Tipo Novedad</label>
                                        <select id="tiporeclamo" name="tiporeclamo" class="form-control" >
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pr-1">
                                    <div class="form-group">
                                        <label>Novedad</label>
                                        <select id="listnovedad" name="listnovedad" class="form-control" >
                                        </select>
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>Comentario </label>
                                        <textarea id='comentarionov' name='comentarionov'rows="4" cols="80" class="form-control" value=""></textarea>
                                        <input id="facturaid" name="facturaid" type="hidden" class="form-control" readonly value="">
                                        <input id="usuario" name="usuario" type="hidden" class="form-control" readonly value="<?php echo $usuario ?>">
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-6 pr-1">
                                <button type="button" class="btn btn-info btn-fill pull-right" name="btn_save" onclick="SaveNovedad();">Save</button>
                              </div>
                          </div>
                        </div>
                        </div>
                          <div id= "noexistemsn" class="col-md-12"  style="display:none;">
                            <div class="card ">
                              <div class="card-body ">
                                <div class="row">
                                    <h2 class="text-danger" id="noexiste"></h2>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div id="resultados" class="col-md-12"  style="display:none;">
                            <div class="card ">
                              <div class="card-body ">
                                <div class="row">
                                    <h2 class="text-danger" id="resultado"></h2>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div id="datoscedula" class="col-md-12"  style="display:none;">
                            <div class="card ">
                              <div class="card-body ">
                                  <div class="card strpied-tabled-with-hover">
                                        <div class="card-body table-full-width table-responsive">
                                            <table id="Tablecedula" class="table table-hover table-striped">
                                                <thead><th>Fecha</th><th>SucursalID</th><th>Secuencia</th><th>Ruc</th><th>Detalle</th><th>Subtotal</th><th>Dscto</th><th>IVA</th><th>Total</th><th>F.Pago</th></thead>
                                            </table>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div id= "datosfactura" class="col-md-12"  style="display:none;">
                          <div class="card ">
                            <div class="card-body ">
                              <div class="row">
                                  <div class="col-md-3 pr-1"><div class="form-group">
                                      <label>Factura</label>
                                      <input id="factura" name="factura" type="text" class="form-control" readonly value="">
                                  </div></div>
                                  <div class="col-md-3 pr-1"><div class="form-group">
                                      <label>Fecha</label>
                                      <input id="fecha" name="fecha" type="text" class="form-control" readonly value="">
                                  </div></div>
                                  <div class="col-md-3 pr-1"><div class="form-group">
                                      <label>Bodega</label>
                                      <input id="bodega" name="bodega" type="text" class="form-control" readonly value="">
                                  </div></div>
                                  <div class="col-md-3 pr-1"><div class="form-group">
                                      <a id="addnovedad"> <img src="assets\img\addnovedad.png">Novedades</a>
                                  </div></div>
                              </div>
                                <div class="row">
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Ruc</label>
                                        <input id="ruc" name="ruc" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label> Cliente </label>
                                        <input id="cliente" name="cliente" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                      <label>Tienda</label>
                                      <input id="tienda" name="tienda" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pr-1"><div class="form-group">
                                        <label>Direccion</label>
                                        <input id="direccion" name="direccion" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-2 pr-1"><div class="form-group">
                                        <label>Telefono</label>
                                        <input id="telefono" name="telefono" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-4 pr-1"><div class="form-group">
                                        <label>Correo</label>
                                        <input id="correo" name="correo" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Vendedor</label>
                                        <input id="vendedor" name="vendedor" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Pago</label>

                                    </div></div>
                                    <div class="col-md-5 pr-1"><div class="form-group">

                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Preparado</label>
                                        <input id="preparado" name="preparado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Preparado</label>
                                        <input id="fpreparado" name="fpreparado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Verificado</label>
                                        <input id="verificado" name="verificado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Verificado</label>
                                        <input id="fverificado" name="fverificado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>IngresaGuia</label>
                                        <input id="guia" name="guia" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Guia</label>
                                        <input id="fguia" name="fguia" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-2 pr-1"><div class="form-group">
                                        <label>Guia #</label>
                                        <input id="guianum" name="guianum" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-1 pr-1"><div class="form-group">
                                        <label>Bultos</label>
                                        <input id="bultos" name="bultos" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Transporte</label>
                                        <input id="trasnporte" name="trasnporte" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Despachado</label>
                                        <input id="despachado" name="despachado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Despacho</label>
                                        <input id="fdespachado" name="fdespachado" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>Vehiculo</label>
                                        <input id="vehiculo" name="vehiculo" type="text" class="form-control" readonly value="">
                                    </div></div>
                                    <div class="col-md-3 pr-1"><div class="form-group">
                                        <label>F.Vehiculo</label>
                                        <input id="fvehiculo" name="fvehiculo" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pr-1"><div class="form-group">
                                        <label>Comentario Despacho</label>
                                        <input id="comentario" name="comentario" type="text" class="form-control" readonly value="">
                                    </div></div>
                                </div>
                                 <div class="card strpied-tabled-with-hover">
                                    <div class="card-body table-full-width table-responsive">
                                      <table id="Table" class="table table-hover table-striped">
                                        <thead><th>Codigo</th><th>Producto</th><th>Bodega</th><th>Cantidad</th><th>Subtotal</th><th>Dscto</th><th>IVA</th><th>Total</th></thead>
                                      </table>
                                    </div>
                                 </div>
                                <div class="row">
                                  <div class="col-md-2 pr-1"><div class="form-group">
                                      <label>PDF Factura  </label>
                                      <input  type="text" class="form-control" readonly value="">
                                  </div></div>
                                     <div class="col-md-2 pr-1"><div class="form-group">
                                         <label>Subtotal</label>
                                         <input id="subtotal" name="subtotal" type="text" class="form-control" readonly value="">
                                     </div></div>
                                     <div class="col-md-2 pr-1"><div class="form-group">
                                         <label>Descuento</label>
                                         <input id="descuento" name="descuento" type="text" class="form-control" readonly value="">
                                     </div></div>
                                     <div class="col-md-2 pr-1"><div class="form-group">
                                         <label>Financiamiento</label>
                                         <input id="financiamiento" name="financiamiento" type="text" class="form-control" readonly value="">
                                     </div></div>
                                     <div class="col-md-2 pr-1"><div class="form-group">
                                         <label>IVA</label>
                                         <input id="impuesto" name="impuesto" type="text" class="form-control" readonly value="">
                                     </div></div>
                                     <div class="col-md-2 pr-1"><div class="form-group">
                                         <label>Total</label>
                                         <input id="total" name="total" type="text" class="form-control" readonly value="">
                                     </div></div>
                                 </div>
                                 <div class="card strpied-tabled-with-hover">
                                   <div class="card-header ">
                                       <h6 class="text-primary">Novedades Reportadas</h6>
                                   </div>
                                    <div class="card-body table-full-width table-responsive">
                                      <table id="TableN" class="table table-hover table-striped">
                                        <thead><th>Fecha</th><th>Novedad</th><th>Comentario</th><th>Departamento</th><th>Estado</th><th>ReportadoPor</th></thead>
                                      </table>
                                    </div>
                                 </div>
                            </div>
                      </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <p class="copyright text-center">
                            ©
                            <a href="http://www.computron.com.ec" target="_blank">Computronsa</a>, made with love for a better web
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<script type="text/javascript">
//poner foco en el campo serie al cargar pagina
$(document).ready(function(){
	$("#busqueda").focus();
  //recargar_treclamos();
});

$('#departamento').change(function(){
  recargar_treclamos();
  $('#listnovedad').empty();
});

$('#tiporeclamo').change(function(){
  cargar_listnovedad();
});
//Llenar listas d reclamos en base a lo escogido en el select Departamento
function recargar_treclamos(){
  $("#tiporeclamo").empty();
  seleccion= $("#departamento").val();
  var parametros =
  {
      "buscartipo":"1",
      "seleccion":seleccion
  };
  $.ajax(
    {
        data:parametros,
        datatype:'json',
        url: '../servitech/consultascomputron.php',
        type:'POST',
        error:function()
        {alert("Error");},
        success: function(treclamos)
        {
          let jsontreclamos = JSON.parse(treclamos);
          console.log(jsontreclamos);
          var i;
          for(i = 0; i < jsontreclamos.length; i += 1)
            {
              $('#tiporeclamo').append('<option value=' + jsontreclamos[i]["IdReclamo"] + '>' + jsontreclamos[i]["Reclamo"] + '</option>');
            }
        }
  })
}

//Llenar listas d reclamos en base a lo escogido en el select Departamento
function cargar_listnovedad(){
  $("#listnovedad").empty();
  seleccion= $("#tiporeclamo").val();
  var parametros =
  {
      "buscarnovedad":"1",
      "seleccion2":seleccion
  };
  $.ajax(
    {
        data:parametros,
        datatype:'json',
        url: '../servitech/consultascomputron.php',
        type:'POST',
        error:function()
        {alert("Error");},
        success: function(listanovedades)
        {
          let jsonlistanovedades = JSON.parse(listanovedades);
          console.log(jsonlistanovedades);
          var i;
          for(i = 0; i < jsonlistanovedades.length; i += 1)
            {
              $('#listnovedad').append('<option value=' + jsonlistanovedades[i]["IdReclamoDet"] + '>' + jsonlistanovedades[i]["Detalle"] + '</option>');
            }
        }
  })
}


$('#addnovedad').on('click',function(){
  $("#novedad").show();
});
//para dar foco al campo busqueda cada vez q la opcion d busqueda cambia (factura/cedula/nombre)
$('#buscapor').on('change', function() {
    $("#busqueda").focus();
    $("#noexistemsn").hide();
    $("#datosfactura").hide();
    $("#datoscedula").hide();
    $("#busqueda").val("");
});

// se ejecuta funcion de busqueda segun lo seleccionado
$(document.getElementsByName('busqueda')).keypress(function(e) {
	if(e.which == 13  ) {
    buscapor= $("#buscapor").val();
    switch (buscapor) {
      case '0':
      buscar_factura($("#busqueda").val());
      break;
      case '1':
      buscar_cedula();
      break;
      case '2':
      buscar_nombre();
      break;
    }
	}
});

//Buscar cliente en la base y llenar inputs con los datos
function buscar_factura(busfactura)
	{
		//busfactura= $("#busqueda").val();
		var parametros =
		{
				"buscarfactura":"1",
				"txt_busqueda":busfactura
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: '../servitech/consultascomputron.php',
				type:'POST',
				error:function()
				{alert("Error");},
				success: function(valores)
				{
          let jsonParse = JSON.parse(valores);
          console.log(jsonParse);
          if ((jsonParse[0]['existe'])=="0"){
            $("#noexistemsn").show();
            $("#datosfactura").hide();
            $("#noexiste").text("Factura no existe");
            $("#busqueda").val("");
            $("#busqueda").focus();
          }
          else
            {
              $("#noexistemsn").hide();
              $("#datosfactura").show();
              $('#Table > tbody').empty();
              $('#TableF> tbody').empty();
              $('#TableN> tbody').empty();
              var i;
              for(i = 0; i < jsonParse.length; i += 1)
                {
                  if (jsonParse[i]['Section']=='DETALLE'){
                    $("#Table").append('<tbody><tr>' +
                                   	'<td>' + jsonParse[i]['Codigo'] + '</td>'+
                                   	'<td>' + jsonParse[i]['Nombre'] + '</td>'+
                                    '<td>' + jsonParse[i]['Bodega'] + '</td>'+
                                    '<td>' + jsonParse[i]['Cantidad'] + '</td>'+
                                    '<td>' +'$'+jsonParse[i]['Subtotal'] + '</td>'+
                                    '<td>' +'$'+ jsonParse[i]['Descuento'] + '</td>'+
                                    '<td>' +'$'+jsonParse[i]['Impuesto'] + '</td>'+
                                   	'<td>' +'$'+ jsonParse[i]['Total'] + '</td>'+'</tr>');
                    }
                    else
                    {
                      if (jsonParse[i]['Section']=='NOVEDAD'){
                        $("#TableN").append('<tbody><tr>' +
                                        '<td>' + jsonParse[i]['Fecha'] + '</td>'+
                                        '<td>' + jsonParse[i]['Nombre'] + '</td>'+
                                        '<td>' + jsonParse[i]['Comentario'] + '</td>'+
                                        '<td>' + jsonParse[i]['Bodega'] + '</td>'+
                                        '<td>' + jsonParse[i]['Vendedor'] + '</td>'+
                                        '<td>' + jsonParse[i]['Preparado'] + '</td>'+'</tr>');
                      }
                      else {
                              $("#factura").val(jsonParse[i]['Secuencia']);
                              $("#ruc").val(jsonParse[i]['Ruc']);
                              $("#cliente").val(jsonParse[i]['Nombre']);
                              $("#direccion").val(jsonParse[i]['Direccion']);
                              $("#telefono").val(jsonParse[i]['Telefono']);
                              $("#correo").val(jsonParse[i]['Correo']);
                              $("#vendedor").val(jsonParse[i]['Vendedor']);
                              $("#fecha").val(jsonParse[i]['Fecha']);
                              $("#preparado").val(jsonParse[i]['Preparado']);
                              $("#fpreparado").val(jsonParse[i]['FPreparado']);
                              $("#verificado").val(jsonParse[i]['Verificado']);
                              $("#fverificado").val(jsonParse[i]['FVerificado']);
                              $("#guia").val(jsonParse[i]['Guiapor']);
                              $("#fguia").val(jsonParse[i]['Fguia']);
                              $("#guianum").val(jsonParse[i]['Guianum']);
                              $("#bultos").val(jsonParse[i]['Bultos']);
                              $("#trasnporte").val(jsonParse[i]['Transporte']);
                              $("#despachado").val(jsonParse[i]['Despachado']);
                              $("#fdespachado").val(jsonParse[i]['FDespachado']);
                              $("#comentario").val(jsonParse[i]['Comentario']);
                              $("#subtotal").val(jsonParse[i]['Subtotal']);
                              $("#descuento").val(jsonParse[i]['Descuento']);
                              $("#financiamiento").val(jsonParse[i]['Financiamiento']);
                              $("#impuesto").val(jsonParse[i]['Impuesto']);
                              $("#total").val(jsonParse[i]['Total']);
                              $("#ruta").text(jsonParse[i]['Ruta']);
                              $("#facturaid").val(jsonParse[i]['FacturaId']);
                              $("#tienda").val(jsonParse[i]['Sucursal']);
                    }
                  }
                }
                $("#TableN").append('</tbody>');
                $("#TableF").append('</tbody>');
                $("#Table").append('</tbody>');
                $("#busqueda").val("");

        }
      }
	})
}

// BUscar facturas por cedula
function buscar_cedula()
	{
		buscedula= $("#busqueda").val();
		var parametros =
		{
      "buscarcedula":"1",
      "txt_busqueda":buscedula
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: '../servitech/consultascomputron.php',
				type:'POST',
				error:function()
				{alert("Error");},
				success: function(valores)
				{
					let jsonParse = JSON.parse(valores);
					console.log(jsonParse);
					if ((jsonParse[0]['existe'])=="1")
					{
            $('#Tablecedula > tbody').empty();
            $("#datoscedula").show();
            $("#noexistemsn").hide();
            $("#datosfactura").hide();
            var i;
            for(i = 0; i < jsonParse.length; i += 1)
              {
                $("#Tablecedula").append('<tbody><tr>' +
                                '<td>' + jsonParse[i]['Fecha'] + '</td>'+
                                '<td>' + jsonParse[i]['SucursalId'] + '</td>'+
                                '<td>' + jsonParse[i]['Secuencia'] + '</td>'+
                                '<td>' + jsonParse[i]['Ruc'] + '</td>'+
                                '<td>' + jsonParse[i]['Detalle'] + '</td>'+
                                '<td>' +'$'+ jsonParse[i]['Subtotal'] + '</td>'+
                                '<td>' +'$'+ jsonParse[i]['Descuento'] + '</td>'+
                                '<td>' +'$'+jsonParse[i]['Impuesto'] + '</td>'+
                                '<td>' +'$'+jsonParse[i]['Total'] + '</td>'+
                                '<td>' + jsonParse[i]['Pago'] + '</td>'+'</tr>');
              }
					}
					else {
              $("#noexistemsn").show();
              $("#datoscedula").hide();
              $("#noexiste").text("Cedula Incorrecta");
					}
				}
		})
	}

  // BUscar facturas por nombre
  function buscar_nombre()
  	{
  		busnombre= $("#busqueda").val();
  		var parametros =
  		{
        "buscarnombre":"1",
        "txt_busqueda":busnombre
  		};
  		$.ajax(
  			{
  				data:parametros,
  				datatype:'json',
  				url: '../servitech/consultascomputron.php',
  				type:'POST',
  				error:function()
  				{alert("Error");},
  				success: function(valores)
  				{
  					let jsonParse = JSON.parse(valores);
  					console.log(jsonParse);
  					if ((jsonParse[0]['existe'])=="1")
  					{

              $('#Tablecedula > tbody').empty();
              $("#datoscedula").show();
              $("#noexistemsn").hide();
              $("#datosfactura").hide();
              var i;
              for(i = 0; i < jsonParse.length; i += 1)
                {
                  $("#Tablecedula").append('<tbody><tr>' +
                                  '<td>' + jsonParse[i]['Fecha'] + '</td>'+
                                  '<td>' + jsonParse[i]['SucursalId'] + '</td>'+
                                  '<td>' + jsonParse[i]['Secuencia'] + '</td>'+
                                  '<td>' + jsonParse[i]['Ruc'] + '</td>'+
                                  '<td>' + jsonParse[i]['Detalle'] + '</td>'+
                                  '<td>' +'$'+ jsonParse[i]['Subtotal'] + '</td>'+
                                  '<td>' +'$'+ jsonParse[i]['Descuento'] + '</td>'+
                                  '<td>' +'$'+jsonParse[i]['Impuesto'] + '</td>'+
                                  '<td>' +'$'+jsonParse[i]['Total'] + '</td>'+
                                  '<td>' + jsonParse[i]['Pago'] + '</td>'+'</tr>');
                }
  					}
  					else {
                $("#noexistemsn").show();
                $("#datoscedula").hide();
                $("#noexiste").text("No hay datos registrados ");
  					}
  				}
  		})
  	}
//Para grabar la novedad en esta factura
function SaveNovedad(){
      var parametros =
      {
          "guardarnovedad":"1",
          "facturaid":$("#facturaid").val(),
          "novedad":$("#listnovedad").val(),
          "comentarionov": $("#comentarionov").val(),
          "IngresadoPor":$("#usuario").val()
      };
      $.ajax(
        {
          data:parametros,
          datatype:'json',
          url: '../servitech/consultascomputron.php',
          type:'POST',
          beforeSend:function()
          {
            //cuando ponemos . nos referimos a la clase formulario
            },
          error:function()
          {alert("Error");},
          complete:function()
          {
            $('.formulario').show();
            },
          success: function(mensaje)
          {
            buscar_factura($("#factura").val());
            $('#novedad').hide();
            $('#resultados').show();
            $("#resultado").text("Novedad Ingresada con exito");
            $('#resultados').fadeOut(5000);
            $('#comentarionov').val("");
            }
      })
    }

</script>
</html>
<?php } ?>
