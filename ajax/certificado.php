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
    <script src="../node_modules/qrcode-generator/qrcode.js"></script>
    <title>Document</title>
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
        i {
            cursor: pointer;
            transform: scale(1); 
            transition: transform 0.2s; 
        }

        i:hover {
            transform: scale(1.8); 
            color: #FF5733;
        }
        /*loading*/
        /* Estilo para el contenedor del indicador de carga */
        .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
        z-index: 1000; /* Asegura que esté en la parte superior de todos los elementos */
        }

        /* Estilo para el indicador de carga en sí */
        .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 15% auto; /* Centra el indicador de carga verticalmente */
        animation: spin 2s linear infinite; /* Agrega una animación de giro */
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
        .center-pagination{
            display: flex;
            justify-content: center;
            left: 50%;
            translate: translateX(-50%);
        }
    </style>
</head>
<body>
    <center><h1>Certificados</h1></center>
    <div class="container">
        <input type="text" id="buscar" placeholder="Buscar: Folio, Nombre, Equipo y Status" class="form-control">
        <hr>
        <form action="" method="post" id="form-certificados">
            <div class="input-group input-group-sm mb-3">
                <select name="folio" id="folio" class="form-control">
                    <option value="">Selecciona un folio</option>
                    <?php
                        $cargar = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE estado = 'APROBADO'");
                        while ($rows = mysqli_fetch_array($cargar)) {
                            echo "<option value='".$rows['id']."'>".$rows['folio']."</option>";
                        }
                    ?>
                </select>
                <input type="file" class="form-control" name="archivo" id="archivo" accept=".pdf">
                <input type="submit" name="subir" id="subir" value="SUBIR ARCHIVO FIRMADO" title="SUBIR ARCHIVOS" class="btn btn-primary">
            </div>
        </form>
        <div id="datos"></div>
    </div>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>
<script>
window.addEventListener('load', function () {
    const inputBuscar = document.getElementById('buscar');
    const datosContainer = document.getElementById('datos');

    // Función para cargar resultados
    function cargarResultados(searchTerm, pagina) {
        const formData = new FormData();
        formData.append('search', searchTerm);
        formData.append('pagina', pagina);

        fetch('buscarCertificado.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('datos').innerHTML = data;
            
            // Agregar evento de clic a los iconos
            const iconos = document.querySelectorAll('i.certif');
            iconos.forEach(icono => {
                icono.addEventListener('click', function () {
                    const dataId = icono.getAttribute('data-id');
                    
                    // Crear un formulario oculto
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'certificadoPdf.php';
                    
                    // Crear un campo oculto para enviar dataId
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'certificado';
                    input.value = dataId;
                    
                    // Agregar el campo al formulario
                    form.appendChild(input);
                    
                    // Agregar el formulario al documento y enviarlo
                    document.body.appendChild(form);
                    form.submit();
                    
                    console.log(dataId);
                });
            });
        })
        .catch(error => {
            console.error('Error al cargar los resultados: ' + error);
        });
    }

    // Cargar todos los resultados al cargar la página
    cargarResultados('', 1);

    // Agregar un evento de escucha para el evento "input" en el campo de búsqueda
    inputBuscar.addEventListener('input', function () {
        const searchTerm = inputBuscar.value.trim();
        cargarResultados(searchTerm, 1);
    });

    // Agregar un evento de delegación de eventos al contenedor de datos para manejar los clics en los enlaces de paginación
    datosContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('page-link')) {
            event.preventDefault(); // Evita que el enlace realice la navegación estándar
            const pagina = event.target.getAttribute('data-pagina');
            cargarResultados('', pagina);
        }
    });
});

// Función para subir el archivo
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("form-certificados");

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        document.getElementById("loading-overlay").style.display = "block";
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_certificados.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                document.getElementById("loading-overlay").style.display = "none";
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        console.log(response.message);
                        swal({
                            title: "¡Bien hecho!",
                            text: response.message,
                            icon: "success",
                            button: "OK",
                        });
                        location.reload();
                    } else {
                        console.error(response.message);
                        swal({
                            title: "¡Algo salió mal!",
                            text: response.message,
                            icon: "error",
                            button: "OK",
                        });
                    }
                } else {
                    console.error("Error en la solicitud");
                }
            }
        };
        xhr.send(formData);
    });
});

</script>
</body>
</html>