<?php
session_start();
error_reporting(0);
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
        $rut = $row['rut'];
        $cta = $row['tipocta'];
        $banco = $row['banco'];
        $numerocta = $row['cta'];
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si los datos 'inicio' y 'fin' están presentes en la solicitud
    if (isset($_POST["inicio"]) && isset($_POST["fin"])) {
        $Tipo = $_POST['tipo'];
        $fechaInicio = $_POST["inicio"];
        $fechaFin = $_POST["fin"];

        $nuevaFechaInicio = date("Y-m-d 00:00:00", strtotime($fechaInicio));
        $nuevaFechaFin = date("Y-m-d 23:59:59", strtotime($fechaFin));

        if($Tipo == 'O'){
                // Buscar rango de fechas
                $busqueda1 = mysqli_query($conn, "SELECT * FROM `document` WHERE `user` = '$usuario' AND tipo='O'");
                $num_registros = mysqli_num_rows($busqueda1);
                $bus_date = mysqli_fetch_array($busqueda1);
                
                $mensaje = "";
                
                if ($num_registros > 0) {
                    $date_in = $bus_date['date_in'];
                    $date_out = $bus_date['date_out'];

                    // Convertir las fechas a timestamp
                    $timestampFechaInicio = strtotime($nuevaFechaInicio);
                    $timestampFechaFin = strtotime($nuevaFechaFin);
                    $timestampDateIn = strtotime($date_in);
                    $timestampDateOut = strtotime($date_out);

                    // Verificar si $date_in y $date_out están dentro del rango
                    if ($timestampDateIn >= $timestampFechaInicio && $timestampDateIn <= $timestampFechaFin &&
                        $timestampDateOut >= $timestampFechaInicio && $timestampDateOut <= $timestampFechaFin) {
                        
                        // Ambas fechas están dentro del rango
                        $mensaje = "Ambas fechas están dentro del rango.";
                        
                    } elseif ($timestampDateIn >= $timestampFechaInicio && $timestampDateIn <= $timestampFechaFin) {
                        // Solo $date_in está dentro del rango
                        $mensaje = "Solo la fecha de inicio está fuera del rango.";
                    } elseif ($timestampDateOut >= $timestampFechaInicio && $timestampDateOut <= $timestampFechaFin) {
                        // Solo $date_out está dentro del rango
                        $mensaje = "Solo la fecha de fin está fuera del rango.";
                    } else {
                        // Ambas fechas están fuera del rango
                                // Simulemos una búsqueda y devolvamos resultados de ejemplo
                                $resultados = "Resultados para el período: " . date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($nuevaFechaFin)) . "";
                                echo '<div id="elemento-a-capturar">';
                                echo $resultados;

                                $query = "SELECT * FROM `informes` WHERE `fechaInforme` BETWEEN '$nuevaFechaInicio' AND '$nuevaFechaFin' AND `userInforme` = '$usuario'";
                                $result = mysqli_query($conn, $query);

                                $num_registros = mysqli_num_rows($result);

                                if ($num_registros > 0) {
                                    // Si hay resultados, muestra una tabla
                                    echo ". Se encontraron $num_registros registros.";
                                    echo '<hr>';
                                    echo '<table width="100%" border="0" class="table table-responsive">
                                            <tr>
                                                <th>N° FOLIO</th>
                                                <th>FECHA</th>
                                                <th>OPERADOR</th>
                                                <th>EMPRESA</th>
                                                <th>RESULTADO</th>
                                            </tr>';

                                    $resultadosFechas = array();

                                    while ($row = mysqli_fetch_array($result)) {

                                        $fechaInforme = date("d-m-Y", strtotime($row['fecha']));

                                        $enterprice = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id= '" . $row['IdOper'] . "' ");
                                        $fila = mysqli_fetch_array($enterprice);
                                        $empresa = $fila['empresa'];
                                        $codigo = $row['folio'];

                                        $icon = ($row['resultado'] == "APROBADO") ? '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="APROBADO"></i>' : (($row['resultado'] == "RECHAZADO") ? '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: red;" title="REPROBADO"></i' : '');

                                        echo '<tr>';
                                        echo '<td> ' . $fila['ip'] . ' ' . $fila['folio'] . '</td>';
                                        echo '<td>' . $fechaInforme . '</td>';
                                        echo '<td align="left">' . $row['nombre_candidato'] . '</td>';
                                        echo '<td> ' . $empresa . '</td>';
                                        echo '<td>' . $icon . '</td>';
                                        echo '</tr>';

                                        // Almacena la fecha en el array solo si no está presente
                                        if (!in_array($fechaInforme, $resultadosFechas)) {
                                            $resultadosFechas[] = $fechaInforme;
                                        }
                                    }

                                    echo '</table>';
                                    echo '<hr>';

                                    $numFechasDistintas = count($resultadosFechas);

                                    //echo "Número de fechas distintas: " . $numFechasDistintas . "<br>";

                                    // Muestra las fechas distintas
                                    //echo "Fechas distintas: " . implode(', ', $resultadosFechas) . "<br>";

                                    $evaluador = mysqli_query($conn, "SELECT valor FROM `insp_eva` WHERE user = '$usuario'");
                                    $fila = mysqli_fetch_array($evaluador);
                                    $valor = $fila['valor'];
                                    $valorVisita = 30000;
                                    $totalVisita = $valorVisita * $numFechasDistintas;
                                    $totaL = $valor * $num_registros;
                                    $total = $totaL + $totalVisita;
                                    $Total = number_format($total, 0, ',', '.');

                                    echo '<div class="tabla">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">EVALUACIONES</span>
                                                        <input type="text" class="form-control" placeholder="' . $num_registros . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                        <input type="text" class="form-control" placeholder="' . number_format($totaL, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VISITAS</span>
                                                        <input type="text" class="form-control" placeholder="' . $numFechasDistintas . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                        <input type="text" class="form-control" placeholder="' . number_format($totalVisita, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div style="color: blue;"> TOTAL EVALUACIONES DEL PERIODO $ ' . $Total . '</div>
                                                </div>
                                                <div class="col"></div>
                                            </div>
                                    </div>';
                                    echo '</div>';
                                    echo '<form action="subirBoleta.php" method="POST" enctype="multipart/form-data" id="formularioBoleta">';
                                    echo '<div class="tabla">
                                            <div class="row">
                                                <div class="col">
                                                FORMULARIO PARA SUBIR BOLETA DE SERVICIO
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'">
                                                        <input type="hidden" id="date_in" name="date_in" value="' . $nuevaFechaInicio  . '">
                                                        <input type="hidden" id="date_out" name="date_out" value="' . $nuevaFechaFin . '">
                                                        <input type="hidden" id="codigo" name="codigo" value="' . $codigo . '">
                                                        <input type="hidden" id="numero" name="numero" value="' . $num_registros . '">
                                                        <input type="hidden" id="name" name="name" value="' . $nombre . '">
                                                        <input type="hidden" id="rut" name="rut" value="' . $rut . '">
                                                        <input type="hidden" id="cta" name="cta" value="' . $cta . '">
                                                        <input type="hidden" id="banco" name="banco" value="' . $banco . '">
                                                        <input type="hidden" id="numerocta" name="numerocta" value="' . $numerocta . '">
                                                        <input type="hidden" id="total" name="total" value="' . $Total . '">
                                                        <input type="hidden" id="visitas" name="visitas" value="' . $numFechasDistintas . '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="basic-addon1">N° BOLETA</span>
                                                        <input type="text" name="boleta" id="boleta" class="form-control" placeholder="Ingresar número de Boleta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="basic-addon1">Fecha Boleta</span>
                                                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">SUBIR BOLETA</span>
                                                        <input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">SUBIR RESPALDO</span>
                                                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="checkbox" id="btn-menu" name="acepto_valor" required> Acepto el valor indicado según el periodo.
                                                </div>
                                                <div class="col">
                                                   
                                                </div>
                                            </div>
                                            <div class="row"><div class="col">&nbsp;</div></div>
                                            <div class="row">
                                                <div class="col">
                                                    <button onclick="capturarPantalla()" class="btn btn-warning botones "><i class="fa fa-camera" aria-hidden="true"></i> CAPTURAR RESPALDO</button>
                                                    <input type="submit" name="subirboleta" id="subirboleta" class="btn btn-primary botones " value="ENVIAR">
                                                </div>
                                            </div>
                                        </div>';
                                    echo '<br>';
                                    echo '</form>';
                                
                                } else {
                                    // Si no hay resultados, muestra un mensaje
                                    echo ". No se encontraron registros para el período especificado.";
                                }
                    }

                    // Imprimir el mensaje
                    echo $mensaje;

                } else {
                    // Acción a realizar si no se encuentran resultados
                                            // Simulemos una búsqueda y devolvamos resultados de ejemplo
                                            $resultados = "Resultados para el período: " . date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($nuevaFechaFin)) . "";
                                            echo '<div id="elemento-a-capturar">';
                                            echo $resultados;
                    
                                            $query = "SELECT * FROM `informes` WHERE `fechaInforme` BETWEEN '$nuevaFechaInicio' AND '$nuevaFechaFin' AND `userInforme` = '$usuario'";
                                            $result = mysqli_query($conn, $query);
                    
                                            $num_registros = mysqli_num_rows($result);
                    
                                            if ($num_registros > 0) {
                                                // Si hay resultados, muestra una tabla
                                                echo ". Se encontraron $num_registros registros.";
                                                echo '<hr>';
                                                echo '<table width="100%" border="0" class="table table-striped responsive-font">
                                                        <tr>
                                                            <th>N° FOLIO</th>
                                                            <th>FECHA</th>
                                                            <th>OPERADOR</th>
                                                            <th>EMPRESA</th>
                                                            <th>RESULTADO</th>
                                                        </tr>';
                    
                                                $resultadosFechas = array();
                    
                                                while ($row = mysqli_fetch_array($result)) {
                    
                                                    $fechaInforme = date("d-m-Y", strtotime($row['fecha']));
                    
                                                    $enterprice = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id= '" . $row['IdOper'] . "' ");
                                                    $fila = mysqli_fetch_array($enterprice);
                                                    $empresa = $fila['empresa'];
                                                    $codigo = $row['folio'];
                    
                                                    $icon = ($row['resultado'] == "APROBADO") ? '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="APROBADO"></i>' : (($row['resultado'] == "RECHAZADO") ? '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: red;" title="REPROBADO"></i' : '');
                    
                                                    echo '<tr>';
                                                    echo '<td> ' . $fila['ip'] . ' ' . $fila['folio'] . '</td>';
                                                    echo '<td>' . $fechaInforme . '</td>';
                                                    echo '<td align="left">' . $row['nombre_candidato'] . '</td>';
                                                    echo '<td> ' . $empresa . '</td>';
                                                    echo '<td>' . $icon . '</td>';
                                                    echo '</tr>';
                    
                                                    // Almacena la fecha en el array solo si no está presente
                                                    if (!in_array($fechaInforme, $resultadosFechas)) {
                                                        $resultadosFechas[] = $fechaInforme;
                                                    }
                                                }
                    
                                                echo '</table>';
                                                echo '<hr>';
                    
                                                $numFechasDistintas = count($resultadosFechas);
                    
                                                //echo "Número de fechas distintas: " . $numFechasDistintas . "<br>";
                    
                                                // Muestra las fechas distintas
                                                //echo "Fechas distintas: " . implode(', ', $resultadosFechas) . "<br>";
                    
                                                $evaluador = mysqli_query($conn, "SELECT valor FROM `insp_eva` WHERE user = '$usuario'");
                                                $fila = mysqli_fetch_array($evaluador);
                                                $valor = $fila['valor'];
                                                $valorVisita = 30000;
                                                $totalVisita = $valorVisita * $numFechasDistintas;
                                                $totaL = $valor * $num_registros;
                                                $total = $totaL + $totalVisita;
                                                $Total = number_format($total, 0, ',', '.');
                    
                                                echo '<div class="tabla">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">EVALUACIONES</span>
                                                                    <input type="text" class="form-control" placeholder="' . $num_registros . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                                    <input type="text" class="form-control" placeholder="' . number_format($totaL, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VISITAS</span>
                                                                    <input type="text" class="form-control" placeholder="' . $numFechasDistintas . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                                    <input type="text" class="form-control" placeholder="' . number_format($totalVisita, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div style="color: blue;"> TOTAL EVALUACIONES DEL PERIODO $ ' . $Total . '</div>
                                                            </div>
                                                            <div class="col"></div>
                                                        </div>
                                                </div>';
                                                echo '</div>';
                                                echo '<form action="subirBoleta.php" method="POST" enctype="multipart/form-data" id="formularioBoleta">';
                                                echo '<div class="tabla">
                                                        <div class="row">
                                                            <div class="col">
                                                            FORMULARIO PARA SUBIR BOLETA DE SERVICIO
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'">
                                                                    <input type="hidden" id="date_in" name="date_in" value="' . $nuevaFechaInicio  . '">
                                                                    <input type="hidden" id="date_out" name="date_out" value="' . $nuevaFechaFin . '">
                                                                    <input type="hidden" id="codigo" name="codigo" value="' . $codigo . '">
                                                                    <input type="hidden" id="numero" name="numero" value="' . $num_registros . '">
                                                                    <input type="hidden" id="name" name="name" value="' . $nombre . '">
                                                                    <input type="hidden" id="rut" name="rut" value="' . $rut . '">
                                                                    <input type="hidden" id="cta" name="cta" value="' . $cta . '">
                                                                    <input type="hidden" id="banco" name="banco" value="' . $banco . '">
                                                                    <input type="hidden" id="numerocta" name="numerocta" value="' . $numerocta . '">
                                                                    <input type="hidden" id="total" name="total" value="' . $Total . '">
                                                                    <input type="hidden" id="visitas" name="visitas" value="' . $numFechasDistintas . '">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1">N° BOLETA</span>
                                                                    <input type="text" name="boleta" id="boleta" class="form-control" placeholder="Ingresar número de Boleta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1">Fecha Boleta</span>
                                                                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">SUBIR BOLETA</span>
                                                                    <input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">SUBIR RESPALDO</span>
                                                                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <input type="checkbox" id="btn-menu" name="acepto_valor" required> Acepto el valor indicado según el periodo.
                                                            </div>
                                                            <div class="col">
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="row"><div class="col">&nbsp;</div></div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <button onclick="capturarPantalla()" class="btn btn-warning botones "><i class="fa fa-camera" aria-hidden="true"></i> CAPTURAR RESPALDO</button>
                                                                <input type="submit" name="subirboleta" id="subirboleta" class="btn btn-primary botones " value="ENVIAR">
                                                            </div>
                                                        </div>
                                                    </div>';
                                                echo '<br>';
                                                echo '</form>';
                                            
                                            } else {
                                                // Si no hay resultados, muestra un mensaje
                                                echo ". No se encontraron registros para el período especificado.";
                                            }
                    
                }
        }else{
                // Buscar rango de fechas
                $busqueda1 = mysqli_query($conn, "SELECT * FROM `document` WHERE `user` = '$usuario' AND tipo='M'");
                $num_registros = mysqli_num_rows($busqueda1);
                $bus_date = mysqli_fetch_array($busqueda1);
                
                $mensaje = "";
                
                if ($num_registros > 0) {
                    $date_in = $bus_date['date_in'];
                    $date_out = $bus_date['date_out'];

                    // Convertir las fechas a timestamp
                    $timestampFechaInicio = strtotime($nuevaFechaInicio);
                    $timestampFechaFin = strtotime($nuevaFechaFin);
                    $timestampDateIn = strtotime($date_in);
                    $timestampDateOut = strtotime($date_out);

                    // Verificar si $date_in y $date_out están dentro del rango
                    if ($timestampDateIn >= $timestampFechaInicio && $timestampDateIn <= $timestampFechaFin &&
                        $timestampDateOut >= $timestampFechaInicio && $timestampDateOut <= $timestampFechaFin) {
                        
                        // Ambas fechas están dentro del rango
                        $mensaje = "Ambas fechas están dentro del rango.";
                        
                    } elseif ($timestampDateIn >= $timestampFechaInicio && $timestampDateIn <= $timestampFechaFin) {
                        // Solo $date_in está dentro del rango
                        $mensaje = "Solo la fecha de inicio está fuera del rango.";
                    } elseif ($timestampDateOut >= $timestampFechaInicio && $timestampDateOut <= $timestampFechaFin) {
                        // Solo $date_out está dentro del rango
                        $mensaje = "Solo la fecha de fin está fuera del rango.";
                    } else {
                        // Ambas fechas están fuera del rango
                                // Simulemos una búsqueda y devolvamos resultados de ejemplo
                                $resultados = "Resultados para el período: " . date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($nuevaFechaFin)) . "";
                                echo '<div id="elemento-a-capturar">';
                                echo $resultados;

                                $query = "SELECT * FROM `informesM` WHERE `fechaInforme` BETWEEN '$nuevaFechaInicio' AND '$nuevaFechaFin' AND `userInforme` = '$usuario'";
                                $result = mysqli_query($conn, $query);

                                $num_registros = mysqli_num_rows($result);

                                if ($num_registros > 0) {
                                    // Si hay resultados, muestra una tabla
                                    echo ". Se encontraron $num_registros registros.";
                                    echo '<hr>';
                                    echo '<table width="100%" border="0" class="table table-striped responsive-font">
                                            <tr>
                                                <th>N° FOLIO</th>
                                                <th>FECHA</th>
                                                <th>OPERADOR</th>
                                                <th>EMPRESA</th>
                                                <th>RESULTADO</th>
                                            </tr>';

                                    $resultadosFechas = array();

                                    while ($row = mysqli_fetch_array($result)) {

                                        $fechaInforme = date("d-m-Y", strtotime($row['fecha']));

                                        $enterprice = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id= '" . $row['IdOper'] . "' ");
                                        $fila = mysqli_fetch_array($enterprice);
                                        $empresa = $fila['empresa'];
                                        $codigo = $row['folio'];

                                        $icon = ($row['resultado'] == "APROBADO") ? '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="APROBADO"></i>' : (($row['resultado'] == "RECHAZADO") ? '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: red;" title="REPROBADO"></i' : '');

                                        echo '<tr>';
                                        echo '<td> ' . $fila['ip'] . ' ' . $fila['folio'] . '</td>';
                                        echo '<td>' . $fechaInforme . '</td>';
                                        echo '<td align="left">' . $row['nombre_candidato'] . '</td>';
                                        echo '<td> ' . $empresa . '</td>';
                                        echo '<td>' . $icon . '</td>';
                                        echo '</tr>';

                                        // Almacena la fecha en el array solo si no está presente
                                        if (!in_array($fechaInforme, $resultadosFechas)) {
                                            $resultadosFechas[] = $fechaInforme;
                                        }
                                    }

                                    echo '</table>';
                                    echo '<hr>';

                                    $numFechasDistintas = count($resultadosFechas);

                                    //echo "Número de fechas distintas: " . $numFechasDistintas . "<br>";

                                    // Muestra las fechas distintas
                                    //echo "Fechas distintas: " . implode(', ', $resultadosFechas) . "<br>";

                                    $evaluador = mysqli_query($conn, "SELECT valor FROM `insp_eva` WHERE user = '$usuario'");
                                    $fila = mysqli_fetch_array($evaluador);
                                    $valor = $fila['valor'];
                                    $valorVisita = 30000;
                                    $totalVisita = $valorVisita * $numFechasDistintas;
                                    $totaL = $valor * $num_registros;
                                    $total = $totaL + $totalVisita;
                                    $Total = number_format($total, 0, ',', '.');

                                    echo '<div class="tabla">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">EVALUACIONES</span>
                                                        <input type="text" class="form-control" placeholder="' . $num_registros . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                        <input type="text" class="form-control" placeholder="' . number_format($totaL, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VISITAS</span>
                                                        <input type="text" class="form-control" placeholder="' . $numFechasDistintas . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                        <input type="text" class="form-control" placeholder="' . number_format($totalVisita, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div style="color: blue;"> TOTAL EVALUACIONES DEL PERIODO $ ' . $Total . '</div>
                                                </div>
                                                <div class="col"></div>
                                            </div>
                                    </div>';
                                    echo '</div>';
                                    echo '<form action="subirBoleta.php" method="POST" enctype="multipart/form-data" id="formularioBoleta">';
                                    echo '<div class="tabla">
                                            <div class="row">
                                                <div class="col">
                                                FORMULARIO PARA SUBIR BOLETA DE SERVICIO
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'">
                                                        <input type="hidden" id="date_in" name="date_in" value="' . $nuevaFechaInicio  . '">
                                                        <input type="hidden" id="date_out" name="date_out" value="' . $nuevaFechaFin . '">
                                                        <input type="hidden" id="codigo" name="codigo" value="' . $codigo . '">
                                                        <input type="hidden" id="numero" name="numero" value="' . $num_registros . '">
                                                        <input type="hidden" id="name" name="name" value="' . $nombre . '">
                                                        <input type="hidden" id="rut" name="rut" value="' . $rut . '">
                                                        <input type="hidden" id="cta" name="cta" value="' . $cta . '">
                                                        <input type="hidden" id="banco" name="banco" value="' . $banco . '">
                                                        <input type="hidden" id="numerocta" name="numerocta" value="' . $numerocta . '">
                                                        <input type="hidden" id="total" name="total" value="' . $Total . '">
                                                        <input type="hidden" id="visitas" name="visitas" value="' . $numFechasDistintas . '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="basic-addon1">N° BOLETA</span>
                                                        <input type="text" name="boleta" id="boleta" class="form-control" placeholder="Ingresar número de Boleta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="basic-addon1">Fecha Boleta</span>
                                                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">SUBIR BOLETA</span>
                                                        <input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text" id="addon-wrapping">SUBIR RESPALDO</span>
                                                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="checkbox" id="btn-menu" name="acepto_valor" required> Acepto el valor indicado según el periodo.
                                                </div>
                                                <div class="col">
                                                    
                                                </div>
                                            </div>
                                            <div class="row"><div class="col">&nbsp;</div></div>
                                            <div class="row">
                                                <div class="col">
                                                    <button onclick="capturarPantalla()" class="btn btn-warning botones "><i class="fa fa-camera" aria-hidden="true"></i> CAPTURAR RESPALDO</button>
                                                    <input type="submit" name="subirboleta" id="subirboleta" class="btn btn-primary botones " value="ENVIAR">
                                                </div>
                                            </div>
                                        </div>';
                                    echo '<br>';
                                    echo '</form>';
                                
                                } else {
                                    // Si no hay resultados, muestra un mensaje
                                    echo ". No se encontraron registros para el período especificado.";
                                }
                    }

                    // Imprimir el mensaje
                    echo $mensaje;

                } else {
                    // Acción a realizar si no se encuentran resultados
                                            // Simulemos una búsqueda y devolvamos resultados de ejemplo
                                            $resultados = "Resultados para el período: " . date("d-m-Y", strtotime($fechaInicio)) . " al " . date("d-m-Y", strtotime($nuevaFechaFin)) . "";
                                            echo '<div id="elemento-a-capturar">';
                                            echo $resultados;
                    
                                            $query = "SELECT * FROM `informesM` WHERE `fechaInforme` BETWEEN '$nuevaFechaInicio' AND '$nuevaFechaFin' AND `userInforme` = '$usuario'";
                                            $result = mysqli_query($conn, $query);
                    
                                            $num_registros = mysqli_num_rows($result);
                    
                                            if ($num_registros > 0) {
                                                // Si hay resultados, muestra una tabla
                                                echo ". Se encontraron $num_registros registros.";
                                                echo '<hr>';
                                                echo '<table width="100%" border="0" class="table table-striped responsive-font">
                                                        <tr>
                                                            <th>N° FOLIO</th>
                                                            <th>FECHA</th>
                                                            <th>OPERADOR</th>
                                                            <th>EMPRESA</th>
                                                            <th>RESULTADO</th>
                                                        </tr>';
                    
                                                $resultadosFechas = array();
                    
                                                while ($row = mysqli_fetch_array($result)) {
                    
                                                    $fechaInforme = date("d-m-Y", strtotime($row['fecha']));
                    
                                                    $enterprice = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE id= '" . $row['IdOper'] . "' ");
                                                    $fila = mysqli_fetch_array($enterprice);
                                                    $empresa = $fila['empresa'];
                                                    $codigo = $row['folio'];
                    
                                                    $icon = ($row['resultado'] == "APROBADO") ? '<i class="fa fa-check fa-1x" aria-hidden="true" style="color: #3FFF33;" title="APROBADO"></i>' : (($row['resultado'] == "RECHAZADO") ? '<i class="fa fa-times fa-1x" aria-hidden="true" style="color: red;" title="REPROBADO"></i' : '');
                    
                                                    echo '<tr>';
                                                    echo '<td> ' . $fila['ip'] . ' ' . $fila['folio'] . '</td>';
                                                    echo '<td>' . $fechaInforme . '</td>';
                                                    echo '<td align="left">' . $row['nombre_candidato'] . '</td>';
                                                    echo '<td> ' . $empresa . '</td>';
                                                    echo '<td>' . $icon . '</td>';
                                                    echo '</tr>';
                    
                                                    // Almacena la fecha en el array solo si no está presente
                                                    if (!in_array($fechaInforme, $resultadosFechas)) {
                                                        $resultadosFechas[] = $fechaInforme;
                                                    }
                                                }
                    
                                                echo '</table>';
                                                echo '<hr>';
                    
                                                $numFechasDistintas = count($resultadosFechas);
                    
                                                //echo "Número de fechas distintas: " . $numFechasDistintas . "<br>";
                    
                                                // Muestra las fechas distintas
                                                //echo "Fechas distintas: " . implode(', ', $resultadosFechas) . "<br>";
                    
                                                $evaluador = mysqli_query($conn, "SELECT valor FROM `insp_eva` WHERE user = '$usuario'");
                                                $fila = mysqli_fetch_array($evaluador);
                                                $valor = $fila['valor'];
                                                $valorVisita = 30000;
                                                $totalVisita = $valorVisita * $numFechasDistintas;
                                                $totaL = $valor * $num_registros;
                                                $total = $totaL + $totalVisita;
                                                $Total = number_format($total, 0, ',', '.');
                    
                                                echo '<div class="tabla">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">EVALUACIONES</span>
                                                                    <input type="text" class="form-control" placeholder="' . $num_registros . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                                    <input type="text" class="form-control" placeholder="' . number_format($totaL, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VISITAS</span>
                                                                    <input type="text" class="form-control" placeholder="' . $numFechasDistintas . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">VALOR $ </span>
                                                                    <input type="text" class="form-control" placeholder="' . number_format($totalVisita, 0, ',', '.') . '" aria-label="Username" aria-describedby="addon-wrapping" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div style="color: blue;"> TOTAL EVALUACIONES DEL PERIODO $ ' . $Total . '</div>
                                                            </div>
                                                            <div class="col"></div>
                                                        </div>
                                                </div>';
                                                echo '</div>';
                                                echo '<form action="subirBoleta.php" method="POST" enctype="multipart/form-data" id="formularioBoleta">';
                                                echo '<div class="tabla">
                                                        <div class="row">
                                                            <div class="col">
                                                            FORMULARIO PARA SUBIR BOLETA DE SERVICIO
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'">
                                                                    <input type="hidden" id="date_in" name="date_in" value="' . $nuevaFechaInicio  . '">
                                                                    <input type="hidden" id="date_out" name="date_out" value="' . $nuevaFechaFin . '">
                                                                    <input type="hidden" id="codigo" name="codigo" value="' . $codigo . '">
                                                                    <input type="hidden" id="numero" name="numero" value="' . $num_registros . '">
                                                                    <input type="hidden" id="name" name="name" value="' . $nombre . '">
                                                                    <input type="hidden" id="rut" name="rut" value="' . $rut . '">
                                                                    <input type="hidden" id="cta" name="cta" value="' . $cta . '">
                                                                    <input type="hidden" id="banco" name="banco" value="' . $banco . '">
                                                                    <input type="hidden" id="numerocta" name="numerocta" value="' . $numerocta . '">
                                                                    <input type="hidden" id="total" name="total" value="' . $Total . '">
                                                                    <input type="hidden" id="visitas" name="visitas" value="' . $numFechasDistintas . '">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1">N° BOLETA</span>
                                                                    <input type="text" name="boleta" id="boleta" class="form-control" placeholder="Ingresar número de Boleta" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="basic-addon1">Fecha Boleta</span>
                                                                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">SUBIR BOLETA</span>
                                                                    <input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text" id="addon-wrapping">SUBIR RESPALDO</span>
                                                                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <input type="checkbox" id="btn-menu" name="acepto_valor" required> Acepto el valor indicado según el periodo.
                                                            </div>
                                                            <div class="col">
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="row"><div class="col">&nbsp;</div></div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <button onclick="capturarPantalla()" class="btn btn-warning botones "><i class="fa fa-camera" aria-hidden="true"></i> CAPTURAR RESPALDO</button>
                                                                <input type="submit" name="subirboleta" id="subirboleta" class="btn btn-primary botones " value="ENVIAR">
                                                            </div>
                                                        </div>
                                                    </div>';
                                                echo '<br>';
                                                echo '</form>';
                                            
                                            } else {
                                                // Si no hay resultados, muestra un mensaje
                                                echo ". No se encontraron registros para el período especificado.";
                                            }
                    
                }
        }
    } else {
        // Algunos de los datos faltan
        echo "Error: Datos de inicio y fin no proporcionados.";
    }
} else {
    // La solicitud no es de tipo POST
    echo "Error: Esta página solo acepta solicitudes POST.";
}
?>