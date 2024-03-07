<?php
session_start();
error_reporting(0);
require_once('../../admin/conex.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $procedimiento = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $version = $_POST['version'];

    $archivoNombre = $_FILES['archivo']['name'];
    $archivoTmpName = $_FILES['archivo']['tmp_name'];
    $archivoTipo = $_FILES['archivo']['type'];

    $perfil = $_POST['roles'];

    $extension = pathinfo($archivoNombre, PATHINFO_EXTENSION);
    if ($extension !== 'pdf') {
        $response = ['success' => false, 'message' => 'Solo se permiten archivos PDF.'];
        echo json_encode($response);
        exit;
    }

    $tiposPermitidos = ['application/pdf'];
    $tipoArchivo = mime_content_type($archivoTmpName);
    if (!in_array($tipoArchivo, $tiposPermitidos)) {
        $response = ['success' => false, 'message' => 'El archivo no es un PDF válido.'];
        echo json_encode($response);
        exit;
    }

    $ruta = 'procedimientosNC/' . $archivoNombre;

    if (move_uploaded_file($archivoTmpName, $ruta)) {
        // Realizar la inserción en la base de datos
        if ($conn) {
            // Escapar los datos antes de la consulta (previene SQL injection)
            $nombreEscapado = mysqli_real_escape_string($conn, $procedimiento);
            $codigoEscapado = mysqli_real_escape_string($conn, $codigo);
            $versionEscapada = mysqli_real_escape_string($conn, $version);
            $rutaEscapada = mysqli_real_escape_string($conn, $ruta);

            if (is_array($perfil) && !empty($perfil)) {
                // Convertir el array en una cadena de texto separada por comas
                $perfilTexto = implode(', ', $perfil);
            } else {
                // Si no hay roles seleccionados, puedes asignar un valor predeterminado o manejarlo de otra manera
                $perfilTexto = 'Ningún rol seleccionado';
            }

            // Consulta SQL para insertar los datos en la tabla 'curriculum'
            $sql = "INSERT INTO `procedimientosNCINN` (nombre, codigo, version, ruta, perfil) VALUES ('$nombreEscapado', '$codigoEscapado', '$versionEscapada', '$rutaEscapada', '$perfilTexto')";
            
            if (mysqli_query($conn, $sql)) {
                // Inserción exitosa
                $response = ['success' => true, 'message' => 'El procedimiento ha sido subido con exito.'];
                echo json_encode($response);
            } else {
                // Error en la consulta SQL
                $response = ['success' => false, 'message' => 'Ha habido un error : ' . mysqli_error($conexion)];
                echo json_encode($response);
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conn);
        } else {
            // Error en la conexión a la base de datos
            $response = ['success' => false, 'message' => 'Error en la conexión a la base de datos.'];
            echo json_encode($response);
        }
    } else {
        // Error al mover el archivo
        $response = ['success' => false, 'message' => 'Error al subir el archivo.'];
        echo json_encode($response);
    }

} else {
    // Manejar el caso en que no se recibe una solicitud POST
    http_response_code(405); // Método no permitido
    echo 'Método no permitido';
}
?>