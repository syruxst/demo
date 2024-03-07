<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');
date_default_timezone_set('America/Santiago');

$date = date("Y-m-d H:i:s");

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $nombre = $row['name'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se han recibido los datos del formulario
    if (isset($_POST['boleta']) && isset($_FILES['file']) && isset($_FILES['imagen'])) {
        $Tipo = $_POST['tipo'];
        $codigo = $_POST['codigo'];
        $cantidad = $_POST['numero'];
        $name = $_POST['name'];
        $rut = $_POST['rut'];
        $cta = $_POST['cta'];
        $banco = $_POST['banco'];
        $numerocta = $_POST['numerocta'];
        $total = $_POST['total'];
        $visitas = $_POST['visitas'];
        $total = str_replace('.', '', $total);
        $boleta = $_POST['boleta'];
        $fecha = $_POST['fecha'];
        $file = $_FILES['file'];
        $imagen = $_FILES['imagen'];
        $date_in = $_POST['date_in'];
        $date_out = $_POST['date_out'];

        // Comprobar si el número de boleta y usuario ya existen en la base de datos
        $consultaExistencia = "SELECT * FROM document WHERE user = '$usuario' AND boleta = '$boleta'";
        $resultadoExistencia = mysqli_query($conn, $consultaExistencia);

        if (mysqli_num_rows($resultadoExistencia) > 0) {
            // Si ya existe, no se puede guardar
            echo 'info';
        } else {
            // Si no existe, continuar con el proceso de guardado
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $fileInfo->file($file['tmp_name']);

            if ($mime === 'application/pdf') {
                // Generar un nombre único para el archivo PDF
                $timestampPDF = time();
                $newFileNamePDF = $timestampPDF . '_' . $file['name'];

                // Mover el archivo PDF a la ubicación deseada con el nuevo nombre
                if (move_uploaded_file($file['tmp_name'], 'boletas/' . $newFileNamePDF)) {

                    // Comprobar si el archivo de imagen es una imagen válida
                    $fileInfoImagen = new finfo(FILEINFO_MIME_TYPE);
                    $mimeImagen = $fileInfoImagen->file($imagen['tmp_name']);

                    if ($mimeImagen === 'image/jpeg' || $mimeImagen === 'image/png' || $mimeImagen === 'image/gif') {
                        // Generar un nombre único para el archivo de imagen
                        $timestampImagen = time();
                        $newFileNameImagen = $timestampImagen . '_' . $imagen['name'];

                        // Mover el archivo de imagen a la ubicación deseada con el nuevo nombre
                        if (move_uploaded_file($imagen['tmp_name'], 'respaldo/' . $newFileNameImagen)) {
                            // Guardar el nombre del archivo en la base de datos
                            $sql = "INSERT INTO `document` 
                            (`user`, `codigo`, `nombre`, `rut`, `cantidad`, `visitas`, `boleta`, `dateBoleta`, `ruta`, `respaldo`, `total`, `cta`, `banco`, `numerocta`, `fecha`, `estado`, `date_in`, `date_out`, `tipo`)
                             VALUES
                             ('$usuario', '$codigo', '$name', '$rut', '$cantidad', '$visitas', '$boleta', '$fecha', '$newFileNamePDF', '$newFileNameImagen', '$total', '$cta', '$banco', '$numerocta', '$date', 'ABIERTA', '$date_in', '$date_out', '$Tipo')";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                // Si la consulta se ejecutó correctamente, puedes considerarlo un éxito
                                echo 'success';
                            } else {
                                // Si hubo un problema al subir el archivo, puedes considerarlo un error
                                echo 'error';
                            }
                        } else {
                            // Si hubo un problema al subir el archivo de imagen, puedes considerarlo un error
                            echo 'error';
                        }
                    } else {
                        // El archivo de imagen no es válido, considerarlo un error
                        echo 'error';
                    }
                } else {
                    // Si hubo un problema al subir el archivo PDF, puedes considerarlo un error
                    echo 'error';
                }
            } else {
                // El archivo no es un PDF, considerarlo un error
                echo 'error';
            }
        }
    } else {
        // Datos del formulario incompletos o incorrectos
        echo 'error';
    }
} else {
    // Solicitud incorrecta
    http_response_code(400);
    echo 'Solicitud incorrecta';
}
?>