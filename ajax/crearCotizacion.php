<?php session_start(); error_reporting(0);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../login.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../admin/conex.php');

  // Consulta para obtener la última ID de la tabla nomina

      //$folio = 0;

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
        .date{
            position: absolute;
            top: 10px;
            right: 10px;
            width: 250px;
            height: auto;
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
        #summaryDiv {
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 280px;
            float: right;
        }

        .detalles {
            clear: both; /* Añadido para "limpiar" el flotado */
            width: 100%;
            height: 100%;
            margin-top: 10px; /* Ajustado para el margen superior */
            top: 10px; 
            padding: 10px;
        }
        textarea {
            width: 100%;
            height: 100%;
            resize: none;
            border: none;
            outline: none;
            margin-top: 5px;
        }
        .logo{
            position: absolute;
            top: 0;
            left: 50px;
            padding: 10px;
            z-index: 9999;
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
    </style>
</head>
<body>
<div class="logo">
    <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="200" height="90" title="OPERAMAQ" class="logo">
</div>
<form action="save_Cotizacion.php" method="post" name="formCotizacion" id="formCotizacion">
    <div class="date">
        <div class="input-group mb-1">
            <span class="input-group-text" id="basic-addon1">Fecha</span>
            <input type="date" id="fecha" name="fecha" class="form-control" required>
        </div>
        <!--
        <div class="input-group mb-1">
            <span class="input-group-text" id="basic-addon1">Cod</span>
            <input type="text" id="numberCot" name="numberCot" class="form-control" value="<?php echo $folio;?>" required>
        </div>
        -->
    </div>
    <?php
    if (isset($_GET['tipo'])) {
        $ti = $_GET['tipo'];
        if ($ti == 'M') {
            $titulo = 'COTIZACIÓN DE MAQUINARIA';
        } elseif ($ti == 'O') {
            $titulo = 'COTIZACIÓN DE OPERADORES';
        } elseif ($ti == 'E') {
            $titulo = 'COTIZACIÓN DE EVALUACIÓN';
        } else {
            $titulo = 'COTIZACIÓN';
        }
    }else {
        $titulo = 'COTIZACIÓN';
    }

    ?>
 <center><h1 id="titulo"><?php echo $titulo; ?></h1></center>
    <br>
    <div class="container">
        <div class="tablaDiv">
                <div class="row">
                    <div class="col">
                        <div class="input-group" style="width: 400px;">
                            <span class="input-group-text" id="basic-addon1">Tipo</span>
                            <select name="tipo" id="tipo" class="form-control">
                                <option value="">SELECCIONAR TIPO</option>
                                <option value="M">INSP. MAQUINARIA</option>
                                <option value="O">ACREDIT. OPER</option>
                                <option value="E">EVALUACIÓN LEY OP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group" style="width: 400px;">
                            <span class="input-group-text" id="basic-addon1">SR.</span>
                            <select name="empresa" id="empresa" class="form-control" required>
                                <option value="">SELECCIONAR EMPRESA</option>
                                <?php 
                                    $cargar = mysqli_query($conn, "SELECT * FROM `empresa` ORDER BY `nombre` ASC");
                                    while ($row = mysqli_fetch_array($cargar)) {
                                        echo '<option value="'.$row['nombre'].'" data-contacto="'.$row['contacto'].'" data-mail="'.$row['correo'].'">'.$row['nombre'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">

                    </div>
                </div>
        </div>
        <br>
        <div class="tablaDiv">
            <div class="row">
                <div class="col">
                    <div class="input-group" style="width: 400px;">
                        <span class="input-group-text" id="basic-addon1">Atte.</span>
                        <input type="text" name="contacto" id="contacto" class="form-control" style="text-transform:capitalize;" oninput="allowLettersOnly(this)" value="<?php echo isset($contacto) ? $contacto : ''; ?>" required>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group" style="width: 400px;">
                        <span class="input-group-text" id="basic-addon1">Correo</span>
                        <input type="text" name="mail" id="mail" class="form-control" style="text-transform:lowercase;" onblur="validateEmail(this)" value="<?php echo isset($mail) ? $mail : ''; ?>" autocomplete="off" required>
                    </div> 
                    <span id="emailError" style="color: red;"></span>              
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group" style="width: 400px;">
                        <span class="input-group-text" id="basic-addon1">Teléfono</span>
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required onfocus="agregarCodigoArea(this)" onblur="if(this.value==='+569')this.value='';" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="12" autocomplete="off">
                    </div>
                </div>
                <div class="col">
                    <div class="input-group" style="width: 400px;">
                        <span class="input-group-text" id="basic-addon1">Faena</span>
                        <input type="text" name="faena" id="faena" class="form-control" placeholder="Nombre de la Faena" autocomplete="off" required>
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
        <br>
        <table id="myTable" border="1" width="100%" class="table table-striped">
            <thead>
                <tr>
                <th>Item</th>
                <th>Und.</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>P. Unitario</th>
                <th>Descuentos</th>
                <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr data-textarea-id="textarea-1">
                <td>1</td>
                <td>
                    CU
                </td>
                <td>
                    <input type="number" name="cantidad[]" class="form-control cantidad" value="0" maxlength="3" style="width: 80px;" required>
                </td>
                <td>
                    <select class="form-control service-select service" name="service[]" style="width: 300px;">
                        <option value="">SELECCIONAR SERVICIO</option>
                        <?php
                        if (isset($_GET['tipo'])) {
                            $tipo = $_GET['tipo'];

                            $cargar = mysqli_query($conn, "SELECT * FROM `servicios` WHERE (tipo='$tipo' OR tipo='T') ORDER BY `nombre` ASC");
                        } else {
                            $cargar = mysqli_query($conn, "SELECT * FROM `servicios` WHERE tipo='T' ORDER BY `nombre` ASC");
                        }

                        while ($row = mysqli_fetch_array($cargar)) {
                            echo '<option value="'.$row['nombre'].'" data-precio="'.number_format($row['valor'], '0', ',', '.') .'">'.$row['nombre'].'</option>';
                        }
                        ?>

                    </select>
                    <input type="hidden" name="tipes" value="<?php echo $tipo;?>">
                </td>
                <td>
                <div class="input-group mb-3">
                    <span class="input-group-text">$</span>
                    <input type="text" name="precio[]" class="form-control service-price-input precio" aria-label="Amount (to the nearest dollar)" value="0" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" readonly required>
                </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="porcentaje[]" class="form-control porcentaje" value="0" max="90" maxlength="2">
                        <span class="input-group-text">%</span>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" name="total[]" class="form-control total" value="0" readonly>
                    </div>
                </td>
                </tr>
            </tbody>
        </table>
        <br>
        <button id="addRowButton" type="button" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>
        <button type="button" class="btn btn-danger btn-delete"><i class="fa fa-minus" aria-hidden="true"></i> Eliminar</button>
        <div id="summaryDiv">
            <div class="row">
                <div class="col">
                    <span>Sub-total: </span>
                </div>
                <div class="col">
                    <span id="subTotal">$0</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span>IVA (19%): </span>
                </div>
                <div class="col">
                    <span id="iva">$0</span>
                </div>
            </div>  
            <div class="row">
                <div class="col">
                    <span>Total: </span>
                </div>
                <div class="col">
                    <span id="totalGeneral">$0</span>
                </div>
            </div>          
        </div>
        <br>
        <div class="detalles" id="detalles">
        <textarea name="detalle[]" id="textarea-1" cols="30" rows="3" class="form-control"></textarea>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <span class="input-group-text">Forma de Pago</span>
                    <select name="formaPago" id="formaPago" class="form-control">
                        <option value="">SELECCIONAR FORMA DE PAGO</option>
                        <option value="PAGO 30 DIAS">PAGO 30 DÍAS</option>
                        <option value="PAGO CONTADO">PAGO CONTADO</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button>
            </div>
        </div>
    </div>
    <div class="loading-overlay" id="loading-overlay">
        <div class="loader"></div>
    </div>
<script>

function agregarCodigoArea(input) {
    if (!input.value.startsWith("+569")) {
    input.value = "+569" + input.value;
    }
}
// Cargar segun select
document.addEventListener("DOMContentLoaded", function() {

  // Obtén una referencia al elemento de entrada de fecha
  var fechaInput = document.getElementById("fecha");

  // Obtén la fecha actual en formato "YYYY-MM-DD"
  var fechaActual = new Date().toISOString().slice(0, 10);
  
  // Asigna la fecha actual como el valor por defecto al campo de entrada
  fechaInput.value = fechaActual;

        var tipoSelect = document.getElementById("tipo");

        tipoSelect.addEventListener("change", function() {
            var tipoSeleccionado = tipoSelect.value;
            if (tipoSeleccionado !== "") {
                window.location.href = "crearCotizacion.php?tipo=" + tipoSeleccionado;
            }
        });
});

// al seleccionar una empresa, se obtiene el contacto y el correo
document.getElementById("empresa").addEventListener("change", function() {
    var selectedOption = this.options[this.selectedIndex];
    var contacto = selectedOption.getAttribute("data-contacto") || "";
    var mail = selectedOption.getAttribute("data-mail") || "";
    document.getElementById("contacto").value = contacto;
    document.getElementById("mail").value = mail;
});

// formato para aldunos input 
function allowLettersOnly(inputElement) {
    inputElement.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });
}

function validateEmail(inputElement) {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const errorElement = document.getElementById('emailError');

    if (!re.test(inputElement.value)) {
        errorElement.textContent = 'Correo electrónico no válido';
    } else {
        errorElement.textContent = ''; 
    }
}

function formatPhone(inputElement) {
    let value = inputElement.value.replace(/\D/g, '');

    if (value.length > 2 && value.length <= 6) {
        value = `(${value.substring(0, 2)})${value.substring(2)}`;
    } else if (value.length > 6) {
        value = `(${value.substring(0, 2)})${value.substring(2, 3)}-${value.substring(3)}`;
    }

    inputElement.value = value;
}

// Eliminar fila
document.querySelector('.btn-delete').addEventListener('click', function() {
    var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
    var rows = table.rows;
    swal({
        title: "Estas seguro?",
        text: "Si eliminas la fila perderas todo el contenido!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            if (rows.length > 1) {
                table.deleteRow(rows.length - 1);
                // Elimina el último textarea dentro del div detalles
                var detallesDiv = document.getElementById('detalles');
                if (detallesDiv.lastChild) {
                    detallesDiv.removeChild(detallesDiv.lastChild);
                }
            }
            updateSummary();
            swal("La fila ha sido eliminado correctamente!", {
            icon: "success",
            });
        } else {
            swal("No se ha elimnado las fila!");
        }
    });
});

