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
        hr{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
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

        /*para input*/
        .file-upload {
            position: relative;
            width: 60px;
            height: 80px;
        }
        .file-uploadR {
            position: relative;
            width: 60px;
            height: 80px;
        }

        .file-uploadR label {
            background-color: #FF0000;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .file-upload label {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        .file-upload input {
            display: none;
        }

        .file-uploadR input {
            display: none;
        }

        .icon {
            margin-right: 8px;
        }

        #fileName {
            margin-left: 8px;
        }

        .image-preview-container {
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerHds {
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerPlaca {
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerSello{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerRevisionTecnica{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerPadron{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerPCirculacion{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerFrente{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerIzquierdo{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerDerecho{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerTrasera{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerSoap{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerPEmergencia{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerCorriente{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerExtintor{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .image-preview-containerOtros{
            display: none;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }


        #imagePreview {
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewHds {
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewPlaca {
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewSello{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewRevisionTecnica{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewPadron{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewPCirculacion{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewFrente{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewIzquierdo{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewDerecho{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewTrasera{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewSoap{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewPEmergencia{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewCorriente{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewExtintor{
            max-width: 100px;
            max-height: 100px;
        }
        #imagePreviewOtros{
            max-width: 100px;
            max-height: 100px;
        }


        .image-preview-container:hover {
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerHds:hover {
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerPlaca:hover {
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerSello:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerRevisionTecnica:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerPadron:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerPCirculacion:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerFrente:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerIzquierdo:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerDerecho:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerTrasera:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerSoap:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerPEmergencia:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerCorriente:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerExtintor:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }
        .image-preview-containerOtros:hover{
            transform: scale(3);
            z-index: 9999; 
            cursor: pointer;
        }


        .logo{
            position: absolute;
            top: 10px;
            right: 22px;
            width: 180px;
        }

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
<div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo"></div>
<center><h1>Informe Observación en Terreno</h1></center>
    <hr>
    
    <?php
        $id = $_GET['dataId'];
        $query = "SELECT * FROM detallle_ot WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $folio = $row['folio'];
        $id_ot = $row['id_ot'];
        $patente = $row['patente'];

        $Sql = mysqli_query($conn, "SELECT * FROM `ot` WHERE id_ot = '$id_ot'");
        $Sql_row = mysqli_fetch_array($Sql);
        $id_cotiz = $Sql_row['id_cotiz'];

        $Cot = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE id_cotiz = '$id_cotiz' ");
        $Row_Cot = mysqli_fetch_array($Cot);

        /*consulta para la patnte*/
        $Query = mysqli_query($conn, "SELECT * FROM `apKey`");
        $Rows = mysqli_fetch_array($Query);
        $key = $Rows['ApiKey'];
    ?>
    <script>
        function consultarPatente() {
            document.getElementById("loading-overlay").style.display = "block";
            var patente = <?php echo json_encode($patente);?>; // Asegúrate de encerrar la patente entre comillas

            const url = `https://api.boostr.cl/vehicle/${patente}.json`;

            fetch(url, { method: 'GET', headers: { 
                    'X-API-KEY': '<?php echo $key; ?>',
                    'Accept': 'application/json' 
                
            } 
                }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                }).then(data => {
                        // Manejar la respuesta del servidor
                        cargarDatosEnTabla(data);
                        document.getElementById("loading-overlay").style.display = "none";
                }).catch(error => console.error('Error:', error));
        }

        function cargarDatosEnTabla(data) {
            console.log('Ejecutando cargarDatosEnTabla');
            if (data.status === 'success') {
                const vehicleData = data.data;

                var inputMarca = document.getElementById('marca');
                var inputModelo = document.getElementById('modelo');
                var inputYear = document.getElementById('year');
                var inputTipo = document.getElementById('tipo');
                var inputMotor = document.getElementById('motor');

                // Verificar si las propiedades existen en vehicleData antes de asignar valores
                inputMarca.value = vehicleData && vehicleData.make ? vehicleData.make : '';
                inputModelo.value = vehicleData && vehicleData.model ? vehicleData.model : '';
                inputYear.value = vehicleData && vehicleData.year ? vehicleData.year : '';
                inputTipo.value = vehicleData && vehicleData.type ? vehicleData.type : '';
                inputMotor.value = vehicleData && vehicleData.engine ? vehicleData.engine : '';

            } else {
                // Manejar el error si es necesario
                console.error('Error en la consulta');
                swal("Algo Salio Mal!", "No se encontraron datos disponibles!", "info");

                var inputPatente = document.getElementById('patente');

                inputPatente.focus();
            }
        }

        // Llamar automáticamente a la función consultarPatente al cargar la página
        window.onload = consultarPatente;
    </script>
<form  id="formularioInformeM" action="">
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla" id="tablaPatente">
        <!-- Aquí se agregarán dinámicamente las filas y celdas -->
    </table>
        <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
        <tr>
            <td>
                FOLIO
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $folio; ?>
            </td>
            <td>
                <b>EVALUADOR</b>
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $nombre; ?>
            </td>
            <td>
                FECHA 
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo date("d-m-Y", strtotime($row['fecha'])); ?>
            </td>
        </tr>
        <tr>
            <td>
                CLIENTE
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $Row_Cot['name_cliente']; ?>
            </td>
            <td>
                FAENA
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $Row_Cot['faena']; ?>
            </td>
            <td>
               
            </td>
            <td>
                
            </td>
            <td>
            
            </td>        
        </tr>
        <tr>
            <td>
                CONTACTO
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $Row_Cot['contacto'];?>
            </td>
            <td>
                CORREO
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $Row_Cot['correo'];?>
            </td>
            <td>
                CELULAR
            </td>
            <td>
                :
            </td>
            <td>
                <?php echo $Row_Cot['telefono'];?>
            </td>
        </tr>
    </table>
    <hr>
    <samp>DATOS DEL EQUIPO</samp>
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla" id="tablaPatente">
        <tr>
            <td>
                PATENTE
            </td>
            <td>
                <input type="hidden" name="IdOper" value="<?php echo $id; ?>">
                <input type="hidden" name="folio" value="<?php echo $folio; ?>">
                <input type="hidden" name="equipo" value="<?php echo $row['equipo']; ?>">
                <input type="hidden" name="fecha" value="<?php echo $row['fecha']; ?>">
                <input type="hidden" name="evaluador" value="<?php echo $nombre; ?>">
                <input type="text" name="patente" id="patente" value="<?php echo $patente; ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
            </td>
            <td>
                MARCA
            </td>
            <td>
                <input type="text" name="marca" id="marca" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
            </td>
        </tr>
        <tr>
            <td>
                MODELO
            </td>
            <td>
                <input type="text" name="modelo" id="modelo" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
            </td>
            <td>
                AÑO
            </td>
            <td>
                <input type="text" name="year" id="year" value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="4" required>
            </td>
        </tr>
        <tr>
            <td>
                TIPO
            </td>
            <td>
                <input type="text" name="tipo" id="tipo" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
            </td>
            <td>
                HOROMETRO
            </td>
            <td>
                <input type="text" name="horometro" id="horometro" value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" autocomplete="off" required>
            </td>
        </tr>
        <tr>
            <td>
                MOTOR
            </td>
            <td>
                <input type="text" name="motor" id="motor" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
            </td>
            <td>
                CODIGO INTERNO
            </td>
            <td>
                <input type="text" name="codigo" id="codigo" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" autocomplete="off" required>
            </td>
        </tr>
    </table>
    <br>
    <b style="color: red;">ANTES DE COMENZAR DEBE SELECCIONAR SI DESEA APROBAR O RECHAZAR</b>
    <hr>
    <samp>EVIDENCIA TERRENO</samp>
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
        <tr>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="fileInput">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="fileInput" name="fileInput" accept="image/*" onchange="displaySelectedFile(this)">
                </div>

                <div id="imagePreviewContainer" class="image-preview-container">
                    <img id="imagePreview" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="hseguridad">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Hoja de Seguridad"></i>
                    </label>
                    <input type="file" id="hseguridad" name="hseguridad" accept="image/*" onchange="displaySelectedFileHSeguridad(this)">
                </div>

                <div id="imagePreviewContainerHds" class="image-preview-containerHds">
                    <img id="imagePreviewHds" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="rechazoInfo" style="display: none;">
                    <div class="file-uploadR">
                        <label for="placa">
                            <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                        </label>
                        <input type="file" id="placa" name="placa" accept="image/*" onchange="displaySelectedFilePlaca(this)">
                    </div>

                    <div id="imagePreviewContainerPlaca" class="image-preview-containerPlaca">
                        <img id="imagePreviewPlaca" alt="Vista previa de la imagen">
                    </div>
                </div>
            </td>
            <td width="25%" align="center">
                <div class="SelloInfo">
                    <div class="file-upload">
                        <label for="sello">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                        </label>
                        <input type="file" id="sello" name="sello" accept="image/*" onchange="displaySelectedFileSello(this)">
                    </div>

                    <div id="imagePreviewContainerSello" class="image-preview-containerSello">
                        <img id="imagePreviewSello" alt="Vista previa de la imagen">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">Informe REG-INT-04</td>
            <td align="center" title="Hoja de Seguridad">HDS REG-INT-02</td>
            <td align="center" title="Informe Rechazo">Informe Rechazo REG-INT-03 </td>
            <td align="center">Sello</td>
        </tr>
        <tr>
    </table>
    <hr>
    <samp>EVIDENCIA DOCUMENTAL</samp>
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
        <tr>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="revisionTecnica">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="revisionTecnica" name="revisionTecnica" accept="image/*" onchange="displaySelectedFileRevisionTecnica(this)">
                </div>

                <div id="imagePreviewContainerRevisionTecnica" class="image-preview-containerRevisionTecnica">
                    <img id="imagePreviewRevisionTecnica" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="padron">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="padron" name="padron" accept="image/*" onchange="displaySelectedFilePadron(this)">
                </div>

                <div id="imagePreviewContainerPadron" class="image-preview-containerPadron">
                    <img id="imagePreviewPadron" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="PCirculacion">
                        <i class="fa fa-upload fa-2x" aria-hidden="true" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="PCirculacion" name="PCirculacion" accept="image/*" onchange="displaySelectedFilePCirculacion(this)">
                </div>

                <div id="imagePreviewContainerPCirculacion" class="image-preview-containerPCirculacion">
                    <img id="imagePreviewPCirculacion" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Soap">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Soap" name="Soap" accept="image/*" onchange="displaySelectedFileSoap(this)">
                </div>

                <div id="imagePreviewContainerSoap" class="image-preview-containerSoap">
                    <img id="imagePreviewSoap" alt="Vista previa de la imagen">
                </div>
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
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Frente">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Frente" name="Frente" accept="image/*" onchange="displaySelectedFileFrente(this)">
                </div>

                <div id="imagePreviewContainerFrente" class="image-preview-containerFrente">
                    <img id="imagePreviewFrente" alt="Vista previa de la imagen">
                </div>
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Izquierdo">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Izquierdo" name="Izquierdo" accept="image/*" onchange="displaySelectedFileIzquierdo(this)">
                </div>

                <div id="imagePreviewContainerIzquierdo" class="image-preview-containerIzquierdo">
                    <img id="imagePreviewIzquierdo" alt="Vista previa de la imagen">
                </div>            
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Derecho">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Derecho" name="Derecho" accept="image/*" onchange="displaySelectedFileDerecho(this)">
                </div>

                <div id="imagePreviewContainerDerecho" class="image-preview-containerDerecho">
                    <img id="imagePreviewDerecho" alt="Vista previa de la imagen">
                </div>             
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Trasera">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Trasera" name="Trasera" accept="image/*" onchange="displaySelectedFileTrasera(this)">
                </div>

                <div id="imagePreviewContainerTrasera" class="image-preview-containerTrasera">
                    <img id="imagePreviewTrasera" alt="Vista previa de la imagen">
                </div>  
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
    <samp>EVIDENCIA DE SEGURIDAD Ó RECHAZO</samp>
    <table width="100%" border="0" cellspacing="8" cellpadding="6" class="tabla">
        <tr>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="PEmergencia">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="PEmergencia" name="PEmergencia" accept="image/*" onchange="displaySelectedFilePEmergencia(this)">
                </div>

                <div id="imagePreviewContainerPEmergencia" class="image-preview-containerPEmergencia">
                    <img id="imagePreviewPEmergencia" alt="Vista previa de la imagen">
                </div>  
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Corriente">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Corriente" name="Corriente" accept="image/*" onchange="displaySelectedFileCorriente(this)">
                </div>

                <div id="imagePreviewContainerCorriente" class="image-preview-containerCorriente">
                    <img id="imagePreviewCorriente" alt="Vista previa de la imagen">
                </div>              
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Extintor">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Extintor" name="Extintor" accept="image/*" onchange="displaySelectedFileExtintor(this)">
                </div>

                <div id="imagePreviewContainerExtintor" class="image-preview-containerExtintor">
                    <img id="imagePreviewExtintor" alt="Vista previa de la imagen">
                </div>             
            </td>
            <td width="25%" align="center">
                <div class="file-upload">
                    <label for="Otros">
                        <i class="fa fa-upload fa-2x" title="Subir Evidencia Informe"></i>
                    </label>
                    <input type="file" id="Otros" name="Otros" accept="image/*" onchange="displaySelectedFileOtros(this)">
                </div>

                <div id="imagePreviewContainerOtros" class="image-preview-containerOtros">
                    <img id="imagePreviewOtros" alt="Vista previa de la imagen">
                </div>             
            </td>
        </tr>
        <tr>
            <td align="center">A</td>
            <td align="center">B</td>
            <td align="center">C</td>
            <td align="center">D</td>
        </tr>
    </table>
    <hr>
    <table width="100%" border="0">
        <tr>
            <td colspan="2">
                <div class="input-group mb-3">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon1">LUGAR DE INSPECCIÓN</button>
                    <input type="text" class="form-control" name="lugar" id="lugar" placeholder="Ingrese Lugar de Inspección" aria-label="Example text with button addon" aria-describedby="button-addon1">
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%"><button type="submit" name="aprobado" id="aprobado" class="btn btn-success" style="width: 200px;" title="APROBAR INFORME" value="APROBAR">APROBAR</button></td>
            <td width="50%"><button type="submit" name="rechazado" id="rechazado" class="btn btn-danger" style="width: 200px;" title="RECHAZAR INFORME" value="RECHAZAR">RECHAZAR</button></td>
        </tr>
    </table>
    <hr>
</form>
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>
</body>
<script>
    /*Primera tabla de datos*/

    function displaySelectedFile(input) {
        const fileInput = input;
        const file = fileInput.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                var imagePreview = document.getElementById('imagePreview');

                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            };

            reader.readAsDataURL(file);
        }
    }

    function displaySelectedFileHSeguridad(input) {
        const fileInputHds = input;
        const fileHds = fileInputHds.files[0];

        if (fileHds && fileHds.type.startsWith('image/')) {
            const readerHds = new FileReader();

            readerHds.onload = function(e) {
                var imagePreviewContainerHds = document.getElementById('imagePreviewContainerHds');
                var imagePreviewHds = document.getElementById('imagePreviewHds');

                imagePreviewHds.src = e.target.result;
                imagePreviewContainerHds.style.display = 'block';
            };

            readerHds.readAsDataURL(fileHds);
        }
    }

    function displaySelectedFilePlaca(input) {
        const fileInputPlaca = input;
        const filePlaca = fileInputPlaca.files[0];

        if (filePlaca && filePlaca.type.startsWith('image/')) {
            const readerPlaca = new FileReader();

            readerPlaca.onload = function(e) {
                var imagePreviewContainerPlaca = document.getElementById('imagePreviewContainerPlaca');
                var imagePreviewPlaca = document.getElementById('imagePreviewPlaca');

                imagePreviewPlaca.src = e.target.result;
                imagePreviewContainerPlaca.style.display = 'block';
            };

            readerPlaca.readAsDataURL(filePlaca);
        }
    }

    function displaySelectedFileSello(input) {
        const fileInputSello = input;
        const fileSello = fileInputSello.files[0];

        if (fileSello && fileSello.type.startsWith('image/')) {
            const readerSello = new FileReader();

            readerSello.onload = function(e) {
                var imagePreviewContainerSello = document.getElementById('imagePreviewContainerSello');
                var imagePreviewSello = document.getElementById('imagePreviewSello');

                imagePreviewSello.src = e.target.result;
                imagePreviewContainerSello.style.display = 'block';
            };

            readerSello.readAsDataURL(fileSello);
        }
    }

    /* Segunda tabla de datos*/
    function displaySelectedFileRevisionTecnica(input) {
        const fileInputRevisionTecnica = input;
        const fileRevisionTecnica = fileInputRevisionTecnica.files[0];

        if (fileRevisionTecnica && fileRevisionTecnica.type.startsWith('image/')) {
            const readerRevisionTecnica = new FileReader();

            readerRevisionTecnica.onload = function(e) {
                var imagePreviewContainerRevisionTecnica = document.getElementById('imagePreviewContainerRevisionTecnica');
                var imagePreviewRevisionTecnica = document.getElementById('imagePreviewRevisionTecnica');

                imagePreviewRevisionTecnica.src = e.target.result;
                imagePreviewContainerRevisionTecnica.style.display = 'block';
            };

            readerRevisionTecnica.readAsDataURL(fileRevisionTecnica);
        }
    }
    function displaySelectedFilePadron(input) {
        const fileInputPadron = input;
        const filePadron = fileInputPadron.files[0];

        if (filePadron && filePadron.type.startsWith('image/')) {
            const readerPadron = new FileReader();

            readerPadron.onload = function(e) {
                var imagePreviewContainerPadron = document.getElementById('imagePreviewContainerPadron');
                var imagePreviewPadron = document.getElementById('imagePreviewPadron');

                imagePreviewPadron.src = e.target.result;
                imagePreviewContainerPadron.style.display = 'block';
            };

            readerPadron.readAsDataURL(filePadron);
        }
    }
    function displaySelectedFilePCirculacion(input) {
        const fileInputPCirculacion = input;
        const filePCirculacion = fileInputPCirculacion.files[0];

        if (filePCirculacion && filePCirculacion.type.startsWith('image/')) {
            const readerPCirculacion = new FileReader();

            readerPCirculacion.onload = function(e) {
                var imagePreviewContainerPCirculacion = document.getElementById('imagePreviewContainerPCirculacion');
                var imagePreviewPCirculacion = document.getElementById('imagePreviewPCirculacion');

                imagePreviewPCirculacion.src = e.target.result;
                imagePreviewContainerPCirculacion.style.display = 'block';
            };

            readerPCirculacion.readAsDataURL(filePCirculacion);
        }
    }
    function displaySelectedFileSoap(input) {
        const fileInputSoap = input;
        const fileSoap = fileInputSoap.files[0];

        if (fileSoap && fileSoap.type.startsWith('image/')) {
            const readerSoap = new FileReader();

            readerSoap.onload = function(e) {
                var imagePreviewContainerSoap = document.getElementById('imagePreviewContainerSoap');
                var imagePreviewSoap = document.getElementById('imagePreviewSoap');

                imagePreviewSoap.src = e.target.result;
                imagePreviewContainerSoap.style.display = 'block';
            };

            readerSoap.readAsDataURL(fileSoap);
        }
    }

    /* tercera tabla de datos*/
    function displaySelectedFileFrente(input) {
        const fileInputFrente = input;
        const fileFrente = fileInputFrente.files[0];

        if (fileFrente && fileFrente.type.startsWith('image/')) {
            const readerFrente = new FileReader();

            readerFrente.onload = function(e) {
                var imagePreviewContainerFrente = document.getElementById('imagePreviewContainerFrente');
                var imagePreviewFrente = document.getElementById('imagePreviewFrente');

                imagePreviewFrente.src = e.target.result;
                imagePreviewContainerFrente.style.display = 'block';
            };

            readerFrente.readAsDataURL(fileFrente);
        }
    }
    function displaySelectedFileIzquierdo(input) {
        const fileInputIzquierdo = input;
        const fileIzquierdo = fileInputIzquierdo.files[0];

        if (fileIzquierdo && fileIzquierdo.type.startsWith('image/')) {
            const readerIzquierdo = new FileReader();

            readerIzquierdo.onload = function(e) {
                var imagePreviewContainerIzquierdo = document.getElementById('imagePreviewContainerIzquierdo');
                var imagePreviewIzquierdo = document.getElementById('imagePreviewIzquierdo');

                imagePreviewIzquierdo.src = e.target.result;
                imagePreviewContainerIzquierdo.style.display = 'block';
            };

            readerIzquierdo.readAsDataURL(fileIzquierdo);
        }
    }
    function displaySelectedFileDerecho(input) {
        const fileInputDerecho = input;
        const fileDerecho = fileInputDerecho.files[0];

        if (fileDerecho && fileDerecho.type.startsWith('image/')) {
            const readerDerecho = new FileReader();

            readerDerecho.onload = function(e) {
                var imagePreviewContainerDerecho = document.getElementById('imagePreviewContainerDerecho');
                var imagePreviewDerecho = document.getElementById('imagePreviewDerecho');

                imagePreviewDerecho.src = e.target.result;
                imagePreviewContainerDerecho.style.display = 'block';
            };

            readerDerecho.readAsDataURL(fileDerecho);
        }
    }
    function displaySelectedFileTrasera(input) {
        const fileInputTrasera = input;
        const fileTrasera = fileInputTrasera.files[0];

        if (fileTrasera && fileTrasera.type.startsWith('image/')) {
            const readerTrasera = new FileReader();

            readerTrasera.onload = function(e) {
                var imagePreviewContainerTrasera = document.getElementById('imagePreviewContainerTrasera');
                var imagePreviewTrasera = document.getElementById('imagePreviewTrasera');

                imagePreviewTrasera.src = e.target.result;
                imagePreviewContainerTrasera.style.display = 'block';
            };

            readerTrasera.readAsDataURL(fileTrasera);
        }
    }

    /* cuarta tabla de datos*/
    function displaySelectedFilePEmergencia(input) {
        const fileInputPEmergencia = input;
        const filePEmergencia = fileInputPEmergencia.files[0];

        if (filePEmergencia && filePEmergencia.type.startsWith('image/')) {
            const readerPEmergencia = new FileReader();

            readerPEmergencia.onload = function(e) {
                var imagePreviewContainerPEmergencia = document.getElementById('imagePreviewContainerPEmergencia');
                var imagePreviewPEmergencia = document.getElementById('imagePreviewPEmergencia');

                imagePreviewPEmergencia.src = e.target.result;
                imagePreviewContainerPEmergencia.style.display = 'block';
            };

            readerPEmergencia.readAsDataURL(filePEmergencia);
        }
    }
    function displaySelectedFileCorriente(input) {
        const fileInputCorriente = input;
        const fileCorriente = fileInputCorriente.files[0];

        if (fileCorriente && fileCorriente.type.startsWith('image/')) {
            const readerCorriente = new FileReader();

            readerCorriente.onload = function(e) {
                var imagePreviewContainerCorriente = document.getElementById('imagePreviewContainerCorriente');
                var imagePreviewCorriente = document.getElementById('imagePreviewCorriente');

                imagePreviewCorriente.src = e.target.result;
                imagePreviewContainerCorriente.style.display = 'block';
            };

            readerCorriente.readAsDataURL(fileCorriente);
        }
    }
    function displaySelectedFileExtintor(input) {
        const fileInputExtintor = input;
        const fileExtintor = fileInputExtintor.files[0];

        if (fileExtintor && fileExtintor.type.startsWith('image/')) {
            const readerExtintor = new FileReader();

            readerExtintor.onload = function(e) {
                var imagePreviewContainerExtintor = document.getElementById('imagePreviewContainerExtintor');
                var imagePreviewExtintor = document.getElementById('imagePreviewExtintor');

                imagePreviewExtintor.src = e.target.result;
                imagePreviewContainerExtintor.style.display = 'block';
            };

            readerExtintor.readAsDataURL(fileExtintor);
        }
    }
    function displaySelectedFileOtros(input) {
        const fileInputOtros = input;
        const fileOtros = fileInputOtros.files[0];

        if (fileOtros && fileOtros.type.startsWith('image/')) {
            const readerOtros = new FileReader();

            readerOtros.onload = function(e) {
                var imagePreviewContainerOtros = document.getElementById('imagePreviewContainerOtros');
                var imagePreviewOtros = document.getElementById('imagePreviewOtros');

                imagePreviewOtros.src = e.target.result;
                imagePreviewContainerOtros.style.display = 'block';
            };

            readerOtros.readAsDataURL(fileOtros);
        }
    }

// Control para enviar los datos
document.addEventListener('DOMContentLoaded', function() {

    var btnAprobar = document.querySelector('[name="aprobado"]');
    var btnRechazar = document.querySelector('[name="rechazado"]');
    var form = document.getElementById('formularioInformeM');
    var rechazoInfoDiv = document.querySelector('.rechazoInfo');
    var rechazoInfoMostrado = false;
    var SelloInfo = document.querySelector('.SelloInfo');

    btnAprobar.addEventListener('click', handleButtonClick);
    btnRechazar.addEventListener('click', rechazarInforme);



    function rechazarInforme(e){
        e.preventDefault();
        btnRechazar.addEventListener('click', function() {

            if (!rechazoInfoMostrado) {
                swal({
                    title: "¿Estás seguro de que quieres rechazar el informe?",
                    text: "Se mostrará la sección de rechazo si decides continuar.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willReject) => {
                    if (willReject) {
                        swal("Se mostrará la sección de rechazo.", {
                            icon: "success",
                        });
                        rechazoInfoDiv.style.display = 'block';
                        rechazoInfoMostrado = true;
                        SelloInfo.style.display = "none";
                        btnAprobar.style.display = "none";
                    } else {
                        swal("El informe se mantiene intacto.");
                        rechazoInfoDiv.style.display = 'none';
                    }
                });
            }else{

                var horometro = document.getElementById('horometro');
                var horometroValue = horometro.value.trim();
                
                if (horometroValue === '') {
                    swal("Advertencia!", "Por favor, ingrese el Horometro del equipo antes de enviar los datos.", "info");
                    return; 
                    document.getElementById("loading-overlay").style.display = "none";
                    horometro.focus();
                }

                var codigo = document.getElementById('codigo');
                var codigoValue = codigo.value.trim();

                if (codigoValue === '') {
                    swal("Advertencia!", "Por favor, ingrese el CODIGO INTERNO  del equipo antes de enviar los datos.", "info");
                    return; 
                    document.getElementById("loading-overlay").style.display = "none";
                    codigo.focus();
                }

                if (!validateFileInputsRechazado()) {
                    return; 
                }

                var lugarInput = document.getElementById('lugar');
                var lugarValue = lugarInput.value.trim();

                if (lugarValue === '') {
                    swal("Advertencia!", "Por favor, ingrese el lugar de inspección antes de enviar los datos.", "info");
                    return; 
                    document.getElementById("loading-overlay").style.display = "none";
                    lugarInput.focus();
                }

                document.getElementById("loading-overlay").style.display = "block";

                var buttonId = event.target.id;
                var action;

                if (buttonId === 'aprobado') {
                    action = 'APROBADO';
                    console.log(action);
                } else if (buttonId === 'rechazado') {
                    action = 'RECHAZADO';
                    console.log(action);
                }

                // Crear un nuevo FormData para almacenar los datos del formulario
                var formData = new FormData(form);
                formData.append('action', action);

                // Crear un nuevo objeto XMLHttpRequest
                var xhr = new XMLHttpRequest();

                // Especificar el método, la URL y configurar asíncrono en true
                xhr.open('POST', 'save_informeM.php', true);

                // Configurar los controladores de eventos onload y onerror
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        // La solicitud fue exitosa
                        console.log(xhr.responseText);
                        // Puedes manejar la respuesta aquí
                        if(xhr.responseText === 'info'){
                            swal("Advertencia!", "El informe ya existe!", "info");
                        }else if(xhr.responseText === 'success'){
                            swal("Bien hecho!", "Los datos han sido guardado correctamente!", "success");
                        }else if(xhr.responseText === 'error'){
                            swal("Algo salio mal!", "No se ha podido guardar los datos!", "error");
                        }
                        document.getElementById("loading-overlay").style.display = "none";
                    } else {
                        // La solicitud falló
                        console.error('Error:', xhr.statusText);
                        document.getElementById("loading-overlay").style.display = "none";
                    }
                };

                xhr.onerror = function() {
                    console.error('Error de red');
                    document.getElementById("loading-overlay").style.display = "none";
                };

                // Enviar el objeto FormData como cuerpo de la solicitud
                xhr.send(formData);
            }
        });
    }

    // Función para manejar el clic en los botones
    function handleButtonClick(event) {
        event.preventDefault(); 

        var horometro = document.getElementById('horometro');
        var horometroValue = horometro.value.trim();
        
        if (horometroValue === '') {
            swal("Advertencia!", "Por favor, ingrese el Horometro del equipo antes de enviar los datos.", "info");
            return; 
            document.getElementById("loading-overlay").style.display = "none";
            horometro.focus();
        }

        var codigo = document.getElementById('codigo');
        var codigoValue = codigo.value.trim();

        if (codigoValue === '') {
            swal("Advertencia!", "Por favor, ingrese el CODIGO INTERNO  del equipo antes de enviar los datos.", "info");
            return; 
            document.getElementById("loading-overlay").style.display = "none";
            codigo.focus();
        }

        if (!validateFileInputs()) {
            return; 
        }

        var lugarInput = document.getElementById('lugar');
        var lugarValue = lugarInput.value.trim();

        if (lugarValue === '') {
            swal("Advertencia!", "Por favor, ingrese el lugar de inspección antes de enviar los datos.", "info");
            return; 
            document.getElementById("loading-overlay").style.display = "none";
            lugarInput.focus();
        }

        document.getElementById("loading-overlay").style.display = "block";

        var buttonId = event.target.id;
        var action;

        if (buttonId === 'aprobado') {
            action = 'APROBADO';
            console.log(action);
        } else if (buttonId === 'rechazado') {
            action = 'RECHAZADO';
            console.log(action);
        }

        // Crear un nuevo FormData para almacenar los datos del formulario
        var formData = new FormData(form);
        formData.append('action', action);

        // Crear un nuevo objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Especificar el método, la URL y configurar asíncrono en true
        xhr.open('POST', 'save_informeM.php', true);

        // Configurar los controladores de eventos onload y onerror
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // La solicitud fue exitosa
                console.log(xhr.responseText);
                // Puedes manejar la respuesta aquí
                if(xhr.responseText === 'info'){
                    swal("Advertencia!", "El informe ya existe!", "info");
                }else if(xhr.responseText === 'success'){
                    swal("Bien hecho!", "Los datos han sido guardado correctamente!", "success");
                }else if(xhr.responseText === 'error'){
                    swal("Algo salio mal!", "No se ha podido guardar los datos!", "error");
                }
                document.getElementById("loading-overlay").style.display = "none";
            } else {
                // La solicitud falló
                console.error('Error:', xhr.statusText);
                document.getElementById("loading-overlay").style.display = "none";
            }
        };

        xhr.onerror = function() {
            console.error('Error de red');
            document.getElementById("loading-overlay").style.display = "none";
        };

        // Enviar el objeto FormData como cuerpo de la solicitud
        xhr.send(formData);
    }
});

function validateFileInputs() {
    var fileInputs = [
        'fileInput', 'hseguridad', 'sello',
        'revisionTecnica', 'padron', 'PCirculacion', 'Soap',
        'Frente', 'Izquierdo', 'Derecho', 'Trasera',
        'PEmergencia', 'Corriente', 'Extintor', 'Otros'
    ];

    for (var i = 0; i < fileInputs.length; i++) {
        var inputId = fileInputs[i];
        var fileInput = document.getElementById(inputId);

        if (fileInput.files.length === 0) {
            swal("Advertencia!", "Seleccione al menos un archivo para " + inputId, "info");
            return false;
        }
    }

    return true;
}

function validateFileInputsRechazado() {
    var fileInputs = [
        'fileInput', 'hseguridad', 'placa', 
        'revisionTecnica', 'padron', 'PCirculacion', 'Soap',
        'Frente', 'Izquierdo', 'Derecho', 'Trasera',
        'PEmergencia', 'Corriente', 'Extintor', 'Otros'
    ];

    for (var i = 0; i < fileInputs.length; i++) {
        var inputId = fileInputs[i];
        var fileInput = document.getElementById(inputId);

        if (fileInput.files.length === 0) {
            swal("Advertencia!", "Seleccione al menos un archivo para " + inputId, "info");
            return false;
        }
    }

    return true;
}

</script>
</html>