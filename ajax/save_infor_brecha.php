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

// (dataInforme, accion, brecha_tco, brecha_tcr, brecha_pco, brecha_pcr, oport_mco, oport_mcr)

$dataInforme = isset($_POST['dataInforme']) ? $_POST['dataInforme'] : null;
$accion = isset($_POST['accion']) ? $_POST['accion'] : null;
$obs = isset($_POST['obs']) ? $_POST['obs'] : null;
$brecha_tco = isset($_POST['brecha_tco']) ? $_POST['brecha_tco'] : null;
$brecha_tcr = isset($_POST['brecha_tcr']) ? $_POST['brecha_tcr'] : null;
$brecha_pco = isset($_POST['brecha_pco']) ? $_POST['brecha_pco'] : null;
$brecha_pcr = isset($_POST['brecha_pcr']) ? $_POST['brecha_pcr'] : null;
$oport_mco = isset($_POST['oport_mco']) ? $_POST['oport_mco'] : null;
$oport_mcr = isset($_POST['oport_mcr']) ? $_POST['oport_mcr'] : null;

$sql = "UPDATE detallle_ot SET brecha_tco = '$brecha_tco', brecha_tcr = '$brecha_tcr', brecha_pco = '$brecha_pco', brecha_pcr = '$brecha_pcr', oport_mco = '$oport_mco', oport_mcr = '$oport_mcr', date_brecha = '$fecha' WHERE id = '$dataInforme'";

if (mysqli_query($conn, $sql)) {
    $response = array('status' => 'success', 'message' => 'Se ha rechazado la orden con éxito.');
} else {
    $response = array('status' => 'error', 'message' => 'Error al rechazar la orden: ' . mysqli_error($conn));
}

header('Content-Type: application/json');
echo json_encode($response);
?>