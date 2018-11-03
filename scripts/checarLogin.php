<?php
session_start();
?>

<?php
include("conexiones.php");
class Verificacion
{
    private $conexion;
    private $usuario;
    private $contrasenia;

    function __construct($usuario, $contrasenia)
    {
        $nuevaConexion = new Conexion();
        $this->conexion = $nuevaConexion->conectarBD();
        $this->conexion->set_charset("utf8");

        $this->usuario = $usuario;
        $this->contrasenia = $contrasenia;

    }
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function getContrasenia()
    {
        return $this->contrasenia;
    }
    public function verificaUsuario()
    {
        $consultaUsuario = "SELECT * FROM `usuario_sistema` WHERE `usuario` = '{$this->getUsuario()}'";
        $resultadoConsulta = $this->conexion->query($consultaUsuario);


        if ($resultadoConsulta->num_rows > 0) {
        
            /* array numérico y asociativo */
            $arregloNumerico_Asociativo = $resultadoConsulta->fetch_array(MYSQLI_BOTH);
            //descifrar hash
            //if (password_verify($contrasenia, $arregloNumerico_Asociativo['contraseña'])) {
            if ($this->getContrasenia() === $arregloNumerico_Asociativo['contraseña']) {
                $_SESSION['conectado'] = true;
                $_SESSION['usuario'] = $this->getUsuario();
                $_SESSION['inicia'] = time();
                $_SESSION['expira'] = $_SESSION['inicia'] + (360 * 60);

                echo "Bienvenido! " . $_SESSION['usuario'];
                //echo "<br><br><a href=../principal.php>Página principal</a>";
                header("Location:../principal.php");    //Abre pagina

            } else {
                echo "Username o Password estan incorrectos.";

                echo "<br><a href='../index.php'>Volver a Intentarlo</a>";
            }
        }
    }
}
//proceso de verificación
$verifica = new Verificacion($_POST['usuario'], $_POST['contrasenia']);
$verifica->verificaUsuario();

?>