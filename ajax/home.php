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
        body {
            margin: 0;
            padding: 2px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            padding: 10px;
            height: 95vh;
            grid-auto-rows: minmax(0, 1fr);
        }

        .grid-item {
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 2px;
            font-size: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .full-width {
            grid-column: 1 / span 3;
        }
    </style>
    <script>
        function actualizarPagina() {
            location.reload(true);
        }
        setInterval(actualizarPagina, 60000);
    </script>
</head>
<body>
<div class="grid-container">
        <div class="grid-item">
            <!--PRIMER DIV 1-->
            <?php
                setlocale(LC_TIME, 'es_ES');
                $mesActual = date("n");
                $mesActualMayuscula = strtoupper(strftime("%B"));

                /****SI******/
                $Sql = "SELECT tipo, COUNT(*) as cantidad 
                FROM `cotiz` 
                WHERE `estado` = 'APROBADO' 
                AND MONTH(`fecha_creacion`) = $mesActual 
                AND `tipo` IN ('O', 'M', 'E') 
                GROUP BY tipo";

                $Result = mysqli_query($conn, $Sql);
       
                // Verificar si la consulta se ejecutó correctamente
                if ($Result) {
                    // Inicializar las cantidades en caso de que no haya resultados para un tipo específico
                    $Cant_O = 0;
                    $Cant_M = 0;
                    $Cant_E = 0;
                
                    // Obtener resultados como un array asociativo
                    while ($row = mysqli_fetch_assoc($Result)) {
                        // Asignar la cantidad correspondiente al tipo de cotización
                        if ($row['tipo'] == 'O') {
                            $Cant_O = $row['cantidad'];
                        } elseif ($row['tipo'] == 'M') {
                            $Cant_M = $row['cantidad'];
                        } elseif ($row['tipo'] == 'E'){
                            $Cant_E = $row['cantidad'];
                        }
                    }
                }

                /****NO******/
                $sql = "SELECT tipo, COUNT(*) as cantidad 
                FROM `cotiz` 
                WHERE `estado` = 'PENDIENTE' 
                AND MONTH(`fecha_creacion`) = $mesActual 
                AND `tipo` IN ('O', 'M', 'E') 
                GROUP BY tipo";

                $result = mysqli_query($conn, $sql);
       
                // Verificar si la consulta se ejecutó correctamente
                if ($result) {
                    // Inicializar las cantidades en caso de que no haya resultados para un tipo específico
                    $cant_O_no = 0;
                    $cant_M_no = 0;
                    $cant_E_no = 0;
                
                    // Obtener resultados como un array asociativo
                    while ($Row = mysqli_fetch_assoc($result)) {
                        // Asignar la cantidad correspondiente al tipo de cotización
                        if ($Row['tipo'] == 'O') {
                            $cant_O_no = $Row['cantidad'];
                        } elseif ($Row['tipo'] == 'M') {
                            $cant_M_no = $Row['cantidad'];
                        } elseif ($Row['tipo'] == 'E'){
                            $cant_E_no = $Row['cantidad'];
                        }
                    }
                }

                /****TOTAL******/
                // Tipos de cotización
                $tipos = ['O', 'M', 'E'];

                // Inicializar un array para almacenar las cantidades
                $cantidades = [];

                // Realizar consultas y obtener cantidades
                foreach ($tipos as $tipo) {
                    $query = "SELECT COUNT(*) as cantidad FROM `cotiz` WHERE `tipo` = ? AND MONTH(`fecha_creacion`) = $mesActual ";
                    
                    // Preparar la declaración
                    $stmt = mysqli_prepare($conn, $query);

                    // Vincular el parámetro
                    mysqli_stmt_bind_param($stmt, 's', $tipo);

                    // Ejecutar la consulta
                    mysqli_stmt_execute($stmt);

                    // Vincular el resultado
                    mysqli_stmt_bind_result($stmt, $cantidad);

                    // Obtener el resultado
                    mysqli_stmt_fetch($stmt);

                    // Almacenar la cantidad en el array
                    $cantidades[$tipo] = $cantidad;

                    // Cerrar la declaración
                    mysqli_stmt_close($stmt);
                }

                /* Mostrar las cantidades (puedes utilizar esta información según tus necesidades)
                foreach ($cantidades as $tipo => $cantidad) {
                    echo "Cantidad de resultados para tipo $tipo: $cantidad<br>";
                }*/
            ?>

            <canvas id="myChart"></canvas>
            <script>
                // Datos de ejemplo
                const data = {
                    labels: ['SI', 'NO', 'TOTAL'],
                    datasets: [
                        {
                            label: 'M',
                            data: [<?php echo $Cant_M; ?>, <?php echo $cant_M_no; ?>, <?php echo $cantidades['M']; ?>],
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'O',
                            data: [<?php echo $Cant_O; ?>, <?php echo $cant_O_no; ?>, <?php echo $cantidades['O']; ?>],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'E',
                            data: [<?php echo $Cant_E; ?>, <?php echo $cant_E_no; ?>, <?php echo $cantidades['E']; ?>],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1  
                        }
                    ]
                };
                <?php $totales = $cantidades['M'] +  $cantidades['O']; ?>
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'COTIZACIONES MES <?php echo $mesActualMayuscula; ?>'
                            },
                            subtitle: {
                                display: true,
                                text: 'TOTAL: <?php echo $totales; ?>'
                            }
                        },
                        responsive: true,
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

                // Obtener el contexto del lienzo
                const ctx = document.getElementById('myChart').getContext('2d');

                // Crear el gráfico
                const myChart = new Chart(ctx, config);
            </script>
        </div>
        <div class="grid-item">
            <!--SEGUNDO DIV 2-->
            <?php
                $ot = "SELECT equipo, patente, COUNT(*) as cantidad 
                        FROM `detallle_ot` 
                        WHERE MONTH(`fecha_creacion`) = $mesActual 
                        AND patente = ''
                        GROUP BY equipo";
                $rOt = mysqli_query($conn, $ot);

                // Calcular la suma total de cantidades
                $sumaTotal = 0;
                while ($fila = mysqli_fetch_assoc($rOt)) {
                    $sumaTotal += $fila['cantidad'];
                }

                // Reiniciar el conjunto de resultados para el bucle principal
                mysqli_data_seek($rOt, 0);
            ?>

            <canvas id="myChartOTmes"></canvas>
            <script>
                // 1. Obtener y procesar los datos de la consulta SQL
                const equipos = [];
                const cantidades = [];

                <?php
                while ($fila = mysqli_fetch_assoc($rOt)) {
                    echo "equipos.push('{$fila['equipo']}');\n";
                    echo "cantidades.push({$fila['cantidad']});\n";
                }
                ?>

                // 2. Configurar el objeto 'data' para el gráfico de barras
                const DATA = {
                    labels: equipos, // Nombres de los equipos
                    datasets: [{
                        label: 'Cantidad',
                        data: cantidades, // Cantidades de cada equipo
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Color de fondo de las barras
                        borderColor: 'rgba(255, 99, 132, 1)', // Color del borde de las barras
                        borderWidth: 1 // Ancho del borde de las barras
                    }]
                };

                // 3. Configurar el objeto 'options' para el gráfico
                const OPTIONS = {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Ocultar leyenda
                        },
                        title: {
                            display: true,
                            text: 'CERTIFICACIONES MES <?php echo $mesActualMayuscula; ?>'
                        },
                        subtitle: {
                            display: true,
                            text: 'TOTAL: <?php echo $sumaTotal; ?>'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value, index, values) {
                                    // Mostrar el nombre del equipo al hacer hover sobre la barra
                                    return index + 1;
                                }
                            }
                        }
                    }
                };

                // 4. Configurar el objeto 'config' con las opciones del gráfico
                const CONFIG = {
                    type: 'bar',
                    data: DATA,
                    options: OPTIONS
                };

                // 5. Crear el gráfico utilizando Chart.js
                const CTX = document.getElementById('myChartOTmes').getContext('2d');
                const MYCHART = new Chart(CTX, CONFIG);
            </script>
        </div>
        <div class="grid-item">
            <!--TERCER DIV 3-->
            <?php
                $ot_clone = "SELECT equipo, patente, COUNT(*) as cantidad 
                            FROM `detallle_ot` 
                            WHERE MONTH(`fecha_creacion`) = $mesActual 
                            AND patente != ''
                            GROUP BY equipo";
                $rOt_clone = mysqli_query($conn, $ot_clone);

                // Calcular la suma total de cantidades
                $sumaTotal_clone = 0;
                while ($fila_clone = mysqli_fetch_assoc($rOt_clone)) {
                    $sumaTotal_clone += $fila_clone['cantidad'];
                }

                // Reiniciar el conjunto de resultados para el bucle principal
                mysqli_data_seek($rOt_clone, 0);
            ?>

            <canvas id="myChartOTmes_clone"></canvas>
            <script>
                // 1. Obtener y procesar los datos de la consulta SQL
                const equipos_clone = [];
                const cantidades_clone = [];

                <?php
                while ($fila_clone = mysqli_fetch_assoc($rOt_clone)) {
                    echo "equipos_clone.push('{$fila_clone['equipo']}');\n";
                    echo "cantidades_clone.push({$fila_clone['cantidad']});\n";
                }
                ?>

                // 2. Configurar el objeto 'data' para el gráfico de barras
                const DATA_clone = {
                    labels: equipos_clone, // Nombres de los equipos
                    datasets: [{
                        label: 'Cantidad',
                        data: cantidades_clone, // Cantidades de cada equipo
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                        borderWidth: 1 // Ancho del borde de las barras
                    }]
                };

                // 3. Configurar el objeto 'options' para el gráfico
                const OPTIONS_clone = {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Ocultar leyenda
                        },
                        title: {
                            display: true,
                            text: 'INSPECCIONES MES <?php echo $mesActualMayuscula; ?>'
                        },
                        subtitle: {
                            display: true,
                            text: 'TOTAL: <?php echo $sumaTotal_clone; ?>'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value, index, values) {
                                    // Mostrar el nombre del equipo al hacer hover sobre la barra
                                    return index + 1;
                                }
                            }
                        }
                    }
                };

                // 4. Configurar el objeto 'config' con las opciones del gráfico
                const CONFIG_clone = {
                    type: 'bar',
                    data: DATA_clone,
                    options: OPTIONS_clone
                };

                // 5. Crear el gráfico utilizando Chart.js
                const CTX_clone = document.getElementById('myChartOTmes_clone').getContext('2d');
                const MYCHART_clone = new Chart(CTX_clone, CONFIG_clone);
            </script>        
        </div>  
        <div class="grid-item">
            <!--CUARTO DIV 4-->
            <?php
                $año = date("Y");

                // Otras consultas y variables para el gráfico de doughnut
                $totalDoughnut = 0;

                // Consulta para 'APROBADO'
                $queryDoughnutAprobado = "SELECT COUNT(*) as cantidad FROM `cotiz` WHERE YEAR(`fecha_creacion`) = $año AND estado = 'APROBADO'";

                $stmtDoughnutAprobado = mysqli_prepare($conn, $queryDoughnutAprobado);

                mysqli_stmt_execute($stmtDoughnutAprobado);

                mysqli_stmt_bind_result($stmtDoughnutAprobado, $cantidadDoughnutAprobado);

                mysqli_stmt_fetch($stmtDoughnutAprobado);

                $totalDoughnutAprobado = $cantidadDoughnutAprobado;

                mysqli_stmt_close($stmtDoughnutAprobado);

                // Consulta para 'PENDIENTE'
                $queryDoughnutPendiente = "SELECT COUNT(*) as cantidad FROM `cotiz` WHERE YEAR(`fecha_creacion`) = $año AND estado = 'PENDIENTE'";

                $stmtDoughnutPendiente = mysqli_prepare($conn, $queryDoughnutPendiente);

                mysqli_stmt_execute($stmtDoughnutPendiente);

                mysqli_stmt_bind_result($stmtDoughnutPendiente, $cantidadDoughnutPendiente);

                mysqli_stmt_fetch($stmtDoughnutPendiente);

                $totalDoughnutPendiente = $cantidadDoughnutPendiente;

                mysqli_stmt_close($stmtDoughnutPendiente);

                // Calcular la suma de las variables
                $sumaCantidades = $totalDoughnutAprobado + $totalDoughnutPendiente;
            ?>

            <canvas id="myDoughnutChart"></canvas>
            <script>
                const dataDoughnut = {
                    labels: ['SI', 'NO'],
                    datasets: [
                        {
                            data: [<?php echo $totalDoughnutAprobado; ?>, <?php echo $totalDoughnutPendiente; ?>],
                            backgroundColor: ['rgba(20, 124, 23, 0.5)', 'rgba(255, 0, 0, 0.5)'], 
                            borderColor: ['rgba(20, 124, 23, 1)', 'rgba(255, 0, 0, 1)'],
                            borderWidth: 1
                        }
                    ]
                };

                const totalText = 'Total: <?php echo $sumaCantidades; ?>';

                const configDoughnut = {
                    type: 'doughnut',
                    data: dataDoughnut,
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'ACUMULADO AÑO <?php echo date("Y"); ?>',
                                position: 'top'
                            },
                            datalabels: {
                                color: '#000',
                                anchor: 'center',
                                align: 'center',
                                offset: 0,
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                formatter: function(value, context) {
                                    return context.chart.data.labels[context.dataIndex] === 'NO' ? totalText : '';
                                },
                                text: totalText // Add this line to set the totalText
                            }
                        },
                        responsive: true,
                    }
                };

                const ctxDoughnut = document.getElementById('myDoughnutChart').getContext('2d');
                const myDoughnutChart = new Chart(ctxDoughnut, configDoughnut);
            </script>
        </div>
        <div class="grid-item">
            <!--QUINTO DIV 5-->
            <?php
                // Modifica las variables y consulta SQL según tus necesidades
                $otDoughnut = "SELECT equipo, patente, COUNT(*) as cantidad 
                                FROM `detallle_ot` 
                                WHERE YEAR(`fecha_creacion`) = $año 
                                AND patente = '' 
                                GROUP BY equipo";
                $rOtDoughnut = mysqli_query($conn, $otDoughnut);

                // Calcular la suma total de cantidades
                $sumaTotalDoughnut = 0;
                while ($filaDoughnut = mysqli_fetch_assoc($rOtDoughnut)) {
                    $sumaTotalDoughnut += $filaDoughnut['cantidad'];
                }

                // Reiniciar el conjunto de resultados para el bucle principal
                mysqli_data_seek($rOtDoughnut, 0);
            ?>

            <canvas id="myChartDoughnut"></canvas>

            <script>
                // 1. Obtener y procesar los datos de la consulta SQL para Doughnut
                const equiposDoughnut = [];
                const cantidadesDoughnut = [];

                <?php
                while ($filaDoughnut = mysqli_fetch_assoc($rOtDoughnut)) {
                    echo "equiposDoughnut.push('{$filaDoughnut['equipo']}');\n";
                    echo "cantidadesDoughnut.push({$filaDoughnut['cantidad']});\n";
                }
                ?>

                // 2. Configurar el objeto 'data' para el gráfico de Doughnut
                const DATA_Doughnut = {
                    labels: equiposDoughnut, // Nombres de los equipos
                    datasets: [{
                        data: cantidadesDoughnut, // Cantidades de cada equipo
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Color de fondo de las barras
                        borderColor: 'rgba(255, 99, 132, 1)', // Color del borde de las barras +++++
                        borderWidth: 1 // Ancho del borde de las barras
                    }]
                };

                // 3. Configurar el objeto 'options' para el gráfico de Doughnut
                const OPTIONS_Doughnut = {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Ocultar completamente la leyenda
                        },
                        title: {
                            display: true,
                            text: 'ACUMULADO (O) AÑO <?php echo date("Y"); ?>'
                        },
                        subtitle: {
                            display: true,
                            text: 'TOTAL: <?php echo $sumaTotalDoughnut; ?>'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value, index, values) {
                                    // Mostrar el nombre del equipo al hacer hover sobre la barra
                                    return index + 1;
                                }
                            }
                        }
                    }
                };

                // 4. Configurar el objeto 'config' con las opciones del gráfico de Doughnut
                const CONFIG_Doughnut = {
                    type: 'bar',
                    data: DATA_Doughnut,
                    options: OPTIONS_Doughnut
                };

                // 5. Crear el gráfico de Doughnut utilizando Chart.js
                const CTX_Doughnut = document.getElementById('myChartDoughnut').getContext('2d');
                const MYCHART_Doughnut = new Chart(CTX_Doughnut, CONFIG_Doughnut);
            </script>
        </div>
        <div class="grid-item">
            <!--SEXTO DIV 6-->
            <?php
                // Modifica las variables y consulta SQL según tus necesidades
                $otDoughnut_clone = "SELECT equipo, patente, COUNT(*) as cantidad 
                                    FROM `detallle_ot` 
                                    WHERE YEAR(`fecha_creacion`) = $año 
                                    AND patente != '' 
                                    GROUP BY equipo";
                $rOtDoughnut_clone = mysqli_query($conn, $otDoughnut_clone);

                // Calcular la suma total de cantidades
                $sumaTotalDoughnut_clone = 0;
                while ($filaDoughnut_clone = mysqli_fetch_assoc($rOtDoughnut_clone)) {
                    $sumaTotalDoughnut_clone += $filaDoughnut_clone['cantidad'];
                }

                // Reiniciar el conjunto de resultados para el bucle principal
                mysqli_data_seek($rOtDoughnut_clone, 0);
            ?>

            <canvas id="myChartDoughnut_clone"></canvas>

            <script>
                // 1. Obtener y procesar los datos de la consulta SQL para Doughnut
                const equiposDoughnut_clone = [];
                const cantidadesDoughnut_clone = [];

                <?php
                while ($filaDoughnut_clone = mysqli_fetch_assoc($rOtDoughnut_clone)) {
                    echo "equiposDoughnut_clone.push('{$filaDoughnut_clone['equipo']}');\n";
                    echo "cantidadesDoughnut_clone.push({$filaDoughnut_clone['cantidad']});\n";
                }
                ?>

                // 2. Configurar el objeto 'data' para el gráfico de Doughnut
                const DATA_Doughnut_clone = {
                    labels: equiposDoughnut_clone, // Nombres de los equipos
                    datasets: [{
                        data: cantidadesDoughnut_clone, // Cantidades de cada equipo
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras +++++
                        borderWidth: 1 // Ancho del borde de las barras
                    }]
                };

                // 3. Configurar el objeto 'options' para el gráfico de Doughnut
                const OPTIONS_Doughnut_clone = {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false, // Ocultar completamente la leyenda
                        },
                        title: {
                            display: true,
                            text: 'ACUMULADO (M) AÑO <?php echo date("Y"); ?>'
                        },
                        subtitle: {
                            display: true,
                            text: 'TOTAL: <?php echo $sumaTotalDoughnut_clone; ?>'
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value, index, values) {
                                    // Mostrar el nombre del equipo al hacer hover sobre la barra
                                    return index + 1;
                                }
                            }
                        }
                    }
                };

                // 4. Configurar el objeto 'config' con las opciones del gráfico de Doughnut
                const CONFIG_Doughnut_clone = {
                    type: 'bar',
                    data: DATA_Doughnut_clone,
                    options: OPTIONS_Doughnut_clone
                };

                // 5. Crear el gráfico de Doughnut utilizando Chart.js
                const CTX_Doughnut_clone = document.getElementById('myChartDoughnut_clone').getContext('2d');
                const MYCHART_Doughnut_clone = new Chart(CTX_Doughnut_clone, CONFIG_Doughnut_clone);
            </script>

        </div>  
        <div class="grid-item full-width">
        <!--SEPTIMO DIV 7-->
            <?php
                $resultados_por_mes = array();

                for ($mes = 1; $mes <= 12; $mes++) {
                    $query = "SELECT COUNT(*) as cantidad FROM `cotiz` WHERE estado ='APROBADO' AND MONTH(fecha_creacion) = $mes AND YEAR(fecha_creacion) = $año";
                    $cotizaciones_query = mysqli_query($conn, $query);

                    // Verificar si hay resultados antes de intentar extraer el valor
                    if ($cotizaciones_query) {
                        $row = mysqli_fetch_assoc($cotizaciones_query);
                        $resultados_por_mes[$mes] = $row['cantidad'];
                    } else {
                        $resultados_por_mes[$mes] = 0; // Si no hay resultados, establecer la cantidad en 0
                    }
                }

                // Generar cadena de datos para el gráfico
                $data_cadena = '[' . implode(', ', $resultados_por_mes) . ']';

                $resultados_por_mes_cert = array();

                for($mes = 1; $mes <= 12; $mes++){
                    $query_cet = "SELECT COUNT(*) as cant FROM `detallle_ot` WHERE MONTH(fecha_creacion) = $mes AND YEAR(fecha_creacion) = $año AND patente =''";
                    $cert_query = mysqli_query($conn, $query_cet);

                    if($cert_query){
                        $Row = mysqli_fetch_assoc($cert_query);
                        $resultados_por_mes_cert[$mes] = $Row['cant'];
                    }else{
                        $resultados_por_mes_cert[$mes] = 0;
                    }
                }

                $data_cadena_cert = '[' . implode(', ', $resultados_por_mes_cert) . ']';
            ?>
            <?php
                $resultados_por_mes_clone = array();

                for ($mes_clone = 1; $mes_clone <= 12; $mes_clone++) {
                    $query_clone = "SELECT COUNT(*) as cantidad FROM `cotiz` WHERE estado ='APROBADO' AND MONTH(fecha_creacion) = $mes_clone AND YEAR(fecha_creacion) = $año";
                    $cotizaciones_query_clone = mysqli_query($conn, $query_clone);

                    // Verificar si hay resultados antes de intentar extraer el valor
                    if ($cotizaciones_query_clone) {
                        $row_clone = mysqli_fetch_assoc($cotizaciones_query_clone);
                        $resultados_por_mes_clone[$mes_clone] = $row_clone['cantidad'];
                    } else {
                        $resultados_por_mes_clone[$mes_clone] = 0; // Si no hay resultados, establecer la cantidad en 0
                    }
                }

                // Generar cadena de datos para el gráfico
                $data_cadena_clone = '[' . implode(', ', $resultados_por_mes_clone) . ']';

                $resultados_por_mes_cert_clone = array();

                for ($mes_clone = 1; $mes_clone <= 12; $mes_clone++) {
                    $query_cet_clone = "SELECT COUNT(*) as cant FROM `detallle_ot` WHERE MONTH(fecha_creacion) = $mes_clone AND YEAR(fecha_creacion) = $año AND patente !=''";
                    $cert_query_clone = mysqli_query($conn, $query_cet_clone);

                    if ($cert_query_clone) {
                        $row_cert_clone = mysqli_fetch_assoc($cert_query_clone);
                        $resultados_por_mes_cert_clone[$mes_clone] = $row_cert_clone['cant'];
                    } else {
                        $resultados_por_mes_cert_clone[$mes_clone] = 0;
                    }
                }

                $data_cadena_cert_clone = '[' . implode(', ', $resultados_por_mes_cert_clone) . ']';

                $resultados_por_mes_eva = array();

                for ($mes_clone_eva = 1; $mes_clone_eva <= 12; $mes_clone_eva++) {
                    $query_eva_clone = "SELECT SUM(cantidad) as cant FROM `serviceCot` WHERE MONTH(fecha_creacion) = $mes_clone_eva AND YEAR(fecha_creacion) = $año AND estado ='A' AND tipo = 'E'";
                    $eva_query_clone = mysqli_query($conn, $query_eva_clone);

                    if ($eva_query_clone) {
                        $row_eva_clone = mysqli_fetch_assoc($eva_query_clone);
                        $resultados_por_mes_eva[$mes_clone_eva] = $row_eva_clone['cant'];
                    } else {
                        $resultados_por_mes_eva[$mes_clone_eva] = 0;
                    }
                }

                $data_cadena_eva_clone = '[' . implode(', ', $resultados_por_mes_eva) . ']';

            ?>
            <canvas id="myChartL" style="width: 100%; height:100%;"></canvas>

            <script>
                const dataL = {
                    // Meses del año
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembe'],
                    datasets: [
                        {
                            label: 'Certificaciones',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_cert; ?>, // Dos barras por mes
                            type: 'bar', // Tipo de gráfico de barras
                        },
                        {
                            label: 'Inspecciones',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_cert_clone;?>, // Dos barras por mes
                            type: 'bar', // Tipo de gráfico de barras
                        },
                        {
                            label: 'Evaluaciones',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: <?php echo $data_cadena_eva_clone;?>, // Dos barras por mes
                            type: 'bar', // Tipo de gráfico de barras
                        },
                        {
                            label: 'Cotizaciones',
                            borderColor: 'rgba(20, 124, 23, 1)',
                            borderWidth: 2,
                            data: <?php echo $data_cadena; ?>,
                            fill: false, // No rellenar debajo de la línea
                            type: 'line', // Tipo de gráfico de línea
                        }
                    ]
                };

                const configL = {
                    type: 'bar', // Puedes cambiar a 'line' si prefieres que el gráfico sea solo de línea
                    data: dataL,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'GRÁFICO DE OT vs CT'
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
    </div>
</body>
<?php
    // Cierra la conexión a la base de datos
    mysqli_close($conn);
?>
</html>