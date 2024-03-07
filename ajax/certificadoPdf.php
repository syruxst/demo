<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        :root {
            --color: #95A5A6;
        }
        a{
            text-decoration: none;
        }
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('https://acreditasys.tech/img/SelloAguaDos.png');
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            color:#797D7F;
        }
        .logo {
            position: absolute;
            top: -20px;
            left: 10px;
            width: 210px;
            height: 80px;
            text-align: center;
        }
        .logo img {
            width: 100%;
        }
        .subtitle {
            position: relative;
            top: 0px;
            right: 0px;
            width: 100%;
            height:50px;
            text-align: right;
            font-size: 12px;
            color: #797D7F;
        }
        hr{
            border: 1px solid #e5e5e5;
        }
        .span-title{
            text-align: center;
            justify-content: center;
        }
        #span-center{
            text-align: center;
            justify-content: center;
            font-size: 11px;
        }
        #span-subtitle {
            text-align: center;
            justify-content: center;
            font-size: 24px;
        }
        p {
            text-align: justify;
        }
        .cuadro {
            /*border: 2px solid black;*/
            width: 80%;
            height: 60px;
            margin: 0 auto;
            text-align: center;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }
        .pers {
            background-color: rgba(95, 116, 122, 0.8); 
            color: white; 
            padding: 10px;
            border: 1px solid white;
        }
        .color {
            background-color: rgba(241, 241, 241, 0.6);
        }
        .pie {
            font-size: 12px;
            color: #797D7F;
        }
        .tableta {
            border-collapse: collapse;
            width: 100%;
        }

        .tableta th, .tableta td {
            border: 1px solid #B2BABB;
        }
        .mi-clase-th {
            max-height: 200px;
            overflow: hidden; /* Para cortar el contenido que sobresale */
        }
        .img_logo{
            position: absolute;
            top: 100px;
            right: 20px;
        }
    </style>
