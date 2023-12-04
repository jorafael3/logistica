<HTML>

<meta name="viewport" content="width=370, initial-scale=0.95, minimum-scale=0.95"/>

<HEAD>
<TITLE>Números de Serie</TITLE>
</HEAD>
<BODY>
<body oncontextmenu="return false">


<% 
if session("ssacceso")="C" or session("ssacceso")="c" then 
response.redirect "ventas1.asp"
 end if  
%>

<%
if session("ssacceso")="V" or session("ssacceso")="v" then 
variable = session("sscodigo")
response.redirect "http://10.5.1.245/verifica/verifica0a.php?usr=" &variable

 end if  

if session("ssacceso")="P" or session("ssacceso")="p" then 
response.redirect "serial_p.asp"
 end if  

%>



<%fh = cstr(day(date()))+"/"+cstr(month(date()))+"/"+cstr(year(date()))%>

<center>Usuario: <%=session("sscodigo")%></center> 


<H1><CENTER>Ingreso de Serie por Factura<br></H1>
<br>

<center>
<table border=1 cellpadding=5 cellspacing=0 width=800>
<td>
<Form Action="ing_selec.asp" Method="post">
	<center>Ingreso / Egreso: <Input Type=Text Size = 10 Maxlenght=10 Name="Factura">
<input type="radio" name="group1" value="1" checked>Factura
<input type="radio" name="group1" value="2" >Transferencia

	<Input Type=Submit Value="Go"></Center>
</Form>

</td><td>

<Form Action="convertir.asp" Method="post">
	<center>Buscar numero factura preimpreso: <Input Type=Text Size = 15 Maxlenght=15 Name="numFactura">
	<Input Type=Submit Value="Go"></Center>
</Form>

</td></tr>
<td>

<%if session("ssacceso")="2" or session("ssacceso")="3" or session("ssacceso")="4"  then %>
   <Form Action="consulta.asp" Method="post">
	<Center>Serie <Input Type=Text Size = 40 Maxlenght=40 Name="Serie">
   	<Input Type=Submit Value="Go"></Center>
   </Form>
<% end if %>  

</td>
<td>
<Form Action="selec.asp" Method="post">
	<Center>Pendientes desde <Input Type=Text Size = 6 Maxlenght=6 Name="fh">
         hasta <Input Type=Text Size = 6 Maxlenght=6 Name="ff"><br>
		 <input type="radio" name="group1" value="1" checked>Factura
<input type="radio" name="group1" value="2" >Transferencia<br>

	<Input Type=Submit Value="Go"></Center>
<center>Ingresar solo números ejemplo 25/Dic/13 = 251213</Center>
</Form>

</td></tr>


<td>

<%if session("ssacceso")="2" or session("ssacceso")="3" or session("ssacceso")="4"  then %>

   <!---- Form Action="http://10.5.1.245/verifica/verifica0a.php?usr=session('sscodigo')" Method="GET" ---->
   <Form Action="http://10.5.1.245/verifica/verifica0a.php" Method="get">
        <input type= "hidden" name= "usr" value=<%=session("sscodigo")%>  >
	<!----- 
        <Center>Verificar factura: <Input Type=Text Size = 18 Maxlenght=18 Name="factura">
        ----->
   	<Center><Input Type=Submit Value="Verificar Facturas"></Center>
   </Form>
<% end if %>  

</td>
<td>
<!---- esta seccion vacia hasta proxima vez ----->

<%if session("ssacceso")="2" or session("ssacceso")="3" or session("ssacceso")="4"  then %>

   <!---- Form Action="http://10.5.1.245/verifica/verifica0a.php?usr=session('sscodigo')" Method="GET" ---->
   <Form Action="http://10.5.1.245/verificatr/verifica0a.php" Method="get">
        <input type= "hidden" name= "usr" value=<%=session("sscodigo")%>  >
	<!----- 
        <Center>Verificar factura: <Input Type=Text Size = 18 Maxlenght=18 Name="factura">
        ----->
   	<Center><Input Type=Submit Value="Verificar Transferencias"></Center>
   </Form>
<% end if %>  

</td></tr>



</table>
<Form Action="cambioclave.asp" Method="post">
	<Input Type=Submit Value="Cambiar contraseña"></Center>

</Form>

<br>


<center>
<table border=0 cellpadding=0 cellspacing=0 width=400>
<td>
<%if session("ssacceso")="3" or session("ssacceso")="9" then %>
<Form Action="rep_bloqueos.asp" Method="post">
	<Input Type=Submit Value="Listar facturas bloqueadas"></Center>
</Form>
<%Else%>-
<%End If%>
</td><td>
<%if session("ssacceso")="3"  then %>
<Form Action="consultarusuarios.asp" Method="post">
	<Input Type=Submit Value="Administrar usuarios"></Center>
</Form>
<%Else%>-
<%End If%>
</td></tr></table>


<%if session("ssacceso")="3" or session("ssacceso")="9" then %>
<H1><CENTER>Consulta de despachos por Factura<br></H1>

<center>
<table border=1 cellpadding=5 cellspacing=0 width=800>
<td>

<Form Action="despacho.asp" Method="post">
	<Center>Factura <Input Type=Text Size = 17 Maxlenght=17 Name="Factura">
	<Input Type=Submit Value="Consultar"></Center>
</Form>

</td><td>
<Form Action="listaclientes.asp" Method="post">
	<Center>Nombre <Input Type=Text Size = 30 Maxlenght=30 Name="nombre">
	<Input Type=Submit Value="Consultar"></Center>
</Form>

</td></tr><td>

<Form Action="facturasdiadesp.asp" Method="post">
	<Center>Pendientes por día <Input Type=Text Size = 6 Maxlenght=6 Name="fh">
	<Input Type=Submit Value="Go"></Center>
<center>Ingresar solo números ejemplo 25/Dic/13 = 251213</Center>
</Form>

</td><td>
<Form Action="desp_modi.asp" Method="post">
	<Center>Factura a modificar <Input Type=Text Size = 10 Maxlenght=10 Name="Factura">
	<Input Type=Submit Value="Modificar"></Center>
</Form>

</td></tr></table>

<%end if%><!---------- Del if aaceso 3 o 9 --------------->

	




</BODY>
</HTML>