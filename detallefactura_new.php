<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/tablas.css">
	<link rel="stylesheet" type="text/css" href="css/boton.css">
	<html>
	<!-- <head> -->
	<title>SGL</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/menus.css"> -->
	<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
	<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

</head>
<html>

<body>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#ddlpickup").change(function() {
				if ($(this).val() == "Entrega en tienda") {
					$("#dvdespacho").show();
				} else {
					$("#dvdespacho").hide();
				}
			});
		});
	</script>

	<script>
		function getState(val) {
			$.ajax({
				type: "POST",
				url: "get_state.php",
				data: 'idgrupo=' + val,
				success: function(data) {
					$("#state-list").html(data);
				}
			});
		}
	</script>

	<div id="header" align="center">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$("#ddlpickup").change(function() {
					if ($(this).val() == "Casillero") {
						$("#casilleros").show();
					} else {
						$("#casilleros").hide();
					}
				});
			});
		</script>


		<?PHP
		session_start();
		//Variables de sesion de SGL
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso	= $_SESSION['acceso'];
		$secu = TRIM($_POST["secu"]);
		$bodegaf = $_SESSION['bodega'];
		$nomsuc = $_SESSION['nomsuc'];
		$bodegaFAC = $_SESSION['bodegaFAC'];
		$_SESSION['usuario'] = $usuario;
		if ($base == 'CARTIMEX') {
			require 'headcarti.php';
		} else {
			require 'headcompu.php';
		}
		// aqui ingreso numero de guia y bultos
		//echo "bodega facturacion".$bodegaf;
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Guias Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center" width="100%">
			
		<div className="card mb-4">
    <div className="card-header">
        <div className="card-title d-flex flex-column">
            <span className="fs-2 fw-bold text-dark">69,700</span>
            <span className="text-muted pt-1 fw-semibold fs-6">Expected Earnings This Month</span>
        </div>
    </div>
    <div className="card-body d-flex align-items-end pt-0">
        <div className="d-flex me-3">
            <canvas className="chart" width="78" height="78"></canvas>
        </div>
        <div className="d-flex flex-column">
            <div className="d-flex fs-6 fw-semibold align-items-center mb-1">
                <div className="bullet bg-success me-3"></div>
                <div className="text-dark">Used Truck freight</div>
                <div className="separator mx-2"></div>
                <div className="fw-bolder text-end">45%</div>
            </div>
            <div className="d-flex fs-6 fw-semibold align-items-center mb-1">
                <div className="bullet bg-primary me-3"></div>
                <div className="text-dark">Used Ship freight</div>
                <div className="separator mx-2"></div>
                <div className="fw-bolder text-end">21%</div>
            </div>
            <div className="d-flex fs-6 fw-semibold align-items-center">
                <div className="bullet" style={{ backgroundColor: '#E4E6EF' }}></div>
                <div className="text-dark">Used Plane freight</div>
                <div className="separator mx-2"></div>
                <div className="fw-bolder text-end">34%</div>
            </div>
        </div>
    </div>
</div>

		</div>
	</div>
</body>