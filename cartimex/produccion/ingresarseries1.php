<html>
<body>
<body oncontextmenu="return false">

<?php 
	session_start();		
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$bodega	=$_SESSION['bodega'];
			$EnsambleId= $_SESSION['EnsambleId'];
			
			if ($base=='CARTIMEX'){
					require '../../headcarti.php';  
			}
			else{
					require '../../headcompu.php';
			}
			
			$Id	=$_POST['Id'];
			$Scpu	=$_POST['Scpu'];
			$Smain	=$_POST['Smain'];
			$Shdd	=$_POST['Shdd'];
			$Smem1	=$_POST['Smem1'];
			$Smem2	=$_POST['Smem2'];
			$Soptico=$_POST['Soptico'];
			$Sfdd	=$_POST['Sfdd'];
			$Sotro1	=$_POST['Sotro1'];
			$Sotro2	=$_POST['Sotro2'];
			$_SESSION['usuario']= $usuario;
			$fin = count($Id);
			$i=0;
			require('../../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			//echo "Usuario viene ".$usuario;
					 
			for ($i = 0; $i <= $fin-1; $i++) 
				{	 
					$newId  	= $Id[$i];
					$newScpu	= $Scpu[$i];
					$newSmain	= $Smain[$i];
					$newShdd 	= $Shdd[$i];
					$newSmem1	= $Smem1[$i];
					$newSmem2	= $Smem2[$i];
					$newSoptico	= $Soptico[$i];
					$newSfdd	= $Sfdd[$i];
					$newSotro1  = $Sotro1[$i];
					$newSotro2  = $Sotro2[$i];
				
		    		//print  $newId.  $newScpu . $newSmain .$newShdd.$newSmem1.$newSmem2.$newSoptico. $newSfdd. $newSotro1. $newSotro2  ; 
							
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 

					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("update INV_PRODUCCION_ENSAMBLE_DT set Scpu=:Scpu, Smain=:Smain, Shdd=:Shdd,Smem1=:Smem1, Smem2=:Smem2, Soptico=:Soptico, 
											Sfdd=:Sfdd, Sotro1=:Sotro1, Sotro2=:Sotro2, UserId=:UserId
											where EnsambleId=:EnsambleId and Id=:Id " );
					$result->bindParam(':Scpu',$newScpu,PDO::PARAM_STR);
					$result->bindParam(':Smain',$newSmain,PDO::PARAM_STR);
					$result->bindParam(':Shdd',$newShdd,PDO::PARAM_STR);
					$result->bindParam(':Smem1',$newSmem1,PDO::PARAM_STR);
					$result->bindParam(':Smem2',$newSmem2,PDO::PARAM_STR);
					$result->bindParam(':Soptico',$newSoptico,PDO::PARAM_STR);
					$result->bindParam(':Sfdd',$newSfdd,PDO::PARAM_STR);
					$result->bindParam(':Sotro1',$newSotro1,PDO::PARAM_STR);
					$result->bindParam(':Sotro2',$newSotro2,PDO::PARAM_STR);
					$result->bindParam(':UserId',$usuario,PDO::PARAM_STR);
					$result->bindParam(':EnsambleId',$EnsambleId,PDO::PARAM_STR);
					$result->bindParam(':Id',$newId,PDO::PARAM_STR);
					$result->execute();
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
					
					$_SESSION['base']= $base;
					$_SESSION['usuario']=$usuario;
					$_SESSION['acceso']=$acceso;
					$_SESSION['bodega']= $bodega;
					
				}
				header("location: ensamblespendientes.php");
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>