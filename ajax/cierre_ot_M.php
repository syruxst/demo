<?php
session_start();
error_reporting(0);

// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}

$fechaHoraActual = date('dmYHis');
$contenidoAEncriptar = $usuario . $fechaHoraActual;

$timezone = new DateTimeZone(date_default_timezone_get()); // Obtiene la zona horaria local
$date = new DateTime('now', $timezone); // Crea una instancia de DateTime con la fecha y hora actual y la zona horaria local
$localTime = $date->format('Y-m-d'); // Obtiene la fecha y hora local formateada

$hash = md5($contenidoAEncriptar);

$qr = $hash;

// Conectarse a la base de datos
require_once('../admin/conex.php');

// Recuperar los datos enviados por JavaScript
$dataInforme = isset($_POST['dataInforme']) ? $_POST['dataInforme'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : null; // Variable $accion agregada
$resolucionValue = $accion;

if ($accion === 'aprobar') {
    $estado = "APROBADO"; // Cambia esto al valor que desees
    $sql = "UPDATE detallle_ot SET estado = '$estado', qr = '$qr', fecha_arprob = '$localTime', certificate = '$estado' WHERE id = '$dataInforme'";
} elseif ($accion === 'rechazar') {
    $estado = "RECHAZADO"; // Cambia esto al valor que desees
    $sql = "UPDATE detallle_ot SET estado = '$estado', qr = '$qr', fecha_arprob = '$localTime', certificate = '$estado' WHERE id = '$dataInforme'";
} else {
    $response = array('status' => 'error', 'message' => 'Acción desconocida: ' . $accion);
    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    // Cierra la conexión a la base de datos
    mysqli_close($conn);
    exit(); // Agregar exit después de enviar la respuesta de error
}

if (mysqli_query($conn, $sql)) {
    $response = array('status' => 'success', 'message' => 'Operación exitosa.');
} else {
    $response = array('status' => 'error', 'message' => 'Error en la operación: ' . mysqli_error($conn));
}

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>