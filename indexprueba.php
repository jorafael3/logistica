<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Collapsible sidebar using Bootstrap 4</title>

    <!-- Bootstrap Para el sidebar -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom Para el estilo -->
    <link rel="stylesheet" href="style.css">

    
</head>

<body>
<?php
	
	//Trae los modulos 
	$usuarioid= '0000000260';
	$sql_serverName = "tcp:10.5.1.3,1433";
	$sql_database = "COMPUTRONSA";
	$sql_user = "jairo";
	$sql_pwd = "qwertys3gur0";
	$pdomodulo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
    $result = $pdomodulo->prepare("select Modulo= M.Nombre , ModuloId= M.IdModulo
                              from SIS_MODULOS_USUARIO_PERMISOS MUP
                              inner join SIS_MODULOS_SUBMENU  MS on MS.IdSubmenu=MUP.IdSubmenu
                              INNER JOIN SIS_MODULOS_MENU MM ON MM.IDMENU= MS.IDMENU
                              INNER JOIN SIS_MODULOS M ON M.IdModulo= MM.IDMODULO
                              inner join SERIESUSR u  on u.usrid=MUP.iduser
                              INNER JOIN SIS_SUCURSALES S ON U.lugartrabajo= S.ID
                              where u.usrid =:usuarioid
                              group by M.Nombre , M.IdModulo" );
    $result->bindParam(':usuarioid',$usuarioid,PDO::PARAM_STR);
    $result->execute();
	$Modulos = array();
    $x=0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC))
      {
        $Modulos[$x][Modulo]=$row['Modulo'];
        $Modulos[$x][ModuloId]=$row['ModuloId'];
        $x++;
      }
	  $count = count($Modulos); 
	  
	//Trae los menus por modulos
	$pdomenu = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
    $menu = $pdomenu->prepare("select MM.IdMenu, Menu= MM.nombre, ModuloPadre=M.Idmodulo
                                from SIS_MODULOS_USUARIO_PERMISOS MUP
                                inner join SIS_MODULOS_SUBMENU MS on MS.IdSubmenu=MUP.IdSubmenu
                                INNER JOIN SIS_MODULOS_MENU MM ON MM.IDMENU= MS.IDMENU
                                INNER JOIN SIS_MODULOS M ON M.IdModulo= MM.IDMODULO
                                inner join SERIESUSR u  on u.usrid=MUP.iduser
                                INNER JOIN SIS_SUCURSALES S ON U.lugartrabajo= S.ID
                                where u.usrid =:usuarioid  
                                GROUP BY MM.IdMenu, MM.nombre,M.Idmodulo
                                order by ModuloPadre" );
    $menu->bindParam(':usuarioid',$usuarioid,PDO::PARAM_STR);
    $menu->execute();
	$Menu = array();
    $x=0;
    while ($rowmenu = $menu->fetch(PDO::FETCH_ASSOC))
		{
          $Menu[$x][IdMenu]=$rowmenu['IdMenu'];
          $Menu[$x][Menu]=$rowmenu['Menu'];
          $Menu[$x][ModuloPadre]=$rowmenu['ModuloPadre'];
          $x++;
        }	
	 $cmenu = count($Menu);

	$pdosubmenu = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
    $submenu = $pdosubmenu->prepare("select PadreId= MS.IdMenu, SubMenu= MS.nombre
                                from SIS_MODULOS_USUARIO_PERMISOS MUP
                                inner join SIS_MODULOS_SUBMENU MS on MS.IdSubmenu=MUP.IdSubmenu
                                INNER JOIN SIS_MODULOS_MENU MM ON MM.IDMENU= MS.IDMENU
                                INNER JOIN SIS_MODULOS M ON M.IdModulo= MM.IDMODULO
                                inner join SERIESUSR u  on u.usrid=MUP.iduser
                                INNER JOIN SIS_SUCURSALES S ON U.lugartrabajo= S.ID
                                where u.usrid =:usuarioid  " );
    $submenu->bindParam(':usuarioid',$usuarioid,PDO::PARAM_STR);
    $submenu->execute();
	$SubMenu = array();
    $x=0;
        while ($rowsub = $submenu->fetch(PDO::FETCH_ASSOC))
          {
            $SubMenu[$x][PadreId]=$rowsub['PadreId'];
            $SubMenu[$x][SubMenu]=$rowsub['SubMenu'];
            $x++;
          } 
	$csubmenu = count($SubMenu);	  
	//echo "Aqui sale la cantidad de modulos ".$csubmenu; 
	//echo '<pre>'; print_r($Modulos); echo '</pre>';
?>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Bootstrap Sidebar</h3>
            </div>
            <ul class="list-unstyled components">
                <p>Dummy Heading</p>
	<?php 
		
			foreach( $Modulos as $Mod ) 
			{
	?>	    
			<li >
				<a href="#<?php echo $Mod['ModuloId']?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <?php echo $Mod['Modulo']?></a>
                    <ul class="collapse list-unstyled" id="<?php echo $Mod['ModuloId']?>">
            <?php  
					
					foreach($Menu as $Men)
					{
						if ($Mod['ModuloId']==$Men['ModuloPadre'])
						{
			?>            
						<li>
                            <a href="#<?php echo $Men['Menu']?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?php echo $Men['Menu']?></a>
							<ul class="collapse list-unstyled" id="<?php echo $Men['Menu']?>">
			<?php			
							foreach($SubMenu as $Sub)
							{
								if ($Men['IdMenu']==$Sub['PadreId'])
								{
			?>					<li>
									<a href="#"><?php echo $Sub['SubMenu']?></a>
								</li>	 
			<?php				 
								}
			?>					 
			<?php				 
							}
			?>				</ul>
                        </li>
			<?php
						}
					 
					}
			?>		
                    </ul>
            </li>
	<?php
			 
			}
	?>		
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Page 1</a>
                        </li>
                        <li>
                            <a href="#">Page 2</a>
                        </li>
                        <li>
                            <a href="#">Page 3</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Portfolio</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
                </li>
                <li>
                    <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Collapsible Sidebar Using Bootstrap 4</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h3>Lorem Ipsum Dolor</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>