<?php
// Establecer el nivel de error para mostrar errores durante el desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../admin/conex.php');

// Verificar si se recibió el parámetro "ot" en la solicitud POST
if (isset($_POST['ot'])) {
    // Obtener el valor del parámetro "ot" de manera segura
    $ot = mysqli_real_escape_string($conn, $_POST['ot']);

    // Utilizar una sentencia preparada para evitar inyección SQL
    $stmt = $conn->prepare("UPDATE `ot` SET estado = 'PROCESO' WHERE id_ot = ?");
    $stmt->bind_param("s", $ot);

    if ($stmt->execute()) {
        echo "Registro actualizado con éxito";
    } else {
        echo "Error al actualizar el registro: " . $stmt->error;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Si no se recibió el parámetro "ot", devuelve un mensaje de error
    echo "Parámetro 'ot' no proporcionado";
}
?>