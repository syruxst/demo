<?php
session_start();
error_reporting(0);
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../login.php");
    exit();
}

require_once('../admin/conex.php');

// Función para generar contraseñas automáticas
function generarContraseña($longitud = 12) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $contraseña = '';

    for ($i = 0; $i < $longitud; $i++) {
        $indice = rand(0, strlen($caracteres) - 1);
        $contraseña .= $caracteres[$indice];
    }

    return $contraseña;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rut = $_POST["inRut"];
    $nombre = ucwords(strtolower($_POST["inNombre"]));
    $apellido = ucwords(strtolower($_POST["inApellido"]));
    $correo = $_POST["inMail"];

    $contraseñaAutomatica = generarContraseña(12);

    $sql_check = "SELECT rut FROM operadores WHERE rut = '$rut' LIMIT 1";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "El correo ya ha sido registrado previamente.";
    } else {
        $sql = "INSERT INTO operadores (rut, nombre, apellidos, email, clave_web, web) VALUES ('$rut', '$nombre', '$apellido', '$correo', PASSWORD('$contraseñaAutomatica'), 'NO')";
        
        if ($conn->query($sql) === TRUE) {
            enviarEmail($rut, $correo, $contraseñaAutomatica);
            echo "Datos guardados en la base de datos correctamente";
        } else {
            echo "Error al guardar los datos: " . $conn->error;
        }
    }

    $conn->close();
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Método no permitido";
}

function enviarEmail($rut, $correo, $contraseñaAutomatica) {
    require_once('PHPMailer/PHPMailer.php');
    require_once('PHPMailer/SMTP.php');
    require_once('PHPMailer/Exception.php');

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'oficina.tecnica@operamaq.cl';
    $mail->Password = '0FicinaTech2023%';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Destinatarios
    $mail->setFrom('oficina.tecnica@operamaq.cl', 'Operamaq Empresa Spa');
    $mail->addAddress($correo);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Registro de ingreso a Operamaq Empresa Spa';
    $body = 'Por la presente informo a usted, que se han ingresado sus datos al sistema Operamaq Empresa Spa, para gestionar su evaluación y/o certificación correspondiente. 
    <br> Sus datos de acceso son los siguientes:
    <br> Usuario : ' . $rut . ' 
    <br> Contraseña : ' . $contraseñaAutomatica . ' 
    <br> Linck de acceso : <a href="https://acreditasys.tech/ajax/login.php"> <b>pinchar aquí</b></a>.<br>';
    $body .= '<br><hr>Saluda Atte
    <br> <img src="https://acreditasys.tech/img/FirmaDeCorreoOT.png" alt="Logo Operamaq" width="50%">';

    $mail->Body = $body;

    // Enviar el correo
    return $mail->send();
}
?>