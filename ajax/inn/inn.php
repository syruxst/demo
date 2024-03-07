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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Sistema Gestión Calidad ::</title>
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
        .contenedor {
            position: absolute;
            top: 80px; 
            width: 100%;
            height: calc(100% - 80px); 
            display: flex;
            flex-direction: row; 
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
            height: 70px;
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
        }
        #centrado {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        @media (max-width: 666px) {
            .contenedor {
                flex-direction: column;
            }
            #menu {
                max-width: 100%;
                flex-basis: auto;
                height: 100px;
            }
            #menu .bienvenido {
                height: 50px;
            }
            #pantalla {
                flex-grow: 1;
                height: 100%; /* Cambiar la altura a 100% para ocupar todo el espacio disponible */
                overflow-y: auto; /* Agregar un scroll vertical si es necesario */
            }
        }
    </style>
</head>
<body>
<div class="cabecera">
    <img src="https://operamaq.cl/nuevo/img/logoFinal2.png" width="350" alt="">
    <center id="centrado"><h3>Sistema de Gestión de Calidad</h3></center>
</div>
<div class="contenedor">
    <div id="menu">
        <?php if($perfil == 'venta'){?> 
            <div class="bienvenido" id="pruebas">
                <i class="fa fa-file-text" aria-hidden="true"></i> Politica Calidad
            </div>
            <div class="bienvenido" id="nch">
                <i class="fa fa-certificate" aria-hidden="true"></i> NCH-17020
            </div>
            <div class="bienvenido descriptor" id="descriptor">
                <i class="fa fa-file-text" aria-hidden="true"></i> Descriptor Cargo
            </div>  
            <div class="bienvenido" id="casa">
                <i class="fa fa-home" aria-hidden="true"></i> Infraestructura Admin.
            </div> 
            <div class="bienvenido" id="organigrama">
                <i class="fa fa-sitemap" aria-hidden="true"></i> Organigrama
            </div>  
            <div class="bienvenido" id="procedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Procedimientos y Registros No Controlado
            </div> 
            <div class="bienvenido" id="ncprocedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Manual de Calidad
            </div> 
            <div class="bienvenido" id="registros">
                <i class="fa fa-registered" aria-hidden="true"></i> Registros
            </div> 
        <?php } if ($perfil == 'administrador' || $perfil == 'calidad') { ?>
            <div class="bienvenido" id="pruebas">
                <i class="fa fa-file-text" aria-hidden="true"></i> Politica Calidad
            </div>
            <div class="bienvenido" id="nch">
                <i class="fa fa-certificate" aria-hidden="true"></i> NCH-17020
            </div>
            <div class="bienvenido" id="standar">
                <i class="fa fa-list-ol" aria-hidden="true"></i> Estandar Minero
            </div>
            <div class="bienvenido" id="personal">
                <i class="fa fa-folder-open" aria-hidden="true"></i> CV Personal
            </div>
            <div class="bienvenido" id="titulos">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i> Titulos Profesionales
            </div>
            <div class="bienvenido" id="descriptor">
                <i class="fa fa-file-text" aria-hidden="true"></i> Descriptor Cargo
            </div>
            <div class="bienvenido" id="casa">
                <i class="fa fa-home" aria-hidden="true"></i> Infraestructura Admin.
            </div>
            <div class="bienvenido" id="organigrama">
                <i class="fa fa-sitemap" aria-hidden="true"></i> Organigrama
            </div>
            <div class="bienvenido" id="capacitaciones">
                <i class="fa fa-users" aria-hidden="true"></i> Capacitaciones
            </div>
            <div class="bienvenido" id="procedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Procedimientos y Registros No Controlado
            </div>
            <div class="bienvenido" id="ncprocedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Manual de Calidad
            </div>
            <div class="bienvenido" id="registros">
                <i class="fa fa-registered" aria-hidden="true"></i> Registros
            </div>
            <?php } if ($perfil == 'administracion') { ?>
                <div class="bienvenido" id="pruebas">
                    <i class="fa fa-file-text" aria-hidden="true"></i> Politica Calidad
                </div>  
                <div class="bienvenido" id="nch">
                    <i class="fa fa-certificate" aria-hidden="true"></i> NCH-17020
                </div>
                <div class="bienvenido" id="personal">
                    <i class="fa fa-folder-open" aria-hidden="true"></i> CV Personal
                </div>
                <div class="bienvenido" id="titulos">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i> Titulos Profesionales
                </div>
                <div class="bienvenido" id="descriptor">
                    <i class="fa fa-file-text" aria-hidden="true"></i> Descriptor Cargo
                </div>
                <div class="bienvenido" id="casa">
                    <i class="fa fa-home" aria-hidden="true"></i> Infraestructura Admin.
                </div>
                <div class="bienvenido" id="organigrama">
                    <i class="fa fa-sitemap" aria-hidden="true"></i> Organigrama
                </div>
                <div class="bienvenido" id="capacitaciones">
                    <i class="fa fa-users" aria-hidden="true"></i> Capacitaciones
                </div>
                <div class="bienvenido" id="procedimientos">
                    <i class="fa fa-book" aria-hidden="true"></i> Procedimientos y Registros No Controlado
                </div>
                <div class="bienvenido" id="ncprocedimientos">
                    <i class="fa fa-book" aria-hidden="true"></i> Manual de Calidad
                </div>
                <div class="bienvenido" id="registros">
                    <i class="fa fa-registered" aria-hidden="true"></i> Registros
                </div> 
            <?php } if ($perfil == 'coordinador') { ?> 
            <div class="bienvenido" id="pruebas">
                <i class="fa fa-file-text" aria-hidden="true"></i> Politica Calidad
            </div>  
            <div class="bienvenido" id="nch">
                <i class="fa fa-certificate" aria-hidden="true"></i> NCH-17020
            </div>
            <div class="bienvenido" id="standar">
                <i class="fa fa-list-ol" aria-hidden="true"></i> Estandar Minero
            </div>
            <div class="bienvenido" id="personal">
                <i class="fa fa-folder-open" aria-hidden="true"></i> CV Personal
            </div>
            <div class="bienvenido" id="titulos">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i> Titulos Profesionales
            </div>
            <div class="bienvenido" id="descriptor">
                <i class="fa fa-file-text" aria-hidden="true"></i> Descriptor Cargo
            </div>
            <div class="bienvenido" id="casa">
                <i class="fa fa-home" aria-hidden="true"></i> Infraestructura Admin.
            </div>
            <div class="bienvenido" id="organigrama">
                <i class="fa fa-sitemap" aria-hidden="true"></i> Organigrama
            </div>
            <div class="bienvenido" id="capacitaciones">
                <i class="fa fa-users" aria-hidden="true"></i> Capacitaciones
            </div>
            <div class="bienvenido" id="procedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Procedimientos y Registros No Controlado
            </div>
            <div class="bienvenido" id="ncprocedimientos">
                <i class="fa fa-book" aria-hidden="true"></i> Manual de Calidad
            </div>
            <div class="bienvenido" id="registros">
                <i class="fa fa-registered" aria-hidden="true"></i> Registros
            </div>
            <?php } ?>
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
document.addEventListener("DOMContentLoaded", function() {
    var elementos = document.getElementsByClassName("bienvenido");
    var iframe = document.getElementById("iframe");

    // Definir las URLs correspondientes para cada elemento
    var urls = {
        "nch": "nch.php",
        "standar": "estandar.php",
        "personal": "cvPersonal.php",
        "procedimientos": "procedimientos.php",
        "registros": "registros.php",
        "ncprocedimientos": "procedimientosNC.php",
        "organigrama": "organigrama.php",
        "titulos": "titulos.php",
        "casa": "casa.php",
        "descriptor": "descriptor.php",
        "pruebas": "pruebas.php",
        "capacitaciones": "capacitaciones.php"
    };

    // Agregar un evento de clic a cada elemento con la clase "bienvenido"
    for (var i = 0; i < elementos.length; i++) {
        elementos[i].addEventListener("click", function(event) {
            var elementoClic = event.currentTarget;
            var idElementoClic = elementoClic.id;

            // Obtener la URL correspondiente del objeto "urls"
            var url = urls[idElementoClic];

            // Establecer la URL del iframe
            iframe.src = url;
        });
    }
});


function esDispositivoMovil() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

if (esDispositivoMovil()) {
    console.log("Estás viendo desde un dispositivo móvil.");
} else {
    console.log("Estás viendo desde una PC.");
}

</script>
</body>
</html>