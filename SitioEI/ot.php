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
            $celular = $row['telefono'];

            if($celular == ""){
                $dataperfil = "";
                $informacion = "<h3>LLENAS TUS DATOS PARA PODER VER TUS SERVICIOS.</h3>";
            }else{  
                $dataperfil = 'data-perfil="';
                $informacion = "";
            }
    } 


} else {
    header("Location: ../logInsp.php");
    exit();
}
$Pendiente = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE (ip = '$ev' OR ip = '$ip') AND resultado != '' AND doc = 'SI' AND estado = ''");
$numResultados = mysqli_num_rows($Pendiente);

$numEquipos = 0;
$numOperadores = 0;

if ($numResultados > 0) {
    while ($row = mysqli_fetch_assoc($Pendiente)) {
        if ($row['ip'] == $ev) {
            $numOperadores++;
        } elseif ($row['ip'] == $ip) {
            $numEquipos++;
        }
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

        body {
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
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .tabla{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        h1 {
            color: var(--color);
        }

        #pendientes {
            width: 100%; 
            order: 1;
        }
        .resultado{
            width: 100%;
            order: 2;
        }
        .item {
            width: 200px;
            height: 80px;
            float: left;
            margin: 8px;
            cursor: pointer;
            border: 1px solid #e5e5e5;
            transition: box-shadow 0.3s;
            border-radius: 10px;
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .item:hover {
            border: 1px solid var(--color);
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);
        }
        i {
            cursor: pointer;
            transform: scale(1); 
            transition: transform 0.2s; 
        }

        i:hover {
            transform: scale(1.3); 
            color: var(--color);
        }
        .informe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); 
            z-index: 1; 
            display: none;
            backdrop-filter: blur(2px);
            padding: 10px;
        }
        .contenido-informe{
            width: 100%;
            height: 100%;
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            overflow: auto;
        }
        .informeM {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); 
            z-index: 1; 
            display: none;
            backdrop-filter: blur(2px);
            padding: 10px;
        }
        .contenido-informeM{
            width: 100%;
            height: 100%;
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            overflow: auto;
        }
        .close {
            position: absolute;
            top: 5px;
            left: 10px;
            margin: 10px;
        }
        .tabla {
            padding: 10px;
            border-radius: 5px;
        }
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .col {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 3px;
            margin: 5px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
        }
        @media (max-width: 666px) {
            body{
                padding: 0;
            }
            .container {
                width: 100%;
            }
            .item {
                width: 100%;
            }
            .resultado{
                width: 100%;
            }
            h1 .subtitulo {
                position: absolute;
                top: 80px;
                left: 50%; 
                transform: translateX(-50%);
                font-size: 12px;
            }
        }
    </style>
</head>
<body background="white">
    <h1>Orden de Trabajo</h1>
    <div class="container">
        <?php
            $buscarOt = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE (ip = '$ev' OR ip = '$ip') AND resultado != '' AND doc = 'SI' AND estado = ''");

            // Comprueba si la consulta se realizó con éxito
            if ($buscarOt) {

                $numero_resultados = mysqli_num_rows($buscarOt);

                if ($numero_resultados > 0) {
                    $row = mysqli_fetch_array($buscarOt);
                    echo $nombre . ", tienes " . $numero_resultados . " Ordenes de Trabajo pendientes";
                } else {
                    echo $nombre . ", no tienes Ordenes de Trabajo pendientes";
                }
            } else {
                echo "Error en la consulta: " . mysqli_error($conn);
            }
        ?>
        <div id="pendientes">
            <div class="item" <?php echo $dataperfil;?><?php echo $ev; ?>" onclick="cargarDatos(this)">
                OPERADORES &nbsp;&nbsp;
                <?php if ($numOperadores > 0): ?>
                    <span class="badge bg-danger"><?php echo $numOperadores; ?></span>
                <?php endif; ?>
            </div>
            <div class="item" data-perfil="<?php echo $ip; ?>" onclick="cargarDatosM(this)">
                EQUIPOS &nbsp;&nbsp;
                <?php if ($numEquipos > 0): ?>
                    <span class="badge bg-danger"><?php echo $numEquipos; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="resultado"><?php echo $informacion;?></div>

        <div class="informe">
            <button type="button" class="btn btn-primary close" id="cerrarInforme">
                <i class="fa fa-times-circle-o" aria-hidden="true" title="CERRAR INFORME"></i> CERRAR
            </button>

            <div class="contenido-informe">
                <iframe src="" width="100%" id="iframe" height="800px"></iframe>
            </div>
        </div>

        <div class="informeM">
            <button type="button" class="btn btn-primary close" id="cerrarInformeM">
                <i class="fa fa-times-circle-o" aria-hidden="true" title="CERRAR INFORME"></i> CERRAR
            </button>

            <div class="contenido-informeM">
                <iframe src="" width="100%" id="iframeM" height="800px"></iframe>
            </div>
        </div>
    </div>
<script>
/* Variabe Generales*/

var dataPerfilGlobal;

function guardarDataPerfil(dataPerfil) {
    dataPerfilGlobal = dataPerfil;
}

