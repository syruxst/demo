<?php
ob_start();
session_start();
require_once('../admin/conex.php');
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}
$variableEncriptada = $_GET['dato'];
$miVariable = base64_decode(urldecode($variableEncriptada));
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: EXMANE ::</title>
    <style>
        :root{
            --color : #e5e5e5;
            --colorHover: #04C9FA;
            --colorButton: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 10px;
        }
        .container {
            border-radius: 10px;
            border: 1px solid var(--color);
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        /* Estilos para la clase "tabla" */
        .tabla {
            padding: 10px;
            border-radius: 5px;
        }
        /* Estilos para la clase "row" */
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Estilos para la clase "col" */
        .col {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 3px;
            margin: 5px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
        }
        hr{
            border: 1px solid var(--color);
        }
        section{
            border: 1px solid var(--color);
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
        }
        label {
    padding: 15px;
    border-radius: 5px;
    cursor: pointer;
    border: 1px solid var(--color);
    margin-bottom: 3px;
    white-space: normal; /* Permite el ajuste automático del texto en múltiples líneas */
}
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
            .row {
                width: 100%; 
                display: block;
            }
            .col {
                width: 100%; 
                float: none;
            }
            .tabla {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <hr>
    <?php
        $buscarExamen = mysqli_query($conn, "SELECT * FROM `$miVariable`");
        $n = 1;
        while ($mostrar = mysqli_fetch_array($buscarExamen)) {
            echo '<section class="pregunta" data-id="' . $mostrar['id'] . '">';
            echo '<h5 name="pregunta' . $mostrar['id'] . '">'.$n.'. ' . $mostrar['PREGUNTA'] .'</h5>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="1"> ' . $mostrar['R1'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="2"> ' . $mostrar['R2'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="3"> ' . $mostrar['R3'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="4"> ' . $mostrar['R4'] . '</label>';
            echo '</section>';
            $n++;
        }
    ?>
    </div>
</body>
</html>
<?php
$nombre = $miVariable;
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