</head>
<body>
<?php
session_start();
require_once('../admin/conex.php');
$id = "";
$id = $_POST['certificado'];
$buscar_qr = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id = '$id'");
$encontrados = "";
$encontrados = mysqli_fetch_array($buscar_qr);
$id_ot = $encontrados['id_ot'];
$certificate  = ($encontrados['certificate'] === "APROBADO") ? "CUMPLE" : "NO CUMPLE";
    /*Datos de empresa*/
    $empresa = mysqli_query($conn, "SELECT * FROM `ot` WHERE id_ot = '$id_ot'");
    $rowEmpresa = mysqli_fetch_array($empresa);
    $Id_cot  = $rowEmpresa['id_cotiz'];
    $tipo = $rowEmpresa['tipo'];

    if($tipo == 'O'){

$qr = $encontrados['qr'];
$nombre = $encontrados['nombre'];
$rut = $encontrados['rut'];
$codigo_eva = $encontrados['ip'];
$user_aprob = $encontrados['user_creacion'];
$porNota = $encontrados['porNota'];
$porcentaje = $encontrados['porcentaje'];
$porcentajeRedondeado = round($porcentaje);

$equipo = $encontrados['equipo'];
$partes = explode('_', $equipo);
$equipoFormateado = implode(' ', $partes);

$modelo = $encontrados['modelo'];

$fechaAprobacion = $encontrados['fecha_arprob'];
$dateOriginal = $encontrados['fecha_arprob'];

$fechaAprobacion = DateTime::createFromFormat('Y-m-d', $fechaAprobacion);
$dateOriginal = DateTime::createFromFormat('Y-m-d', $dateOriginal);

$fechaAprobacion->modify('+1 year');
$nuevaFecha = $fechaAprobacion->format('Y-m-d');

// Establecer el locale a español
setlocale(LC_TIME, 'es_ES.utf8');

// Formatear la fecha en español
$nuevaFechaFormatoDeseado = strftime('%e de %B del %Y', $dateOriginal->getTimestamp());

$resultado_1 = $porNota * 20;
$resultado_2 = $porcentajeRedondeado * 80;
$resultado_3 = ($resultado_1 + $resultado_2)/100;

$resolucion = ($encontrados['estado'] === "APROBADO" && $resultado_3 >= 80) ? "TRABAJADOR ACREDITADO" : "TRABAJADOR NO ACREDITADO";

$condicion = ($resolucion === "TRABAJADOR ACREDITADO") ? "ACREDITAR" : "NO ACREDITAR";

$condicion2 = ($resolucion === "TRABAJADOR ACREDITADO") ? "" : "NO";

/* fechas */

$dateTeorico = $encontrados['date_out'];
$dateTeorico = date("d-m-Y", strtotime($dateTeorico));
$datePractico = $encontrados['fecha'];
$datePractico = date("d-m-Y", strtotime($datePractico));



    $dataEmpresa = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE folio = '$Id_cot'");
    $row_Empresa = mysqli_fetch_array($dataEmpresa);
    $nombreEmpresa = $row_Empresa['name_cliente'];
    $faena = $row_Empresa['faena'];

	//Agregamos la libreria para genera códigos QR
	require "../phpqrcode/qrlib.php";    
	
	//Declaramos una carpeta temporal para guardar la imagenes generadas
	$dir = '../temp/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
        mkdir($dir);
	
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.$id.'.png';

        //Parametros de Condiguración
	
	$tamano = 10; //Tamaño de Pixel
	$level = 'H'; //Precisión Baja
	$framSize = 3; //Tamaño en blanco
	$contenido = "https://acreditasys.tech/validarCertificados.php?hrshs=".$qr; //Texto
	
    //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamano, $framSize);

    // Datos evaluador
    $eva = mysqli_query($conn, "SELECT * FROM `insp_eva` WHERE ip  = '$codigo_eva ' OR ev = '$codigo_eva '");
    $rowEva = mysqli_fetch_array($eva);
    $nombreEvaluador = $rowEva['name'];
    $rutEvaluador = $rowEva['rut'];

    // Datos del Informe
    $informe = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper = '$id'");
    $rowInforme = mysqli_fetch_array($informe);
    $lugarInforme = $rowInforme['lugar'];

    // Datos de usuario
    $user = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$user_aprob'");
    $rowUser = mysqli_fetch_array($user);
    $nombreUser = $rowUser['nombre_usuario'];
    $rutUser = $rowUser['rut'];
?>
    <table width="100%" border="0">
        <tr>
            <td style="height: 55px;">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td colspan="2">
                <div class="subtitle">
                    Certificado de Evaluación de Competencias Laborales.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE COMPETENCIAS LABORALES
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span class="span-title" style="color: #797D7F;">
                    CERTIFICACIONES OPERAMAQ EMPRESA SPA
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span style="justify-content: center; color: #797D7F;" id="span-center">
                    Centro de Evaluación y Certificación de Competencias Laborales<br>
                    Acreditado por ChileValora - Ley N° 20.267<br>
                    Resolución Exenta N° xxx
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span id="span-subtitle" style="color: #424949;">
                    <b>CERTIFICADO DE EVALUACIÓN DE <br> COMPETENCIAS LABORALES</b>
                </span>
                <br>
                PERFIL EVALUADO
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="height: 60px;">
                <span class="span-title" style="color: #424949;">
                    <b>OPERADOR <?php echo $equipoFormateado; ?>, MODELO <?php echo $modelo; ?></b>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="font-size: 16px; color:#797D7F;">
                    Mediante el presente documento, se formaliza que el Sr.(a) <?php echo $nombre; ?>, <?php echo $condicion2; ?> ha superado satisfactoriamente el proceso de Evaluación de Competencias Laborales al que se ha sometido, obteniendo el siguiente resultado general:
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="cuadro" style="color: #424949;">
                    <b>RESOLUCIÓN: <?php echo $resolucion; ?></b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="font-size: 16px; color:#797D7F;">
                    En consecuencia, se ha resuelto 
                    <b><?php echo $condicion; ?></b> las competencias laborales del Sr.(a) <b><?php echo $nombre; ?>, Rut <?php echo $rut; ?></b>, quien ha sido evaluado en el perfil de Operador de <?php echo $equipoFormateado; ?>, Modelo <?php echo $modelo; ?>
                </p>
                <p style="font-size: 16px; color:#797D7F;">
                    El presente certificado se extiende a la empresa <b><?php echo $nombreEmpresa; ?></b>, con una vigencia de un año, a contar de la fecha de emisión del presente documento.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 33%"></td>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/img/Timbre Certificado.png" style="width: 200px;"/></td>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/temp/<?php echo $id;?>.png" style="width: 100px; height: 100px;" /></td>
        </tr>
        <tr style="font-size: 14px; color:#797D7F" align="center">
            <td align="center">Representante Legal </td>
            <td></td>
            <td align="center">Folio: <?php echo $codigo_eva. " ". $encontrados['folio']; ?></td>
        </tr>
        <tr>
            <td style="font-size: 14px; color:#797D7F" align="center">Operamaq Empresa Spa</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>
    <!-- Pagina 2-->
    <table width="100%" border="0"> 
        <tr>
            <td width="33%">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td align="right">
                <div class="subtitle">
                    Certificado de Evaluación de Competencias Laborales.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE COMPETENCIAS LABORALES
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="span-title" style="color: #424949;">
                   <b>INFORME DE EVALUACIÓN DE COMPETENCIAS LABORALES</b> 
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="font-size: 16px; color:#797D7F;">
                    El presente informe, contiene el resumen de los resultados obtenidos por el Sr.(a) <?php echo $nombre; ?>, Rut <?php echo $rut; ?>, en el proceso de Evaluación de Competencias Laborales desarrollado por el Centro OPERAMAQ EMPRESA SPA.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color: #424949;">
                <b>1. ANTECEDENTES GENERALES DE LA EVALUACIÓN</b>
            </td>
        </tr>
        <tr>
            <td rowspan="4" class="pers" width="33%">
                Antecedentes del Candidato
            </td>
            <td class="color" style="color:#797D7F;">&nbsp;Nombre: &nbsp;<?php echo $nombre; ?></td>
        </tr>
        <tr>
            <td class="color" style="color:#797D7F;">&nbsp;RUN: <?php echo $rut; ?></td>
        </tr>
        <tr>
            <td class="color" style="color:#797D7F;"v>
                &nbsp;Empresa: &nbsp;<?php echo $nombreEmpresa; ?>
            </td>
        </tr>
        <tr>
            <td class="color" style="color:#797D7F;">
                &nbsp;Faena: &nbsp;<?php echo $faena; ?>
            </td>
        </tr>
        <tr>
            <td rowspan="2" class="pers">
                Evaluador ChileValora
            </td>
            <td style="color:#797D7F;">
                &nbsp;Nombre: &nbsp;<?php echo $nombreEvaluador; ?>
            </td>
        </tr>
        <tr>
            <td style="color:#797D7F;">&nbsp;RUN: <?php echo $rutEvaluador; ?></td>
        </tr>
        <tr>
            <td rowspan="2" class="pers">
                Fecha de Evaluación
            </td>
            <td class="color">
                &nbsp;Evaluación Teórica: &nbsp;<?php echo $dateTeorico; ?>
            </td>
        </tr>
        <tr>
            <td class="color">
                &nbsp;Evaluación Práctica: <?php echo $datePractico; ?>
            </td>
        </tr>
        <tr>
            <td class="pers" rowspan="2">
                Antecedentes del Equipo
            </td>
            <td>
                &nbsp;Equipo: &nbsp;<?php echo $equipoFormateado; ?>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;Modelo: &nbsp;<?php echo $modelo; ?>
            </td>
        </tr>
        <tr>
            <td class="pers">
                Lugar de Evaluación
            </td>
            <td class="color">
                &nbsp;<?php echo $lugarInforme; ?>
            </td>
        </tr>
        <tr>
            <td class="pers">
                Observaciones del proceso de Evaluación
            </td>
            <td>
                &nbsp;Evaluación <br>
                <ul>
                    <li>Teórico: Modalidad online plataforma Aula Virtual Operamaq Empresa Spa</li>
                    <li>Práctico: modalidad presencial en <?php echo $lugarInforme; ?></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td rowspan="2" class="pers">
                Informe elaborado por:
            </td>
            <td class="color">
                &nbsp;Nombre: <?php echo $nombreUser; ?>
            </td>
        </tr>
        <tr>
            <td class="color">
                &nbsp;RUN: <?php echo $rutUser; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>
    <!-- Pagina 3-->

    <!-- Pagina 4-->
    <table width="100%" border="0"> 
        <tr>
            <td width="33%">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td align="right">
                <div class="subtitle">
                    Certificado de Evaluación de Competencias Laborales.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE COMPETENCIAS LABORALES
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color: #424949;">
                <b>2. INFORME GENERAL DE BRECHAS</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Brechas de Competencias en la Prueba de Conocimientos Teóricos
            </td>
        </tr>
        <tr>
            <td colspan="2" class="color mi-clase-th" style="padding: 10px; font-size: 16px; height: 200px;">
                <div style="position:absolute; max-height:200px; overflow: hidden;">
                    El candidato presenta las siguientes Brechas de Competencias Laborales en la Prueba de Conocimientos Teóricos:
                    <br>
                    <?php
                        $query = "SELECT * FROM examenes WHERE id_oper = ? AND equipo = ? AND date_realizada = ?";
                        $stmt = mysqli_prepare($conn, $query);

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "sss", $rut, $equipo, $encontrados['date_out']);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $p_values = array();
                            $r_values = array();
                        
                            while ($row = mysqli_fetch_assoc($result)) {
                                $resultado = $row['resultado'];
                        
                                // Almacenar las preguntas y respuestas en arrays
                                for ($i = 1; $i <= 20; $i++) {
                                    $p_values[] = $row['p' . $i];
                                    $r_values[] = $row['r' . $i];
                                }
                            }
                        
                            for ($i = 0; $i < 20; $i++) {
                                $num_pregunta = $i + 1;
                                $num_respuesta = $i + 1;
                        
                                // Utilizar una consulta parametrizada para evitar inyecciones SQL
                                $prueba_stmt = mysqli_query($conn, "SELECT * FROM `$equipo` WHERE `id` = '{$p_values[$i]}' ");
                                $prueba = mysqli_fetch_array($prueba_stmt);
                                $pregunta = $prueba['PREGUNTA'];
                                $dato = "R" . $r_values[$i];
                                $correcta = $prueba['id_respuesta_correcta'];
                                $respuesta = $prueba[$dato];
                        
                                if ($r_values[$i] != $correcta) {
                                    $color = "red";
                                    $estado = "INCORRECTA";
                        
                                    echo "<section class='pregunta'>";
                                    echo "{$num_pregunta}.- " . $pregunta . "<br><br>";
                                    echo '</section>';
                                }
                            }
                        }
                    ?>
                </div>    
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Brechas de Competencias en Evaluación Práctica
            </td>
        </tr>
        <tr>
            <td colspan="2" class="color mi-clase-th" style="padding: 10px; font-size: 16px; height: 200px;">
                El candidato presenta las siguientes Brechas de Competencias Laborales en la Evaluación Práctica:
                <br>
                <?php
                    $buscar = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper='$id'");
                    while ($rows = mysqli_fetch_array($buscar)) {
                        $observaciones = $rows['observaciones'];
                        
                        $observaciones = preg_replace('/(\d+\.-)/', PHP_EOL . "$1", $observaciones);
                        
                        echo nl2br($observaciones);
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Oportunidades de Desarrollo de Competencias par alcanzar la Exclencia Operacional Practica
            </td>
        </tr>
        <tr>
            <td colspan="2" class="color mi-clase-th" style="height: 200px; font-size: 14px;">
                <?php

                    $mejora = $encontrados['oport_m'];

                    // Agrega un salto de línea delante de la frase específica
                    $mejora = preg_replace('/PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:/i', '<br>PARA LOGRAR LA EXCELENCIA DEBE MEJORAR SU CONOCIMIENTO DE:', $mejora);

                    // Agrega un salto de línea después de cada punto seguido de espacio
                    $mejora = preg_replace('/\.(?=\s|$)/', ".<br>", $mejora);

                    echo $mejora;
                    
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Para obtener las recomendaciones de capacitación y la asesoria para diseñar e implementar un Plan de Desarrollo Integral, contacte al Centro de Evaluación.
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>

    <!-- Pagina 4B-->

    <table width="100%" border="0">
        <tr>
            <td width="33%">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td align="right">
                <div class="subtitle">
                    Certificado de Evaluación de Competencias Laborales.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE COMPETENCIAS LABORALES
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color: #424949;">
                <b>3. INFORME DE BRECHAS CONDICIONANTES CRÍTICAS</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Brechas Condicionantes Críticas Básicas
            </td>
        </tr>
        <tr>
            <td colspan="2" class="color mi-clase-th" style="font-size: 16px; height: 200px;">
                <ul>
                    <li>
                        El trabajador presenta Brechas Condicionantes Críticas Básicas.
                    </li>
                </ul>
                <b>DEBE MEJORAR SU CONOCIMINETO DE:</b> Ejecutar maniobras de estacionamiento de acuerdo con procedimientos de trabajo.
                <br>
                <b>EN LO REFERENTE A:</b> Estacionar el vehiculo/equipo en un lugar pertinente y/o habilitado, medidas de seguridad
                correspondientes, direccionar ruedas en pendientes y aplicar freno de estacionamiento.
                <br>
                <b>DE ACUERDO CON:</b> Procedimiento de conducción.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="pers">
                Brechas Condicionantes Críticas Específicas del Proyecto o Carrera de Proyectos
            </td>
        </tr>
        <tr>
            <td colspan="2" class="color mi-clase-th" style="font-size: 16px; height:200px;">
                <ul>
                    <li>
                        El trabajador no presenta Brechas Condicionantes Críticas Específicas del Proyecto o Carrera de Proyectos.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 16px;" class="color">
                Para obtener las recomendaciones de lcapacitación pertinentes para realia el 
                <b>Tratamiento de las Brechas Condicionantes Críticas</b> y para posteriormente 
                realizar el proceso de <b>Evaluación para el Cierre de Brechas Condiconantes Críticas,</b> 
                contacte al Centro de Evaluación.
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>DEFINICIONES RELEVANTES</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <ul>
                    <li>
                        <b>Tratamiento de Brechas:</b>
                        Implica Capacitar al trabajador. Esta capacitación debe ser formal y debe estar documentada.
                        Se debe realizar para Todas las Brechas de Competenecia identificadas.
                    </li>
                    <li>
                        <b>Evaluación para el Cierre de Brechas:</b>
                        Implica realizar el tratamiento de la Brecha y además realizar una evaluación espesifica para 
                        Certificar que el trabajador incorporó es conocimiento descendido. Se realiza solo para las 
                        Brechas Críticas.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>
    <!-- Pagina 5-->
    <table width="100%" border="0"> 
        <tr>
            <td width="33%">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td colspan="2" align="right">
                <div class="subtitle">
                    Certificado de Evaluación de Competencias Laborales.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE COMPETENCIAS LABORALES
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="color: #424949;">
                <b>4. CONCLUSIÓN GENERAL</b>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 16px;">
                El candidato <b><?php echo $condicion2; ?> CUMPLE</b> con los requisitos establecidos en la rúbica, en base a los siguientes resultados parciales:
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="tableta">
                    <tr>
                        <td class="pers" align="center">Instrumento</td>
                        <td class="pers" align="center">Nota</td>
                        <td class="pers" align="center">Requisito</td>
                    </tr>
                    <tr>
                        <td class="color"> Evaluación Teórica:</td>
                        <td align="center" class="color"><?php echo $porNota; ?> %</td>
                        <td class="color"> No se indica mínimo aceptable</td>
                    </tr>
                    <tr style="font-size: 12px;">
                        <td style="padding-left: 10px;">* Brechas Teóricas</td>
                        <td align="center">
                            <?php
                                $valorT = ($encontrados['brecha_s'] != "") ? "Tiene" : "No tiene";
                                echo $valorT;
                                $valorT2 = ($valorT == "Tiene") ? "Si tiene: Gestionar Plan de tratamiento" : "No tiene: No requiere tratamiento";
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $valorT2; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="color"> Evaluación Práctica:</td>
                        <td align="center" class="color"><?php echo $porcentajeRedondeado; ?> %</td>
                        <td class="color"> Debe ser mayor o igual a 80 % </td>
                    </tr>
                    <tr style="font-size: 12px;">
                        <td style="padding-left: 10px;">* Brechas Ptáctica</td>
                        <td align="center">
                            <?php
                                $valorP = ($encontrados['brecha_p'] != "") ? "Tiene" : "No Tiene";
                                echo $valorP;
                                $valorP2 = ($valorP == "Tiene") ? "Si tiene: Gestionar Plan de tratamiento" : "No tiene: No requiere tratamiento";
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $valorP2; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="color"> Brechas Criticas:</td>
                        <td align="center" class="color"></td>
                        <td class="color"></td>
                    </tr>
                    <tr style="font-size: 12px;">
                        <td style="padding-left: 10px;">* Brechas Criticas Teóricas</td>
                        <td align="center">No Tiene</td>
                        <td>No tiene: No requiere tratamiento</td>
                    </tr>
                    <tr style="font-size: 12px;">
                        <td style="padding-left: 10px;">* Brechas Criticas Prácticas</td>
                        <td align="center">No Tiene</td>
                        <td>No tiene: No requiere tratamiento</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">Entonces, se concluye:</td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="color: #424949; font-size: 24px;">
                <b>NOTA FINAL :
                    <?php
                        echo $resultado_3. " %";
                    ?>
                </b>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="color: #424949; font-size: 24px;">
                <b>
                    <?php 
                        switch (true) {
                            case $resultado_3 >= 0 && $resultado_3 < 50:
                                $calificacion = 'LOGRO DEFICIENTE';
                                break;
                            case $resultado_3 >= 50 && $resultado_3 < 80:
                                $calificacion = 'LOGRO INSUFICIENTE';
                                break;
                            case $resultado_3 >= 80 && $resultado_3 < 90:
                                $calificacion = 'LOGRO SATISFACTORIO';
                                break;
                            case $resultado_3 >= 90 && $resultado_3 <= 100:
                                $calificacion = 'LOGRO SUPERIOR';
                                break;
                            default:
                                $calificacion = '';
                        }
                        echo $calificacion;
                    ?>
                </b>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <p style="font-size: 16px;">
                    El candidato Sr.(a) <b><?php echo $nombre; ?>, Rut <?php echo $rut; ?></b>, <b><?php echo $condicion2; ?></b> ha superado satisfactoriamente el proceso de Evaluación de Competencias Laborales en el Perfil:
                </p>
                <p>
                    OPERADOR <?php echo $equipoFormateado; ?>, MODELO <?php echo $modelo; ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">Autoriza:</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/img/FirmaCoordinador.png" style="width: 220px;"/></td>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/img/Timbre Certificado.png" style="width: 200px;"/></td>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/temp/<?php echo $id;?>.png" style="width: 100px; height: 100px;" /></td>
        </tr>
        <tr>
            <td align="center">Firma Coordinador</td>
            <td></td>
            <td align="center">Folio: <?php echo $codigo_eva. " ". $encontrados['folio']; ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 12px;">
                <b>Rúbica de Evaluación:</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" style="border: 1px solid black; font-size: 10px;">
                    <tr>
                        <th colspan="2">Tabla de Calificación General</th>
                    </tr>
                    <tr>
                        <td>De 00 % a 49,9 %</td>
                        <td>Logro Deficiente</td>
                    </tr>
                    <tr>
                        <td>De 50 % a 79,9 %</td>
                        <td>Logro Insuficiente</td>
                    </tr>
                    <tr>
                        <td>De 80 % a 89,9 %</td>
                        <td>Logro Saticfactorio</td>
                    </tr>
                    <tr>
                        <td>De 90 % a 100 %</td>
                        <td>Logro Superior</td>
                    </tr>
                </table>
            </td>
            <td>
                
            </td>
            <td>
                <table width="100%" style="border:1px solid black; font-size: 10px;">
                    <tr>
                        <th colspan="2">Ponderación para Calcular Nota Final</th>
                    </tr>
                    <tr>
                        <td>Prueba Conocimientos</td>
                        <td align="center" width="20%">20 %</td>
                    </tr>
                    <tr>
                        <td>Observaciones en Terreno > 79 %</td>
                        <td align="center">80 %</td>
                    </tr>
                    <tr>
                        <td>Nota Final para Aprobación > 80 %</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
         <tr>
            <td colspan="3">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>
<?php
    }else{

        $qr = $encontrados['qr'];
        $codigo_eva = $encontrados['ip'];
        $user_aprob = $encontrados['user_creacion'];

        $equipo = $encontrados['equipo'];
        $partes = explode('_', $equipo);
        $equipoFormateado = implode(' ', $partes);
        
        
        
        $fechaAprobacion = $encontrados['fecha_arprob'];
        $dateOriginal = $encontrados['fecha_arprob'];
        
        $fechaAprobacion = DateTime::createFromFormat('Y-m-d', $fechaAprobacion);
        $dateOriginal = DateTime::createFromFormat('Y-m-d', $dateOriginal);
        
        $fechaAprobacion->modify('+1 year');
        $nuevaFecha = $fechaAprobacion->format('Y-m-d');
        
        // Establecer el locale a español
        setlocale(LC_TIME, 'es_ES.utf8');
        
        // Formatear la fecha en español
        $nuevaFechaFormatoDeseado = strftime('%e de %B del %Y', $dateOriginal->getTimestamp());
        
            $dataEmpresa = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE folio = '$Id_cot'");
            $row_Empresa = mysqli_fetch_array($dataEmpresa);
            $nombreEmpresa = $row_Empresa['name_cliente'];
            $faena = $row_Empresa['faena'];
        
            //Agregamos la libreria para genera códigos QR
            require "../phpqrcode/qrlib.php";    
            
            //Declaramos una carpeta temporal para guardar la imagenes generadas
            $dir = '../temp/';
            
            //Si no existe la carpeta la creamos
            if (!file_exists($dir))
                mkdir($dir);
            
                //Declaramos la ruta y nombre del archivo a generar
            $filename = $dir.$id.'.png';
        
                //Parametros de Condiguración
            
            $tamano = 10; //Tamaño de Pixel
            $level = 'H'; //Precisión Baja
            $framSize = 3; //Tamaño en blanco
            $contenido = "https://acreditasys.tech/validarCertificados.php?hrshs=".$qr; //Texto
            
            //Enviamos los parametros a la Función para generar código QR 
            QRcode::png($contenido, $filename, $level, $tamano, $framSize);
        
            // Datos evaluador
            $eva = mysqli_query($conn, "SELECT * FROM `insp_eva` WHERE ip  = '$codigo_eva ' OR ev = '$codigo_eva '");
            $rowEva = mysqli_fetch_array($eva);
            $nombreEvaluador = $rowEva['name'];
            $rutEvaluador = $rowEva['rut'];
        
            // Datos del Informe
            $informe = mysqli_query($conn, "SELECT * FROM `informesM` WHERE IdOper = '$id'");
            $rowInforme = mysqli_fetch_array($informe);
            $lugarInforme = $rowInforme['lugar'];
            $EquipoProcedimiento = $rowInforme['equipo'];
        
            // Datos de usuario
            $user = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$user_aprob'");
            $rowUser = mysqli_fetch_array($user);
            $nombreUser = $rowUser['nombre_usuario'];
            $rutUser = $rowUser['rut'];

            // Ver procedimiento del equipo

            $pro = mysqli_query($conn, "SELECT * FROM `equiposInsp` WHERE equipo = '$EquipoProcedimiento'");
            $ResultEquipo = mysqli_fetch_array($pro);
            $verProcedimiento = $ResultEquipo['procedimiento'];
?>
<div class="img_logo">
<img src="https://acreditasys.tech/temp/<?php echo $id;?>.png" style="width: 100px; height: 100px;" />
</div>
    <table width="100%" border="0">
        <tr>
            <td style="height: 55px;">
                <div class="logo"><img src="https://acreditasys.tech/img/LogoPrincipal.png"/></div>
            </td>
            <td colspan="2">
                <div class="subtitle">
                    Certificado Organismo de Inspección.
                    <hr>
                    Los Andes <?php echo $nuevaFechaFormatoDeseado;?>, INFORME DE INSPECCIÓN.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span class="span-title" style="color: #797D7F;">
                    CERTIFICACIONES OPERAMAQ EMPRESA SPA
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span style="justify-content: center; color: #797D7F;" id="span-center">
                    Organismo de Inspección<br>
                    Acreditado por INN - NCH ISO 17020<br>
                    Resolución Exenta N° xxx
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span id="span-subtitle" style="color: #424949;">
                    <b>CERTIFICADO DE INSPECCIÓN MAQUINARIAS</b>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    Operamaq Empresa Spa, RUT: 77.741.739-8, certifica que el equipo individualizado <b><?php echo $certificate; ?></b> con la ley del tránsito 19495
                    - DS 132 Reglamento seguridad minera ART 51 - Manual del Equipo y Procedimiento <?php echo $verProcedimiento; ?>, según detalle:
                </p>
            </td>
        </tr>
        <tr>
            <td>Folio Inspección</td>
            <td colspan="2">: <?php echo $codigo_eva. " ". $encontrados['folio']; ?></td>
        </tr>
        <tr>
            <td>Vigencia</td>
            <td colspan="2">: <?php echo date('d-m-Y', strtotime($nuevaFecha)); ?></td>
        </tr>
        <tr>
            <td>Empresa</td>
            <td colspan="2">: <?php echo $nombreEmpresa; ?></td>
        </tr>
        <tr>
            <td>Equipo</td>
            <td colspan="2">: <?php echo $rowInforme['equipo']; ?></td>
        </tr>
        <tr>
            <td>Patente</td>
            <td colspan="2">: <?php echo $rowInforme['patente']; ?></td>
        </tr>
        <tr>
            <td>Marca</td>
            <td colspan="2">: <?php echo $rowInforme['marca']; ?></td>
        </tr>
        <tr>
            <td>Modelo</td>
            <td colspan="2">: <?php echo $rowInforme['modelo']; ?></td>
        </tr>
        <tr>
            <td>Año</td>
            <td colspan="2">: <?php echo $rowInforme['ano']; ?></td>
        </tr>
        <tr>
            <td>Motor</td>
            <td colspan="2">: <?php echo $rowInforme['motor']; ?></td>
        </tr>
        <tr>
            <td>Código Interno</td>
            <td colspan="2">: <?php echo $rowInforme['codigoInterno']; ?></td>
        </tr>
        <tr>
            <td>Horometro</td>
            <td colspan="2">: <?php echo $rowInforme['horometro']; ?></td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    De acuedo a inspeccíon realizada al equipo, <b><?php echo $certificate; ?></b> con los criterios establecidos, 
                    revisión sistemática efectuada y la aplicacíon procedimientos y normativa vigente.
                </p>
                Inspección realizada en <?php echo $lugarInforme; ?>  con fecha <?php echo $nuevaFechaFormatoDeseado;?>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/img/FirmaCoordinador.png" style="width: 220px;"/></td>
            <td style="width: 33%" align="center"><img src="https://acreditasys.tech/img/Timbre Certificado.png" style="width: 200px;"/></td>
            <td style="width: 33%" align="center"></td>
        </tr>
        <tr>
            <td align="center">Coordinador <br>Operamaq Empresa Spa</td>
            <td></td>
            <td align="center">Representante Legal<br>Operamaq Empresa Spa</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <hr>
                <span class="pie">
                    Para validar el Certificado y obtener detalles del proceso de ACREDITACIÓN escanea el QR<br>
                    Morenos # 239 - Los Andes – Chile / Celular: +56 9 27137337 / <a href="https://operamaq.cl">www.operamaq.cl</a><br>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>

</body>
</html>
<?php
$certificado = "Certificado_".$nombre.".pdf";
$html=ob_get_clean();

require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4');

$dompdf->render();

$dompdf->stream($certificado , array("Attachment"=> false));
?>