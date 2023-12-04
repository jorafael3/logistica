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
	                                    <h4 class="card-title">RMA- Orden de Garantia</h4>
																			<h6>Tipo: ORD-GAR</h6>
	                                </div>
	                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 pr-1">
                                            <div class="form-group">
                                                <label>Fecha Ingreso</label>
                                                 <input type="date" id ="fingreso" class="form-control" name="fingreso" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>">
                                            </div>
                                        </div>
																				<div class="col-md-3 pr-1">
																						<div class="form-group">
																								<label>Serie Producto:</label>
																								<input id="busqueda" name="busqueda" type="text" class="form-control" placeholder="Serie" value="">
																						</div>
																				</div>
                                    </div>
                                    <div class="card-header">
  																			<h4 class="card-title">Datos del Cliente</h4>
  	                                </div>
																					<div class="row">
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label>Factura</label>
																											 <input id="factura" name= "factura" type="text" class="form-control"  readonly placeholder="Factura" value="">
																									</div>
																							</div>
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label> Fecha Factura</label>
																											<input id="fechaf" name="fechaf" type="text" class="form-control" readonly value="">
																									</div>
																							</div>
																					</div>
	                                        <div class="row">
	                                            <div class="col-md-3 pr-1">
	                                                <div class="form-group">
	                                                    <label>Cedula/Ruc</label>
																											 <input id="cedula" name= "cedula" type="text" class="form-control" readonly placeholder="Cedula/Ruc" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-5 pr-1">
	                                                <div class="form-group">
	                                                    <label> Nombre</label>
	                                                    <input id="nombre" name="nombre" type="text" class="form-control" readonly placeholder="Nombre" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-4 pl-1">
	                                                <div class="form-group">
	                                                    <label>Correo</label>
	                                                    <input id="mail" name="mail" type="email" class="form-control" readonly placeholder="Correo@example.com">
	                                                </div>
	                                            </div>
	                                        </div>
																					<div class="row">
																							<div class="col-md-12">
																									<div class="form-group">
																											<label>Direccion</label>
																											<input id="direccion" name="direccion" type="text" class="form-control" readonly placeholder="Direccion" value="">
																									</div>
																							</div>
																					</div>
	                                        <div class="row">
	                                            <div class="col-md-3 pr-1">
	                                                <div class="form-group">
	                                                    <label>Telefono</label>
	                                                    <input id="telefono" name="telefono" type="text" class="form-control" readonly placeholder="04999999" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-3 pl-1">
	                                                <div class="form-group">
	                                                    <label>Celular</label>
	                                                    <input id="celular" name="celular" type="text" class="form-control" readonly placeholder="0999999999" value="">
	                                                </div>
	                                            </div>
	                                        </div>
																					<div class="row">
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label>Tipo Cliente</label>
																											<input id="tipocli" name="tipocli" type="text" class="form-control" readonly value="">
																									</div>
																							</div>
																					</div>
																					<div class="row" id="cfinal" style="display:none;" >
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label>Factura# </label>
																											<input id="facturaufc" name="facturaufc" type="text" class="form-control"  placeholder="Factura" value="">
																									</div>
																							</div>
																							<div class="col-md-3 pl-1">
																									<div class="form-group">
																											<label>Cliente Final</label>
																											<input id="clientef" name="clientef" type="text" class="form-control" placeholder="Cliente" value="">
																									</div>
																							</div>
																					</div>
                                          <div class="card-header">
        																			<h4 class="card-title">Datos del Producto</h4>
        	                                </div>
	                                        <div class="row">
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label>Codigo</label>
																											<input id="codigo" name="codigo" type="text" readonly class="form-control" placeholder="Codigo" value="">
																									</div>
																							</div>
	                                            <div class="col-md-5 pr-1">
	                                                <div class="form-group">
	                                                    <label>Producto</label>
	                                                    <input id="producto" name="producto" type="text" readonly class="form-control" placeholder="Producto" value="">
	                                                </div>
	                                            </div>
	                                            <div class="col-md-4 px-1">
	                                                <div class="form-group">
	                                                    <label>Modelo</label>
	                                                    <input id="modelo" name="modelo" type="text" readonly class="form-control" placeholder="Modelo" value="">
	                                                </div>
	                                            </div>
	                                        </div>
																					<div class="row">
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																											<label>Marca</label><br>
																											<select id="marca" class="form-control" readonly name="marca"></select>
																									</div>
																							</div>
																							<div class="col-md-5 pr-1">
																									<div class="form-group">
																											<label>Serie</label>
																											<input id="serie" name="serie" type="text" readonly class="form-control" placeholder="Serie" value="">
																									</div>
																							</div>
																							<div class="col-md-4 px-1">
																									<div class="form-group">
																											<label>Vencimiento Garantia</label>
																											<input id="vencimiento" name="vencimiento" type="text" readonly class="form-control" value="">
																									</div>
																							</div>
																					</div>
																					<div class="row">
																							<div class="col-md-3 pr-1">
																									<div class="form-group">
																										<label>Problema</label><br>
																										<select id="problema" class="form-control">
																													<option value='0'> (Ninguno) </option>
																													<option value='1'> No Enciende </option>
																													<option value='2'> Golpeado </option>
																													<option value='3'> Mantenimiento </option>
																										</select>
																									</div>
																							</div>
																					</div>
	                                        <div class="row">
	                                            <div class="col-md-12">
	                                                <div class="form-group">
	                                                    <label>Accesorios</label>
	                                                    <textarea id='accesorios' name='accesorios' rows="4" cols="80" class="form-control" placeholder="Accesorios" value=""></textarea>
	                                                </div>
	                                            </div>
	                                        </div>
																					<div class="row">
																							<div class="col-md-12">
																									<div class="form-group">
																											<label>Observaciones</label>
																											<textarea id='observaciones' name='observaciones'rows="4" cols="80" class="form-control" placeholder="Observaciones" value=""></textarea>
																									</div>
																							</div>
																					</div>
                                          <div class="card-header">
        																			<h4 class="card-title">Asignar Tecnico</h4>
        	                                </div>
																					<div class="row">
	                                            <div class="col-md-5 pr-1">
	                                                <div class="form-group">
	                                                    <label>Tecnico</label><br>
                                                        <select id="tecnico" class="form-control" name="tecnico"></select>
	                                                </div>
	                                            </div>
	                                        </div>
	                                        <button type="button" class="btn btn-info btn-fill pull-right" name="btn_save" onclick="SaveGarantia();">Save</button>
																					<input name="facturaid" type="hidden" id="facturaid" size= "25%" value= "" >
	                                        <input name="clienteid" type="hidden" id="clienteid" size= "25%" value= "" >
	                                        <input name="productoid" type="hidden" id="productoid" size= "25%" value= "" >
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
//poner foco en el campo serie al cargar pagina
$(document).ready(function(){
	$("#busqueda").focus();
});
//Cargar arreglo de marcas con consulta a la base al cargar la pagina
$(document).ready(function(){
	var parametros =
		{
				"bmarcas":"1"
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'consultasservitech.php',
				type:'POST',
				success: function(marcas)
					{
						let jsonParse = JSON.parse(marcas);
						var i;
						for(i = 0; i < jsonParse.length; i += 1)
							{
								$('#marca').append('<option value=' + jsonParse[i]["nombre"] + '>' + jsonParse[i]["nombre"] + '</option>');
							}
					}
			})
})

