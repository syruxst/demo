<?php
ob_start();
session_start();
error_reporting(0);
require_once('../admin/conex.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa Semanal</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php
    $inicio = isset($_GET['in']) ? $_GET['in'] : null;
    $fin = isset($_GET['out']) ? $_GET['out'] : null;

    if ($inicio !== null && $fin !== null) {
        $sql = "SELECT DISTINCT d.ip, d.id_ot, d.fecha, d.empresa, d.faena, c.contacto, c.telefono, c.correo
                FROM detallle_ot d
                JOIN ot o ON d.id_ot = o.id_ot
                JOIN cotiz c ON o.id_cotiz = c.id_cotiz
                WHERE d.fecha BETWEEN ? AND ?
                ORDER BY d.fecha ASC";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $inicio, $fin);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            ?>
            <img src="https://operamaq.cl/nuevo/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo">

            <center><h1>Programa Semanal</h1></center>
            <center>Periodo Desde: <?php echo date("d-m-Y", strtotime($inicio));?> Hasta: <?php echo date("d-m-Y", strtotime($fin));?></center>
            <br>
            <table width="100%" border="0" class="table table-striped" style="font-size: 12px;">
                <tr>
                    <th>EV-IP</th>
                    <th>N° OT</th>
                    <th>FECHA</th>
                    <th>EMPRESA</th>
                    <th>FAENA</th>
                    <th>CONTAC</th>
                    <th>CORREO</th>
                    <th>FONO</th>
                    <th>CANT</th>
                </tr>
                <?php
                $totalCantidadFilas = 0;

                while ($ver = mysqli_fetch_array($result)) {
                    $ip = $ver['ip'];
                    $id_ot = $ver['id_ot'];
                    $Fecha = $ver['fecha'];

                    $query = "SELECT COUNT(*) as cantidadFilas
                                FROM detallle_ot
                                WHERE fecha BETWEEN ? AND ? AND ip = ? AND id_ot = ?";
                    $stmt_query = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt_query, "ssss", $Fecha, $Fecha, $ip, $id_ot);
                    mysqli_stmt_execute($stmt_query);
                    $result_query = mysqli_stmt_get_result($stmt_query);
                    $row_query = mysqli_fetch_assoc($result_query);
                    $cantidadFilas = $row_query['cantidadFilas'];

                    $totalCantidadFilas += $cantidadFilas;

                    ?>
                    <tr>
                        <td><?php echo $ip; ?></td>
                        <td><?php echo $id_ot; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($Fecha)); ?></td>
                        <td><?php echo $ver['empresa']; ?></td>
                        <td><?php echo $ver['faena']; ?></td>
                        <td><?php echo $ver['contacto']; ?></td>
                        <td><?php echo $ver['correo']; ?></td>
                        <td><?php echo $ver['telefono']; ?></td>
                        <td align="center"><?php echo $cantidadFilas; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
            Total de eventos: <?php echo $totalCantidadFilas; ?> programados.
    <?php
        }
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
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$canvas = $dompdf->getCanvas();
$footer = $canvas->open_object();
$canvas->close_object();
$canvas->add_object($footer, "all");

$dompdf->stream("Programa_Semanal.pdf", array("Attachment" => false));
?>