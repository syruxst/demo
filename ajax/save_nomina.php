<?php
// Incluir la conexión a la base de datos
require_once('../admin/conex.php');

// Primera fila de datos
$emp = $_POST['emp'];
$faena = $_POST['faena'];
$cot = $_POST['cot'];
$contact = $_POST['contact'];
$inicio = $_POST['inicio']; // Inicio de faena
$fin = $_POST['fin']; // Fin de faena   

// datos para enviar correo
$email = $_POST['para'];
$cc = $_POST['cc'];
$asunto = $_POST['asunto'];

// Otros datos
$folio = $_POST['folio'];
$titulo = $_POST['titulo'];
//aqui recibo la id del operador 
$idOper = $_POST['operadores'];
$statusNomina = "PENDIENTE";

$timezone = new DateTimeZone(date_default_timezone_get()); // Obtiene la zona horaria local
$date = new DateTime('now', $timezone); // Crea una instancia de DateTime con la fecha y hora actual y la zona horaria local
$localTime = $date->format('Y-m-d'); // Obtiene la fecha y hora local formateada

$buscarNomina = mysqli_query($conn, "SELECT * FROM nomina WHERE id_nomina = '$folio'");

$response = [];

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
        $sql = "UPDATE nomina SET titulo = ?, date = ?, empresa = ?, faena = ?, cotizacion = ?, contact = ?, date_in = ?, date_end = ?, para = ?, cc = ?, asunto = ?, estado = ? WHERE id_nomina = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssisssssssi", $titulo, $localTime, $emp, $faena, $cot, $contact, $inicio, $fin, $email, $cc, $asunto, $statusNomina, $folio);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Actualizar la tabla 'operadores'
            if (strpos($idOper, ',') === false) {
                // Solo un valor en la cadena, ejecutar la actualización
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?  WHERE Id = ?";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssissi", $emp, $faena, $folio, $inicio, $fin, $idOper);
                $stmtO->execute();
                $stmtO->close();
            } else {
                // Más de un valor en la cadena, ejecutar código adicional
                $idArray = explode(",", $idOper);
                $idList = implode(",", $idArray);
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?  WHERE Id IN ($idList)";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssiss", $emp, $faena, $folio, $inicio, $fin);
                $stmtO->execute();
                $stmtO->close();
            }

            // La tabla se actualizó correctamente
            $response = [
                'success' => true,
                'message' => 'La tabla se actualizó correctamente.'
            ];
        } else {
            // Actualizar la tabla 'operadores'
            if (strpos($idOper, ',') === false) {
                // Solo un valor en la cadena, ejecutar la actualización
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?  WHERE Id = ?";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssissi", $emp, $faena, $folio, $inicio, $fin, $idOper);
                $stmtO->execute();
                $stmtO->close();
                $response = [
                    'success' => true,
                    'message' => 'Los operadores han sido agregados en la Nomina'
                ];
            } else {
                // Más de un valor en la cadena, ejecutar código adicional
                $idArray = explode(",", $idOper);
                $idList = implode(",", $idArray);
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ?  WHERE Id IN ($idList)";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssiss", $emp, $faena, $folio, $inicio, $fin);
                $stmtO->execute();
                $stmtO->close();
                $response = [
                    'success' => true,
                    'message' => 'Los operadores han sido agregados en la Nomina'
                ];
            }
        }
    } else {
        // Preparar la consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO nomina (titulo, date, empresa, faena, cotizacion, contact, date_in, date_end, para, cc, asunto, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssisssssss", $titulo, $localTime, $emp, $faena, $cot, $contact, $inicio, $fin, $email, $cc, $asunto, $statusNomina);

        // Ejecutar la consulta SQL de inserción
        if ($stmt->execute()) {
            $lastInsertId = $stmt->insert_id;
            // Actualizar la tabla 'operadores'
            if (strpos($idOper, ',') === false) {
                // Solo un valor en la cadena, ejecutar la actualización
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ? WHERE Id = ?";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssissi", $emp, $faena, $lastInsertId, $inicio, $fin, $idOper);
                $stmtO->execute();
                $stmtO->close();
            } else {
                // Más de un valor en la cadena, ejecutar código adicional
                $idArray = explode(",", $idOper);
                $idList = implode(",", $idArray);
                $sqlO = "UPDATE operadores SET empresa = ?, faena = ?, id_nomina = ?, date_inicio = ?, date_termino = ? WHERE Id IN ($idList)";
                $stmtO = $conn->prepare($sqlO);
                $stmtO->bind_param("ssiss", $emp, $faena, $lastInsertId, $inicio, $fin);
                $stmtO->execute();
                $stmtO->close();
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

    $stmt->close();
    $conn->close();

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>