<?php require('../admin/conex.php'); 
// Verificar si se recibieron los datos esperados
if (isset($_POST['iduser'])) {
    // Obtener los valores enviados
    echo $equipo1 = $_POST['equipo1'];
    echo $equipo2 = $_POST['equipo2'];
    echo $sueldo = $_POST['sueldo'];
    echo $experiencia = $_POST['experiencia'];
    echo $licencia = $_POST['licencia'];
    echo $trabajando = $_POST['trabajando'];
    echo $revision = $_POST['revision'];
    echo $status2 = $_POST['status2'];
    echo $iduser = $_POST['iduser'];
  
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
    // Realizar acciones con los datos recibidos
    $insertSQL = sprintf("UPDATE operadores SET equipo1=".GetSQLValueString(trim($equipo1), "text").", equipo2=".GetSQLValueString(trim($equipo2), "text").", id_rango_sueldo=".GetSQLValueString(trim($sueldo), "text").", trabajando=".GetSQLValueString(trim($trabajando), "text").", cumple_requisitos=".GetSQLValueString(trim($revision), "text").", experiencia=".GetSQLValueString(trim($experiencia), "text").", licencia=".GetSQLValueString(trim($licencia), "text").", status2=".GetSQLValueString(trim($status2), "text")." WHERE Id = ".$iduser."");

    $Result1 = mysqli_query($conn,$insertSQL);
    // Enviar una respuesta al cliente (opcional)
    //echo "Datos recibidos correctamente señor". $campo1. "y". $campo2;
  } else {
    // No se recibieron los datos esperados
    echo "Error al recibir los datos";
  }
?>
