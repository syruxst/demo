<?php
session_start();
error_reporting(0);
require_once('../../admin/conex.php');

$buscar = mysqli_query($conn, "SELECT * FROM nch ORDER BY codigo ASC");
echo '<table width="100%" border="0" class="tabla table table-striped">';
echo '<tr><th>N°</th><th align="left">Nombre</th><th align="left">Código</th><th>Rev</th><th>Archivo</th></tr>';
$n = 1;
while($fila = mysqli_fetch_array($buscar)){
echo '<tr>';
echo '<td>'.$n.'</td>';
echo '<td align="left">'.$fila['nombre'].'</td>';
echo '<td align="left">'.$fila['codigo'].'</td>';
echo '<td align="left">'.$fila['rev'].'</td>';
echo '<td><a href="'.$fila['ruta'].'" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
echo '</tr>';
$n++;
}
echo '</table>';
?>