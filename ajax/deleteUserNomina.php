<?php
// Conexión a la base de datos
require_once('../admin/conex.php');
// Verificar si se ha enviado el ID del registro
if (isset($_POST['id'])) {
  // Obtener el ID del registro a eliminar desde la petición AJAX
  $id = $_POST['id'];
  // Escapar el ID para evitar inyección de SQL
  $id_esc = mysqli_real_escape_string($conn, $id);
  // Realizar la consulta de eliminación
  $eliminarRegistro = mysqli_query($conn, "UPDATE `operadores` SET trabajando = '0', empresa = '', faena = '', id_nomina = '0', date_inicio = '', date_termino =  '', suedo = '', job = '0', date_disp = '', selectOper = '', valid = '' WHERE Id = '$id_esc'");
  // Verificar si la eliminación fue exitosa
  if ($eliminarRegistro) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el Operador de la Nomina.']);
  }
}
?>

