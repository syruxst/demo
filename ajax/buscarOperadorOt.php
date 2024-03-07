<?php
session_start();
error_reporting(0);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no está autenticado, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}

// Conectar a la base de datos
require_once('../admin/conex.php');

if (isset($_GET['rut'])) {
    $rut = $_GET['rut'];
    
    // Evitar inyección SQL utilizando sentencias preparadas
    $stmt = mysqli_prepare($conn, "SELECT nombre, apellidos FROM operadores WHERE rut = ?");
    mysqli_stmt_bind_param($stmt, "s", $rut);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombreOperador, $apellidosOperador);
    
    if (mysqli_stmt_fetch($stmt)) {

        $nombreFormateado = ucwords(strtolower($nombreOperador));
        $apellidosFormateados = ucwords(strtolower($apellidosOperador));

        echo json_encode(array("nombre" => $nombreFormateado, "apellidos" => $apellidosFormateados));
    } else {
        echo json_encode(array("error" => "Operador no encontrado"));
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>