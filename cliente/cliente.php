<?php
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Conectar a la base de datos
include_once('../admin/conex.php');


$username = trim($_POST['usuario']);
$password = trim($_POST['pass']);

// Hashear la contraseña
$hash = password_hash($password, PASSWORD_BCRYPT);

// Consulta para validar el usuario y la contraseña
$sql = "SELECT * FROM `clientes` WHERE user = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado, ahora verificar la contraseña
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['pass'])) {
        session_start();
        // Inicia una sesión en PHP.
    
        $_SESSION['loggedin'] = true;
        // Establece el valor de 'loggedin' en la sesión como verdadero, indicando que el usuario ha iniciado sesión.
    
        $_SESSION['cliente'] = $username;
        // Almacena el nombre de usuario en la sesión con la clave 'cliente'.
    
        $_SESSION['start'] = time();
        // Establece el tiempo de inicio de la sesión como el momento actual.
    
        $_SESSION['expire'] = $_SESSION['start'] + (15 * 60);
        // Establece el tiempo de expiración de la sesión 15 minutos después del inicio.
    
        header("Location: ../cliente/");
        // Redirige al usuario a la página '../cliente/'.
    
        exit();
        // Termina la ejecución del script para evitar que se procesen más líneas después de la redirección.
    } else {
        include("../cliente.php");
        mostrarError("El usuario o la contraseña son incorrectos.");
    }
} else {
    include("../cliente.php");
    mostrarError("El usuario o la contraseña son incorrectos.");
}

function mostrarError($mensaje) {
    echo '
    <script>
    swal({
        title: "Control de Usuario!",
        text: "'. $mensaje .'",
        icon: "info",
        button: "Aceptar!",
      }).then(function() {
        window.location.href = "../cliente.php";
      });
    </script>
    ';
    exit();
}

$stmt->close();
$conn->close();
?>