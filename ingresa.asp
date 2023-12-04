<body>
<body oncontextmenu="return false">

<p>
<%factura = Request.Form("factura")%>
<%if factura="" then %>
   <%factura = request.querystring("factura")%>
<%end if%>
<%factura = right("0000000000"+factura,10)%>

<Form Action="BUSCA1.asp" Method="post">
<right><Input Type="image" src="001_41.png" alt="Cambiar Contraseña" /></right></Form>

<%pendiente = 0%>
<%ensamble = 0%>
<%listoens = 0%>
<%revisado1 = 0%>
<%entregado1 = 0%>



<!--
       lee el registro 
  -->
<%sql = "select * from ven_facturas WITH (NOLOCK) where id='"+ factura +"' "%> 
<%set session("ven_facturas") = session("dsccnn").execute(sql)%> 

<%sql = "select * from emp_empleados WITH (NOLOCK) where id='"+session("ven_facturas")("vendedorid")+"' "%>
<%set session("emp_empleados") = session("dsccnn").execute(sql)%> 

<%sql = "select * from series WITH(NOLOCK) " %>
<%sql = sql + "where factura ='"+factura+"' " %> 
<%set session("series") = session("dsccnn").execute(sql)%> 

<%sql = "select * from facturaslistas WITH(NOLOCK) " %>
<%sql = sql + "where factura ='"+factura+"' " %> 
<%set session("facturaslistas") = session("dsccnn").execute(sql)%> 

<%sql = "select * from biess WITH(NOLOCK) " %>
<%sql = sql + "where factura ='"+session("ven_facturas")("fact_preimpresa")+"' " %> 
<%set session("biess") = session("dsccnn").execute(sql)%> 


<%if session("ven_facturas").eof then %>
Fin de Archivo - no hay más registros

<% else %>

<%espacio = "    "%>



<%if session("facturaslistas").eof then %>
<%revisado1 = 0%>
<%entregado1 = 0%>
<% else %>
<!------Establesco estado de revisado--------->
<%if (isnull(session("facturaslistas")("revisado"))) or (session("facturaslistas")("revisado")) = " " then %>
<%revisado1 = 0%>
<% else %> 
<%revisado1 = 1%>
<% end if %> 

<!------Establesco estado de entregado--------->
<%if (isnull(session("facturaslistas")("entregado"))) or (session("facturaslistas")("entregado"))= " " then %>
<%entregado1 = 0%>
<% else %> 
<%entregado1 = 1%>
<% end if %> 
<% end if %> 



<%if session("ven_facturas")("anulado")=true then%>
<h1><center>Factura ANULADA!!</center></h1>
<%end if%>

<%sql= "select top 1 a.id as aid, a.secuencia as secuencia, a.bloqueado as bloqueado, a.NotaBloqueo as nota , " %>  
 <%sql = sql + " b.facturaid as facturaid, b.bodegaid as bodegaid , " %>
 <%sql = sql + " c.nombre as nombre, c.id as cid, c.Código as Código , " %>
 <%sql = sql + " p.nombre as tipo " %>
 <%sql = sql + " from ven_facturas as a " %>
 <%sql = sql + " inner join ven_facturas_dt as b on b.facturaid = a.id " %>
 <%sql = sql + " inner join inv_bodegas as c   on b.bodegaid=c.id  " %>
 <%sql = sql + " inner join cli_clientes as cl on cl.id = a.ClienteID" %>
 <%sql = sql + " inner join sis_parametros as p on p.id = cl.tipoid" %>
 <%sql = sql + " where a.id='"+ factura +"'" %>
<%set session("bodega") = session("dsccnn").execute(sql)%> 

<%if session("bodega")("bloqueado")=true then%>
	<h2><center>Bloqueada!!</center></h2>
<% else %> 
	<%if session("bodega")("nota") <> "" then%>
		<h2><center>Desbloqueada!!</center></h2>
	<%end if%>	
<%end if%>
 
<h1>Bodega: <%=session("bodega")("código")%></h1>  

<strong>Numero</strong> <%=session("ven_facturas")("id")%>  
<strong>- ID </strong><%=session("ven_facturas")("clienteid")%> <br>
<strong>Cliente:</strong>  <%=session("ven_facturas")("Detalle") %> <strong> Tipo Cliente :</strong>  <%=session("bodega")("tipo") %><br>
<strong>Factura:  <%=session("ven_facturas")("Secuencia")%><br></strong>
   
<strong>Fecha y hora creación:</strong> <%=session("ven_facturas")("creadodate")%><br> 
 
 </td>



<!--EMPIEZAN LOS CAMBIOS--->



