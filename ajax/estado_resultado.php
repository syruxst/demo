<?php
    session_start();
    error_reporting(1);

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
    $ano = date("Y");
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
    <!-- Agrega esto en la sección head de tu HTML -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@2.8.0"></script>


    <title>DataBoard</title>
    <style>
    body {
        margin: 0;
        padding: 10px;
        font-family: Arial, Helvetica, sans-serif;
        overflow: hidden; /* Evita barras de desplazamiento en el cuerpo de la página */
    }

    .grid-container {
        display: grid;
        gap: 1px;
        height: 95vh;
        grid-template-rows: repeat(3, 1fr);
    }

    .grid-container > div {
        max-width: 100%; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .ventas {
        width: 200px;
        height: 50px;
        padding: 5px;
        margin: 10px;
        background-color: rgba(255, 99, 132, 0.1);
        border: 1px solid rgba(255, 99, 132, 0.5);
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .kpi {
        width: 200px;
        height: 50px;
        padding: 5px;
        margin: 10px;
        background-color: rgba(20, 124, 23, 0.1);
        border: 1px solid rgba(20, 124, 23, 0.5);
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    #contenedor {
      width: 100%;
      display: table;
    }

    #fila {
      display: table-row;
    }

    #columna1, #columna2 {
      display: table-cell;
      border: 1px solid red;
      padding: 10px;
    }

    #columna1 {
      width: 80%;
    }

    #columna2 {
      width: 20%;
    }
    </style>
