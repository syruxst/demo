<?php require_once('admin/conex.php'); error_reporting(0); date_default_timezone_set('America/Santiago');?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!--icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/icons/ms-icon-144x144.png">
    <title>::: Verificacion de Certificado :::</title>
    <style>
        :root {
            --color: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
            height: 100vh;
            background-image: url('https://acreditasys.tech/img/SelloAguaDos.png');
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            justify-content: center;
            align-items: center;
            color: #A6A7A7;
        }
        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: left;
            backdrop-filter: blur(5px);
        }
        h3{
            color: var(--color);
        }
        .logo {
            position: absolute;
            top: 5px;
            left: 60px;
        }
        label {
            border-bottom: 2px solid #e5e5e5; 
            padding-bottom: 1px; 
            display: inline-block; 
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
            border: 1px solid #e5e5e5;
            justify-content: left;
            align-items: left;
            text-align: left;
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
            .logo {
                position: relative;
                width: 90%;
                left: 0;
                justify-content: center;
                margin-bottom: 20px;
                text-align: center;
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
    </style>
</head>
<body>
    <?php
    $qr = $_GET['hrshs'];
    /*Detalle de la OT*/
    $query = "SELECT * FROM `detallle_ot` WHERE qr='$qr'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $ID = $row['id'];
    $folio = $row['folio'];
    $codigo = $row['ip'];
    $Rut = $row['rut'];
    $iD = $row['id_ot'];
    $userCreacion = $row['user_creacion'];
    $resolucion = ($row['certificate'] === "APROBADO") ? "TRABAJADOR ACREDITADO" : "TRABAJADOR NO ACREDITADO";
    $eq_certificado = ($row['certificate'] === "APROBADO") ? "CUMPLE" : "NO CUMPLE";
    
    /*Datos de Responsable*/
    $usuarios = "SELECT * FROM `usuarios` WHERE usuario = '$userCreacion'";
    $rsuser = mysqli_query($conn, $usuarios);
    $rowUser = mysqli_fetch_array($rsuser);

    /*Datos del evaluador*/
    $buscarEvaluador = "SELECT * FROM `insp_eva` WHERE ip = '$codigo' OR ev = '$codigo'";
    $resultado = mysqli_query($conn, $buscarEvaluador);
    $rowEvaluador = mysqli_fetch_array($resultado);
    $nombreEvaluador = $rowEvaluador['name'];
    /*Datos del Operador*/
    $oper = "SELECT * FROM `operadores` WHERE rut = '$Rut'";
    $resultOper = mysqli_query($conn, $oper);
    $rowOper = mysqli_fetch_array($resultOper);
    $licencia = $rowOper['licencia'];
    /*Datos de empresa*/
    $empresa = mysqli_query($conn, "SELECT * FROM `ot` WHERE id_ot = '$iD'");
    $rowEmpresa = mysqli_fetch_array($empresa);
    $Id_cot  = $rowEmpresa['id_cotiz'];
    $tipo = $rowEmpresa['tipo'];

    $dataEmpresa = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE folio = '$Id_cot'");
    $row_Empresa = mysqli_fetch_array($dataEmpresa);
    $nombreEmpresa = $row_Empresa['name_cliente'];
    $nombreFaena = $row_Empresa['faena'];
    /*Informe*/
    $dataInforme = mysqli_query($conn, "SELECT * FROM `informes` WHERE IdOper = '$ID'");
    $rowInforme = mysqli_fetch_array($dataInforme);
    $operador = $rowInforme['IdOper'];
    $obs = $rowInforme['observaciones'];

    $dateOriginal = $row['fecha_arprob'];
    $dateOriginal = DateTime::createFromFormat('Y-m-d', $dateOriginal);
    setlocale(LC_TIME, 'es_ES.utf8');
    $nuevaFechaFormatoDeseado = strftime('%e de %B del %Y', $dateOriginal->getTimestamp());

    if($tipo == 'O'){
    ?>
    <center><label for="">INFORME DE EVALUACIÓN DE COMPETENCIAS LABORALES</label></center>

    Los Andes <?php echo $nuevaFechaFormatoDeseado; ?>
    <div class="container">
        <div class="logo">
            <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo">
        </div>
        1.- ANTECEDENTES GENERALES DE LA EVALUACIÓN FOLIO N° <?php echo $folio; ?>
        <br><br>
        <label for="" >ANTECEDENTES DEL CANDIDATO</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Nombre : <?php echo $row['nombre']; ?>
                </div>
                <div class="col">
                    Rut : <?php echo $Rut; ?>
                </div>
                <div class="col">
                    Licencia : <?php echo $licencia; ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Empresa : <?php echo $nombreEmpresa; ?>
                </div>
                <div class="col">
                    Faena : <?php echo $nombreFaena; ?>
                </div>
            </div>
        </div>
        <label for="">ANTECEDENTES DEL EVALUADOR</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Nombre : <?php echo $nombreEvaluador; ?>
                </div>
                <div class="col">
                    RUT : <?php echo $rowEvaluador['rut']; ?>
                </div>
                <div class="col">
                    Código : <?php echo $codigo; ?>
                </div>
            </div>
        </div>
        <label for="">RESULTADO DE EVALUACIÓN</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Prueba Teorica : <?php echo $row['porNota']; ?> % Fecha : <?php echo date('d-m-Y', strtotime($row['date_out'])); ?>
                </div>
                <div class="col">
                    Prueba Práctica : <?php echo number_format($row['porcentaje'], 0); ?> % Fecha : <?php echo date('d-m-Y', strtotime($rowInforme['fechaInforme'])); ?>
                </div>
            </div>
        </div>
        <label for="">ANTECEDENTES DEL EQUIPO</label>
        <br>
        <div class="table">
            <div class="row">
                    <div class="col">
                    Equipo : <?php echo $row['equipo']; ?>
                </div>
                <div class="col">
                    Modelo : <?php echo $row['modelo']; ?>
                </div>
                <div class="col">
                   
                </div>
            </div>
        </div>
        <label for="">LUGAR DE EVALUACIÓN</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    TEÓRICO : PLATAFORMA OPERAMAQ ONLINE
                </div>
                <div class="col">
                    PRÁCTICO : <?php echo $rowInforme['lugar']; ?>
                </div>
            </div>
        </div>
        <label for="">OBSERVACIONES PROCESO DE EVALUACIÓN</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    TEÓRICO : Modalidad online aula virtual, plataforma operamaq. 
                </div>
                <div class="col">
                    PRÁCTICO : Modalidad presencial en terreno <?php echo $rowInforme['lugar']; ?>
                </div>
            </div>
        </div>
        <label for="">ELAVORADO POR</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Nombre : <?php echo $rowUser['nombre_usuario']; ?>
                </div>
                <div class="col">
                    RUN : <?php echo $rowUser['rut'] ?>
                </div>
            </div>
        </div>

        <label for="">RESULTADO DE EVALUACIÓN</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    <?php echo $resolucion;?>
                </div>
            </div>
        </div>
    </div>
<?php
    }else {
        $eq = mysqli_query($conn, "SELECT * FROM `informesM` WHERE folio ='$folio' ");
        $rst_eq = mysqli_fetch_array($eq);
?>
    <center><label for="">CERTIFICADO ORGANISMO DE INSPECCIÓN</label></center>
    Los Andes <?php echo $nuevaFechaFormatoDeseado; ?>
    <div class="container">
        <div class="logo">
            <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo">
        </div>
        ANTECEDENTES GENERALES DE LA INSPECCIÓN FOLIO N° <?php echo $folio; ?>
        <br><br>
        <label for="" >ANTECEDENTES DEL EQUIPO</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Equipo : <?php echo $rst_eq['equipo']; ?>
                </div>
                <div class="col">
                    Patente : <?php echo $rst_eq['patente'];?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Marca : <?php echo $rst_eq['marca'];?>
                </div>
                <div class="col">
                    Modelo  : <?php echo $rst_eq['modelo'];?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Año : <?php echo $rst_eq['ano'];?>
                </div>
                <div class="col">
                    Motor : <?php echo $rst_eq['motor'];?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Codigo Interno : <?php echo $rst_eq['codigoInterno'];?>
                </div>
                <div class="col">
                    Horometro : <?php echo $rst_eq['horometro'];?>
                </div>
            </div>
        </div>
        <label for="" >ANTECEDENTES DE LA EMPRESA</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Empresa : <?php echo $nombreEmpresa; ?>
                </div>
                <div class="col">
                    Faena : <?php echo $nombreFaena; ?>
                </div>
            </div>
        </div>
        <label for="">ANTECEDENTES DEL INSPECTOR</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    Nombre : <?php echo $nombreEvaluador; ?>
                </div>
                <div class="col">
                    RUT : <?php echo $rowEvaluador['rut']; ?>
                </div>
                <div class="col">
                    Código : <?php echo $codigo; ?>
                </div>
            </div>
        </div>
        <label for="">RESULTADO DE LA INSPECCIÓN</label>
        <br>
        <div class="table">
            <div class="row">
                <div class="col">
                    <p style="text-align: justify; color: #A6A7A7;">De acuedo a inspeccíon realizada al equipo, <b><?php echo $eq_certificado; ?></b> con los criterios establecidos, revisión sistemática efectuada y la aplicacíon procedimientos y normativa vigente.</p>
                </div>
            </div>
        </div>
    </div>

<?php
    }
?>
</body>
</html>