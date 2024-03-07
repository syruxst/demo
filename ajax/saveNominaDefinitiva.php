<?php
// Incluir la conexión a la base de datos
require_once('../admin/conex.php');
// Primera fila de datos
$emp = $_POST['emp'];
$faena = $_POST['faena'];
$cot = $_POST['cot'];
$contact = $_POST['contact'];
$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

// datos para enviar correo
$email = $_POST['para'];
$cc = $_POST['cc'];
$asunto = $_POST['asunto'];

// Otros datos
$folio = $_POST['folio'];
$titulo = 'nomina';
$idOper = $_POST['operadores'];
$IdOperadores = $_POST['IdOperadores'];
$idArray = explode(",", $IdOperadores);
$estado = '2';
$statusNomina = "AUTORIZADA";

$timezone = new DateTimeZone(date_default_timezone_get()); // Obtiene la zona horaria local
$date = new DateTime('now', $timezone); // Crea una instancia de DateTime con la fecha y hora actual y la zona horaria local
$localTime = $date->format('Y-m-d'); // Obtiene la fecha y hora local formateada
$fechaFinal = $date->format('Y-m-d'); // Obtiene la fecha y hora local formateada

$buscarNomina = mysqli_query($conn, "SELECT * FROM nomina WHERE id_nomina = '$folio'");

$response = [];
if (empty(trim($folio))) {
    $response = [
        'success' => false,
        'message' => 'El campo folio está vacío.'
    ];
    // Devolver la respuesta en formato JSON y salir del script
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} 

// Verificar si algún valor está vacío o contiene solo espacios en blanco
if (empty(trim($emp)) || empty(trim($faena)) || empty(trim($cot)) || empty(trim($contact))) {
    $response = [
        'success' => false,
        'message' => 'Alguno de los campos está vacío.'
    ];
    // Devolver la respuesta en formato JSON y salir del script
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    if (mysqli_num_rows($buscarNomina) > 0) {
        // Registro existente, procede a actualizar
        $sql = "UPDATE nomina SET titulo = ?, date = ?, empresa = ?, faena = ?, cotizacion = ?, contact = ?, date_in = ?, date_end = ?, para = ?, cc = ?, asunto = ?, fecha = ?, estado = ? WHERE id_nomina = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssssssi", $titulo, $localTime, $emp, $faena, $cot, $contact, $inicio, $fin, $email, $cc, $asunto, $fechaFinal, $statusNomina, $folio);
    
        // Ejecutar la consulta SQL de inserción
        if ($stmt->execute()) {
            // Actualizar la tabla 'operadores'
            foreach ($idArray as $id) {
                $sqlO = "UPDATE operadores SET trabajando = ?, empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?, job = ? WHERE Id = ?";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ississii", $estado, $emp, $faena, $lastInsertId, $inicio, $fin, $estado, $id);
                $stmtO->execute();
            }
            // Verificar el número de filas afectadas por la operación de inserción
            if ($stmt->affected_rows > 0) {
                // Los datos se han guardado correctamente
                $response = [
                    'success' => true,
                    'message' => 'Los datos se han guardado correctamente.'
                ];
            } else {
                // No se insertó ninguna fila, probablemente debido a una violación de restricción de clave única u otro error
                $response = [
                    'success' => false,
                    'message' => 'Hubo un error al guardar los datos. Intenta nuevamente.'
                ];
            }
        }    
    } else {
        // Preparar la consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO nomina (titulo, date, empresa, faena, cotizacion, contact, date_in, date_end, para, cc, asunto, fecha, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssssss", $titulo, $localTime, $emp, $faena, $cot, $contact, $inicio, $fin, $email, $cc, $asunto, $fechaFinal, $statusNomina);

        // Ejecutar la consulta SQL de inserción
        if ($stmt->execute()) {
            $lastInsertId = $stmt->insert_id;
            // Actualizar la tabla 'operadores'
            if (strpos($idOper, ',') === false) {
                // Solo un valor en la cadena, ejecutar la actualización
                $sqlO = "UPDATE operadores SET trabajando = ?, empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?, job = ? WHERE Id = ?";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ississii", $estado, $emp, $faena, $lastInsertId, $inicio, $fin, $estado, $idOper);
                $stmtO->execute();
            } else {
                // Más de un valor en la cadena, ejecutar código adicional
                $idArray = explode(",", $idOper);
                $idList = implode(",", $idArray);
                $sqlO = "UPDATE operadores SET trabajando = ?, empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?, job = ? WHERE Id IN ($idList)";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("isssssi", $estado, $emp, $faena, $lastInsertId, $inicio, $fin, $estado);
                $stmtO->execute();
            }
            // Verificar el número de filas afectadas por la operación de inserción
            if ($stmt->affected_rows > 0) {
                // Los datos se han guardado correctamente
                $response = [
                    'success' => true,
                    'message' => 'Los datos se han guardado correctamente.'
                ];
            } else {
                // No se insertó ninguna fila, probablemente debido a una violación de restricción de clave única u otro error
                $response = [
                    'success' => false,
                    'message' => 'Hubo un error al guardar los datos. Intenta nuevamente.'
                ];
            }
        } else {
            // Si se produce un error durante la ejecución de la consulta
            $response = [
                'success' => false,
                'message' => 'Hubo un error al guardar los datos. Intenta nuevamente.'
            ];
        }
    }



    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
    // Cerrar la conexión
    $stmtO->close();
    $stmt->close();
    $conn->close(); 
?>