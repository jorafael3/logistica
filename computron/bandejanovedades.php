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
    <!--Bootstrap para Modal (pantalla de actualzaciones, etc ) -->

    <script type="text/javascript" src="js/contact.js"></script>
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
                      <?php
                       echo $base?>
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
                                       echo $usuario?>
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
            <div id="contact">
               <!-- <button type="button" class="btn btn-info btn" data-toggle="modal" data-target="#contact-modal">Show Contact Form</button></div>-->
                <div id="contact-modal" class="modal fade" role="dialog">
                	<div class="modal-dialog">
                		<div class="modal-content">
                			<div class="modal-header">
                				<h3>Actualizar Estado </h3>
                			</div>
                			<form id="contactForm" name="contact" role="form">
                				<div class="modal-body">
                          <div class="row">
                            <div class="col-md-4 pr-1">
                    					<div class="form-group">
                    						<label for="facturaid">Id</label>
                    						<input id="facturaid" type="text" name="facturaid" readonly class="form-control">
                    					</div>
                            </div>
                            <div class="col-md-5 pr-1">
                                <div class="form-group">
                      						<label for="secuencia">Secuencia</label>
                      						<input id = "secuencia" type="text" name="secuencia" readonly class="form-control">
                      					</div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10 pr-1">
                                <div class="form-group">
                      						<label for="cliente">Cliente</label>
                      						<input id = "cliente" type="text" name="cliente" readonly class="form-control">
                      					</div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4 pr-1">
                      					<div class="form-group">
                      						<label for="estado">Estado</label>
                                  <select id="estado" name="estado" class="form-control">
                                        <option value='Reportado'> Reportado </option>
                                        <option value='Resuelto'> Resuelto </option>
                                        <option value='NoResuelto'> NoResuelto </option>
                                        <option value='Reasignado'> Reasignado </option>
                                  </select>
                      					</div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-10 pr-1">
                                <div class="form-group">
                                  <label for="comentario">Comentario</label>
                                  <textarea id = "comentario"   name="comentario"   class="form-control"> </textarea>
                                </div>
                            </div>
                          </div>
                				</div>
                				<div class="modal-footer">
                					<button type="button" class="btn btn-warning btn-fill pull-right" data-dismiss="modal">Close</button>
                					<button type="button" class="btn btn-info btn-fill pull-right" data-dismiss="modal" onclick="showactualiza();">Actualizar</button>
                				</div>
                			</form>
                		</div>
                	</div>
                </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-header ">
                                    <h6 class="text-primary">Bandeja Novedades Facturas</h6>
                                </div>
                            </div>
                        </div>
                        <div id="datoscedula" class="col-md-12">
                            <div class="card ">
                              <div class="card-body ">
                                  <div class="card strpied-tabled-with-hover">
                                        <div class="card-body table-full-width table-responsive">
                                            <table id="novedades" class="table table-hover table-striped">
                                                <thead><th></th><th>Fecha</th><th>Secuencia</th><th>Cliente</th><th>Ciudad</th><th>Tienda</th><th>Vendedor</th><th>TipoPago</th><th>TipoNovedad</th><th>Departamento</th><th>Novedad</th><th>Estado</th></thead>
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
    </div> <!-- cierra el MainPanel -->
</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> -->
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	cargar_novedades();
});

function cargar_novedades(){
  var parametros =
		{
				"cargarnove":"1"
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: '../servitech/consultascomputron.php',
				type:'POST',
				success: function(novedades)
					{
						let jsonParse = JSON.parse(novedades);
            console.log(jsonParse);
            $('#novedades> tbody').empty();
            var i;
            var x ;
            for(i = 0; i < jsonParse.length; i += 1)
              {

                $("#novedades").append('<tbody><tr>' +
                                '<td><input id="facturaid" type="hidden" class="form-control" value="' + jsonParse[i]['FacturaId'] + '"></td>'+
                                '<td><input id="fecha" type="text" class="form-control" value="' + jsonParse[i]['Fecha'] + '"></td>'+
                                '<td><input id="secuencia" type="text" class="form-control" value="' + jsonParse[i]['Secuencia'] + '"></td>'+
                                '<td><input id="detalle" type="text" class="form-control" value="' + jsonParse[i]['Detalle'] + '"></td>'+
                                '<td><input id="ciudad" type="text" class="form-control" value="' + jsonParse[i]['Ciudad'] + '"></td>'+
                                '<td><input id="tienda" type="text" class="form-control" value="' + jsonParse[i]['Tienda'] + '"></td>'+
                                '<td><input id="vendedor" type="text" class="form-control" value="' + jsonParse[i]['Vendedor'] + '"></td>'+
                                '<td><input id="fpago" type="text" class="form-control" value="' + jsonParse[i]['FPago'] + '"></td>'+
                                '<td><input id="treclamo" type="text" class="form-control" value="' + jsonParse[i]['TipoReclamo'] + '"></td>'+
                                '<td><input id="dpto" type="text" class="form-control" value="' + jsonParse[i]['Dpto'] + '"></td>'+
                                '<td><input id="novedad" type="text" class="form-control" value="' + jsonParse[i]['Novedad'] + '"></td>'+
                                '<td><input id="estado" type="text" class="form-control" value="' + jsonParse[i]['Estado'] + '"></td>'+
                                '<td><button type="button" id="update" data-toggle="modal" data-target="#contact-modal" class="btn btn-info btn-fill pull-right" value="" onclick="datoaactualizar('+jsonParse[i]['Datos'] +');">Update</td>'+'</tr>');
              }
              $("#novedades").append('</tbody>');
					}
			});
}

function datoaactualizar(Datos)
  {
    d=Datos.split('||');
    $('#facturaid').val(d[0]);
    $('#secuencia').val(d[1]);
    $('#cliente').val(d[2]);
    $("#estado option[value="+ d[3] +"]").attr("selected",true);
  }

function showactualiza(){
var parametros =
  {
      "actualizaestado":"1",
      "facturaid":$("#facturaid").val(),
      "estado":$("#estado").val()
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

      success: function(mensaje)
      {
        //Aqui va todo lo que hace cuando la funcion es exitosa

        cargar_novedades();
        }
  })
}



</script>


</html>
<?php }

?>
