<?php session_start(); error_reporting(0);
    // Verificar si la variable de sesión para el usuario existe
    if (isset($_SESSION['usuario'])) {
        // Obtener el usuario de la variable de sesión
        $usuario = $_SESSION['usuario'];
    } else {
        // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
        header("Location: ../index.php");
        exit();
    }
    // Conectarse a la base de datos
    require_once('../admin/conex.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            color: #B2BABB;
            background-image: url('https://acreditasys.tech/img/SelloAguaDos.png');
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            justify-content: center;
            align-items: center;
        }
        h1 {
            color: var(--color);
        }
        img {
            border: 2px solid var(--color);
            transition: transform 0.3s ease-in-out;
        }
        img:hover {
            transform: scale(3);
        }
        a {
            text-decoration: none;
            color:rgba(0, 0, 0, 0.8);
        }
        a:hover{
            text-decoration: none;
            color: var(--color);
        }
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

        textarea {
            border: none;
            resize: none;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            border: 1px solid #f3f3f3;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .tabla{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }
        input {
            border: none;
            outline: none; 
            border-bottom: 1px solid #E5E8E9;
            color: #B2BABB;
        }

        input:hover {
            border-bottom: 1px solid var(--color); 
        }
        samp {
            color: var(--color);
            font-size: 18px;
            /*font-weight: bold;*/
        }
        a .abtn{
            color: white;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: #f8f8f8;
            color: #333;
        }

        .custom-file-upload:hover {
            background-color: #e0e0e0;
        }

        #informe {
            display: none;
        }
    </style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btnPdf = document.getElementById('btnPdf');
        if (btnPdf) {
            btnPdf.addEventListener('click', function() {
                enviarDatos();
            });
        } else {
            console.error('El elemento con ID "btnPdf" no se encontró.');
        }

        var btnRch = document.getElementById('btnRch');
        if(btnRch){
            btnRch.addEventListener('click', function(){
                rechazados();
            });
        }else{
            console.error('El elemento con ID "btnRch" no se encontró.');
        }
    });

    function rechazados() {
        var dataValue = document.getElementById('data').value;
        var textObs = document.getElementById('textObs').value;

        if (textObs === '') {
            document.getElementById('textObs').focus();
            swal("Advertencia!", "Recuerda ingresar alguna observación!", "info");
            return;
        }

        var formData = new FormData();
        formData.append('data', dataValue);
        formData.append('textObs', textObs);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_rechazo_informe.php', true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // La solicitud fue exitosa
                console.log('Respuesta:', xhr.responseText);
                var jsonResponse = JSON.parse(xhr.responseText);

                if (jsonResponse.success) {
                    swal("Bien hecho!", jsonResponse.success, "success").then(function() {
                        // Esperar un segundo antes de recargar la página
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // 1000 milisegundos = 1 segundo
                    });
                } else {
                    swal("Algo salió mal!", jsonResponse.error, "error");
                }
            } else {
                // Hubo un error en la solicitud
                swal("Algo salió mal!", "Error en la solicitud al servidor", "error");
            }
        };

        xhr.send(formData);
    }

    function enviarDatos() {
        // Obtener el valor del input de texto
        var dataValue = document.getElementById('data').value;
        var textObs = document.getElementById('textObs').value

        // Obtener el archivo seleccionado
        var informeFile = document.getElementById('informe').files[0];

        // Validar que el campo de archivo no esté vacío
        if (!informeFile) {
            swal("Algo salio mal!", "Por favor seleccione el informe en pdf!", "info");
            return;
        }

        if(textObs === ''){
            swal("Advertencia!", "Recuerda ingresa alguna observación!", "info");
            return;
            document.getElementById('textObs').focus();
        }

        // Crear un objeto FormData y agregar los datos
        var formData = new FormData();
        formData.append('data', dataValue);
        formData.append('informe', informeFile);
        formData.append('textObs', textObs);

        // Realizar la solicitud Ajax
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_infome_final_pdf.php', true);

        // Manejar la respuesta
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // La solicitud fue exitosa
                console.log('Respuesta:', xhr.responseText);
                var jsonResponse = JSON.parse(xhr.responseText);

                if (jsonResponse.success) {
                    swal("Bien hecho!", jsonResponse.success, "success").then(function() {
                        // Esperar un segundo antes de recargar la página
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // 1000 milisegundos = 1 segundo
                    });
                } else {
                    swal("Algo salio mal!", "jsonResponse.success", "error")
                }
            } else {
                // Hubo un error en la solicitud
                swal("Algo salio mal!", "jsonResponse.success", "error")
            }
        };

        // Enviar la solicitud con el objeto FormData
        xhr.send(formData);
    }

    function enviarAccion(accion) {
        var dataValor = document.getElementById('data').value;

        if (accion === 'rechazar') {
            swal({
                title: "¿Estás seguro?",
                text: "Una vez rechazado, no podrás recuperar este documento",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willRechazar) => {
                if (willRechazar) {
                    enviarValor(dataValor, accion);
                } else {
                    swal("Tu documento está seguro.");
                }
            });
        } else {
            enviarValor(dataValor, accion);
        }
    }

    function enviarValor(dataValor, accion) {
        var xhr = new XMLHttpRequest();
        var url = 'cierre_ot_M.php';

        // Muestra el indicador de carga
        var loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.style.display = 'block';

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                // Oculta el indicador de carga cuando la respuesta se recibe
                loadingOverlay.style.display = 'none';

                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            swal({
                                title: "¡Bien hecho!",
                                text: "Operación exitosa: " + response.message,
                                icon: "success",
                                button: "Aceptar",
                            });
                        } else {
                            swal({
                                title: "Algo salió mal",
                                text: "Fallo: " + response.message + "!",
                                icon: "error",
                                button: "Aceptar",
                            });
                        }
                    } catch (e) {
                        console.error('Error al analizar la respuesta JSON: ' + e);
                    }
                } else {
                    console.error('Error en la solicitud. Estado: ' + xhr.status);
                }
            }
        };

        var data = 'dataInforme=' + encodeURIComponent(dataValor) + '&accion=' + encodeURIComponent(accion);

        xhr.send(data);
    }

