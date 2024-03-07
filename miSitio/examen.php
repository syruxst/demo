<?php
session_start();
require_once('../admin/conex.php');
$usuario = $_GET['nombre'];
$prueba = $_GET['examen'];

if (isset($_SESSION['operador']) || isset($_SESSION['usuario'])) {
    if (isset($_SESSION['operador'])) {
       $usuario = $_SESSION['operador'];
       $query = "SELECT * FROM operadores WHERE rut = '$usuario'";
         $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $nombre = $row['nombre']. " " .$row['apellidos'];
    } else {
       $usuario = $_SESSION['usuario'];
    }
} else {
    header("Location: ../ajax/login.php");
    exit();
}
    /*Buscar datos de operador*/
    $buscar = mysqli_query($conn, "SELECT * FROM operadores WHERE rut = '$usuario'");
    while($ver = mysqli_fetch_array($buscar )){
        $Nombre = $ver['nombre'];
        $Apellidos = $ver['apellidos'];
        $Rut = $ver['rut'];
        $Email = $ver['email'];
        $Examen = $ver['familia'];
    }

    $timezone = new DateTimeZone('America/Santiago');
    $now = new DateTime("now", $timezone); 
    echo $fecha = $now->format("Y-m-d H:i:s");
    $hora = $now->format("H:i:s"); 
    $fechaFormateada = $now->format("d-m-Y"); 
    $finTime = $now->add(new DateInterval('PT2H'));
    echo $finTimeFormatted = $finTime->format("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/style.operador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Examen ::</title>
    <style>
        :root{
            --color : #e5e5e5;
            --colorHover: #04C9FA;
            --colorButton: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        #timer {
            font-size: 24px;
            text-align: center;
        }
        .countdown-alert {
            font-size: 24px;
            text-align: center;
            color: red;
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
            opacity: 0;
            }
        }
        .container {
            border-radius: 10px;
            border: 1px solid var(--color);
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        .paragraph {
            text-align: justify;
            width: 100%;
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
        }
        hr{
            border: 1px solid var(--color);
        }
        section{
            border: 1px solid var(--color);
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
        }
        label{
            display: block;
            padding: 7px;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid var(--color);
            margin-bottom: 3px;
        }
        label:hover{
            background-color: var(--colorHover);
            color: white;
        }
        #enviarRespuestas {
            width: 200px; 
            height: 40px; 
            background-color: var(--colorButton);
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        #enviarRespuestas:hover {
            background-color: #03a4d3; 
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
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
        /* temporizador */
        .red {
        color: red;
        }
        
        /* Agrega un estilo CSS para hacer que el texto parpadee */
        .blinking {
        animation: blink 1s cubic-bezier(0.5, 0, 1, 1) infinite alternate;
        }

        /* Define la animación de parpadeo */
        @keyframes blink {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
        }
    </style>
</head>
<body>
    <div class="tabla">
        <div class="row">
            <div class="col">
                Bienvenido a tú examen <?php echo $nombre; ?>
            </div>
            <div class="col">
                <div id="timer">2:00:00</div>
            </div>
        </div>
    </div>
    <div class="container">
     <p class="paragraph">Esta evaluación busca reflejar sus conocimientos respecto al cargo en el cual usted es candidato para certificación de competencias, para responder la evaluación deberá́ seleccionar la alternativa que usted considera correcta, para todas las preguntas existe una sola respuesta válida, en caso de dudas pida la aclaración o asistencia del evaluador. </p>
    <div class="tabla">
        <div class="row">
            <div class="col">
                Fecha: <?php echo $fechaFormateada; ?>
            </div>
            <div class="col">
                Hora de inicio: <?php echo $hora; ?>
            </div>
        </div>
    </div>
    <hr>
    <p id="hora-caducidad">Hora de Temino del examen : --:--:--</p>
    <form id="miFormulario" action="saveRespuestas.php" method="post">
    <?php
    // busca si el usuario ya realizo el examen
    $chequear = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE rut = '$usuario' AND equipo = '$prueba' AND date_in != '0000-00-00 00:00:00' AND resultado != 'APROBADO' AND doc != ''");
    if (mysqli_num_rows($chequear) > 0) {
        echo 'El usuario ya realizó el examen.';
    } else {
        $modificar = "UPDATE detallle_ot SET date_in = '$fecha', datefin = '$finTimeFormatted' WHERE rut = '$usuario' AND equipo = '$prueba' AND resultado != 'APROBADO'";
        $conn->query($modificar);
        $noCriticaQuery = "SELECT * FROM `$prueba` WHERE estado != 'CRITICA' ORDER BY RAND() LIMIT 16";
        $criticaQuery = "SELECT * FROM `$prueba` WHERE estado = 'CRITICA' ORDER BY RAND() LIMIT 4";
        
        $noCriticaResults = mysqli_query($conn, $noCriticaQuery);
        $criticaResults = mysqli_query($conn, $criticaQuery);
        
        $buscarExamen = array();
        
        while ($fila = mysqli_fetch_assoc($noCriticaResults)) {
            $buscarExamen[] = $fila;
        }
        
        while ($fila = mysqli_fetch_assoc($criticaResults)) {
            $buscarExamen[] = $fila;
        }
        
        // Mezclar aleatoriamente el array
        shuffle($buscarExamen);
        
        // Mostrar los resultados
        $n = 1;
        foreach ($buscarExamen as $mostrar) {
            echo '<section class="pregunta" data-id="' . $mostrar['id'] . '">';
            echo '<h5 name="pregunta' . $mostrar['id'] . '">'.$n.'. ' . $mostrar['PREGUNTA'] .' '.$mostrar['id'].' '.$mostrar['codigo'].' '.$mostrar['estado'].'</h5>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="1"> ' . $mostrar['R1'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="2"> ' . $mostrar['R2'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="3"> ' . $mostrar['R3'] . '</label>';
            echo '<label><input type="radio" name="p' . $mostrar['id'] . '" value="4"> ' . $mostrar['R4'] . '</label>';
            echo '</section>';
            $n++;
        }
        echo '<input type="hidden" name="rut" value="' . $usuario . '">';
        echo '<input type="hidden" name="equipo" value="' . $prueba . '">';
        echo '<button type="button" id="enviarRespuestas" title="ENVIAR RESPUESTAS">ENVIAR RESPUESTAS</button>';
    }
    ?>
    </form>
    </div>
<script>
const horaInicioPHP = "<?php echo $fecha; ?>";
const horaInicio = new Date(horaInicioPHP);

// Sumar 2 horas a la hora de inicio
horaInicio.setHours(horaInicio.getHours() + 2);

const horaTermino = new Date(horaInicio);
document.getElementById('hora-caducidad').innerHTML = `Hora de Temino del examen : ${horaTermino.toLocaleTimeString()}`;

function actualizarTemporizador() {
  const ahora = new Date();

  if (ahora >= horaTermino) {
    document.getElementById('timer').innerHTML = 'Tu tiempo terminó';
    enviarFormulario();
    window.parent.postMessage('formularioEnviado', '*');
    console.log("Tiempo caducado. Llamando a enviarFormulario()");
    clearInterval(temporizador);
  } else {
    const diferencia = new Date(horaTermino - ahora);
    const horas = diferencia.getUTCHours();
    const minutos = diferencia.getUTCMinutes();
    const segundos = diferencia.getUTCSeconds();

    if (horas === 0 && minutos === 0 && segundos === 0) {
      document.getElementById('timer').innerHTML = 'Tu tiempo terminó';
      enviarFormulario();
      window.parent.postMessage('formularioEnviado', '*');
      console.log("Tiempo caducado (0:00:00). Llamando a enviarFormulario()");
      clearInterval(temporizador);
    } else {
      const formato = `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
      document.getElementById('timer').innerHTML = formato;

      // Si faltan 10 minutos o menos, activa el parpadeo y cambia el color
      if (horas === 0 && minutos <= 10) {
        document.getElementById('timer').classList.add('blinking', 'red');
      } else {
        document.getElementById('timer').classList.remove('blinking', 'red');
      }
    }
  }
}

function iniciarTemporizador() {
  setInterval(actualizarTemporizador, 1000);
  actualizarTemporizador(); // Actualizar el temporizador inmediatamente al cargar la página
}

iniciarTemporizador();

// Función para enviar datos por Ajax
function enviarFormulario() {
    console.log("Enviando formulario...");
    var formulario = document.getElementById("miFormulario");
    formulario.submit();
}

const enviarRespuestasButton = document.getElementById("enviarRespuestas");
enviarRespuestasButton.addEventListener("click", function(event) {
    event.preventDefault(); // Evita el envío automático del formulario

    // Validar que no haya campos de radio vacíos
    const preguntas = document.querySelectorAll('.pregunta');

    for (const pregunta of preguntas) {
        const radioButtons = pregunta.querySelectorAll('input[type="radio"]');
        let respuestaSeleccionada = false;

        for (const radioButton of radioButtons) {
            if (radioButton.checked) {
                respuestaSeleccionada = true;
                break;
            }
        }

        if (!respuestaSeleccionada) {
            swal("Advertencia!", "¡Debes seleccionar una respuesta para todas las preguntas antes de enviar!", "info");
            return; // Detiene el envío del formulario si falta una respuesta
        }
    }

    // Si todas las preguntas tienen respuestas seleccionadas, muestra la confirmación
    swal({
        title: "¿Estás seguro?",
        text: "Revisa tus respuestas antes de enviar, si todo está bien puedes continuar presionando OK!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            console.log("Enviando formulario desde el botón.");
            enviarFormulario();
        } else {
            swal("Puedes seguir con tu examen.");
        }
    });
});


</script>
</body>
</html>