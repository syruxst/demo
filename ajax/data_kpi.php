<?php
    session_start();
    error_reporting(0);

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
    include('../admin/hash.php');

    setlocale(LC_TIME, 'es_ES.UTF-8');
    $mesActual = date("n");
    $mesActualMayuscula = strtoupper(strftime("%B"));
    $año = date("Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Agrega esto en la sección head de tu HTML -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@2.8.0"></script>


    <title>DataBoard</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden;  /* Esto oculta la barra de desplazamiento horizontal */
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(calc(33.33% - 2px), 1fr));
            gap: 1px;
            padding: 10px;
            height: 100vh; /* Cambiado a 100vh para ocupar el 100% del alto de la pantalla */
            box-sizing: border-box;
        }
        .grid-item {
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 2px;
            font-size: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh; /* Cambiado a 50% para ocupar el 50% del alto de la fila */
            width: 100%;
            box-sizing: border-box;
        }
        canvas {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="grid-container">
        <div class="grid-item">

            <!-- para grafico de barras -->
            <?php
                // Construir la consulta SQL
                $query_cert = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad_total 
                        FROM serviceCot 
                        WHERE YEAR(fecha_creacion) = $año 
                        AND (servicio='CERT OPERADOR 3 (51- En Adelante)' 
                            OR servicio='CERT OPERADOR 2 (21-50)' 
                            OR servicio='CERT OPERADOR 1 (1-20)') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

                // Ejecutar la consulta
                $result_cert = $conn->query($query_cert);

                // Inicializar array para almacenar datos
                $data_cadena_cert = array_fill(0, 12, 0); // Inicializar con ceros para todos los meses

                // Rellenar array con datos de la consulta
                while ($row_cert = $result_cert->fetch_assoc()) {
                    $mes = intval($row_cert['mes']) - 1;
                    $cantidad_cert = intval($row_cert['cantidad_total']);

                    // Rellenar el array correspondiente según la categoría
                    $data_cadena_cert[$mes] = $cantidad_cert; 
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_cert_json = json_encode($data_cadena_cert);
            ?>

            <!-- fin de codigo de barras -->
            <!-- codigo grafico de liena -->
            <?php
                // Obtener datos para el gráfico de línea (KPI)
                $query_kpi = "SELECT 
                                mes,
                                certificacion_op
                                FROM 
                                kpi_table
                                WHERE 
                                mes BETWEEN 1 AND 12
                                ORDER BY 
                                mes";

                $result_kpi = $conn->query($query_kpi);

                // Inicializar array para almacenar datos de KPI
                $data_cadena_kpi = array_fill(0, 12, 0);

                // Rellenar array con datos de KPI
                while ($row_kpi = $result_kpi->fetch_assoc()) {
                    $mes_kpi = intval($row_kpi['mes']) - 1;
                    $certificacion_op_kpi = intval($row_kpi['certificacion_op']);
                    $data_cadena_kpi[$mes_kpi] = $certificacion_op_kpi;
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_kpi_json = json_encode($data_cadena_kpi);
            ?>
            <!-- fin de codigo grafico linea -->

            <!-- Imprimir el gráfico después de obtener los datos -->
            <canvas id="myChartL" style="width: 200vh; height: 150vh;"></canvas>

            <script>
                const dataL = {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    datasets: [
                        {
                            label: 'Certificaciones',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_cert_json; ?>,
                            type: 'bar',
                        },
                        {
                            label: 'KPI',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_kpi_json; ?>,
                            fill: false, 
                            type: 'line', 
                        }
                    ]
                };

                const configL = {
                    type: 'bar',
                    data: dataL,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'CERTIFICACIÓN'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                const ctxL = document.getElementById('myChartL').getContext('2d');
                new Chart(ctxL, configL);
            </script>
        </div>
        <div class="grid-item">
            <!--SEGUNDO DIV 2-->
            <!-- para grafico de barras -->
            <?php
                // Construir la consulta SQL
                $query_eva = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad_total 
                        FROM serviceCot 
                        WHERE YEAR(fecha_creacion) = $año 
                        AND (servicio='EVA LEY 1 CHV (EXC-ALJIBE-RETROEX)' 
                            OR servicio='EVA LEY 2 CHV (BULL-TTES PERSONAL)' 
                            OR servicio='EVA LEY 3 CHV (MOTO-TOLVA-CARGADOR-EQ. MOVILES)' 
                            OR servicio='EVA LEY 5 CHV (RIGGER ALTA)' 
                            OR servicio='EVA LEY 4 CHV (MANIPULADOR-HORQUILLA-ALZA HOMBRE)') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

                // Ejecutar la consulta
                $result_eva = $conn->query($query_eva);

                // Inicializar array para almacenar datos
                $data_cadena_eva = array_fill(0, 12, 0); // Inicializar con ceros para todos los meses

                // Rellenar array con datos de la consulta
                while ($row_eva = $result_eva->fetch_assoc()) {
                    $mes = intval($row_eva['mes']) - 1;
                    $cantidad_eva = intval($row_eva['cantidad_total']);

                    // Rellenar el array correspondiente según la categoría
                    $data_cadena_eva[$mes] = $cantidad_eva; 
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_eva_json = json_encode($data_cadena_eva);
            ?>
            <!-- fin de codigo de barras -->
            <!-- codigo grafico de liena -->
            <?php
                // Obtener datos para el gráfico de línea (KPI)
                $query_kpi_eva = "SELECT 
                                mes,
                                evaluacion
                                FROM 
                                kpi_table
                                WHERE 
                                mes BETWEEN 1 AND 12
                                ORDER BY 
                                mes";

                $result_kpi_eva = $conn->query($query_kpi_eva);

                // Inicializar array para almacenar datos de KPI
                $data_cadena_kpi_eva = array_fill(0, 12, 0);

                // Rellenar array con datos de KPI
                while ($row_kpi_eva = $result_kpi_eva->fetch_assoc()) {
                    $mes_kpi_eva = intval($row_kpi_eva['mes']) - 1;
                    $certificacion_eva_kpi = intval($row_kpi_eva['evaluacion']);
                    $data_cadena_kpi_eva[$mes_kpi_eva] = $certificacion_eva_kpi;
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_kpi_json_eva = json_encode($data_cadena_kpi_eva);
            ?>
            <!-- fin de codigo grafico linea -->

            <!-- Imprimir el gráfico después de obtener los datos -->
            <canvas id="myChartLE" style="width: 200vh; height: 150vh;"></canvas>

            <script>
                const dataLE = {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    datasets: [
                        {
                            label: 'Evaluación',
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_eva_json; ?>,
                            type: 'bar',
                        },
                        {
                            label: 'KPI',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_kpi_json_eva; ?>,
                            fill: false, 
                            type: 'line', 
                        }
                    ]
                };

                const configLE = {
                    type: 'bar',
                    data: dataLE,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'EVALUACIÓN'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                const ctxLE = document.getElementById('myChartLE').getContext('2d');
                new Chart(ctxLE, configLE);
            </script>
        </div>
        <div class="grid-item">
            <!--TERCER DIV 3-->
            <!-- para grafico de barras -->
            <?php
                // Construir la consulta SQL
                $query_Operador = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad 
                        FROM `serviceCot`
                        WHERE YEAR(fecha_creacion) = $año 
                        AND servicio='SUM. OPERADOR'
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

                // Ejecutar la consulta
                $result_Operador = $conn->query($query_Operador);

                // Inicializar array para almacenar datos
                $data_cadena_Operador = array_fill(0, 12, 0); // Inicializar con ceros para todos los meses

                // Rellenar array con datos de la consulta
                while ($row_Operador = $result_Operador->fetch_assoc()) {
                    $mes = intval($row_Operador['mes']) - 1;
                    $cantidad_Operador = intval($row_Operador['cantidad']);

                    // Rellenar el array correspondiente según la categoría
                    $data_cadena_Operador[$mes] = $cantidad_Operador; 
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_Operador_json = json_encode($data_cadena_Operador);
            ?>
            <!-- fin de codigo de barras -->
            <!-- codigo grafico de liena -->
            <?php
                // Obtener datos para el gráfico de línea (KPI)
                $query_kpi_Operador = "SELECT 
                                mes,
                                suministros_op
                                FROM 
                                kpi_table
                                WHERE 
                                mes BETWEEN 1 AND 12
                                ORDER BY 
                                mes";

                $result_kpi_Operador = $conn->query($query_kpi_Operador);

                // Inicializar array para almacenar datos de KPI
                $data_cadena_kpi_Operador = array_fill(0, 12, 0);

                // Rellenar array con datos de KPI
                while ($row_kpi_Operador = $result_kpi_Operador->fetch_assoc()) {
                    $mes_kpi_Operador = intval($row_kpi_Operador['mes']) - 1;
                    $certificacion_Operador_kpi = intval($row_kpi_Operador['suministros_op']);
                    $data_cadena_kpi_Operador[$mes_kpi_Operador] = $certificacion_Operador_kpi;
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_kpi_json_Operador = json_encode($data_cadena_kpi_Operador);
            ?>
            <!-- fin de codigo grafico linea -->

            <!-- Imprimir el gráfico después de obtener los datos -->
            <canvas id="myChartLOperador" style="width: 200vh; height: 150vh;"></canvas>

            <script>
                const dataLOperador = {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    datasets: [
                        {
                            label: 'Operadores',
                            backgroundColor: 'rgba(255, 206, 86, 0.5)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_Operador_json; ?>,
                            type: 'bar',
                        },
                        {
                            label: 'KPI',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_kpi_json_Operador; ?>,
                            fill: false, 
                            type: 'line', 
                        }
                    ]
                };

                const configLOperador = {
                    type: 'bar',
                    data: dataLOperador,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'OPERADORES'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                const ctxLOperador = document.getElementById('myChartLOperador').getContext('2d');
                new Chart(ctxLOperador, configLOperador);
            </script>
        </div>  
        <div class="grid-item">
            <!--CUARTO DIV 4-->
            <!-- para grafico de barras -->
            <?php
                // Construir la consulta SQL
                $query_Inps = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad 
                        FROM `serviceCot`
                        WHERE YEAR(fecha_creacion) = $año 
                        AND (servicio='CERT EQUIPO 1 (1-10)' 
                            OR servicio='CERT EQUIPO 2 (11-20)' 
                            OR servicio='CERT EQUIPO 3 (21-En Adelante)' 
                            OR servicio='CERT EQUIPO ESTACINARIO') 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

                // Ejecutar la consulta
                $result_Inps = $conn->query($query_Inps);

                // Inicializar array para almacenar datos
                $data_cadena_Inps = array_fill(0, 12, 0); // Inicializar con ceros para todos los meses

                // Rellenar array con datos de la consulta
                while ($row_Inps = $result_Inps->fetch_assoc()) {
                    $mes = intval($row_Inps['mes']) - 1;
                    $cantidad_Inps = intval($row_Inps['cantidad']);

                    // Rellenar el array correspondiente según la categoría
                    $data_cadena_Inps[$mes] = $cantidad_Inps; 
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_Inps_json = json_encode($data_cadena_Inps);
            ?>
            <!-- fin de codigo de barras -->
            <!-- codigo grafico de liena -->
            <?php
                // Obtener datos para el gráfico de línea (KPI)
                $query_kpi_Inps = "SELECT 
                                mes,
                                inspeccion
                                FROM 
                                kpi_table
                                WHERE 
                                mes BETWEEN 1 AND 12
                                ORDER BY 
                                mes";

                $result_kpi_Inps = $conn->query($query_kpi_Inps);

                // Inicializar array para almacenar datos de KPI
                $data_cadena_kpi_Inps = array_fill(0, 12, 0);

                // Rellenar array con datos de KPI
                while ($row_kpi_Inps = $result_kpi_Inps->fetch_assoc()) {
                    $mes_kpi_Inps = intval($row_kpi_Inps['mes']) - 1;
                    $certificacion_Inps_kpi = intval($row_kpi_Inps['inspeccion']);
                    $data_cadena_kpi_Inps[$mes_kpi_Inps] = $certificacion_Inps_kpi;
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_kpi_json_Inps = json_encode($data_cadena_kpi_Inps);
            ?>
            <!-- fin de codigo grafico linea -->

            <!-- Imprimir el gráfico después de obtener los datos -->
            <canvas id="myChartLInps" style="height:100%;"></canvas>

            <script>
                const dataLInps = {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    datasets: [
                        {
                            label: 'Inspecciones',
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_Inps_json; ?>,
                            type: 'bar',
                        },
                        {
                            label: 'KPI',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_kpi_json_Inps; ?>,
                            fill: false, 
                            type: 'line', 
                        }
                    ]
                };

                const configLInps = {
                    type: 'bar',
                    data: dataLInps,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'INSPECCIÓN'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                const ctxLInps = document.getElementById('myChartLInps').getContext('2d');
                new Chart(ctxLInps, configLInps);
            </script>
        </div>
        <div class="grid-item">
            <!--QUINTO DIV 5-->
            <!-- para grafico de barras -->
            <?php
                // Construir la consulta SQL
                $query_Modelo = "SELECT MONTH(fecha_creacion) AS mes, SUM(cantidad) AS cantidad 
                        FROM `serviceCot`
                        WHERE YEAR(fecha_creacion) = $año 
                        AND servicio='CERT OPERADOR (Modelo Adicional)' 
                        AND estado = 'A'
                        GROUP BY MONTH(fecha_creacion)";

                // Ejecutar la consulta
                $result_Modelo = $conn->query($query_Modelo);

                // Inicializar array para almacenar datos
                $data_cadena_Modelo = array_fill(0, 12, 0); // Inicializar con ceros para todos los meses

                // Rellenar array con datos de la consulta
                while ($row_Modelo = $result_Modelo->fetch_assoc()) {
                    $mes = intval($row_Modelo['mes']) - 1;
                    $cantidad_Modelo = intval($row_Modelo['cantidad']);

                    // Rellenar el array correspondiente según la categoría
                    $data_cadena_Modelo[$mes] = $cantidad_Modelo; 
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_Modelo_json = json_encode($data_cadena_Modelo);
            ?>
            <!-- fin de codigo de barras -->
            <!-- codigo grafico de liena -->
            <?php
                // Obtener datos para el gráfico de línea (KPI)
                $query_kpi_Modelo = "SELECT 
                                mes,
                                modelo_adicional
                                FROM 
                                kpi_table
                                WHERE 
                                mes BETWEEN 1 AND 12
                                ORDER BY 
                                mes";

                $result_kpi_Modelo = $conn->query($query_kpi_Modelo);

                // Inicializar array para almacenar datos de KPI
                $data_cadena_kpi_Modelo = array_fill(0, 12, 0);

                // Rellenar array con datos de KPI
                while ($row_kpi_Modelo = $result_kpi_Modelo->fetch_assoc()) {
                    $mes_kpi_Modelo = intval($row_kpi_Modelo['mes']) - 1;
                    $certificacion_Modelo_kpi = intval($row_kpi_Modelo['modelo_adicional']);
                    $data_cadena_kpi_Modelo[$mes_kpi_Modelo] = $certificacion_Modelo_kpi;
                }

                // Convertir array a formato JSON para usar en JavaScript
                $data_cadena_kpi_json_Modelo = json_encode($data_cadena_kpi_Modelo);
            ?>
            <!-- fin de codigo grafico linea -->

            <!-- Imprimir el gráfico después de obtener los datos -->
            <canvas id="myChartLModelo" style="height:100%;"></canvas>

            <script>
                const dataLModelo = {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    datasets: [
                        {
                            label: 'Modelo Adicional',
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_Modelo_json; ?>,
                            type: 'bar',
                        },
                        {
                            label: 'KPI',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_kpi_json_Modelo; ?>,
                            fill: false, 
                            type: 'line',
                        }
                    ]
                };

                const configLModelo = {
                    type: 'bar',
                    data: dataLModelo,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'MODELO ADICIONAL'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                const ctxLModelo = document.getElementById('myChartLModelo').getContext('2d');
                new Chart(ctxLModelo, configLModelo);
            </script>
        </div>
        <div class="grid-item">
            <!--SEXTO DIV 6-->
            <?php
                // Obtener el índice del mes actual (considerando que los meses comienzan desde 0)
                $currentMonthIndex = date('n') - 1;

                // Inicializar array para almacenar la suma de valores por mes
                $sum_values_by_month = array_fill(0, 12, 0);

                // Sumar los valores de cada categoría por mes
                foreach ($data_cadena_kpi as $mes => $value_cert) {
                    $sum_values_by_month[$mes] += $value_cert;
                }
    
                foreach ($data_cadena_kpi_eva as $mes => $value_eva) {
                    $sum_values_by_month[$mes] += $value_eva;
                }
    
                foreach ($data_cadena_kpi_Operador as $mes => $value_Operador) {
                    $sum_values_by_month[$mes] += $value_Operador;
                }
    
                foreach ($data_cadena_kpi_Inps as $mes => $value_Inps) {
                    $sum_values_by_month[$mes] += $value_Inps;
                }
    
                foreach ($data_cadena_kpi_Modelo as $mes => $value_Modelo) {
                    $sum_values_by_month[$mes] += $value_Modelo;
                }
    
                // Convertir el array de la suma a formato JSON para usar en JavaScript
                $sum_values_by_month_json = json_encode($sum_values_by_month);

                // Filtrar los datos del mes actual
                $filtered_cert = [$data_cadena_cert[$currentMonthIndex]];
                $filtered_cert_kpi = [$data_cadena_kpi[$currentMonthIndex]];

                $filtered_eva = [$data_cadena_eva[$currentMonthIndex]];
                $filtered_eva_kpi = [$data_cadena_kpi_eva[$currentMonthIndex]];

                $filtered_Operador = [$data_cadena_Operador[$currentMonthIndex]];
                $filtered_Operador_kpi = [$data_cadena_kpi_Operador[$currentMonthIndex]];

                $filtered_Inps = [$data_cadena_Inps[$currentMonthIndex]];
                $filtered_Inps_kpi = [$data_cadena_kpi_Inps[$currentMonthIndex]];

                $filtered_Modelo = [$data_cadena_Modelo[$currentMonthIndex]];
                $filtered_Modelo_kpi = [$data_cadena_kpi_Modelo[$currentMonthIndex]];

                $filtered_sum = [$sum_values_by_month[$currentMonthIndex]];


                // Convertir los datos filtrados a formato JSON
                $filtered_cert_json = json_encode($filtered_cert);
                $filtered_cert_json_kpi = json_encode($filtered_cert_kpi);

                $filtered_eva_json = json_encode($filtered_eva);
                $filtered_eva_json_kpi = json_encode($filtered_eva_kpi);

                $filtered_Operador_json = json_encode($filtered_Operador);
                $filtered_Operador_json_kpi = json_encode($filtered_Operador_kpi);

                $filtered_Inps_json = json_encode($filtered_Inps);
                $filtered_Inps_json_kpi = json_encode($filtered_Inps_kpi);

                $filtered_Modelo_json = json_encode($filtered_Modelo);
                $filtered_Modelo_json_kpi = json_encode($filtered_Modelo_kpi);

                $filtered_sum_json = json_encode($filtered_sum);
            ?>

            <canvas id="myChartStacked" style="height: 100%;"></canvas>

            <script>
            const data = {
                labels: ['SERVICIOS', 'KPI'],
                datasets: [
                    {
                        label: 'Certificaciones',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        data: [<?php echo $filtered_cert_json; ?>, <?php echo $filtered_cert_json_kpi; ?>]
                    },
                    {
                        label: 'Evaluación',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        data: [<?php echo $filtered_eva_json; ?> , <?php echo $filtered_eva_json_kpi; ?>]
                    },
                    {
                        label: 'Operadores',
                        backgroundColor: 'rgba(255, 206, 86, 0.5)',
                        data: [<?php echo $filtered_Operador_json; ?>, <?php echo $filtered_Operador_json_kpi; ?>]
                    },
                    {
                        label: 'Inspecciones',
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        data: [<?php echo $filtered_Inps_json; ?>, <?php echo $filtered_Inps_json_kpi; ?>]
                    },
                    {
                        label: 'Modelo Adicional',
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        data: [<?php echo $filtered_Modelo_json; ?>, <?php echo $filtered_Modelo_json_kpi; ?>]
                    },
                    {
                        label: 'KPI',
                        backgroundColor: 'rgba(20, 124, 23, 0.5)',
                        data: [0 ,0]
                    }
                ]
            };
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: '<?php echo $mesActualMayuscula; ?>'
                            },
                        },
                        responsive: true,
                        interaction: {
                            intersect: false,
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                };

                const ctx = document.getElementById('myChartStacked').getContext('2d');
                new Chart(ctx, config);
            </script>
        </div>  
    </div>
</body>

<?php
$conn->close();
?>
</html>