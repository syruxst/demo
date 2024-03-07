<?php
session_start();
error_reporting(1);
require_once('../../admin/conex.php');

$usuario = $_SESSION['usuario'];

// Consulta en la tabla usuarios
$consulta_usuarios = "SELECT permiso FROM usuarios WHERE usuario = '$usuario'";
$resultado_usuarios = mysqli_query($conn, $consulta_usuarios);

if (mysqli_num_rows($resultado_usuarios) > 0) {
    // Si encuentra un resultado en la tabla usuarios, obtén el permiso
    $fila = mysqli_fetch_array($resultado_usuarios);
    $perfil = $fila['permiso'];
} else {
    // Si no encuentra un resultado en la tabla usuarios, busca en la tabla insp_eva
    $consulta_insp_eva = "SELECT permiso FROM insp_eva WHERE user = '$usuario'";
    $resultado_insp_eva = mysqli_query($conn, $consulta_insp_eva);

    if (mysqli_num_rows($resultado_insp_eva) > 0) {
        // Si encuentra un resultado en la tabla insp_eva, obtén el permiso
        $fila = mysqli_fetch_array($resultado_insp_eva);
        $perfil = $fila['permiso'];
    } else {
        // Si no encuentra el usuario en ninguna tabla, asigna un valor por defecto o muestra un mensaje de error
        $perfil = "";
    }
}

$valorBusqueda = $_GET['valorBusqueda'];

$sql = "SELECT * FROM `registros` 
        WHERE (nombre LIKE '%$valorBusqueda%' OR codigo LIKE '%$valorBusqueda%' OR version LIKE '%$valorBusqueda%')
        AND (perfil LIKE '%$perfil%' OR perfil LIKE '%$perfil,%' OR perfil LIKE '%, $perfil' OR perfil LIKE '%, $perfil,%')
        ORDER BY version DESC, codigo ASC";

$buscar = mysqli_query($conn, $sql);

echo '<table width="100%" border="0" class="tabla table table-striped responsive-font">';

echo '<tr class="cabecera">
    <th>N°</th>
    <th>Código Proc.</th>
    <th id="nombre" align="left">Nombre del Registro</th>
    <th id="codigo" align="left">Código del Registro</th>
    <th id="version" align="left">Versión</th>
    <th>Archivo</th>
</tr>';

$n = 1;
while($fila = mysqli_fetch_array($buscar)){
    echo '<tr>';
    echo '<td>'.$n.'</td>';
    echo '<td align="left">'.$fila['id_proc'].'</td>';
    echo '<td align="left">'.$fila['nombre'].'</td>';
    echo '<td align="left">'.$fila['codigo'].'</td>';
    echo '<td>'.$fila['version'].'</td>';
    echo '<td><a href="'.$fila['ruta'].'" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
    echo '</tr>';
    $n++;
}
echo '</table>';
?>