// Agregar fila
document.getElementById('addRowButton').addEventListener('click', function() {
    var table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
    var firstRow = table.rows[0];
    var newRow = table.insertRow(table.rows.length);
    // Copia cada celda de la primera fila a la nueva fila
    for (var i = 0; i < firstRow.cells.length; i++) {
        var newCell = newRow.insertCell(i);
        newCell.innerHTML = firstRow.cells[i].innerHTML;
    }
    // Actualiza el número del ítem en la nueva fila
    newRow.cells[0].innerText = table.rows.length;
    // Añadir el nuevo textarea con ID único al div "detalles"
    var detallesDiv = document.getElementById('detalles');
    var newTextArea = document.createElement('textarea');
    newTextArea.setAttribute("rows", "3");
    newTextArea.setAttribute("cols", "30");
    newTextArea.className = "form-control";
    newTextArea.id = 'textarea-' + table.rows.length;
    newTextArea.name = 'detalle[]';
    newRow.setAttribute('data-textarea-id', newTextArea.id);
    detallesDiv.appendChild(newTextArea);
    attachEventListeners(nuevoInput);
});

document.getElementById('myTable').getElementsByTagName('tbody')[0].addEventListener('change', function(event) {
    if (event.target.classList.contains('service-select')) {
        var precio = event.target.options[event.target.selectedIndex].getAttribute('data-precio');
        var priceInput = event.target.closest('tr').querySelector('.precio');
        priceInput.value = precio;
        cargarDatosTextarea(event);
    }
    if (event.target.classList.contains('service') || event.target.classList.contains('service-select')) {
        updateTotal(event);
    }
});

