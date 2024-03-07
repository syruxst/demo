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
    <title>KPI</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        body {
            font-family: 'Roboto', sans-serif;
            padding: 50px;
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
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input {
            width: 40px;
            text-align: center;
            border: 1px solid #ABB2B9;
        }
        .container{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>

<center>KPI <?php echo $year = date('Y');?></center>

<div class="container">
    <?php
    // Realizar una única consulta para obtener todos los datos
    $sql = mysqli_query($conn, "SELECT * FROM `kpi_table`");

    // Almacenar los resultados en un array asociativo para facilitar el acceso
    $resultados = array();
    while ($rst = mysqli_fetch_array($sql)) {
        $resultados[$rst['mes']] = $rst;
    }
    ?>

    <table id="kpiTable" border="1">
        <tr>
            <th>&nbsp;</th>
            <?php
            for ($i = 1; $i <= 12; $i++) {
                echo "<th>$i</th>";
            }
            ?>
            <th>Total</th>
        </tr>
        <?php
        $titulos = array("certificacion_op", "evaluacion", "suministros_op", "inspeccion", "modelo_adicional");
        $titles = array("Certificación OP", "Evaluación", "Suministro OP", "Inspección", "Modelo Adicional");

        // Bucle externo
        for ($row = 0; $row < count($titulos); $row++) {
            echo '<tr>';
            echo '<td style="text-align: left;">' . $titulos[$row] . '</td>';
        
            $total = 0; // Inicializar el total para cada fila
        
            // Bucle interno
            for ($mes = 1; $mes <= 12; $mes++) {
                $valor = isset($resultados[$mes]) ? $resultados[$mes][$titulos[$row]] : 0; // Obtener el valor actual
                $total += $valor; // Acumular el valor al total
        
                // Obtener el valor del mes en esta iteración
                $mesActual = $mes;
                echo '<td>
                        <input type="hidden" name="'. $mesActual .'" value="' . $mesActual .'">
                        <input type="text" name="input_' . ($row + 1) . '_' . $mesActual . '" value="' . $valor . '" oninput="updateTotal(' . ($row + 1) . ')" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="3">
                    </td>';
            }
        
            // Imprimir el total al final de la fila
            echo '<td><input type="text" id="total_' . ($row + 1) . '" value="'.$total.'" oninput="this.value = Math.abs(this.value)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="3" readonly></td>';
            echo '</tr>';
        }
        ?>
    </table>
    <hr>
    <button id="guardarButton" onclick="guardarDatos()" class="btn btn-success" title="GUARDAR KPI">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
    </button>
</div>
<script>
    function guardarDatos() {
        var table = document.getElementById("kpiTable");
        var formData = new FormData();

        // Iterate through each row in the table
        for (var i = 1; i < table.rows.length; i++) {
            var rowData = table.rows[i].cells;
            var title = rowData[0].innerText.trim();
            var total = rowData[rowData.length - 1].querySelector('input').value;

            formData.append('titles[]', title);
            formData.append('totals[]', total);

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
        xhr.open('POST', 'save_kpi.php', true);
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
       /*
        xhr.onload = function () {
            console.log('Respuesta completa del servidor:', xhr.responseText);

            try {
                var response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    console.log('Datos guardados correctamente');
                    // Manejar el éxito, por ejemplo, mostrar un mensaje de éxito al usuario
                } else {
                    console.error('Error al guardar datos: ' + response.message);
                    // Manejar el error, por ejemplo, mostrar un mensaje de error al usuario
                }
            } catch (e) {
                console.error('Error al analizar la respuesta JSON:', e);
            }
        };*/

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

    function updateTotal(row) {
        var total = 0;
        for (var mes = 1; mes <= 12; mes++) {
            var input = document.getElementsByName('input_' + row + '_' + mes)[0];
            var value = parseInt(input.value) || 0;
            total += value;
        }
        document.getElementById('total_' + row).value = total;
    }
</script>
</body>
</html>