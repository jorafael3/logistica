<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$ubi = TRIM($_POST["ubi"]);
			$id = TRIM($_POST["id"]);
			$secu = TRIM($_POST["secu"]);
			//echo "Usuario:".$usuario. $base. $codigo. $nombre.$clave . $acceso .$lugartrabajo.$usrid.$id; 
			
			echo "Usuario:---".$usuario."Datos a grabar ". $base. $ubi. $id. $secu;
			require('conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);

			//Select Query
			$result = $pdo->prepare("LOG_MODIFICAR_SKU @ID=:id, @ubi=:ubi , @secuencia=:secu" );
			$result->bindParam(':id',$id,PDO::PARAM_STR);	
			$result->bindParam(':ubi',$ubi,PDO::PARAM_STR);			
			$result->bindParam(':secu',$secu,PDO::PARAM_INT);
			
			//Executes the query
			$result->execute();
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			//echo "Usuario:---".$usuario."Datos a grabar ". $base. $ubi. $prodid; 
			header("location: consultasku.php?id=$id");
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>