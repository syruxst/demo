<?php
session_start();
error_reporting(0);
require_once('../../admin/conex.php');

// Obtén el valor de búsqueda desde el parámetro GET
$valorBusqueda = $_GET['valorBusqueda'];

// Prepara la consulta SQL con LIKE para buscar en los campos relevantes
$sql = "SELECT * FROM `pruebasINN`
        WHERE nombre LIKE '%$valorBusqueda%' OR codigo LIKE '%$valorBusqueda%' OR version LIKE '%$valorBusqueda%'
        ORDER BY version DESC, codigo ASC";

$buscar = mysqli_query($conn, $sql);

echo '<table width="100%" border="0" class="tabla table table-striped">';
echo '<tr class="cabecera">
    <th>N°</th>
    <th id="nombre" align="left">Nombre de la Prueba</th>
    <th id="codigo" align="left">Código de la Prueba</th>
    <th id="version" align="left">Versión</th>
    <th>Archivo</th>
</tr>';

$n = 1;
while($fila = mysqli_fetch_array($buscar)){
    echo '<tr>';
    echo '<td>'.$n.'</td>';
    echo '<td align="left">'.$fila['nombre'].'</td>';
    echo '<td align="left">'.$fila['codigo'].'</td>';
    echo '<td>'.$fila['version'].'</td>';
    echo '<td><a href="'.$fila['ruta'].'" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
    echo '</tr>';
    $n++;
}
echo '</table>';
?>