<?php
session_start();
error_reporting(1);


// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}
    // Conectarse a la base de datos
    require_once('../admin/conex.php');
    // Obtener los datos del formulario
    $operadoresSeleccionados = $_POST['operadores'];
    $mensajeria = $_POST['mensajeria'];

  // Consulta para obtener la última ID de la tabla nomina
    $sql = "SELECT MAX(id_nomina) as max_id FROM nomina";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxId = $row["max_id"];
        $folio = $maxId + 1;
    } else {
        $folio = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/style_other.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
        <style type="text/css">
            .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            }
            .nomina {
                position: absolute;
                top: 80px;
                left: 50%;
                transform: translateX(-50%);
                width: 100%;
                min-width: 300px;
                height: 100%;
                min-height: 300px;
                background-color: white;
                border-radius: 20px;
                backdrop-filter: blur(10px);
                padding: 20px;
            }
            .tabla{
            box-shadow: 0 12px 28px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            padding: 10px; border-collapse: collapse; border-spacing: 4px;
            }
            .tabla td {
                padding: 4px; /* Ajusta el valor de padding según tus necesidades */
            }
            .tabla tr{
                padding: 4px;
            }
            a {
                text-decoration: none;
            }
            h2 {
                text-align: center; 
            }
            #folio {
                text-align: center;
                border: none !important;
                width: 60px;
            }
            hr {
                border: 1px solid black;
            }
            .whatsapp::placeholder {
                color: #25D366;
            }
            .cabecera {
                font-size: 25px;
                font-weight: bold;
                border: none;
            }
            .save{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <br>
        <table width="90%" border="0" style="position: absolute; left:20px;">
            <tr>
                <td>
                    <form id="myForm" action="cotizacion.php" method="post" >
                        <select id="nomina" name="nomina" class="form-control" onchange="submitForm()" style='background-color: #C2DBFE;'>
                            <option value="">Seleccionar Nomina</option>
                                <?php
                                    $seach = mysqli_query($conn, "SELECT * FROM `nomina` ORDER BY `id_nomina` DESC");
                                        while ($rows = mysqli_fetch_array($seach)) {
                                            echo '<option value="'.$rows['id_nomina'].'">'.$rows['id_nomina'].' '.$rows['empresa'].' '.$rows['faena'].'</option>';
                                        }
                                ?>
                        </select>
                    </form>                  
                </td>
                <td align="rigth">
                    <?php
                        $idNomina = $_POST['nomina'];
                        $cargarNomina = mysqli_query($conn, "SELECT * FROM `nomina` WHERE `id_nomina` = '$idNomina'");
                        while ($row = mysqli_fetch_array($cargarNomina)) {
                            $folio = $row['id_nomina'];
                            $Title = $row['titulo'];
                            $empresa = $row['empresa'];
                            $faena = $row['faena'];
                            $cotizacion = $row['cotizacion'];
                            $contacto = $row['contact']; 
                            $inicio = $row['date_in'];
                            $fin = $row['date_end'];
                            $para = $row['para'];
                            $cc = $row['cc'];
                            $asunto = $row['asunto'];
                            $bloquedo = "readonly";
                            $disabled = "disabled";
                        }
                    ?>
                    <center>
                            <select class="cabecera" name="titulo" id="titulo">
                                <option value="nomina" <?php if($Title=='nomina'){ echo "selected"; } ?>>NOMINA OPERADORES</option>
                                <option value="pre-nomina" <?php if($Title=='pre-nomina'){ echo "selected"; } ?>>PRE NOMINA OPERADORES</option>
                            </select>
                            <input type="text" class="cabecera" name="folio" id="folio" placeholder="folio" value="<?php echo $folio;?>">
                    </center>
                </td>
                <td>
                <?php
                    // Establecer la zona horaria
                    date_default_timezone_set('America/Santiago');
                    // Obtener la fecha actual
                    $fechaActual = new DateTime();
                    // Establecer el localismo a español
                    setlocale(LC_TIME, 'es_ES.utf8');
                    // Formatear la fecha
                    $fechaFormateada = strftime('%d de %B de %Y', $fechaActual->getTimestamp());
                    // Mostrar la fecha formateada
                    echo "La Serena, " . $fechaFormateada;
                    ?>
                </td>
            </tr>
        </table>
    <div class="container">
        <div class="nomina">
        <table width="100%" border="0" cellspacing="6" cellpadding="6">
            <tr>
                <td>
                    <img src="../img/logo3.png" width="200" alt="Logo Operamaq">
                </td>
                <td>
                    <select name="emp" id="emp" class="form-control" onchange="limpiarCampos()" <?php echo $disabled; ?>>
                        <option value="">Seleccionar Empresa</option>
                        <?php
                            $seach = mysqli_query($conn, "SELECT * FROM `empresa` ORDER BY `nombre` ASC");
                                while ($rows = mysqli_fetch_array($seach)) {
                                    $id_emp = $rows['nombre'];
                                    ?>
                                    <option value="<?php echo $id_emp; ?>" <?php if($empresa==$id_emp){ echo "selected"; } ?> ><?php  echo $rows['nombre']; ?></option>
                                <?php
                                }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="text" placeholder="Faena" name="faena" id="faena" class="form-control" value="<?php echo $faena;?>" <?php echo $bloquedo; ?>>
                </td>
                <td>
                    <select name="cot" id="cot" class="form-control" <?php echo $disabled; ?>>
                        <option value="<?php echo $cotizacion;?>"><?php echo $cotizacion;?></option>
                    </select>
                </td>
                <td>
                    <input type="text" placeholder="Contacto" name="contact" id="contact" class="form-control" value="<?php echo $contacto;?>" <?php echo $bloquedo; ?>>
                </td>
                <td>
                    <input type="date" name="inicio" id="inicio" class="form-control" value="<?php echo $inicio;?>" title="INGRESAR FECHA DE INICIO DE FAENA">
                </td>
                <td>
                    <input type="date" name="fin" id="fin" class="form-control" value="<?php echo $fin;?>" title="INGRESAR FECHA DE TERMINIO DE FAENA">
                </td>
            </tr>
            <tr>
                <td align="right" colspan="7">
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="para" id="para" placeholder="Para" class="form-control" value="<?php echo $para;?>" <?php echo $bloquedo; ?>></td>
                <td colspan="2"><input type="text" name="cc" id="cc" placeholder="Cc" class="form-control" value="<?php echo $cc; ?>"></td>
                <td colspan="2"><input type="text" name="asunto" id="asunto" placeholder="asunto" class="form-control" value="<?php echo $asunto;?>"></td>
                <td><input type="submit" value="Enviar" class="btn btn-secondary" id="enviar-btn"></td>
            </tr>
            <tr>
                <td colspan="7">
                    <hr>
                </td>
            </tr>
        </table>
        <div style="overflow-y: scroll; max-height: 400px;">
        <table border="1" width="100%" class="tabla table table-striped" style="font-size: 12px;">
            <tr>
                <th>Item</th>
                <th>Nombre</th>
                <th>Run</th>
                <th>Cargo</th>
                <th>Celular</th>
                <th>Licencia</th>
                <th>Disp-Acred</th>
                <th>Obs.1</th>
                <th>Obs.2</th>
                <th>Sueldo</th>
                <th></th>
            </tr>
            <?php 
                if (!empty($empresa) || !empty($faena)) { // sólo ejecutar si al menos uno de los campos tiene un valor
                    $empresa_esc = mysqli_real_escape_string($conn, $empresa);
                    $faena_esc = mysqli_real_escape_string($conn, $faena);
                    $buscarDatos = mysqli_query($conn, "SELECT * FROM `operadores` WHERE empresa = '$empresa_esc' AND faena = '$faena_esc'");
                    while($DatoEncontrados = mysqli_fetch_array($buscarDatos)){
                        switch ($DatoEncontrados['equipo1']) {
                            case '13':
                                $maq = 'Bulldozer D6';
                                break;
                            case '1':
                                $maq = 'Bulldozer D8';
                                break;
                            case '14':
                                $maq = 'Bulldozer D09';
                                break;
                            case '15':
                                $maq = 'Bulldozer D10';
                                break;
                            case '2':
                                $maq = 'Camión Aljibe 15 m3';
                                break;
                            case '3':
                                $maq = 'Camión Aljibe 30 m3';
                                break;
                            case '19':
                                $maq = 'Camión Dumper';
                                break;
                            case '24':
                                $maq = 'Camión Lubricador';
                                break;
                            case '23':
                                $maq = 'Camión Petroleador';
                                break;
                            case '4':
                                $maq = 'Camión Pluma 5 ton';
                                break;
                            case '16':
                                $maq = 'Camión Pluma 8 ton';
                                break;
                            case '17':
                                $maq = 'Camión Pluma 10 ton';
                                break;
                            case '18':
                                $maq = 'Camión Pluma 15 ton';
                                break;
                            case '5':
                                $maq = 'Camión Tolva 20 m3';
                                break;
                            case '22':
                                $maq = 'Cargador Frontal';
                                break;
                            case '6':
                                $maq = 'Excavadora 20-22 Ton.';
                                break;
                            case '7':
                                $maq = 'Excavadora 35 Ton.';
                                break;
                            case '8':
                                $maq = 'Excavadora 50 Ton.';
                                break;
                            case '20':
                                $maq = 'Excavadora 70 Ton.';
                                break;
                            case '21':
                                $maq = 'Excavadora 80 Ton.';
                                break;
                            case '9':
                                $maq = 'Minicargador';
                                break;
                            case '10':
                                $maq = 'Motoniveladora';
                                break;
                            case '11':
                                $maq = 'Retroexcavadora';
                                break;
                            case '25':
                                $maq = 'Rigger';
                                break;
                            case '12':
                                $maq = 'Rodillo Compactador';
                                break;
                            default:
                                $maq = ''; // Valor por defecto si no coincide con ninguno de los casos anteriores
                                break;
                        } 
                        echo "
                        <form id='formOper" . $DatoEncontrados['Id'] . "'>
                            <tr>
                                <td>
                                    <i class='fa fa-trash-o eliminar-registro fa-lg' aria-hidden='true' data-id='" . $DatoEncontrados['Id'] . "' style='cursor: pointer;' title='El identificador del operador es : ". $DatoEncontrados['Id'] ."'></i>
                                    </td>
                                <td>
                                    ".ucwords(strtolower($DatoEncontrados['nombre'])) ." ".ucwords(strtolower($DatoEncontrados['apellidos']))."
                                </td>
                                <td>
                                    ".$DatoEncontrados['rut']."
                                </td>
                                <td>
                                    ".$maq."
                                </td>
                                <td>
                                    <span class='numero'>" . $DatoEncontrados['celular'] . "</span>
                                </td>
                                <td>
                                    ".$DatoEncontrados['licencia']."
                                </td>
                                <td>
                                    <input type='hidden' name='IdOperadores[]' id='IdOperadores' value='". $DatoEncontrados['Id'] ."'>
                                    <input type='hidden' name='operadorId' id='operadorId' class='operadorId' value='". $DatoEncontrados['Id'] ."'>
                                    <input type='date' name='date_disp' id='date_disp' class='date_disp' value='".$DatoEncontrados['date_disp']."' style='border: none;'>
                                </td>
                                <td>
                                    <select name='selectOper' id='selectOper' class='selectOper' style='border: none;'>
                                        <option value='DISPONIBLE' " . ($DatoEncontrados['selectOper'] == 'DISPONIBLE' ? 'selected' : '') . ">DISPONIBLE</option>
                                        <option value='ESPERA DE FINIQUITO' " . ($DatoEncontrados['selectOper'] == 'ESPERA DE FINIQUITO' ? 'selected' : '') . ">ESPERA DE FINIQUITO</option>
                                    </select>
                                </td>
                                <td>
                                    <select name='valid' id='valid' class='valid' style='border: none;'>
                                        <option value='VALIDADO POR OBRA' " . ($DatoEncontrados['valid'] == 'VALIDADO POR OBRA' ? 'selected' : '') . ">VALIDADO POR OBRA</option>
                                        <option value='EN REVISION' " . ($DatoEncontrados['valid'] == 'EN REVISION' ? 'selected' : '') . ">EN REVISION</option>
                                        <option value='CUMPLE' " . ($DatoEncontrados['valid'] == 'CUMPLE' ? 'selected' : '') . ">CUMPLE</option>
                                    </select>
                                </td>
                                <td>
                                    <input type='text' name='sueldo' id='sueldo' class='sueldo' placeholder='$###.###' value='".$DatoEncontrados['suedo']."' style='border: none;' maxlength='10'>
                                    </td>
                                <th width='5px'>
                                    <i class='fa fa-floppy-o fa-lg save' aria-hidden='true' style='cursor: pointer;' title='Guardar' data-id='" . $DatoEncontrados['Id'] . "'></i>
                                </th>
                            </tr>
                        </form>";
                    }
                }
            ?>
                <?php
                if (isset($_POST['operadores'])) {
                $operadoresSeleccionados = $_POST['operadores'];
                $mensajeria = $_POST['mensajeria'];

                // Verificar los datos recibidos
                // Construir la consulta SQL para obtener los nombres de los operadores seleccionados
                $consulta = "SELECT * FROM `operadores` WHERE Id IN (".implode(",", $operadoresSeleccionados).") ORDER BY `equipo1`ASC";

                // Ejecutar la consulta SQL
                $resultado = mysqli_query($conn, $consulta);

                // Verificar si se encontraron resultados
                if (mysqli_num_rows($resultado) > 0) {
                    // Crear un array para almacenar los números de teléfono
                    $numeros = array();
                    $n = 1; // Declarar e inicializar la variable $n antes del bucle
                    while ($row = mysqli_fetch_assoc($resultado)) {

                    // Agregar el número de teléfono al array
                    $numeros[] = $row['celular'];

                    // Generar los enlaces de WhatsApp para cada número
                    foreach ($numeros as $numero) {
                        $enlace = "whatsapp://send?phone=" . urlencode($numero) . "&amp;text=" . urlencode($mensajeria);
                        }
                        switch ($row['equipo1']) {
                            case '13':

                                $maq = 'Bulldozer D6';

                                break;

                            case '1':

                                $maq = 'Bulldozer D8';

                                break;

                            case '14':

                                $maq = 'Bulldozer D09';

                                break;

                            case '15':

                                $maq = 'Bulldozer D10';

                                break;

                            case '2':

                                $maq = 'Camión Aljibe 15 m3';

                                break;

                            case '3':

                                $maq = 'Camión Aljibe 30 m3';

                                break;

                            case '19':

                                $maq = 'Camión Dumper';

                                break;

                            case '24':

                                $maq = 'Camión Lubricador';

                                break;

                            case '23':

                                $maq = 'Camión Petroleador';

                                break;

                            case '4':

                                $maq = 'Camión Pluma 5 ton';

                                break;

                            case '16':

                                $maq = 'Camión Pluma 8 ton';

                                break;

                            case '17':

                                $maq = 'Camión Pluma 10 ton';

                                break;

                            case '18':

                                $maq = 'Camión Pluma 15 ton';

                                break;

                            case '5':

                                $maq = 'Camión Tolva 20 m3';

                                break;

                            case '22':

                                $maq = 'Cargador Frontal';

                                break;

                            case '6':

                                $maq = 'Excavadora 20-22 Ton.';

                                break;

                            case '7':

                                $maq = 'Excavadora 35 Ton.';

                                break;

                            case '8':

                                $maq = 'Excavadora 50 Ton.';

                                break;

                            case '20':

                                $maq = 'Excavadora 70 Ton.';

                                break;

                            case '21':

                                $maq = 'Excavadora 80 Ton.';

                                break;

                            case '9':
                                $maq = 'Minicargador';
                                break;
                            case '10':
                                $maq = 'Motoniveladora';
                                break;
                            case '11':
                                $maq = 'Retroexcavadora';
                                break;
                            case '25':
                                $maq = 'Rigger';
                                break;
                            case '12':
                                $maq = 'Rodillo Compactador';
                                break;
                            default:
                                $maq = ''; // Valor por defecto si no coincide con ninguno de los casos anteriores
                                break;
                        }    
                    echo    "
                                <tr>
                                    <td>". $n ."</td>
                                    <td>".ucwords(strtolower($row['nombre'])) ." ".ucwords(strtolower($row['apellidos']))."</td>
                                    <td>".$row['rut']."</td>
                                    <td>".$maq."</td>
                                    <td><span class='numero'>" . $numero . "</span></td>
                                    <td>".$row['licencia']."</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th></th>
                            </tr>
                            ";
                            $n++;
                    }
                } else {
                    echo "No se encontraron operadores seleccionados.";
                }
                // Cerrar la conexión a la base de datos
                mysqli_close($conn);
                } else {
                    echo '<script>          
                            swal({
                                title: "Nomina!",
                                text: "No se ha seleccionado ningun Operador!",
                                icon: "info",
                                button: "Aceptar!",
                                timer: 4000
                            });
                        </script>';
                }
                ?>
                <tr>
                    <td colspan="11">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="11">
                        <input type="text" name="mess" id="mess" value="<?php echo $mensajeria; ?>" class="form-control whatsapp" placeholder="Ingresar Mensaje Whatsapp">
                    </td>
                </tr>
                <tr>
                    <td colspan="11">
                        <button id="enviarBtn" class="btn btn-success" title="ENVIAR WHATSAPP"><i class="fa fa-whatsapp" aria-hidden="true"></i> Enviar Whatsapp</button>
                        <button onclick="confirmAndGoBack()" class="btn btn-primary" title="VOLVER A PAGINA ANTERIOR"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver Atrás</button>
                        <a href="vistaPrevia.php?id=<?php echo $folio;?>" target="_blank" rel="noopener noreferrer">
                        <button type="button" class="btn btn-info" title="VISTRA PREVIA"><i class="fa fa-search" aria-hidden="true"></i> Vista Previa</button>
                        </a>
                        <button type="button" class="btn btn-warning" onclick="saveData()">Guardar Nomina</button>
                        <button type="button" class="btn btn-primary" onclick="SaveNomina()">APROBAR NOMINA</button>
                    </td>
                </tr>
        </table>
        </div>
        </div>
    </div>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  // Variable para almacenar el estado original de los campos de entrada
  var originalFormState = null;

  // Función para obtener el estado original de los campos de entrada
  function getFormState() {
    var formInputs = document.querySelectorAll("input, select, textarea");
    var formState = [];

    for (var i = 0; i < formInputs.length; i++) {
      var input = formInputs[i];
      formState.push(input.value);
    }

    return formState.join();
  }

  // Función para comparar el estado original con el estado actual de los campos de entrada
  function hasFormChanged() {
    var currentFormState = getFormState();
    return currentFormState !== originalFormState;
  }

  // Función para mostrar el mensaje de confirmación y volver atrás
  function confirmAndGoBack() {
    if (hasFormChanged()) {
      swal({
        title: "¿Deseas guardar los cambios antes de volver?",
        text: "Una vez que vuelvas atrás, los cambios no se guardarán.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willSave) => {
        if (willSave) {
          // Ejecutar la función para guardar los datos en PHP
          saveDatas();
        } else {
          // Volver atrás sin guardar los cambios
          window.location.href = "oper.php";
        }
      });
    } else {
      // No ha habido cambios, volver atrás sin mostrar el mensaje de confirmación
      window.location.href = "oper.php";
    }
  }


  function saveData() {
    // Obtener los valores de los campos de entrada
    var empValue = document.getElementById('emp').value;
    var faenaValue = document.getElementById('faena').value;
    var cotValue = document.getElementById('cot').value;
    var folio = document.getElementById('folio').value;
    var contact = document.getElementById('contact').value;
    var inicio = document.getElementById('inicio').value;
    var fin = document.getElementById('fin').value;
    var titulo = document.getElementById('titulo').value;
    var para = document.getElementById('para').value;
    var cc = document.getElementById('cc').value;
    var asunto = document.getElementById('asunto').value;

    // Verificar el valor seleccionado en el campo 'emp'
    if (empValue === '') {
        swal("Error", "Debe seleccionar una empresa antes de guardar.", "error");
        return; // Detener la ejecución de la función
    }
    if (faenaValue === '') {
        swal("Error", "Debe ingresar una faena antes de guardar.", "error");
        return; // Detener la ejecución de la función
        faenaValue.focus();
    }
    if (cotValue === '') {
        swal("Error", "Debe ingresar una cotizacion antes de guardar.", "error");
        return; // Detener la ejecución de la función
        cotValue.focus();
    }
    if (contact === '') {
        swal("Error", "Debe ingresar un contacto antes de guardar.", "error");
        return; // Detener la ejecución de la función
        contact.focus();
    }

    // Crear un objeto FormData
    var formData = new FormData();
    formData.append('emp', empValue);
    formData.append('faena', faenaValue);
    formData.append('cot', cotValue);
    formData.append('operadores', <?php echo json_encode($operadoresSeleccionados); ?>);
    formData.append('folio', folio);
    formData.append('contact', contact);
    formData.append('inicio', inicio);
    formData.append('fin', fin);
    formData.append('titulo', titulo);
    formData.append('para', para);
    formData.append('cc', cc);
    formData.append('asunto', asunto);

    // Realizar la petición AJAX a save_nomina.php
    fetch('save_nomina.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        var message = data.message; // Obtener el mensaje de la respuesta JSON
        swal(message, {
            icon: data.success ? "success" : "error",
        }).then(() => {
            // Volver atrás después de guardar los cambios
            //window.location.href = "oper.php";
            location.reload();
            console.log(message);
        });
    })
    .catch((error) => {
        swal("Hubo un error al guardar los datos. Intenta nuevamente.", {
            icon: "error",
        });
    });
}



