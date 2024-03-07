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
echo '<th>FOLIO</th><th>OT</th><th>VISITA</th><th>PATENTE</th><th>EQUIPO</th><th title="CREAR INFORME">INF.</th>';
while($row = mysqli_fetch_array($datos)){
    $Rut = $row['rut'];
    $id_ot = $row['id_ot'];
    $Ot = base64_encode($id_ot);
    if($row['resultado'] != ''){

        //$info = ($row['informe'] == '') ? '<i class="fa fa-file-text-o informe-iconM" name="idOt[]" aria-hidden="true" title="CREAR INFORME" data-id="'.$row['id'].'"></i>' : '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="'.($row['informe'] == 'APROBADO' ? 'APROBADO' : 'RECHAZADO').'"></i>';

        switch($row['informe']){
            case '':
                $info = '<i class="fa fa-file-text-o informe-iconM" name="idOt[]" aria-hidden="true" title="CREAR INFORME" data-id="'.$row['id'].'"></i>';
                break;
            
            case 'APROBADO':
                $info = '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;"></i>';
                break;

            case 'RECHAZADO':
                $info = '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: #FF0000;"></i>';
                break;
        }

    }

    echo '<tr>';
    echo '<td>'.$row['folio'].'</td>';
    echo '<td><a href="../ajax/ot_pdf.php?ttw='.$Ot.'" title="VER ORDEN DE TRABAJO" target="_blank" style="text-decoration:none;">'.$id_ot.'</a></td>';
    echo '<td>'.date("d-m-Y", strtotime($row['fecha'])).'</td>';
    echo '<td align="left">'.$row['patente'].'</td>';
    echo '<td align="left">'.$row['equipo'].'</td>';
    echo '<td>'.$info.'</td>';
    echo '</tr>';
}
echo '</table>';
?>