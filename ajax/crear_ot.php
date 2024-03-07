<?php session_start(); error_reporting(0);
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
    } else {
        header("Location: ../index.php");
        exit();
    }
    require_once('../admin/conex.php');
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
</head>
<style>
    :root {
        --color: #04C9FA;
    }
    a {
        text-decoration: none;
    }
    hr {
        border: 1px solid #000;
    }
    .date{
        position: absolute;
        top: 10px;
        right: 20px;
        width: 250px;
        height: auto;
    }
    .contenidos{
        position: relative;
        width: 100%;
        height: auto;
    }
    .tabla{
        box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
    }
    .custom-input {
    border: none;
    border-bottom: 1px solid white;
    margin: 0;
    padding: 5px;
    transition: border-bottom-color 0.4s; 
    }

    .custom-input:focus {
    outline: none;
    border-bottom-color: #04C8FC; 
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
    .registro {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%; 
        height: 100vh; 
        background-color: rgba(0, 0, 0, 0.8); 
        display: none;
        transition: opacity 0.8s ease;
    }
    .registro.mostrar {
        display: block; 
        opacity: 1; 
        pointer-events: auto;
    }
    .ingreso {
        width: 850px;
        height: auto;
        background-color: white; 
        margin: auto; 
        margin-top: calc(50vh - 50px); 
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }
    hr{
        box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        border: 1px solid #ccc;
    }
    .logo{
        position: absolute;
        top: 0;
        left: 50px;
        padding: 10px;
        z-index: 9999;
    }
    select {
    width: 80px; 
    overflow: hidden; 
    }

    select option {
        width: 200px; 
    }
    .informe_final {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10;
        display: none;
        padding: 50px;
        box-sizing: border-box;
    }
    .documental {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10;
        display: none;
        padding: 50px;
        box-sizing: border-box;
    }
    .info_maq {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10;
        display: none;
        padding: 50px;
        box-sizing: border-box;
    }
    .informe_brechas{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10;
        display: none;
        padding: 50px;
        box-sizing: border-box; 
    }
    #contend-iframe {
        width: 100%;
        height: auto;
    }
    #contend-document-iframe {
        width: 100%;
        height: auto;
    }
    #contend-info-maq {
        width: 100%;
        height: auto;
    }
    #contend-brechas {
        width: 100%;
        height: auto;  
    }
    .contend-info{
        background: white;
        margin: 10px;
        width: 100%;
        height: 100%;
        border-radius: 10px;
        padding: 50px;
        z-index: 11;
        overflow: auto;
        margin-top: 20px;
    }
    .contend-info-maq {
        background: white;
        margin: 10px;
        width: 100%;
        height: 100%;
        border-radius: 10px;
        padding: 50px;
        z-index: 11;
        overflow: auto;
        margin-top: 20px;
    }
    .contend-brechas {
        background: white;
        margin: 10px;
        width: 100%;
        height: 100%;
        border-radius: 10px;
        padding: 50px;
        z-index: 11;
        overflow: auto;
        margin-top: 20px;
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
        background-color: rgba(0, 0, 0, 0.5); 
        z-index: 1000; 
        }

        /* Estilo para el indicador de carga en sí */
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
</style>
<body>
<div class="logo">
    <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="200" height="90" title="OPERAMAQ" class="logo">
</div>
<br>
<center><h1>ORDEN DE TRABAJO</h1></center>
    <div class="container">
        <div class="date">
            <div class="input-group mb-1">
                <!--
                <span class="input-group-text" id="basic-addon1">Fecha</span>
                <input type="date" id="fecha" name="fecha" class="form-control" required>-->
            </div>
        </div>
        <div class="input-group input-group-sm mb-3" style="width: 300px;">
            <span class="input-group-text" id="inputGroup-sizing-sm">OT</span>
            <select name="ot" id="ot" class="form-control" onchange="buscarCotizaciones()">
                <option value="">Seleccione una opción</option>
                <?php
                    $query = mysqli_query($conn, "SELECT * FROM `ot` WHERE estado != 'CERRADO'");
                    while ($row = mysqli_fetch_array($query)) {
                        echo '<option value="'.$row['id_ot'].'" data-tipo="'.$row['tipo'].'">'.$row['id_ot'].'</option>';
                    }
                ?>
            </select>
        </div>
        <hr>
        <div class="result" id="result">
            
        </div>
    </div>
    <div class="registro" id="registro">
        <div class="ingreso">
            <center>Ingresar Operador</center>
            <hr>
            <div class="input-group mb-1">
                <input type="text" name="inRut" id="inRut" class="form-control rut-input" placeholder="RUT" maxlength="12" autocomplete="off" required>
                <input type="text" name="inNombre" id="inNombre" class="form-control" placeholder="Nombre" style="text-transform:capitalize;" autocomplete="off" required>
                <input type="text" name="inApellido" id="inApellido" class="form-control" placeholder="Apellidos" style="text-transform:capitalize;" autocomplete="off" required>
            </div>
            <div class="input-group mb-1">
            <input type="text" name="inMail" id="inMail" class="form-control" placeholder="Correo" autocomplete="off" required>                
            <button type="button" class="btn btn-primary" id="guardar"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
            </div>
        </div>
    </div>