function saveDatas() {

    // Obtener los valores de los campos de entrada
    var empValue = document.getElementById('emp').value;
    var faenaValue = document.getElementById('faena').value;
    var cotValue = document.getElementById('cot').value;
    var folio = document.getElementById('folio').value;
    var contact = document.getElementById('contact').value;
    var inicio = document.getElementById('inicio').value;
    var fin = document.getElementById('fin').value;
    var titulo = document.getElementById('titulo').value;
    var para = document.getElementById('para').value;
    var cc = document.getElementById('cc').value;
    var asunto = document.getElementById('asunto').value;
    
    // Crear un objeto FormData
    var formData = new FormData();
    formData.append('emp', empValue);
    formData.append('faena', faenaValue);
    formData.append('cot', cotValue);
    formData.append('operadores', <?php echo json_encode($operadoresSeleccionados); ?>);
    formData.append('folio', folio);
    formData.append('contact', contact);
    formData.append('inicio', inicio);
    formData.append('fin', fin);
    formData.append('titulo', titulo);
    formData.append('para', para);
    formData.append('cc', cc);
    formData.append('asunto', asunto);

    // Realizar la petición AJAX a save_nomina.php
    fetch('save_nomina.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        var message = data.message; // Obtener el mensaje de la respuesta JSON
        swal(message, {
        icon: data.success ? "success" : "error",
        }).then(() => {
        // Volver atrás después de guardar los cambios
        window.location.href = "oper.php";
        });
    })

    .catch((error) => {
        swal("Hubo un error al guardar los datos. Intenta nuevamente.", {
        icon: "error",
        });
    });
}



