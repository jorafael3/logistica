<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body>
	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$id = $_GET["id"];
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
			}
			//echo " 1 esto envio".$base.$usuario.$acceso;
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">
			<div id="izq"></div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Usuarios </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a></div>
		</div>
		<hr>
		<?php
			//echo "2esto envio".$base.$usuario.$acceso;
			$usuario1 = $usuario;
			require('conexion_mssql.php');
			//echo "3esto envio".$base.$usuario1.$acceso;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);

			//Select Query
			$result = $pdo->prepare("SELECT 
			usrid,usuario,nombre,acceso,lugartrabajo,clave,departamento,
			SGL_DROPSHIPING,
			SGL_DROPSHIPING_UIO,
			SGL_DROPSHIPING_GYE
			FROM seriesusr WITH (NOLOCK) where usrid=:id");
			$result->bindParam(':id', $id, PDO::PARAM_STR);
			//Executes the query
			$result->execute();


			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$usrid = $row['usrid'];
				$usermo = $row['usuario'];
				$nombre = $row['nombre'];
				$acc = $row['acceso'];
				$lugartrabajo = $row['lugartrabajo'];
				$clave = $row['clave'];
				$dpto = $row['departamento'];
				$drop = $row['SGL_DROPSHIPING'];
				$gye = $row['SGL_DROPSHIPING_UIO'];
				$uio = $row['SGL_DROPSHIPING_GYE'];
			}
		?>

		<div id="cuerpo2" align="center">
			<div>
				<form name="formusuario" action="modificarusuario2.php" method="POST">
					<table align="center">
						<tr>
							<td id="label">Id: </td>
							<td id="box"> <input readonly name="id" type="text" id="id" size="32" value="<?php echo $usrid ?>"> </td>
						</tr>
						<tr>
							<td id="label">Usuario: </td>
							<td id="box"> <input readonly name="usermo" type="text" id="usermo" size="32" value="<?php echo $usermo ?>"> </td>
						</tr>
						<tr>
							<td id="label">Nombre: </td>
							<td id="box"> <input name="nombre" type="text" id="nombre" size="32" value="<?php echo $nombre ?>"> </td>
						</tr>
						<tr>
							<td id="label">Clave: </td>
							<td id="box"> <input name="clave" type="password" id="clave" size="32" value="<?php echo $clave ?>"> </td>
						</tr>
						<tr>
							<td id="label">Acceso: </td>
							<td id="box"> <input name="acc" type="text" id="acc" size="32" value="<?php echo $acc ?>"> </td>
						</tr>
						<tr>
							<td id="label">Sucursal: </td>
							<td>
								<select name="lugartrabajo" id="lugartrabajo">
									<?php
									require('conexion_mssql.php');
									$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									//new PDO($dsn, $sql_user, $sql_pwd);
									//Select Query
									$result1 = $pdo1->prepare("SELECT ID, Nombre FROM sis_sucursales WITH (NOLOCK) where anulado = 0 order by 1");
									//Executes the query
									$result1->execute();
									while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
									?>
										<option value="<?php echo $row1['ID']; ?>"><?php echo $row1['Nombre']; ?></option>
									<?php
									}
									?>
								</select>

							</td>
						</tr>
						<tr>
							<td id="label">Dpto: </td>
							<td id="box"> <input name="dpto" type="text" id="dpto" size="32" value="<?php echo $dpto ?>"> </td>
						</tr>
						<tr>
							<td id="label">DROP: </td>
							<td>
								<h4>GYE</h4>
								<?php
								if ($gye == 1) {
								?>
									<input checked name="CHECKGYE" type="checkbox">

								<?php

								} else {
								?>
									<input name="CHECKGYE" type="checkbox">

								<?php

								}
								?>
							</td>
							<td>
								<h4>uio</h4>
								<?php
								if ($gye == 1) {
								?>
									<input checked name="CHECKUIO" type="checkbox">

								<?php

								} else {
								?>
									<input name="CHECKUIO" type="checkbox">

								<?php

								}
								?>
							</td>
						</tr>
						<tr>
							<td id="label"></td>
							<td id="label"> Grabar
								<input name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image">
								<a href="consultarusuarios.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>


			</div>
		</div>
	<?php
			$_SESSION['base'] = $base;
			$_SESSION['usuario'] = $usuario1;
			$_SESSION['acceso'] = $acceso;

			//echo "esto envio".$base.$usuario1.$acceso; 
		} else {
			header("location: index.html");
		}
	?>
	</div>
</body>