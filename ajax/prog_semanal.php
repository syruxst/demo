<?php
session_start();
error_reporting(0);
$usuario = $_GET['nombre'];

require_once('../admin/conex.php');

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['usuario'])) {
       $usuario = $_SESSION['usuario'];
       $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['name'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}
/*Buscar datos de operador*/
$buscar = mysqli_query($conn, "SELECT * FROM insp_eva WHERE user = '$usuario'");
while($ver = mysqli_fetch_array($buscar )){
    $id_oper = $ver['id'];
    $Rut = $ver['rut'];
    $Nombre = $ver['name'];
    $Email = $ver['correo'];
    $Telefono = $ver['telefono'];
    $Direccion = $ver['direccion'];
    $comuna = $ver['comuna'];
    $region = $ver['region'];
    $banco = $ver['banco'];
    $tipoCta = $ver['tipocta'];
    $cta = $ver['cta'];

    if($comuna == '' || $region == ''){
        $comuna = '<select id="selectComunas" name="comunas" class="form-control" required></select>';
        $region = '<select id="selectRegiones" name="regiones" class="form-control" required></select>';
    }else{
        $comuna = '<input type="text" class="form-control" id="selectComunas" name="comunas" value="'.$comuna.'" required>';
        $region = '<input type="text" class="form-control" id="selectRegiones" name="regiones" value="'.$region.'" required>';
    }

    if($banco ){

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
    <title>Document</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        a{
            text-decoration: none;
            color: var(--color);
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
        table{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1{
            color: var(--color);
        }
        h3{
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

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
        #actualizar {
            width: 200px; 
            height: 40px; 
            background-color: var(--color);
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        #actualizar:hover {
            background-color: #03a4d3; 
        }
    </style>
</head>
<body background="white">
<div class="container">
<center><h4>PROGRAMA SEMANAL</h4></center>
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

    btnBuscar.addEventListener("click", function () {
        const fechaInicioValue = fechaInicio.value;
        const fechaFinValue = fechaFin.value;

        if (!fechaInicioValue || !fechaFinValue) {
            swal({
                title: "Advertencia!",
                text: "Las Fechas no pueden estar vacias!",
                icon: "info",
                button: "Aceptar!",
            });
        }else{
            const fechaInicioDate = new Date(fechaInicioValue);
            const fechaFinDate = new Date(fechaFinValue);  

                if (fechaFinDate < fechaInicioDate) {
                    swal({
                        title: "Advertencia!",
                        text: "La Fecha final no puede ser inferior a la fecha de inicio!",
                        icon: "info",
                        button: "Aceptar!",
                    });
                }else{
                    document.getElementById("loading-overlay").style.display = "block";
                        // Realiza la búsqueda por Ajax
                        fetch("buscar_prog.php", {
                            method: "POST",
                            body: new URLSearchParams({
                                inicio: fechaInicioValue,
                                fin: fechaFinValue
                            }),
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            }
                        })
                        .then(response => response.text())
                        .then(data => {
                            resultadosDiv.innerHTML = data;
                            document.getElementById("loading-overlay").style.display = "none";

                            // JavaScript para abrir una nueva ventana y enviar datos
                            document.getElementById('pdf').addEventListener('click', function() {
                                // Obtener los valores de las variables $inicio y $fin
                                var inicio = fechaInicioValue;
                                var fin = fechaFinValue;

                                // Construir la URL con los parámetros
                                var url = "progrmapdf.php?in=" + inicio + "&out=" + fin;

                                // Abrir la URL en una nueva ventana
                                window.open(url, '_blank');
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
        }
    });
});
</script>
</body>
</html>