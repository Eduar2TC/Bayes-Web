<?php
session_start();
if (isset($_SESSION['conectado']) && $_SESSION['conectado'] == true) {
} else {
      //echo "Esta pagina es solo para usuarios registrados.<br>";
      //echo "<br><a href='./index.php'>Login</a>";
  echo "Su sesion expiró";
  exit;
}
$now = time();
if ($now > $_SESSION['expira']) {
  session_destroy();
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
    <title>Inicio de Cálculo de probabilidades </title>
    <!-- Estilos propios -->
    <link rel="stylesheet" type="text/css" href = "css/mistilo.css">
    <!--Materializecss min-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css" />
    <!--Fonts-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
      <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Sweet Alert-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
          function myAjax() {
          $.ajax({
              type: 'POST',
              url: 'http://localhost:80/tratamientoDeLaInformacion/aspirantes/web/scripts/logout.php',
              data:{action:'call_this'},
              success:function(html) {
                alert("adios");
              }

          });
        }
    </script>
  
</head>

<body>

<!--Menu de navegación principal-->
</div>

<div class = "navbar-fixed">
  <nav>
    <div class="nav-wrapper blue lighten-2">
      <div class="container">
        
        <div class="nav-wrapper">
            
            <a href="#" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
            <a href="#" class="brand-logo" ><i class="far fa-chart-bar"></i><span>Cálculo Bayes</span></a>
            <!--            <a href="#" class="brand-logo" ><i class="fa fa-superscript left" aria-hidden="true"></i><i class="far fa-chart-bar"></i><span>Cálculo Bayes</span></a>-->
        </div>

        <ul class="right hide-on-med-and-down" >
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="estadisticasTotales.php">Probabilidad Total</a></li>
            <li><a href="estadisticas.php">Estadisticas por Preparatoria</a></li>
            <li id = "salir"><a href="scripts/logout.php" ><i class="median left material-icons red-text">directions_run</i>Salir</a></li>
            <li><a href="ayuda.php">Ayuda</a></li>
        </ul>
     </div>
     
  </nav>
</div>

<!--Menu de navegacion Mobil -->
  <ul class="side-nav" id="mobile-menu">
    <li><a href="principal.php" class="blue-text">Inicio</a></li>
    <li><a href="estadisticasTotales.php" class="blue-text">Probabilidad Total</a></li>
    <li><a href="estadisticas.php" class="blue-text">Estadisticas por Preparatoria</a></li>
    <li><a href="scripts/logout.php" class = "red-text"><i class="material-icons red-text">directions_run</i>Salir</a></li>
    <li><a href="ayuda.php" class="blue-text">Ayuda</a></li>
  </ul>

<div class="container">

  <div class = "center ">

      <h4>
          Calculo de probabilidad de Alumnos aspirantes
      </h4> 
      <img src="img/logo-600x600.png" alt="Bayes" class="responsive-img"/>
      
  </div>

  <div class="row">
      <p class="flow-text">
        <h4>Descripción</h4>
      </p>
      <div class = "lime lighten-5">
            <p class="flow-text">
            El proposito de este sitio es el cálculo de la probabilidad de que un alumno se inscriba en alguna de las carreras (ITI, ICC y LCC) dada la condición de que venga de alguna de las preparatorias del Estado de Puebla y pertenesca en el sector Público o Privado.
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br></p>
  </div>
  <div class="row">    
        <h4>
            ¿Que hacer?
        </h4>

      <div class="row lime lighten-5">
        <p class="flow-text">Para poder cancular la probabilidad de que un alumno venga de una determinado Sector (escuela publica o privada). Debe de ingresar a la pestaña "Calcular probalilidades" después se procedera a selecciónar una metricula al azar. Por consiguiente se le podrá clickear el boton "Calcular". Por consiguiente podra ver el porcentage de las probabilidades de elección de cada carrera.</p>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br></p>
      </div>
  </div>
</div>

</div>

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

          //recibe mensaje de bienvenida del administrador
          var mensaje = "Bienvenido " + "<?php echo $_GET['usuario'] ?> "+ "!";
          Materialize.toast(mensaje , 8000, 'blue rounded');
          
          //Verificar Sesión
          function verificaSesion(){
            $.ajax(
                {
                  url:"principal.php",  //La url donde se invoca las variables y el tiempo de Sesión
                  method: "POST",
                  success:function(data){
                    if(data === 'Su sesion expiró'){
                      //Alerta de termino de sesión
                      swal({
                        title: "Sesión terminada",
                        text: "Procede a iniciar de nuevo",
                        icon: "warning",
                      })
                      .then((value) => {
                        window.location.href="index.php";
                      });
                    }
                  }
                });
          }
          setInterval(function(){
            verificaSesion(); // Llama la funcion cada 3 segundos para verificar si la sesión há expirado
          }, 3000);
          
    });
    </script>
</body>

</html>