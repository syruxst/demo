<?php
// Incluir la conexión a la base de datos
require_once('../admin/conex.php');

// Obtén los datos enviados a través de la solicitud Ajax
echo $operadorId = $_POST['operadorId'];
echo $date_disp = $_POST['date_disp'];
echo $selectOper = $_POST['selectOper'];
echo $valid = $_POST['valid'];
echo $sueldo = $_POST['sueldo'];

$sql = "UPDATE operadores SET suedo = ?, date_disp = ?, selectOper = ?, valid = ? WHERE Id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $sueldo, $date_disp, $selectOper, $valid, $operadorId);

$stmt->execute();

$response = [];

if ($stmt->affected_rows > 0) {
    $response = [
        'success' => true,
        'message' => 'Los datos se han guardado correctamente.'
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Hubo un error al guardar los datos. Intenta nuevamente.'
    ];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
$stmt->close();
?>
