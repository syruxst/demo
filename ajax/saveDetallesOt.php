<?php
session_start();
error_reporting(0);

// Establecer la zona horaria a Santiago, Chile
date_default_timezone_set('America/Santiago');

// Obtener la fecha actual
$fecha_creacion = new DateTime();
$fecha_creacion = $fecha_creacion->format('Y-m-d');

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../login.php");
    exit();
}

require_once('../admin/conex.php');

// Verificamos si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibimos los datos JSON y los decodificamos en un arreglo asociativo
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificamos si los datos se decodificaron correctamente
    if ($data !== null) {
        // Accedemos a los datos individualmente
        $cliente = $data['cliente'];
        $faena = $data['faena'];   
        $contacto = $data['contacto'];
        $telefono = $data['telefono'];
        $email = $data['mail'];
        $id_ot = $data['id_ot'];
        $rut = $data['rut'];
        $nombre = $data['nombre'];
        $status = $data['status'];
        $equipo = $data['equipo'];
        $modelo = $data['modelo'];
        $eva = $data['eva'];
        $familia = $equipo . ',';
        $fecha = $data['fecha'];
        $mes_actual = date('m');
        $ano_actual = date('Y');
        $dos_digitos_ano = substr($ano_actual, -2);
        $buscarDatos = mysqli_query($conn, "SELECT * FROM `operadores` WHERE rut = '$rut'");
        $rows = mysqli_fetch_array($buscarDatos);
        $correo = $rows['email'];
        $licencia = $rows['foto_licencia'];
        $cv = $rows['cv'];

        $validar = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE rut = '$rut' AND equipo = '$equipo' AND id_ot = '$id_ot'");

        if ($validar) {
            // Verificar si hay al menos una fila devuelta
            if (mysqli_num_rows($validar) > 0) {
                $response = [
                    'status' => 'info',
                    'message' => 'El Operador ya existe.',
                    'data' => $data
                ];
        
                echo json_encode($response);
            } else {
                $sql = "INSERT INTO `detallle_ot` (id_ot, rut, nombre, status, equipo, modelo, ip, fecha, contacto, fono, mail, empresa, faena, fecha_creacion, user_creacion) VALUES ('$id_ot', '$rut', '$nombre', '$status', '$equipo', '$modelo', '$eva', '$fecha', '$contacto', '$telefono', '$email', '$cliente', '$faena', '$fecha_creacion', '$usuario')";
                $Sqli = "UPDATE `operadores` SET `empresa` = '$cliente', `faena` = '$faena', `familia` = CONCAT(`familia`, '$familia') WHERE `rut` = '$rut'";
                $conn->query($Sqli);
                if ($conn->query($sql) === TRUE) {
        
                    $last_inserted_id = $conn->insert_id;
                    $folio = $mes_actual.$dos_digitos_ano.$id_ot."-".$last_inserted_id;
        
                    $actualizarFOlio = mysqli_query($conn, "UPDATE `detallle_ot` SET `folio` = '$folio' WHERE `id` = '$last_inserted_id'");
        
                    enviarEmail($rut, $correo);
                    $response = [
                        'status' => 'success',
                        'message' => 'Los datos ha sido guardado con exito!.',
                        'data' => $data
                    ];
            
                    echo json_encode($response);
                }else{
                    $response = [
                        'status' => 'error',
                        'message' => 'Ocurrio un problema al guardar los datos!.'
                    ];
            
                    echo json_encode($response);
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error al verificar los datos'
            ];
        }

    } else {
        // Si no se pudieron decodificar los datos JSON correctamente
        $response = [
            'status' => 'error',
            'message' => 'Error al decodificar los datos JSON.'
        ];

        echo json_encode($response);
    }
} else {
    // Si no se recibió una solicitud POST válida
    $response = [
        'status' => 'error',
        'message' => 'Solicitud no válida.'
    ];

    echo json_encode($response);
}

function enviarEmail($rut, $correo) {
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
    $mail->Subject = 'Evaluación y/o Certificación';
    $body = 'Por la presente informo a usted, que se encuentra disponible para iniciar su proceso de acreditación y/o certificación. 
    <br> Linck de acceso : <a href="https://acreditasys.tech/ajax/login.php"> <b>pinchar aquí</b></a>.
    <br> Manual de uso : <a href="https://acreditasys.tech/Manual_Plataforma_Operador.pdf"> <b> Ver Manual</b></a>.';
    $body .= '<br><hr>Saluda Atte
    <br> <img src="https://acreditasys.tech/img/pieOficina.jpg" alt="Logo Operamaq" width="50%">';

    $mail->Body = $body;

    // Enviar el correo
    return $mail->send();
}
?>