<?php
session_start();
error_reporting(0);
date_default_timezone_set('America/Santiago');

require_once('../admin/conex.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
} else {
    header("Location: ../cliente.php");
    exit();
}


$valorBusqueda = isset($_POST['buscar']) ? mysqli_real_escape_string($conn, $_POST['buscar']) : '';
$empresa = isset($_POST['empresa']) ? mysqli_real_escape_string($conn, $_POST['empresa']) : '';
$faena = isset($_POST['faena']) ? mysqli_real_escape_string($conn, $_POST['faena']) : '';

$sql = "SELECT * FROM `detallle_ot` 
        WHERE (rut LIKE '%$valorBusqueda%' 
            OR nombre LIKE '%$valorBusqueda%' 
            OR id_ot LIKE '%$valorBusqueda%'
            OR status LIKE '%$valorBusqueda%' 
            OR equipo LIKE '%$valorBusqueda%' 
            OR modelo LIKE '%$valorBusqueda%' 
            OR faena LIKE '%$valorBusqueda%') 
        AND empresa = '$empresa' 
        AND faena = '$faena' AND patente = ''";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table width='100%' class='tabla table table-striped' style='font-size: 12px;' border='1'>
            <tr>
                <th>Folio</th>
                <th>Rut</th>
                <th>Nombre</th>
                <th>Equipo</th>
                <th title='ORDEN DE TRABAJO'>OT</th>
                <th title='PRUEBA TERORICA'>T</th>
                <th title='CHEQUEO DOCUMENTAL'>D</th>
                <th title='PRUEBA PRACTICA'>P</th>
                <th title='INFORME DE BRECHAS'>BR</th>
                <th><i class='fa fa-upload' aria-hidden='true'></i> BR</th>
                <th>VB</th>
                <th>STATUS</th>
                <th>CERT</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $ruta = $row['ruta_firma'];
        $certificate = $row['certificate'];
        $ot = $row['id_ot'];
        $Equipo = $row['equipo'];
        $EquipoFormateado = str_replace('_', ' ', $Equipo);

        $iconT = ($row['resultado'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA TEORICA REALIZADA CON FECHA ' . date("d-m-Y H:m:s", strtotime($row['date_out'])) . '"></i>' : '';
        $iconD = ($row['doc'] == 'SI') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="DOCUMENTACION REVISADA"></i>' : ($row['doc'] == 'NO' ? '<i class="fa fa-times fa-lg" aria-hidden="true"></i>' : '');
        $iconP = ($row['informe'] != '') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="PRUEBA PRACTICA REALIZADA"></i>' : '';

        $informe = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id = '$id' AND date_brecha !='0000-00-00 00:00:00'");
        if (mysqli_num_rows($informe) > 0) {
            while ($rst = mysqli_fetch_array($informe)) {
                $info = '<i class="fa fa-file-text-o fa-lg abrir-popup" data-informe="'.$rst['id'].'" aria-hidden="true" title="INFORME DE BRECHAS"></i>';
                $submit = '<i class="fa fa-upload fa-lg abrir-upload" data-Submit="'.$rst['id'].'" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i>';
                
                    if($row['info_brechas'] !='' && $row['brecha'] == ''){
                        $vb = '<i class="fa fa-info-circle fa-lg" style="color: #F1C40F;" aria-hidden="true" title="PENDIENTE DE APROBACIÓN '.$rstInf['id'].'"></i>';
                    }elseif($row['info_brechas'] !='' && $row['brecha'] == 'APROBADO'){
                        $vb = '<i class="fa fa-check fa-lg" aria-hidden="true" title="APROBACIÓN REALIZADA '.$rstInf['id'].'"></i>';
                        $submit = '';
                    }elseif($row['info_brechas'] !='' && $row['brecha'] == 'RECHAZADO'){
                        $vb = '<i class="fa fa-times fa-lg" aria-hidden="true" title="APROBACIÓN RECHAZADA"></i>';
                    }
            }
        } else {
            $info = '';
            $submit = '';
            $vb = '';
        }

        $imgFirma = ($ruta == '') ? '' : '<a href="'.$ruta.'" target="_blank"><i class="fa fa-file-pdf-o ruta fa-lg" aria-hidden="true" style="color: red;"></i></a>';
        $estado = ($certificate == 'APROBADO') ? '<i class="fa fa-check fa-lg" aria-hidden="true" title="OPERADOR ACREDITADO"></i>' : ($certificate == 'RECHAZADO' ? '<i class="fa fa-times fa-lg" aria-hidden="true" title="OPERADOR RECHAZADO"></i>' : '');

        echo "<tr>
                <td>" . $row['folio'] . "</td>
                <td>" . $row['rut'] . "</td>
                <td>" . $row['nombre'] . "</td>
                <td>" . $EquipoFormateado . "</td>
                <td style='color: #2ECC71; cursor: pointer;' title='ORDEN DE TRABAJO N° ". $ot ." '><b>" . $ot . "</b></td>
                <td>" . $iconT . "</td>
                <td>" . $iconD . "</td>
                <td>" . $iconP . "</td>
                <td>" . $info . "</td>
                <td>". $submit ."</td>
                <td>" . $vb . "</td>
                <td>" . $estado . "</td>
                <td>" . $imgFirma . "</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}
$conn->close();
?> 