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

        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
            overflow: hidden; /* Agrega overflow: hidden para que los divs hijos no se salgan del contenedor */
        }

        h1 {
            color: var(--color);
        }

        #pendientes {
            width: 100%; 
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
            #resultado{
                width: 100%;
            }
        }
    </style>
</head>
<body background="white">
    <img src="https://operamaq.cl/nuevo/img/logo_chilevalora.png" width="200">
    <div class="container">
        <h1>SELECCIONA UNA OPCIÓN</h1>
        <div id="pendientes">
            <div class="item" onclick="cargarPagina('../ajax/administracion/procedimientosNC.php')"><i class="fa fa-book" aria-hidden="true"></i> &nbsp;&nbsp;Manual de Calidad</div>
            <div class="item" onclick="cargarPagina('../ajax/administracion/registros.php')"><i class="fa fa-registered" aria-hidden="true"></i> &nbsp;&nbsp;Registros</div>
        </div>
        <div class="resultado" id="resultado">
            <iframe id="iframe"
                width="100%"
                height="100%"
                frameborder="0"
                src=""
            >
            </iframe>
        </div>
    </div>
    <!--script-->
    <script>
    function cargarPagina(pagina) {
        var iframe = document.getElementById("iframe");
        iframe.src = pagina;
        ajustarContenedor()
    }

    function ajustarContenedor() {
        var resultado = document.getElementById("resultado");
        var iframe = document.getElementById("iframe");
        var contenidoHeight = iframe.contentWindow.document.body.scrollHeight;
        resultado.style.height = Math.max(contenidoHeight, 800) + "px";
    }
    </script>
</body>
</html>