</head>
<body>
<div class="grid-container">
    <div>
        <!-- div de ventas -->
        <table border="0" style="width: 80%; height: 100%;">
            <tr>
                <td width="85%">
                    <?php 
                        // ventas reales
                        // Inicializar array para almacenar datos
                        $data_cadena = array_fill(0, 12, 0);

                        // Construir la consulta SQL con REPLACE para quitar puntos
                        $sale = "SELECT MONTH(fecha_creacion) AS mes, SUM(REPLACE(total, '.', '')) AS suma_total FROM `serviceCot` WHERE estado = 'A' GROUP BY mes";
                        $rst_sale = $conn->query($sale);
                    
                        // Iterar sobre los resultados
                        while ($sale = mysqli_fetch_array($rst_sale)) {
                            $mes = intval($sale['mes']) - 1;
                            $total_sin_puntos = $sale['suma_total'];
                    
                            // Sumar los valores por mes
                            $data_cadena[$mes] += $total_sin_puntos;
                        }
                    
                        // Convertir array a formato JSON para usar en JavaScript
                        $data_cadena_json = json_encode($data_cadena);


                        // promedio por servicio
                        $query_cet = "SELECT ROUND(AVG(valor)) AS promedio_total
                        FROM `servicios`
                        WHERE (nombre='CERT OPERADOR 3 (51- En Adelante)' 
                            OR nombre='CERT OPERADOR 2 (21-50)' 
                            OR nombre='CERT OPERADOR 1 (1-20)')";
            
                        // Ejecutar la consulta
                        $result_cet = $conn->query($query_cet);
            
                        // Verificar si hay resultados
                        if ($result_cet->num_rows > 0) {
                        // Obtener el resultado como un array asociativo
                        $row_cet = $result_cet->fetch_assoc();
            
                        // Mostrar el promedio como número entero
                        $promedio_cet = $row_cet['promedio_total'];
                        } else {
                        }

                        
                        $query_insp = "SELECT ROUND(AVG(valor)) AS promedio_total_inps
                        FROM `servicios`
                        WHERE (nombre='CERT EQUIPO 1 (1-10)' 
                            OR nombre='CERT EQUIPO 2 (11-20)' 
                            OR nombre='CERT EQUIPO 3 (21-En Adelante)' 
                            OR nombre='CERT EQUIPO ESTACINARIO')";
            
                        // Ejecutar la consulta
                        $result_insp = $conn->query($query_insp);
            
                        // Verificar si hay resultados
                        if ($result_insp->num_rows > 0) {
                        // Obtener el resultado como un array asociativo
                        $row_insp = $result_insp->fetch_assoc();
            
                        // Mostrar el promedio como número entero
                        $promedio_insp = $row_insp['promedio_total_inps'];
                        } else {
                        }

                        $query_eva = "SELECT ROUND(AVG(valor)) AS promedio_total_eva
                        FROM `servicios`
                        WHERE (nombre='EVA LEY 1 CHV (EXC-ALJIBE-RETROEX)' 
                            OR nombre='EVA LEY 2 CHV (BULL-TTES PERSONAL)' 
                            OR nombre='EVA LEY 3 CHV (MOTO-TOLVA-CARGADOR-EQ. MOVILES)' 
                            OR nombre='EVA LEY 5 CHV (RIGGER ALTA)'
                            OR nombre='EVA LEY 4 CHV (MANIPULADOR-HORQUILLA-ALZA HOMBRE)')";
            
                        // Ejecutar la consulta
                        $result_eva = $conn->query($query_eva);
            
                        // Verificar si hay resultados
                        if ($result_eva->num_rows > 0) {
                        // Obtener el resultado como un array asociativo
                        $row_eva = $result_eva->fetch_assoc();
            
                        // Mostrar el promedio como número entero
                        $promedio_eva = $row_eva['promedio_total_eva'];
                        } else {
                        }

                        $query_mod = "SELECT ROUND(AVG(valor)) AS promedio_total_mod
                        FROM `servicios`
                        WHERE nombre='CERT OPERADOR (Modelo Adicional)'";
            
                        // Ejecutar la consulta
                        $result_mod = $conn->query($query_mod);
            
                        // Verificar si hay resultados
                        if ($result_mod->num_rows > 0) {
                        // Obtener el resultado como un array asociativo
                        $row_mod = $result_mod->fetch_assoc();
            
                        // Mostrar el promedio como número entero
                        $promedio_mod = $row_mod['promedio_total_mod'];
                        } else {
                        }

                        $query_sum = "SELECT ROUND(AVG(valor)) AS promedio_total_sum
                        FROM `servicios`
                        WHERE nombre='SUM. OPERADOR'";
            
                        // Ejecutar la consulta
                        $result_sum = $conn->query($query_sum);
            
                        // Verificar si hay resultados
                        if ($result_sum->num_rows > 0) {
                        // Obtener el resultado como un array asociativo
                        $row_sum = $result_sum->fetch_assoc();
            
                        // Mostrar el promedio como número entero
                        $promedio_sum = $row_sum['promedio_total_sum'];
                        } else {
                        }




                        // datos para promediar con kpi
                        $sql_servicios = "SELECT ROUND(AVG(valor)) AS promedio FROM servicios";
                        $resultado = $conn->query($sql_servicios);

                        if ($resultado) {
                            $fila = $resultado->fetch_assoc();
                            $promedio = $fila['promedio'];
                        } else {
                            echo "Error en la consulta: " . $conn->error;
                        }

                        // Obtener datos para el gráfico de línea (KPI)
                        $query_kpi = "SELECT 
                                        mes,
                                        certificacion_op, 
                                        evaluacion,
                                        suministros_op,
                                        inspeccion,
                                        modelo_adicional
                                        FROM 
                                        kpi_table
                                        WHERE 
                                        mes BETWEEN 1 AND 12
                                        ORDER BY 
                                        mes";

                        $result_kpi = $conn->query($query_kpi);

                        // Inicializar array para almacenar datos de KPI
                        $data_cadena_kpi = array_fill(0, 12, 0);

                        // Rellenar array con la suma de las columnas
                        while ($row_kpi = $result_kpi->fetch_assoc()) {
                        $mes_kpi = intval($row_kpi['mes']) - 1;
                        $certificacion_op_kpi = intval($row_kpi['certificacion_op']) * $promedio_cet;
                        $evaluacion_kpi = intval($row_kpi['evaluacion']) * $promedio_eva;
                        $suministros_op_kpi = intval($row_kpi['suministros_op']) * $promedio_sum;
                        $inspeccion_kpi = intval($row_kpi['inspeccion']) * $promedio_insp;
                        $modelo_adicional_kpi = intval($row_kpi['modelo_adicional']) * $promedio_mod;

                        // Sumar los valores de las columnas
                        $data_cadena_kpi[$mes_kpi] += $certificacion_op_kpi + $evaluacion_kpi + $suministros_op_kpi + $inspeccion_kpi + $modelo_adicional_kpi;
                        }

                        // Convertir array a formato JSON para usar en JavaScript
                        $data_cadena_kpi_json = json_encode($data_cadena_kpi);
                    ?>

                    <canvas id="myChartVenta" style="width: 100%; height: 100%;"></canvas>
        
                    <script>
                        const dataL = {
                            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            datasets: [
                                {
                                    label: 'REAL',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1,
                                    data: <?php echo $data_cadena_json; ?>,
                                    type: 'line',
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
                                        text: 'GRAFICO DE VENTAS'
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

                        const ctxL = document.getElementById('myChartVenta').getContext('2d');
                        new Chart(ctxL, configL);
                    </script>
            </td>
            <td width="15%" align="center">
                <!-- acumulado al mes en curso -->
                <?php
                    echo '<span style="font-size: 14px;">ACUMULADO HASTA ' .$mesActualMayuscula .'</span>';
                    // Calcular acumulado hasta el mes actual
                    $acumulado_ventas = 0;
                    $acumulado_kpi = 0;
                    
                    // Recorrer los datos de KPI y ventas hasta el mes actual
                    for ($i = 0; $i <= date('n') - 1; $i++) {
                        $acumulado_ventas += $data_cadena[$i]; // Sumar el valor de ventas promedio
                        $acumulado_kpi += $data_cadena_kpi[$i]; // Sumar el valor de KPI
                    }

                    // Calcular la diferencia en porcentaje
                    $diferencia_porcentaje = ($acumulado_ventas - $acumulado_kpi) / $acumulado_kpi * 100;

                    // Determinar la dirección de la flecha y el color
                    $flecha = ($acumulado_ventas >= $acumulado_kpi) ? '↑' : '↓';
                    $color = ($acumulado_ventas >= $acumulado_kpi) ? 'green' : 'red';
                ?>
                <div class="ventas">
                    <?php
                        echo "REAL: $ " . number_format($acumulado_ventas, 0, ',', '.');
                    ?>
                </div>
                <div class="kpi">
                    <?php
                        echo "KPI: $ " . number_format($acumulado_kpi, 0, ',', '.');
                    ?>
                </div>
                <div style="color: <?php echo $color; ?>;">
                    <?php
                        echo "Diferencia: " . number_format(abs($diferencia_porcentaje), 2) . "% " . $flecha;
                    ?>
                </div>
            </td>
            </tr>
        </table>
    </div> 
    <div>
        <table border="0" style="width: 80%; height: 100%;">
            <tr>
                <td width="70%">
                    <?php

                    // Obtener datos para el gráfico de línea (REAL)
                    $query_real = "SELECT * FROM `costos_real_$ano` WHERE mes BETWEEN 1 AND 12 ORDER BY mes";

                    $result_real = $conn->query($query_real);

                    // Inicializar array para almacenar datos de REAL
                    $data_cadena_real = array_fill(0, 12, 0);

                    // Rellenar array con la suma de las columnas
                    while ($row_real = $result_real->fetch_assoc()) {
                        $mes_real = intval($row_real['mes']) - 1;
                        $mano_de_obra_real = intval($row_real['mano_de_obra']);
                        $evaluador_cet_oper_real = intval($row_real['evaluador_cet_oper']);
                        $evaluador_chilevalora_real = intval($row_real['evaluador_chilevalora']);
                        $inspector_real = intval($row_real['inspector']);
                        $arriendo_maq_real = intval($row_real['arriendo_maq']);
                        $arriendo_oficina_real = intval($row_real['arriendo_ofcina']);
                        $contador_real = intval($row_real['contador']);
                        $redes_real = intval($row_real['redes']);
                        $ti_real = intval($row_real['ti']);
                        $gc_real = intval($row_real['gc']);
                        $viatico_real = intval($row_real['viatico']);
                        $telefonia_real = intval($row_real['telefonia']);
                        $art_oficina_real = intval($row_real['art_oficina']);
                        $credito_banco_real = intval($row_real['credito_banco']);
                        $inv_acreditacion_real= intval($row_real['inv_acreditacion']);
                        $retiro_utilidades_real = intval($row_real['retiro_utilidades']);
                        $bono_personal_real = intval($row_real['bono_personal']);
                        $varios_real = intval($row_real['varios']);

                        // Sumar los valores de las columnas
                        $data_cadena_real[$mes_real] += $mano_de_obra_real + $evaluador_cet_oper_real + $evaluador_chilevalora_real + $inspector_real + $arriendo_maq_real + $arriendo_oficina_real + $contador_real + $redes_real + $ti_real + $gc_real + $viatico_real + $telefonia_real + $art_oficina_real + $credito_banco_real + $inv_acreditacion_real + $retiro_utilidades_real + $bono_personal_real + $varios_real;
                    }

                    // Convertir array a formato JSON para usar en JavaScript
                    $data_cadena_real_json = json_encode($data_cadena_real);

                    // Obtener datos para el gráfico de línea (COSTO)
                    $query_costo = "SELECT * FROM kpi_costos_$ano WHERE mes BETWEEN 1 AND 12 ORDER BY mes";

                    $result_costo = $conn->query($query_costo);

                    // Inicializar array para almacenar datos de COSTO
                    $data_cadena_costo = array_fill(0, 12, 0);

                    // Rellenar array con la suma de las columnas
                    while ($row_costo = $result_costo->fetch_assoc()) {
                        $mes_costo = intval($row_costo['mes']) - 1;
                        $mano_de_obra = intval($row_costo['mano_de_obra']);
                        $evaluador_cet_oper = intval($row_costo['evaluador_cet_oper']);
                        $evaluador_chilevalora = intval($row_costo['evaluador_chilevalora']);
                        $inspector = intval($row_costo['inspector']);
                        $arriendo_maq = intval($row_costo['arriendo_maq']);
                        $arriendo_oficina = intval($row_costo['arriendo_ofcina']);
                        $contador = intval($row_costo['contador']);
                        $redes = intval($row_costo['redes']);
                        $ti = intval($row_costo['ti']);
                        $gc = intval($row_costo['gc']);
                        $viatico = intval($row_costo['viatico']);
                        $telefonia = intval($row_costo['telefonia']);
                        $art_oficina = intval($row_costo['art_oficina']);
                        $credito_banco = intval($row_costo['credito_banco']);
                        $inv_acreditacion = intval($row_costo['inv_acreditacion']);
                        $retiro_utilidades = intval($row_costo['retiro_utilidades']);
                        $bono_personal = intval($row_costo['bono_personal']);
                        $varios = intval($row_costo['varios']);

                        // Sumar los valores de las columnas
                        $data_cadena_costo[$mes_costo] += $mano_de_obra + $evaluador_cet_oper + $evaluador_chilevalora + $inspector + $arriendo_maq + $arriendo_oficina + $contador + $redes + $ti + $gc + $viatico + $telefonia + $art_oficina + $credito_banco + $inv_acreditacion + $retiro_utilidades + $bono_personal + $varios;
                    }

                    // Convertir array a formato JSON para usar en JavaScript
                    $data_cadena_costo_json = json_encode($data_cadena_costo);
                    ?>

                    <canvas id="myChartCostos" style="width: 100%; height: 100%;"></canvas>

                    <script>
                        const dataLCosto = {
                            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            datasets: [
                                {
                                    label: 'REAL',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1,
                                    data: <?php echo $data_cadena_real_json; ?>,
                                    type: 'line',
                                },
                                {
                                    label: 'KPI',
                                    borderColor: 'rgba(20, 124, 23, 1)',
                                    borderWidth: 1,
                                    data: <?php echo $data_cadena_costo_json; ?>,
                                    fill: false, 
                                    type: 'line', 
                                }
                            ]
                        };

                        const configLCosto = {
                            type: 'bar',
                            data: dataLCosto,
                            options: {
                                responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        title: {
                                            display: true,
                                            text: 'GRAFICO DE COSTOS'
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

                    const ctxLCosto = document.getElementById('myChartCostos').getContext('2d');
                    new Chart(ctxLCosto, configLCosto);
                    </script>
                </td>
                
                <td width="30%" align="center">
                    <!-- acumulado al mes en curso -->
                    <?php
                        echo '<span style="font-size: 14px;">ACUMULADO HASTA ' .$mesActualMayuscula .'</span>';
                        // Calcular acumulado hasta el mes actual
                        $acumulado_real = 0;
                        $acumulado_costo = 0;
                        
                        // Recorrer los datos de KPI y ventas hasta el mes actual
                        for ($i = 0; $i <= date('n') - 1; $i++) {
                            $acumulado_real += $data_cadena_real[$i]; // Sumar el valor de ventas promedio
                            $acumulado_costo += $data_cadena_costo[$i]; // Sumar el valor de KPI
                        }

                        // Calcular la diferencia en porcentaje
                        $diferencia_porcentaje_clone = ($acumulado_real - $acumulado_costo) / $acumulado_costo * 100;

                        // Determinar la dirección de la flecha y el color
                        $flecha_clone = ($acumulado_real >= $acumulado_costo) ? '↑' : '↓';
                        $color_clone = ($acumulado_real >= $acumulado_costo) ? 'green' : 'red';
                    ?>
                    <div class="ventas">
                        <?php
                            echo "REAL: $ " . number_format($acumulado_real, 0, ',', '.');
                        ?>
                    </div>
                    <div class="kpi">
                        <?php
                            echo "KPI: $ " . number_format($acumulado_costo, 0, ',', '.');
                        ?>
                    </div>
                    <div style="color: <?php echo $color_clone; ?>;">
                        <?php
                            echo "Diferencia: " . number_format(abs($diferencia_porcentaje_clone), 2) . "% " . $flecha_clone;
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div> 
    <div>
        prueba
    </div> 
</div>
</body>
<?php
    // Cierra la conexión a la base de datos
    mysqli_close($conn);
?>
</html>