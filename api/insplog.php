<?php session_start();
    if (isset($_POST['usuario']) && isset($_POST['pass'])) {
        require_once('../admin/conex.php');
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['pass'];
        // Utilizamos una sentencia preparada con marcadores de posición (?)
        $query = "SELECT * FROM insp_eva WHERE user = ? AND pass = PASSWORD(?)";
        $stmt = mysqli_prepare($conn, $query);
        // Vinculamos los valores a los marcadores de posición de la sentencia preparada
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $contraseña);
        // Ejecutamos la consulta preparada
        mysqli_stmt_execute($stmt);
        // Obtenemos el resultado de la consulta
        $result = mysqli_stmt_get_result($stmt);
        // Verificamos el número de filas obtenidas en el resultado
        $num_filas = mysqli_num_rows($result);
        if ($num_filas > 0) {
            $_SESSION['usuario'] = $usuario;
            header("Location: ../SitioEI");
            exit();
        } else {
            $_SESSION['error'] = "El usuario no existe";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    }
?>