<!--Almaceno el ID del cliente en la variable IDDELCLIENTE--->
<%iddelcliente=session("ven_facturas")("clienteid")%>

<!--Busco en la base de clientes por el registroque tenga ese ID de cliente-->
<%sql = "select * from cli_clientes where id='"+ iddelcliente +"' "%> 

<!--escojo la base cli_clientes para trabajar--->
<%set session("cli_clientes") = session("dsccnn").execute(sql)%>
<!-Muestro los datos-->

<strong>Direccion:</strong>  <%=session("cli_clientes")("Dirección")%><br>
<strong>Ciudad:</strong>  <%=session("cli_clientes")("Ciudad")%>
<strong>Teléfono:</strong>  <%=session("cli_clientes")("Teléfono1")%><br>
<strong>Contacto:</strong>  <%=session("cli_clientes")("contacto")%><br>

<%if session("ven_facturas")("vendedorid")="0000000594" then%>
<strong>Vendedor:</strong>             <h1>                    <%=session("emp_empleados")("nombre")%></h1><br>
<%else%>
<strong>Vendedor:</strong>  <%=session("emp_empleados")("nombre")%>      <strong>E-Mail:</strong> <%=session("emp_empleados")("email")%><br>
<%end if%>
<strong>Factura Preimpresa:</strong>  <%=session("ven_facturas")("fact_preimpresa")%><br>

   <%if session("facturaslistas").eof then %>
   <strong>Preparada por: * * P E N D I E N T E * *</strong><br>  
   <%pendiente = 1%>
   <% else %> 

      <%if isnull(session("facturaslistas")("ensout"))=true and isnull(session("facturaslistas")("ensin"))=false then %>
      <center><table border=1 cellpadding=5 cellspacing=1 width=250>
      <td align=left  width=600><strong>PARTES EN     E N S A M B L E  </strong><br></td>
      </table>
      </center>
      <%ensamble = 1%>
      <% else %>
      <%ensamble = 0%>
      <%end if%>

   <!-----%end if%------>


<%Preppor = trim(session("facturaslistas")("preparadopor"))%>
<%prephor = trim(session("facturaslistas")("fechayhora"))%>
<strong>Preparada por:</strong> <%=trim(session("facturaslistas")("preparadopor"))%><tab>  
<%vhora=right(session("facturaslistas")("fechayhora"),10)%>
<%vfechat=left(session("facturaslistas")("fechayhora"),10)%>
<%vfechadm=left(vfechat,4)%>
<%vfecham=left(vfechadm,1)%>
<%vfechad=right(vfechadm,2)%>
<%vfechaa=right(vfechat,5)%>
 
  <!----- Hora y fecha de preparación:  <%="Día:"+trim(vfechad)+" Mes:"+trim(vfecham)+" Año:"+trim(vfechaa)+" Hora:"+vhora%><br----->
   <strong>Hora y fecha de preparación:</strong>  <%=session("facturaslistas")("fechayhora")%><br>




<!--- Esto debe desaparecer luego ********************** --->
<br>
<%if isnull(session("facturaslistas")("revisado"))=false then%>
<strong>Revisado por:</strong> <%=session("facturaslistas")("revisado")%><br>
<%end if%>
<%if isnull(session("facturaslistas")("entregado"))=false then%>
<strong>Entgregado por:</strong> <%=session("facturaslistas")("entregado")%><br>
<%end if%>
<br>



<%if isnull(session("facturaslistas")("fechayhora")) then %>
<%pendiente = 1%>
<% else %> 
<%pendiente = 0%>
<% end if %> 


<% end if %>     

<Br>
<strong>Observaciones   :</strong>  <%=session("ven_facturas")("nota")%><br>
<strong>Medio de entrega:</strong>  <%=session("ven_facturas")("transporte")%><br>
<Br>
    

<!--FIN DE LOS CAMBIOS--->

<%modemCNT=0%>

<table border=1 cellpadding=0 cellspacing=0 width=750>
<%m_numero = factura%> 
<%sql = "select a.id as id, a.productoid as productoid, a.cantidad as cantidad, b.código as código, b.nombre as nombre, " %>
<%sql = sql + "b.registroseries as registroseries   " %>
<%sql = sql + "from ven_facturas_dt as a WITH (NOLOCK), inv_productos as b WITH (NOLOCK) " %>
<%sql = sql + "where a.facturaid ='"+m_numero+"' " %> 
<%sql = sql + "and a.productoid=b.id "%>
<%set session("ven_facturas_dt") = session("dsccnn").execute(sql)%> 
<%total=0%>

