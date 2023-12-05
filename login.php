 <!DOCTYPE html>
 <html>
 <script type="text/javascript">
 </script>
 <link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
 <link href="estilos/estilos.css" rel="stylesheet" type="text/css">
 <?php require 'head.php'; // Llama a la cabecera a utilizar en toda las páginas 
	?>

 <BODY>
 	<div id="Todo">
 		<div id="Cuerpo">

 			<?PHP

				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
				$usuario = trim($_POST['Usr']);
				$pass = $_POST['Pwd'];
				$empresa = $_POST['Empresa'];
				$base = $_POST['Empresa'];
				//<meta charset="UTF-8"/>
				header('Content-type: text/html; charset=iso-8859-1');
				//$header .= "Content-type: text/html; charset=iso-8859-1 \r\n";
				?>
 			<div id="container">
 				<div id="izq"> </div>

 				<?php

					//Encriptar clave para ingresar 



					require('conexion_mssql.php');
					echo "Empresa" . $empresa;
					/* if ($empresa == 'SERVITECH') 
					{  	$passecripta= strtolower($pass);
						$passecripta = strtoupper($passecripta);
						$pass= ''; 
						$cont= strlen($passecripta); 
						for ( $w = 1 ; $w <  strlen($passecripta) ; $w++)
							{
								$pass = $pass.Chr(ord(substr($passecripta, $cont -1, 1))+60 + $cont);
								$cont= $cont-1; 	
							}
							 
							echo "entra". $pass ;
					 
					}
			 */
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("LOG_USUARIO_LOGIN @usuario = :usuario , @pass=:pass , @empresa=:empresa");
					$result->bindParam(':usuario', $usuario, PDO::PARAM_STR);
					$result->bindParam(':pass', $pass, PDO::PARAM_STR);
					$result->bindParam(':empresa', $empresa, PDO::PARAM_STR);
					//Executes the query
					$result->execute();
					$count = $result->rowcount();

					echo $count;
					if ($count  == 0) {
					?>
 					<div id="centro"> <a class="titulo">
 							<center> Error de usuario o contrasena</center>
 						</a></div><br>
 				<?php
						header("Refresh: 1  ; URL=/logistica");
					} else {
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							$usuario = $row['usuario'];
							$usrnombre = $row['nombre'];
							$acceso = $row['acceso'];
							$loggedin = $row['usuario'];
							$bodega = $row['codbodega'];
							$nomsuc = $row['nomsuc'];
							session_start();
							$_SESSION['loggedin'] = $loggedin;
							$_SESSION['usuario'] = $usuario;
							$_SESSION['usrnombre'] = $usrnombre;
							$_SESSION['acceso'] = $acceso;
							$_SESSION['base'] = $base;
							$_SESSION['empresa'] = $empresa;
							$_SESSION['bodega'] = $bodega;
							$_SESSION['nomsuc'] = $nomsuc;
							header("location: menu.php");

							if ($empresa == "COMPUTRONSA") {
								$_SESSION['drop'] = $row["SGL_DROPSHIPING"];
								$_SESSION['drop_gye'] = $row["SGL_DROPSHIPING_GYE"];
								$_SESSION['drop_uio'] = $row["SGL_DROPSHIPING_UIO"];
							}
						}
					}
					?>
 			</div>
 		</div>
 		<BR>
 </body>

 </html>