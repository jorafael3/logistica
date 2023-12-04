<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<link href="css/servitech.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablasservitech.css">
<body>
<?php
		    session_start();
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$_SESSION['usuario'] = $usuario;
					$usuario = $_SESSION['usuario'];

echo "<div id= head>";
	include("../headservitech.html");
echo "</div>";
?>
<body>
<div class= "formulario" id="contenedor">
	<div id="izquierda" >
	<form class="formorden" id = "formorden">
			<div id= "vinetas"> RMA- Orden de Servicio </div>
			<div id= "vinetas2" width="100%">
				<div id = "filaserviizq" width="50%" border= 1px > <a> Tipo: ORD-SER </a> </div>
				<div id = "filaservider" width="50%"> <a> Fecha de Ingreso: <input type="date" name="fingreso" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></a></div>
			</div>
			<div id= "vinetas">Datos del Cliente </div>
			<div id= "contenedor2">
				<div class="table-responsive" id= "vinetas3" width="100%">
						<table  border=0 width=100%>
							<tr><th colspan="6"> </th></tr>
							<tr>
								<td id= "label" colspan=1 > Ruc/Cédula:</td> <td width=25 colspan=2><input name="cedula" type="text" size= "25%" id="cedula" ></td>
								<td id= "label" colspan=1 > Nombre: </td> <td width=25 colspan=2 ><input name="nombre" type="text" id="nombre" size= "45%" >
								<input name="clienteid" type="hidden" id="clienteid" size= "45%" >
							  <input name="IngresadoPor" type="hidden" id="IngresadoPor" size= "45%" value=" <?php echo $usuario ?>" >	</td>
							</tr>
							<tr>
								<td id= "label" colspan=1 > Telefono:</td> <td width=25 colspan=2><input name="telefono" type="text" size= "25%" id="telefono" value= "" ></td>
								<td id= "label" colspan=1 > Dirección: </td> <td width=25 colspan=2 ><input name="direccion" type="text" id="direccion" size= "45%" value= "" ></td>
							</tr>
							<tr>
								<td id= "label" colspan=1 >Celular:</td> <td width=25 colspan=2> <input name="celular" type="text" size= "25%" id="celular" value= "" ></td>
								<td id= "label" colspan=1 >Mail:</td> <td width=25 colspan=2><input name="mail" type="text" id="mail"  size= "45%" value= "" ></td>
							</tr>
					</table>
				</div>
			</div>
			<div id= "vinetas">Detalle de Producto </div>
				<div id= "contenedor2">
				<div class="table-responsive" id= "vinetas3" width="100%">
					<table  border=0 width=100%>
						<tr><th colspan="6"> </th></tr>
						<tr>
							<td id= "label" colspan=1 > Producto: </td> <td width=25 colspan=1 ><input name="producto" type="text" id="producto" size= "55%" value= "" ></td>
							<td id= "label" colspan=1 > Modelo:</td> <td width=25 colspan=2><input name="modelo" type="text" size= "25%" id="modelo" value= "" ></td>
						</tr>
						<tr>
							<td id= "label" colspan=1 > Marca:</td><td width=25 colspan=1>
								<select id="marca">
								</select> </td>
							<td id= "label" colspan=1 > Serie: </td> <td width=25 colspan=3 ><input name="serie" type="text" id="serie" size= "25%" value= "" ></td>
						</tr>
						<tr>
							<td id= "label" colspan=1 >Problema:</td> <td width=25 colspan=5>
								<select id="problema">
										<option value='0'> (Ninguno) </option>
										<option value='1'> No Enciende </option>
										<option value='2'> Golpeado </option>
										<option value='3'> Mantenimiento </option>
								</select> </td>
						</tr>
						<tr>
							<td id= "label" colspan=1 >Accesorios:</td> <td width=25 colspan=5> <textarea Size = 20 rows= 2 cols=153 Name='accesorios' id='accesorios'></textarea></td>
						</tr>
						<tr>
							<td id= "label" colspan=1 >Observaciones:</td> <td width=25 colspan=5> <textarea Size = 20 rows= 4 cols=153 Name='observaciones' id='observaciones'></textarea></td>
						</tr>
					</table>
				</div>
			</div>
			<div id= "vinetas">Asignar Tecnico </div>
			<div id= "contenedor2">
				<div class="table-responsive" id= "vinetas3" width="100%">
					<table  border=0 width=100%>
						<tr><th colspan="6"> </th></tr>
						<tr>
							<td id= "label" colspan=1 > Tecnico:</td><td width=25 colspan=2>
								<select id="tecnico">
								</select> </td>
						</tr>
					</table>
				</div>
			</div>
			<br>
	</form>
	<div id = "filaservider"><input type="button" value="Guardar Orden" class="btn btn-sm btn-primary" name="btn_save" onclick="SaveService();"></div>
	<div class="resultados" id= "resultados"></div>
	</div>
</div>
<?php	$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc;
			}
			else
			{
				header("location: index.html");
			}
?>
</body>
</html>
<script type="text/javascript">
//poner foco en el campo cedula al cargar pagina
$(document).ready(function(){
	$("#cedula").focus();
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
				url: 'codigos_php.php',
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
				url: 'codigos_php.php',
				type:'POST',
				success: function(tecnicos)
					{
						let jsontecnicos = JSON.parse(tecnicos);
						//console.log(jsontecnicos);
						var i;
						for(i = 0; i < jsontecnicos.length; i += 1)
							{
								$('#tecnico').append('<option value=' + jsontecnicos[i]["ID"] + '>' + jsontecnicos[i]["nombre"] + '</option>');
							}

					}
			})
})

$(document.getElementsByName('cedula')).keypress(function(e) {
	if(e.which == 13  ) {
		buscar_datos();
	}
});

//Buscar cliente en la base y llenar inputs con los datos
function buscar_datos()
	{
		cedula= $("#cedula").val();
		var parametros =
		{
				"buscar":"1",
				"cedula":cedula
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'codigos_php.php',
				type:'POST',
				error:function()
				{alert("Error");},
				success: function(valores)
				{
					let jsonParse = JSON.parse(valores);
					//console.log(jsonParse);
					if ((jsonParse.existe)=="1")
					{
						$("#nombre").val(jsonParse.nombre);
						$("#telefono").val(jsonParse.tel);
						$("#direccion").val(jsonParse.direc);
						$("#celular").val(jsonParse.celu);
						$("#mail").val(jsonParse.email);
						$("#clienteid").val(jsonParse.clienteid);
						$("#producto").focus();
					}
					else {
						alert("Cliente no existe");
					}
				}
		})
	}
//Funcion para grabar la orden de Servicio
	function SaveService(){
		var parametros =
		{
				"guardarser":"1",
				"clienteid":$("#clienteid").val(),
				"producto": $("#producto").val(),
				"modelo": $("#modelo").val(),
				"marca": $("#marca").val(),
				"serie": $("#serie").val(),
				"problema":$("#problema").val(),
				"accesorios":$("#accesorios").val(),
				"observaciones":$("#observaciones").val(),
				"tecnico":$("#tecnico").val(),
				"IngresadoPor":$("#IngresadoPor").val()
		};
		$.ajax(
			{
				data:parametros,
				datatype:'json',
				url: 'codigos_php.php',
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
