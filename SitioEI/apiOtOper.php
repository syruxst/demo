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
$data = $_GET['dataPerfil'];

$datos = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE ip = '$data' AND resultado != '' AND estado = '' AND doc = 'SI'");
echo '<hr>';
echo '<table width="100%" border="0" class="tabla table table-striped responsive-font">';
echo '<tr>';
echo '<th>FOLIO</th><th>OT</th><th>VISITA</th><th>OPERADOR</th><th>EQUIPO</th><th>PT</th><th>LC</th><th>CV</th><th>PP</th><th title="CREAR INFORME">INF.</th>';
while($row = mysqli_fetch_array($datos)){
    $Rut = $row['rut'];
    $id_ot = $row['id_ot'];
    $Ot = base64_encode($id_ot);
    if($row['resultado'] != ''){

        $info = ($row['informe'] == '') ? '<i class="fa fa-file-text-o informe-icon" name="idOt[]" aria-hidden="true" title="CREAR INFORME" data-id="'.$row['id'].'"></i>' : '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="'.($row['informe'] == 'APROBADO' ? 'APROBADO' : 'REALIZADO').'"></i>';

        $re = ($row['informe'] == 'APROBADO') ? '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="APROBADO"></i>' : (($row['informe'] == 'RECHAZADO') ? '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: red;" title="RECHAZADO"></i' : '');


        $r = ($row['resultado'] == 'REPROBADO') ? '<i class="fa fa-check" aria-hidden="true" style="color: #3FFF33;" title="REALIZADO"></i>' : (($row['resultado'] == 'APROBADO') ? '<i class="fa fa-check" aria-hidden="true" style="color: #3FFF33;"></i>' : '');

    }

    echo '<tr>';
    echo '<td>'.$row['folio'].'</td>';
    echo '<td><a href="../ajax/ot_pdf.php?ttw='.$Ot.'" title="VER ORDEN DE TRABAJO" target="_blank" style="text-decoration:none;">'.$id_ot.'</a></td>';
    echo '<td>'.date("d-m-Y", strtotime($row['fecha'])).'</td>';
    echo '<td align="left">'.$row['nombre'].'</td>';
    echo '<td align="left">'.$row['equipo'].'</td>';
    echo '<td>'.$r.'</td>';

    $buscar = mysqli_query($conn, "SELECT * FROM operadores WHERE rut = '$Rut'");
    $row2 = mysqli_fetch_array($buscar);
    $lc = $row2['foto_licencia'];
    $cv = $row2['nombre_archivo'];

    echo '<td><a href="https://acreditasys.tech/licencias/'.$lc.'" target="_blank" title="VER LICENCIA DE CONDUCIR"><i class="fa fa-id-card-o" aria-hidden="true"></i></a></td>';
    echo '<td><a href="https://acreditasys.tech/uploads_op/'.$cv.'" target="_blank" title="VER CURRICULUM"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
    echo '<td>'.$re.'</td>';
    echo '<td>'.$info.'</td>';
    echo '</tr>';
}
echo '</table>';
?>