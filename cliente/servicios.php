<?php
session_start();
error_reporting(0);
date_default_timezone_set('America/Santiago');

$usuario = $_GET['nombre'];

require_once('../admin/conex.php');

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $usuario = $_SESSION['cliente'];
    $sql = "SELECT * FROM `clientes` WHERE user = '$usuario'";
    $rst = $conn->query($sql);
    
    if ($rst->num_rows > 0) {
        $row = $rst->fetch_assoc();
        $name = $row['contacto'];
        $empresa = $row['empresa'];
        $faena = $row['faena'];
    } else {
        header("Location: ../cliente.php");
        exit();
    }
} else {
    header("Location: ../cliente.php");
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
    <title>:: Perfil ::</title>
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
        }
        .tabla {
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        .fa-check {
            color: #2ECC71;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .fa-check:hover {
            transform: scale(2); 
        }

        .fa-times {
            color: red;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .fa-times:hover {
            transform: scale(2); 
        }

        .fa-file-text-o {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .fa-file-text-o:hover {
            transform: scale(2);
        }
        .fa-upload {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }
        .fa-upload:hover {
            transform: scale(2);
        }
        .fa-floppy-o {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }
        .fa-floppy-o:hover {
            transform: scale(2);
        }
        .fa-file-pdf-o {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }
        .fa-file-pdf-o:hover {
            transform: scale(2);
        }
        .fa-info-circle {
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }
        .fa-info-circle:hover {
            transform: scale(2);
        }
        .modal {
            display: none; /* Oculta el modal por defecto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fondo semi-transparente */
            z-index: 1;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            width: 600px;
            margin: 0 auto;
            margin-top: 100px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        @media (max-width: 600px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
        }
        .upload-icon {
            color: #000; /* Color predeterminado del icono */
            cursor: pointer;
        }

        .upload-icon.green {
            color: green; /* Color cuando el archivo está cargado 300000*/
        }

        .file-input {
            display: none; /* Ocultar el input type file por defecto 60000 */
        }
     </style>
<script>
  function recargarPagina() {
    location.reload();
  }

  setInterval(recargarPagina, 300000);
</script>
</head>
<body background="white">
    <div class="container">
        <h4>Faena : <?php echo $faena; ?></h4>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
            <input type="text" name="buscar" id="buscar" placeholder="Buscar por: OT, faena, nombre, rut, equipo" class="form-control">
        </div>
        <hr>
        <div id="resultado"></div>
        <?php
        $consulta = "SELECT * FROM `cotiz` WHERE name_cliente = ? AND faena = ? AND estado = 'APROBADO'";
        $statement = $conn->prepare($consulta);
        $statement->bind_param("ss", $empresa, $faena);
        $statement->execute();
        $resultado = $statement->get_result();

        if ($resultado->num_rows > 0) {
            while ($ver = $resultado->fetch_assoc()) {
                // Aquí puedes mostrar los resultados iniciales si es necesario
            }
        } else {
            echo "No se encontraron resultados.";
        }

        $statement->close();
        ?>
    </div>
    <div class="modal" id="modal" style="display: none;">
        <div class="modal-content">
            <center><span style="font-size: 20px;">SUBIR INFORME DE EVIDENCIA BRECHAS</span></center>
            <hr>
            <!-- Agregar data-id al campo de entrada oculto -->
            <input type="hidden" name="datos" id="datos" class="datoId" value="">
            <input type="file" data-id="" name="file" id="file" accept=".pdf, .doc, .docx">
            <!-- Agregar data-id al botón de subir informe -->
            <hr>
            <button type="button" class="btn btn-success" id="subirInformeBtn" data-id=""><i class="fa fa-upload fa-lg" aria-hidden="true" title="SUBIR EVIDENCIA DE BRECHAS"></i> SUBIR INFORME</button>
        </div>
    </div>
    <script>
        // Función para realizar la búsqueda
        function realizarBusqueda(valor) {
            // Crear objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Obtener los valores de $empresa y $faena desde PHP
            var empresa = '<?php echo $empresa; ?>';
            var faena = '<?php echo $faena; ?>';

            // Configurar la solicitud
            xhr.open('POST', 'buscar_servicios.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Manejar el evento de carga
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('resultado').innerHTML = xhr.responseText;

                    // Agregar evento click a los elementos con la clase "abrir-popup"
                    var elementosPopup = document.getElementsByClassName('abrir-popup');
                    for (var i = 0; i < elementosPopup.length; i++) {
                        elementosPopup[i].addEventListener('click', function () {
                            // Obtener el valor del atributo data-informe
                            var informeId = this.getAttribute('data-informe');

                            // Abrir la ventana emergente y pasar el valor de data-informe
                            abrirVentanaEmergente(informeId);
                        });
                    }

                    /*inicio de modal*/
                    var modal = document.getElementById('modal');
                    var btnSub = document.getElementsByClassName('abrir-upload');

                    for (var i = 0; i < btnSub.length; i++) {
                        btnSub[i].addEventListener('click', function () {
                            // Mostrar el modal
                            modal.style.display = 'block';

                            // Obtener el valor del atributo 'data-Submit' del botón clickeado
                            var dataSub = this.getAttribute('data-Submit');

                            // Asignar el valor a los campos correspondientes dentro del modal
                            document.getElementById('datos').value = dataSub;
                            document.getElementById('file').setAttribute('data-id', dataSub);
                            document.getElementById('subirInformeBtn').setAttribute('data-id', dataSub);
                        });
                    }

                    document.getElementById('subirInformeBtn').addEventListener('click', function () {
                        // Aquí puedes agregar la lógica para subir el informe

                        // Obtener el valor del atributo 'data-id' del botón de subir informe
                        var dataId = this.getAttribute('data-id');
                        console.log('Valor de data-id:', dataId);

                        // Ocultar el modal después de hacer lo necesario
                        modal.style.display = 'none';
                    });

                    window.addEventListener('click', function (event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    });
                    /*fin de modal*/
  

                        document.getElementById("subirInformeBtn").addEventListener("click", function() {
                            // Obtener el archivo y los datos
                            // Obtener el archivo y los datos del bloque específico
                            var modalContent = this.closest('.modal-content');
                            var archivoInput = modalContent.querySelector("#file");
                            var archivo = archivoInput.files[0];
                            var dataId = archivoInput.getAttribute('data-id');

                            // Ahora, puedes utilizar el valor de data-id como lo necesites
                            console.log("Valor de data-id:", dataId);
                            var datos = modalContent.querySelector('.datoId').value;

                            // Validar que se haya seleccionado un archivo
                            if (!archivo) {
                                swal("Advertencia", "Debes seleccionar un archivo antes de continuar!", "info");
                                console.log('Debes seleccionar un archivo antes de continuar!');
                                return;
                            }

                            // Crear objeto FormData para enviar el archivo y datos
                            var formData = new FormData();
                            formData.append("file", archivo);
                            formData.append("datos", datos);
                            formData.append("dataId", dataId);

                            // Crear objeto XMLHttpRequest
                            var xhr = new XMLHttpRequest();

                            // Configurar la solicitud
                            xhr.open("POST", "save_brechas.php", true);

                            // Configurar el manejo de la respuesta
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState == 4) {
                                    if (xhr.status == 200) {
                                        // Analizar la respuesta JSON
                                        var response = JSON.parse(xhr.responseText);

                                        // Manejar la respuesta del servidor según el estado
                                        if (response.status === 'success') {
                                            console.log("¡Archivo subido exitosamente!");
                                            swal({
                                                icon: 'success',
                                                title: 'Bien hecho!',
                                                text: '¡Archivo subido exitosamente!'
                                            });
                                            // Puedes realizar acciones adicionales si es necesario
                                        } else if (response.status === 'error') {
                                            console.error("Error: " + response.message);
                                            swal({
                                                icon: 'error',
                                                title: 'Algo salio mal!',
                                                text: response.message
                                            });
                                            // Puedes manejar el error de alguna manera
                                        } else if (response.status === 'info') {
                                            console.log("Información: " + response.message);
                                            swal({
                                                icon: 'info',
                                                title: 'Advertencia!',
                                                text: response.message
                                            });
                                            // Puedes manejar la información de alguna manera
                                        }
                                    } else {
                                        // Manejar errores de la solicitud Ajax
                                        console.error("Error: " + xhr.status);
                                    }
                                }
                            };

                            // Enviar la solicitud con el objeto FormData
                            xhr.send(formData);
                        });

                }
            };

            // Enviar la solicitud con los datos de búsqueda y las variables PHP
            xhr.send('buscar=' + encodeURIComponent(valor) +
                '&empresa=' + encodeURIComponent(empresa) +
                '&faena=' + encodeURIComponent(faena));
        }

        // Realizar búsqueda inicial al cargar la página
        realizarBusqueda('');

        // Asociar la función al evento input del input de búsqueda
        document.getElementById('buscar').addEventListener('input', function () {
            var valorBusqueda = this.value.trim();
            realizarBusqueda(valorBusqueda);
        });

        // Función para abrir la ventana emergente
        function abrirVentanaEmergente(informeId) {
            // Encriptar el valor de informeId
            var informeIdEncriptado = encriptar(informeId);

            // Puedes personalizar la URL de la ventana emergente y otros parámetros según tus necesidades
            var urlVentanaEmergente = 'detalle_de_brechas.php?informe=' + informeIdEncriptado;

            // Obtener las dimensiones de la pantalla
            var screenWidth = window.screen.width;
            var screenHeight = window.screen.height;

            // Calcular las coordenadas para centrar la ventana emergente
            var left = (screenWidth - 800) / 2;
            var top = (screenHeight - 600) / 2;

            // Abrir la ventana emergente en el centro de la pantalla
            window.open(urlVentanaEmergente, '_blank', 'width=800,height=600,left=' + left + ',top=' + top + ',resizable=yes,scrollbars=yes');
        }

        // Función para encriptar el valor
        function encriptar(valor) {
            try {
                var textoEncriptado = btoa(valor);
                return textoEncriptado;
            } catch (error) {
                console.error('Error al encriptar:', error);
                return null;
            }
        }
    </script>
</body>
</html>