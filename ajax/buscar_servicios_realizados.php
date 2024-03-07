<?php
date_default_timezone_set('America/Santiago');
require_once('../admin/conex.php');
error_reporting(0);

$busqueda = $_POST['input_busqueda']; 

$sql = "SELECT * FROM `detallle_ot` WHERE (
        `nombre` LIKE '%$busqueda%' OR
        `equipo` LIKE '%$busqueda%' OR
        `ip` LIKE '%$busqueda%' OR
        `folio` LIKE '%$busqueda%' OR
        `empresa` LIKE '%$busqueda%' OR
        `faena` LIKE '%$busqueda%') AND `estado` != ''";

$result = $conn->query($sql); 
if ($result !== false && $result->num_rows > 0) {

    echo '<table width="100%" border="0" class="table table-striped responsive-font">';
    echo '<tr><th>FOLIO</th><th>EMPRESA</th><th>OPERADOR</th><th>EQUIPO</th><th>STATUS</th><th>LC</th><th>CV</th><th>PP</th><th>PT</th><th>CERT</th></tr>';

    while ($row = $result->fetch_assoc()) {

        switch ($row['estado']) {

            case 'APROBADO':
                $Icon = 'check';
                $Color = '#3FFF33';
                break;
            
            case 'RECHAZADO':
                $Icon = 'times';
                $Color = '#FF0000';
                break;
        }
        $Icon = "<i class='fa fa-$Icon fa-1x' aria-hidden='true' style='color: $Color;'></i>";
        echo "<tr>";
        echo '<td>' . $row['ip']. ' '. $row['folio'] . '</td>';
        echo '<td>' .$row['empresa']. '</td>';
        echo '<td>'. $row['nombre'] . '</td>';
        echo '<td>'. $row['equipo'] . '</td>';
        echo '<td>' . $Icon. '</td>';
        echo '<td><a href="https://operamaq.cl//nuevo/licencias/'. $row['licencia'] .'" target="_blank" title="LICENCIA DE CONDUCIR"><i class="fa fa-id-card-o" aria-hidden="true"></i></a></td>';
        echo '<td><a href="https://operamaq.cl//nuevo/uploads_op/'. $row['cv'] .'" target="_blank" title="CURRICULUM"><i class="fa fa-address-book-o" aria-hidden="true"></i></a></td>';
        echo '<td><a href="" target="_blank" title="PRUEBA PRACTICA"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></td>';
        echo '<td><a href="" target="_blank" title="PRUEBA TEORICA"><i class="fa fa-file" aria-hidden="true"></i></a></td>';
        echo '<td><a href="" target="_blank" title="CERTIFICADO"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo "No se encontraron resultados para : " . $busqueda;
}

$conn->close();
?>