//Cargar arreglo de tecnicos con consulta a la base al cargar la pagina
$(document).ready(function(){
	var parametros =
		{
				"btecnicos":"1"
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'consultasservitech.php',
				type:'POST',
				success: function(tecnicos)
					{
						let jsontecnicos = JSON.parse(tecnicos);
						console.log(jsontecnicos);
						var i;
						for(i = 0; i < jsontecnicos.length; i += 1)
							{
								$('#tecnico').append('<option value=' + jsontecnicos[i]["ID"] + '>' + jsontecnicos[i]["nombre"] + '</option>');
							}

					}
			})
})

$(document.getElementsByName('busqueda')).keypress(function(e) {
	if(e.which == 13  ) {
		buscar_serie();
	}
});

//Buscar cliente en la base y llenar inputs con los datos
function buscar_serie()
	{
		busqueda= $("#busqueda").val();
		var parametros =
		{
				"buscarinfo":"1",
				"txt_busqueda":busqueda
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'consultasservitech.php',
				type:'POST',
				beforeSend:function()
				{
					//cuando ponemos . nos referimos a la clase formulario
					$('.formulario').hide();
					},
				error:function()
				{alert("Error");},
				complete:function()
				{
					$('.formulario').show();
					},
				success: function(info)
				{
					let jsonParse = JSON.parse(info);
					console.log(jsonParse);
					if (jsonParse.cant==0){alert ("Serie NO registrada");}
					else {
						if (jsonParse.cant==1){
							$("#cedula").val(jsonParse.cedula);
							$("#nombre").val(jsonParse.nombre);
							$("#telefono").val(jsonParse.tel);
							$("#direccion").val(jsonParse.direc);
							$("#celular").val(jsonParse.celu);
							$("#mail").val(jsonParse.email);
							$("#clienteid").val(jsonParse.clienteid);
							$("#facturaid").val(jsonParse.facturaid);
							$("#factura").val(jsonParse.secuencia);
							$("#fechaf").val(jsonParse.fecha);
							$("#tipocli").val(jsonParse.TipoCli);
							$("#codigo").val(jsonParse.codigo);
							$("#producto").val(jsonParse.producto);
							$("#modelo").val(jsonParse.modelo);
							$("#serie").val(jsonParse.serie);
							$("#vencimiento").val(jsonParse.fechagar);
							$("#productoid").val(jsonParse.productoid);
							$("#marca option[value="+ jsonParse.marca +"]").attr("selected",true);
							if(jsonParse.TipoCli !="UFC"){$("#cfinal").show(); $("#facturaufc").focus();}else{$("#cfinal").hide();$("#problema").focus();}
						}
						else {
							alert ("Serie registrada en varias facturas");
						}
					}
					$("#busqueda").val("");
			}
	})
}
//Funcion para grabar la orden de Servicio
	function SaveGarantia(){
		var parametros =
		{
				"guardargar":"1",
				"facturaid":$("#facturaid").val(),
				"clienteid":$("#clienteid").val(),
				"productoid":$("#productoid").val(),
				"facturaufc": $("#facturaufc").val(),
				"clientef": $("#clientef").val(),
				"codigo": $("#codigo").val(),
				"producto": $("#producto").val(),
				"modelo": $("#modelo").val(),
				"serie": $("#serie").val(),
				"marca": $("#marca").val(),
				"vencimiento":$("#vencimiento").val(),
				"problema":$("#problema").val(),
				"accesorios":$("#accesorios").val(),
				"observaciones":$("#observaciones").val(),
				"tecnico":$("#tecnico").val(),
				"IngresadoPor":$("#ingresadopor").val()
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'consultasservitech.php',
				type:'POST',
				beforeSend:function()
				{
					//cuando ponemos . nos referimos a la clase formulario
					$('.formulario').hide();
					},
				error:function()
				{alert("Error");},
				complete:function()
				{
					$('.formulario').show();
					},
				success: function(mensaje)
				{$('.resultados').html(mensaje);}
		})
	 limpiar();
	}

	//borrar datos del formulario
	function limpiar(){
		document.getElementById("formorden").reset();
		$('.resultados').html(" ");
	}
</script>
</html>