function SaveNomina(){
    // Obtener los valores de los campos de entrada
    var empValue = document.getElementById('emp').value;
    var faenaValue = document.getElementById('faena').value;
    var cotValue = document.getElementById('cot').value;
    var folio = document.getElementById('folio').value;
    var contact = document.getElementById('contact').value;
    var inicio = document.getElementById('inicio').value;
    var fin = document.getElementById('fin').value;
    var titulo = document.getElementById('titulo').value;
    var para = document.getElementById('para').value;
    var cc = document.getElementById('cc').value;
    var asunto = document.getElementById('asunto').value;
    var IdOperadores = document.getElementById('IdOperadores').value;

    // Verificar el valor seleccionado en el campo 'emp'
    if (empValue === '') {
        swal("Error", "Debe seleccionar una empresa antes de guardar.", "error");
        return; // Detener la ejecución de la función
    }if (faenaValue === '') {
        swal("Error", "Debe ingresar una faena antes de guardar.", "error");
        return; // Detener la ejecución de la función
        faenaValue.focus();
    }if(cotValue === ''){
        swal("Error", "Debe ingresar una cotizacion antes de guardar.", "error");
        return; // Detener la ejecución de la función
        cotValue.focus();
        
    }if(contact === ''){
        swal("Error", "Debe ingresar un contacto antes de guardar.", "error");
        return; // Detener la ejecución de la función
        contact.focus();

    }if(inicio === ''){
        swal("Error", "Debe ingresar una fecha de inicio antes de guardar.", "error");
        return; // Detener la ejecución de la función
        inicio.focus();
    }if(fin === ''){
        swal("Error", "Debe ingresar una fecha de termino antes de guardar.", "error");
        return; // Detener la ejecución de la función
        fin.focus();
    }

    // Crear un objeto FormData
    var formData = new FormData();
    formData.append('emp', empValue);
    formData.append('faena', faenaValue);
    formData.append('cot', cotValue);
    formData.append('operadores', <?php echo json_encode($operadoresSeleccionados); ?>);
    formData.append('folio', folio);
    formData.append('contact', contact);
    formData.append('inicio', inicio);
    formData.append('fin', fin);
    formData.append('titulo', titulo);
    formData.append('para', para);
    formData.append('cc', cc);
    formData.append('asunto', asunto);
    formData.append('IdOperadores', IdOperadores);

    // Realizar la petición AJAX a save_nomina.php
    fetch('saveNominaDefinitiva.php', {
        method: 'POST',
        body: formData,
    })

    .then((response) => response.json())
    .then((data) => {
        var message = data.message; // Obtener el mensaje de la respuesta JSON
        console.log(message);
        swal(message, {
        icon: data.success ? "success" : "error",
        }).then(() => {
        location.reload();
        });
    })
    .catch((error) => {
        console.log(error);
        swal("Hubo un error al guardar los datos. Intenta nuevamente mas tarde.", {
        icon: "error",
        });
    });
}

  // Al cargar la página, se guarda el estado original de los campos de entrada
  window.addEventListener("DOMContentLoaded", function() {
    originalFormState = getFormState();
  });



    function submitForm() {
        document.getElementById("myForm").submit();
    }



