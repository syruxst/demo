<?php
date_default_timezone_set('America/Santiago');
require_once('../admin/conex.php');
error_reporting(0);

$fechaInicio = $_POST['inicio'];
$fechaFin = $_POST['fin'];

// Ajustar fecha de inicio para incluir desde las 00:00:00
$fechaInicio = $fechaInicio . " 00:00:00";

// Ajustar fecha de fin para incluir hasta las 23:59:59
$fechaFin = $fechaFin . " 23:59:59";

$eva = $_POST['eva'];

$query = "SELECT * FROM `informes` WHERE fechaInforme BETWEEN '$fechaInicio' AND '$fechaFin'";
$query .= ($eva != "todos") ? " AND userInforme = '$eva'" : "";


//$query = "SELECT * FROM `informes` WHERE userInforme = '$eva' AND fechaInforme BETWEEN '$fechaInicio' AND '$fechaFin'";
$ver = $conn->query($query);

if ($ver->num_rows > 0) {
    // Obtener la primera fila (puedes ajustar esto según tus necesidades)
    $row = $ver->fetch_assoc();
    
    // Obtener el nombre de la primera fila
    //$Nombre = $row['name'];

    echo 'Se encontraron ' . $ver->num_rows . ' informes en el rango de fechas proporcionado. <br>';
    echo 'Para el usuario ' . $eva . '';
} else {
    echo 'No se encontraron informes en el rango de fechas proporcionado.';
}
// Consulta SQL para obtener los registros entre las fechas dadas
$sql = "SELECT * FROM `document` WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND estado = 'ABIERTA'";
$sql .= ($eva != "todos") ? " AND user = '$eva'" : "";

$resultado = $conn->query($sql);
// Verificar si se obtuvieron resultados
if ($resultado->num_rows > 0) {
    // Imprimir la tabla HTML con los resultados
    echo '<hr>';
    echo '<table width="100%" border="0" class="table table-striped responsive-font">';
    echo '<tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>E</th>
            <th>V</th>
            <th>VALOR</th>
            <th>N° B/F</th>
            <th>RESP</th>
            <th>Banco</th>
            <th>Tipo Cta</th>
            <th>N° Cta</th>
            <th>RUN</th>
            <th>Vencimiento</th>
          </tr>';

    while($Rows = mysqli_fetch_array($resultado)){
        // La fecha actual de la variable $Rows['dateBoleta']
        $vencimiento = $Rows['dateBoleta'];

        // Crear un objeto DateTime con la fecha actual
        $fecha = new DateTime($vencimiento);

        // Sumar 30 días
        $fecha->add(new DateInterval('P30D'));

        // Obtener la nueva fecha
        $nuevaFecha = $fecha->format('Y-m-d');

        // Obtener la fecha actual
        $fechaActual = date('Y-m-d');

        // Definir el color base
        $color = 'black';

        // Verificar condiciones y ajustar el color
        if ($fecha->diff(new DateTime())->days <= 15) {
            $color = 'orange'; // Cambiar a naranja si faltan 15 días o menos
        } elseif ($nuevaFecha < $fechaActual) {
            $color = 'red'; // Cambiar a rojo si la nueva fecha es menor a la fecha actual
        }

        echo '<tr>
                <td>' . $Rows['codigo'] . '</td>
                <td>' . $Rows['nombre'] . '</td>
                <td>' . $Rows['cantidad'] . '</td>
                <td>' . $Rows['visitas'] . '</td>
                <td> $' . number_format($Rows['total'], 0, ',', '.'). '</td>
                <td>' . $Rows['boleta'] . ' <a href="https://operamaq.cl/nuevo/SitioEI/boletas/'.$Rows['ruta'].'" target="_blank" title="VER BOLETA O FACTURA"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>
                <td> <a href="https://operamaq.cl/nuevo/SitioEI/respaldo/' . $Rows['respaldo'] .'" target="_blank" title="VER RESPALDO"><i class="fa fa-file-image-o" aria-hidden="true"></i></a></td>
                <td>' . $Rows['banco'] . '</td>
                <td>' . $Rows['cta'] . '</td>
                <td>' . $Rows['numerocta'] . '</td>
                <td>' . $Rows['rut'] . '</td>
                <td><span style="color: '.$color.';">' . date("d-m-Y", strtotime($nuevaFecha)) . '</span></td>
              </tr>';    
    }
    echo '</table>';
} else {
    echo "No se encontraron registros entre las fechas dadas.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>