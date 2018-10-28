<!DOCTYPE html>
<html lang="es">

<head>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de Cálculo de probabilidades </title>
    <link rel="icon" href="img/favicon-32x32.png">
    <!-- Estilos propios -->
    <link rel="stylesheet" href = "css/mistilo.css">
    <!--Materializecss min-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css" />
    <!--Fonts-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
      <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  
</head>

<body>

<!--Menu de navegación principal-->
<div class="row">
    <div class = "navbar-fixed">
    <nav>
        <div class="nav-wrapper blue lighten-2">
            <div class="container">

                <div class="nav-wrapper">
                    
                    <a  href="index.php" class="brand-logo"><i class="fas fa-brain"></i>Cálculo Bayes</a>
                    <a href="index.php" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
                    
                </div>

                <ul class="right hide-on-med-and-down">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#">Ayuda</a></li>
                </ul>
        </div>
            
    </nav>
    </div>
</div>

<!--Menu de navegacion Mobil -->
  <ul class="side-nav" id="mobile-menu">
    <li><a href="index.php" class="blue-text">Inicio</a></li>
    <li><a href="#" class="blue-text">Ayuda</a></li>
  </ul>


<!--Form de inicio de sesión-->

<div class="row">
    <div class="col s12 m4 offset-m4">
        <div class="card">

            <div class="card-action light-blue darken-3 white-text">
                    <h3 class = "center"><i class="fas fa-user-tie"></i>&nbspIniciar sesión</h3>
            </div>

            <form action="scripts/checarLogin.php" method = "POST">

                <div class="input-field">
                    <label for="usuario">Usuario</label>
                    <input type="text" name = "usuario" id = "idUsuario" class="validate">
                </div><br>
    
                <div class="form-field">
                    <label for="password">Constraseña</label>
                    <input type="password" name = "contrasenia" id = "idContrasenia" class="validate">
                </div><br>
                
                <div class="form-field">
                    <input type="checkbox" id = "recordarme">
                    <label for="recordarme">Recordar mi contraseña</label>    
                </div><br>

                <div class="form-field">
                    <button class="btn-large light-blue darken-3 waves-effect waves-orange" style = "width: 100%;" type="submit" name="action" >Login</button>
                </div>
                
            </form>

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
          
            $(".button-collapse").sideNav();
        });
    </script>
</body>

</html>