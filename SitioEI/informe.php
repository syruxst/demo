<?php session_start(); error_reporting(1);
require_once('../admin/conex.php');
if (isset($_SESSION['usuario'])) {
    if (isset($_SESSION['usuario'])) {
       $usuario = $_SESSION['usuario'];
       $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['name'];
            $ev = $row['ev'];
            $ip = $row['ip'];
    } 
} else {
    header("Location: ../logInsp.php");
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
    <title>Document</title>
    <style>
        :root {
            --color: #04C9FA;
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        h1 {
            color: var(--color);
        }
        hr{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        .tabla{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
            border-radius: 10px;
        }
        .contenido {
            border: 1px solid #e5e5e5;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
        }
        label{
            color: var(--color);
            font-weight: bold;
        }
        .input-group {
            display: flex;
            flex-wrap: wrap;
        }
        .timbre-rechazado {
            width: 400px;
            height: 200px;
            border-radius: 10px;
            transform: translate(-50%, -50%) rotate(-0deg); 
            position: absolute;
            top: 50%;
            left: 50%;
            transform-origin: 0 0;
            padding: 30px; 
            text-align: center;
            display: none;
        }

        .timbre-rechazado h2 {
            font-size: 60px;
            color: rgba(255, 0, 0, 0.2); 
            margin: 0; 
            font-weight: bold;
        }

        @media (max-width: 666px) {
            body{
                padding: 0;
            }
            .tabla {
                font-size: 12px;
            }
            .input-group-text {
                flex-basis: 100%; 
            }

            input.form-control {
                flex-basis: 100%; 
            }
        }
    </style>
</head>
<body>

<div class="timbre-rechazado"><h2>RECHAZADO</h2></div>
    <center><h1>Informe Observación en Terreno</h1></center>
    <hr>
    
    <?php
    $id = $_GET['dataId'];
    $query = "SELECT * FROM detallle_ot WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $folio = $row['folio'];
    ?>
    <form action="save_informe.php" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
        <tr>
            <td>
                ID
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $folio; ?>
            </td>
            <td>
                EQUIPO
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $row['equipo']; ?>
            </td>
            <td>
                FECHA 
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo date("d-m-Y", strtotime($row['fecha'])); ?>
            </td>
        </tr>
        <tr>
            <td>
                Nombre del Candidato
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $row['nombre']; ?>
            </td>
            <td>
                RUT
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $row['rut']; ?>
            </td>
            <td>
               <b>Evaluador</b>
            </td>
            <td>
                :
            </td>
            <td>
            <?php echo $nombre; ?>
            </td>        
        </tr>
    </table>
    <hr>
    <br>
    <div class="contenido">
        <table width="100%" border="0" cellspacing="8" cellpadding="6">
            <tr>
                <th>Item</th><th>Descripción</th><th colspan="4">Evaluación</th><th>Justificación</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td title="Logro Deficiente">LD</td>
                <td title="Logro Insuficiente">LI</td>
                <td title="Logro Suficiente">LS</td>
                <td title="Logro Superior">LSu</td>
                <td></td>
            </tr>
            <tr>
                <td>1</td><td>Condiciones de Operación</td>
                <td><input type="radio" name="1" value="5.5444444444"></td>
                <td><input type="radio" name="1" value="8.877777778"></td>
                <td><input type="radio" name="1" value="9.99"></td>
                <td><input type="radio" name="1" value="11.1111111"></td>
                <td><select name="op1" id="op1" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>  
                </select></td>
            </tr>
            <tr>
                <td>2</td><td>Traslado de equipo a área de trabajo</td>
                <td><input type="radio" name="2" value="5.5444444444"></td>
                <td><input type="radio" name="2" value="8.877777778"></td>
                <td><input type="radio" name="2" value="9.99"></td>
                <td><input type="radio" name="2" value="11.1111111"></td>
                <td><select name="op2" id="op2" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>                
                </select></td>
            </tr>
            <tr>
                <td>3</td><td>Operación de equipo</td>
                <td><input type="radio" name="3" value="5.5444444444"></td>
                <td><input type="radio" name="3" value="8.877777778"></td>
                <td><input type="radio" name="3" value="9.99"></td>
                <td><input type="radio" name="3" value="11.1111111"></td>
                <td><select name="op3" id="op3" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>            
                </select></td>
            </tr>
            <tr>
                <td>4</td><td>¿Identifica las condiciones de seguridad?</td>
                <td><input type="radio" name="4" value="5.5444444444"></td>
                <td><input type="radio" name="4" value="8.877777778"></td>
                <td><input type="radio" name="4" value="9.99"></td>
                <td><input type="radio" name="4" value="11.1111111"></td>
                <td><select name="op4" id="op4" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>           
                </select></td>
            </tr>
            <tr>
                <td>5</td><td>¿Resguarda las condiciones de seguridad?</td>
                <td><input type="radio" name="5" value="5.5444444444"></td>
                <td><input type="radio" name="5" value="8.877777778"></td>
                <td><input type="radio" name="5" value="9.99"></td>
                <td><input type="radio" name="5" value="11.1111111"></td>
                <td><select name="op5" id="op5" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>         
                </select></td>
            </tr>
            <tr>
                <td>6</td><td>¿Cuenta y conoce los procedimientos de trabajo?</td>
                <td><input type="radio" name="6" value="5.5444444444"></td>
                <td><input type="radio" name="6" value="8.877777778"></td>
                <td><input type="radio" name="6" value="9.99"></td>
                <td><input type="radio" name="6" value="11.1111111"></td>
                <td><select name="op6" id="op6" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>         
                </select></td>
            </tr>
            <tr>
                <td>7</td><td>¿Cuenta y conoce manual de operaciones?</td>
                <td><input type="radio" name="7" value="5.5444444444"></td>
                <td><input type="radio" name="7" value="8.877777778"></td>
                <td><input type="radio" name="7" value="9.99"></td>
                <td><input type="radio" name="7" value="11.1111111"></td>
                <td><select name="op7" id="op7" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>           
                </select></td>
            </tr>
            <tr>
                <td>8</td><td>¿Cuenta con antecedentes historicos para operar el equipo?</td>
                <td><input type="radio" name="8" value="5.5444444444"></td>
                <td><input type="radio" name="8" value="8.877777778"></td>
                <td><input type="radio" name="8" value="9.99"></td>
                <td><input type="radio" name="8" value="11.1111111"></td>
                <td><select name="op8" id="op8" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>          
                </select></td>
            </tr>
            <tr>
                <td>9</td><td>¿Cuenta con licencia adecuada al cargo que postula?</td>
                <td><input type="radio" name="9" value="5.5444444444"></td>
                <td><input type="radio" name="9" value="8.877777778"></td>
                <td><input type="radio" name="9" value="9.99"></td>
                <td><input type="radio" name="9" value="11.1111111"></td>
                <td><select name="op9" id="op9" class="form-control" style="width: 130px;">
                    <option value="0">Seleccione</option>
                    <option value="Logro Deficiente">Logro Deficiente</option>
                    <option value="Logro Insuficiente">Logro Insuficiente</option>
                    <option value="Logro Suficiente">Logro Suficiente</option>
                    <option value="Logro Superior">Logro Superior</option>
                </select></td>
            </tr>
        </table>
        <br>
        <div class="input-group mb-3">
            <span class="input-group-text" id="resultado">Puntaje: 0</span>
            <span class="input-group-text" id="porcentaje">Porcentaje: 0%</span>
            <button type="button" class="btn btn-info" onclick="calcularTotal()">Calcular</button>
        </div>
    </div>
    <br>
    <div class="contenido">
        <label>Cargar Evidencia</label>
        <br>
        <br>
        <!-- valores para ser enviados junto a la demas info-->
        <input type="hidden" name="IdOper" value="<?php echo $id; ?>">
        <input type="hidden" name="folio" value="<?php echo $folio; ?>">
        <input type="hidden" name="equipo" value="<?php echo $row['equipo']; ?>">
        <input type="hidden" name="fecha" value="<?php echo $row['fecha']; ?>">
        <input type="hidden" name="nombre_candidato" value="<?php echo $row['nombre']; ?>">
        <input type="hidden" name="rut" value="<?php echo $row['rut']; ?>">
        <input type="hidden" name="evaluador" value="<?php echo $nombre; ?>">
        <input type="hidden" name="resultado" id="result" value="">
        <input type="hidden" name="por" id="por" value="">
        <input type="hidden" name="puntaje" id="puntaje" value="">
        <!-- fin -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Foto 1</span>
            <span class="input-group-text" id="basic-addon1-new" style="background-color: #62BF4C; display: none;">
                <i class="fa fa-check" style="color: white;" aria-hidden="true"></i>
            </span>
            <input type="file" class="form-control file-input" name="photography1" id="photography1" accept=".jpg, .jpeg, .png">
            
            <span class="input-group-text" id="basic-addon2">Foto 2</span>
            <span class="input-group-text" id="basic-addon2-new" style="background-color: #62BF4C; display: none;">
                <i class="fa fa-check" style="color: white;" aria-hidden="true"></i>
            </span>
            <input type="file" class="form-control file-input" name="photography2" id="photography2" accept=".jpg, .jpeg, .png">
            
            <span class="input-group-text" id="basic-addon3">Informe</span>
            <span class="input-group-text" id="basic-addon3-new" style="background-color: #62BF4C; display: none;">
                <i class="fa fa-check" style="color: white;" aria-hidden="true"></i>
            </span>
            <input type="file" class="form-control file-input" name="photography3" id="photography3" accept=".jpg, .jpeg, .png">
        </div>
        <textarea cols="30" rows="6" class="form-control" placeholder="Observaciones" name="obs" id="obs"></textarea>
    </div>
    <br>
    <br>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Lugar de Evaluación</span>
        <input type="text" name="lugar" id="lugar" class="form-control" required>
    </div>
     
    <button type="submit" name="guardar" class="btn btn-primary">GUARDAR INFORME</button>
    <script>
        function calcularTotal() {
            var total = 0;
            var radioInputs = document.querySelectorAll('input[type="radio"]');
            
            for (var i = 0; i < radioInputs.length; i++) {
                if (radioInputs[i].checked) {
                    total += parseFloat(radioInputs[i].value);
                }
            }

            var totalPorcentaje = (total / 100) * 100;

            var resultado = totalPorcentaje < 80 ? "RECHAZADO" : "APROBADO";

            var timbreRechazado = document.querySelector('.timbre-rechazado');
            if (totalPorcentaje < 80) {
                timbreRechazado.style.display = 'block'; 
            } else {
                timbreRechazado.style.display = 'none'; 
            }

            document.getElementById('resultado').textContent = "Puntaje: " + parseInt(total);
            document.getElementById('porcentaje').textContent = "Porcentaje: " + totalPorcentaje.toFixed(1) + "%";

            var resultadoInput = document.getElementById('result'); 
            resultadoInput.value = resultado;

            var porcentajeInput = document.getElementById('por');
            porcentajeInput.value = totalPorcentaje.toFixed(2);

            var puntajeInput = document.getElementById('puntaje');
            puntajeInput.value = total;
        }

        var formulario = document.querySelector('form');
        

        formulario.addEventListener('submit', function(event) {
            event.preventDefault(); 

            var radioButtons = formulario.querySelectorAll('input[type="radio"]');
            var allRadioSelected = true;

            var lugar  = document.getElementById('lugar').value;

            radioButtons.forEach(function(radio) {
                var name = radio.getAttribute("name");
                var selectedRadio = document.querySelector('input[type="radio"][name="' + name + '"]:checked');
                if (!selectedRadio) {
                    allRadioSelected = false;
                    return;
                }
            });

            if (!allRadioSelected) {
                swal("¡Error!", "Por favor, verifique que todos los items este correctos.", "error");
                return;
            }

            var resultadoInput = document.getElementById('result');
            var resultadoValue = resultadoInput.value.trim();

            if (resultadoValue === '') {
                swal("¡Error!", "Por favor, presione el Boton CALCULAR antes de guardar!", "error");
                return;
            }

            if (lugar === '') {
                swal("¡Error!", "Por favor, ingrese el lugar de evaluación.", "error");
                return;
            }

            var filesNotEmpty = true;

            var fileInputs = formulario.querySelectorAll('input[type="file"]');
            for (var i = 0; i < fileInputs.length; i++) {
                if (fileInputs[i].files.length === 0) {
                    filesNotEmpty = false;
                    break;
                }
            }

            if (!filesNotEmpty) {
                swal("¡Error!", "Por favor, ingrese la imagenes de evidencia.", "error");
                return;
            }

            swal({
                title: 'Procesando...',
                text: 'Por favor, espere...',
                icon: 'info',
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
            });

            var formData = new FormData(formulario);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_informe.php', true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        if (xhr.responseText === 'success') {
                            swal("¡Éxito!", "Los datos se han guardado con éxito.", "success");
                            formulario.reset();
                        } else if (xhr.responseText === 'info') {
                            swal("Información", "El informe ya existe. Consulte con el Coordinador", "info");
                        } else {
                            swal("¡Error!", "Hubo un error al guardar los datos.", "error");
                        }
                    } else {
                        swal("¡Error!", "Hubo un error en la solicitud: " + xhr.status, "error");
                    }
                }
            };

            xhr.send(formData);
        });

        // Obtener todas las filas de la tabla excepto la primera (encabezado)
        var filas = document.querySelectorAll("table tr:not(:first-child)");

        // Función para cambiar la opción del select
        function cambiarOpcion(radio, select) {
        var valorRadio = radio.value;
        
        // Obtener el índice de la opción en el select
        var index = -1;
        switch (valorRadio) {
            case "5.5444444444": 
            index = 1;
            break;

            case "8.877777778":
            index = 2;
            break;

            case "9.99":
            index = 3;
            break;

            case "11.1111111":
            index = 4;
            break;
        }

        // Cambiar la selección del select según el índice
        select.selectedIndex = index;
        }

        // Iterar a través de las filas y asignar eventos a los radio buttons
        filas.forEach(function (fila) {
            var radioButtons = fila.querySelectorAll('input[type="radio"]');
            var select = fila.querySelector('select');

                radioButtons.forEach(function (radio) {
                    radio.addEventListener("click", function () {
                    cambiarOpcion(radio, select);
                    });
                });
        });

    // Inicializa un array para almacenar las leyendas
    var leyendas = [];

    // Obtén todos los elementos de radio
    var RadioInputs = document.querySelectorAll('input[type="radio"]');

    // Agrega un evento de cambio a cada elemento de radio
    RadioInputs.forEach(function (Radio) {
        Radio.addEventListener('change', function () {

            // Obtén el número del ítem y la descripción asociada al radio seleccionado
            var itemNumber = Radio.getAttribute('name');

            // Obtén el valor del radio seleccionado
            var RadioValue = parseFloat(Radio.value);

            // Busca si ya existe una leyenda para este ítem
            var existingIndex = leyendas.findIndex(function (leyenda) {
                return leyenda.startsWith('Brecha en Item ' + itemNumber + ':');
            });

            // Si el valor es 5.5444444444 y no existe una leyenda, agrégala al array
            if (RadioValue === 5.5444444444 && existingIndex === -1) {
                // Obtiene el texto del td correspondiente al ítem
                var itemName = Radio.parentNode.previousElementSibling.textContent;

                // Agrega una leyenda para el ítem específico
                leyendas.push('Logro Deficiente ' + ': ' + itemName);
            }

            // Si el valor es 8.877777778 y no existe una leyenda, agrégala al array
            else if (RadioValue === 8.877777778 && existingIndex === -1) {
                // Obtiene el texto del td correspondiente al ítem
                var itemName = Radio.parentNode.previousElementSibling.previousElementSibling.textContent;

                // Agrega una leyenda para el ítem específico
                leyendas.push('Logro Isuficiente ' + ': ' + itemName);
            }

            // Si el valor no es 0 y existe una leyenda, elimínala del array
            else if (RadioValue !== 5.5444444444 && RadioValue !== 8.877777778 && existingIndex !== -1) {
                leyendas.splice(existingIndex, 1);
            }
                        
            // Actualiza el textarea con las leyendas acumuladas
            document.getElementById('obs').value = leyendas.join('\n');
        });
    });

    document.getElementById('photography1').addEventListener('change', function () {
        var fileInput = this;
        var newAddon = document.getElementById('basic-addon1-new');
        var oldAddon = document.getElementById('basic-addon1');

        if (fileInput.files.length > 0) {
            // Archivo seleccionado, ocultar el antiguo y mostrar el nuevo
            newAddon.style.display = 'inline-block';
            oldAddon.style.display = 'none';
        } else {
            // No hay archivo seleccionado, mostrar el antiguo y ocultar el nuevo
            newAddon.style.display = 'none';
            oldAddon.style.display = 'inline-block';
        }    
    });
    document.getElementById('photography2').addEventListener('change', function () {
        var fileInput = this;
        var newAddon = document.getElementById('basic-addon2-new');
        var oldAddon = document.getElementById('basic-addon2');

        if (fileInput.files.length > 0) {
            // Archivo seleccionado, ocultar el antiguo y mostrar el nuevo
            newAddon.style.display = 'inline-block';
            oldAddon.style.display = 'none';
        } else {
            // No hay archivo seleccionado, mostrar el antiguo y ocultar el nuevo
            newAddon.style.display = 'none';
            oldAddon.style.display = 'inline-block';
        }    
    });
    document.getElementById('photography3').addEventListener('change', function () {
        var fileInput = this;
        var newAddon = document.getElementById('basic-addon3-new');
        var oldAddon = document.getElementById('basic-addon3');

        if (fileInput.files.length > 0) {
            // Archivo seleccionado, ocultar el antiguo y mostrar el nuevo
            newAddon.style.display = 'inline-block';
            oldAddon.style.display = 'none';
        } else {
            // No hay archivo seleccionado, mostrar el antiguo y ocultar el nuevo
            newAddon.style.display = 'none';
            oldAddon.style.display = 'inline-block';
        }    
    });
    </script>
</form>
</body>
</html>