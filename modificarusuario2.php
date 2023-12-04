<html>

<body>

	<body oncontextmenu="return false">

		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];

			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
			}

			$codid = $_POST["id"];
			$usermo = $_POST["usermo"];
			$nombre = $_POST["nombre"];
			$clave = $_POST["clave"];
			$acc = $_POST["acc"];
			$lugartrabajo = $_POST["lugartrabajo"];
			$dpto = $_POST["dpto"];
			$GYE = $_POST["CHECKGYE"];
			$UIO = $_POST["CHECKUIO"];
			$drop = 0;

			if ($GYE == "on" || $UIO == "on") {
				$drop = 1;
			}

			if ($GYE == "on") {
				$GYE =  1;
			} else {
				$GYE =  0;
			}

			if ($UIO == "on") {
				$UIO =  1;
			} else {
				$UIO =  0;
			}
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----".$dpto; 


			$usuario1 = $usuario;
			require('conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("LOG_MODIFICAR_USUARIOS  
			@nombre=:nombre , 
			@clave=:clave ,
			@acceso=:acceso , 
			@lugartrabajo=:lugartrabajo, 
			@dpto=:departamento, 
			@usrid=:usrid,
			@is_drop = :is_drop,
			@drop_gye = :drop_gye,
			@drop_uio = :drop_uio
			");
			$result->bindParam(':nombre', $nombre, PDO::PARAM_STR);
			$result->bindParam(':clave', $clave, PDO::PARAM_STR);
			$result->bindParam(':acceso', $acc, PDO::PARAM_STR);
			$result->bindParam(':lugartrabajo', $lugartrabajo, PDO::PARAM_STR);
			$result->bindParam(':departamento', $dpto, PDO::PARAM_STR);
			$result->bindParam(':usrid', $codid, PDO::PARAM_STR);
			$result->bindParam(':is_drop', $drop, PDO::PARAM_STR);
			$result->bindParam(':drop_gye', $GYE, PDO::PARAM_STR);
			$result->bindParam(':drop_uio', $UIO, PDO::PARAM_STR);

			//Executes the query
			$result->execute();
			$usuario = $usuario1;
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";

			$_SESSION['base'] = $base;
			$_SESSION['usuario'] = $usuario;
			$_SESSION['acceso'] = $acceso;
			header("location: modificausuario1.php?id=".$codid."");

		} else {
			header("location: index.html");
		}

		?>
	</body>

</html>