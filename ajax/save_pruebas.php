<?php
session_start();
require_once('../admin/conex.php');

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: ../login.php");
    exit();
}

$pregunta = $_POST['pregunta'];
$respuesta1 = $_POST['respuesta1'];
$respuesta2 = $_POST['respuesta2'];
$respuesta3 = $_POST['respuesta3'];
$respuesta4 = $_POST['respuesta4'];
$respuestaCorrecta = $_POST['respuestaCorrecta'];
$tabla = $_POST['tabla'];
$codigo = $_POST['codigo'];
$codigo = strtoupper($codigo);
$ucl = $_POST['ucl'];
$ucl = strtoupper($ucl);
$tipo = $_POST['tipo'];
$estado = $_POST['estado'];

$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d H:i:s");
$versiones = 1;

// Consulta SQL para verificar si la tabla existe
$checkTableSql = "SHOW TABLES LIKE '$tabla'";
$result = $conn->query($checkTableSql);

if ($result->num_rows == 0) {
    // La tabla no existe, entonces la creamos
    $createTableSql = "CREATE TABLE `u992209295_demo`.`$tabla` (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `PREGUNTA` VARCHAR(500) NOT NULL ,
        `R1` VARCHAR(500) NOT NULL ,
        `R2` VARCHAR(500) NOT NULL ,
        `R3` VARCHAR(500) NOT NULL ,
        `R4` VARCHAR(500) NOT NULL ,
        `id_respuesta_correcta` INT NOT NULL ,
        `fecha` DATETIME NOT NULL ,
        `versiones` VARCHAR(10) NOT NULL ,
        `codigo` VARCHAR(19) NOT NULL,
        `ucl` VARCHAR(19) NOT NULL,
        `tipo` VARCHAR(11) NOT NULL,
        `item` VARCHAR(100) NOT NULL,
        `estado` VARCHAR(13) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;";
    
    if ($conn->query($createTableSql) === TRUE) {
        // La tabla se creó correctamente, ahora insertamos los datos
        insertData($conn, $tabla);
    } else {
        echo "Error al crear la tabla: " . $conn->error;
    }
} else {
    // La tabla ya existe, simplemente insertamos los datos
    insertData($conn, $tabla);
}

// Función para insertar datos en la tabla
function insertData($conn, $tabla) {
    global $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuestaCorrecta, $fecha, $versiones, $ucl, $tipo, $codigo, $estado;
    
    $insert = "INSERT INTO $tabla (PREGUNTA, R1, R2, R3, R4 , id_respuesta_correcta, fecha, versiones, ucl, tipo, codigo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sssssissssss", $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuestaCorrecta, $fecha, $versiones, $ucl, $tipo, $codigo, $estado);
    
    if ($stmt->execute()) {
        echo "Datos guardados correctamente";
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>