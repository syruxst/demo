<?php
session_start();
require_once('../admin/conex.php');
$usuario = $_GET['nombre'];

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['operador']) || isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
    if (isset($_SESSION['operador'])) {
       $usuario = $_SESSION['operador'];
       $query = "SELECT * FROM operadores WHERE rut = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['nombre']. " " .$row['apellidos'];
    } else {
       $usuario = $_SESSION['usuario'];
    }
} else {
    header("Location: ../ajax/login.php");
    exit();
}
/*Buscar datos de operador*/
$buscar = mysqli_query($conn, "SELECT * FROM operadores WHERE rut = '$usuario'");
while($ver = mysqli_fetch_array($buscar )){
    $id_oper = $ver['Id'];
    $Nombre = $ver['nombre'];
    $Apellidos = $ver['apellidos'];
    $Rut = $ver['rut'];
    $Email = $ver['email'];
    $Telefono = $ver['celular'];
    $Direccion = $ver['direccion'];
    $comunaSeleccionada = $ver['id_ciudad'];
    $regionSeleccionada = $ver['id_region'];
    $Licencia = $ver['licencia'];
    $Familia = $ver['familia'];
    $valores = explode(',', $Familia);
    $familiaFormateada = implode(', ', $valores);
    $cv_ok = $ver['nombre_archivo'];
    $foto = $ver['foto_licencia'];

    if($comunaSeleccionada == '' || $regionSeleccionada == ''){
        $comunaSeleccionada = '<select id="selectComunas" name="comunas" class="form-control" required></select>';
        $regionSeleccionada = '<select id="selectRegiones" name="regiones" class="form-control" required></select>';
    }else{
        $comunaSeleccionada = '<input type="text" class="form-control" id="selectComunas" name="comunas" value="'.$comunaSeleccionada.'" required>';
        $regionSeleccionada = '<input type="text" class="form-control" id="selectRegiones" name="regiones" value="'.$regionSeleccionada.'" required>';
    }

    if($cv_ok != ""){
        $mostrar = '<div class="col" style="text-align: left;"><a href="../uploads_op/'.$ver['nombre_archivo'].'" target="_blank" title="VER CURRICULUM"> <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i> Ver CV </a> <input type="checkbox" name="actualizar_cv" value="1" onclick="habilitarInputFile(this, \'cv\')"> Actualizar currículum</div>';
        $disable = 'disabled';
    }else {
        $mostrar = '<div class="col"></div>';
        $disable = '';
    }
    
    if($foto != ''){
        $mostrarImg = '<div class="col" style="text-align: left;"><a href="../licencias/'.$ver['foto_licencia'].'" target="_blank" title="VER LICENCIA"> <i class="fa fa-file-image-o fa-2x" aria-hidden="true"></i> Ver Licencia </a> <input type="checkbox" name="actualizar_LC" value="1" onclick="habilitarInputFile(this, \'foto\')"> Actualizar Licencia Conductor</div>';
        $disable = 'disabled';
    }else{
        $mostrarImg = '<div class="col"></div>';
        $disable = '';
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
        .lc {
            color: green;
            cursor: pointer;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); /* Fondo transparente */
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .example-image {
            /* Estilo adicional para la imagen de ejemplo */
            max-width: 80%;
            max-height: 80%;
            border: 1px solid #ccc;
            background-color: #fff;
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
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $Apellidos;?>" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="hidden" name="id_oper" id="id_oper" value="<?php echo $id_oper; ?>" />
                <input type="hidden" name="rutUser" id="rutUser" value="<?php echo $Rut;?>" >
                <input type="text" class="form-control" id="rut" name="rut" value="<?php echo $Rut;?>" disabled>
            </div>
            <div class="col">
                <input type="text" class="form-control" id="correo" name="correo" value="<?php echo $Email;?>" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $Telefono;?>" autocomplete="off" placeholder="Teléfono" onkeypress="soloNumeros(event)" onfocus="agregarCodigoArea(this)" onblur="if(this.value==='+569')this.value='';" oninput="validarTelefono(this)" maxlength="12">
            </div>
            <div class="col">
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $Direccion;?>" autocomplete="off" placeholder="Dirección">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php echo $regionSeleccionada; ?>
            </div>
            <div class="col">
                <?php echo $comunaSeleccionada; ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="familia" name="familia" value="<?php echo $familiaFormateada;?>" placeholder="Familia de Equipo" disabled>            
            </div>
            <div class="col">
                <input type="text" class="form-control" id="licencia" name="licencia" value="<?php echo $Licencia;?>" autocomplete="off" placeholder="Formato Licencia A2-A3-A4-A5-B-C-D" maxlength="20" oninput="formatInput(this)">            </div>
            <div class="row">
                <div class="col" style="text-align: left;">
                    <p>Para el CV solo formato PDF</p>
                    <input type="file" id="cv" name="cv" placeholder="Subir CV" class="form-control" accept="application/pdf" title="SOLO SUBIR EN FORMATO PDF" <?php echo $disable; ?>>
                </div>
                <div class="col" style="text-align: left;">
                    <!-- <p>Subir Licencia de Conductor ver Ej. <i class="fa fa-id-card-o lc" aria-hidden="true" title="VER EJEMPLO"></i></p> -->
                    <p>Subir Licencia de Conductor ver Ej. 
                        <i class="fa fa-id-card-o lc" aria-hidden="true" title="VER EJEMPLO" onclick="mostrarEjemplo()"></i>
                    </p>
                    <input type="file" id="foto" name="foto" placeholder="Subir Foto" class="form-control" accept="image/*" title="SOLO SUBIR EN FORMATO JPG, JPEG, PNG" <?php echo $disable; ?>>
                </div>
            </div>
        </div>
        <div class="row">
            <?php echo $mostrar; ?> <?php echo $mostrarImg; ?>
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
<div id="overlay" class="overlay" onclick="ocultarEjemplo()">
    <div id="imagenEjemplo" class="example-image">
        <img src="../img/licenciaEjemplo.png" width="600" alt="Ejemplo de Licencia de Conductor">
    </div>
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

function habilitarInputFile(checkbox, inputId) {
    const inputFile = document.getElementById(inputId);
    inputFile.disabled = !checkbox.checked;
}
// Obtener el elemento checkbox y el input de tipo file por su ID
const checkbox = document.querySelector('input[name="actualizar_cv"]');
const inputFile = document.getElementById('cv');

// Agregar un evento 'click' al checkbox
checkbox.addEventListener('click', function() {
    // Verificar si el checkbox está marcado
    if (checkbox.checked) {
        // Abrir el input de tipo file
        inputFile.click();
    }
});

const checkbox2 = document.querySelector('input[name="actualizar_LC"]');
const inputFile2 = document.getElementById('foto');

// Agregar un evento 'click' al checkbox
checkbox2.addEventListener('click', function() {
    // Verificar si el checkbox está marcado
    if (checkbox2.checked) {
        // Abrir el input de tipo file
        inputFile2.click();
    }
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

function formatInput(input) {
  // Reemplazar letras minúsculas por mayúsculas
  input.value = input.value.toUpperCase();
  // Eliminar caracteres no permitidos
  input.value = input.value.replace(/[^A-E1-5-]/g, '');
  // Reemplazar múltiples guiones seguidos por uno solo
  input.value = input.value.replace(/-+/g, '-');
}

function guardarDatos() {

    // Obtener los valores de los campos
    const nombre = document.getElementById("nombre").value;
    const apellidos = document.getElementById("apellidos").value;
    const rut = document.getElementById("rut").value;
    const correo = document.getElementById("correo").value;
    const telefono = document.getElementById("telefono").value;
    const direccion = document.getElementById("direccion").value;
    const comuna = document.getElementById("selectComunas").value;
    const region = document.getElementById("selectRegiones").value;
    const familia = document.getElementById("familia").value;
    const licencia = document.getElementById("licencia").value;
    const cvInput = document.getElementById("cv");
    const fotoInput = document.getElementById("foto");

    // Verificar si algún campo está vacío
    if (
        nombre === "" ||
        apellidos === "" ||
        rut === "" ||
        correo === "" ||
        telefono === "" ||
        direccion === "" ||
        comuna === "" ||
        region === "" ||
        familia === "" ||
        licencia === "" ||
        (cvInput.disabled === false && cvInput.files.length === 0) ||
        (fotoInput.disabled === false && fotoInput.files.length === 0)
    ) {
        // Mostrar un mensaje de error
        swal("¡Advertencia!", "Tienes campos vacios, debes llenar todos los datos.", "info");
        return;
    }

  // Mostrar el indicador de carga
  document.getElementById("loading-overlay").style.display = "block";

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

function mostrarEjemplo() {
    var overlay = document.getElementById('overlay');
    overlay.style.display = 'flex';
}

function ocultarEjemplo() {
    var overlay = document.getElementById('overlay');
    overlay.style.display = 'none';
}
</script>
</body>
</html>