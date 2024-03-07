<?php
ob_start();
session_start();
require_once('../admin/conex.php');

// Validar y sanitizar los parámetros de entrada
$data = isset($_GET['data']) ? mysqli_real_escape_string($conn, $_GET['data']) : '';// rut
$E = isset($_GET['E']) ? mysqli_real_escape_string($conn, $_GET['E']) : '';// equipo
$D = isset($_GET['D']) ? mysqli_real_escape_string($conn, $_GET['D']) : '';// fecha final
$P = isset($_GET['P']) ? mysqli_real_escape_string($conn, $_GET['P']) : '';// %
$N = isset($_GET['N']) ? mysqli_real_escape_string($conn, $_GET['N']) : '';// nota

// Verificar si alguna de las variables de sesión existe
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Datos del operador
$operador = mysqli_query($conn, "SELECT * FROM `operadores` WHERE rut = '$data'");
$rowOperador = mysqli_fetch_array($operador);
$nombre = $rowOperador['nombre'];
$apellidos = $rowOperador['apellidos'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VER EXAMEN</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            color: #95A5A6;
        }
        a {
            color: #95A5A6;
            text-decoration: none;
        }
        .logo img {
            width: 100%;
            position: absolute;
            top: 5px;
            left: 10px;
            width: 250px;
            height: 80px;
            text-align: center;
        }
        .calificacion{
            position: absolute;
            top: 15px;
            right: 10px;
            width: 250px;
            height: 80px;
            text-align: left;
        }
        .subrayado {
            display: inline-block;
            border-bottom: 4px solid #95A5A6; 
        }
        h1 {
            color: #95A5A6;
        }
        section{
            border: 1px solid #e5e5e5;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
        }
        label{
            display: block;
            padding: 7px;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid #e5e5e5;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
<header>
    <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
    <div class="calificacion">
        Porcentaje <?php echo $P; ?>% <br>
        Puntaje <?php echo $N; ?>
    </div>
</header>
<br><br><br><br><br><br>
<center><h1 class="subrayado">EXAMEN TEORICO</h1></center> 
   <?php

   // Realizar una consulta parametrizada para prevenir inyecciones SQL
   $query = "SELECT * FROM examenes WHERE id_oper = ? AND equipo = ? AND date_realizada = ?";
   $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
       // Vincular los parámetros
       mysqli_stmt_bind_param($stmt, "sss", $data, $E, $D);

       // Ejecutar la consulta
       mysqli_stmt_execute($stmt);

       // Obtener el conjunto de resultados
       $result = mysqli_stmt_get_result($stmt);

       // Usar un conjunto diferente de variables para las preguntas
       $p_values = array();
       $r_values = array();

       while ($row = mysqli_fetch_assoc($result)) {
           $resultado = $row['resultado'];

           // Almacenar las preguntas y respuestas en arrays
           for ($i = 1; $i <= 20; $i++) {
               $p_values[] = $row['p' . $i];
               $r_values[] = $row['r' . $i];
           }
       }

       echo '<br>';
       echo 'Operador: '.$nombre . ' ' .$apellidos;
       echo '<br>';
       echo 'Equipo: '. $equipo = str_replace('_', ' ', $E); 
       echo '<br>';
       echo 'Estado: '. $resultado;
       echo '<br><br><br>';
       // Cerrar la declaración
       mysqli_stmt_close($stmt);
        // Mostrar preguntas y respuestas
        for ($i = 0; $i < 20; $i++) {
            $num_pregunta = $i + 1;
            $num_respuesta = $i + 1;

            // Utilizar una consulta parametrizada para evitar inyecciones SQL
            $prueba_stmt = mysqli_query($conn, "SELECT * FROM `$E` WHERE `id` = '{$p_values[$i]}' ");
            $prueba = mysqli_fetch_array($prueba_stmt);
            $pregunta = $prueba['PREGUNTA'];
            $dato = "R" . $r_values[$i];
            $correcta = $prueba['id_respuesta_correcta'];
            $respuesta = $prueba[$dato];

            $color = ($r_values[$i] == $correcta) ? "green" : "red";
            $estado = ($r_values[$i] == $correcta) ? "CORRECTA" : "INCORRECTA";

            echo "<section class='pregunta'>";
            echo "PREGUNTA {$num_pregunta}. <br> " . $pregunta . "<br><br>";
            echo "<label>RESPUESTA SELECCIONADA<br> <span style=\"color: " . $color . "\">" . $respuesta . "</span></label> <br>" . $estado . "<br>";
            echo "<br>";
            echo '</section>';
        }

        // Cerrar la conexión a la base de datos después de realizar todas las consultas
        mysqli_close($conn);
    } else {
        // Manejar el error en la preparación de la primera consulta
        echo "Error al preparar la primera consulta SQL.";
    }
   ?>
</body>
</html>
<?php
$html = ob_get_clean();
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setIsRemoteEnabled(true);
$options->setDefaultFont('Arial');
$options->setIsHtml5ParserEnabled(true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');

$dompdf->render();

$canvas = $dompdf->getCanvas();
$footer = $canvas->open_object();
$canvas->close_object();
$canvas->add_object($footer, "all");

$dompdf->stream("Examen_Teorico.pdf", array("Attachment" => false));
?>