// Actualizar el total cuando se cambia el precio, la cantidad o el porcentaje
document.getElementById('myTable').getElementsByTagName('tbody')[0].addEventListener('input', function(event) {
    if (event.target.classList.contains('cantidad') || event.target.classList.contains('porcentaje') || event.target.classList.contains('precio')) {
        updateTotal(event);
    }
});

function cargarDatosTextarea(event) {
    var selectedService = event.target.value;
    var row = event.target.closest('tr');
    var inputCantidad = row.querySelector('input[name="cantidad[]"]');
    var inputValue = inputCantidad.value;
    var associatedTextareaId = row.getAttribute('data-textarea-id');
    var associatedTextarea = document.getElementById(associatedTextareaId);
    var inputPrecio = row.querySelector('input[name="precio[]"]');
    
    // Verificar si el valor de cantidad es igual a 0
    if (parseInt(inputValue) === 0) {
        swal("Alerta!", "El campo CANTIDAD no puede ser 0!", "info");
        
        // Restablecer el select a la opción por defecto
        event.target.value = "";
        
        // Restablecer el valor del input precio a una cadena vacía
        inputPrecio.value = "0";

        return; // Salir de la función si la cantidad es 0
    }

    associatedTextarea.value = "Se requiere " + inputValue + " " + selectedService + " (TOLVA: CAMION: RODILLO: EXCA: RETRO: MOTONIVELADORA: CARGADOR FRONTAL: MINICARGADOR: GRUA: OTRO:)" + "\n" + "Observaciones:";
}





function parseValue(value) {
    if (typeof value !== 'string') return value;
    return parseFloat(value.replace(/\./g, '').replace(',', '.'));
}