//Buscar cotizacion segun cliente

// cuando existe un cambio llamamos a la funcion para buscar la cotizacion 
document.getElementById("emp").addEventListener("change", buscarCotizacion);

// furncion para cargar en el select la cotizacion
function buscarCotizacion(){
    var empresas = document.getElementById("emp").value;
    var xhttp = new XMLHttpRequest();
    var respuestaServidor = ""; // Variable para almacenar la respuesta del servidor
    // Realizar una solicitud AJAX al servidor para obtener los resultados filtrados
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Almacenar la respuesta del servidor en la variable global
            respuestaServidor = this.responseText;
            // Actualizar la tabla con los resultados recibidos del servidor
            document.getElementById("cot").innerHTML = respuestaServidor;
        }
    };

    // Construir la URL de solicitud con los parámetros seleccionados
    var url = "buscarCotizacion.php?empresas=" + empresas;
    xhttp.open("GET", url, true);
    xhttp.send();
}

// fin de buscar cotizacion segun cliente



//Buscar datos de la cotizacion

// cuando existe un cambio llamamos a la funcion para buscar la cotizacion
document.getElementById("cot").addEventListener("focus", dataCotizacion);
document.getElementById("cot").addEventListener("change", dataCotizacion);

// función para cargar en el select la cotización
function dataCotizacion() {
    var cotizacion = document.getElementById("cot").value; // Obtener el valor seleccionado

  // Crear una instancia de XMLHttpRequest
  var xhttp = new XMLHttpRequest();

  // Definir la función de respuesta
  xhttp.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
      var response = JSON.parse(this.responseText); // Convertir la respuesta JSON a objeto JavaScript

      // Actualizar los campos con los datos recibidos
      document.getElementById("contact").value = response.contacto;
      document.getElementById("faena").value = response.faena;
      document.getElementById("para").value = response.para;
    }
  };

  // Realizar la solicitud AJAX
  xhttp.open("GET", "buscarDatosCotizacion.php?coti=" + cotizacion, true);
  xhttp.send();
}