</script>
</head>
<body>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>

<?php
$data = $_GET['dataInforme'];

$sql = "SELECT * FROM `informesM` WHERE IdOper = '$data'";
$rst = mysqli_query($conn, $sql);
$ver = mysqli_fetch_array($rst);

echo '<center><h1>Informe de Equipos N° '.$ver['folio'].'</h1></center>';

if ($rst) {
    $numRows = mysqli_num_rows($rst);

    if ($numRows > 0) {

        $query = "SELECT * FROM detallle_ot WHERE id = '$data'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $folio = $row['folio'];
        $id_ot = $row['id_ot'];
        $patente = $row['patente'];
        $estadoRechazo = $row['informe'];

        ?>
        <samp>DATOS DEL EQUIPO</samp>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
            <tr>
                <td>
                    PATENTE
                </td>
                <td>
                    <input type="hidden" name="data" id="data" value="<?php echo $data; ?>">
                    <input type="text" name="patente" id="patente" value="<?php echo $patente; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
                <td>
                    MARCA
                </td>
                <td>
                    <input type="text" name="marca" id="marca" value="<?php echo $ver['marca']; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    MODELO
                </td>
                <td>
                    <input type="text" name="modelo" id="modelo" value="<?php echo $ver['modelo']; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
                <td>
                    AÑO
                </td>
                <td>
                    <input type="text" name="year" id="year" value="<?php echo $ver['ano']; ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="4" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    TIPO
                </td>
                <td>
                    <input type="text" name="tipo" id="tipo" value="<?php echo $ver['tipo']; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
                <td>
                    HOROMETRO
                </td>
                <td>
                    <input type="text" name="horometro" id="horometro" value="<?php echo $ver['horometro']; ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    MOTOR
                </td>
                <td>
                    <input type="text" name="motor" id="motor" value="<?php echo $ver['motor']; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
                <td>
                    CODIGO INTERNO
                </td>
                <td>
                    <input type="text" name="codigo" id="codigo" value="<?php echo $ver['codigoInterno']; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" readonly>
                </td>
            </tr>
        </table>
        <hr>
        <samp>EVIDENCIA TERRENO</samp>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
            <tr>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgInforme']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgInforme']; ?>" width="80px" height="80px">
                    </a>
                </td>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgHDS']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgHDS']; ?>" width="80px" height="80px">
                    </a>
                </td>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPlaca']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPlaca']; ?>" width="80px" height="80px">
                    </a>
                </td>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgSello']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgSello']; ?>" width="80px" height="80px">
                    </a>
                </td>
            </tr>
            <tr>
                <td align="center">Informe REG-INT-04</td>
                <td align="center" title="Hoja de Seguridad">HDS REG-INT-02</td>
                <td align="center" title="Informe Rechazo">Informe Rechazo REG-INT-03 </td>
                <td align="center">Sello</td>
            </tr>
        </table>
        <hr>
        <samp>EVIDENCIA DOCUMENTAL</samp>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
            <tr>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgRTecnica']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgRTecnica']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPadron']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPadron']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPCirculacion']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPCirculacion']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgSoap']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgSoap']; ?>" width="80px" height="80px">
                    </a>
                </td>            
            </tr>
            <tr>
                <td align="center">Revision Tecnica</td>
                <td align="center">Padrón</td>
                <td align="center">P. Circulación</td>
                <td align="center">S.O.A.P</td>
            </tr>
        </table>
        <hr>
        <samp>EVIDENCIA DEL EQUIPO</samp>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
            <tr>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgFrente']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgFrente']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgIzquierdo']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgIzquierdo']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgDerecho']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgDerecho']; ?>" width="80px" height="80px">
                    </a>
                </td> 
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgTrasera']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgTrasera']; ?>" width="80px" height="80px">
                    </a>
                </td> 
            </tr>
            <tr>
                <td align="center">Frente</td>
                <td align="center">Izquierdo</td>
                <td align="center">Derecho</td>
                <td align="center">Trasera</td>
            </tr>
        </table>
        <hr>
        <samp>EVIDENCIA DE SEGURIDAD</samp>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
            <tr>
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPEmergencia']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgPEmergencia']; ?>" width="80px" height="80px">
                    </a>
                </td>     
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgCorriente']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgCorriente']; ?>" width="80px" height="80px">
                    </a>
                </td>    
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgExtintor']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgExtintor']; ?>" width="80px" height="80px">
                    </a>
                </td>    
                <td align="center" width="25%">
                    <a href="https://acreditasys.tech/SitioEI/<?php echo $ver['imgOtros']; ?>" target="_blank">
                    <img src="https://acreditasys.tech/SitioEI/<?php echo $ver['imgOtros']; ?>" width="80px" height="80px">
                    </a>
                </td>            
            </tr>
            <tr>
                <td align="center">Parada Emergencia</td>
                <td align="center">Corta Corriente</td>
                <td align="center">Extintor</td>
                <td align="center">Otros</td>
            </tr>        
        </table>
        <hr>
        Observación del Inspector: <?php echo $ver['observaciones'];?> <br><hr>

        <?php 
            if($ver['info_final'] == ''){

                $boton = ($estadoRechazo == 'RECHAZADO') ? '' : '<button type="button" class="btn btn-danger" id="btnRch" title="RECHAZAR EVIDENCIA"><i class="fa fa-times" aria-hidden="true"></i> RECHAZAR EVIDENCIA</button>';
                
                echo '
                    <table width="100%">
                        <tr>
                            <td>
                                <label for="informe" class="custom-file-upload">
                                    <i class="fa fa-upload" aria-hidden="true"></i> Adjuntar Check List PDF
                                </label>
                                <input type="file" name="informe" id="informe" accept="application/pdf">
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" id="btnPdf" title="SUBIR ARCHIVO"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> SUBIR ARCHIVO</button>
                            </td>
                            <td>
                            '.$boton.'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                            Observaciones:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <textarea widht="100%" id="textObs" name="textObs"></textarea>
                            </td>
                        </tr>
                    </table>
                ';
            }else { 
                
                $Boton = ($estadoRechazo == 'RECHAZADO') ? '<button type="button" class="btn btn-danger" title="RECHAZAR INFORME" onclick="enviarAccion(\'rechazar\')"><i class="fa fa-times" aria-hidden="true"></i> RECHAZAR</button>' : '<button type="button" class="btn btn-success"title="APROBAR INFORME" onclick="enviarAccion(\'aprobar\')"><i class="fa fa-check" aria-hidden="true"></i> GUARDAR</button>';

                ?>
                <a class="abtn" href="informesFinalesM/<?php echo $ver['info_final']; ?>" target="_blank"><button type="button" class="btn btn-info"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Check List Terreno </button></a>&nbsp;
                &nbsp;<?php echo $Boton; ?>&nbsp;
            <?php
            }
        ?>
        <?php
    }else{
        echo "No se encontraron resultados."; 
    }
    mysqli_free_result($rst);
}else{
    echo "Error en la consulta: " . mysqli_error($con);
}

mysqli_close($con);

?>
</body>
</html>