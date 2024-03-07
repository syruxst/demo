<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
    $sql = "SELECT * FROM `clientes` WHERE user = '$usuario'";
    $rst = $conn->query($sql);
    
    if ($rst->num_rows > 0) {
        $row = $rst->fetch_assoc();
        $name = $row['empresa'];
    } else {
        header("Location: ../cliente.php");
        exit();
    }
} else {
    header("Location: ../cliente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="apple-touch-icon" sizes="57x57" href="../img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../img/icons/ms-icon-144x144.png">
    <title>:: Detalle de Brechas ::</title>
    <style>
        body {
            padding: 20px;
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
        table {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }

        td {
            padding: 10px; 
        }

        #contenido-a-imprimir {
            padding: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #contenido-a-imprimir, #contenido-a-imprimir * {
                visibility: visible;
            }

            #contenido-a-imprimir {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>
<body>
<?php
// Función para desencriptar el valor
function desencriptar($valorEncriptado) {
    $valorDesencriptado = base64_decode($valorEncriptado);
    return $valorDesencriptado;
}

// Obtener el valor de informeId desde la URL
$informeIdEncriptado = $_GET['informe'] ?? '';
$informeId = desencriptar($informeIdEncriptado);

$buscarA = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id='$informeId'");
$row_i = mysqli_fetch_array($buscarA);
$E = $row_i['equipo'];
// Ahora $informeId contiene el valor desencriptado
//echo $informeId;
?>
<div id="contenido-a-imprimir">
    <table width="100%"> 
        <tr>
            <td><img src="../img/LogoPrincipal.png" width="200px"></td>
            <td align="center"><h3>INFORME GENERAL DE BRECHAS</h3></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                Nombre : <?php echo $row_i['nombre']; ?>&nbsp; RUT : <?php echo $row_i['rut'] ;?>
                <br>
                Equipo : <?php echo  $row_i['equipo'];?>&nbsp; <br> Obra : <?php echo $row_i['faena'];?><br>
                N° Folio : <?php echo $row_i['ip']. "-" . $row_i['folio'];?>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="pers">
                Brechas de Competencias en la Prueba de Conocimientos Teóricos
            </td>
        </tr>
        <tr>
            <td colspan="3" class="color" style="padding: 10px; font-size: 16px;">
                <?php 
                    $query = "SELECT * FROM examenes WHERE id_oper = ? AND equipo = ? AND date_realizada = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sss", $row_i['rut'], $row_i['equipo'], $row_i['date_out']);
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
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="pers">
                Brechas de Competencias en Evaluación Práctica
            </td>
        </tr>
        <tr>
            <td colspan="3" class="color" style="padding: 10px; font-size: 16px;">
                <?php
                    $buscar = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper='$informeId'");
                        while ($rows = mysqli_fetch_array($buscar)) {
                            $observaciones = $rows['observaciones'];
                            
                            $observaciones = preg_replace('/(\d+\.-)/', PHP_EOL . "$1", $observaciones);
                            
                            echo nl2br($observaciones);
                        }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="pers">
                Oportunidades de Desarrollo de Competencias par alcanzar la Exclencia Operacional Practica
            </td>
        </tr>
        <tr>
            <td colspan="3" class="color mi-clase-th" style="min-height: 200px; font-size: 14px;">
                <?php

                    $mejora = $row_i['oport_m'];

                    // Agrega un salto de línea delante de la frase específica
                    $mejora = preg_replace('/PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:/i', '<br>PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:', $mejora);

                    // Agrega un salto de línea después de cada punto seguido de espacio
                    $mejora = preg_replace('/\.(?=\s|$)/', ".<br>", $mejora);

                    echo $mejora;
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
            <img src="https://acreditasys.tech/img/FirmaCoordinador.png" style="width: 220px;"/><br>
            <b>Coordinador Técnico<br>
            Operamaq Empresa Spa</b>
            </td>
        </tr>
    </table>
</div>
<hr>
<button onclick="imprimirContenido()" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button>
<hr>
<label for="" style="font-weight: bold; color: red;">Si desea utilizar nuestro formato de brechas, puedes bajar una copia desde aquí. <a href="../FORM-EVA-04 A Informes Respuesta de brechas.docx" target="_blank" title="DESCARGAR FORMULARIO DE BRECHAS"><i class="fa fa-file-word-o fa-2x" aria-hidden="true"></i></a> </label>
</body>
<script>
    function imprimirContenido() {
        window.print();
    }
</script>
</html>