// fin de buscar cotizacion segun cliente

// Limpiar input al seleccionar otro cliente
function limpiarCampos() {
    var selectEmp = document.getElementById("emp");
    var paraInput = document.getElementById("para");
    var contactInput = document.getElementById("contact");
    var faenaInput = document.getElementById("faena");

    if (selectEmp.value === "") {
        paraInput.value = "";
        contactInput.value = "";
        faenaInput.value = "";
    }
}

  // Obtener todos los elementos con la clase 'save'
  const saveButtons = document.querySelectorAll('.save');

  saveButtons.forEach(button => {
  button.addEventListener('click', function() {
    // Obtener el ID del formulario asociado al botón de guardar
    const formId = button.getAttribute('data-id');

    // Buscar el formulario por su ID
    const form = document.getElementById('formOper' + formId);

    // Verificar que el formulario existe antes de acceder a sus campos
    if (form) {
      // Crear un objeto FormData para el formulario
      const formData = new FormData(form);

      // Obtener los valores de los campos por sus nombres
      const operadorId = formData.get('operadorId');
      const dateDisp = formData.get('date_disp');
      const selectOper = formData.get('selectOper');
      const valid = formData.get('valid');
      const sueldo = formData.get('sueldo');

      // Hacer algo con los datos obtenidos, por ejemplo, enviarlos a través de una solicitud AJAX para guardarlos en el servidor
      console.log('Se hizo clic en el botón de guardar para el operador con ID:', operadorId);
      console.log('Fecha de disponibilidad:', dateDisp);
      console.log('Estado del operador:', selectOper);
      console.log('Estado de validación:', valid);
      console.log('Sueldo:', sueldo);

        // Realizar la petición AJAX a save_nomina.php
        fetch('save_nomina_datos.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            var message = data.message; // Obtener el mensaje de la respuesta JSON
            swal(message, {
            icon: data.success ? "success" : "error",
            }).then(() => {
            location.reload();
            });
        })
        .catch((error) => {
            swal("Hubo un error al guardar los datos. Intenta nuevamente.", {
                icon: "error",
            });
        }); 
    } else {
      console.error('No se encontró el formulario asociado al botón de guardar.');
    }
  });
});

