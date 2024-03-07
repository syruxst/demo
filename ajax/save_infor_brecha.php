<?php session_start(); error_reporting(0);

date_default_timezone_set('America/Santiago');
$hora_actual = new DateTime();
$fecha = $hora_actual->format('Y-m-d H:i:s') . PHP_EOL;

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../login.php");
    exit();
}

require_once('../admin/conex.php');

$dataInforme = isset($_POST['dataInforme']) ? $_POST['dataInforme'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
$obs = isset($_POST['obs']) ? $_POST['obs'] : null;
$brechas = isset($_POST['brechas']) ? $_POST['brechas'] : null;
$oport = isset($_POST['oport']) ? $_POST['oport'] : null;

$sql = "UPDATE detallle_ot SET brecha_s = '$brechas', brecha_p = '$obs', oport_m = '$oport', date_brecha = '$fecha' WHERE id = '$dataInforme'";

if (mysqli_query($conn, $sql)) {
    $response = array('status' => 'success', 'message' => 'Se ha rechazado la orden con éxito.');
} else {
    $response = array('status' => 'error', 'message' => 'Error al rechazar la orden: ' . mysqli_error($conn));
}

header('Content-Type: application/json');
echo json_encode($response);
?>