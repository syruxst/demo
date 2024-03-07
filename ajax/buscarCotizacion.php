<?php 
require_once('../admin/conex.php');
$empresas = $_GET['empresas'];

// Buscar cotizaciones asociados a la empresa
$buscarCotizacion = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE `name_cliente` = '$empresas'");

while($encontrado = mysqli_fetch_array($buscarCotizacion)){
    echo "<option value='".$encontrado['folio']."'>".$encontrado['folio']."</option>";
}

// Cerrar la conexión
$conn->close();
?>
