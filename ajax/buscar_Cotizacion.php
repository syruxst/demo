<?php session_start(); error_reporting(0);
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
<html lang="en">
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
        body{
            background-color: white;
            padding: 20px;
        }
        .tablaDiv{
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
            width: 100%;    
            height: 100%;
        }
        .row {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 60%;
        }
        .col {
            flex-grow: 1;
            height: 100%;
            width: 100%;
            padding: 5px;
            margin: 5px;
        }
        .verCotizacion{
            display: none;
            background-color: rgba(0, 0, 0, 0.63);
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; 
            width: 100%; height: auto; justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
            padding: 50px;
            overflow-y: auto;
        }
        .innerContainer {
            background-color: white;
            width: 700px;
            height: 500px; 
            border-radius: 10px;
            padding: 20px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .pdfCotizacion {
            padding: 10px;
            max-height: 350px; /* Ajusta la altura máxima según tus necesidades */
            overflow-y: auto; /* Habilita el desplazamiento vertical si el contenido excede la altura máxima */
        }
        a{
            text-decoration: none;
            color: red;
        }
        a:hover{
            text-decoration: none;
            color: #86FF33;
        }
        /*inicio*/
        .rectangulo{
            width: 25px;
            height: 25px;
            background-color: #CCC;
            align-content: center;
            justify-content: center;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }
        .rectangulo:hover{
            background-color: #06B1DF;
            color: white;
            transform: scale3d(1.6, 1.6, 1.6);
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .hovered {
            background-color: #06B1DF;
        }

        .event {
        display: flex;
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .event-title {
        font-weight: bold;
        margin-right: 10px;
        }

        .event-description {
        color: #555;
        }

        .timeline {
        position: relative;
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        overflow-x: hidden; /* Ocultamos el desbordamiento horizontal */
        position: relative; /* Agregamos posición relativa para los círculos */
        animation: slideRight 2s ease-in-out;
        }

        .line {
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #ccc;
        top: 50%;
        transform: translateY(-50%);
        }

        .circle-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        position: relative;
        padding: 10px;
        }

        .circle {
            width: 20px;
            height: 20px;
            background-color: #3498db;
            border-radius: 50%;
            position: relative;
            z-index: 1;
            cursor: pointer;
        }

        .circle::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background-color: #fff;
        border-radius: 50%;
        z-index: -1;
        }
        .comment {
        display: none;
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 12px;
        text-align: center;
        width: 100px;
        }

        .circle:hover .comment {
        display: block;
        }
        @keyframes slideRight {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(0);
        }
        }
        .title {
        font-size: 10px;
        text-align: center;
        color: #555;
        margin-top: 20px;
        }
        /*fin*/

        .events-titles {
        font-weight: bold;
        margin-right: 10px;
        }

        .events-descriptions {
        color: #555;
        }

        .timelines {
        position: relative;
        width: 100%;
        height: 40px;
        display: flex;
        align-items: center;
        overflow-x: hidden; /* Ocultamos el desbordamiento horizontal */
        overflow-y: hidden; /* Ocultamos el desbordamiento vertical */
        position: relative; /* Agregamos posición relativa para los círculos */
        animation: slideRight 2s ease-in-out;
        margin: 5px;
        scroll-behavior: smooth;
        }

        .lines {
        position: absolute;
        width: 100%;
        height: 4px;
        top: 50%;
        transform: translateY(-50%);
        }

        .circles-containers {
        display: flex;
        justify-content: space-between;
        width: 100%;
        position: relative;
        padding: 10px;
        }

        .circles {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            position: relative;
            z-index: 1;
            cursor: pointer;
            color: white;
            color: white;
        }

        .circles::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background-color: #fff;
        border-radius: 50%;
        z-index: -1;
        }
        .comments {
        display: none;
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 12px;
        text-align: center;
        width: 100px;
        }

        .circles:hover .comments {
        display: block;
        }
        @keyframes slideRight {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(0);
        }
        }
        .titles {
        font-size: 10px;
        text-align: center;
        color: #555;
        margin-top: 20px;
        }
        .hola{
            color: white;
        }
        .logo{
            position: absolute;
            top: 0;
            left: 50px;
            padding: 10px;
            z-index: 9999;
        }
    </style>
</head>
<body>
<div class="logo">
    <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="200" height="90" title="OPERAMAQ" class="logo">
</div>
<center><h1>FLUJO DE PROCESO</h1></center>
    <br>
    <div class="container">
        <div class="tablaDiv">
            <div class="row">
                <div class="col">
                    <div class="input-group" style="width: 400px;">
                        <span class="input-group-text" id="basic-addon1">CLIENTE</span>
                        <select name="cliente" id="cliente" class="form-control">
                            <option value="">SELECCIONAR CLIENTE</option>
                            <?php
                                $cargarClientes = mysqli_query($conn, "SELECT DISTINCT name_cliente FROM `cotiz` ORDER BY `name_cliente` ASC");
                                    while($result = mysqli_fetch_array($cargarClientes)){
                                        echo '<option value="'.$result['name_cliente'].'">'.$result['name_cliente'].'</option>';
                                    }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col">

                </div>
            </div>
        </div>
        <hr>    
  
