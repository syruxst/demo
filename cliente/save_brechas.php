<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
} else {
    header("Location: ../cliente.php");
    exit();
}
// Verificar si se ha recibido la solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $datos = $_POST["datos"];
    $dataId = $_POST['dataId'];

    // Verificar si se ha cargado un archivo
    if (isset($_FILES["file"])) {
        $archivo = $_FILES["file"];

        // Verificar si no hubo errores durante la carga del archivo
        if ($archivo["error"] == 0) {
            // Verificar el tipo de archivo permitido
            $extensionesPermitidas = array("pdf", "doc", "docx", "PDF", "DOC", "DOCX");
            $nombreArchivoOriginal = strtolower(pathinfo($archivo["name"], PATHINFO_FILENAME));
            $extensionArchivo = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
            $nombreArchivo = $nombreArchivoOriginal . "_" . uniqid() . "." . $extensionArchivo;

            // Acceder a la información del archivo
            $tipoArchivo = $archivo["type"];
            $tamanioArchivo = $archivo["size"];
            $rutaTemporal = $archivo["tmp_name"];

            // Mover el archivo a la ubicación deseada (ajusta la ruta según tu necesidad)
            $carpetaDestino = "brechas/";
            $rutaFinal = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                // El archivo se ha movido exitosamente, aquí puedes realizar más acciones si es necesario
                // Ejemplo: Guardar información en la base de datos o realizar alguna operación adicional
                $guardar = mysqli_prepare($conn, "UPDATE `detallle_ot` SET info_brechas = ? WHERE id = ?");
                mysqli_stmt_bind_param($guardar, 'si', $rutaFinal, $dataId);
                mysqli_stmt_execute($guardar);
                mysqli_stmt_close($guardar);

                if ($guardar) {
                    // Éxito
                    $response = array(
                        'status' => 'success',
                        'message' => '¡Archivo subido exitosamente!'
                    );
                } else {
                    // Error al actualizar la base de datos
                    $response = array(
                        'status' => 'error',
                        'message' => '¡Error al actualizar la base de datos!'
                    );
                }
            } else {
                // Hubo un error al mover el archivo
                $response = array(
                    'status' => 'error',
                    'message' => '¡Error al mover el archivo!'
                );
            }
        } else {
            // Hubo un error durante la carga del archivo
            $response = array(
                'status' => 'error',
                'message' => '¡Hubo un error durante la carga del archivo!'
            );
        }
    } else {
        // No se ha recibido el archivo esperado
        $response = array(
            'status' => 'info',
            'message' => '¡No se ha recibido el archivo!'
        );
    }
} else {
    // La solicitud no es de tipo POST
    $response = array(
        'status' => 'info',
        'message' => '¡Acceso no permitido!'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
?>