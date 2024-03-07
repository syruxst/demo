<?php
require_once('../admin/conex.php');

if (isset($_POST['correo'])) {

    function generateRandomPassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $password;
    }
    
    // Ejemplo de uso para generar una contraseña aleatoria de 12 caracteres
    $randomPassword = generateRandomPassword();
    
    $run = $_POST['correo'];
    $query = mysqli_query($conn, "SELECT * FROM `operadores` WHERE rut = '$run'");
    
    if ($query) {
        // Verifica si se encontró una fila
        if (mysqli_num_rows($query) > 0) {
            // Se encontró una fila, realiza la acción aquí
            $row = mysqli_fetch_array($query);
            // Realiza tu acción aquí, por ejemplo, imprimir los datos
            $correo = $row['email'];
            $sql = mysqli_query($conn, "UPDATE `operadores` SET `clave_web` = PASSWORD('$randomPassword') WHERE `operadores`.`rut` = '$run'");
            if ($sql) {
                if (enviarEmail($correo, $randomPassword)) {
                    echo "exito";
                } else {
                    echo "Error al enviar el correo";
                }
            } else {
                echo "Error al actualizar la contraseña";
            }
        } else {
            // No se encontraron filas, puedes mostrar un mensaje de que no se encontraron resultados
            echo "No se encontraron resultados para el correo: " . $run;
        }
    } else {
        // Ocurrió un error en la consulta SQL
        echo "Error en la consulta: " . mysqli_error($conn);
    }
} else {
    echo "error";
}

function enviarEmail($correo, $randomPassword) {
    require_once('../ajax/PHPMailer/PHPMailer.php');
    require_once('../ajax/PHPMailer/SMTP.php');
    require_once('../ajax/PHPMailer/Exception.php');

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
    $mail->Subject = 'Restablecer contraseña';
    $body = 'Se ha enviado una solicitud para restablece su contraseña. 
    <br> Nueva Contraseña : ' . $randomPassword . ' 
    <br> Linck de acceso : <a href="https://operamaq.cl/nuevo/ajax/login.php"> <b>pinchar aquí</b></a>.<br>';
    $body .= '<br><hr>Saluda Atte
    <br> <img src="https://operamaq.cl/nuevo/img/pieOficina.jpg" alt="Logo Operamaq" width="50%">';

    $mail->Body = $body;

    // Enviar el correo
    return $mail->send();
}

?>