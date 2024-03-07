<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $nombre = $row['name'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}

// Capturar la salida HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>COTIZACIÓN PDF</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #dddddd;
            /*text-align: left;*/
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .fecha{
            position: absolute;
            top: 80px;
            right: 0;
        }
        li{
            margin-bottom: 5px;
        }
        #summaryDiv {
            margin-top: 5px;
            padding: 0px;
            width: 200px;
            float: right;
        }
            /* Estilo del footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            padding: 10px;
            color: #A6ACAF;
        }

        .footer a {
            text-decoration: none;
            color: #A6ACAF;
        }
    </style>
</head>
<body>
    <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo"><br>

    <?php
    $ot_encoded = $_GET['cot'];
    $id_cotiz = base64_decode($ot_encoded);
    $query = "SELECT * FROM `cotiz` WHERE id_cotiz = '$id_cotiz'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $id_cotiz = $row['id_cotiz'];
    $fechaOriginal = $row['fecha_creacion'];

    function formatearFecha($fechaOriginal) {
        $timestamp = strtotime($fechaOriginal);
    
        // Establecer el idioma a español
        $locale = 'es_ES';
    
        // Crear un formateador de fecha y hora con el formato deseado
        $dateFormatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'UTC', // Zona horaria, puedes ajustarla según sea necesario
            IntlDateFormatter::GREGORIAN,
            'd MMMM \'de\' y'
        );
    
        // Formatear la fecha y hora
        $fechaFormateada = $dateFormatter->format($timestamp);
    
        return $fechaFormateada;
    }
    ?>
    <br>
    <br>
    <br>
    <div class="fecha">Los Andes, <?php echo formatearFecha($fechaOriginal); ?></div>
    <center><h4>COTIZACIÓN N° <?php echo $row['id_cotiz']; ?></h4></center>

    Sr(a). <?php echo $row['contacto']; ?><br>
    <?php echo $row['name_cliente']; ?><br>
    <?php echo $row['faena']; ?><br>
    <br>
    <p style="text-align: justify;">Por la presente, enviamos cotización, evaluación y certificación de competencias laborales. Según detalle:</p>
        <div class="detalles">
            <?php
                $detalle = mysqli_query($conn, "SELECT * FROM `serviceCot` WHERE id_cotiz = '$id_cotiz'");
                    echo '<table width="100%" border="0" class="table table-striped" style="font-size: 12px;">';
                    echo '<tr>
                        <th>ITEM</th>
                        <th>CANT</th>
                        <th>DESCRIPCIÓN</th>
                        <th>P. UNIT</th>
                        <th>DESCUENTO</th>
                        <th>TOTAL</th>
                    </tr>';
                    $n = 1;
                    $totalSum = 0;
                    while($resutaldo = mysqli_fetch_array($detalle)){
                        echo '<tr>';
                        echo '<td align="center">'.$n.'</td>';
                        echo '<td align="center">'.$resutaldo['cantidad'].'</td>';
                        echo '<td>'.$resutaldo['servicio'].'</td>'; 
                        echo '<td align="right"> $ '.$resutaldo['unitario'].'</td>';
                        echo '<td align="right">'.$resutaldo['descuento'].' % </td>';
                        echo '<td align="right"> $ '.$resutaldo['total'].'</td>';
                        echo '</tr>';
                        $n++;
                        $totalSum += floatval(str_replace('.', '', $resutaldo['total']));
                        $iva = $totalSum * 0.19;
                        $Total = $totalSum + $iva;
                    }
                    echo '</table>';
            ?>
        </div>
        <div id="summaryDiv">
            <table width="100%" style="font-size: 12px;">
                <tr>
                    <td>Sub-total</td>
                    <td align="center">:</td>
                    <td align="right">$ <?php echo number_format($totalSum, 0, '', '.'); ?></td>
                </tr>
                <tr>
                    <td>IVA (19%)</td>
                    <td align="center">:</td>
                    <td align="right">$ <?php echo number_format($iva, 0, '', '.'); ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td align="center">:</td>
                    <td align="right">$ <?php echo number_format($Total, 0, '', '.'); ?></td>
                </tr>
            </table>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br
    <p style="text-align: justify;">Esta cotización tiene una vigencia de 15 dias. Cualquier duda comuniquese con nosotros. Estareos gustosos en atenderlo.</p>
    <br>
    <h4>Datos Comerciales</h4>
    Razón Social&nbsp;: Operamaq Empresa Spa.<br>
    Giro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Agencia de Empleo y Certificación.<br>
    Dirección&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Los Morenos N° 239, Los Andes<br>
    RUT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 77.741.739-8<br>
    Banco&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Santander<br>
    Cuenta Corriente&nbsp;: 0-000-9105818-9<br>
    <h4>Consideraciones</h4>
    <ol type="a">
        <li> <?php echo $row['pago'];?></li>
        <li> Se factura por cada OT cerrada.</li>
        <li> Aceptada la cotización, enviar OC.</li>
        <li> Para programar el servicio, enviar nomina con nombre, rut y equipo por cada operador.</li>
        <li> Traslado evaluador, se cobra por cada día de evaluación.</li>
        <li> La NO disponibilidad del equipo para realizar la evaluación, constituye cobro de $30.000 por cada evaluación no realizada.</li>
        <li> Por cada visita solo se podrá realizar las evaluaciones programadas.</li>
        <li> Se adjunta procedimiento proceso de evaluación.</li>
    </ol>
    <br>
    <br>
    <br>
    <br>    
    <br>
    <br>
        <div style="text-align: center; margin-top: 50px;">
        <div style="border-bottom: 1px solid black; width: 250px; margin: 0 auto;"></div>
        Operamaq Empresa Spa
        <br>
        RUT: 77.741.739-8
    </div>
    <div class="footer">
        <hr>
        <center><a href="https://operamaq.cl/" target="_blank">www.operamaq.cl</a> - venta@operamaq.cl – Cel. +56 9 6362 6835</center>
    </div>
</body>
</html>
<?php
$value = "cotizacion_N°" . $id_cotiz;
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

$dompdf->stream($value.".pdf", array("Attachment" => false));
?>