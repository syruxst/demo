<?php 
require_once('../admin/conex.php');
$coti = $_GET['coti'];

// Realiza la consulta a la base de datos
$datosCotizacion = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE `folio` = '$coti'");
$rows = mysqli_fetch_array($datosCotizacion);

$contact = $rows['contacto'];
$para = $rows['correo'];
$faena = $rows['faena'];



$datos = array(
  'contacto' => $contact,
  'faena' => $faena,
  'para' => $para

);

echo json_encode($datos);

// Cerrar la conexión
$conn->close();
?>