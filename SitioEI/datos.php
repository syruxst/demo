<?php
session_start();
error_reporting(1);
$usuario = $_GET['nombre'];

require_once('../admin/conex.php');

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['usuario'])) {
       $usuario = $_SESSION['usuario'];
       $query = "SELECT * FROM insp_eva WHERE user = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['name'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}
/*Buscar datos de operador*/
$buscar = mysqli_query($conn, "SELECT * FROM insp_eva WHERE user = '$usuario'");
while($ver = mysqli_fetch_array($buscar )){
    $id_oper = $ver['id'];
    $Rut = $ver['rut'];
    $Nombre = $ver['name'];
    $Email = $ver['correo'];
    $Telefono = $ver['telefono'];
    $Direccion = $ver['direccion'];
    $comuna = $ver['comuna'];
    $region = $ver['region'];
    $banco = $ver['banco'];
    $tipoCta = $ver['tipocta'];
    $cta = $ver['cta'];

    if($comuna == '' || $region == ''){
        $comuna = '<select id="selectComunas" name="comunas" class="form-control" required></select>';
        $region = '<select id="selectRegiones" name="regiones" class="form-control" required></select>';
    }else{
        $comuna = '<input type="text" class="form-control" id="selectComunas" name="comunas" value="'.$comuna.'" required>';
        $region = '<input type="text" class="form-control" id="selectRegiones" name="regiones" value="'.$region.'" required>';
    }

    if($banco ){

    }
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
        .col a{
            text-decoration: none;
            color: var(--color);
        }
        .col a:hover{
            text-decoration: none;
            color: #03a4d3;
        }
        /* Estilos para la clase "perfil" */
        .perfil {
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto; 
            border: 1px solid var(--color);
        }
        .perfil i {
            color: var(--color);
        }
        #actualizar {
            width: 200px; 
            height: 40px; 
            background-color: var(--color);
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        #actualizar:hover {
            background-color: #03a4d3; 
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
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
<body background="white">
   <h1>Actualiza tus datos</h1>
   
<div class="container">
    <form id="formulario" method="POST" enctype="multipart/form-data">
    <div class="perfil">
        <i class="fa fa-user-o fa-4x" aria-hidden="true"></i>
    </div>
    Hola <?php echo $nombre; ?>, para poder tener acceso a todos las opciones de la plataforma, debes actualizar tus datos.
    <div class="tabla">
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $Nombre;?>" disabled>
            </div>
            <div class="col">
                <input type="hidden" name="id_oper" id="id_oper" value="<?php echo $id_oper; ?>" />
                <input type="text" class="form-control" id="rut" name="rut" value="<?php echo $Rut;?>" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $Telefono;?>" autocomplete="off" placeholder="Teléfono" onkeypress="soloNumeros(event)" onfocus="agregarCodigoArea(this)" onblur="if(this.value==='+569')this.value='';" oninput="validarTelefono(this)" maxlength="12">
            </div>
            <div class="col">
                <input type="text" class="form-control" id="correo" name="correo" value="<?php echo $Email;?>" oninput="validarCorreo(this);">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php echo $region; ?>
            </div>
            <div class="col">
                <?php echo $comuna; ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $Direccion;?>" autocomplete="off" placeholder="Dirección">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <select class="form-control" id="banco" name="banco">
                    <option value="0">Seleccionar Banco</option>
                    <option value="banco-bice" <?php if ($banco == 'banco-bice') echo 'selected'; ?>>Banco Bice</option>
                    <option value="bbva" <?php if ($banco == 'bbva') echo 'selected'; ?>>BBVA</option>
                    <option value="banco-consorcio" <?php if ($banco == 'banco-consorcio') echo 'selected'; ?>>Banco Consorcio</option>
                    <option value="banco-de-chile-edwuards-citi" <?php if ($banco == 'banco-de-chile-edwuards-citi') echo 'selected'; ?>>Banco de Chile - Edwuards Citi</option>
                    <option value="banco-del-desarrollo" <?php if ($banco == 'banco-del-desarrollo') echo 'selected'; ?>>Banco del Desarrollo</option>
                    <option value="banco-estado" <?php if ($banco == 'banco-estado') echo 'selected'; ?>>Banco Estado</option>
                    <option value="banco-falabella" <?php if ($banco == 'banco-falabella') echo 'selected'; ?>>Banco Falabella</option>
                    <option value="banco-internacional" <?php if ($banco == 'banco-internacional') echo 'selected'; ?>>Banco Internacional</option>
                    <option value="banco-itau" <?php if ($banco == 'banco-itau') echo 'selected'; ?>>Banco Itaú</option>
                    <option value="banco-paris" <?php if ($banco == 'banco-paris') echo 'selected'; ?>>Banco Paris</option>
                    <option value="banco-ripley" <?php if ($banco == 'banco-ripley') echo 'selected'; ?>>Banco Ripley</option>
                    <option value="banco-santander-banefe" <?php if ($banco == 'banco-santander-banefe') echo 'selected'; ?>>Banco Santander - Banefe</option>
                    <option value="banco-security" <?php if ($banco == 'banco-security') echo 'selected'; ?>>Banco Security</option>
                    <option value="bci-tbanc-nova" <?php if ($banco == 'bci-tbanc-nova') echo 'selected'; ?>>BCI-TBanc-Nova</option>
                    <option value="coopeuch" <?php if ($banco == 'coopeuch') echo 'selected'; ?>>Coopeuch</option>
                    <option value="copec-pay" <?php if ($banco == 'copec-pay') echo 'selected'; ?>>Copec Pay</option>
                    <option value="corpbanca" <?php if ($banco == 'corpbanca') echo 'selected'; ?>>Corpbanca</option>
                    <option value="hsbc-bank" <?php if ($banco == 'hsbc-bank') echo 'selected'; ?>>HSBC Bank</option>
                    <option value="los-heroes" <?php if ($banco == 'los-heroes') echo 'selected'; ?>>Los Héroes</option>
                    <option value="mercado-pago" <?php if ($banco == 'mercado-pago') echo 'selected'; ?>>Mercado Pago Emisora S.A</option>
                    <option value="scotiabank" <?php if ($banco == 'scotiabank') echo 'selected'; ?>>Scotiabank</option>
                    <option value="tapp-caja-los-andes" <?php if ($banco == 'tapp-caja-los-andes') echo 'selected'; ?>>Tapp Caja Los Andes</option>
                    <option value="tenpo-prepago" <?php if ($banco == 'tenpo-prepago') echo 'selected'; ?>>Tenpo Prepago</option>
                    <option value="transbank" <?php if ($banco == 'transbank') echo 'selected'; ?>>Transbank</option>
                </select>
            </div>
            <div class="col">
                <select class="form-control" id="tipoCta" name="tipoCta">
                    <option value="0">Tipo de Cuenta</option>
                    <option value="corriente" <?php if ($tipoCta == 'corriente') echo 'selected'; ?>>Cta Corriente</option>
                    <option value="vista" <?php if ($tipoCta == 'vista') echo 'selected'; ?>>Cta Vista</option>
                    <option value="rut" <?php if ($tipoCta == 'rut') echo 'selected'; ?>>Cta RUT</option>
                </select>
            </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="cta" name="cta" value="<?php echo $cta; ?>" autocomplete="off" placeholder="N° Cuenta Bancaria" onkeypress="soloNumeros(event)">
            </div>
            <div class="col">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="button" id="actualizar" title="Actualizar Datos" onclick="guardarDatos()">Actualizar</button>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
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

//Cargar comunas
const regionesYcomunas = {
    "Seleccione una región": [],
    "Arica y Parinacota": ["Arica", "Camarones", "Putre", "General Lagos"],
    Tarapacá: ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"],
    Antofagasta: ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"],
    Atacama: ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"],
    Coquimbo: ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paiguano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"],
    Valparaíso: ["Valparaíso", "Casablanca", "Concón", "Juan Fernández", "Puchuncaví", "Quintero", "Viña del Mar", "Isla de Pascua", "Los Andes", "Calle Larga", "Rinconada", "San Esteban", "La Ligua", "Cabildo", "Papudo", "Petorca", "Zapallar", "Quillota", "Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Algarrobo", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Catemu", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Quilpué", "Limache", "Olmué", "Villa Alemana"],
    "Metropolitana de Santiago": ["Santiago", "Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "Colina", "Lampa", "Tiltil", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor"],
    "Libertador General Bernardo O'Higgins": ["Rancagua", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "Las Cabras", "Machalí", "Malloa", "Mostazal", "Olivar", "Peumo", "Pichidegua", "Quinta de Tilcoco", "Rengo", "Requínoa", "San Vicente", "Pichilemu", "La Estrella", "Litueche", "Marchihue", "Navidad", "Paredones", "San Fernando", "Chépica", "Chimbarongo", "Lolol", "Nancagua", "Palmilla", "Peralillo", "Placilla", "Pumanque", "Santa Cruz"],
    Maule: ["Talca", "Constitución", "Curepto", "Empedrado", "Maule", "Pelarco", "Pencahue", "Río Claro", "San Clemente", "San Rafael", "Cauquenes", "Chanco", "Pelluhue", "Curicó", "Hualañé", "Licantén", "Molina", "Rauco", "Romeral", "Sagrada Familia", "Teno", "Vichuquén", "Linares", "Colbún", "Longaví", "Parral", "Retiro", "San Javier", "Villa Alegre", "Yerbas Buenas"],
    Ñuble: ["Chillán", "Bulnes", "Cobquecura", "Coelemu", "Coihueco", "Chillán Viejo", "El Carmen", "Ninhue", "Ñiquén", "Pemuco", "Pinto", "Portezuelo", "Quillón", "Quirihue", "Ránquil", "San Carlos", "San Fabián", "San Ignacio", "San Nicolás", "Treguaco", "Yungay"],
    Biobío: ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío"],
    Araucanía: ["Temuco", "Carahue", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Cholchol", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria"],
    "Los Ríos": ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"], 
    "Los Lagos": ["Ancud", "Calbuco", "Castro", "Chaitén", "Chonchi", "Cochamó", "Curaco de Vélez", "Dalcahue", "Fresia", "Frutillar", "Futaleufú", "Hualaihué", "Llanquihue", "Los Muermos", "Maullín", "Osorno", "Palena", "Puerto Montt", "Puerto Octay", "Puerto Varas", "Puqueldón", "Purranque", "Puyehue", "Queilén", "Quellón", "Quemchi", "Quinchao", "Río Negro", "San Juan de la Costa", "San Pablo"], 
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

// Solo permitir ingresar números en el input de teléfono
function soloNumeros(e) {
    var charCode = e.keyCode ? e.keyCode : e.which;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        e.preventDefault();
    }
}
// Agregar evento de input al input de teléfono
function agregarCodigoArea(input) {
    if (!input.value.startsWith("+569")) {
        input.value = "+569" + input.value;
    }
}

function validarTelefono(input) {
    if (input.value.length !== 12) {
        input.style.border = "2px solid red";
    } else {
        input.style.border = "";
    }
}

function validarCorreo(input) {
    // Expresión regular para verificar el formato de correo electrónico
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (!emailPattern.test(input.value)) {
        input.style.border = "2px solid red";
    } else {
        input.style.border = "";
    }
}

function formatInput(input) {
  // Reemplazar letras minúsculas por mayúsculas
  input.value = input.value.toUpperCase();
  // Eliminar caracteres no permitidos
  input.value = input.value.replace(/[^A-E1-5-]/g, '');
  // Reemplazar múltiples guiones seguidos por uno solo
  input.value = input.value.replace(/-+/g, '-');
}

function guardarDatos() {
  // Mostrar el indicador de carga
  document.getElementById("loading-overlay").style.display = "block";

    // Validar que el campo de teléfono no esté vacío
    var telefonoInput = document.getElementById("telefono");
    var correo = document.getElementById("correo");
    var selectRegiones = document.getElementById("selectRegiones");
    var selectedValue = selectRegiones.value;
    var direccion = document.getElementById("direccion");
    var banco = document.getElementById("banco");
    var valueBanco = banco.value;
    var tipoCta = document.getElementById("tipoCta");
    var valueTipoCta = tipoCta.value;
    var cta = document.getElementById("cta");

    if (telefonoInput.value.trim() === "") {
        // Ocultar el indicador de carga
        document.getElementById("loading-overlay").style.display = "none";

        // Mostrar un mensaje de error
        swal({
            title: "Advertencia",
            text: "El teléfono no puede estar vacío.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            telefonoInput.focus();
        });
        return;
        
    }

    if(correo.value.trim() === ""){
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "El correo no puede esta vacio.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            correo.focus();
        });
        return;
    }

    if (selectedValue === "Seleccione una región") {
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "Debes seleccionar una Región y posteriormente una Comuna.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            selectRegiones.focus();
        });
        return;
    }

    if(direccion.value.trim() === ""){
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "Ingrese una dirección valida.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            direccion.focus();
        });
        return;
    }

    if(valueBanco === "0"){
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "Seleccione un banco.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            banco.focus();
        });
        return;
    }

    if(valueTipoCta === "0"){
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "Seleccione un tipo de cuenta.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            tipoCta.focus();
        });
        return;
    }

    if(cta.value.trim() === ""){
        document.getElementById("loading-overlay").style.display = "none";

        swal({
            title: "Advertencia",
            text: "Ingrese un número de cuenta.",
            icon: "info",
            button: "OK",
        }).then((value) => {
            cta.focus();
        });
        return;
    }

  // Crear un objeto FormData para enviar todos los datos del formulario
  var formData = new FormData(document.getElementById("formulario"));

  // Realizar la solicitud POST utilizando fetch
  fetch("saveData.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    // Ocultar el indicador de carga
    document.getElementById("loading-overlay").style.display = "none";

    // Resto del código para manejar la respuesta del servidor
    console.log(data);
    window.location.reload();
    swal("¡Bien hecho!", "Los datos se han actualizado correctamente.", "success");
  })
  .catch(error => {
    // Ocultar el indicador de carga en caso de error
    document.getElementById("loading-overlay").style.display = "none";

    console.error("Error al enviar los datos:", error);
    swal("¡Algo salió mal!", "Los datos no se han podido actualizar", "error");
  });
}
</script>
</body>
</html>