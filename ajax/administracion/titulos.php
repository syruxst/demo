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
<!DOCTYPE html>
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
        a{
            text-decoration: none;
            color: #04C9FA;
        }
        a:hover{
            text-decoration: none;
            color: #FF5733;
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
        .tabla{
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h4>TITULOS PROFESIONALES DEL PERSONAL</h4></center>
        <?php if ($perfil == 'administrador' || $perfil == 'calidad') { ?>
        <div class="tabla">
            <div class="row">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre" id="nombre" onkeyup="capitalizarNombre()">
                    <input type="text" class="form-control" placeholder="Titulo Profesional" name="cargo" id="cargo">
                    <input type="file" class="form-control" name="archivo" id="archivo" accept=".pdf">
                    <button type="button" class="btn btn-primary" id="enviar">GUARDAR</button>
                </div>
            </div>
        </div>
        <?php }?>
        <hr>
        <div class="mostrar"></div>
    </div>
    <div id="loader" class="loader"></div>
<script>
// Función para cargar y mostrar los datos en el div
function mostrarDatosEnDiv() {
    fetch('mostrarTitulos.php')
        .then(response => response.text()) // Obtener el contenido HTML como texto
        .then(data => {
            // Obtener el elemento div con la clase "mostrar"
            var mostrarDiv = document.querySelector('.mostrar');
            
            // Insertar el contenido HTML en el div
            mostrarDiv.innerHTML = data;
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });
}

// Llamar a la función para mostrar los datos cuando la página se carga
window.addEventListener('load', mostrarDatosEnDiv);
// Obtener los elementos de los campos de texto
var cargoInput = document.getElementById('cargo');

function capitalizarNombre() {
  var inputNombre = document.getElementById("nombre");
  var nombre = inputNombre.value;

  // Separa el nombre completo en palabras
  var palabras = nombre.split(" ");

  // Capitaliza la primera letra de cada palabra
  var nombreCapitalizado = palabras.map(function(word) {
    if (word.length > 0) {
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    } else {
      return "";
    }
  }).join(" ");

  // Asigna el valor capitalizado de vuelta al campo de entrada
  inputNombre.value = nombreCapitalizado;
}

// Agregar un evento 'input' para el campo de cargo
cargoInput.addEventListener('input', function() {
    // Convertir el texto a mayúsculas y establecerlo de nuevo en el campo
    this.value = this.value.toUpperCase();
});
document.getElementById('enviar').addEventListener('click', function() {
    var nombre = document.getElementById('nombre').value;
    var cargo = document.getElementById('cargo').value;
    var archivo = document.getElementById('archivo').files[0]; // Obtener el archivo seleccionado

    // Obtener el elemento del indicador de carga
    var loader = document.getElementById('loader');

    // Mostrar el indicador de carga
    loader.style.display = 'block';

    // Verificar que los campos de texto no estén vacíos
    if (nombre.trim() === '' || cargo.trim() === '') {
        swal({
            title: "Campos incompletos!",
            text: "Por favor, complete todos los campos.",
            icon: "info",
            button: "Aceptar!",
        });

        // Ocultar el indicador de carga
        loader.style.display = 'none';

        return; // No continuar con la solicitud si algún campo de texto está vacío
    }

    // Verificar que el campo de archivo no esté vacío
    if (!archivo) {
        swal({
            title: "Archivo no seleccionado!",
            text: "Por favor, seleccione un archivo.",
            icon: "info",
            button: "Aceptar!",
        });

        // Ocultar el indicador de carga
        loader.style.display = 'none';

        return; // No continuar con la solicitud si el campo de archivo está vacío
    }

    // Crear un objeto FormData para enviar datos (incluyendo archivos)
    var formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('cargo', cargo);
    formData.append('archivo', archivo);

    fetch('saveTitulos.php', {
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
            document.getElementById("cargo").value = "";
            document.getElementById("archivo").value = "";
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

        // Ocultar el indicador de carga después de recibir la respuesta
        loader.style.display = 'none';
    })
    .catch(error => {
        // Manejar errores de la solicitud
        console.error('Error:', error);
        swal({
            title: "Error en la solicitud!",
            text: "Ha ocurrido un error en la solicitud.",
            icon: "error",
            button: "Aceptar!",
        });

        // Ocultar el indicador de carga en caso de error
        loader.style.display = 'none';
    });
});
</script>
</body>
</html>