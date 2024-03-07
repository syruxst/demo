<?php
session_start();
error_reporting(0);
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
    $User = $ver['user'];
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

    $validarFecha = mysqli_query($conn, "SELECT * FROM `document` WHERE user = '$User'");
    $RowFecha = mysqli_num_rows($validarFecha);

    if($RowFecha > 0){
        while($verFecha = mysqli_fetch_array($validarFecha )){
            $Fecha = $verFecha['date_out'];

            $NewDate = date("d-m-Y", strtotime($Fecha));
            
            $NuevaFecha = date("d-m-Y", strtotime($NewDate . " +1 day"));
        }
    }else{
        $NuevaFecha = date("d-m-Y");
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
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
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
            padding: 2px;
            border-radius: 3px;
            margin: 2px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
            text-align: left;
            /*border: 1px solid #e5e5e5;*/
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
        .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 15% auto; 
        animation: spin 2s linear infinite; 
        }
        .botones {
            width: 300px;
        }
        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body background="white">
   <h1>Mis Sercicios</h1>
   
<div class="container">
    <div class="tabla">
        <div class="row">
            <div class="col">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">SELECCIONAR</span>
                    <select class="form-control" name="selector" id="selector">
                        <option value="0">---------------</option>
                        <option value="O">CERTIFICACIONES</option>
                        <option value="M">INSPECCIONES</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">INICIO</span>
                    <input type="date" class="form-control" name="inicio" id="inicio" value="<?php echo date("Y-m-d", strtotime($NuevaFecha)); ?>" title="SELECCIONA UNA FECHA DE INICIO DEL PERIODO">
                </div>
            </div>
            <div class="col">
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">FIN</span>
                    <input type="date" class="form-control" name="fin" id="fin" title="SELECCIONA UNA FECHA DE TERMINO DEL PERIODO">
                </div>
            </div>
            <div class="col">
                <button type="button" name="buscar" id="buscar" class="btn btn-primary">BUSCAR</button>
            </div>
        </div>
    </div>
    <div class="resultados"></div>
</div>
<div class="loading-overlay" id="loading-overlay">
  <div class="loader"></div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnBuscar = document.getElementById("buscar");
    const selector = document.getElementById("selector");
    const fechaInicio = document.getElementById("inicio");
    const fechaFin = document.getElementById("fin");
    const resultadosDiv = document.querySelector(".resultados");

    btnBuscar.addEventListener("click", function () {
        const selectTipo = selector.value;
        const fechaInicioValue = fechaInicio.value;
        const fechaFinValue = fechaFin.value;

        if(selectTipo == '0'){
            swal({
                title: "Advertencia!",
                text: "Debes seleccionar un tipo de busqueda!",
                icon: "info",
                button: "Aceptar!",
            });
            return;
            selector.focus();
        }

        if (!fechaInicioValue || !fechaFinValue) {
            swal({
                title: "Advertencia!",
                text: "Las Fechas no pueden estar vacias!",
                icon: "info",
                button: "Aceptar!",
            });
        } else {
            const fechaInicioDate = new Date(fechaInicioValue);
            const fechaFinDate = new Date(fechaFinValue);

            if (fechaFinDate < fechaInicioDate) {
                swal({
                    title: "Advertencia!",
                    text: "La Fecha final no puede ser inferior a la fecha de inicio!",
                    icon: "info",
                    button: "Aceptar!",
                });
            } else {
                document.getElementById("loading-overlay").style.display = "block";
                // Realiza la búsqueda por Ajax
                fetch("buscarServicios.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        tipo: selectTipo,
                        inicio: fechaInicioValue,
                        fin: fechaFinValue
                    }),
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                })
                .then(response => response.text())
                .then(data => {
                    // Muestra los resultados en el elemento con clase 'resultados'
                    resultadosDiv.innerHTML = data;
                    document.getElementById("loading-overlay").style.display = "none";


                        var formulario = document.getElementById('formularioBoleta');
                        formulario.addEventListener('submit', function(e) {
                        e.preventDefault(); // Evita el envío del formulario por defecto

                        // Verificar si la casilla de verificación está marcada
                        if (!document.getElementById('btn-menu').checked) {
                            swal({
                                title: "¡Acepta el valor!",
                                text: "Debes aceptar el valor indicado según el periodo para continuar.",
                                icon: "warning",
                                button: "Aceptar",
                            });
                            return; 
                        }

                        document.getElementById("loading-overlay").style.display = "block";

                        var xhr = new XMLHttpRequest();
                        var formData = new FormData(formulario);

                        xhr.open('POST', 'subirBoleta.php', true);

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    var response = xhr.responseText;

                                    if (response === 'success') {
                                        swal({
                                            title: "Bien hecho!",
                                            text: "La boleta se ha subido correctamente!",
                                            icon: "success",
                                            button: "Aceptar",
                                        });
                                        formulario.reset();
                                        document.getElementById("loading-overlay").style.display = "none";
                                    } else if (response === 'info') {
                                        swal({
                                            title: "Advertencia!",
                                            text: "La Boleta ya existe en la base de datos",
                                            icon: "info",
                                            button: "Aceptar",
                                        });
                                        document.getElementById("loading-overlay").style.display = "none";
                                    } else if (response === 'error') {
                                        swal({
                                            title: "Algo salió mal!",
                                            text: "Hubo un error al enviar los datos!",
                                            icon: "error",
                                            button: "Aceptar",
                                        });
                                        document.getElementById("loading-overlay").style.display = "none";
                                    } else {
                                        swal({
                                            title: "Respuesta inesperada",
                                            text: "Hubo un problema con la respuesta del servidor.",
                                            icon: "error",
                                            button: "Aceptar",
                                        });
                                        document.getElementById("loading-overlay").style.display = "none";
                                    }
                                } else {
                                    swal({
                                            title: "Algo salio mal!",
                                            text: "Error en la solicitud Ajax!",
                                            icon: "error",
                                            button: "Aceptar!",
                                        });
                                    document.getElementById("loading-overlay").style.display = "none";
                                }
                            }
                        };

                        xhr.send(formData);
                    });
                })
                .catch(error => console.error("Error en la solicitud: " + error));
            }
        }
    });
});
function capturarPantalla() {
      // Captura el contenido del elemento
      html2canvas(document.getElementById('elemento-a-capturar')).then(function(canvas) {
        // Convierte el canvas en una imagen base64
        var imgData = canvas.toDataURL('image/png');

        // Crea un enlace de descarga para la imagen
        var a = document.createElement('a');
        a.href = imgData;
        a.download = 'captura_pantalla.png';

        // Simula un clic en el enlace para descargar la imagen
        a.click();
      });
}
</script>
</body>
</html>