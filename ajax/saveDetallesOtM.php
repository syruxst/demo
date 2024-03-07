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
        $equipo = $data['equipo'];
        $fecha = $data['fecha'];
        $ip = $data['ip'];
        $patente = $data['patente'];

        $mes_actual = date('m');
        $ano_actual = date('Y');
        $dos_digitos_ano = substr($ano_actual, -2);

        $sql = "INSERT INTO `detallle_ot` (
            id_ot, 
            equipo, 
            resultado,
            ip, 
            fecha, 
            contacto, 
            fono, 
            mail, 
            empresa, 
            faena, 
            fecha_creacion, 
            user_creacion,
            doc,
            patente) VALUES ('
            $id_ot', 
            '$equipo', 
            'APROBADO',
            '$ip', 
            '$fecha', 
            '$contacto', 
            '$telefono', 
            '$email', 
            '$cliente', 
            '$faena', 
            '$fecha_creacion', 
            '$usuario',
            'SI',
            '$patente')";

        if ($conn->query($sql) === TRUE) {

            $last_inserted_id = $conn->insert_id;
            $folio = $mes_actual.$dos_digitos_ano.$id_ot."-".$last_inserted_id;

            $actualizarFOlio = mysqli_query($conn, "UPDATE `detallle_ot` SET `folio` = '$folio' WHERE `id` = '$last_inserted_id'");

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
}


?>