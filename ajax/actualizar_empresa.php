<?php 
require_once('../admin/conex.php'); 
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: formulario_inicio_sesion.php");
    exit();
}

// Recibiendo los datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : '';
$rut = isset($_POST['rut']) ? $_POST['rut'] : '';
$giro = isset($_POST['giro']) ? $_POST['giro'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$selectRegiones = isset($_POST['region']) ? $_POST['region'] : '';
$selectComunas = isset($_POST['comuna']) ? $_POST['comuna'] : '';
$contacto = isset($_POST['contacto']) ? $_POST['contacto'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';


// Si la empresa no existe, procedemos a insertarla en la base de datos
$stmt = $conn->prepare("UPDATE empresa SET nombre = ?, rut = ?, giro = ?, direccion = ?, comuna = ?, ciudad = ?, telefono = ?, contacto = ?, correo = ? WHERE id = ?");
$stmt->bind_param("sssssssssi", $empresa, $rut, $giro, $direccion, $selectRegiones, $selectComunas, $telefono, $contacto, $correo, $nombre);
$insertSuccessful = $stmt->execute();


if ($insertSuccessful) {
    if ($stmt->affected_rows > 0) {
        echo 'success'; // Al menos una fila fue actualizada correctamente
    } else {
        echo 'info'; // No se modificaron filas, es decir, no se encontró el registro con el ID proporcionado
    }
} else {
    echo 'error'; // Ocurrió un error durante la ejecución de la consulta
}
?>