<!--documental-->
    <div class="documental">
        <div class="contend-document">
            <iframe src="" frameborder="1" width="100%" height="auto" id="contend-document-iframe"></iframe>
        </div>
    </div>
<!--fin documental-->

<!--informe de brechas-->
    <div class="informe_brechas">
        <div class="contend-brechas">
            <iframe src="" frameborder="1" width="100%" height="auto" id="contend-brechas"></iframe>
        </div>
    </div>
<!-- fin informe de brechas -->

<!--informe final Operadores-->
    <div class="informe_final">
        <div class="contend-info">
            <iframe src="" frameborder="1" width="100%" height="auto" id="contend-iframe"></iframe>
        </div>
    </div>
<!--fin informe final-->

<!--informe final Maquinaria-->
    <div class="info_maq">
        <div class="contend-info-maq">
            <iframe src="" frameborder="1" width="100%" height="auto" id="contend-info-maq"></iframe>
        </div>
    </div>
<!--fin informe final maquinaria-->

<!--loading-->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loader"></div>
    </div>
<!--fin loading-->
</body>
<script>
function buscarCotizaciones() {
    var selectElement = document.getElementById("ot");
    var selectedValue = selectElement.value;

    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var tipoValue = selectedOption.dataset.tipo;
    console.log(tipoValue);

    var resultDiv = document.getElementById("result");

    if (selectedValue === "") {
        resultDiv.innerHTML = ""; 
        return;
    }

    if (tipoValue === "M") {
    // Llamar a la función correspondiente para el tipo M
    handleTipoM();
    } else if (tipoValue === "O") {
        // Llamar a la función correspondiente para el tipo O
        handleTipoO();
    } else {
        // Manejar otros casos o dejar en blanco según sea necesario
    }

    function handleTipoM() {
        // Lógica específica para el tipo M
        console.log("Tipo M seleccionado");
        // Llamar a otras funciones o realizar acciones adicionales según sea necesario
        var xhr = new XMLHttpRequest();
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;

                    // Trabajar dentro de la respuesta

                    // Obtiene el div con la clase 'info_maq'
                    var info_maqDiv = document.querySelector('.info_maq');
                    // Obtiene todos los elementos con la clase 'fa-info-circle'
                    var infoIcons = document.querySelectorAll('.info');
                    infoIcons.forEach(function (element) {
                        element.addEventListener('click', function (event) {
                            event.stopPropagation();
                            var dataInforme = element.getAttribute('data-informe');
                            info_maqDiv.style.display = 'block';
                            var iframe = document.getElementById("contend-info-maq");
                            iframe.src = 'informe_final_maq.php?dataInforme=' + dataInforme;
                            iframe.onload = function () {
                                iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
                            };
                        });
                    });

                    // Agrega un manejador de eventos de clic al div para cerrarlo
                    info_maqDiv.addEventListener('click', function() {
                    // Oculta el div al hacer clic sobre él
                        info_maqDiv.style.display = 'none';
                        buscarCotizaciones();
                    });

                    // Agrega un manejador de eventos de clic al div para cerrarlo
                    info_maqDiv.addEventListener('click', function() {
                    // Oculta el div al hacer clic sobre él
                        info_maqDiv.style.display = 'none';
                        buscarCotizaciones();
                    });

                    // Agrega un controlador de eventos clic a los íconos de guardar maquinaria
                    document.querySelectorAll('.save').forEach(function(icon){
                        icon.addEventListener('click', function(){
                            // Encuentra la fila actual
                            var row = this.closest('tr');
                            
                            // Recopila los datos de los campos de entrada en esa fila
                            var cliente = document.getElementById("cliente").value;
                            var faena = document.getElementById("faena").value;
                            var contacto = document.getElementById("contacto").value;
                            var telefono = document.getElementById("telefono").value;
                            var mail = document.getElementById("mail").value;
                            var id_ot = row.querySelector('.custom-input[name="id_ot[]"]').value;
                            var equipo = row.querySelector('.custom-input[name="equipo[]"]').value;
                            var fechaInput = row.querySelector('.custom-input[name="fecha[]"]');
                            var fecha = fechaInput.value;
                            var ip = row.querySelector('.custom-input[name="insp[]"]').value;
                            var patente = row.querySelector('.custom-input[name="patente[]"]').value;

                            // Muestra los datos por consola
                            console.log('Cliente:', cliente);
                            console.log('Faena:', faena);
                            console.log('Contacto:', contacto);
                            console.log('Telefono:', telefono);
                            console.log('Mail:', mail);
                            console.log('ID OT:', id_ot);
                            console.log('Equipo:', equipo);
                            console.log('Patente:', patente);
                            console.log('Fecha Visita:', fecha);
                            console.log('Inspector:', ip);

                            if(patente.trim() === ''){
                                swal({
                                    title: "Advertencia",
                                    text: "Por favor, indicar PATENTE o CODIGO INTERNO.",
                                    icon: "info",
                                    button: "OK",
                                }).then((value) => {
                                    patente.focus();
                                });
                                return;
                                document.getElementById("loading-overlay").style.display = "none";
                            }
                            
                            if (fecha.trim() === '') {
                                swal({
                                    title: "Advertencia",
                                    text: "El Campo FECHA no puede estar vacío",
                                    icon: "info",
                                    button: "OK",
                                }).then((value) => {
                                    fechaInput.focus();
                                });
                                return;
                                document.getElementById("loading-overlay").style.display = "none";
                            }

                            // Llama a la función para guardar los datos aquí
                            document.getElementById("loading-overlay").style.display = "none";
                            saveMachine(cliente, faena, contacto, telefono, mail, id_ot, equipo, fecha, ip, patente);
                        });
                    });

                    // Funcion para guardar datos de maquinarias

                    function saveMachine(cliente, faena, contacto, telefono, mail, id_ot, equipo, fecha, ip, patente){
                        var xhr = new XMLHttpRequest();

                        document.getElementById("loading-overlay").style.display = "block";

                        xhr.open('POST', 'saveDetallesOtM.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/json');

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                var data = JSON.parse(xhr.responseText);
                                console.log('Respuesta del servidor:', data);

                                if (data.status === 'success') {
                                    swal({
                                        icon: 'success',
                                        title: '¡Bien hecho!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                    buscarCotizaciones();
                                } else if (data.status === 'info') {
                                    swal({
                                        icon: 'info',
                                        title: '¡Advertencia!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                    buscarCotizaciones();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Algo salió mal!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                }

                            } else {
                                console.error('Error al guardar los datos:', xhr.statusText);
                                document.getElementById("loading-overlay").style.display = "none";
                            }
                        };


                        xhr.onerror = function () {
                            console.error('Error de red al intentar guardar los datos.');
                        };

                        var dataToSend = JSON.stringify({
                            cliente: cliente,
                            faena: faena,
                            contacto: contacto,
                            telefono: telefono,
                            mail: mail,
                            id_ot: id_ot,
                            equipo: equipo,
                            fecha: fecha,
                            ip: ip,
                            patente: patente
                        });

                        xhr.send(dataToSend);
                    }

                    const guardarFilas = document.getElementById("guardarFilas");

                    const element = document.getElementById("ot");
                    const elementValue = element.value;

                    guardarFilas.addEventListener("click", () => {
                        var FilasIncompletas = 0;

                        if (FilasIncompletas === 0) {
                            swal({
                                title: "¡Todos los datos están completos!",
                                text: "¿Deseas registrar la OT como en PROCESO?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then(function(willDelete) {
                                if (willDelete) {
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "save_en_proceso.php", true);
                                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState == 4 && xhr.status == 200) {
                                    swal("Los Datos han sido guardados con éxito!", { icon: "success" });
                                    }
                                };
                                xhr.send("ot=" + elementValue);
                                } else {
                                swal("La OT se mantiene intacta!");
                                }
                            });
                        } else {
                            swal("Advertencia!", "Faltan completar " + FilasIncompletas + " fila(s).", "info");
                        }
                    });


                    // Fin del trabajo dentro de la respuesta
                } else {
                    resultDiv.innerHTML = "Error al cargar los datos.";
                }
            }
        };

        xhr.open("POST", "buscarDatosOtM.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var data = "ot=" + encodeURIComponent(selectedValue);
        xhr.send(data);
    }

    function handleTipoO() {
        // Lógica específica para el tipo O
        console.log("Tipo O seleccionado");
        // Llamar a otras funciones o realizar acciones adicionales según sea necesario
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;

                    // Trabajar dentro de la respuesta

                    var informe_brechas= document.querySelector('.informe_brechas');

                    var infoIconsBrecha = document.querySelectorAll('.br');

                    infoIconsBrecha.forEach(function (element) {
                        element.addEventListener('click', function (event) {
                            event.stopPropagation();
                            var dataInforme = element.getAttribute('data-brecha');
                            informe_brechas.style.display = 'block';
                            var iframeBrecha = document.getElementById("contend-brechas");
                            iframeBrecha.src = 'brechas_info.php?data-brecha=' + dataInforme;
                            iframeBrecha.onload = function () {
                                iframeBrecha.style.height = iframeBrecha.contentWindow.document.body.scrollHeight + 'px';
                            };
                        });
                    });

                    informe_brechas.addEventListener('click', function() {
                        informe_brechas.style.display = 'none';
                        buscarCotizaciones();
                    });
                    
                    // sector de Operadores
                    
                    // Selecciona todos los elementos con la clase "rut-input"
                    const rutInputs = document.querySelectorAll(".rut-input");

                    // Itera sobre cada elemento y agrega un evento de entrada
                    rutInputs.forEach(input => {
                        input.addEventListener("input", function() {
                            // Obtiene el valor del input, lo limpia y formatea
                            const inputValue = this.value.trim();
                            const cleanedValue = inputValue.replace(/[^\dKk.-]/g, "");
                            const formattedValue = formatRut(cleanedValue);
                            
                            // Establece el valor formateado de nuevo en el input
                            this.value = formattedValue;
                        });
                    });

                    // Función para formatear el RUT
                    function formatRut(rut) {
                        // Convierte el RUT a mayúsculas
                        rut = rut.toUpperCase();

                        // Verifica si el RUT tiene más de un carácter
                        if (rut.length > 1) {
                            // Obtiene el último carácter
                            const lastChar = rut[rut.length - 1];

                            // Elimina puntos y guiones, formatea los números con puntos y agrega el último carácter
                            rut = rut.substring(0, rut.length - 1).replace(/\./g, "").replace(/\-/g, "");
                            rut = rut.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                            rut = rut + "-" + lastChar;
                        }

                        // Devuelve el RUT formateado
                        return rut;
                    }

                    // Selecciona todos los elementos con la clase "nombre-input"
                    const nombreInputs = document.querySelectorAll('.nombre-input');

                    // Itera sobre cada elemento "rut-input" y agrega un evento de entrada
                    rutInputs.forEach((input, index) => {
                        input.addEventListener('input', function() {
                            // Obtiene los atributos de fila y columna
                            const row = this.getAttribute('data-row');
                            const column = this.getAttribute('data-column');
                            
                            // Obtiene el valor del RUT
                            const rut = this.value;

                            // Obtiene el campo de nombre correspondiente
                            const nombreInput = nombreInputs[index];

                            // Verifica si el RUT tiene al menos 11 caracteres
                            if (rut.length >= 11) {
                                // Crea una nueva instancia de XMLHttpRequest
                                const xhr = new XMLHttpRequest();

                                // Configura y envía una solicitud GET para buscar el operador
                                xhr.open('GET', `buscarOperadorOt.php?rut=${rut}`, true);
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        // Parsea la respuesta JSON
                                        const response = JSON.parse(xhr.responseText);

                                        // Verifica si se encontró un operador
                                        if (response.nombre && response.apellidos) {
                                            console.log(`Escribiendo en fila ${parseInt(row) + 1}, columna ${column}`);
                                            console.log(`Nombre del operador: ${response.nombre} ${response.apellidos}`);

                                            // Muestra el nombre concatenado en el campo de nombre
                                            nombreInput.value = `${response.nombre} ${response.apellidos}`;
                                        } else {
                                            console.log("Operador no encontrado");

                                            // Muestra un mensaje de información y limpia el campo de nombre
                                            swal({
                                                title: "Información!",
                                                text: "Operador no existe en la base de datos!",
                                                icon: "info",
                                                button: "OK",
                                            });
                                        }
                                    }
                                };
                                xhr.send();
                            } else {
                                // Limpiar el campo de nombre si el RUT es demasiado corto
                                nombreInput.value = '';
                            }
                        });
                    });

                    // Obtiene el botón de mostrar registro por su ID
                    const mostrarRegistroBtn = document.getElementById("mostrarRegistro");

                    // Obtiene el div de registro por su ID
                    const registroDiv = document.getElementById("registro");

                    // Agrega un evento de clic al botón de mostrar registro
                    mostrarRegistroBtn.addEventListener("click", () => {
                        // Agrega la clase "mostrar" al div de registro para hacerlo visible
                        registroDiv.classList.add("mostrar");
                    });

                    // Agrega un evento de clic al div de registro
                    registroDiv.addEventListener("click", (event) => {
                        // Verifica si el clic se realizó en el propio div de registro
                        if (event.target === registroDiv) {
                            // Remueve la clase "mostrar" para ocultar el div cuando se hace clic fuera de su contenido
                            registroDiv.classList.remove("mostrar");
                        }
                    });

                    // boton guardar para nuevo operador
                    const guardarBtn = document.getElementById("guardar");

                    guardarBtn.addEventListener("click", function() {
                        const inrut = document.getElementById("inRut").value;
                        const nombre = document.getElementById("inNombre").value;
                        const apellido = document.getElementById("inApellido").value;
                        const email = document.getElementById("inMail").value;

                        // Verificar si algún campo está vacío
                        if (inrut === '' || nombre === '' || apellido === '' || email === '') {
                            // Mostrar mensaje de advertencia usando SweetAlert
                            swal("Campos incompletos", "Por favor, completa todos los campos.", "warning");
                            return; // Detener el proceso si hay campos vacíos
                        }

                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "save_oper_ot.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    // Manejar la respuesta del servidor si es necesario
                                    console.log(xhr.responseText);
                                    swal("¡Bien hecho!", "El Operador ha sido agregado correctamente.", "success");
                                    // Limpiar los campos después de guardar con éxito
                                    document.getElementById("inRut").value = '';
                                    document.getElementById("inNombre").value = '';
                                    document.getElementById("inApellido").value = '';
                                    document.getElementById("inMail").value = '';
                                } else {
                                    // Manejar errores si ocurren
                                    console.error("Error en la solicitud:", xhr.status, xhr.statusText);
                                    swal("¡Algo salió mal!", "No se ha podido guardar el Operador.", "error");
                                }
                            }
                        };

                        const data = "inRut=" + encodeURIComponent(inrut) + "&inNombre=" + encodeURIComponent(nombre) + "&inApellido=" + encodeURIComponent(apellido) + "&inMail=" + encodeURIComponent(email);
                        xhr.send(data);
                    });
                    // fin para guaradar nuevo operador


                    // Agrega un controlador de eventos clic a los íconos de guardar operadores
                    document.querySelectorAll('.save').forEach(function(icon) {
                        icon.addEventListener('click', function() {
                            // Encuentra la fila actual
                            var row = this.closest('tr');
                            
                            // Recopila los datos de los campos de entrada en esa fila
                            var cliente = document.getElementById("cliente").value;
                            var faena = document.getElementById("faena").value;
                            var contacto = document.getElementById("contacto").value;
                            var telefono = document.getElementById("telefono").value;
                            var mail = document.getElementById("mail").value;
                            var id_ot = row.querySelector('.custom-input[name="id_ot[]"]').value;
                            var rutInput = row.querySelector('.rut-input');
                            var rut = rutInput.value;
                            var nombre = row.querySelector('.nombre-input').value;
                            var status = row.querySelector('.custom-input[name="status[]"]').value;
                            var equipo = row.querySelector('.custom-input[name="equipo[]"]').value;
                            var modeloInput = row.querySelector('.custom-input[name="modelo[]"]');
                            var modelo = modeloInput.value;
                            var eva = row.querySelector('.custom-input[name="insp[]"]').value;
                            var fechaInput = row.querySelector('.custom-input[name="fecha[]"]');
                            var fecha = fechaInput.value;

                            // Muestra los datos por consola
                            console.log('Cliente:', cliente);
                            console.log('Faena:', faena);
                            console.log('Contacto:', contacto);
                            console.log('Telefono:', telefono);
                            console.log('Mail:', mail);
                            console.log('ID OT:', id_ot);
                            console.log('RUT:', rut);
                            console.log('Nombre:', nombre);
                            console.log('Status:', status);
                            console.log('Equipo:', equipo);
                            console.log('Modelo:', modelo);
                            console.log('Fecha:', fecha);
                            console.log('Evaluador:', eva);

                            // Validar que el campo de rut no esté vacío
                            if (rut.trim() === '') {
                                swal({
                                    title: "Advertencia!",
                                    text: "El Campo RUT no puede estar vacio!",
                                    icon: "info",
                                    button: "OK!",
                                }).then((value) => {
                                    rutInput.focus();
                                });
                                return;
                                document.getElementById("loading-overlay").style.display = "none";
                            }

                            if(modelo.trim() === ''){
                                swal({
                                    title: "Advertencia!",
                                    text: "El Campo MODELO no puede estar vacio!",
                                    icon: "info",
                                    button: "OK!",
                                }).then((value) => {
                                    modeloInput.focus();
                                });
                                return;
                                document.getElementById("loading-overlay").style.display = "none";
                            }

                            if (fecha.trim() === '') {
                                swal({
                                    title: "Advertencia",
                                    text: "El Campo FECHA no puede estar vacío",
                                    icon: "info",
                                    button: "OK",
                                }).then((value) => {
                                    fechaInput.focus();
                                });
                                return;
                                document.getElementById("loading-overlay").style.display = "none";
                            }
                            // Llama a la función para guardar los datos aquí
                            document.getElementById("loading-overlay").style.display = "none";
                            guardarDatos(cliente, faena, contacto, telefono, mail, id_ot, rut, nombre, status, equipo, modelo, eva, fecha);
                            
                        });
                    });

                    // Función para guardar los datos
                    function guardarDatos(cliente, faena, contacto, telefono, mail, id_ot, rut, nombre, status, equipo, modelo, eva, fecha) {
                        var xhr = new XMLHttpRequest();

                        document.getElementById("loading-overlay").style.display = "block";

                        xhr.open('POST', 'saveDetallesOt.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/json');

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                var data = JSON.parse(xhr.responseText);
                                console.log('Respuesta del servidor:', data);

                                if (data.status === 'success') {
                                    swal({
                                        icon: 'success',
                                        title: '¡Bien hecho!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                    buscarCotizaciones();
                                } else if (data.status === 'info') {
                                    swal({
                                        icon: 'info',
                                        title: '¡Advertencia!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                    buscarCotizaciones();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Algo salió mal!',
                                        text: data.message
                                    });
                                    document.getElementById("loading-overlay").style.display = "none";
                                }

                            } else {
                                console.error('Error al guardar los datos:', xhr.statusText);
                                document.getElementById("loading-overlay").style.display = "none";
                            }
                        };


                        xhr.onerror = function () {
                            console.error('Error de red al intentar guardar los datos.');
                        };

                        var dataToSend = JSON.stringify({
                            cliente: cliente,
                            faena: faena,
                            contacto: contacto,
                            telefono: telefono,
                            mail: mail,
                            id_ot: id_ot,
                            rut: rut,
                            nombre: nombre,
                            status: status,
                            equipo: equipo,
                            modelo: modelo,
                            eva: eva,
                            fecha: fecha
                        });

                        xhr.send(dataToSend);
                    }

                    // Obtén el elemento del botón "Guardar Todas las Filas" por su ID
                    const guardarTodasFilas = document.getElementById("guardarTodasFilas");

                    // Obtén el valor del elemento con ID "ot"
                    const Element = document.getElementById("ot");
                    const ElementValue = Element.value;

                    // Agrega un evento de clic al botón "Guardar Todas las Filas"
                    guardarTodasFilas.addEventListener("click", () => {
                        // Inicializa el contador de filas incompletas
                        var filasIncompletas = 0;

                        // Obtén todos los elementos de entrada con la clase "custom-input" y atributo "data-column" igual a "rut"
                        var rutInputs = document.querySelectorAll(".custom-input[data-column='rut']");

                        // Recorre los elementos de entrada de RUT y verifica si están vacíos o son inválidos
                        for (var i = 0; i < rutInputs.length; i++) {
                            var rut = rutInputs[i].value;

                            // Si el RUT está vacío o no es válido, incrementa el contador de filas incompletas
                            if (rut === '' || !validarRut(rut)) {
                                filasIncompletas++;
                            }
                        }

                        // Si no hay filas incompletas, muestra un mensaje para confirmar el registro como en proceso
                        if (filasIncompletas === 0) {
                            swal({
                                title: "¡Todos los datos están completos!",
                                text: "¿Deseas registrar la OT como en PROCESO?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then(function(willDelete) {
                                // Si se confirma, realiza una solicitud POST para guardar la OT como en proceso
                                if (willDelete) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", "save_en_proceso.php", true);
                                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState == 4 && xhr.status == 200) {
                                            swal("Los Datos han sido guardados con éxito!", { icon: "success" });
                                        }
                                    };
                                    xhr.send("ot=" + ElementValue);
                                } else {
                                    // Si se cancela, muestra un mensaje indicando que la OT se mantiene intacta
                                    swal("La OT se mantiene intacta!");
                                }
                            });
                        } else {
                            // Si hay filas incompletas, muestra un mensaje de advertencia con la cantidad de filas faltantes
                            swal("Advertencia!", "Faltan completar " + filasIncompletas + " fila(s).", "info");
                        }
                    });

                    // documental
                    var documentalDiv = document.querySelector('.documental');

                    var infoDocumet = document.querySelectorAll('.infoDocument');

                    infoDocumet.forEach(function(doct){
                        doct.addEventListener('click', function(event){
                            event.stopPropagation();

                            var data_document = doct.getAttribute('data-document');

                            documentalDiv.style.display = 'block';

                            var iframeDoc = document.getElementById("contend-document-iframe");
                            iframeDoc.src = 'documental_ot.php?data_document=' + data_document;

                            iframeDoc.onload = function() {
                                iframeDoc.style.height = iframeDoc.contentWindow.document.body.scrollHeight + 'px';
                            };
                        });
                    });

                    
                    documentalDiv.addEventListener('click', function() {
                        documentalDiv.style.display = 'none';
                        buscarCotizaciones();
                    });

                    // Obtiene el div con la clase 'informe_final'
                    var informeFinalDiv = document.querySelector('.informe_final');

                    // Obtiene todos los elementos con la clase 'fa-info-circle'
                    var infoIcons = document.querySelectorAll('.info');

                    infoIcons.forEach(function(icon) {
                        icon.addEventListener('click', function(event) {
                            event.stopPropagation(); // Evita que el clic se propague al div padre

                            // Obtiene el valor del atributo 'data-informe'
                            var dataInforme = icon.getAttribute('data-informe');

                            // Muestra el div con la clase "informe_final"
                            informeFinalDiv.style.display = 'block';

                            var iframe = document.getElementById("contend-iframe");
                            iframe.src = 'informe_final_ot.php?dataInforme=' + dataInforme;

                            iframe.onload = function() {
                                iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
                            };
                        });
                    });

                    // Agrega un manejador de eventos de clic al div para cerrarlo
                    informeFinalDiv.addEventListener('click', function() {
                    // Oculta el div al hacer clic sobre él
                        informeFinalDiv.style.display = 'none';
                        buscarCotizaciones();
                    });

                    // Fin del trabajo dentro de la respuesta
                } else {
                    resultDiv.innerHTML = "Error al cargar los datos.";
                }
            }
        };

        xhr.open("POST", "buscarDatosOt.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var data = "ot=" + encodeURIComponent(selectedValue);
        xhr.send(data);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    var correoInput = document.getElementById("mail");
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;

    correoInput.addEventListener("input", function() {
        this.value = this.value.replace(/\s/g, "").toLowerCase();
    });

    correoInput.addEventListener("blur", function() {
        if (emailPattern.test(this.value)) {
            
        } else {
            swal("Ups!", "Formato invalido del correo!", "info");
        }
    });

});

