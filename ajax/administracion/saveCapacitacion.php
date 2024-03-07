<?php
session_start();
error_reporting(0);
require_once('../../admin/conex.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];

    // Obtener información sobre el archivo
    $archivoNombre = $_FILES['archivo']['name'];
    $archivoTmpName = $_FILES['archivo']['tmp_name'];
    $archivoTipo = $_FILES['archivo']['type'];

    // Verificar la extensión del archivo
    $extension = pathinfo($archivoNombre, PATHINFO_EXTENSION);
    if ($extension !== 'pdf') {
        $response = ['success' => false, 'message' => 'Solo se permiten archivos PDF.'];
        echo json_encode($response);
        exit;
    }

    // Verificar el tipo MIME del archivo
    $tiposPermitidos = ['application/pdf'];
    $tipoArchivo = mime_content_type($archivoTmpName);
    if (!in_array($tipoArchivo, $tiposPermitidos)) {
        $response = ['success' => false, 'message' => 'El archivo no es un PDF válido.'];
        echo json_encode($response);
        exit;
    }

    // Generar un nombre único para el archivo PDF
    $nombreUnico = uniqid() . '.pdf';
    $ruta = 'capacitacion/' . $nombreUnico;

    // Mover el archivo a la ruta especificada
    if (move_uploaded_file($archivoTmpName, $ruta)) {
        // Realizar la inserción en la base de datos
        if ($conn) {
            // Escapar los datos antes de la consulta (previene SQL injection)
            $nombreEscapado = mysqli_real_escape_string($conn, $nombre);
            $cargoEscapado = mysqli_real_escape_string($conn, $cargo);
            $rutaEscapada = mysqli_real_escape_string($conn, $ruta);
            $nombreCapitalizado = ucwords(strtolower($nombreEscapado));

            // Consulta SQL para insertar los datos en la tabla 'curriculum'
            $sql = "INSERT INTO capacitacion (nombre, capacitacion, ruta) VALUES ('$nombreCapitalizado', '$cargoEscapado', '$rutaEscapada')";
            
            if (mysqli_query($conn, $sql)) {
                // Inserción exitosa
                $response = ['success' => true, 'message' => 'Datos y archivo recibidos y guardados correctamente en la base de datos.'];
                echo json_encode($response);
            } else {
                // Error en la consulta SQL
                $response = ['success' => false, 'message' => 'Error en la consulta SQL: ' . mysqli_error($conexion)];
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