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

    $valid = mysqli_query($conn, "SELECT IdOper FROM `informes` WHERE IdOper = '$IdOper'");

    if (mysqli_num_rows($valid) > 0) {
            echo 'info';
    } else {
        $folio = $_POST['folio'];

        // buscar datos de la OT

        $Query = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id = '$IdOper'");
        $Row = mysqli_fetch_array($Query);
        $EvIp = $Row['ip'];
        $Folio = $EvIp;

        $equipo = $_POST['equipo'];
        $FechaEvaluacion = $_POST['fecha'];
        $nombre_candidato = $_POST['nombre_candidato'];
        $rut = $_POST['rut'];
        $evaluador = $_POST['evaluador'];
        $observaciones = $_POST['obs'];
        $resultado = $_POST['resultado'];
        $porcentaje = $_POST['por'];
        $puntaje = $_POST['puntaje'];
    
        $radio1 = $_POST['1'];
        $radio2 = $_POST['2'];
        $radio3 = $_POST['3'];
        $radio4 = $_POST['4'];
        $radio5 = $_POST['5'];
        $radio6 = $_POST['6'];
        $radio7 = $_POST['7'];
        $radio8 = $_POST['8'];
        $radio9 = $_POST['9'];
    
        $select1 = $_POST['op1'];
        $select2 = $_POST['op2'];
        $select3 = $_POST['op3'];
        $select4 = $_POST['op4'];
        $select5 = $_POST['op5'];
        $select6 = $_POST['op6'];
        $select7 = $_POST['op7'];
        $select8 = $_POST['op8'];
        $select9 = $_POST['op9'];

        $lugar = $_POST['lugar'];
    
        $photos = [];
        for ($i = 1; $i <= 3; $i++) {
            $photo = $_FILES["photography$i"];
            $photos[] = moveUploadedFile($photo, "evidencia/");
        }
    
        if (in_array(false, $photos)) {
            echo 'Error uploading files.';
        } else {
            $insertQuery = "INSERT INTO informes (
                IdOper,
                folio, 
                equipo, 
                fecha, 
                nombre_candidato, 
                rut, 
                evaluador, 
                radio1, 
                radio2, 
                radio3, 
                radio4, 
                radio5, 
                radio6, 
                radio7, 
                radio8, 
                radio9, 
                select1, 
                select2, 
                select3, 
                select4, 
                select5, 
                select6, 
                select7, 
                select8, 
                select9, 
                observaciones, 
                resultado, 
                porcentaje,
                puntaje,
                photography1, 
                photography2, 
                photography3, 
                fechaInforme, 
                userInforme,
                lugar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
    
            $stmt->bind_param("issssssssssssssssssssssssssssssssss",$IdOper, $Folio, $equipo, $FechaEvaluacion, $nombre_candidato, $rut, $evaluador, $radio1, $radio2, $radio3, $radio4, $radio5, $radio6, $radio7, $radio8, $radio9, $select1, $select2, $select3, $select4, $select5, $select6, $select7, $select8, $select9, $observaciones, $resultado, $porcentaje, $puntaje, $photos[0], $photos[1], $photos[2], $FechaSave, $user_id, $lugar);
    
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
            
                echo 'success';
            // Buscar ultimos datos del operador
            $query = mysqli_query($conn, "SELECT nombre_archivo, foto_licencia FROM `operadores` WHERE rut = '$rut'");
            $row = mysqli_fetch_array($query);
            $nombre_archivo = $row['nombre_archivo'];
            $foto_licencia = $row['foto_licencia'];

            // Actualizar estado de la OT
            $update = "UPDATE detallle_ot SET img_1 = '$photos[0]', img_2 = '$photos[1]', img_3 = '$photos[2]', informe = '$resultado', porcentaje = '$porcentaje', puntaje = '$puntaje', licencia = '$foto_licencia', cv = '$nombre_archivo' WHERE id = '$IdOper'";
            $Stmt = $conn->prepare($update);
            $Stmt->execute();

            } else {
                echo 'error';
            }
            
            if($resultado == 'RECHAZADO') {
                $fechaHoraActual = date('dmYHis');
                $contenidoAEncriptar = $user_id . $fechaHoraActual;
    
                $hash = md5($contenidoAEncriptar);
    
                $qr = $hash;
                $guardar = mysqli_query($conn, "UPDATE detallle_ot SET estado = 'RECHAZADO', qr = '$qr', fecha_arprob = '$FechaSave' WHERE id = '$IdOper' ");
            }

            $stmt->close();
            $conn->close();
        }
    }

} else {
    echo 'Solicitud no válida.';
}

function moveUploadedFile($file, $targetFolder) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false; 
    }

    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return false; 
    }
    
    $acceptedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($extension), $acceptedExtensions)) {
        return false; 
    }

    $fileName = generateUniqueFileName($targetFolder, $extension);
    move_uploaded_file($file['tmp_name'], $targetFolder . $fileName);
    return $fileName;
}

function generateUniqueFileName($targetFolder, $extension) {
    $fileName = sha1(uniqid(mt_rand(), true)) . ".$extension";
    while (file_exists($targetFolder . $fileName)) {
        $fileName = sha1(uniqid(mt_rand(), true)) . ".$extension";
    }
    return $fileName;
}
?>