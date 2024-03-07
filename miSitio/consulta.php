<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['operador']) || isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['operador'])) {
       $usuario = $_SESSION['operador'];
       $query = "SELECT * FROM operadores WHERE rut = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['nombre']. " " .$row['apellidos'];
    } else {
       $usuario = $_SESSION['usuario'];
    }
} else {
    header("Location: ../ajax/login.php");
    exit();
}

$query = "SELECT rut, resultado FROM `detallle_ot` WHERE rut = '$usuario' AND resultado = ''";
$result = mysqli_query($conn, $query);

if ($result) {
    $numResultados = mysqli_num_rows($result);
    echo json_encode(["numResultados" => $numResultados]);
} else {
    echo json_encode(["error" => "Error en la consulta"]);
}
?>