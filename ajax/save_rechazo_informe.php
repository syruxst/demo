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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $textObs = isset($_POST['textObs']) ? $_POST['textObs'] : null;
    $textObs = mysqli_real_escape_string($conn, $textObs);
    $textObs = htmlspecialchars($textObs, ENT_QUOTES, 'UTF-8');

    $Sql = "SELECT * FROM `informesM` WHERE IdOper = '$data'";
    $Rst = mysqli_query($conn, $Sql);
    $Ver = mysqli_fetch_array($Rst);
    $userInforme = $Ver['userInforme'];

    $useInfo = "SELECT * FROM `insp_eva` WHERE user = '$userInforme'";
    $userRst = mysqli_query($conn, $useInfo);
    $userVer = mysqli_fetch_array($userRst);
    $correo = $userVer['correo'];

    $sql = mysqli_query($conn, "UPDATE detallle_ot SET informe = '' WHERE id = '$data'"); // Corregir el nombre de la variable

    $del = $conn->prepare("DELETE FROM informesM WHERE IdOper = ?");
    $del->bind_param("i", $data);
    $del->execute();

    require_once('PHPMailer/PHPMailer.php');
    require_once('PHPMailer/SMTP.php');
    require_once('PHPMailer/Exception.php');

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'venta@operamaq.cl';
    $mail->Password = 'Operamaq2023#';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    //Destinatarios
    $mail->setFrom('venta@operamaq.cl', 'Operamaq Empresa Spa');
    $mail->addAddress('daniel.ugalde@operamaq.cl');
    $mail->addAddress('cristhian.baez@operamaq.cl');
    $mail->addAddress($correo);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Se ha Rechazado la evidencia del folio N° ' . $Ver['folio'];
    $body = 'Por favor debe realizar el informe nuevamente. <br>
    Observaciones del rechazo: ' . $textObs . '
    <hr><img src="https://acreditasys.tech/img/FirmaDeCotizacion.png" alt="Logo Operamaq" width="80%">';
    $mail->Body = $body;

    // Enviar el correo
    if ($mail->send()) {
        $response['success'] = 'El informe ha sido eliminado correctamente.';
    } else {
        $response['error'] = 'No se pudo eliminar el informe';
    }
}

echo json_encode($response);
?>