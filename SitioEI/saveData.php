<?php
session_start();
require_once('../admin/conex.php');

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
$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d H:i:s");

if (isset($_POST['id_oper'])) {
    $id_oper = mysqli_real_escape_string($conn, $_POST['id_oper']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $region = mysqli_real_escape_string($conn, $_POST['regiones']);
    $comuna = mysqli_real_escape_string($conn, $_POST['comunas']);
    $banco = mysqli_real_escape_string($conn, $_POST['banco']);
    $tipo_cta = mysqli_real_escape_string($conn, $_POST['tipoCta']);
    $cta = $_POST['cta'];

    $verificar = mysqli_prepare($conn, "SELECT id FROM `insp_eva` WHERE id = ?");
    mysqli_stmt_bind_param($verificar, "s", $id_oper);
    mysqli_stmt_execute($verificar);
    mysqli_stmt_store_result($verificar);

    if (mysqli_stmt_num_rows($verificar) > 0) {
        // La ID existe, puedes realizar las acciones que necesitas aquí.

        // Actualiza la tabla con sentencia preparada
        $updateQuery = mysqli_prepare($conn, "UPDATE insp_eva 
                            SET correo = ?, 
                                telefono = ?, 
                                direccion = ?,
                                comuna = ?,
                                region = ?,
                                banco = ?,
                                tipocta = ?,
                                cta = ?
                            WHERE id = ?");

        mysqli_stmt_bind_param($updateQuery, "ssssssssi", $correo, $telefono, $direccion, $comuna, $region, $banco, $tipo_cta, $cta, $id_oper);

        if (mysqli_stmt_execute($updateQuery)) {
            echo "Los datos se han actualizado correctamente.";
        } else {
            echo "Error al actualizar los datos: " . mysqli_error($conn);
        }
    } else {
        echo "La ID no existe en la base de datos.";
    }
    mysqli_stmt_close($verificar);
    mysqli_stmt_close($updateQuery);
} else {
    echo "No se recibieron todos los datos esperados.";
}

mysqli_close($conn);
?>