<?php
ob_start();
require_once('../admin/conex.php');
$id = $_GET['id'];
$buscar_nomima = mysqli_query($conn, "SELECT * FROM `nomina` WHERE id_nomina = '$id'");

while ($result = mysqli_fetch_array($buscar_nomima)) {
    $numero = $result['id_nomina'];
    $titulo = $result['titulo'];
    $empresa = $result['empresa'];
    $faena = $result['faena'];
    $cotizacion = $result['cotizacion'];
    $contact = $result['contact'];
    $contactFormateado = ucwords($contact);
    $date_in = $result['date_in'];
    $date_end = $result['date_end'];
    $fecha = $result['fecha'];

    $fecha = $result['fecha'];
    $meses = array(
        'January' => 'enero',
        'February' => 'febrero',
        'March' => 'marzo',
        'April' => 'abril',
        'May' => 'mayo',
        'June' => 'junio',
        'July' => 'julio',
        'August' => 'agosto',
        'September' => 'septiembre',
        'October' => 'octubre',
        'November' => 'noviembre',
        'December' => 'diciembre'
    );
    $fechaFormateada = date('d \d\e ', strtotime($fecha)) . $meses[date('F', strtotime($fecha))] . date(' \d\e\l Y', strtotime($fecha));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: Vista Previa Nomina ::</title>
</head>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    hr {
        border: 1px solid #E5E7E9;
    }

    .fecha {
        position: absolute;
        right: 0px;
        width: auto;
        height: auto;
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

    /* Estilo para la numeración de páginas */
    .page-number {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .content {
        margin-bottom: 60px;
        /* Considera la altura del footer más un margen adicional */
        flex: 1;
    }

    .nota {
        margin-bottom: 60px;
        /* Considera la altura del footer más un margen adicional */
    }
</style>

<body>
    <img src="https://operamaq.cl/nuevo/ajax/logo.png" width="150">
    <hr>
    <br>
    <div class="fecha"><?php echo "La Serena $fechaFormateada"; ?></div>
    <br>
    <br>
    <center>
        <h1><?php echo strtoupper($titulo) . " OPERADORES"; ?></h1>
    </center>
    <br>
    <br>
    <label>Sr(a). <?php echo $contactFormateado; ?> </label><br>
    <label><?php echo strtoupper($empresa); ?></label>
    <br>
    <br>
    <label>Mediante la presente enviamos <?php echo $titulo; ?> operadores, según cotización N° <?php echo $cotizacion; ?></label>
    <br>
    <br>
    <div class="content">
        <table width="100%" border="0" style="font-size: 10px;">
            <tr style="background-color: #D7DBDD;">
                <th>COT</th>
                <th>Nombre</th>
                <th>Rut</th>
                <th>Cargo</th>
                <th>Celular</th>
                <th>Licencia</th>
                <th>Disp-Acred</th>
                <th>Obs.1</th>
                <th>Obs.2</th>
                <th>Sueldo</th>
            </tr>
            <?php
            $buscar_operadores = mysqli_query($conn, "SELECT * FROM `operadores` WHERE id_nomina = '$numero'");
            while ($encontrados = mysqli_fetch_array($buscar_operadores)) {

                switch ($encontrados['equipo1']) {

                    case '13':

                        $maq = 'Bulldozer D6';

                        break;

                    case '1':

                        $maq = 'Bulldozer D8';

                        break;

                    case '14':

                        $maq = 'Bulldozer D09';

                        break;

                    case '15':

                        $maq = 'Bulldozer D10';

                        break;

                    case '2':

                        $maq = 'Camión Aljibe 15 m3';

                        break;

                    case '3':

                        $maq = 'Camión Aljibe 30 m3';

                        break;

                    case '19':

                        $maq = 'Camión Dumper';

                        break;

                    case '24':

                        $maq = 'Camión Lubricador';

                        break;

                    case '23':

                        $maq = 'Camión Petroleador';

                        break;

                    case '4':

                        $maq = 'Camión Pluma 5 ton';

                        break;

                    case '16':

                        $maq = 'Camión Pluma 8 ton';

                        break;

                    case '17':

                        $maq = 'Camión Pluma 10 ton';

                        break;

                    case '18':

                        $maq = 'Camión Pluma 15 ton';

                        break;

                    case '5':

                        $maq = 'Camión Tolva 20 m3';

                        break;

                    case '22':

                        $maq = 'Cargador Frontal';

                        break;

                    case '6':

                        $maq = 'Excavadora 20-22 Ton.';

                        break;

                    case '7':

                        $maq = 'Excavadora 35 Ton.';

                        break;

                    case '8':

                        $maq = 'Excavadora 50 Ton.';

                        break;

                    case '20':

                        $maq = 'Excavadora 70 Ton.';

                        break;

                    case '21':

                        $maq = 'Excavadora 80 Ton.';

                        break;

                    case '9':

                        $maq = 'Minicargador';

                        break;

                    case '10':

                        $maq = 'Motoniveladora';

                        break;

                    case '11':

                        $maq = 'Retroexcavadora';

                        break;

                    case '25':

                        $maq = 'Rigger';

                        break;

                    case '12':

                        $maq = 'Rodillo Compactador';

                        break;

                    default:

                        $maq = ''; // Valor por defecto si no coincide con ninguno de los casos anteriores

                        break;

                }    


                echo '
                <tr>
                    <td> ' . $cotizacion . ' </td>
                    <td> ' . $encontrados['nombre'] . ' ' . $encontrados['apellidos'] . '</td>
                    <td> ' . $encontrados['rut'] . ' </td>
                    <td> ' . $maq . ' </td>
                    <td> ' . $encontrados['celular'] . ' </td>
                    <td> ' . $encontrados['licencia'] . ' </td>
                    <td> ' . date("d-m-Y", strtotime($encontrados['date_disp'])) . ' </td>
                    <td> ' . $encontrados['selectOper'] . ' </td>
                    <td> ' . $encontrados['valid'] . ' </td>
                    <td> ' . $encontrados['suedo'] . ' </td>
                </tr>
            ';
            }
            ?>
        </table>
        <br>
    </div>
    <div class="nota">
        <label>Nota:</label>
        <ul>
            <li type="disc">Operadores contactados y disponibles a contar de la fecha indicada </li>
            <li type="disc">Sueldo operadores, según lo indicado </li>
            <li type="disc">Se solicita tomar contacto con la gente e iniciar su proceso acreditación </li>
            <li type="disc">Se Adjuntan Curriculum</li>
        </ul>
        <label>Atte.</label><br>
        <label>Operamaq Empresa SpA</label>
    </div>
    <div class="footer">
        <hr>
        <center><a href="https://operamaq.cl/" target="_blank">www.operamaq.cl</a> - venta@operamaq.cl – Cel. +56 9 6362 6835</center>
    </div>
</body>

</html>

<?php
$nombre = $numero;
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
$dompdf->setPaper('letter');

$dompdf->render();

$canvas = $dompdf->getCanvas();
$footer = $canvas->open_object();
$canvas->page_text(550, 18, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0));
$canvas->close_object();
$canvas->add_object($footer, "all");

$dompdf->stream("$nombre.pdf", array("Attachment" => false));

?>