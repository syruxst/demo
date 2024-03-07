<?php
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
';
if (isset($_POST['query'])) {
require_once('../admin/conex.php'); 
$busqueda = mysqli_real_escape_string($conn, $_POST['query']);
$paginaActual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$resultadosPorPagina = 10;
$inicio = ($paginaActual - 1) * $resultadosPorPagina;

$sql = "SELECT * FROM empresa WHERE nombre LIKE '%$busqueda%' OR rut LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' LIMIT $inicio, $resultadosPorPagina";
$resultado = mysqli_query($conn, $sql);

echo '<table width="100%" border="0" class="tabla table table-striped">';
echo '<tr>
        <th>N°</th>
        <th>NOMBRE</th>
        <th>R.U.T</th>
        <th>CONTACTO</th>
        <th>TELEFONO</th>
        <th>EMAIL</th>
      </tr>';

$n = 1 + $inicio;
while ($row = mysqli_fetch_array($resultado)) {
    $tel = $row['telefono'];
    $Telefono = '+'.preg_replace('/[^0-9]/', '', $tel);

    echo "<tr>";
    echo "<td>".$n."</td>";
    echo "<td><a href='#' title='EDITAR EMPRESA' onclick='mostrarDiv(\"" . $row['id'] . "\")'>" . $row['nombre'] . "</a></td>";
    echo "<td>".$row['rut']."</td>";
    echo "<td>".ucwords(strtolower($row['contacto']))."</td>";
    echo '<td><a href="tel:' . $Telefono . '" title="HACER LLAMADA">' . $tel . '</a></td>';
    echo "<td><a href='mailto: ".$row['correo']."'' title='ENVIAR CORREO'>".$row['correo']."</td>";
    echo "</tr>";
    $n++;
}

echo '</table>'; // Cierre de la tabla aquí

// Obtener el número total de resultados
$sqlTotal = "SELECT COUNT(*) as total FROM empresa WHERE nombre LIKE '%$busqueda%' OR rut LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR correo LIKE '%$busqueda%'";
$resultadoTotal = mysqli_query($conn, $sqlTotal);
$filaTotal = mysqli_fetch_assoc($resultadoTotal);
$totalResultados = $filaTotal['total'];
$totalPaginas = ceil($totalResultados / $resultadosPorPagina);

// Mostrar los enlaces de paginación
echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';

if ($paginaActual > 1) {
  echo '<li class="page-item"><a class="page-link pagina" href="javascript:void(0)" data-pagina="'.($paginaActual - 1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
} else {
  echo '<li class="page-item disabled"><span class="page-link"><span aria-hidden="true">&laquo;</span></span></li>';
}

for ($i = 1; $i <= $totalPaginas; $i++) {
  if ($i == $paginaActual) {
    echo '<li class="page-item active"><a class="page-link" href="javascript:void(0)">'.$i.'</a></li>';
  } else {
    echo '<li class="page-item"><a class="page-link pagina" href="javascript:void(0)" data-pagina="'.$i.'">'.$i.'</a></li>';
  }
}

if ($paginaActual < $totalPaginas) {
  echo '<li class="page-item"><a class="page-link pagina" href="javascript:void(0)" data-pagina="'.($paginaActual + 1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
} else {
  echo '<li class="page-item disabled"><span class="page-link"><span aria-hidden="true">&raquo;</span></span></li>';
}

echo '</ul></nav>';

mysqli_close($conn);
}
?>