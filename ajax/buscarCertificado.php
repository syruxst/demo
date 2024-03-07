<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');

// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
    $buscarUser = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$usuario'");
    $row = mysqli_fetch_array($buscarUser);
    $perfil = $row['permiso'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}

// Verifica si se recibieron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el término de búsqueda y la página actual enviados por POST
    $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
    $paginaActual = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;

    // Incluye el archivo de conexión a la base de datos
    require_once('../admin/conex.php');

    // Datos para la paginación
    $resultadosPorPagina = 10; // Cambia esto al número de resultados por página que desees
    $offset = ($paginaActual - 1) * $resultadosPorPagina;

    // Consulta SQL para filtrar resultados según el término de búsqueda, o sin filtrar si no hay término de búsqueda
    $query = "SELECT * FROM `detallle_ot`";
    if (!empty($searchTerm)) {
        $query .= " WHERE 
            (
            `ip` LIKE '%$searchTerm%' OR
            `folio` LIKE '%$searchTerm%' OR 
            `empresa` LIKE '%$searchTerm%' OR
            `nombre` LIKE '%$searchTerm%' OR 
            `equipo` LIKE '%$searchTerm%')
            AND estado != ''";
    } else {
        $query .= " WHERE estado != ''";
    }
    
    // Obtener el número total de resultados
    $totalResultadosQuery = mysqli_query($conn, $query);
    $totalResultados = mysqli_num_rows($totalResultadosQuery);

    // Calcular el número total de páginas
    $totalPaginas = ceil($totalResultados / $resultadosPorPagina);

    // Actualizar la consulta para aplicar límite y orden
    $query .= " LIMIT $offset, $resultadosPorPagina";

    $result = mysqli_query($conn, $query);

    // Genera la salida en formato HTML
    echo '<table width="100%" class="tabla table table-striped">
            <tr>
                <th>N°</th>
                <th>FOLIO</th>
                <th style="text-align: left;">EMPRESA</th>
                <th style="text-align: left;">NOMBRE</th>
                <th style="text-align: left;">EQUIPO</th>
                <th style="text-align: left;">STATUS</th>
                <th>SIN FIRMAR</th>
                <th>FIRMADO</th>
            </tr>';

    $n = 1;
    
    while ($row = mysqli_fetch_array($result)) {
        $ruta = $row['ruta_firma'];
        $id_ot = $row['id_ot'];
        $estado = $row['certificate'];

        $ticket = ($estado == 'APROBADO') ? "<i class='fa fa-check fa-1x' aria-hidden='true' style='color: #3FFF33;' title='APROBADO'></i>" : "<i class='fa fa-times fa-1x' aria-hidden='true' style='color: #FA0324;' title='RECHAZADO'></i>";

        // buscar empresa

        $empresa = mysqli_query($conn, "SELECT * FROM `ot` WHERE id_ot = '$id_ot'");

        while($ver = mysqli_fetch_array($empresa)){
            $id_Cotizacion = $ver['id_cotiz'];
            
                $cotiz = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE folio = '$id_Cotizacion'");
                while($ver_cot = mysqli_fetch_array($cotiz)){
                    $nombreEmpresa = $ver_cot['name_cliente'];
                }
        }

        $imgFirma = ($ruta == '') ? '' : '<a href="'.$ruta.'" target="_blank"><i class="fa fa-file-pdf-o ruta" aria-hidden="true" style="color: red;"></i></a>';

        echo '<tr>
            <td>' . $n . '</td>
            <td> '. $row['ip'] .' ' . $row['folio'] . '</td>
            <td align="left">' . $nombreEmpresa . '</td>
            <td align="left">' . $row['nombre'] . '</td>
            <td align="left">' . $row['equipo'] . '</td>
            <td align="center">' . $ticket . '</td>
            <td align="center"><i class="fa fa-file-pdf-o certif" aria-hidden="true" data-id="' . $row['id'] . '" style="color: red;"></i></td>
            <td>' . $imgFirma . '</td>
        </tr>';

        $n++;
    }

    echo '</table>';

    // Genera la paginación
    echo '<div class="center-pagination">';
    echo '<nav aria-label="...">
            <ul class="pagination pagination-sm">';
    for ($i = 1; $i <= $totalPaginas; $i++) {
        if ($i === $paginaActual) {
            echo '<li class="page-item active" aria-current="page">
                    <span class="page-link">' . $i . '</span>
                </li>';
        } else {
            echo '<li class="page-item">
                    <a class="page-link" href="#" data-pagina="' . $i . '">' . $i . '</a>
                </li>';
        }
    }
    echo '</ul></nav>';
    echo '</div>';

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
} else {
    // Si no se recibieron datos por POST, puedes devolver un mensaje de error o realizar otra acción apropiada.
    echo 'No se enviaron datos por POST.';
}
?>