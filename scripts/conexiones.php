<?php

class Conexion
{

    public function conectarBD()
    {
        $conexion = mysqli_connect('localhost', 'root', 'ACM1PT', 'alumnos_aspirantes');
        if (!$conexion) {
            die("Error al conectar la bd : " . mysqli_connect_error());
        } else {
            //echo "Conexion establecida \n";
            return $conexion;
        }
    }

    public function conectarBD2()
    {
        $conexion = new mysqli('localhost', 'root', 'ACM1PT', 'alumnos_aspirantes');
    
        /* verificar la conexión */
        if (mysqli_connect_errno()) {
            printf("Falla de la conexión : %s\n", $mysqli->connect_error);
            exit();
        } else {
            return $conexion;
            /* liberar la serie de resultados */
            $resultado->free();
            /* cerrar la conexión */
            $mysqli->close();
        }
    }

    public function buscaCoincidenciasEnBd($matriculaBuscada)
    {
        $conexion = conectarBD2();
        $consulta = "SELECT matricula FROM aspirante WHERE matricula = '$matriculaBuscada'";
        $resultado = $conexion->query($consulta);
        /* array numérico y asociativo */
        $arregloNumerico_Asociativo = $resultado->fetch_array(MYSQLI_BOTH);
        printf("Resultados : %s (%s)\n", $arregloNumerico_Asociativo[0], $arregloNumerico_Asociativo['matricula']);

    }

}


?>