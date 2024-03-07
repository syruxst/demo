<?php session_start(); error_reporting(0);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../index.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../admin/conex.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Document</title>
    <style>
        :root {
            --color: #04C9FA;
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        .container-obs {
            text-align: left;
        }
        h1 {
            color: var(--color);
        }
        .pers {
            background-color: rgba(95, 116, 122, 0.8); 
            color: white; 
            padding: 10px;
            border: 1px solid white;
        }
        .color {
            background-color: rgba(241, 241, 241, 0.6);
        }
        .colorB {
            background-color: #34495E;
            color: white;
        }
        textarea {
            width: 100%;
            height: 100%;
            padding: 10px;
            text-align: left;
        }
        table {
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        td {
            padding: 10px; 
        }
        /* Estilo para el contenedor del indicador de carga */
        .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
        z-index: 1000; /* Asegura que esté en la parte superior de todos los elementos */
        }

        /* Estilo para el indicador de carga en sí */
        .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 15% auto; /* Centra el indicador de carga verticalmente */
        animation: spin 2s linear infinite; /* Agrega una animación de giro */
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <center><h1>INFORME DE BRECHAS</h1></center>
    <?php 
    $data = $_GET['data-brecha'];
    $buscar = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id='$data'");
    $row = mysqli_fetch_array($buscar);
    $Id_informe = $row['id'];
    $E = $row['equipo'];
    
    $bre_prac = empty($row['brecha_p']) ? '<td style="white-space: pre-wrap;"><textarea class="tex" name="obs" id="obs" cols="30" rows="5"></textarea></td>' : '<td style="white-space: pre-wrap; min-width: 200px;">' . $row['brecha_p'] . '</td>';
    $bre_sist = empty($row['brecha_s']) ? '</td><td style="white-space: pre-wrap;"><textarea name="brechas" class="tex" style="min-height: 500px; width: 100%;" id="brechas" cols="30" rows="5"></textarea></td>': '</td><td style="white-space: pre-wrap;">' . $row['brecha_s'] . '</td>';
    if($row['brecha_s'] != ''){
        $textAreaOb = $row['oport_m'];
    }else {
        $textAreaOb = '<textarea name="oport" id="oport" cols="30" rows="8">PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:' . "\n" . 'EN LO REFERENTE A:' . "\n" . 'DE ACUERDO CON: ' . "\n\n" . '</textarea>';
    }
    ?>

        <div class="container-obs">
            <!--datos par enviar-->
            <input type="hidden" name="dataInforme" id="dataInforme" value="<?php echo $data;?>">
            <?php
                $buscar = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper='$Id_informe'");
                
                // Imprimir la tabla con observaciones en una sola fila y saltos de línea
                echo '<table width="100%" border="0">
                        <tr>
                            <th class="pers">Brechas de Competencias en Evaluación Práctica</th>';
                    
                echo '<th class="colorB">Clasificación</th>';
                echo '
                        </tr>';

                while ($rows = mysqli_fetch_array($buscar)) {
                    // Agregar saltos de línea antes de cada "n.-"
                    $observaciones = preg_replace('/(\d+\.-)/', "\n$1", $rows['observaciones']);
                    
                    echo '<tr>
                            <td style="white-space: pre-wrap;" class="color">' . $observaciones . '</td>';
                    echo $bre_prac;
                    echo '</tr>';
                }
                echo '<tr>
                        <th class="pers">Brechas de Competencias en la Prueba de Conocimientos Teóricos</th>';
                
                echo '<th class="colorB">Clasificación</th>';
                echo '
                </tr>';
                echo '<tr>
                        <td class="color">';
                $query = "SELECT * FROM examenes WHERE id_oper = ? AND equipo = ? AND date_realizada = ?";
                $stmt = mysqli_prepare($conn, $query);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $row['rut'], $row['equipo'], $row['date_out']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
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
                
                        if ($r_values[$i] != $correcta) {
                            $color = "red";
                            $estado = "INCORRECTA";
                
                            echo "<section class='pregunta'>";
                            echo "{$num_pregunta}.- " . $pregunta . "<br><br>";
                            echo '</section>';
                        }
                    }
                }
                
                
                echo $bre_sist;

                echo '</tr>';
                echo '<tr>';
                echo '<th colspan="2" class="pers">Oportunidades de Desarrollo de Competencias par alcanzar la Exclencia Operacional Practica</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<td colspan="2">';
                echo $textAreaOb;
                echo '</td>';
                echo '</tr>';
                echo '<tr><td colspan="2"><button id="agregarTexto" class="btn btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Texto</button></td></tr>';
                echo '</table>';
            ?>

        </div>
        <br>
        <hr>
        <button type="button" class="btn btn-success" title="GUARDAR INFORME DE BRECHAS" onclick="enviarAccion('aprobar')">
            <i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp; &nbsp; GUARDAR
        </button>        
        <div class="loading-overlay" id="loading-overlay">
            <div class="loader"></div>
        </div>
<script>
document.getElementById('agregarTexto').addEventListener('click', function() {
    var textarea = document.getElementById('oport');
    textarea.value += 'PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:' + "\n" +
                     'EN LO REFERENTE A:' + "\n" +
                     'DE ACUERDO CON:' + "\n\n";
});

function enviarAccion(accion) {
    var dataInforme = document.getElementById("dataInforme").value;
    var obsValue = document.getElementById("obs").value;
    var brechasValue = document.getElementById("brechas").value;
    var oportValue = document.getElementById("oport").value;
    
    if (accion === 'rechazar') {
        // Preguntar al usuario si realmente desea rechazar el servicio
        swal({
            title: "¿Estás seguro?",
            text: "Una vez rechazado, no podrás recuperar este documento",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willRechazar) => {
            if (willRechazar) {
                // El usuario ha confirmado el rechazo, ahora puedes enviar el valor al archivo cierre_ot.php
                enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue);
            } else {
                swal("Tu documento está seguro.");
            }
        });
    } else {
        // Si la acción es aprobar, simplemente envía el valor al archivo cierre_ot.php
        enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue);
    }
}

function enviarValor(dataInforme, accion, obsValue, brechasValue, oportValue) {

        // Validar que los valores no estén vacíos
        if (!obsValue || !brechasValue || !oportValue) {
            // Mostrar un mensaje de error o tomar la acción apropiada
            swal({
                title: "Advertencia!",
                text: "Debe llenar los campos vacios",
                icon: "info",
                button: "Aceptar!",
            });
            return;
        }

    var xhr = new XMLHttpRequest();
    var url = 'save_infor_brecha.php';

    // Muestra el indicador de carga
    var loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'block';

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            // Oculta el indicador de carga cuando la respuesta se recibe
            loadingOverlay.style.display = 'none';

            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        swal({
                            title: "Bien hecho!",
                            text: "Operación exitosa: " + response.message,
                            icon: "success",
                            button: "Aceptar!",
                        });
                    } else {
                        swal({
                            title: "Algo sali mal",
                            text: "Fallo: " + response.message + "!",
                            icon: "error",
                            button: "Aceptar!",
                        });
                    }
                } catch (e) {
                    console.error('Error al analizar la respuesta JSON: ' + e);
                }
            } else {
                console.error('Error en la solicitud. Estado: ' + xhr.status);
            }
        }
    };

    var data = 'dataInforme=' + encodeURIComponent(dataInforme) + '&accion=' + encodeURIComponent(accion) + '&obs=' + encodeURIComponent(obsValue) + '&brechas=' + encodeURIComponent(brechasValue) + '&oport=' + encodeURIComponent(oportValue);
    
    xhr.send(data);
}


</script>
</body>
</html>