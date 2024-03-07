<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');
$user_id = $_SESSION['usuario'];

$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone);
$FechaSave = $now->format("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $IdOper = $_POST['IdOper'];

    $valid = mysqli_query($conn, "SELECT IdOper FROM `informesM` WHERE IdOper = '$IdOper'");

    if (mysqli_num_rows($valid) > 0) {
        echo 'info';
    } else {
        $folio = $_POST['folio'];

        $Query = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id = '$IdOper'");
        $Row = mysqli_fetch_array($Query);
        $EvIp = $Row['ip'];
        $Folio = $EvIp;

        $fecha = $_POST['fecha'];
        $patente = $_POST['patente'];
        $equipo = $_POST['equipo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $year = $_POST['year'];
        $tipo = $_POST['tipo'];
        $horometro = $_POST['horometro'];
        $motor = $_POST['motor'];
        $codigo = $_POST['codigo'];
        $lugar = filter_input(INPUT_POST, 'lugar', FILTER_SANITIZE_STRING);
        $evaluador = $_POST['evaluador'];
        $resultado = $_POST['action'];

        if($resultado == 'APROBADO'){

            if ($_FILES) {
                $carpetaDestino = 'imgInformes/';
                $formatosPermitidos = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
            
                function obtenerExtension($nombreArchivo) {
                    $info = pathinfo($nombreArchivo);
                    return $info['extension'];
                }
            
                // Definir los tipos de evidencia y sus respectivos nombres de input
                $evidencias = array(
                    'Informe' => 'fileInput',
                    'HDS' => 'hseguridad',
                    'Sello' => 'sello',
                    'RevisionTecnica' => 'revisionTecnica',
                    'Padron' => 'padron',
                    'PCirculacion' => 'PCirculacion',
                    'Soap' => 'Soap',
                    'Frente' => 'Frente',
                    'Izquierdo' => 'Izquierdo',
                    'Derecho' => 'Derecho',
                    'Trasera' => 'Trasera',
                    'PEmergencia' => 'PEmergencia',
                    'Corriente' => 'Corriente',
                    'Extintor' => 'Extintor',
                    'Otros' => 'Otros'
                );
            
                // Manejo de archivos
                $archivosMovidosExitosamente = true;
            
                // Variables para almacenar las rutas de las imágenes
                $rutaInforme = '';
                $rutaHDS = '';
                $rutaSello = '';
                $rutaRevisionTecnica = '';
                $rutaPadron = '';
                $rutaPCirculacion = '';
                $rutaSoap = '';
                $rutaFrente = '';
                $rutaIzquierdo = '';
                $rutaDerecho = '';
                $rutaTrasera = '';
                $rutaPEmergencia = '';
                $rutaCorriente = '';
                $rutaExtintor = '';
                $rutaOtros = '';
            
                foreach ($evidencias as $nombreEvidencia => $nombreInputFile) {
                    $nombreArchivo = $_FILES[$nombreInputFile]['name'];
                    $archivoTemporal = $_FILES[$nombreInputFile]['tmp_name'];
                    $extension = obtenerExtension($nombreArchivo);
            
                    // Generar un nombre de archivo único
                    $nombreArchivoUnico = uniqid($nombreEvidencia . '_') . '.' . $extension;
            
                    // Ruta de destino con el nuevo nombre
                    $rutaDestino = $carpetaDestino . $nombreArchivoUnico;
            
                    if (in_array($extension, $formatosPermitidos)) {
                        if (move_uploaded_file($archivoTemporal, $rutaDestino)) {
                            // Almacenar la ruta del archivo en la variable correspondiente
                            ${'ruta' . $nombreEvidencia} = $rutaDestino;
                        } else {
                            echo "Error al mover el archivo para $nombreEvidencia.";
                            $archivosMovidosExitosamente = false;
                        }
                    } else {
                        echo "Error: Formato de archivo no permitido para $nombreEvidencia.";
                        $archivosMovidosExitosamente = false;
                    }
                }
            
                if ($archivosMovidosExitosamente) {
                    $query = mysqli_query($conn, "INSERT INTO `informesM` (
                        `IdOper`, 
                        `folio`, 
                        `equipo`, 
                        `patente`, 
                        `marca`, 
                        `modelo`, 
                        `ano`, 
                        `tipo`, 
                        `horometro`, 
                        `motor`, 
                        `codigoInterno`, 
                        `fecha`, 
                        `imgInforme`, 
                        `imgHDS`,
                        `imgSello`, 
                        `imgRTecnica`, 
                        `imgPadron`, 
                        `imgPCirculacion`, 
                        `imgSoap`, 
                        `imgFrente`, 
                        `imgIzquierdo`, 
                        `imgDerecho`, 
                        `imgTrasera`, 
                        `imgPEmergencia`, 
                        `imgCorriente`, 
                        `imgExtintor`, 
                        `imgOtros`, 
                        `evaluador`, 
                        `resultado`, 
                        `fechaInforme`, 
                        `userInforme`, 
                        `lugar`
                    ) VALUES (
                        '$IdOper', 
                        '$folio', 
                        '$equipo', 
                        '$patente', 
                        '$marca', 
                        '$modelo', 
                        '$year', 
                        '$tipo', 
                        '$horometro', 
                        '$motor', 
                        '$codigo', 
                        '$fecha ', 
                        '$rutaInforme', 
                        '$rutaHDS',
                        '$rutaSello', 
                        '$rutaRevisionTecnica', 
                        '$rutaPadron', 
                        '$rutaPCirculacion', 
                        '$rutaSoap', 
                        '$rutaFrente', 
                        '$rutaIzquierdo', 
                        '$rutaDerecho', 
                        '$rutaTrasera', 
                        '$rutaPEmergencia', 
                        '$rutaCorriente', 
                        '$rutaExtintor', 
                        '$rutaOtros', 
                        '$evaluador',
                        '$resultado', 
                        '$FechaSave', 
                        '$user_id',
                        '$lugar'
                    )");
            
                // Actualizar estado de la OT
                $update = mysqli_query($conn, "UPDATE detallle_ot SET informe = '$resultado' WHERE id = '$IdOper'");
            
                if ($query) {
                    // Verificar el número de filas afectadas
                    $filas_afectadas = mysqli_affected_rows($conn);
            
                    if ($filas_afectadas > 0) {
                        // Los datos se guardaron correctamente
                        echo 'success';
                    } else {
                        // No se guardaron datos
                        echo 'error';
                    }
                } else {
                    // Error en la consulta
                    echo 'error';
                }
            
                } else {
                    echo "No se pudieron mover todos los archivos correctamente.";
                }
            
            } else {
                echo "No se han enviado archivos.";
            }

        }else{
           
            if ($_FILES) {
                $carpetaDestino = 'imgInformes/';
                $formatosPermitidos = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
            
                function obtenerExtension($nombreArchivo) {
                    $info = pathinfo($nombreArchivo);
                    return $info['extension'];
                }
            
                // Definir los tipos de evidencia y sus respectivos nombres de input
                $evidencias = array(
                    'Informe' => 'fileInput',
                    'HDS' => 'hseguridad',
                    'Placa' => 'placa',
                    'RevisionTecnica' => 'revisionTecnica',
                    'Padron' => 'padron',
                    'PCirculacion' => 'PCirculacion',
                    'Soap' => 'Soap',
                    'Frente' => 'Frente',
                    'Izquierdo' => 'Izquierdo',
                    'Derecho' => 'Derecho',
                    'Trasera' => 'Trasera',
                    'PEmergencia' => 'PEmergencia',
                    'Corriente' => 'Corriente',
                    'Extintor' => 'Extintor',
                    'Otros' => 'Otros'
                );
            
                // Manejo de archivos
                $archivosMovidosExitosamente = true;
            
                // Variables para almacenar las rutas de las imágenes
                $rutaInforme = '';
                $rutaHDS = '';
                $rutaPlaca = '';
                $rutaRevisionTecnica = '';
                $rutaPadron = '';
                $rutaPCirculacion = '';
                $rutaSoap = '';
                $rutaFrente = '';
                $rutaIzquierdo = '';
                $rutaDerecho = '';
                $rutaTrasera = '';
                $rutaPEmergencia = '';
                $rutaCorriente = '';
                $rutaExtintor = '';
                $rutaOtros = '';
            
                foreach ($evidencias as $nombreEvidencia => $nombreInputFile) {
                    $nombreArchivo = $_FILES[$nombreInputFile]['name'];
                    $archivoTemporal = $_FILES[$nombreInputFile]['tmp_name'];
                    $extension = obtenerExtension($nombreArchivo);
            
                    // Generar un nombre de archivo único
                    $nombreArchivoUnico = uniqid($nombreEvidencia . '_') . '.' . $extension;
            
                    // Ruta de destino con el nuevo nombre
                    $rutaDestino = $carpetaDestino . $nombreArchivoUnico;
            
                    if (in_array($extension, $formatosPermitidos)) {
                        if (move_uploaded_file($archivoTemporal, $rutaDestino)) {
                            // Almacenar la ruta del archivo en la variable correspondiente
                            ${'ruta' . $nombreEvidencia} = $rutaDestino;
                        } else {
                            echo "Error al mover el archivo para $nombreEvidencia.";
                            $archivosMovidosExitosamente = false;
                        }
                    } else {
                        echo "Error: Formato de archivo no permitido para $nombreEvidencia.";
                        $archivosMovidosExitosamente = false;
                    }
                }
            
                if ($archivosMovidosExitosamente) {
                    $query = mysqli_query($conn, "INSERT INTO `informesM` (
                        `IdOper`, 
                        `folio`, 
                        `equipo`, 
                        `patente`, 
                        `marca`, 
                        `modelo`, 
                        `ano`, 
                        `tipo`, 
                        `horometro`, 
                        `motor`, 
                        `codigoInterno`, 
                        `fecha`, 
                        `imgInforme`, 
                        `imgHDS`,
                        `imgPlaca`, 
                        `imgRTecnica`, 
                        `imgPadron`, 
                        `imgPCirculacion`, 
                        `imgSoap`, 
                        `imgFrente`, 
                        `imgIzquierdo`, 
                        `imgDerecho`, 
                        `imgTrasera`, 
                        `imgPEmergencia`, 
                        `imgCorriente`, 
                        `imgExtintor`, 
                        `imgOtros`, 
                        `evaluador`, 
                        `resultado`, 
                        `fechaInforme`, 
                        `userInforme`, 
                        `lugar`
                    ) VALUES (
                        '$IdOper', 
                        '$folio', 
                        '$equipo', 
                        '$patente', 
                        '$marca', 
                        '$modelo', 
                        '$year', 
                        '$tipo', 
                        '$horometro', 
                        '$motor', 
                        '$codigo', 
                        '$fecha ', 
                        '$rutaInforme', 
                        '$rutaHDS',
                        '$rutaPlaca', 
                        '$rutaRevisionTecnica', 
                        '$rutaPadron', 
                        '$rutaPCirculacion', 
                        '$rutaSoap', 
                        '$rutaFrente', 
                        '$rutaIzquierdo', 
                        '$rutaDerecho', 
                        '$rutaTrasera', 
                        '$rutaPEmergencia', 
                        '$rutaCorriente', 
                        '$rutaExtintor', 
                        '$rutaOtros', 
                        '$evaluador',
                        '$resultado', 
                        '$FechaSave', 
                        '$user_id',
                        '$lugar'
                    )");
            
                // Actualizar estado de la OT
                $update = mysqli_query($conn, "UPDATE detallle_ot SET informe = '$resultado' WHERE id = '$IdOper'");
            
                if ($query) {
                    // Verificar el número de filas afectadas
                    $filas_afectadas = mysqli_affected_rows($conn);
            
                    if ($filas_afectadas > 0) {
                        // Los datos se guardaron correctamente
                        echo 'success';
                    } else {
                        // No se guardaron datos
                        echo 'error';
                    }
                } else {
                    // Error en la consulta
                    echo 'error';
                }
            
                } else {
                    echo "No se pudieron mover todos los archivos correctamente.";
                }
            
            } else {
                echo "No se han enviado archivos.";
            }
        }
    }
}
?>
