<?php
session_start();
error_reporting(0);
$usuario = $_SESSION['usuario'];
require_once('../admin/conex.php');
$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d H:i:s");
$date_aprobado = $now->format("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Resultado de Examen ::</title>
    <style>
        :root{
            --color : #e5e5e5;
            --colorHover: #04C9FA;
            --colorButton: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        .container {
            border-radius: 10px;
            border: 1px solid var(--color);
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
        }

    </style>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha enviado una solicitud POST
   $rut = $_POST['rut'];
   $equipo = $_POST['equipo'];

    //Buscar datos
    $Sqli = "SELECT * FROM `detallle_ot` WHERE rut = '$rut' AND equipo = '$equipo' AND resultado = '' ";
    $result = mysqli_query($conn, $Sqli);
    $row = mysqli_fetch_assoc($result);
    $nombre = $row['nombre'];
    $IdOt = $row['id_ot'];
    $contador = $row['contador'];
    $contador = $contador + 1;

    $respuestas = []; // Un arreglo para almacenar las respuestas
    $preguntaIds = array();

    // Itera a través de las respuestas potenciales
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'p') === 0) { // Verifica si el campo comienza con 'p'
            $idPregunta = intval(substr($key, 1)); // Obtener el ID de la pregunta
            $preguntaIds[] = $idPregunta;
            $respuestas[$idPregunta] = $value; // Almacena la respuesta en el arreglo
        }
    }
    // Realiza la comparación con las respuestas correctas en la base de datos
    $respuestasCorrectas = 0;

    foreach ($respuestas as $idPregunta => $respuesta) {
        // Realiza una consulta para obtener la respuesta correcta de la base de datos.
        $consulta = mysqli_query($conn, "SELECT id_respuesta_correcta FROM $equipo WHERE id = $idPregunta");

        // Verifica si la consulta fue exitosa.
        if ($consulta) {
            $fila = mysqli_fetch_assoc($consulta);
            $respuestaCorrecta = $fila['id_respuesta_correcta'];

            // Compara la respuesta del usuario con la respuesta correcta.
            if ($respuesta == $respuestaCorrecta) {
                // La respuesta es correcta.
                $respuestasCorrectas++;
            }
        } else {
            // Manejo de errores si la consulta falla.
            echo "Error en la consulta para la pregunta $idPregunta: " . mysqli_error($conn);
        }
    }

    // Calcular el porcentaje de respuestas correctas
    $totalPreguntas = 20; // Total de preguntas
    $porcentaje = ($respuestasCorrectas / $totalPreguntas) * 100;

    // Definir el umbral para aprobar (80%)
    $umbralAprobacion = 80;

    // Calcular la nota utilizando interpolación lineal
    $notaMinima = 1;
    $notaMaxima = 4;
    $nota = $notaMinima + ($notaMaxima - $notaMinima) * ($respuestasCorrectas / $totalPreguntas);

    // Determinar si el usuario aprobó o reprobó
    $resultado = ($porcentaje >= $umbralAprobacion) ? "APROBADO" : "REPROBADO";

    /*
    // Muestra las respuestas correctas, el porcentaje, la nota y el resultado al usuario
    echo "Respuestas Correctas: $respuestasCorrectas<br>";
    echo "Porcentaje de respuestas correctas: $porcentaje%<br>";
    echo "Nota: $nota<br>";
    echo "Resultado: $resultado";*/

} else {
    // Si no es una solicitud POST, puedes mostrar un mensaje de error o redirigir al usuario
    echo "Error: No se ha enviado una solicitud POST.";
}