<tr><td align=left  width=100>Ubicación.</td>
<td align=left  width=100>Cód.</td>
<td align=left  width=400>Articulo</td>
<td align=right width=45>Cant</td>
<td align=right width=50>Serie</td>
</tr>

   <%do while (not session("ven_facturas_dt").eof)%>
   <%m_secuencia=(session("ven_facturas_dt")("id")) %>
   
   <%if session("ven_facturas_dt")("código")="MCNT" then %>
   <%modemCNT=1%>
   <%End if%>

   <%sql = "select count(factura) as cserie from series WITH(NOLOCK) " %>
   <%sql = sql + "where factura ='"+m_numero+"' " %> 
   <%sql = sql + "and secuencia ='"+m_secuencia+"' " %> 
   <%sql = sql + "group by factura " %> 

   <%set session("series") = session("dsccnn").execute(sql)%> 
   <%canserie = 0
        if (not session("series").eof) then
        canserie=session("series")("cserie")
        end if%>
   
    
    <%pid=session("ven_facturas_dt")("productoid")%>
    <% sql="select * from bodega_sku where id='"+pid+"' "%>
    <%set session("bodega_sku") = session("dsccnn").execute(sql)%>
 
<%if session("bodega_sku").eof then %>
   <%per= " - " %>
   <%pro= " - " %>
   <%niv= " - " %>
<% else %>
   <%per= session("bodega_sku")("percha") %>
   <%pro= session("bodega_sku")("profundidad") %>
   <%niv= session("bodega_sku")("nivel") %>
<% end if %>

<%ubicacion= " "+per%>

    <%cantidad=cdbl(session("ven_facturas_dt")("cantidad"))%>
    <tr><td align=left  width=100><% =ubicacion%></td>
    <td align=left  width=100><% =session("ven_facturas_dt")("código")%></td>
    <td align=left  width=400><% =session("ven_facturas_dt")("nombre")%></td>
    <td align=right width=45 >
       <%if  session("ven_facturas_dt")("registroseries") then %>
       <%="<a href=ingserie.asp?documento="+m_numero+"&secuencia="+m_secuencia+"> "%>
       <%end if%>
    <%  =cantidad%>
    </td>
    <td align=right width=45 >
    <%  =canserie%>
    </td>
    </tr>
    <%session("ven_facturas_dt").movenext
     loop%>

</table>

</br>


<!----- Verifico si existe en ensamble para mostrar los numeros de maquinar -----> 
<%sql = "select * from ensamble where factura='"+ factura +"' "%> 
<%set session("ensamble") = session("dsccnn").execute(sql)%> 
<%if session("ensamble").eof then%>
<%Else %>
<%serieinicio=session("ensamble")("seriein")%>
<%seriefin=session("ensamble")("seriefin")%>
<h3>
Numeros de maquina Inicio: <%=serieinicio%>
 Fin:<%=seriefin%><br>
</h3><BR>
<%end if%>





<%if ensamble =1 then %>
   <%if session("ssacceso")="3" or session("ssacceso")="4" then %>
   <Form Action="ensambleout.asp">
   <table border=1 cellpadding=5 cellspacing=1 width=300>
   <td align=left  width=1000><h2>Terminar ensamble:</h2>
   <left>Ingreado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
   <left>Contraseña: <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
   <Input Type=Submit Value="Ensamblar">
   <Input Type="Hidden" name="factura" Value=<%=factura%>> 
   </Form>
   </td>
   <% end if %>  
<% end if %>  




<%if session("facturaslistas").eof or isnull(session("facturaslistas")("fechayhora"))  and ensamble = 0 then %>
    <!----- en este if que viene hago diferenciacion si es biess o no. Si es Biess lo mando a otro programa para que tome la serie del modem y grabe los datos------>
    <%if session("Biess").eof and session("ven_facturas")("vendedorid")="0000000594" then%>
	<Form Action="preparadaBiess.asp">
	<table border=1 cellpadding=5 cellspacing=1 width=300>
	<td align=left  width=1000><h2>Terminar pedido Biess:</h2>
	<left>Preparado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
	<left>Contraseña     : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
	<Input Type=Submit Value="Preparar">
	<Input Type="Hidden" name="factura" Value=<%=factura%>> 
	<Input Type="Hidden" name="modemCNT" Value=<%=modemCNT%>> 
        <Input Type="Hidden" name="biess" Value=<%=session("ven_facturas")("vendedorid")%>>
	</Form>
	</td>
    <%else%>
	<Form Action="preparadabiess.asp">
	<table border=1 cellpadding=5 cellspacing=1 width=300>
	<td align=left  width=1000><h2>Terminar pedido:</h2>
	<left>Preparado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
	<left>Contraseña     : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
	<Input Type=Submit Value="Preparar">
	<Input Type="Hidden" name="factura" Value=<%=factura%>> 
        <Input Type="Hidden" name="biess" Value=<%=session("ven_facturas")("vendedorid")%>>

	</Form>
	</td>
    <%end if%>

	<br>
	<%if session("ssacceso")="9" then %>

	<Form Action="impdoc1.asp" >
	<Input Type="Submit" name="accion" Value="Imprime normal">
	<Input Type="Hidden" name="factura" Value=<%=factura%>>
	</form>
	<br>
	<Form Action="impdoc2.asp" >
	<Input Type="Submit" name="accion" Value="Imprime urgente">
	<Input Type="Hidden" name="factura" Value=<%=factura%>>
	</form>
	<br>

