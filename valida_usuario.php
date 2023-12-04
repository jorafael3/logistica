<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			require('conexion_mssql.php');
			/*Para verificar si ya esta creado el codigo de usuario*/	
			
		    $codigo = (string)$_POST['codigo'];
		  
			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			$result2 = $pdo2->prepare("select * from seriesusr where usuario=:codigo");
			$result2->bindParam(':codigo',$codigo,PDO::PARAM_STR);
			$result2->execute();
			
			while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
				{	
?>	
				<div class="alert" id = "alert"  style="color:red;" width= "5%"><center><strong>Oh no!</strong> Ya existe ese usuario.</center></div>
				
<?php			
				}			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
		}
	else
		{
			header("location: index.html");
		}		