?>
<div class="container">
    <?php
    if($resultado == "APROBADO"){
        echo '<h3 style="color: #06C81B"><i class="fa fa-smile-o" aria-hidden="true"></i> ¡Felicidades '.$nombre.', has aprobado el examen!</h3>';
        echo '<br><br>';
        echo 'Respuestas Correctas: '.$respuestasCorrectas.'<br>';
        echo 'Porcentaje de respuestas correctas: '.$porcentaje.'%<br>';
        echo 'Resultado: '.$resultado.'';  

        $sql = "UPDATE `detallle_ot` SET date_out = ?, resultado = ?, porNota = ?, punNota = ?, contador = ? WHERE rut = ? AND equipo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $fecha, $resultado, $porcentaje, $nota, $contador, $rut, $equipo);
            $stmt->execute();
            $stmt->close();
        }

        // Guardar datos en la base de datos.
        $save = "INSERT INTO `examenes` (`id_oper`, `equipo`, `date_realizada`, `resultado`, `p1`, `p2`, `p3`, `p4`, `p5`, `p6`, `p7`, `p8`, `p9`, `p10`, `p11`, `p12`, `p13`, `p14`, `p15`, `p16`, `p17`, `p18`, `p19`, `p20`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`, `r9`, `r10`, `r11`, `r12`, `r13`, `r14`, `r15`, `r16`, `r17`, `r18`, `r19`, `r20`) VALUES ('$rut', '$equipo', '$fecha', '$resultado', ";

        // Agregar respuestas de opción 'p'
        $save .= implode(', ', $preguntaIds);

        // Agregar respuestas de opción 'r'
        $respuestasR = array();
        foreach ($preguntaIds as $idPregunta) {
            if (isset($respuestas[$idPregunta])) {
                $respuestasR[] = "'" . mysqli_real_escape_string($conn, $respuestas[$idPregunta]) . "'";
            } else {
                $respuestasR[] = "NULL"; // Si no hay respuesta 'r' para una pregunta, se inserta NULL
            }
        }
        $save .= ", " . implode(', ', $respuestasR) . ")";

        $result = mysqli_query($conn, $save);

        if($result){
            //echo 'Datos guardados';
        } else {
            echo 'Error al guardar datos: ' . mysqli_error($conn);
        }
    }else{
        echo '<h3 style="color: black;"> ¡'.$nombre.', hemos registrado tu examen!</h3>';
        echo '<br><br>';
        //echo 'Respuestas Correctas: '.$respuestasCorrectas.'<br>';
        //echo 'Porcentaje de respuestas correctas: '.$porcentaje.'%<br>';
        //echo 'Resultado: '.$resultado.'';

        $sql = "UPDATE `detallle_ot` SET date_out = ?, resultado = ?, porNota = ?, punNota = ?, contador = ?  WHERE rut = ? AND equipo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $fecha, $resultado, $porcentaje, $nota, $contador, $rut, $equipo);
            $stmt->execute();
            $stmt->close();
        }
        if($contador >= '2'){
            $fechaHoraActual = date('dmYHis');
            $contenidoAEncriptar = $usuario . $fechaHoraActual;

            $hash = md5($contenidoAEncriptar);

            $qr = $hash;
            //echo '<h3>Lamentablemente no tiene mas intentos para realizar su examen. Gracias!</h3>';
            //$certificado = mysqli_query($conn, "UPDATE `detallle_ot` SET resultado = 'REPROBADO', informe = 'RECHAZADO', porcentaje = '0', puntaje = '', estado = 'RECHAZADO', qr = '$qr', fecha_arprob = '$date_aprobado' WHERE rut = '$rut' AND equipo = '$equipo'");
            // Guardar datos en la base de datos.
            $save = "INSERT INTO `examenes` (`id_oper`, `equipo`, `date_realizada`, `resultado`, `p1`, `p2`, `p3`, `p4`, `p5`, `p6`, `p7`, `p8`, `p9`, `p10`, `p11`, `p12`, `p13`, `p14`, `p15`, `p16`, `p17`, `p18`, `p19`, `p20`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`, `r9`, `r10`, `r11`, `r12`, `r13`, `r14`, `r15`, `r16`, `r17`, `r18`, `r19`, `r20`) VALUES ('$rut', '$equipo', '$fecha', '$resultado', ";

            // Agregar respuestas de opción 'p'
            $save .= implode(', ', $preguntaIds);

            // Agregar respuestas de opción 'r'
            $respuestasR = array();
            foreach ($preguntaIds as $idPregunta) {
                if (isset($respuestas[$idPregunta])) {
                    $respuestasR[] = "'" . mysqli_real_escape_string($conn, $respuestas[$idPregunta]) . "'";
                } else {
                    $respuestasR[] = "NULL"; // Si no hay respuesta 'r' para una pregunta, se inserta NULL
                }
            }
            $save .= ", " . implode(', ', $respuestasR) . ")";

            $result = mysqli_query($conn, $save);

            if($result){
                //echo 'Datos guardados';
            } else {
                echo 'Error al guardar datos: ' . mysqli_error($conn);
            }
        }else {
            echo '<h3>Se le proporcionará un nuevo examen.</h3>';
            $clear = mysqli_query($conn, "UPDATE `detallle_ot` SET date_in = '0000-00-00 00:00:00', date_out = '0000-00-00 00:00:00', resultado = '', datefin = '0000-00-00 00:00:00' WHERE rut = '$rut' AND equipo = '$equipo'");
        }
    }
    ?>
<h3></h3>
</div>
</body>
</html>