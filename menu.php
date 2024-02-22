<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript"></script>
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">


<body>
	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$usrnombre = $_SESSION['usrnombre'];
			$acceso = $_SESSION['acceso'];
			$base = $_SESSION['base'];
			$empresa = $_SESSION['empresa'];
			$bodega = $_SESSION['bodega'];
			// echo $bodega;
			$drop = $_SESSION['drop'];
			$puerta_p = $_SESSION['puerta_p'];

			//echo "Empresa".$empresa;
			if ($empresa == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				if ($empresa == 'COMPUTRONSA') {
					require 'headcompu.php';
				} else {
					$_SESSION['usuario'] = $usuario;
					$_SESSION['base'] = $base;
					$_SESSION['acceso'] = $acceso;
					$_SESSION['bodega'] = $bodega;
					// $_SESSION['drop'] = $;
					header("location: servitech/boardservitech.php");
					//		require 'servitech/boardservitech.php';
				}
			}
		?>
	</div>

	<div id="Cuerpo">
		<div id="cuerpo2">
			<?php			/*	<div id="container"  >
					<div id = "izq" > <strong> Usuario:</strong> <?php echo $usuario ?></div>
					<div id = "centro" align= "center">*/


			//echo "Acceso".$acceso;
			if ($empresa == 'CARTIMEX') {
				switch ($acceso) {
					case 1:
						include "menucartimex1.html";
						break; //Administrator
					case 2:
						include "menucartimex2.html";
						break; // Supervisor
					case 3:
						include "menucartimex3.html";
						break; //Preparador Galpon y Verificador
					case 4:
						include "menucartimex4.html";
						break; //Solo Preparador	Galpon
					case 12:
						include "menucartimex12.html";
						break; // Preparador Jaula
					case 6:
						include "menucartimex6.html";
						break; //Ventas
					case 7:
						include "menucartimex7.html";
						break; //Produccion
					case 8:
						include "menucartimex8.html";
						break; //Compras
				}

				/*

							if ($acceso == 3)
								{
									include "menucartimex.html";
								}*/
			} else {
				if ($empresa == 'COMPUTRONSA') {
					switch ($acceso) {
						case 1:
							if ($drop == 1) {

								if ($puerta_p == 1) {
									include "menucomputronsa_puerta.html";
									break; //Administrator
								} else {
									include "menucomputronsa1.html";
									break; //Administrator
								}
							} else {
								include "menucomputronsa1.html";
								break; //Administrator
							}

						case 2:
							include "menucomputronsa2.html";
							break; // Supervisor
						case 3:
							include "menucomputronsa3.html";
							break; //Verificador
						case 4:
							include "menucomputronsa4.html";
							break; //Preparador
						case 5:
							include "menucomputronsa5.html";
							break; //Entregador
						case 6:
							//include "menucomputronsa6.html"; break;//Ventas ya esta en SGO 
						case 7:
							include "menucomputronsa7.html";
							break; // Preparacion abreviada
						case 8:
							include "menucomputronsa2.html";
							break; // Supervisor Regional Sierra
						case 9:
							include "menucomputronsa2.html";
							break; // Supervisor Regional Costa
						case 10:
							//	include "menucomputronsa10.html"; break;// Credito ya esta en sgo 
						case 11:
							//include "menucomputronsa1d1.html"; break;// Credito Directo ya esta en SGO 
					}
				}
			}

			/*</div>

				</div>
				<div id = "derecha" > <a href="logout.php" > Cerrar sesion</a></div>*/
			?> </div>

		<hr>
	</div>
<?php

			$_SESSION['usuario'] = $usuario;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['bodega'] = $bodega;
			$_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}

?>
<div id="footer" align="center" class="fw-bold">
	<br>
	&copy; Copyright 2024 All Rights Reserved. This website was built by
	Cartimex S.A
</div>

</body>