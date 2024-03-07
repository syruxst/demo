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
$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : '';
$rut = isset($_POST['rut']) ? $_POST['rut'] : '';
$giro = isset($_POST['giro']) ? $_POST['giro'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$selectRegiones = isset($_POST['selectRegiones']) ? $_POST['selectRegiones'] : '';
$selectComunas = isset($_POST['selectComunas']) ? $_POST['selectComunas'] : '';
$contacto = isset($_POST['contacto']) ? $_POST['contacto'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';

// Consultar si la empresa ya existe en la base de datos por nombre o RUT
$stmt = $conn->prepare("SELECT * FROM empresa WHERE rut = ?");
$stmt->bind_param("s", $rut);
$stmt->execute();
$result = $stmt->get_result();
$rowCount = $result->num_rows;

if ($rowCount > 0) {
    // Si ya existe una empresa con el mismo nombre o RUT, mostrar mensaje de error
    echo 'empresa_existente';
    exit();
}

// Si la empresa no existe, procedemos a insertarla en la base de datos
$stmt = $conn->prepare("INSERT INTO empresa (nombre, rut, giro, direccion, comuna, ciudad, telefono, contacto, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $empresa, $rut, $giro, $direccion, $selectRegiones, $selectComunas, $telefono, $contacto, $correo);
$insertSuccessful = $stmt->execute();

if ($insertSuccessful) {
    echo 'success';
} else {
    echo 'error';
}
?>
