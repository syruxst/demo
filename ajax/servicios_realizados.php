<?php session_start(); error_reporting(0); date_default_timezone_set('America/Santiago');
// Conectarse a la base de datos
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
// Realizar la búsqueda inicial sin ingreso en el campo de búsqueda
$sql = "SELECT * FROM `detallle_ot` WHERE `estado` != ''";
$result = $conn->query($sql);
$rows = [];

if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
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
    <title>:: NOMINA SERVICIOS EVALUADORES ::</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        h1{
            color: var(--color);
        }
        .tabla {
            padding: 10px;
            border-radius: 5px;
        }
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .col {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 3px;
            margin: 5px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
            /*border: 1px solid #e5e5e5;*/
        }
        i {
            cursor: pointer;
            transform: scale(1); 
            transition: transform 0.2s; 
        }

        i:hover {
            transform: scale(1.3); 
            color: var(--color);
        }
        table {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ajusta los valores según tus preferencias */
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
            .row {
                width: 100%; 
                display: block;
            }
            .col {
                width: 100%; 
                float: none;
            }
            .tabla {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <center><h4>SERVICIOS REALIZADOS</h4></center>
    <div class="tabla">
        <div class="row">
            <div class="col">
                <input type="text" placeholder="Buscar:" name="buscar" id="buscar" class="form-control">
            </div>
        </div>
    </div>
    <div class="resultados">
        <?php
        // Mostrar resultados iniciales
        if (!empty($rows)) {
            echo '<table width="100%" border="0" class="table table-striped responsive-font">';
            echo '<tr><th>FOLIO</th><th>EMPRESA</th><th>OPERADOR</th><th>EQUIPO</th><th>STATUS</th><th>LC</th><th>CV</th><th>PP</th><th>PT</th><th>CERT</th></tr>';

            foreach ($rows as $row) {

                switch ($row['certificate']) {

                    case 'APROBADO':
                        $Icon = 'check';
                        $Color = '#3FFF33';
                        break;
                    
                    case 'RECHAZADO':
                        $Icon = 'times';
                        $Color = '#FF0000';
                        break;
                    
                    default:
                        $Icon = 'times';
                        $Color = '#FF0000';
                        break;
                }

                $Icon = "<i class='fa fa-$Icon fa-1x' aria-hidden='true' style='color: $Color;'></i>";
                echo "<tr>";
                // (Mostrar las columnas según tus necesidades)
                echo '<td>' . $row['ip'] . ' ' . $row['folio'] . '</td>';
                echo '<td>' . $row['empresa'] . '</td>';
                echo '<td>' . $row['nombre'] . '</td>';
                echo '<td>' . $row['equipo'] . '</td>';
                echo '<td>' . $Icon . '</td>';
                echo '<td><a href="https://acreditasys.tech/licencias/' . $row['licencia'] . '" target="_blank" title="LICENCIA DE CONDUCIR"><i class="fa fa-id-card-o" aria-hidden="true"></i></a></td>';
                echo '<td><a href="https://acreditasys.tech/uploads_op/' . $row['cv'] . '" target="_blank" title="CURRICULUM"><i class="fa fa-address-book-o" aria-hidden="true"></i></a></td>';
                echo '<td><a href="https://acreditasys.tech/SitioEI/evidencia/' . $row['img_3']. '" target="_blank" title="PRUEBA PRACTICA"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></td>';
                echo '<td><a href="https://acreditasys.tech/miSitio/ver_examen.php?data='.$row['rut'].'&E='.$row['equipo'].'&D='.$row['date_out'].'&P='.$row['porNota'].'&N='.$row['punNota'].'" target="_blank" title="PRUEBA TEORICA"><i class="fa fa-file" aria-hidden="true"></i></a></td>';
                echo '<td><a href="https://acreditasys.tech/firmados/' . $row['ruta_firma'] . '" target="_blank" title="CERTIFICADO"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No se encontraron resultados.";
        }
        ?>
    </div>
</div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var inputBuscar = document.getElementById("buscar");
            var resultadosDiv = document.querySelector(".resultados");

            inputBuscar.addEventListener("input", function () {
                var busqueda = inputBuscar.value.trim();

                // Realizar la búsqueda asincrónica
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {

                        // Mostrar los resultados
                        resultadosDiv.innerHTML = xhr.responseText;
                    }
                };

                xhr.open("POST", "buscar_servicios_realizados.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("input_busqueda=" + encodeURIComponent(busqueda));
            });
        });
    </script>
</body>
</html>