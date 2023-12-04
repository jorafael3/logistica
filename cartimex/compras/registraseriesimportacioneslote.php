<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("serie[]").focus();
}
</script>



<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
//ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['loggedin']))
	{
		
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];		
			if ($base=='CARTIMEX'){require '../../headcarti.php';}else{require '../../headcompu.php';}
			if ($_GET['IDLiq']=='' ){$IDLiq= $_POST['IDLiq'];}else{$IDLiq= $_GET['IDLiq'];}
			$manual = $_POST['manual']; 	 	
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
		<div id = "izq" > </div>
		<div id = "centro" > <a class="titulo"> <center>   IMPORTACIONES PENDIENTES  </center> </a></div>
		<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>		 
	</div> 
<hr>
	<div id="cuerpo2"  >
		<div align= "left" >
			<table  align= "center" border="2" cellpadding="5" cellspacing="1">
				<tr>
					<form method="POST" action="registraseriesimportacioneslote.php" enctype="multipart/form-data" id="myform">
						<tr>
							<td id= "label"> Subir Series: </td>
							<td id= "box" > <input type="file" name="uploadedFile" /> </td>
							<span class="help-block"><?php echo $username_err; ?></span>
						</tr>
						<tr>
							<td id= "box" > <input type="submit" name="uploadBtn" value="Subir archivo" /> </td>
							<input type="hidden" name="productoid" value="<?php echo $productoid ?>" />
							<input type="hidden" name="IDLiq" value="<?php echo $IDLiq ?>" />
							<input type="hidden" name="manual" value= "0"  />
						</tr>	
					</form>	
				</tr>
			</table>	
			<br>
		</div>
	</div>
	
<div id= "cuerpo2" align= "center" >
	<div class=\"table-responsive-xs\">
		<form name = " " action="registraseriesimportacioneslote1.php" method="POST" width="100%">
			<table  align= "center"  >
					<tr>
						<td id= "label">  
						<input id="submit" value="GRABAR SERIES " type="submit">
						<input type="hidden" name="IDLiq" value="<?php echo $IDLiq ?>" />
						<input type="hidden" name="manual" value= "1"  /></td>
					</tr>	
			</table>
			<br>	
			<table align ="center" width="50%" >
				 
				<tr> 
					<th id= "fila4">#  </th>
					<th id= "fila4">Codigo </th>
					<th id= "fila4">Serie </th>
				</tr>
 <?php
	
if ($manual==0)
	{
 		/********* Para subir archivos **********/
		$message='';
		if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
			{
				// get details of the uploaded file
				$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
				$fileName = $_FILES['uploadedFile']['name'];
				$fileSize = $_FILES['uploadedFile']['size'];
				$fileType = $_FILES['uploadedFile']['type'];
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));
				// sanitize file-name
				$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
				// check if file has one of the following extensions
				$allowedfileExtensions = array('pdf','xlsx','xls');
				if (in_array($fileExtension, $allowedfileExtensions))
					{
						// directory in which the uploaded file will be moved
						$uploadFileDir = 'temp/';
						$dest_path = $uploadFileDir . $newFileName;
						$rutaimg=$newFileName; 
						move_uploaded_file($_FILES['uploadedFile']['tmp_name'],"/var/www/html/logistica/cartimex/temp/" . $_FILES['uploadedFile']['name']);
						if(move_uploaded_file($fileTmpPath, $dest_path)) 
							{
								$message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
								//echo $message; 
							}
						else 
							{
								$message ='Proceso Exitoso';
								/***Leer archivo excel subido*****/
								include "SimpleXLSX.php";
							 
								if ( $xlsx = SimpleXLSX::parse('/var/www/html/logistica/cartimex/temp/'.$fileName) ) 
									{	 										
										foreach ($xlsx->rows() as $fila) 
											{ $countfila++;}
									//	echo "Filas". $countfila; 
									 
										
										//if ($countfila==$Cantidad)//comparo las cantidad de productos con las filas del archivo de excel
											//{											
												$n=1;  
												$i = 0;
												foreach ($xlsx->rows() as $fila) 
													{
														if ($i == 0) 
															{
																echo "<tr><td>".$n ."</td><td  align=left>" . $fila[0]. "</td><td id= box> <input name=serie[] type=text required id=serie[] value=" . $fila[1] . "></td><td id= box> <input name=codprod[] type=hidden id=codprod[] value=" . $fila[0] . "></td></tr>";
															} 
														else 
															{
																echo "<tr><td>".$n ."</td><td align=left >" . $fila[0]. "</td><td id= box> <input name=serie[] type=text required id=serie[] value=" . $fila[1] . "><td id= box> <input name=codprod[] type=hidden id=codprod[] value=" . $fila[0] . "></td></td></tr>";
															}      
														$i++; 
														$n++;
													}
									/*		}
										else
											{
												echo "<strong> <h3>NO coinciden la cantidad ingresada con las series del archivo </h3></strong>";
												unlink('/var/www/html/logistica/cartimex/temp/'.$fileName);
											}	*/
									} 
								else 
									{
										echo SimpleXLSX::parseError();
									}
								/***FIN Leer archivo excel subido*****/	
							}
					}
				else
					{
						$message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
					}
			}
		else
			{
				$message = 'There is some error in the file upload. Please check the following error.<br>';
				$message .= 'Error:' . $_FILES['uploadedFile']['error'];
			}
			
		/********* Fin Para subir archivos **********/	
	}
else
	{
		$n=1; 
		$y=0;
		while ( $y <= $Cantidad-1 ) 
			{
				echo "<tr><td>".$n ."</td> <td  align=left >" . $Codigo . "</td><td id= box> <input name=serie[] required type=text id=serie[] value=' ' > </td> </tr>";
			$y=$y+1;
			$n=$n+1;
			}		
	}
?>
				
			</table>
		</form>
	</div>  
</div> 
 <script>
$(document).on('submit', '#myform', function(e){
    e.preventDefault();
    var formData = $(this).serialize();
    alert(formData);
});
</script>
<?php		
			
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc;
			$_SESSION['fileName']=$fileName;
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	