<!DOCTYPE html>
<?php session_start(); error_reporting(1);
    // Conectarse a la base de datos
    require_once('../../admin/conex.php');
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
        $seach = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$usuario'");
        $row = mysqli_fetch_array($seach);
        $perfil = $row['permiso'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../login.php");
        exit();
    }
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Document</title>
    <style>
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
        /* Estilos para la clase "tabla" */
        .tabla {
            padding: 10px;
            border-radius: 5px;
        }
        /* Estilos para la clase "row" */
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Estilos para la clase "col" */
        .col {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 3px;
            margin: 5px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
        }
        .loader {
            border: 4px solid rgba(0, 0, 0, 0.2);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none; /* Inicialmente oculto */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h4>PROCEDIMIENTOS Y REGISTROS NO CONTROLADOS</h4></center>
        <?php if ($perfil == 'administrador' || $perfil == 'calidad') { ?>
        <div class="tabla">
            <div class="row">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Nombre del Procedimiento" name="nombre" id="nombre">
                <input type="text" class="form-control" placeholder="Código" name="codigo" id="codigo">
                <input type="text" class="form-control" placeholder="Versión" name="version" id="version">
                <input type="file" class="form-control" accept=".pdf" name="archivo" id="archivo">
                <button type="button" class="btn btn-primary" id="subir">Subir</button>
            </div>
            </div>
        </div>
        <?php } else {?>

        <?php } ?>
        <hr>
        <input type="text" name="buscar" id="buscar" placeholder="Buscar procedimiento" class="form-control">
        <br>
        <div class="mostrar"></div>
    </div>
    <div id="loader" class="loader"></div>
<!-- Tu HTML existente -->

<script>
    // Función para cargar y mostrar los datos en el div
    function mostrarDatosEnDiv() {
        fetch('mostrarProcedimientos.php')
            .then(response => response.text()) // Obtener el contenido HTML como texto
            .then(data => {
                // Obtener el elemento div con la clase "mostrar"
                var mostrarDiv = document.querySelector('.mostrar');

                // Insertar el contenido HTML en el div
                mostrarDiv.innerHTML = data;

                // Llamar a la función para habilitar la búsqueda
                habilitarBusqueda();
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error);
            });
    }

    // Función para habilitar la búsqueda en tiempo real
    function habilitarBusqueda() {
        var buscarInput = document.getElementById('buscar');
        var filas = document.querySelectorAll('.tabla tbody tr');

        buscarInput.addEventListener('input', function () {
            var valorBusqueda = this.value.toLowerCase().trim();

            filas.forEach(function (fila) {
                var contenidoFila = fila.textContent.toLowerCase();
                if (contenidoFila.includes(valorBusqueda) || fila.classList.contains('cabecera')) {
                    fila.style.display = 'table-row';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }

    // Llamar a la función para mostrar los datos cuando la página se carga
    window.addEventListener('load', mostrarDatosEnDiv);

    var nombre = document.getElementById('nombre');
    var codigo = document.getElementById('codigo');
    var version = document.getElementById('version');

    codigo.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    version.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    document.getElementById('subir').addEventListener('click', function () {
        let nombreProcedimiento = document.getElementById('nombre').value;
        let codigoProcedimiento = document.getElementById('codigo').value;
        let versionProcedimiento = document.getElementById('version').value;
        let archivo = document.getElementById('archivo').files[0];

        var loader = document.getElementById('loader');

        if (nombreProcedimiento.trim() === '' || codigoProcedimiento.trim() === '' || versionProcedimiento.trim() === '' || !archivo) {
            swal({
                title: "Campos incompletos!",
                text: "Por favor, complete todos los campos.",
                icon: "info",
                button: "Aceptar!",
            });
            return;
        }

        loader.style.display = 'block';

        var formData = new FormData();
        formData.append('nombre', nombreProcedimiento);
        formData.append('codigo', codigoProcedimiento);
        formData.append('version', versionProcedimiento);
        formData.append('archivo', archivo);

        fetch('saveProcedimientos.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal({
                        title: "Éxito!",
                        text: data.message,
                        icon: "success",
                        button: "Aceptar!",
                    });
                    mostrarDatosEnDiv();
                    document.getElementById("nombre").value = "";
                    document.getElementById("codigo").value = "";
                    document.getElementById("version").value = "";
                    document.getElementById("archivo").innerHTML = "";
                    document.getElementById("nombre").focus();
                } else {
                    swal({
                        title: "Error!",
                        text: data.message,
                        icon: "error",
                        button: "Aceptar!",
                    });
                }

                loader.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                swal({
                    title: "Error en la solicitud!",
                    text: "Ha ocurrido un error en la solicitud.",
                    icon: "error",
                    button: "Aceptar!",
                });

                loader.style.display = 'none';
            });
    });
</script>

</body>
</html>