<% end if %>     

<% else %>     


<!------ Para verificar si estan LISTO en ensamble y pasarlas a BODEGA --------->
<%if left(session("facturaslistas")("ensamble"),5) = "LISTO" then %>
<Form Action="pasabodega.asp">
<table border=1 cellpadding=5 cellspacing=1 width=300>
<td align=left  width=1000><h2>Pasar a bodega:</h2>
<left>Ingresado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
<left>Contraseña     : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
<Input Type=Submit Value="Preparar">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
</Form>
</td>
<% end if %>     
<% end if %>     



<%if ensamble =0 and session("facturaslistas").eof then %>
<Form Action="ensamble.asp">
<table border=1 cellpadding=5 cellspacing=1 width=300>
<td align=left  width=1000><h2>Pasar a ensamble:</h2>
<left>Pasado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
<left>Contraseña: <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
<Input Type=Submit Value="Ensamble">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
</Form>
</td>
<% end if %>     

<!------******************************************************************************************--------->
<!------******************************************************************************************--------->
<!------******************************************************************************************--------->

<!------Para revisar el pedido--------->
<%if not(session("facturaslistas").eof) then %>
<%if ensamble =0 and session("facturaslistas")("preparadopor")<> "" and revisado1= 0 and entregado1=0 then %>
<Form Action="revisa.asp">
<br>
<table border=1 cellpadding=5 cellspacing=1 width=300>
<td align=left  width=1000><h2>Revisar el pedido:</h2>
<left>Bultos           : <Input Type=Text Size = 10 Maxlenght=5 Name="bultosr"></left><br>
<left>Revisado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
<left>Contraseña   : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
<Input Type=Submit Value="Revisar">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
</Form>
</td>
<% end if %>  
<!------******************************************************************************************--------->
<!------******************************************************************************************--------->
<!------******************************************************************************************--------->


<%if session("biess").eof then %>
<Form Action="preparada.asp">
<table border=1 cellpadding=5 cellspacing=1 width=300>
<td align=left  width=1000><h2>Tomar serie :</h2>
<left>Preparado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
<left>Contraseña     : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
<Input Type=Submit Value="Preparar">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
</Form>
</td>
<% end if %>


<!------Para entregar el pedido--------->   
<%if ensamble =0 and revisado1=1 and entregado1=0   then %>
<Form Action="entrega.asp">
<br>
<table border=1 cellpadding=5 cellspacing=1 width=300>
<td align=left  width=1000><h2>Entregar el pedido:</h2>
<left>Entregado por: <Input Type=Text Size = 10 Maxlenght=25 Name="preppor"></left><br>
<left>Contraseña   : <Input Type=password Size = 10 Maxlenght=10 Name="contrasena"></left>
<Input Type=Submit Value="Entregar">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
</Form>
</td>
<% end if %>     


<% end if %>     


<%if pendiente =0 and ensamble =0 then%>
<Form Action="impseries.asp" >
<Input Type="Submit" name="accion" Value="Imprime documento para el cliente">
<Input Type="Hidden" name="factura" Value=<%=factura%>>
</form>
<br>
<Form Action="impseriesex.asp" >
<Input Type="Submit" name="accion" Value="Imprime documento para exportar">
<Input Type="Hidden" name="factura" Value=<%=factura%>>
</form>


<%end if%>

<%if session("ssacceso")="3" then %>
<br><br>
<br>

<Form Action="elimina.asp" >
<Input Type="Submit" name="accion" Value="Elimina">
<Input Type="Hidden" name="factura" Value=<%=factura%>> 
<Input Type="Hidden" name="secuencia" Value=<%=""%>> 
<br>
</form>
<%end if%>

 
<%end if%>
<% response.flush%>
<% response.end%>








</p>

</body>
