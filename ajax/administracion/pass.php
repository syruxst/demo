<?php
require_once('../../admin/conex.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la contraseña del formulario
    $usuario = $_POST['usuario'];
    $claveweb = $_POST['claveweb'];

    // Encriptar la contraseña usando password_hash
    $hashedPassword = $_POST['claveweb'];

    // Mostrar el hash de la contraseña
    echo "Hash de la contraseña: " . $hashedPassword;

    // Luego, puedes guardar $hashedPassword en tu base de datos.
    $SQL=mysqli_query($conn, "UPDATE `usuarios` SET clave_usuario = PASSWORD('$hashedPassword') WHERE usuario = '$usuario'");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="text" name="usuario">
        <input type="text" name="claveweb">
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
