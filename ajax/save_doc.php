<?php
session_start();
error_reporting(0);

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../login.php");
    exit();
}

require_once('../admin/conex.php');

$id = $_POST['id'];
$opcion = $_POST['opcion'];

$query = "UPDATE `detallle_ot` SET `doc` = '$opcion' WHERE `id` = '$id'";

if ($conn->query($query) === TRUE) {
    $response = [
        'status' => 'success',
        'message' => 'Los datos han sido guardados con éxito.'
    ];
    http_response_code(200);
} else {
    $response = [
        'status' => 'error',
        'message' => 'Ocurrió un problema al guardar los datos.'
    ];
    http_response_code(500);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
