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

    echo "Tu sesion a terminado, <a href='index.php'>Necesitas iniciar de nuevo</a>";
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
    <title>Inicio de Predicciones</title>
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
                alert("kaka");
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
          <li><a href="estadisticas.php">Estadisticas por Preparatoria</a></li>
          <li><a href="scripts/logout.php" ><i class="median left material-icons red-text">directions_run</i>Salir</a></li>
          <li><a href="#">Ayuda</a></li>
      </ul>
          
  </nav>
</div>

<!--Menu de navegacion Mobil -->
  <ul class="side-nav" id="mobile-menu">
    <li><a href="principal.php" class="blue-text">Inicio</a></li>
    <li><a href="estadisticasTotales.php" class="blue-text">Probabilidad Total</a></li>
    <li><a href="estadisticas.php" class="blue-text">Estadisticas por Preparatoria</a></li>
    <li><a href="scripts/logout.php" class = "red-text"><i class="material-icons red-text">directions_run</i>Salir</a></li>
    <li><a href="#" class="blue-text">Ayuda</a></li>
  </ul>

<div class="container">

  <div class = "row s12">
    
    <p class = "flow-text">
        Pocesamiento de los datos almacenados en la Base de datos
    </p> 

  </div>
  
  <div class="row s2">
        <h4>Por el teorema de Bayes</h4>
        <p>Seleccionar las opciones de la lista de preparatorias para poder calcular la probabilidad de Bayes</p>
  </div>

    <div class="row s12 lime lighten-5">
        <form action="scripst/calculoProbalidades.php" method = "POST">  
            <!--Mostrar probabilidades de que un alumno se incriba en una x carrera viniendo de una y preparatoria-->
            <div class="input-field col s12 m6">
                <p> <spam class = "indigo-text text-darken-4">Probabilidad</spam> de que un alumnos se inscriba en la <b>Carrera "x"</b> sabiendo que viene de la <b>Preparatoria "y"</b></p>
            </div>
            
            <!--Seleccion de prepas-->
            <div class="input-field col s12 m3">
                <select id = "preparatorias">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <!--<option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    -->
                    <!--Archivo para calcular las probabilidades -->
                    <?php include("scripts/calculoProbabilidades.php");
                    echo (new CalculaProbabilidad())->retornaListaDePreparatorias(); ?>
                </select>
                <label>Preparatorias</label>
            </div>

            <!--Seleccion de Carreras-->
            <div class="input-field col s12 m3">
                <select id = "carreras">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="1">ITI</option>
                    <option value="2">ICC</option>
                    <option value="3">LCC</option>
                </select>
                <label>Carreras</label>
            </div>

          <!--  <div class="form-field">
                <button class="btn waves-effect waves-light cyan lighten-1" type="submit" name="action">Submit
                <i class="material-icons right">send</i></button>
            </div> -->
        
        </form>
    </div>
    <!--Aqui van los porcentages calculados en terminos de porcentages (include da problemas - mejor include_once)-->
    <div class="row s12 m12 lime lighten-5" id = "mostrarPorcentages">
                        <?php include_once("scripts/calculoProbabilidades.php");
                        echo (new CalculaProbabilidad())->retornaProbabilidadesDeBayes(); ?>
    </div>

    <!--Aqui van las graficas de los calculos-->
    <div class="row s12 m12 lime lighten-5">
    </div>



<!--fin del contenedor-->
</div>
  
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
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

          //inicializacion del seleccion de carreras (selector)
          $('select').material_select();

            //Inicio Consulta de la base de datos
            $(document).on('change','select', function(){
                    var id_preparatorias = $('#preparatorias').val();
                    var id_carreras = $('#carreras').val()
                    console.log(id_carreras);

                    $.ajax({
                        url: "scripts/calculoProbabilidades.php",
                        method: "POST",
                        data:{
                            id_preparatorias : id_preparatorias,
                            id_carreras : id_carreras},
                        success: function(data){
                            $('#mostrarPorcentages').html(data);
                        }
                    });
                });
              
              /*$('#preparatorias').change(function(){
                    var id_preparatorias = $(this).val();
                    var id_carreras = $('#carreras').val()

                    $.ajax({
                        url: "scripts/calculoProbabilidades.php",
                        method: "POST",
                        data:{
                            id_preparatorias : id_preparatorias,
                            id_carreras : id_carreras},
                        success: function(data){
                            $('#mostrarPorcentages').html(data);
                        }
                    });
                });

                $('#carreras').change(function(){
                    var id_carreras = $(this).val();
                    var id_preparatorias = $('#preparatorias').val()
                    console.log(id_preparatorias );

                    $.ajax({
                        url: "scripts/calculoProbabilidades.php",
                        method: "POST",
                        data:{
                              id_carreras : id_carreras,
                              id_preparatorias: id_preparatorias },
                        success: function(data){
                            $('#mostrarPorcentages').html(data);
                        }
                    });
                });
                //Fin Consulta de la base de datos
            */
        });
    </script>
</body>

</html>