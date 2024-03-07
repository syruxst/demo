<?php
session_start();
error_reporting(1);
require_once('../admin/conex.php');
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
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
    <title>:: CREAR PRUEBAS ::</title>
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
        .container .resultadoFamilias{
            text-align: left;
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
            border: 1px solid #e5e5e5;
            margin: 5px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);

        }
        /* Estilos para la clase "col" */
        .col {
            background-color: #ffffff;
            padding: 5px;
            border-radius: 3px;
            margin: 3px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
            text-align: left;
        }
        hr {
            border: 1px solid #e5e5e5;
        }
        .tabla .row a {
            text-decoration: none;
            color: #A6A7A7;
        }
        .logo{
            position: absolute;
            top: 0;
            left: 50px;
            padding: 10px;
            z-index: 9999;
        }
        @media (max-width: 666px) {
            body {
                padding: 10px;
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
        .invalid-input {
            border: 1px solid red;
        }
    </style>
</head>
<body>
<div class="logo">
    <img src="../img/LogoPrincipal.png" alt="" width="200" height="90" title="OPERAMAQ" class="logo">
</div>
    <br>
    <div class="container">
        <div class="tabla">
            <div class="row">
                <div class="col">
                    <select class="form-control" name="selectFamilia" id="selectFamilia">
                        <option value="0">Seleccione una opción</option>
                        <?php
                            $cargar = mysqli_query($conn, "SELECT * FROM `familia_equipos` ORDER BY `familia_equipos`.`equipos` ASC");
                                while ($row = mysqli_fetch_array($cargar)) {
                                    echo '<option value="'.$row['equipos'].'" data-codigo="' . $row['codigo'] . '">'.$row['equipos'].'</option>';
                                }
                        ?>
                    </select>
                </div>
                <div class="col">

                </div>
            </div>
        </div>
        <div class="resultado">

        </div>
    </div>
<script>
    document.getElementById("selectFamilia").addEventListener("change", buscar);

    function buscar() {
        var selectFamilia = document.getElementById('selectFamilia');
        var familia = selectFamilia.value;

        var selectedOption = selectFamilia.options[selectFamilia.selectedIndex];
        var dataCodigo = selectedOption.getAttribute('data-codigo');

        console.log('Código seleccionado:', dataCodigo);

        var xhttp = new XMLHttpRequest();
        var respuestaServidor = "";
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                respuestaServidor = this.responseText;
                document.querySelector(".resultado").innerHTML = respuestaServidor;

                var inputNumero = document.getElementById("respuestaCorrecta");
                document.getElementById("pregunta").focus();

                inputNumero.addEventListener("input", function() {
                    var valor = inputNumero.value;
                    var esNumero = /^\d+$/.test(valor);

                    if (!esNumero) {
                        inputNumero.value = valor.slice(0, -1);
                    }
                    if (isNaN(valor) || valor < 1 || valor > 4) {
                        inputNumero.value = "";
                    }
                });

                document.getElementById("respuesta1").addEventListener("input", agregarA);
                document.getElementById("respuesta2").addEventListener("input", agregarB);
                document.getElementById("respuesta3").addEventListener("input", agregarC);
                document.getElementById("respuesta4").addEventListener("input", agregarD);

                function agregarA(event) {
                const input = event.target;
                    if (!input.value.startsWith("a)")) {
                        input.value = "a) " + input.value;
                    }
                }
                function agregarB(event) {
                const input = event.target;
                    if (!input.value.startsWith("b)")) {
                        input.value = "b) " + input.value;
                    }
                }
                function agregarC(event) {
                const input = event.target;
                    if (!input.value.startsWith("c)")) {
                        input.value = "c) " + input.value;
                    }
                }
                function agregarD(event) {
                const input = event.target;
                    if (!input.value.startsWith("d)")) {
                        input.value = "d) " + input.value;
                    }
                }

                document.getElementById("guardarPrueba").addEventListener("click", guardarPreguntasyRespuestas);

                function guardarPreguntasyRespuestas(){
                    var nombreDeTabla = document.getElementById("tabla").value;
                    var pregunta = document.getElementById("pregunta").value;
                    var respuesta1 = document.getElementById("respuesta1").value;
                    var respuesta2 = document.getElementById("respuesta2").value;
                    var respuesta3 = document.getElementById("respuesta3").value;
                    var respuesta4 = document.getElementById("respuesta4").value;
                    var respuestaCorrecta = document.getElementById("respuestaCorrecta").value;
                    var code = document.getElementById("code").value;
                    var ucl  = document.getElementById("ucl").value;

                    var selectTipo = document.getElementById('tipo');
                    var valorSeleccionadoTipo = selectTipo.value;
                    var selectEstado = document.getElementById('estado');
                    var valorSeleccionadoEstado = selectEstado.value;

                    // Validar que los campos no estén vacíos
                    if (pregunta === "" || respuesta1 === "" || respuesta2 === "" || respuesta3 === "" || respuesta4 === "" || respuestaCorrecta === "" || code === "" || ucl === "" || valorSeleccionadoTipo === "0" || valorSeleccionadoEstado === "SELECCIONAR ESTADO") {
                        swal("Advertencia!", "No pueden haber campos vacíos!", "info");
                    }else{
                        // Crear un objeto XMLHttpRequest
                        var xhttp = new XMLHttpRequest();
                        // Configurar la solicitud POST al archivo save_pruebas.php
                        xhttp.open("POST", "save_pruebas.php", true);
                        // Configurar la cabecera de la solicitud para enviar datos en formato de formulario
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                        // Definir la función de respuesta
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                // Aquí puedes manejar la respuesta del servidor si es necesario
                                swal("Bien hecho!", "Datos guardados con éxito!", "success");
                                // Agregar un retraso antes de limpiar los campos y establecer el enfoque
                                setTimeout(function() {
                                    // Limpiar todos los campos de entrada
                                    document.getElementById("pregunta").value = "";
                                    document.getElementById("respuesta1").value = "";
                                    document.getElementById("respuesta2").value = "";
                                    document.getElementById("respuesta3").value = "";
                                    document.getElementById("respuesta4").value = "";
                                    document.getElementById("respuestaCorrecta").value = "";
                                    document.getElementById("ucl").value = "";
                                    document.getElementById("code").value = "";
                                    // Enfocar el campo de entrada 'pregunta'
                                    document.getElementById("pregunta").focus();
                                }, 2500);
                            }else{
                                swal("Algo salio mal!", "Error al guardar los datos!", "error");
                            }
                        }; 

                        // Construir los datos que se enviarán en la solicitud POST
                        var data = "tabla=" + nombreDeTabla + "&pregunta=" + pregunta + "&respuesta1=" + respuesta1 + "&respuesta2=" + respuesta2 + "&respuesta3=" + respuesta3 + "&respuesta4=" + respuesta4 + "&respuestaCorrecta=" + respuestaCorrecta + "&codigo=" + code + "&ucl=" + ucl + "&tipo=" + valorSeleccionadoTipo + "&estado=" + valorSeleccionadoEstado;

                        // Enviar la solicitud POST con los datos
                        xhttp.send(data);
                    }
                }
            }
        };

        var url = "cargarExamenesPorFamilia.php?familia=" + familia + "&code=" + dataCodigo;
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function validarCodigo(codigo, inputElement) {
        var formatoCorrecto = /^[A-Z]-\d{4}-\d{4}-\d{3}-[A-Z]\d{2}$/;

        if (formatoCorrecto.test(codigo)) {
            inputElement.classList.remove('invalid-input');
        } else {
            inputElement.classList.add('invalid-input');
        }
    }

    function resetInput(inputElement) {
        inputElement.classList.remove('invalid-input');
    }

    function cambiarOpciones() {
            var tipoSelect = document.getElementById('tipo');
            var itemSelect = document.getElementById('item');

            // Limpiar las opciones actuales
            itemSelect.innerHTML = '<option value="SELECCIONAR ITEM">SELECCIONAR ITEM</option>';

            // Obtener el valor seleccionado en el primer select
            var tipoSeleccionado = tipoSelect.value;

            // Añadir las nuevas opciones según la selección
            if (tipoSeleccionado === 'SEGURIDAD') {
                agregarOpcionesSeguridad(itemSelect);
            } else if (tipoSeleccionado === 'OPERACIONAL') {
                agregarOpcionesOperacional(itemSelect);
            }
        }

        function agregarOpcionesSeguridad(selectElement) {
            var opcionesSeguridad = [
                'Identifica Condiciones de Seguridad',
                'Resguarda Condiciones de Seguridad'
            ];

            opcionesSeguridad.forEach(function(opcion) {
                var option = document.createElement('option');
                option.value = opcion;
                option.text = opcion;
                selectElement.add(option);
            });
        }

        function agregarOpcionesOperacional(selectElement) {
            var opcionesOperacional = [
                'Evaluar Contingencia',
                'Resolver Contigencias',
                'Preparar',
                'Traslado'
            ];

            opcionesOperacional.forEach(function(opcion) {
                var option = document.createElement('option');
                option.value = opcion;
                option.text = opcion;
                selectElement.add(option);
            });
        }
</script>
</body>
</html>