function updateTotal(event) {
    var row = event.target.closest("tr");
    var quantity = parseFloat(row.querySelector(".cantidad").value) || 0;
    var priceValue = row.querySelector(".precio").value;
    var price = parseValue(priceValue) || 0;
    var porcentajeInput = row.querySelector(".porcentaje");
    var discount = parseFloat(porcentajeInput.value) || 0;
    if (discount > 90) {
        discount = 90;
        porcentajeInput.value = 90;
        swal({
            title: "% Superado!",
            text: "El valor no puede ser mayo que el 90%!",
            icon: "warning",
            button: "Aceptar!",
        });
    }
    var total = quantity * price;
    total = total - (total * (discount / 100));
    row.querySelector(".total").value = total.toLocaleString('es-CL', { minimumFractionDigits: 0 });
    updateSummary();
}

function updateSummary() {
    let allTotals = document.querySelectorAll(".total");
    let subTotalValue = 0;
    allTotals.forEach(totalInput => {
        subTotalValue += parseValue(totalInput.value) || 0;
    });
    let ivaValue = subTotalValue * 0.19;
    let totalGeneralValue = subTotalValue + ivaValue;

    document.getElementById("subTotal").textContent = "$ " + parseInt(subTotalValue).toLocaleString('es-CL', { minimumFractionDigits: 0 });
    document.getElementById("iva").textContent = "$ " + parseInt(ivaValue).toLocaleString('es-CL', { minimumFractionDigits: 0 });
    document.getElementById("totalGeneral").textContent = "$ " + parseInt(totalGeneralValue).toLocaleString('es-CL', { minimumFractionDigits: 0 });
}

// Función para convertir un string a float
function parseValue(str) {
    return parseFloat(str.replace(/\./g, ''));
}

function attachEventListeners(inputElement) {
    inputElement.addEventListener('focus', handleFocus);
    inputElement.addEventListener('blur', handleBlur);
}

var table = document.getElementById('myTable');

table.addEventListener('focus', function(event) {
    if (event.target.classList.contains('cantidad') && event.target.value == "0") {
        event.target.value = "";
    }
    if(event.target.classList.contains('porcentaje') && event.target.value == "0") {
        event.target.value = "";
    }
}, true); 

table.addEventListener('blur', function(event) {
    if (event.target.classList.contains('cantidad') && event.target.value.trim() === "") {
        event.target.value = "0";
    }
    if(event.target.classList.contains('porcentaje') && event.target.value.trim() === "") {
        event.target.value = "0";
    }
}, true);

// Función para enviar los datos para ser guardados
document.getElementById("formCotizacion").addEventListener("submit", function(event){
    event.preventDefault();  

    // Mostrar el elemento de carga
    document.getElementById("loading-overlay").style.display = "block";

    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.getElementById("formCotizacion"));
    var selectElement = document.getElementById('formaPago');
    var selectedValue = selectElement.value;
    var cantidadArray = document.querySelectorAll('input[name="cantidad[]"]');
    var serviceArray = document.querySelectorAll('select[name="service[]"]');
    // Obtener el elemento span por su ID
    var spanTotalGeneral = document.getElementById('subTotal');

    // Obtener el contenido dentro del span (que incluye el signo de dólar y el valor)
    var contenidoSpan = spanTotalGeneral.innerHTML;

    // Eliminar el símbolo de dólar y los puntos
    var valorSinDolarNiPuntos = contenidoSpan.replace(/\$/g, '').replace(/\./g, '');

    // Convertir el valor a un número
    var valorNumerico = parseInt(valorSinDolarNiPuntos);

    // Imprimir el valor en la consola (opcional)
    console.log(valorNumerico);



    formData.append('valorNumerico', valorNumerico);

    for (var i = 0; i < cantidadArray.length; i++) {
        if (cantidadArray[i].value.trim() === '0') {
            document.getElementById("loading-overlay").style.display = "none";
            swal("Cantidad!", "Por favor ingresa una cantidad valida", "warning");
            return; 
        }
    }
    for(var i = 0; i < serviceArray.length; i++) {
        if (serviceArray[i].value.trim() === '') {
            document.getElementById("loading-overlay").style.display = "none";
            swal("Servicio!", "Por favor selecciona un servicio", "warning");
            return; 
        }
    }
    if (selectedValue === '') {
        document.getElementById("loading-overlay").style.display = "none";
        swal("Forma de Pago!", "No olvides seleccionar una forma de pago para poder guardar", "warning");
        return; 
    }

    xhr.open("POST", "save_Cotizacion.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Aquí recibimos la respuesta del servidor
            document.getElementById("loading-overlay").style.display = "none";
            var respuesta = JSON.parse(xhr.responseText);
            
            if(respuesta.success){ 
                swal("Bien hecho!", respuesta.message, "success");
                document.getElementById("formCotizacion").reset();
            } else {
                swal("Ups!", respuesta.message, "error");
            }
            console.log(respuesta);
        }
    };
    xhr.send(formData);
});

</script>
</form>
</body>
</html>