<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Servitech</title>
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
        <div class="sidebar" data-image="assets/img/sidebar-4.jpg" data-color="blue">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

        Tip 2: you can also add an image using data-image tag
  -->
            <div class="sidebar-wrapper">
                <div class="logo">
									<a href="" class="simple-text">
											SERVITECH
									</a>
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
                              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <a class="dropdown-item" href="newordenservicio.php">Orden de Servicio</a>
                                  <a class="dropdown-item" href="newordengarantia.php">Orden de Garantia</a>
                                  <a class="dropdown-item" href="#">Something</a>
                                  <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a>
                              </div>
                          </li>
                          <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="no-icon">Mantenimientos</span>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <a class="dropdown-item" href="clientes.php">Crear Clientes</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <a class="dropdown-item" href="#">Something</a>
                                  <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a>
                              </div>
                          </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <i class="nc-icon nc-planet"></i>
                                    <span class="notification">5</span>
                                    <span class="d-lg-none">Notification</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Notification 1</a>
                                    <a class="dropdown-item" href="#">Notification 2</a>
                                    <a class="dropdown-item" href="#">Notification 3</a>
                                    <a class="dropdown-item" href="#">Notification 4</a>
                                    <a class="dropdown-item" href="#">Another notification</a>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nc-icon nc-zoom-split"></i>
                                    <span class="d-lg-block">&nbsp;Search</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link">
                                    <span  class="nc-icon nc-circle-09">
                                      <?php
                              		    if (isset($_SESSION['loggedin']))
                              				{
                              					$usuario = $_SESSION['usuario'];
                              					$base = $_SESSION['base'];} echo $usuario?>
                                      </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://10.5.2.62/logistica/logout.php">
                                    <span class="no-icon">Log out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
						<form id = "formorden"  class = "formorden"method="POST" width="100%">
							<div class="content">
	                <div class="container-fluid">
	                    <div class="row">
	                        <div class="col-md-8">
	                            <div class="card">
	                                <div class="card-header">
	                                    <h4 class="card-title">RMA- Crear Cliente</h4>
	                                </div>
	                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 pr-1">
                                          <div id='nocliente' name = "nocliente" class="alert alert-warning alert-dismissible fade show"  role="alert" style="display:none;"><h6>Cliente no existe</h6>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
  																			<h4 class="card-title">Datos del Cliente</h4>
  	                                </div>
	                                        <div class="row">
	                                            <div class="col-md-2 pr-1">
	                                                <div class="form-group">
	                                                    <label>Cedula/Ruc</label>
																											 <input id="cedula" name= "cedula" type="text" class="form-control" placeholder="Cedula/Ruc" value="">
	                                                </div>
	                                            </div>
                                          </div>
                                          <div class="row">
	                                            <div class="col-md-4 pr-1">
	                                                <div class="form-group">
	                                                    <label> Primer Nombre</label>
	                                                    <input id="pnombre" name="pnombre" type="text" class="form-control"  placeholder="Primer Nombre" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-4 pl-1">
	                                                <div class="form-group">
	                                                    <label>Segundo Nombre</label>
	                                                    <input id="snombre" name="snombre" type="email" class="form-control"  placeholder="Segundo Nombre">
	                                                </div>
	                                            </div>
	                                        </div>
                                          <div class="row">
	                                            <div class="col-md-4 pr-1">
	                                                <div class="form-group">
	                                                    <label> Primer Apellido</label>
	                                                    <input id="papellido" name="papellido" type="text" class="form-control"  placeholder="Primer Apellido" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-4 pl-1">
	                                                <div class="form-group">
	                                                    <label>Segundo Apellido</label>
	                                                    <input id="sapellido" name="sapellido" type="email" class="form-control"  placeholder="Segundo Apellido">
	                                                </div>
	                                            </div>
	                                        </div>
																					<div class="row">
																							<div class="col-md-10">
																									<div class="form-group">
																											<label>Direccion</label>
																											<input id="direccion" name="direccion" type="text" class="form-control"  placeholder="Direccion" value="">
																									</div>
																							</div>
																					</div>
	                                        <div class="row">
	                                            <div class="col-md-3 pr-1">
	                                                <div class="form-group">
	                                                    <label>Telefono</label>
	                                                    <input id="telefono" name="telefono" type="text" class="form-control"  placeholder="04999999" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-3 pl-1">
	                                                <div class="form-group">
	                                                    <label>Celular</label>
	                                                    <input id="celular" name="celular" type="text" class="form-control"  placeholder="0999999999" value="">
	                                                </div>
	                                            </div>
                                              <div class="col-md-4 pl-1">
	                                                <div class="form-group">
	                                                    <label>Correo</label>
	                                                    <input id="mail" name="mail" type="email" class="form-control" placeholder="Correo@example.com">
	                                                </div>
	                                            </div>
	                                        </div>
                                          <div class="row">
	                                            <div class="col-md-5 pl-1">
	                                                <div class="form-group">
	                                                    <button type="button" class="btn btn-info btn-fill pull-right" name="btn_save" onclick="SaveService();">Save</button>
	                                                </div>
	                                            </div>
	                                        </div>
                                          <input name="clienteid" type="hidden" id="clienteid" size= "25%" value= "" >
                          				        <input name="ingresadopor" type="hidden" id="ingresadopor" size= "25%" value= "<?php echo $usuario ?>" >
	                                        <div class="clearfix"></div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
					</form>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<script type="text/javascript">
//poner foco en el campo cedula al cargar pagina
$(document).ready(function(){
	$("#cedula").focus();
});

	//borrar datos del formulario
	function limpiar(){
		document.getElementById("formorden").reset();
		$('.resultados').html(" ");
	}
</script>

</html>
