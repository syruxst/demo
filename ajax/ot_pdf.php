<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');

$ot = $_GET['ttw'];
$id_ot = base64_decode($ot);
$query = "SELECT * FROM `ot` WHERE id_ot = '$id_ot'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$OT = $row['id_ot'];
$id_cotiz = $row['id_cotiz'];
$fechaOriginal = $row['date'];
$Tipo = $row['tipo'];

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <title>Document</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .table{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
    <br>
    <br>
    <br>
    <div class="fecha">Los Andes, <?php echo formatearFecha($fechaOriginal); ?></div>
    <center><h4>ORDEN DE TRABAJO N° <?php echo $id_ot; ?></h4></center>
    <br>
    <?php
    $empresa = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE id_cotiz = '$id_cotiz'");
    $rows = mysqli_fetch_array($empresa);
    $nombreEmpresa = $rows['name_cliente'];
    
    ?>
    Nombre Empresa : <?php echo $nombreEmpresa; ?><br>
    Faena : <?php echo $rows['faena']; ?><br>
    Contacto : <?php echo $rows['contacto']; ?><br>
    Celular : <?php echo $rows['telefono']; ?>
    <br>
    <br>
    <br>
    <?php
    $detalle = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id_ot = '$id_ot'");

    if($Tipo == 'M'){
        echo '<table width="100%" border="0" class="table" style="font-size: 12px;">';
        echo '<tr>';
        echo '<th>FOLIO</th>';
        echo '<th>EQUIPO</th>';
        echo '<th>PATENTE</th>';
        echo '<th>EV</th>';
        echo '<th>FECHA</th>';
        echo '</tr>';

        while($mostrar = mysqli_fetch_array($detalle)){
            echo '<tr>';
            echo '<td>'.$mostrar['folio'].'</td>';
            echo '<td>'.$mostrar['equipo'].'</td>';
            echo '<td>'.$mostrar['patente'].'</td>';
            echo '<td>'.$mostrar['ip'].'</td>';
            echo '<td>'.date('d-m-Y', strtotime($mostrar['fecha'])).'</td>';
            echo '</tr>';
        }        
        echo '</table>';
    }else{
        echo '<table width="100%" border="0" class="table" style="font-size: 12px;">';
        echo '<tr>';
        echo '<th>FOLIO</th>';
        echo '<th>OPERADOR</th>';
        echo '<th>RUT</th>';
        echo '<th>STATUS</th>';
        echo '<th>TIPO DE MAQUINA</th>';
        echo '<th>MODELO</th>';
        echo '<th>EV</th>';
        echo '<th>FECHA</th>';
        echo '</tr>';
    
        while($mostrar = mysqli_fetch_array($detalle)){
            echo '<tr>';
            echo '<td>'.$mostrar['folio'].'</td>';
            echo '<td>'.$mostrar['nombre'].'</td>';
            echo '<td>'.$mostrar['rut'].'</td>';
            echo '<td>'.$mostrar['status'].'</td>';
            echo '<td>'.$mostrar['equipo'].'</td>';
            echo '<td>'.$mostrar['modelo'].'</td>';
            echo '<td>'.$mostrar['ip'].'</td>';
            echo '<td>'.date('d-m-Y', strtotime($mostrar['fecha'])).'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }


    ?>
    <div class="footer">
        <hr>
        <center><a href="https://operamaq.cl/" target="_blank">www.operamaq.cl</a> - venta@operamaq.cl – Cel. +56 9 6362 6835</center>
    </div>
</body>
</html>
<?php
$nombre = "OT_N°_" . $id_ot;
$html = ob_get_clean();
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuración de opciones
$options = new Options();
$options->setIsRemoteEnabled(true);
$options->setDefaultFont('Arial');
$options->setIsHtml5ParserEnabled(true);

// Crear instancia de Dompdf
$dompdf = new Dompdf($options);

// Cargar HTML
$dompdf->loadHtml($html);

// Establecer el tamaño del papel a A4 y orientación horizontal
$dompdf->setPaper('A4', 'landscape');

// Renderizar el PDF
$dompdf->render();

// Configurar el pie de página
$canvas = $dompdf->getCanvas();
$footer = $canvas->open_object();
$canvas->page_text(750, 18, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0));
$canvas->close_object();
$canvas->add_object($footer, "all");

// Mostrar o descargar el PDF
$dompdf->stream("$nombre.pdf", array("Attachment" => false));
?>