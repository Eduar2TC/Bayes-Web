<?php
include('conexiones.php');
class CalculaProbabilidad
{
    private $conexion;

    private $id_carrera;      //Temporal para guardar valores $_POST[] Pasados dinamicamente
    private $id_preparatoria; //Temporal para guardar valores $_POST[] Pasados dinamicamente

    public function __construct()
    {
        $nuevaConexion = new Conexion();
        $this->conexion = $nuevaConexion->conectarBD();
        $this->conexion->set_charset("utf8");

        if (isset($_POST['id_preparatorias']) != '' && isset($_POST['id_carreras']) != '') {
            $this->id_carrera = $_POST['id_carreras'];
            $this->id_preparatoria = $_POST['id_preparatorias'];

        } else {
            $tempIdCarrera = "";
            $tempIdPreparatoria = "";
        }

          //Obtener la peticion de las funciones de conversion hacia archivos json para graficar las tablas de la pagina "Estadisticas totales"
        if (isset($_GET['obtenerJson']) && $_GET['obtenerJson'] != '') {

            if ($_GET['obtenerJson'] === 'Si') {
                echo $this->retornaResultadosPorcentagesTotalesJson();
            }
        }
        //Resultados principales en "estadisticas.php"
        if (isset($_POST['calculaProbabilidadesBayes']) && $_POST['calculaProbabilidadesBayes'] != '') {
            if ($_POST['calculaProbabilidadesBayes'] == 'Si') {
                echo $this->retornaProbabilidadesDeBayesEnFormatoJSON();
            }
        }

    }

