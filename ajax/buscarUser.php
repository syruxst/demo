<?php
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Conectar a la base de datos
include_once('../admin/conex.php');


$username = trim($_POST['usuario']);
$password = trim($_POST['pass']);

// Consulta para validar el usuario y la contraseña
$sql = "SELECT * FROM operadores WHERE rut = ? AND clave_web = PASSWORD(?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    session_start();
    $_SESSION['loggedin'] = true;
    $_SESSION['operador'] = $username;
    $_SESSION['start'] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (5 * 60);
    header("Location: ../miSitio/");
    exit();
} else {
    include_once("login.php");

    mostrarError("El usuario o la contraseña son incorrectos.");
}

$stmt->close();

// Función para mostrar mensajes de error y redirigir
function mostrarError($mensaje) {
    echo '
    <script>
    swal({
        title: "Control de Usuario!",
        text: "'. $mensaje .'",
        icon: "info",
        button: "Aceptar!",
      }).then(function() {
        window.location.href = "login.php";
      });
    </script>
    ';
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>