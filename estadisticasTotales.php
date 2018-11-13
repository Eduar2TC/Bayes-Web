<?php
session_start();
if (isset($_SESSION['conectado']) && $_SESSION['conectado'] == true) {
} else {
      //echo "Esta pagina es solo para usuarios registrados.<br>";
      //echo "<br><a href='./index.php'>Login</a>";
  echo "Su sesión expiró";
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
    <title>Inicio Probabilidades Totales</title>
    <!-- Estilos propios -->
    <link rel="stylesheet" type="text/css" href = "css/mistilo.css">
    <!--Materializecss min-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css" />
    <!--Libreria para graficar--->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js" integrity="sha256-oSgtFCCmHWRPQ/JmR4OoZ3Xke1Pw4v50uh6pLcu+fIc=" crossorigin="anonymous"></script> -->
    <script src="js/canvasjs.min.js"></script>
    <!--Fonts-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
      <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Sweet Alert-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>

  <!--Menu de navegación principal-->
  <div class = "navbar-fixed">
    <nav>
      <div class="nav-wrapper blue lighten-2">
        <div class="container">
        <div class="nav-wrapper">
            
            <a href="#" class="brand-logo" ><i class="far fa-chart-bar"></i><span>Cálculo Bayes</span></a>
            <a href="#" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
            
        </div>

        <ul class="right hide-on-med-and-down">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="estadisticasTotales.php">Probabilidad Total</a></li>
            <li><a href="estadisticas.php">Estadisticas por Preparataria</a></li>
            <li id = "salir"><a href="scripts/logout.php" ><i class="median left material-icons red-text">directions_run</i>Salir</a></li>
            <li><a href="ayuda.php">Ayuda</a></li>
        </ul>
            
    </nav>
  </div>

  <!--Menu de navegacion Mobil -->
  <ul class="side-nav" id="mobile-menu">
    <li><a href="principal.php" class="blue-text">Inicio</a></li>
    <li><a href="estadisticasTotales.php" class="blue-text">Probabilidad Total</a></li>
    <li><a href="estadisticas.php" class="blue-text">Estadisticas por Preparataria</a></li>
    <li><a href="scripts/logout.php" class = "red-text"><i class="material-icons red-text">directions_run</i>Salir</a></li>
    <li><a href="ayuda.php" class="blue-text">Ayuda</a></li>
  </ul>

  <div class="container">

      <div class = "center ">
        <h4>
            Probabilidades Totales
        </h4> 
      </div>

      <div class="row">    
          <p class="flow-text">
            Porcentajes de alumnos incritos en las carreras ITI, ICC, LCC.
          </p>
            <!--Mostrar estadisticas totales -->
          <div class = "col s12 m12 lime lighten-5">
              
              <?php include_once("scripts/calculoProbabilidades.php");
              echo (new CalculaProbabilidad())->retornaPorcentagesPorCarrera();
              ?>
          </div>

            <div class = "col s12 m12" id="contenedorGraficaCarreras" style="height: 300px; width: 100%;"></div>

        </div>
        
        <div class="row">
            <p class="flow-text">Porcentajes de alumnos incritos por sector</p>
                
                <div class = "col s12 m12 lime lighten-5">
                    <?php include_once("scripts/calculoProbabilidades.php");
                    echo (new CalculaProbabilidad())->retornaPorcentagesPorSector();
                    ?>
                </div>
                <div class = "col s12 m12"id="contenedorGraficaSectores" style="height: 300px; width: 100%;"></div>
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

          $.ajax({
              type:'GET',
              url: 'scripts/calculoProbabilidades.php?obtenerJson=Si',
              success: function (data){
                  var valorPorcentajesTotales = [];
                  var valorPorcentajesPorCarrera = [];

                  var datos = JSON.parse(data);

                  var porcentajeEscuelasPublicas = parseFloat(datos[0].EscuelasPublicas);
                  var porcentajeEscuelasPrivadas = parseFloat(datos[0].EscuelasPrivadas);

                      valorPorcentajesTotales.push(
                            {label:"Escuelas públicas", y: porcentajeEscuelasPublicas},
                            {label: "Escuelas privadas", y: porcentajeEscuelasPrivadas}
                      );
                 
                  var porcentajeCarreraITI = parseFloat(datos[1].CarreraITI);
                  var porcentajeCarreraICC = parseFloat(datos[1].CarreraICC);
                  var porcentajeCarreraLCC = parseFloat(datos[1].CarreraLCC);

                      valorPorcentajesPorCarrera.push(
                                {label:"Carrera ITI", y: porcentajeCarreraITI},
                                {label:"Carrera ICC", y: porcentajeCarreraICC},
                                {label:"Carrera LCC", y: porcentajeCarreraLCC}
                      );


                    var graficaPorcentagesPorSectores = new CanvasJS.Chart("contenedorGraficaSectores", {
                          animationEnabled: true,
                          theme: "light2",
                          title: {
                            text: "Porcentajes totales"
                          },
                          axisX:{
                            //interval: 1
                          },
                          axisY: {
                            title: "Escuelas",
                            titleFontSize: 24
                          },
                          data: [{
                            type: "column",
                            //yValueFormatString: "#,### Units",
                            dataPoints: valorPorcentajesTotales
                          }]
                        });

                    var graficaPorcentagesPorCarreras = new CanvasJS.Chart("contenedorGraficaCarreras", {
                          animationEnabled: true,
                          theme: "light2",
                          title: {
                            text: "Porcentajes por carreras"
                          },
                          axisX:{
                            //interval: 1
                          },
                          axisY: {
                            title: "Porcentajes",
                            titleFontSize: 24
                          },
                          data: [{
                            type: "column",
                            //yValueFormatString: "#,### Units",
                            dataPoints: valorPorcentajesPorCarrera
                          }]
                        });

                      graficaPorcentagesPorSectores.render();
                      graficaPorcentagesPorCarreras.render();
              }
              
            });
  
         //Verificar Sesión
         function verificaSesion(){
            $.ajax(
                {
                  url:"estadisticasTotales.php",  //La url donde se invoca las variables y el tiempo de Sesión
                  method: "POST",
                  success:function(data){
                    if(data === 'Su sesión expiró'){
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