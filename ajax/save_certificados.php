<?php
session_start();
require_once('../admin/conex.php');
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $buscarUser = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$usuario'");
    $row = mysqli_fetch_array($buscarUser);
    $perfil = $row['permiso'];
} else {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['archivo'])) {
        $folio = $_POST['folio'];
        $directorio_destino = '../firmados/';
        $nombre_archivo = basename($_FILES['archivo']['name']);
        $ruta_firma = $directorio_destino . $nombre_archivo;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_firma)) {
            $sql = "UPDATE detallle_ot SET ruta_firma='$ruta_firma' WHERE id='$folio'";
            if (mysqli_query($conn, $sql)) {
                $response = array("status" => "success", "message" => "Los datos se han recibido y el archivo se ha guardado correctamente.");
            } else {
                $response = array("status" => "error", "message" => "Hubo un error al procesar los datos o al guardar el archivo.");
            }
        } else {
            $response = array("status" => "error", "message" => "Hubo un error al guardar el archivo.");
        }
    } else {
        $response = array("status" => "error", "message" => "No se ha recibido ningún archivo.");
    }
    header("Content-Type: application/json");
    echo json_encode($response);
}
?> 