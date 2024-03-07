<?php
session_start();
error_reporting(0);
require_once('../admin/conex.php');
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}
$familia = $_GET['familia'];
$code = $_GET['code'];

$miVariable = $familia;
$variableEncriptada = base64_encode($miVariable);
$enlace = "verPrueba.php?dato=" . urlencode($variableEncriptada);
?>
        <div class="tabla">
            <h3>Usted esta creando un exmanen para <?php echo $familia. " " . $code;?> </h3>
            <input type="hidden" name="tabla" id="tabla" value="<?php echo $familia;?>">
            <div class="row">
                <div class="col">
                    <input type="text" name="pregunta" id="pregunta" class="form-control" placeholder="Ingrese aquí la pregunta" autofocus required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="respuesta1" id="respuesta1" class="form-control" placeholder="Ingrese aquí la respuesta a)" onfocus="agregarCodigoArea(this)" required>
                </div>
                <div class="col">
                    <input type="text" name="respuesta2" id="respuesta2" class="form-control" placeholder="Ingrese aquí la respuesta b)" onfocus="agregarPrefijo('respuesta2', 'b')" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="respuesta3" id="respuesta3" class="form-control" placeholder="Ingrese aquí la respuesta c)" onfocus="agregarPrefijo('respuesta3', 'c')" required>
                </div>
                <div class="col">
                    <input type="text" name="respuesta4" id="respuesta4" class="form-control" placeholder="Ingrese aquí la respuesta d)" onfocus="agregarPrefijo('respuesta4', 'd')" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="respuestaCorrecta" id="respuestaCorrecta" class="form-control" placeholder="Ingrese aquí en formato numerico la respuesta correcta. Ej: 3" maxlength="1" required>
                </div>
                <div class="col">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!--<input type="text" name="codigo" id="codigo" placeholder="Ingresar Código" class="form-control" style="text-transform: uppercase;" maxlength="19" oninput="validarCodigo(this.value, this)" onfocus="resetInput(this)" required>-->
                    <input type="text" name="ucl" id="ucl" class="form-control" placeholder="Ingresar UCL" maxlength="19" style="text-transform:uppercase;" oninput="validarCodigo(this.value, this)" onfocus="resetInput(this)" required>
                </div>
                <div class="col">
                    <select name="tipo" id="tipo" class="form-control">
                        <option value="0">SELECCIONAR TIPO</option>
                        <option value="SEGURIDAD">SEGURIDAD</option>
                        <option value="OPERACIONAL">OPERACIONAL</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="code" id="code" class="form-control" placeholder="Ingresar Código" maxlength="5" style="text-transform: uppercase;" required>
                </div>
                <div class="col">
                    <!--<select name="item" id="item" placeholder="Ingrese el Item" class="form-control">
                        <option value="SELECCIONAR ITEM">SELECCIONAR ITEM</option>
                    </select>--> 
                    <select name="estado" id="estado" class="form-control">
                        <option value="SELECCIONAR ESTADO">SELECCIONAR ESTADO</option>
                        <option value="CONDICIONANTE">CONDICIONANTE</option>
                        <option value="CRITICA">CRITICA</option>
                    </select>           
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-primary" id="guardarPrueba" title="GUARDAR PRUEBA"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button>
                </div>
                <div class="col">

                </div>
            </div>
        </div>
        <hr>
        <div class="resultadoFamilias">
            <h3>Pruebas creadas para <?php echo $familia; ?></h3>
            <ul class="list-group" style="font-size: 14px;">
                <?php
                $sql = "SELECT * FROM $familia";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // Mostrar los datos de cada fila
                    $n = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<li class='list-group-item'>";
                        echo "<strong>Pregunta " . $n . ":</strong> " . $row["PREGUNTA"];
                        echo "<br>";
                        echo "<strong>Respuesta a):</strong> " . $row["R1"];
                        echo "<br>";
                        echo "<strong>Respuesta b):</strong> " . $row["R2"];
                        echo "<br>";
                        echo "<strong>Respuesta c):</strong> " . $row["R3"];
                        echo "<br>";
                        echo "<strong>Respuesta d):</strong> " . $row["R4"];
                        echo "<br>";
                        
                        // Usar un switch para asignar el valor correcto de respuesta
                        $respuesta = "";
                        switch ($row["id_respuesta_correcta"]) {
                            case 1:
                                $respuesta = "a)";
                                break;
                            case 2:
                                $respuesta = "b)";
                                break;
                            case 3:
                                $respuesta = "c)";
                                break;
                            case 4:
                                $respuesta = "d)";
                                break;
                            default:
                                $respuesta = "";
                                break;
                        }

                        echo "<strong>R. correcta:</strong> " . $respuesta;
                        echo "<br>";

                        echo "<strong>Fecha:</strong> " . date('d-m-Y', strtotime($row["fecha"]));
                        echo "<br>";
                        echo "<strong>Versión:</strong> " . $row["versiones"] . 
                        " <strong> UCL: </strong> " . strtoupper($row['ucl'])  . 
                        " <strong> Tipo: </strong> " . $row['tipo'] . 
                        " <strong> Código: </strong> " . strtoupper($row['codigo']) . 
                        " <strong> Estado: </strong> " . $row['estado'];
                        echo "</li>";

                        $n++;
                    }
                } else {
                    echo "<li class='list-group-item'>No hay pruebas creadas para esta familia</li>";
                }
                ?>
            </ul>
            <a href="<?php echo $enlace; ?>" class="btn btn-primary">Ver prueba</a>
        </div>