<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>

<head>
  <title> SGL </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<style type="text/css"></style>
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<head>

  <div id="Cabecera">
    <div class="container text-center" id="logo">
      <h4 href="#">
        <img src="http://10.5.2.62/logistica/assets/img/logocomputronmovil.jpg" class="img-fluid" alt="Logo Computron" style="max-width: 250px; height: auto;">
        <br>
      </h4>
      <span class="fw-bold fs-3 mt-2 text-muted">SISTEMA DE GESTIÓN LOGÍSTICA SGL</span>
      <br>
      <strong>Usuario:</strong> <span class="text-muted fw-bold"><?php echo $usuario ?> </span> <br>
      <a href="http://10.5.2.62/logistica/logout.php" class="btn btn-outline-danger mt-3">Cerrar sesión</a>
      <hr class="my-4">
    </div>

    <!-- <div align="center" id="logo">
      <a><img src="http://10.5.2.62/logistica/assets/img/logocomputronmovil.jpg" , style="max-width:100%;width:auto;height:auto;" srcset="http://10.5.2.62/logistica/assets/img/logocomputron2.jpg " , width="250" height="50" /> <br>
        <font size="5">SISTEMA DE GESTIÓN LOGÍSTICA SGL</font>
      </a><br>
      <strong> Usuario:</strong> <?php echo $usuario ?> <br>
      <a href="http://10.5.2.62/logistica/logout.php"> Cerrar sesion</a>
      <hr>
    </div> -->
  </div>
</head>