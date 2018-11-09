<?php
session_start();

if (isset($_SESSION['conectado']) && $_SESSION['conectado'] == true) {

} else {
  echo "Esta pagina es solo para usuarios registrados.<br>";
  echo "<br><a href='./index.php'>Login</a>";

  exit;
}

$now = time();

if ($now > $_SESSION['expira']) {
  session_destroy();

  echo "Su sesion a terminado, <a href='index.php'>Necesita Hacer Login</a>";
  exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/favicon-32x32.png">
    <title>Inicio Probabilidades Totales</title>
    <!-- Estilos propios -->
    <link rel="stylesheet" type="text/css" href = "css/mistilo.css">
    <!--Materializecss min-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css" />
    <!--Fonts-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
      <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script>
          function myAjax() {
          $.ajax({
              type: 'POST',
              url: 'http://localhost:80/tratamientoDeLaInformacion/aspirantes/web/scripts/logout.php',
              data:{action:'call_this'},
              success:function(html) {
                alert("salir");
              }

          });
        }
    </script>
  
</head>

<body>

<!--Menu de navegación principal-->
<div class = "navbar-fixed">
  <nav>
    <div class="nav-wrapper blue lighten-2">
      <div class="container">
      <div class="nav-wrapper">
          
          <a href="#" class="brand-logo"><i class="fa fa-superscript" aria-hidden="true"></i> Cálculo Bayes</a>
          <a href="#" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
          
      </div>

      <ul class="right hide-on-med-and-down">
          <li><a href="principal.php">Inicio</a></li>
          <li><a href="estadisticasTotales.php">Probabilidad Total</a></li>
          <li><a href="estadisticas.php">Estadisticas por Preparataria</a></li>
          <li><a href="scripts/logout.php" ><i class="median left material-icons red-text">directions_run</i>Salir</a></li>
          <li><a href="#">Ayuda</a></li>
      </ul>
          
  </nav>
</div>

<!--Menu de navegacion Mobil -->
  <ul class="side-nav" id="mobile-menu">
    <li><a href="principal.php" class="blue-text">Inicio</a></li>
    <li><a href="estadisticasTotales.php" class="blue-text">Probabilidad Total</a></li>
    <li><a href="estadisticas.php" class="blue-text">Estadisticas por Preparataria</a></li>
    <li><a href="scripts/logout.php" class = "red-text"><i class="material-icons red-text">directions_run</i>Salir</a></li>
    <li><a href="#" class="blue-text">Ayuda</a></li>
  </ul>

<img class="materialboxed" src="img/enConstruccion.jpg">


 <footer class="page-footer blue valign-wrapper center blue lighten-2">
   <div class="container">
     <p class="flow-text ">&copy; 2018 Todos los derechos reservados</p>
   </div>
 </footer>
  
 <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

    <script>
        $(document).ready(function () {
          
          //inicia el menu lateral
          $(".button-collapse").sideNav();
          //para el fondo ayuda
          $('.materialboxed').materialbox();
        });
    </script>
</body>

</html>