/* Sector de Operadores*/


/** Funcion para cargar los datos al precionar el boton de operadores **/
function cargarDatos(elemento) {
    var dataPerfil = elemento.getAttribute('data-perfil');
    guardarDataPerfil(dataPerfil);

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "apiOtOper.php?dataPerfil=" + dataPerfilGlobal, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            document.querySelector(".resultado").innerHTML = response;

            var informeIcons = document.querySelectorAll('.informe-icon');
            informeIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    var dataId = this.getAttribute('data-id');
                    var informeDiv = document.querySelector('.informe');
                    informeDiv.style.display = 'block';
                    informeDiv.style.width = '100%';

                    cargarPaginaEnInforme(dataId);
                });
            });
        }
    };
    xhr.send();
}


/** Funcion para refrescar datos de Operadores **/

function refrescar() {
    var dataPerfil = dataPerfilGlobal;

    if (dataPerfil) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "apiOtOper.php?dataPerfil=" + dataPerfil, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText;
                document.querySelector(".resultado").innerHTML = response;

                var informeIcons = document.querySelectorAll('.informe-icon');
                informeIcons.forEach(function(icon) {
                    icon.addEventListener('click', function() {
                        var dataId = this.getAttribute('data-id');
                        var informeDiv = document.querySelector('.informe');
                        informeDiv.style.display = 'block';
                        informeDiv.style.width = '100%';

                        // Ahora, carga la página en el div "informe" y envía dataId como parámetro
                        cargarPaginaEnInforme(dataId);
                    });
                });
            }
        };
        xhr.send();
    }
}

// cargar informe desde el lado de operador
function cargarPaginaEnInforme(dataId) {
    var iframe = document.getElementById("iframe");
    iframe.src = 'informe.php?dataId=' + dataId;

    // Escuchar el evento onload del iframe
    iframe.onload = function() {
        // Establecer la altura del iframe según la altura del contenido
        iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
    };
}

// Boton para cerrar el informe de Operador
document.getElementById('cerrarInforme').addEventListener('click', function() {
    var informeDiv = document.querySelector('.informe');
    informeDiv.style.display = 'none';
    console.log(dataPerfilGlobal);
    refrescar();
});

/* Sector de Maquinaria */

// funcion para cargar maquinaria
function cargarDatosM(elemento) {
    var dataPerfil = elemento.getAttribute('data-perfil');
    guardarDataPerfil(dataPerfil);

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "apiOtMaq.php?dataPerfil=" + dataPerfilGlobal, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            document.querySelector(".resultado").innerHTML = response;

            var informeIcons = document.querySelectorAll('.informe-iconM');
            informeIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    var dataId = this.getAttribute('data-id');
                    var informeDiv = document.querySelector('.informeM');
                    informeDiv.style.display = 'block';
                    informeDiv.style.width = '100%';

                    // Ahora, carga la página en el div "informe" y envía dataId como parámetro
                    cargarPaginaEnInformeM(dataId);
                });
            });
        }
    };
    xhr.send();
}

// refrescar maquinaria
function refrescarM() {
    var dataPerfil = dataPerfilGlobal;

    if (dataPerfil) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "apiOtMaq.php?dataPerfil=" + dataPerfil, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText;
                document.querySelector(".resultado").innerHTML = response;

                var informeIcons = document.querySelectorAll('.informe-iconM');
                informeIcons.forEach(function(icon) {
                    icon.addEventListener('click', function() {
                        var dataId = this.getAttribute('data-id');
                        var informeDiv = document.querySelector('.informeM');
                        informeDiv.style.display = 'block';
                        informeDiv.style.width = '100%';

                        // Ahora, carga la página en el div "informe" y envía dataId como parámetro
                        cargarPaginaEnInformeM(dataId);
                    });
                });
            }
        };
        xhr.send();
    }
}

// cargar informe desde el lado de maquinaria
function cargarPaginaEnInformeM(dataId) {
    var iframe = document.getElementById("iframeM");
    iframe.src = 'informeM.php?dataId=' + dataId;

    // Escuchar el evento onload del iframe
    iframe.onload = function() {
        // Establecer la altura del iframe según la altura del contenido
        iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
    };
}

// Boton para cerrar inform de Maquinarias
document.getElementById('cerrarInformeM').addEventListener('click', function() {
    var informeDiv = document.querySelector('.informeM');
    informeDiv.style.display = 'none';
    console.log(dataPerfilGlobal);
    refrescarM();
});

    // Función para manejar el cambio de orientación
    function handleOrientationChange() {
        if (window.orientation === 90 || window.orientation === -90) {
            // El dispositivo está en modo paisaje
            console.log("Dispositivo en modo paisaje");
        } else {
            // El dispositivo está en modo retrato
            console.log("Dispositivo en modo retrato");
        }
    }

    // Agrega un listener para el evento orientationchange
    window.addEventListener("orientationchange", handleOrientationChange);

    // Llama a la función al cargar la página para verificar la orientación inicial
    handleOrientationChange();
</script>
</body>
</html>