<div class="timeline">
    <div class="line"></div>
        <div class="circle-container">
        <div class="circle" data-etapa="1"> <div class="comment">Etapa 1</div><div class="title">COT</div></div>
        <div class="circle" data-etapa="2"> <div class="comment">Etapa 1</div><div class="title">REV</div></div>
        <div class="circle" data-etapa="3"> <div class="comment">Etapa 1</div><div class="title">APR</div></div>
        <div class="circle" data-etapa="4"> <div class="comment">Etapa 2</div><div class="title">OT1</div></div>
        <div class="circle" data-etapa="5"> <div class="comment">Etapa 2</div><div class="title">OT2</div></div>
        <div class="circle" data-etapa="6" style="background-color: red;"> <div class="comment">Etapa 3</div><div class="title">FAC</div></div>
        <div class="circle" data-etapa="7" style="background-color: red;"> <div class="comment">Etapa 3</div><div class="title">PGO</div></div>
    </div>
</div>
                            
            <div id="resultado"></div>
            <div class="verCotizacion">
            <button type="button" id="cerrarBtn" title="CERRAR FORMULARIO" class="btn btn-primary"><i class="fa fa-times-circle" aria-hidden="true"></i> CERRAR</button>
                <div class="innerContainer">
                    <center><h4>VER COTIZACIÓN</h4></center>
                    <hr>
                    <div class="pdfCotizacion"></div>       
                </div>                        
            </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function(){
        // Mostrar datos de busqueda
        var clienteSelect = document.getElementById("cliente");
        var resultadoDiv = document.getElementById("resultado");

        clienteSelect.addEventListener("change", function(){
            var cliente = clienteSelect.value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState === 4 && xhr.status === 200){
                    resultadoDiv.innerHTML = xhr.responseText;
                }
            };

            xhr.open("POST", "buscar_cot_estado.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("cliente=" + cliente);
        });

        // Mostrar detalle de la cotizacion
        var verCotizacionDiv = document.querySelector(".verCotizacion");
        var innerContainer = document.querySelector(".innerContainer");

        document.addEventListener("click", function(event) {
            var target = event.target;

            if (target.classList.contains("rectangulo")) {
                event.preventDefault();

                var folio = target.getAttribute("data-folio");
                var xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        innerContainer.querySelector('.pdfCotizacion').innerHTML = xhr.responseText;
                        verCotizacionDiv.style.display = "block";
                    }
                };
                xhr.open("GET", "pdfCotizacion.php?folio=" + folio, true);
                xhr.send();
            }else if (!innerContainer.contains(target)) {
            verCotizacionDiv.style.display = "none";
            }
        });        

        // Cerrar ventana verCotizacionDiv
        var cerrarBtn = document.getElementById("cerrarBtn");

        cerrarBtn.addEventListener("click", function() {
            verCotizacionDiv.style.display = "none";
        });        

        // subir archivo pdf para guardar
        document.addEventListener("click", function(event) {
            var target = event.target;

            if (target.id === "subirBtn") {
                var formData = new FormData();
                var pdfInput = document.getElementById("pdf");
                var folioInput = document.getElementById("folios");

                formData.append("pdf", pdfInput.files[0]);
                formData.append("folios", folioInput.value);
                formData.append("botonPresionado", "btnPendiente");

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_cambioCotizacion.php", true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                swal("Bien hecho!", response.message, "success");
                            } else {
                                swal("Hubo un erro!", response.message, "error");
                            }
                        } catch (error) {
                            console.error("Error al analizar la respuesta JSON:", error);
                            swal("Error!", "Error en la respuesta del servidor.", "error");
                        }
                    } else {
                        console.error("Error en la solicitud AJAX:", xhr.statusText);
                        swal("Error!", "Error en la solicitud AJAX: " + xhr.statusText, "error");
                    }
                };

                xhr.onerror = function() {
                    console.error("Error en la solicitud AJAX");
                };

                xhr.send(formData);
            }
        });

        // Cambiar estado de la cotizacion
        document.addEventListener("click", function(event) {
            var target = event.target;

            if (target.id === "actualizarBtn") {
                var formData = new FormData();
                var folioInput = document.getElementById("folios");
                var estadoSelect = document.getElementById("estado");

                formData.append("folios", folioInput.value);
                formData.append("estado", estadoSelect.value);
                formData.append("botonPresionado", "btnRevisado");

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_cambioCotizacion.php", true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                swal("Bien hecho!", response.message, "success");
                            } else {
                                swal("Hubo un erro!", response.message, "error");
                            }
                        } catch (error) {
                            console.error("Error al analizar la respuesta JSON:", error);
                            swal("Error!", "Error en la respuesta del servidor.", "error");
                        }
                    } else {
                        console.error("Error en la solicitud AJAX:", xhr.statusText);
                        swal("Error!", "Error en la solicitud AJAX: " + xhr.statusText, "error");
                    }
                };

                xhr.onerror = function() {
                    console.error("Error en la solicitud AJAX");
                };

                xhr.send(formData);
            }
        });
    });
</script>

</body>
</html>