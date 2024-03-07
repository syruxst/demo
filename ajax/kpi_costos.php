<?php session_start(); error_reporting(1);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../index.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../admin/conex.php');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Kpi Costos</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        body {
            font-family: 'Roboto', sans-serif;
            padding: 45px;
            color: #B2BABB;
            /*background-image: url('https://acreditasys.tech/img/SelloAguaDos.png');*/
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            justify-content: center;
            align-items: center;
        }
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000; 
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin: 15% auto; 
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input {
            width: 100%;
            text-align: center;
            border: 1px solid #ddd;
        }
        .container{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 7px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<center>COSTOS <?php echo $year = date('Y');?></center>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>
<div class="container">
    <?php
        $ano = date("Y");
        // obtener el valor de los promedios
        $sql = "SELECT ROUND(AVG(valor)) AS promedio_valor FROM insp_eva";
        $resultado = mysqli_query($conn, $sql);
        $fila = mysqli_fetch_assoc($resultado);

        // Promedio evaluador cert Operadores
        $promedio = $fila['promedio_valor'];

        $query_cert = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad_total 
                        FROM serviceCot 
                        WHERE YEAR(fecha_creacion) = $ano 
                        AND (servicio='CERT OPERADOR 3 (51- En Adelante)' 
                            OR servicio='CERT OPERADOR 2 (21-50)' 
                            OR servicio='CERT OPERADOR 1 (1-20)') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

        $result_cert = $conn->query($query_cert);

        $data_cadena_cert = array_fill(0, 12, 0);

        while ($row_cert = $result_cert->fetch_assoc()) {
            $mes = intval($row_cert['mes']) - 1;
            $cantidad_cert = is_null($row_cert['cantidad_total']) ? 0 : intval($row_cert['cantidad_total']);

            // Multiplicar el valor por mes por la variable $promedio
            $cantidad_cert *= $promedio;

            // Rellenar el array correspondiente según la categoría
            $data_cadena_cert[$mes] = $cantidad_cert; 
        }

        /*****fin de certificacones****/

        $query_eva = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad_total 
                        FROM serviceCot 
                        WHERE YEAR(fecha_creacion) = $ano 
                        AND (servicio='EVA LEY 1 CHV (EXC-ALJIBE-RETROEX)' 
                            OR servicio='EVA LEY 2 CHV (BULL-TTES PERSONAL)' 
                            OR servicio='EVA LEY 3 CHV (MOTO-TOLVA-CARGADOR-EQ. MOVILES)' 
                            OR servicio='EVA LEY 5 CHV (RIGGER ALTA)' 
                            OR servicio='EVA LEY 4 CHV (MANIPULADOR-HORQUILLA-ALZA HOMBRE)') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

        $result_eva = $conn->query($query_eva);

        $data_cadena_eva = array_fill(0, 12, 0);
        
        while ($row_eva = $result_eva->fetch_assoc()) {
            $mes = intval($row_eva['mes']) - 1;
            $cantidad_eva = is_null($row_eva['cantidad_total']) ? 0 : intval($row_eva['cantidad_total']);
        
            // Multiplicar el valor por mes por la variable $promedio
            $cantidad_eva = $cantidad_eva * ($promedio * 2.4);
        
            // Rellenar el array correspondiente según la categoría
            $data_cadena_eva[$mes] = $cantidad_eva; 
        }

        /*****fin de evaluadores****/

        $query_Inps = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad 
                        FROM `serviceCot`
                        WHERE YEAR(fecha_creacion) = $ano 
                        AND (servicio='CERT EQUIPO 1 (1-10)' 
                            OR servicio='CERT EQUIPO 2 (11-20)' 
                            OR servicio='CERT EQUIPO 3 (21-En Adelante)' 
                            OR servicio='CERT EQUIPO ESTACINARIO') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";
        
        $result_inps = $conn->query($query_Inps);

        $data_cadena_inps = array_fill(0, 12, 0);
        
        while ($row_inps = $result_inps->fetch_assoc()) {
            $mes = intval($row_inps['mes']) - 1;
            $cantidad_inps = is_null($row_inps['cantidad']) ? 0 : intval($row_inps['cantidad']);
        
            // Multiplicar el valor por mes por la variable $promedio
            $cantidad_inps = $cantidad_inps * $promedio;
        
            // Rellenar el array correspondiente según la categoría 
            $data_cadena_inps[$mes] = $cantidad_inps; 
        }

        $sql_costo = mysqli_query($conn, "SELECT * FROM `costos_real_$year`");

        // Almacenar los resultados en un array asociativo para facilitar el acceso
        $resultados_costo = array();
        while ($rst_costo = mysqli_fetch_array($sql_costo)) {
            $resultados_costo[$rst_costo['mes']] = $rst_costo;
        }
        ?>
    <table border="0">
        <tr>
            <th>&nbsp;</th>
            <?php
                for ($i = 1; $i <= 12; $i++) {
                    echo "<th>$i</th>";
                }
            ?>
        </tr>

        <?php
            echo "<tr style='background-color: yellow;'><td style='text-align: left;'>Evaluador Cert. Oper.</td>";
            for ($i = 1; $i <= 12; $i++) {
                $hiddenName = $i;
                $inputName = "costo_a_$i";
                $defaultValue = $data_cadena_cert[$i - 1];
                echo '<td>';
                echo '<input type="hidden" name="' . $hiddenName . '" value="' . $hiddenName . '">';
                echo '<input type="text" name="' . $inputName . '" value="' . $defaultValue . '" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="11">';
                echo '</td>';
            }
            echo "</tr>";
        ?>
        <?php
            echo "<tr style='background-color: yellow;'><td style='text-align: left;'>Evaluador ChileValor</td>";
            for ($i = 1; $i <= 12; $i++) {
                $hiddenName_eva = $i;
                $inputName_eva = "costo_b_$i";
                $defaultValue_eva = $data_cadena_eva[$i - 1];
                echo '<td>';
                echo '<input type="hidden" name="' . $hiddenName_eva . '" value="' . $hiddenName_eva . '">';
                echo '<input type="text" name="' . $inputName_eva . '" value="' . $defaultValue_eva . '" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="11">';
                echo '</td>';
            }
            echo "</tr>";
        ?>
        <?php
            echo "<tr style='background-color: yellow;'><td style='text-align: left;'>Inspector</td>";
            for ($i = 1; $i <= 12; $i++) {
                $hiddenName_inps = $i;
                $inputName_inps = "costo_c_$i";
                $defaultValue_inps = $data_cadena_inps[$i - 1];
                echo '<td>';
                echo '<input type="hidden" name="' . $hiddenName_inps . '" value="' . $hiddenName_inps . '">';
                echo '<input type="text" name="' . $inputName_inps . '" value="' . $defaultValue_inps . '" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="11">';
                echo '</td>';
            }
            echo "</tr>";
        ?>
    </table>
    <hr>
    <table id="secondTable">
        <tr>
            <th>&nbsp;</th>
            <?php
                for($i = 1; $i <= 12; $i++){
                    echo "<th>$i</th>";
                }
            ?>
        </tr>
        <?php
            $titulos = array("mano_de_obra", "evaluador_cet_oper", "evaluador_chilevalora", "inspector", "arriendo_maq", "arriendo_ofcina", "contador", "redes", "ti", "gc", "viatico", "telefonia", "art_oficina", "credito_banco", "inv_acreditacion", "retiro_utilidades", "bono_personal", "varios");

            for ($row = 0; $row < count($titulos); $row++) {
                echo '<tr>';
                echo '<td style="text-align: left;">' . $titulos[$row] . '</td>';
            
                $total = 0; // Inicializar el total para cada fila
            
                // Bucle interno
                for ($mes = 1; $mes <= 12; $mes++) {
                    $valor = isset($resultados_costo[$mes]) ? $resultados_costo[$mes][$titulos[$row]] : 0; // Obtener el valor actual

                    // Obtener el valor del mes en esta iteración
                    $mesActual = $mes;
                    echo '<td>
                            <input type="hidden" name="'. $mesActual .'" value="' . $mesActual .'">
                            <input type="text" name="input_' . ($row + 1) . '_' . $mesActual . '" value="' . $valor . '" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="11">
                        </td>';
                }
                echo '</tr>';
            }
        ?>
    </table>
    <hr>
    <button id="guardarButton" onclick="guardarDatos()" class="btn btn-success" title="ACTUALIZAR DATOS">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
    </button>
</div>
<?php
mysqli_close($conn);
?>
<script>
    function guardarDatos() {
        var table = document.getElementById("secondTable");
        var formData = new FormData();

        // Iterate through each row in the table
        for (var i = 1; i < table.rows.length; i++) {
            var rowData = table.rows[i].cells;
            var title = rowData[0].innerText.trim();

            formData.append('titles[]', title);

            // Iterate through each input field in the row
            for (var j = 1; j < rowData.length - 1; j++) {
                var monthHidden = rowData[j].querySelector('input[type="hidden"]');
                var monthText = rowData[j].querySelector('input[type="text"]');
                
                // Obtener el valor del campo hidden y text
                var monthHiddenValue = monthHidden.value;
                var monthTextValue = monthText.value;

                // Log the values
                //console.log('Hidden Field Value:', monthHiddenValue);
                //console.log('Text Field Value:', monthTextValue);

                formData.append('data[' + title + '][' + j + '][hidden]', monthHiddenValue);
                formData.append('data[' + title + '][' + j + '][text]', monthTextValue);
            }
        }

        // Use AJAX to send the data to save_kpi.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_costo.php', true);
        xhr.onload = function () {

            try {
                var response = JSON.parse(xhr.responseText);

                if (response && response.status === 'success' && response.message) {
                    console.log(response.message);
                    swal("¡Bien hecho!", response.message, "success");
                    // Manejar el éxito, por ejemplo, mostrar un mensaje de éxito al usuario
                } else {
                    console.error('Error al guardar datos: ' + (response ? response.message : 'Respuesta indefinida'));
                    swal("Algo salió mal!", "Error al guardar datos!", "error");
                    // Manejar el error, por ejemplo, mostrar un mensaje de error al usuario
                }
            } catch (e) {
                console.error('Error al analizar la respuesta JSON:', e);
            }
        };
        xhr.onload = function () {
            console.log('Respuesta completa del servidor:', xhr.responseText);

            try {
                var response = JSON.parse(xhr.responseText);

                if (response && response.status === 'success' && response.message) {
                    console.log(response.message);
                    swal("¡Bien hecho!", response.message, "success");
                    // Manejar el éxito, por ejemplo, mostrar un mensaje de éxito al usuario
                } else {
                    console.error('Error al guardar datos: ' + (response ? response.message : 'Respuesta indefinida'));
                    swal("Algo salió mal!", "Error al guardar datos!", "error");
                    // Manejar el error, por ejemplo, mostrar un mensaje de error al usuario
                }
            } catch (e) {
                console.error('Error al analizar la respuesta JSON:', e);
            }
        };
        xhr.send(formData);
    }
</script>
</body>
</html>