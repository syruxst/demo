<?php require_once('../admin/conex.php'); 
session_start();
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: formulario_inicio_sesion.php");
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
        .tabla{
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
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
          .editEmpresa {
            display: none;
            background-color: rgba(0, 0, 0, 0.63);
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; 
            width: 100%; height: auto; justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
            padding: 50px;
            overflow-y: auto;
        } 
        a{
            text-decoration: none;
            color: black;
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
        <img src="../img/LogoPrincipal.png" alt="" width="200" height="90" title="OPERAMAQ" class="logo">
    </div>
    <center><h1>CREAR EMPRESA</h1></center>
    <hr>
    <div class="container">
        <div class="tablaDiv">
            <div class="row">
                <div class="col">
                    <input type="text" name="empresa" id="empresa" placeholder="Nombre de la empresa" class="form-control" style="text-transform:capitalize;"  oninput="allowLettersOnly(this)" autocomplete="off" required>
                </div>
                <div class="col">
                    <input type="text" name="rut" id="rut" placeholder="R.U.T" class="form-control" style="text-transform:uppercase;" oninput="formatRUT(this)" maxlength="10" autocomplete="off" required>
                </div>
                <div class="col">
                    <input type="text" name="giro" id="giro" placeholder="Giro" class="form-control" style="text-transform:capitalize;" oninput="allowLettersOnly(this)" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección" class="form-control" style="text-transform:capitalize;" autocomplete="off">
                </div>
                <div class="col">
                    <select id="selectRegiones" name="selectRegiones" class="form-control"></select>
                </div>
                <div class="col">
                    <select name="selectComunas" id="selectComunas" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="contacto" id="contacto" placeholder="Contacto" class="form-control" style="text-transform:capitalize;" oninput="allowLettersOnly(this)" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="telefono" id="telefono" placeholder="Teléfono" class="form-control" oninput="formatPhone(this)" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="14" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="correo" id="correo" placeholder="Correo Electronico" class="form-control" onblur="validateEmail(this)" style="text-transform:lowercase;" autocomplete="off">
                    <span id="emailError" style="color: red;"></span>                
                </div>
            </div>
        </div>
        <hr>
        <button class="btn btn-primary" id="btnCrearEmpresa">Crear Empresa</button>
        <hr>
        <input class="form-control" name="buscador" id="buscador" placeholder="Buscar empresa" autocomplete="off">
        <br>
        <table width="100%" border="0" class="tabla table table-striped">
            <tr>
                <th>N°</th>
                <th>NOMBRE</th>
                <th>R.U.T</th>
                <th>CONTACTO</th>
                <th>TELEFONO</th>
                <th>EMAIL</th>
            </tr>
            <?php
                $BuscarEmpresa = mysqli_query($conn, "SELECT * FROM `empresa`");
                $n = 1;
                while($row = mysqli_fetch_array($BuscarEmpresa)){
                    $tel = $row['telefono'];
                    $Telefono = '+'.preg_replace('/[^0-9]/', '', $tel);

                    echo "<tr>";
                    echo "<td>".$n."</td>";
                    echo "<td><a href='#' title='EDITAR EMPRESA' onclick='mostrarDiv(\"" . $row['id'] . "\")'>" . $row['nombre'] . "</a></td>";
                    echo "<td>".$row['rut']."</td>";
                    echo "<td>".ucwords(strtolower($row['contacto']))."</td>";
                    echo '<td><a href="tel:' . $Telefono . '" title="HACER LLAMADA">' . $tel . '</a></td>';
                    echo "<td><a href='mailto: ".$row['correo']."'' title='ENVIAR CORREO'>".$row['correo']."</td>";
                    echo "</tr>";
                    $n++;
                }
            ?>
        </table>
    </div>
    <div id="editEmpresa" class="editEmpresa">
        hola
    <!-- Contenido del div -->
    </div>
<script>
    //Cargar regiones
    const regiones = [
    "Seleccione una región",
    "Arica y Parinacota",
    "Tarapacá",
    "Antofagasta",
    "Atacama",
    "Coquimbo",
    "Valparaíso",
    "Metropolitana de Santiago",
    "Libertador General Bernardo O'Higgins",
    "Maule",
    "Ñuble",
    "Biobío",
    "Araucanía",
    "Los Ríos",
    "Los Lagos",
    "Aysén del General Carlos Ibáñez del Campo",
    "Magallanes y la Antártica"
    ];

    const selectRegiones = document.getElementById("selectRegiones");

    regiones.forEach((region) => {
    const option = document.createElement("option");
    option.text = region;
    option.value = region;
    selectRegiones.add(option);
    });

    const regionesYcomunas = {
    "Seleccione una región": [],
    "Arica y Parinacota": ["Arica", "Camarones", "Putre", "General Lagos"],
    Tarapacá: ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"],
    Antofagasta: ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"],
    Atacama: ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"],
    Coquimbo: ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paiguano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"],
    Valparaíso: ["Valparaíso", "Casablanca", "Concón", "Juan Fernández", "Puchuncaví", "Quintero", "Viña del Mar", "Isla de Pascua", "Los Andes", "Calle Larga", "Rinconada", "San Esteban", "La Ligua", "Cabildo", "Papudo", "Petorca", "Zapallar", "Quillota", "Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Algarrobo", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Catemu", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Quilpué", "Limache", "Olmué"],
    "Metropolitana de Santiago": ["Santiago", "Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "Colina", "Lampa", "Tiltil", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor"],
    "Libertador General Bernardo O'Higgins": ["Rancagua", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "Las Cabras", "Machalí", "Malloa", "Mostazal", "Olivar", "Peumo", "Pichidegua", "Quinta de Tilcoco", "Rengo", "Requínoa", "San Vicente", "Pichilemu", "La Estrella", "Litueche", "Marchihue", "Navidad", "Paredones", "San Fernando", "Chépica", "Chimbarongo", "Lolol", "Nancagua", "Palmilla", "Peralillo", "Placilla", "Pumanque", "Santa Cruz"],
    Maule: ["Talca", "Constitución", "Curepto", "Empedrado", "Maule", "Pelarco", "Pencahue", "Río Claro", "San Clemente", "San Rafael", "Cauquenes", "Chanco", "Pelluhue", "Curicó", "Hualañé", "Licantén", "Molina", "Rauco", "Romeral", "Sagrada Familia", "Teno", "Vichuquén", "Linares", "Colbún", "Longaví", "Parral", "Retiro", "San Javier", "Villa Alegre", "Yerbas Buenas"],
    Ñuble: ["Chillán", "Bulnes", "Cobquecura", "Coelemu", "Coihueco", "Chillán Viejo", "El Carmen", "Ninhue", "Ñiquén", "Pemuco", "Pinto", "Portezuelo", "Quillón", "Quirihue", "Ránquil", "San Carlos", "San Fabián", "San Ignacio", "San Nicolás", "Treguaco", "Yungay"],
    Biobío: ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío"],
    Araucanía: ["Temuco", "Carahue", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Cholchol", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria"],
    "Los Ríos": ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"], "Los Lagos": ["Ancud", "Calbuco", "Castro", "Chaitén", "Chonchi", "Cochamó", "Curaco de Vélez", "Dalcahue", "Fresia", "Frutillar", "Futaleufú", "Hualaihué", "Llanquihue", "Los Muermos", "Maullín", "Osorno", "Palena", "Puerto Montt", "Puerto Octay", "Puerto Varas", "Puqueldón", "Purranque", "Puyehue", "Queilén", "Quellón", "Quemchi", "Quinchao", "Río Negro", "San Juan de la Costa", "San Pablo"], 
    "Aysén del General Carlos Ibáñez del Campo": ["Coihaique", "Lago Verde", "Aisén", "Cisnes", "Guaitecas", "Cochrane", "O'Higgins", "Tortel", "Chile Chico", "Río Ibáñez"],
    "Magallanes y la Antártica": ["Punta Arenas", "Laguna Blanca", "Río Verde", "San Gregorio", "Cabo de Hornos (Ex Navarino)", "Antártica"],
    };

    const regionSelector = document.getElementById("selectRegiones");
    const comunaSelector = document.getElementById("selectComunas");

    regionSelector.addEventListener("change", function() {
    const regionSeleccionada = regionSelector.value;
    const comunas = regionesYcomunas[regionSeleccionada];

    // Limpiamos las opciones anteriores
    comunaSelector.innerHTML = "";

    // Agregamos las opciones de las comunas correspondientes
    comunas.forEach(function(comuna) {
        const opcion = document.createElement("option");
        opcion.value = comuna;
        opcion.text = comuna;
        comunaSelector.add(opcion);
    });
    });

    //condiciones para crear empresa
    function allowLettersOnly(inputElement) {
        inputElement.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^A-Za-zÑñ\s.]/g, '');
        });
    }
    function formatRUT(inputElement) {
    inputElement.value = inputElement.value.replace(/[^0-9kK]+/g, '').replace(/^0+/, '');

        if (inputElement.value.length > 1) {
            const rutBody = inputElement.value.slice(0, -1);
            const verifierDigit = inputElement.value.slice(-1);
            inputElement.value = `${rutBody}-${verifierDigit.toUpperCase()}`;
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
    function validarRUT() {
        const rutInput = $('#rut').val();
        
        // Eliminar espacios en blanco del inicio y final del RUT
        const rut = rutInput.trim();

        if (rut === '') {
            swal("Error!", "Debe ingresar el R.U.T de la empresa!", "error");
            return false;
        }

        // Validar formato del RUT
        const rutRegex = /^(\d{1,3}(?:\.\d{3})*)-(\d|k|K)$/;
        if (!rut.match(rutRegex)) {
            swal("Error!", "El R.U.T ingresado no tiene un formato válido!", "error");
            return false;
        }

        // Si llega aquí, el RUT tiene un formato válido
        return true;
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
    function mostrarDiv(nombre) {
    // Crear una nueva instancia de XMLHttpRequest
    var xhttp = new XMLHttpRequest();

    // Configurar la función que se llama cuando la petición cambia de estado
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualizar el contenido del div con la respuesta
            document.getElementById('editEmpresa').innerHTML = this.responseText;
            document.getElementById('editEmpresa').style.display = 'block';

            // Agregar el escuchador de eventos para el botón de cerrar
            var cerrarBtn = document.getElementById('cerrarBtn');
            if (cerrarBtn) { // Comprueba que el botón exista
                cerrarBtn.addEventListener('click', function() {
                    document.getElementById('editEmpresa').style.display = 'none';
                });
            }

            // Agregar el escuchador de eventos para el botón de guardar cambios
            var guardarCambiosBtn = document.getElementById('guardarCambiosBtn');
            if (guardarCambiosBtn) {
                guardarCambiosBtn.addEventListener('click', function() {
                    // Obtener los datos del formulario que se desea guardar
                    var nombre = document.getElementById("nombreDIV").value;
                    var empresa = document.getElementById("empresaDIV").value;
                    var rut = document.getElementById("rutDIV").value;
                    var giro = document.getElementById("giroDIV").value;
                    var direccion = document.getElementById("direccionDIV").value;
                    var region = document.getElementById("region").value;
                    var comuna = document.getElementById("comuna").value;
                    var contacto = document.getElementById("contactoDIV").value;
                    var telefono = document.getElementById("telefonoDIV").value;
                    var correo = document.getElementById("correoDIV").value;

                    // Crear un objeto con los datos del formulario
                    var formData = new FormData();
                    formData.append('nombre', nombre);
                    formData.append('empresa', empresa);
                    formData.append('rut', rut);
                    formData.append('giro', giro);
                    formData.append('direccion', direccion);
                    formData.append('region', region);
                    formData.append('comuna', comuna);
                    formData.append('contacto', contacto);
                    formData.append('telefono', telefono);
                    formData.append('correo', correo);

                    // Crear una nueva instancia de XMLHttpRequest para enviar los datos
                    var xhrGuardarCambios = new XMLHttpRequest();

                    // Configurar la función que se llama cuando la petición cambia de estado
                    xhrGuardarCambios.onreadystatechange = function() {
                        if (xhrGuardarCambios.readyState == 4) {
                                if (xhrGuardarCambios.status == 200) {
                                    // La petición se completó con éxito
                                    var response = xhrGuardarCambios.responseText;
                                    if (response === 'success') {
                                        console.log("Cambios guardados correctamente.");
                                        swal("Bien hecho!", "Los datos se han guardado correctamente!", "success").then(() => {
                                            location.reload();
                                        });
                                    } else if (response === 'info') {
                                        console.log("No se encontró el registro con el ID proporcionado.");
                                        swal("Ups!", "No se fue necesario hacer cambos!", "info");
                                    } else {
                                        console.log("Ocurrió un error durante la ejecución de la consulta.");
                                        swal("Error!", "Ocurrió un error durante la ejecución de la consulta!", "error");
                                    }
                                } else {
                                    // La petición falló o tuvo un estado no exitoso (por ejemplo, 404, 500, etc.)
                                    console.log("Error en la petición AJAX: " + xhrGuardarCambios.status);
                                }
                         }
                    };

                    // Abrir la petición POST para enviar los datos al servidor
                    xhrGuardarCambios.open("POST", "actualizar_empresa.php", true);

                    // Enviar la petición con los datos del formulario
                    xhrGuardarCambios.send(formData);
                });
            }
        }
    };

    // Abrir la petición POST
    xhttp.open("POST", "result_edith_empresa.php", true);

    // Configurar la cabecera para enviar datos de formulario
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Enviar la petición con el nombre
    xhttp.send("nombre=" + nombre);
}

</script>
<script>
    $(document).ready(function() {
         $('#empresa').focus();
        function buscar(pagina = 1) {
            var valorBusqueda = $("#buscador").val();
            $.ajax({
            url: "buscar_empresa.php",
            type: "POST",
            data: { query: valorBusqueda, pagina: pagina },
            success: function(data) {
                $("table").html(data);
            }
            });
        }

        $("#buscador").on("keyup", function() {
            buscar();
        });

        $(document).on("click", ".pagina", function() {
            var pagina = $(this).data("pagina");
            buscar(pagina);
        });
        $('#btnCrearEmpresa').click(function(e) {
            e.preventDefault(); // Esto previene que el formulario se envíe de la manera tradicional

            if($('#empresa').val() == ''){
                swal("Error!", "Debe ingresar el nombre de la empresa!", "error");
                return false;
            }if($('#rut').val() == ''){
                swal("Error!", "Debe ingresar el R.U.T de la empresa!", "error");
                return false;
            }if($('#giro').val() == ''){
                swal("Error!", "Debe ingresar el giro de la empresa!", "error");
                return false;
            }if($('#direccion').val() == ''){
                swal("Error!", "Debe ingresar la dirección de la empresa!", "error");
                return false;
            }if($('#selectRegiones').val() == 'Seleccione una región'){
                swal("Error!", "Debe seleccionar una región!", "error");
                return false;
            }if($('#selectComunas').val() == ''){
                swal("Error!", "Debe seleccionar una comuna!", "error");
                return false;
            }if($('#contacto').val() == ''){
                swal("Error!", "Debe ingresar el nombre del contacto!", "error");
                return false;
            }if($('#telefono').val() == ''){
                swal("Error!", "Debe ingresar el teléfono del contacto!", "error");
                return false;
            }if($('#correo').val() == ''){
                swal("Error!", "Debe ingresar el correo del contacto!", "error");
                return false;
            }

            var empresa = $('#empresa').val();
            var rut = $('#rut').val();
            var giro = $('#giro').val();
            var direccion = $('#direccion').val();
            var selectRegiones = $('#selectRegiones').val();
            var selectComunas = $('#selectComunas').val();
            var contacto = $('#contacto').val();
            var telefono = $('#telefono').val();
            var correo = $('#correo').val();

            $.ajax({
            url: 'save_empresa.php',
            type: 'POST',
            data: {
                empresa: empresa,
                rut: rut,
                giro: giro,
                direccion: direccion,
                selectRegiones: selectRegiones,
                selectComunas: selectComunas,
                contacto: contacto,
                telefono: telefono,
                correo: correo
            },
            success: function(response) {
                if (response === 'success') {
                    swal("Bien hecho!", "Los datos se han guardado correctamente!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    swal("Hubo un problema!", "Los datos NO se han guardado correctamente!", "info");
                }
            },
            error: function() {
                swal("Error al guardar!", "Los datos NO se han guardado en la base de datos!", "error");
            }
            });
        });
    });
</script>
</body>
</html>