/* codigo para buscar nombre a traves del rut*/
let searchName = document.getElementById('inRut');
let inNombre = document.getElementById('inNombre');

searchName.addEventListener('blur', function () {
    buscarNombre();
});

function buscarNombre() {
    // Assuming searchName.value contains the RUT input value
    const url = `https://api.boostr.cl/rut/name/${searchName.value}.json`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    }).then(response => {
        if (!response.ok) {
            throw new Error('No hay respuesta de la red');
        }
        return response.json();
    }).then(data => {
        cargarDatosEnTabla(data)
    }).catch(error => console.error('Error:', error));
}

function cargarDatosEnTabla(data) {
    if (data.status === 'success') {
        const nameData = data.data;

        inNombre.value = nameData.name;
    }else {
        console.log('Error en la consulta');
    }
}
/* fin del codigo */
function manejarGuardadoMaquinaria() {
                // Agrega un controlador de eventos clic a los íconos de guardar maquinaria
                document.querySelectorAll('.salvar').forEach(function(icon){
                    icon.addEventListener('click', function(){
                        // Encuentra la fila actual
                        var row = this.closest('tr');
                        
                        // Recopila los datos de los campos de entrada en esa fila
                        var cliente = document.getElementById("cliente").value;
                        var faena = document.getElementById("faena").value;
                        var contacto = document.getElementById("contacto").value;
                        var telefono = document.getElementById("telefono").value;
                        var mail = document.getElementById("mail").value;
                        var id_ot = row.querySelector('.custom-input[name="id_ot[]"]').value;
                        var equipo = row.querySelector('.custom-input[name="equipo[]"]').value;
                        var fechaInput = row.querySelector('.custom-input[name="fecha[]"]');
                        var fecha = fechaInput.value;
                        var ip = row.querySelector('.custom-input[name="insp[]"]').value;
                        var patente =  row.querySelectorAll('.custom-input[name="patente[]"]').value;

                        // Muestra los datos por consola
                        console.log('Cliente:', cliente);
                        console.log('Faena:', faena);
                        console.log('Contacto:', contacto);
                        console.log('Telefono:', telefono);
                        console.log('Mail:', mail);
                        console.log('ID OT:', id_ot);
                        console.log('Equipo:', equipo);
                        console.log('Patente:', patente);
                        console.log('Fecha Visita:', fecha);
                        console.log('Inspector:', ip);

                        if (fecha.trim() === '') {
                            swal({
                                title: "Advertencia",
                                text: "El Campo FECHA no puede estar vacío",
                                icon: "info",
                                button: "OK",
                            }).then((value) => {
                                fechaInput.focus();
                            });
                            return;
                            document.getElementById("loading-overlay").style.display = "none";
                        }

                        // Llama a la función para guardar los datos aquí
                        document.getElementById("loading-overlay").style.display = "none";
                        saveMachine(cliente, faena, contacto, telefono, mail, id_ot, equipo, fecha, ip, patente);
                    });
                });

                // Funcion para guardar datos de maquinarias

                function saveMachine(cliente, faena, contacto, telefono, mail, id_ot, equipo, fecha, ip, patente){
                    var xhr = new XMLHttpRequest();

                    document.getElementById("loading-overlay").style.display = "block";

                    xhr.open('POST', 'saveDetallesOtM.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            console.log('Respuesta del servidor:', data);

                            if (data.status === 'success') {
                                swal({
                                    icon: 'success',
                                    title: '¡Bien hecho!',
                                    text: data.message
                                });
                                document.getElementById("loading-overlay").style.display = "none";
                                buscarCotizaciones();
                            } else if (data.status === 'info') {
                                swal({
                                    icon: 'info',
                                    title: '¡Advertencia!',
                                    text: data.message
                                });
                                document.getElementById("loading-overlay").style.display = "none";
                                buscarCotizaciones();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Algo salió mal!',
                                    text: data.message
                                });
                                document.getElementById("loading-overlay").style.display = "none";
                            }

                        } else {
                            console.error('Error al guardar los datos:', xhr.statusText);
                            document.getElementById("loading-overlay").style.display = "none";
                        }
                    };


                    xhr.onerror = function () {
                        console.error('Error de red al intentar guardar los datos.');
                    };

                    var dataToSend = JSON.stringify({
                        cliente: cliente,
                        faena: faena,
                        contacto: contacto,
                        telefono: telefono,
                        mail: mail,
                        id_ot: id_ot,
                        equipo: equipo,
                        fecha: fecha,
                        ip: ip,
                        patente: patente
                    });

                    xhr.send(dataToSend);
                }

                const guardarFilas = document.getElementById("guardarFilas");

                const element = document.getElementById("ot");
                const elementValue = element.value;

                guardarFilas.addEventListener("click", () => {
                    var FilasIncompletas = 0;

                    if (FilasIncompletas === 0) {
                        swal({
                            title: "¡Todos los datos están completos!",
                            text: "¿Deseas registrar la OT como en PROCESO?",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then(function(willDelete) {
                            if (willDelete) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "save_en_proceso.php", true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                swal("Los Datos han sido guardados con éxito!", { icon: "success" });
                                }
                            };
                            xhr.send("ot=" + elementValue);
                            } else {
                            swal("La OT se mantiene intacta!");
                            }
                        });
                    } else {
                        swal("Advertencia!", "Faltan completar " + FilasIncompletas + " fila(s).", "info");
                    }
                });
            }
</script>
</html>