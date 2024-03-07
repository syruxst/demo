<?php
session_start(); error_reporting(0);
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../index.php");
    exit();
}
// Conectarse a la base de datos
require_once('../admin/conex.php');
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['informe'])) {
    $data = $_POST['data'];
    $textObs = isset($_POST['textObs']) ? $_POST['textObs']: null;
    $textObs = mysqli_real_escape_string($conn, $textObs);
    $textObs = htmlspecialchars($textObs, ENT_QUOTES, 'UTF-8');
    $targetDir = 'informesFinalesM/';
    $originalFileName = $_FILES['informe']['name'];
    $fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    // Verificar si el archivo es un PDF
    if ($fileType !== 'pdf') {
        $response['error'] = 'Solo se permiten archivos PDF.';
    } else {
        // Generar un nombre único para el archivo
        $uniqueFileName = generateUniqueFileName($targetDir, 'informe_', $fileType);
        $targetFile = $targetDir . $uniqueFileName;

        // Mover el archivo a la carpeta
        if (move_uploaded_file($_FILES['informe']['tmp_name'], $targetFile)) {

            if (file_exists($targetFile)) {
                // Utilizar una consulta preparada para evitar la inyección SQL
                $stmt = $conn->prepare("UPDATE `informesM` SET `info_final` = ?, `observaciones` = ? WHERE `IdOper` = ?");
                $stmt->bind_param("ssi", $uniqueFileName, $textObs, $data);
                $stmt->execute();
                $stmt->close();

                $response['success'] = 'El informe se ha subido correctamente.';
                $response['filename'] = $uniqueFileName;
            }

        } else {
            $response['error'] = 'Hubo un error al subir el informe.';
            $response['php_error'] = error_get_last(); // Obtener información del último error PHP
        }


    }
} else {
    $response['error'] = 'Acceso no autorizado.';
}

echo json_encode($response);

// Función para generar un nombre único con más aleatoriedad
function generateUniqueFileName($targetDir, $prefix, $fileType) {
    do {
        $uniqueFileName = uniqid($prefix, true) . '.' . $fileType;
        $targetFile = $targetDir . $uniqueFileName;
    } while (file_exists($targetFile)); // Verificar colisiones

    return $uniqueFileName;
}

?>