// Obtener todos los campos de entrada con la clase 'sueldo'
const sueldoInputs = document.querySelectorAll('.sueldo');

// Añadir un evento de entrada para cada campo
sueldoInputs.forEach(input => {
  input.addEventListener('input', function() {
    // Obtener el valor actual del campo de entrada
    let value = this.value;

    // Limpiar el valor de cualquier carácter que no sea $ o dígito del 0-9
    value = value.replace(/[^$0-9]/g, '');

    // Si el valor no está vacío, darle formato
    if (value.length > 0) {
      // Eliminar todos los $ para formatear el número adecuadamente
      value = value.replace(/\$/g, '');

      // Dar formato al número
      value = '$' + Number(value).toLocaleString();
    }

    // Actualizar el valor del campo de entrada
    this.value = value;
  });
});
</script>

<script>
$(document).ready(function() {
  // Función para enviar los mensajes por AJAX
  function enviarMensajes() {
    // Obtener los números de teléfono y el mensaje
    var numeros = [];
    $('.numero').each(function() {
      numeros.push($(this).text());
    });
    var mensaje = $('#mess').val();
    if(mensaje.length > 0) {
        // Enviar la solicitud AJAX
        $.ajax({
        url: 'send_whatsapp.php',
        method: 'POST',
        data: {
            numeros: numeros,
            mensaje: mensaje
        },
        success: function(response) {
            console.log(response); // Manejar la respuesta del servidor
            swal({
                title: "Mensaje Whatsapp!",
                text: "El mensaje a sido enviado con exito!",
                icon: "success",
                button: "Aceptar!",
                timer: 4000
            });
        },
        error: function(xhr, status, error) {
            console.log(error); // Manejar errores
            swal({
                title: "Mensaje Whatsapp!",
                text: "El mensaje No a sido enviado!",
                icon: "error",
                button: "Aceptar!",
                timer: 4000
            });
        }
        });
    } else {
        swal({
            title: "Mensaje Whatsapp!",
            text: "El mensaje no puede estar vacio!",
            icon: "error",
            button: "Aceptar!",
            timer: 2000
        });
        $('#mess').focus();
        return false;
        
    }
  }

  // Manejar el evento del botón de enviar
  $('#enviarBtn').click(function() {
    enviarMensajes();
  });

  $('#enviar-btn').click(function(e) {
    e.preventDefault(); // Evita el comportamiento predeterminado del botón
    // Obtén los valores de los campos de entrada
    var para = $('#para').val();
    var cc = $('#cc').val();
    var asunto = $('#asunto').val();
    var id_folio = $('#folio').val();
    // Crea un objeto con los datos a enviar
    var datos = {
      para: para,
      cc: cc,
      asunto: asunto,
      id_folio: id_folio
    };

    // Realiza la solicitud Ajax
    $.ajax({
        url: 'pdfMail.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            swal({
                title: 'Envío de Correo!',
                text: response.message,
                icon: response.status === 'success' ? 'success' : 'error',
                button: 'Aceptar!',
                timer: response.status === 'success' ? 4000 : 2000
            });
        },
        error: function(xhr, status, error) {
            console.log(error);
            swal({
                title: 'Envío de Correo!',
                text: 'Ocurrió un error en la solicitud al servidor!',
                icon: 'error',
                button: 'Aceptar!',
                timer: 2000
            });
        }
    });
  });
});

$(document).ready(function() {
    // Mensaje si desea eliminar al operador.
  $('.eliminar-registro').click(function() {
    var id = $(this).data('id');

    swal({
    title: "¿Estás seguro?",
    text: "El Operador será eliminado de la Nomina definitivamente!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            eliminarRegistro(id);
        } else {
            swal("La Operacion ha sido cancelada!");
        }
    });
  });

  // Funsion para Eliminar operador de la nomina.
  function eliminarRegistro(id) {
    $.ajax({
      url: 'deleteUserNomina.php',
      type: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
            swal({
            icon: 'success',
            title: '¡Eliminado!',
            text: 'El Operador ha sido eliminado de la Nomina exitosamente.',
            timer: 2000,
            showConfirmButton: false
          }).then(function() {
            location.reload();
          });
        } else {
            swal({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar el Operador de la Nomina.'
          });
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
});
</script>
</body>
</html>