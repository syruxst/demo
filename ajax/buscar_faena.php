<?php

require_once('../admin/conex.php');
error_reporting(0);


// Obtener los valores seleccionados de los campos

$estado = $_GET['estado'] ?? '';

$experiencia = $_GET['experiencia'] ?? '';

$tipo = $_GET['tipo'] ?? '';

$familia = $_GET['familia'] ?? '';

$selectMaquinaria1 = $_GET['selectMaquinaria1'] ?? '';

$region = $_GET['region'] ?? '';

$criterio = $_GET['criterio'] ?? '';

//Buscar operadores

$nombre =  $_GET['nombre'] ?? '';



// Construir la consulta SQL base

$sql = "SELECT * FROM operadores WHERE 1=1 ";

$sql_promedio = "SELECT AVG(id_rango_sueldo) AS promedio FROM operadores WHERE 1=1";



// Agregar condiciones a la consulta SQL según los valores de los parámetros

if ($estado != '') {

    $sql .= " AND trabajando = '$estado'";

    $sql_promedio .= " AND trabajando = '$estado'";

} else {

    $sql .= " AND (trabajando = '0' OR trabajando = '1' OR trabajando = '2')";

    $sql_promedio .= " AND (trabajando = '0' OR trabajando = '1' OR trabajando = '2')";

}



if ($experiencia != 'EXPERIENCIA') {

    $sql .= " AND experiencia >= '$experiencia'";

    $sql_promedio .= " AND experiencia >= '$experiencia'";

}



if ($tipo != '0') {

    $sql .= " AND status2 = '$tipo'";

    $sql_promedio .= " AND status2 = '$tipo'";

}





if ($nombre != '') {

    $sql .= " AND (nombre LIKE '%$nombre%' OR apellidos LIKE '%$nombre%')";

}



if ($selectMaquinaria1 != '') {

    $sql .= " AND equipo1 = '$selectMaquinaria1'";

    $sql_promedio .= " AND equipo1 = '$selectMaquinaria1'";

}



if ($region != '') {

    $sql .= " AND id_region = '$region'";

    $sql_promedio .= " AND id_region = '$region'";

}



if ($criterio != '0') {

    $sql .= " AND cumple_requisitos = '$criterio'";

    $sql_promedio .= " AND cumple_requisitos = '$criterio'";

}



// Ejecutar la consulta SQL

$resultado = mysqli_query($conn, $sql);

$resultado_promedio = mysqli_query($conn, $sql_promedio);





// Obtener el número de registros encontrados

$numRegistros = mysqli_num_rows($resultado);



echo "<script>document.getElementById('contador').innerHTML = '<?php echo $numRegistros; ?>';</script>";



// Verificar si se obtuvo un resultado

if ($resultado_promedio->num_rows > 0) {

    $row_promr = $resultado_promedio->fetch_assoc();

    $promedio = number_format($row_promr["promedio"], 0, ',', '.');

    echo "<tr><td colspan='7'>El Sueldo promedio es: $ <b>" . $promedio ."</b></td></tr>";

} else {

    echo "<tr><td colspan='7'>No se encontraron resultados.</td></tr>";

}



// Verificar si se encontraron resultados

if (mysqli_num_rows($resultado) == 0) {

    echo "<tr><td colspan='7'>No se encontraron resultados.</td></tr>";

} else {

    echo "<tr>";

    echo "<th></th>";

    echo "<th>NOMBRE</th>";

    echo "<th>TELEFONO</th>";

    echo "<th>EMPRESA</th>";

    echo "<th>FAENA</th>";

    echo "<th>EQUIPO</th>";

    echo "<th>F. TERMINO</th>";

    echo "</tr>";

    while ($row = mysqli_fetch_array($resultado)) {



        $cumple = $row['cumple_requisitos'];

        //$encryptedId = openssl_encrypt($row['Id'], COD, KEY);

          if ($cumple == 0) {

            $jiub = '<a href="#" class="edit-link" onclick="loadEditForm(\''.$row['Id'].'\')">

            <img src="../img/edit.jpg" title="EDITAR OPERADOR" width="20px" height="20px">

        </a>';

        } elseif ($cumple == 2) {

            $jiub = '';

        } else {

            $jiub = "<input type='checkbox' name='operadores[]' value='".$row['Id']."'>";

        }

        

        switch ($row['equipo1']) {

            case '13':

                $maq = 'Bulldozer D6';

                break;

            case '1':

                $maq = 'Bulldozer D8';

                break;

            case '14':

                $maq = 'Bulldozer D09';

                break;

            case '15':

                $maq = 'Bulldozer D10';

                break;

            case '2':

                $maq = 'Camión Aljibe 15 m3';

                break;

            case '3':

                $maq = 'Camión Aljibe 30 m3';

                break;

            case '19':

                $maq = 'Camión Dumper';

                break;

            case '24':

                $maq = 'Camión Lubricador';

                break;

            case '23':

                $maq = 'Camión Petroleador';

                break;

            case '4':

                $maq = 'Camión Pluma 5 ton';

                break;

            case '16':

                $maq = 'Camión Pluma 8 ton';

                break;

            case '17':

                $maq = 'Camión Pluma 10 ton';

                break;

            case '18':

                $maq = 'Camión Pluma 15 ton';

                break;

            case '5':

                $maq = 'Camión Tolva 20 m3';

                break;

            case '22':

                $maq = 'Cargador Frontal';

                break;

            case '6':

                $maq = 'Excavadora 20-22 Ton.';

                break;

            case '7':

                $maq = 'Excavadora 35 Ton.';

                break;

            case '8':

                $maq = 'Excavadora 50 Ton.';

                break;

            case '20':

                $maq = 'Excavadora 70 Ton.';

                break;

            case '21':

                $maq = 'Excavadora 80 Ton.';

                break;

            case '9':

                $maq = 'Minicargador';

                break;

            case '10':

                $maq = 'Motoniveladora';

                break;

            case '11':

                $maq = 'Retroexcavadora';

                break;

            case '25':

                $maq = 'Rigger';

                break;

            case '12':

                $maq = 'Rodillo Compactador';

                break;

            default:

                $maq = ''; // Valor por defecto si no coincide con ninguno de los casos anteriores

                break;

        }

        echo "<tr class='hover-row'>";

        echo "<td>".$jiub."</td>";

        echo "<td><a href='../uploads_op/".$row['nombre_archivo']."' target='_blank'>" . ucwords(strtolower($row['nombre'])) . " " . ucwords(strtolower($row['apellidos'])) . "</a></td>";

        echo "<td><a target='_blank' href='whatsapp://send?phone".urlencode($row['celular'])."&amp;text=".$familia."'>" . $row['celular'] . "</a></td>";

        echo "<td>" . $row['empresa'] . "</td>";

        echo "<td>" . $row['faena']. "</td>";

        echo "<td>" . $maq . "</td>";

        echo "<td>" . date('d-m-Y', $row['date_termino']) . "</td>";

        echo "</tr>";

    }

}



echo "<tr><td colspan='7'>

<input type='hidden' name='mensajeria' id='mensajeria' value='" . $familia . "'>

<input type='submit' value='Generar Nomina' class='btn btn-primary'> Numero de registros: " . $numRegistros . " " . $familia . "</td></tr>";

// Cerrar la conexión

$conn->close();

?>