    public function calculaProbabilidadTotal($opcionCarrera, $sector)  //Ejemplo : ITI, PUBLICO
    {

        if ($opcionCarrera == 1 && $sector === "PUBLICO") { //ITI

            return ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(1) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(1));

        }
        if ($opcionCarrera == 2 && $sector === "PUBLICO") { //ICC

            return ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(2) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(2));

        }
        if ($opcionCarrera == 3 && $sector === "PUBLICO") { //LCC

            return ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(3) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(3));

        } else {
            if ($opcionCarrera == 1 && $sector === "PRIVADO") { // ITI

                return ($this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(1) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(1));

            } else if ($opcionCarrera == 2 && $sector === "PRIVADO") { //ICC

                return ($this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(2) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(2));

            } else if ($opcionCarrera == 3 && $sector === "PRIVADO") { //LCC

                return ($this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera($opcionCarrera)) / ($this->calculaPorcentageDePrepasPublicas() * $this->consultaDePorcentageDeCarrera(3) + $this->calculaPorcentageDePrepasPrivadas() * $this->consultaDePorcentageDeCarrera(3));
            }

        }

    }

    public function calculaPorcentageDePrepasPublicas()
    {
        $consulta =
            "SELECT
            ( SELECT COUNT(*) AS cantidad from aspirante
              INNER JOIN preparatoria   /*Total Alumnos incritos por prepa*/
              ON aspirante.fk_idPrep = preparatoria.idPreparatoria
              WHERE preparatoria.tipoDePrepa = 'PUBLICO' ) AS cantidadDeAlumnosEscuelasPublicas,
            ( SELECT count(*) AS totalAlumnos from aspirante) AS totalAlumnos";

        $resultadoConsulta = $this->conexion->query($consulta);

        if ($resultadoConsulta->num_rows > 0) {
            // array numérico y asociativo
            $arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH);
            $cantidad = $arregloNumerico_Asociativo['cantidadDeAlumnosEscuelasPublicas'];
            $totalAlumnos = $arregloNumerico_Asociativo['totalAlumnos'];

            return (float)($cantidad / $totalAlumnos);
        } else {
            return 0;
        }

    }

    public function calculaPorcentageDePrepasPrivadas()
    {
        $consulta =
            "SELECT
            ( SELECT COUNT(*) AS cantidad from aspirante
              INNER JOIN preparatoria   /*Total Alumnos incritos por prepa*/
              ON aspirante.fk_idPrep = preparatoria.idPreparatoria
              WHERE preparatoria.tipoDePrepa = 'PRIVADO') AS cantidadDeAlumnosEscuelasPrivadas,
            ( SELECT count(*) AS totalAlumnos from aspirante) AS totalAlumnos";

        $resultadoConsulta = $this->conexion->query($consulta);

        if ($resultadoConsulta->num_rows > 0) {
            // array numérico y asociativo
            $arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH);
            $cantidad = $arregloNumerico_Asociativo['cantidadDeAlumnosEscuelasPrivadas'];
            $totalAlumnos = $arregloNumerico_Asociativo['totalAlumnos'];
            return (float )($cantidad / $totalAlumnos);
        } else {
            return 0;
        }
    }

    public function convierteDatosSectorAjson()
    {
        //porcentajes por sector
        $porcentagePrepasPublicas = $this->calculaPorcentageDePrepasPublicas();
        $porcentagePrepasPrivadas = $this->calculaPorcentageDePrepasPrivadas();

        //Creación de la estructura json
        $jsonArregloPorcentagesPorSector = array(
            'EscuelasPublicas' => number_format($porcentagePrepasPublicas, 4),
            'EscuelasPrivadas' => number_format($porcentagePrepasPrivadas, 4)
        );

        return json_encode($jsonArregloPorcentagesPorSector, JSON_UNESCAPED_UNICODE);
    }

    public function convierteDatosPorcentagesDeCarrerasAjson()
    {
        //Creación de la estructura json
        $porcentajeITI = number_format($this->consultaDePorcentageDeCarrera(1), 4);
        $porcentajeICC = number_format($this->consultaDePorcentageDeCarrera(2), 4);
        $porcentajeLCC = number_format($this->consultaDePorcentageDeCarrera(3), 4);


        $jsonArregloPorCarrera = array(
            'CarreraITI' => $porcentajeITI,
            'CarreraICC' => $porcentajeICC,
            'CarreraLCC' => $porcentajeLCC
        );
        return json_encode($jsonArregloPorCarrera, JSON_UNESCAPED_UNICODE);
    }
    //Utilizado en "estadisticasTotales.php"
    public function retornaResultadosPorcentagesTotalesJson()
    {
        $arregloJson[] = json_decode($this->convierteDatosSectorAjson());
        $arregloJson[] = json_decode($this->convierteDatosPorcentagesDeCarrerasAjson());

        return json_encode($arregloJson, JSON_UNESCAPED_UNICODE);
    }

    public function consultaDePorcentageDeCarrera($carrera) // 1) ITI , 2) ICC, 3) LCC
    {

        $consulta = "SELECT
                        ( SELECT COUNT(*) as cantidad FROM aspirante WHERE fk_IdCarrera = $carrera) AS cantidadAspirantesPorCarrera,
                        ( SELECT COUNT(*) AS totalAlumnos from aspirante) AS totalAlumnos";
        $resultadoConsulta = $this->conexion->query($consulta);

        if ($resultadoConsulta->num_rows > 0) {
            /// array numérico y asociativo
            $arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH);
            $cantidad = $arregloNumerico_Asociativo['cantidadAspirantesPorCarrera'];
            $totalAlumnos = $arregloNumerico_Asociativo['totalAlumnos'];

            return (float)($cantidad / $totalAlumnos);
        } else {
            return 0;
        }

    }


    public function retornaListaDePreparatorias()
    {
        /*$numeroAleatorio = random_int(1, 10000);
        $consulta = "SELECT nombre FROM preparatoria WHERE idPreparatoria = $numeroAleatorio";

        $numeroAleatorio = random_int(1, 1000);
        $minimo = $numeroAleatorio - random_int(1, 20);*/

        $consulta = "SELECT idPreparatoria, nombre FROM preparatoria WHERE idPreparatoria BETWEEN 1315 AND 1323 ";
        $resultadoConsulta = $this->conexion->query($consulta);

        if ($resultadoConsulta->num_rows > 0) {
            /// array numérico y asociativo
            $salida = "";
            while ($arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH)) {
                $salida .= '<option value = ' . $arregloNumerico_Asociativo["idPreparatoria"] . '>' . $arregloNumerico_Asociativo["nombre"] . '</option>';
            }
            return $salida;

        } else {
            return null;
        }
    }
    //Porcentages Totales
    public function retornaPorcentagesPorCarrera()
    {
        $tablaValores = '<table class=' . '"highlight"' . '>
        <thead>
          <tr>
            <th>Porcentaje Carrera ITI</th>
            <th>Porcentaje Carrera ICC</th>
            <th>Porcentaje Carrera LCC</th>
          </tr>
        </thead>

        <tbody>
            <tr>
                <td>' . number_format($this->consultaDePorcentageDeCarrera(1), 4) . '</td>
                <td>' . number_format($this->consultaDePorcentageDeCarrera(2), 4) . '</td>
                <td>' . number_format($this->consultaDePorcentageDeCarrera(3), 4) . '</td>
             </tr>
        </tbody>
      </table>';
        return $tablaValores;
    }
    public function retornaPorcentagesPorSector()
    {

        $tablaValores = '<table class=' . '"highlight"' . '>
      <thead>
        <tr>
            <th>Porcentaje Preparatorias Públicas</th>
            <th>Porcentaje Preparatorias privadas</th>
        </tr>
      </thead>

      <tbody>
          <tr>
              <td>' . number_format($this->calculaPorcentageDePrepasPublicas(), 4) . '</td>
              <td>' . number_format($this->calculaPorcentageDePrepasPrivadas(), 4) . '</td>
           </tr>
      </tbody>
    </table>';

        return $tablaValores;
    }

    //Probabilidades de Bayes aplicado a los resultados principales en "estadisticas.php"
    public function retornaProbabilidadesDeBayes()
    {
        $tablaValores = '<table class=' . '"highlight"' . '>
        <thead>
          <tr>
            <th>Carrera</th>
            <th>Probabilidad de incripción viniendo de Escuela Pública</th>
            <th>Probabilidad de incripción viniendo de Escuela Privada</th>
          </tr>
        </thead>
        
        <tbody>
            <tr>
                <td>' . 'ITI' . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(1, "PUBLICO"), 4) . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(1, "PRIVADO"), 4) . '</td>
            </tr>
            <tr>
                <td>' . 'ICC' . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(2, "PUBLICO"), 4) . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(2, "PRIVADO"), 4) . '</td>
            </tr>
            <tr>
                <td>' . 'LCC' . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(3, "PUBLICO"), 4) . '</td>
                <td>' . number_format($this->calculaProbabilidadTotal(3, "PRIVADO"), 4) . '</td>
            </tr>
        </tbody>
      </table>';
        return $tablaValores; //////////////////////////////////////////7
    }
    //Retorna probabilidades De Bayes en formato json mostrado en "estadisticas.php"
    public function retornaProbabilidadesDeBayesEnFormatoJSON()
    {
        //Probabilidades Bayes totalales de Porcentajes para cada una de las carreras públicas y privadas (mostrada en la pagina principal "estadisticas.php")
        $jsonProbabilidadesBayes = array(
            'ITI' => array(number_format($this->calculaProbabilidadTotal(1, 'PUBLICO'), 4), number_format($this->calculaProbabilidadTotal(1, 'PRIVADO'), 4)),
            'ICC' => array(number_format($this->calculaProbabilidadTotal(2, 'PUBLICO'), 4), number_format($this->calculaProbabilidadTotal(2, 'PRIVADO'), 4)),
            'LCC' => array(number_format($this->calculaProbabilidadTotal(3, 'PUBLICO'), 4), number_format($this->calculaProbabilidadTotal(3, 'PRIVADO'), 4))
        );
        return json_encode($jsonProbabilidadesBayes, JSON_UNESCAPED_UNICODE);
    }

    //LLama dinamicamente por ajax
    public function consultaDinamica()
    {

        if (isset($this->id_preparatoria) != '' && isset($this->id_carrera) != '') {

            $consulta = "SELECT tipoDePrepa FROM preparatoria Where idPreparatoria = '$this->id_preparatoria'";
            $resultadoConsulta = $this->conexion->query($consulta);

            if ($resultadoConsulta->num_rows > 0) {
                /// array numérico y asociativo
                while ($arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH)) {
                    $sector = $arregloNumerico_Asociativo['tipoDePrepa'];
                }

                if ($sector === 'PUBLICO' && $this->id_carrera == 1) { // 1) ITI

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de ITI viniendo de una Escuela Pública</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Pública</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'ITI' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(1, 'PUBLICO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(1, 'PUBLICO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;

                } else if ($sector === 'PRIVADO' && $this->id_carrera == 1) {

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de ITI viniendo de una Escuela Privada</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Privada</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'ITI' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(1, 'PRIVADO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(1, 'PRIVADO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;

                } else if ($sector === 'PUBLICO' && $this->id_carrera == 2) {

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de ICC viniendo de una Escuela Pública</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Pública</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'ICC' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(2, 'PUBLICO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(2, 'PUBLICO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;

                } else if ($sector === 'PRIVADO' && $this->id_carrera == 2) {

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de ICC viniendo de una Escuela Privada</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Privada</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'ICC' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(2, 'PRIVADO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(2, 'PRIVADO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;
                } else if ($sector === 'PUBLICO' && $this->id_carrera == 3) {

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de LCC viniendo de una Escuela Pública</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Pública</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'LCC' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(3, 'PUBLICO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(3, 'PUBLICO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;
                } else if ($sector === 'PRIVADO' && $this->id_carrera == 3) {

                    $tablaValores = '<table class=' . '"highlight"' . '>
                    <thead>
                      <tr>
                        <th>Carrera</th>
                        <th>Probabilidad de inscripción a la carrera de LCC viniendo de una Escuela Privada</th>
                        <th>Probabilidad de inscripción en otras carreras viniendo de una Escuela Privada</th>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>' . 'LCC' . '</td>
                            <td>' . number_format($this->calculaProbabilidadTotal(3, 'PRIVADO'), 4) . '</td>
                            <td>' . number_format(1 - $this->calculaProbabilidadTotal(3, 'PRIVADO'), 4) . '</td>
                        </tr>
                    </tbody>
                  </table>';

                    return $tablaValores;

                } else {
                    $mensaje = "Busqueda vacia";
                    return $mensaje;

                }
            }
        }
    }

}

//$probabilidad = new CalculaProbabilidad();
//echo " ITI  " . $probabilidad->consultaDePorcentageDeCarrera(1);
//echo " PUBLICAS  " . $probabilidad->calculaPorcentageDePrepasPublicas();
//echo " PRIVADAS  " . $probabilidad->calculaPorcentageDePrepasPrivadas();
//echo "  PRIVADAS  " . $probabilidad->calculaPorcentageDePrepasPrivadas();
//echo $probabilidad->calculaProbabilidadTotal(3, "PRIVADO");
//echo $probabilidad->retornaListaDePrepas();
//echo (new CalculaProbabilidad())->retornaProbabilidadesDeBayes();

echo (new CalculaProbabilidad())->consultaDinamica();
//echo (new CalculaProbabilidad())->convierteDatosPorcentagesDeCarreras();
?>
