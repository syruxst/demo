<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../index.php");
    exit();
}
$year = date("Y");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener datos de la solicitud POST
    $datos = $_POST['data'];
    //print_r($datos);
    $titulos = array("certificacion_op", "evaluacion", "suministros_op", "inspeccion", "modelo_adicional");

    foreach ($datos as $cabecera => $items) {
        // Recorrer los elementos dentro de cada cabecera
        foreach ($items as $hiddenValue => $values) {
            // Obtener los valores específicos
            $textValue = $values['text'];
    
            // Construir parte de la consulta SQL para actualizar el valor en la base de datos
            $query = "UPDATE `kpi_table` SET `$cabecera` = '$textValue' WHERE `mes` = '$hiddenValue'";
            
            // Ejecutar la consulta SQL
            $result = mysqli_query($conn, $query);
    
            // Verificar si la consulta se ejecutó con éxito
            if ($result) {
                $response = array(
                    'status' => 'success', // o 'error'
                    'message' => 'Los KPI han sido actualizado correctamente'
                );
            } else {
                    // Manejar casos donde el método de solicitud no es POST
                $response = array(
                    'status' => 'error', // o 'error'
                    'message' => 'Algo salio mal al guardar los KPI'
                );
            }
        }
    }
 

} else {
    // Manejar casos donde el método de solicitud no es POST
    $response = array(
        'status' => 'error', // o 'error'
        'message' => 'Algo salio mal al guardar los KPI'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
?>