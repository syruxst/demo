<?php
session_start();
error_reporting(0);
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
            $ev = $row['ev'];
            $ip = $row['ip'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}
//$Pendiente = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE (ip = '$ev' OR ip = '$ip') AND resultado = 'APROBADO' AND estado = ''");
//$numResultados = mysqli_num_rows($Pendiente);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <!--icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../../img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../img/icons/ms-icon-144x144.png">
    <title>:: Mi Sitio Evaluadores ::</title>
    <style>
        body{
            background-color: #f5f5f5;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        .cabecera{
            position: absolute;
            background-color: #f5f5f5;
            width: 100%;
            height: 80px;
            padding: 10px;
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            border: 1px solid #e5e5e5;
            z-index: 1000;
        }
        #exit{
            position: absolute;
            top: 20px;
            right: 10px;
            border: 1px solid rgba(4,200,252,0.65);
            border-radius: 40px;
            background-color: #C5F1FC;
            padding: 5px;
            cursor: pointer;
        }
        #exit:hover{
            background-color: #04C9FA;
            border: 1px solid rgba(4,200,252,0.65);
            color: white;
        }
        .contenedor {
            position: relative;
            top: 80px; 
            width: 100%;
            height: calc(100% - 80px); 
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }
        #menu {
            width: 100%;
            background-color: #f2f2f2;
            padding: 1px;
            box-sizing: border-box;
            flex-basis: 220px; /* Puedes mantener esto si es necesario */
        }

        #menu .bienvenido {
            display: flex; /* Usar flexbox */
            align-items: center; /* Alinear verticalmente al centro */
            position: relative;
            width: 100%;
            height: 80px;
            background-color: white;
            border-bottom: 1px solid #E5E8E9;
            cursor: pointer;
            overflow: hidden;
            padding: 20px;
        }

        #menu .bienvenido::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #04C9FA;
            transition: width 0.3s ease-in-out;
        }

        #menu .bienvenido:hover::after {
            width: 100%;
            transition: width 0.3s ease-in-out;
        }
        #menu .bienvenido i {
            margin-right: 10px; /* Espacio entre el ícono y el texto (ajusta según sea necesario) */
        }
        #pantalla {
            flex-grow: 1;
            padding: 10px;
            box-sizing: border-box;
            height: 100vh;
        }

        @media (max-width: 666px) {
            .contenedor {
                flex-direction: column;
            }
            #menu {
                max-width: 100%;
                flex-basis: 100%;
                height: 100px;
            }
            #menu .bienvenido {
                height: 60px;
            }
            #pantalla {
                flex-basis: 100%;
                flex-grow: 1;
                height: 100%;
                overflow-y: auto; /* Agregar un scroll vertical si es necesario */
                border: 2px solid #E5E8E9;
            }
            iframe {
                width: 100%;
                height: 800px;
            }
        }
    </style>
</head>
<body>
<div class="cabecera">
   <?php echo "Bienvenido " . $nombre; ?> 
   <div id="exit" title="cerrar sesión"><i class="fa fa-sign-out" aria-hidden="true"></i> cerrar sesión</div>
</div>
<div class="contenedor">
    <div id="menu">
        <div class="bienvenido" id="perfil">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Mi Perfil
        </div>
        <div class="bienvenido" id="ot">
            <i class="fa fa-file-text" aria-hidden="true"></i> <div id="resultados"></div>
            
            <!--Orden de Trab. &nbsp;--><?php //if($numResultados > 0){ echo " <span class='badge bg-danger'>$numResultados</span>"; } ?>
        </div>
        <div class="bienvenido" id="servicios">
            <i class="fa fa-list-ol" aria-hidden="true"></i> Servicios
        </div>        
        <div class="bienvenido" id="chilevalora">
            <i class="fa fa-check" aria-hidden="true"></i> <img src="https://acreditasys.tech/img/logo_chilevalora.png" width="120">
        </div>   
        <div class="bienvenido" id="inn">
            <i class="fa fa-ils" aria-hidden="true"></i> <img src="https://acreditasys.tech/img/Logo-INN.png" width="120">
        </div>   
        <div class="bienvenido" id="calidad">
            <i class="fa fa-file-text-o" aria-hidden="true"></i> Gestión de Calidad
        </div>
    </div>
    <div id="pantalla">
        <iframe id="iframe"
            width="100%"
            height="100%"
            frameborder="0"
            src="">
         </iframe>
    </div>
</div>
<script>
document.getElementById('exit').addEventListener('click', function(event) {
    event.preventDefault(); 

    swal({
        title: "Estas seguro?",
        text: "Si cierras tu sesión perderas datos sin guardar!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = "../api/logout.php";
            console.log("cerrar sesion");
        } else {
            swal("Tu sesión esta a salvo!");
        }
    });
});
document.addEventListener("DOMContentLoaded", function() {
    var perfilDiv = document.getElementById("perfil");
    var otDiv = document.getElementById("ot");
    var iframe = document.getElementById("iframe");
    var chilevalora = document.getElementById("chilevalora");
    var inn = document.getElementById("inn");
    var servicios = document.getElementById("servicios");

    iframe.src = "principal.php";

    var nombre = "<?php echo $nombre; ?>"; // Asegúrate de que $nombre esté definido en PHP
    var urlDatosPhp = "datos.php?nombre=" + encodeURIComponent(nombre);
    var urlot = "ot.php?nombre=" + encodeURIComponent(nombre);
    var urlchilevalora = "chilevalora.php";
    var urlinn = "inn.php";
    var urlServicios = "servicios.php?nombre=" + encodeURIComponent(nombre);

    perfilDiv.addEventListener("click", function() {
        iframe.src = urlDatosPhp;
    });

    otDiv.addEventListener("click", function() {
        iframe.src = urlot;
    });

    chilevalora.addEventListener("click", function(){
        iframe.src = urlchilevalora;
    });

    inn.addEventListener("click", function(){
        iframe.src = urlinn;
    });

    servicios.addEventListener("click", function(){
        iframe.src = urlServicios;
    });
});
function esDispositivoMovil() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (esDispositivoMovil()) {
    // Estás en un dispositivo móvil
    document.body.style.overflow = "visible"; // Mostrar la barra de desplazamiento
    console.log("Estás viendo desde un dispositivo móvil.");
} else {
    // No estás en un dispositivo móvil (PC)
    document.body.style.overflow = "hidden"; // Ocultar la barra de desplazamiento
    console.log("Estás viendo desde una PC.");
}

    function actualizarConsulta() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'consulta.php?ev=<?php echo $ev;?>&ip=<?php echo $ip;?>', true); // Reemplaza 'valor_del_usuario' por el valor apropiado
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.error) {
                    console.log(data.error);
                } else {
                    // Actualiza la cantidad de resultados pendientes
                    document.getElementById('resultados').innerHTML = "Orden de Trab. &nbsp;";
                    if (data.numResultados > 0) {
                        document.getElementById('resultados').innerHTML += "<span class='badge bg-danger'>" + data.numResultados + "</span>";
                    }
                }
            }
        };
        xhr.send();
    }

    // Realiza la consulta inicial
    actualizarConsulta();

    // Actualiza la consulta cada 60 segundos
    setInterval(actualizarConsulta, 10000);
</script>
</body>
</html>