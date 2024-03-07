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
        /*loading*/
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
        table {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ajusta los valores según tus preferencias */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        <center><h4>NOMINA SERVICIOS EVALUADORES</h4></center>
    <div class="tabla">
        <div class="row">
            <div class="col">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">INICIO</span>
                    <input type="date" class="form-control" name="inicio" id="inicio" title="SELECCIONA UNA FECHA DE INICIO DEL PERIODO">
                </div>
            </div>
            <div class="col">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">FIN</span>
                    <input type="date" class="form-control" name="fin" id="fin" title="SELECCIONA UNA FECHA DE TERMINO DEL PERIODO">
                </div>
            </div>
            <div class="col">
                <select class="form-control" name="eva" id="eva">
                    <option value="0">Busca Evaluador</option>
                    <option value="todos">TODOS</option>
                    <?php
                        $seach = mysqli_query($conn, "SELECT * FROM `insp_eva` ORDER BY ev ASC");
                        while($re =  mysqli_fetch_array($seach)){
                            echo '<option value="'.$re['user'].'" title="'.$re['name'].'">'.$re['ev'].'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col">
                <button type="button" name="buscar" id="buscar" class="btn btn-primary">BUSCAR</button>
            </div>
        </div>
    </div>
    <div class="resultados"></div>
</div>
<div class="loading-overlay" id="loading-overlay">
  <div class="loader"></div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnBuscar = document.getElementById("buscar");
    const fechaInicio = document.getElementById("inicio");
    const fechaFin = document.getElementById("fin");
    const resultadosDiv = document.querySelector(".resultados");
    const selectEva = document.getElementById("eva");

    btnBuscar.addEventListener("click", function () {
        const fechaInicioValue = fechaInicio.value;
        const fechaFinValue = fechaFin.value;
        const evaValue = selectEva.value;

        if (!fechaInicioValue || !fechaFinValue || evaValue === "0") {
            swal({
                title: "Advertencia!",
                text: "Las Fechas y el Evaluador deben ser seleccionados!",
                icon: "info",
                button: "Aceptar!",
            });
        } else {
            const fechaInicioDate = new Date(fechaInicioValue);
            const fechaFinDate = new Date(fechaFinValue);

            if (fechaFinDate < fechaInicioDate) {
                swal({
                    title: "Advertencia!",
                    text: "La Fecha final no puede ser inferior a la fecha de inicio!",
                    icon: "info",
                    button: "Aceptar!",
                });
            } else {
                document.getElementById("loading-overlay").style.display = "block";

                // Realiza la búsqueda por Ajax
                fetch("buscar_pago.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        inicio: fechaInicioValue,
                        fin: fechaFinValue,
                        eva: evaValue
                    }),
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                })
                .then(response => response.text())
                .then(data => {
                    // Muestra los resultados en el elemento con clase 'resultados'
                    resultadosDiv.innerHTML = data;
                    document.getElementById("loading-overlay").style.display = "none";
                })
                .catch(error => console.error("Error en la solicitud: " + error));
            }
        }
    });
});
</script>
</body>
</html>