<?php
// Conectarse a la base de datos
require_once('../admin/conex.php');

// Obtener los datos enviados por el formulario
$nombre = ucwords(strtolower($_POST['nombre']));
$apellido = ucwords(strtolower($_POST['apellido']));
$rut = $_POST['rut'];
$direccion = utf8_decode($_POST['direccion']);
$region = utf8_decode($_POST['regiones']);
$comuna = utf8_decode($_POST['comunas']);
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$maq1 = $_POST['maq_1'];
$maq2 = $_POST['maq_2'];
$sueldo = $_POST['sueldo'];
$sueldo = str_replace('.', '', $sueldo);
$sueldo = str_replace('$', '', $sueldo);
$experiencia = $_POST['experiencia'];
$message = utf8_decode($_POST['message']);
$pass = $_POST['password1'];
$estado = $_POST['estado'];
$licencia = $_POST['licencia'];
$dir_subida = '../uploads_op/';
$fichero_subido = $dir_subida . basename($_FILES['cv']['name']);

//Buscar si el rut ya existe
$buscar = mysqli_query($conn, "SELECT rut FROM `operadores` WHERE rut = '$rut'");
//Obtener el número de filas obtenidas en la consulta
$num_filas = mysqli_num_rows($buscar);

//Si el número de filas es mayor a 0, significa que el rut ya existe
if ($num_filas > 0) {
    // Devolver los datos en formato JSON
    header('Content-Type: application/json');

    // Devolver una respuesta en formato JSON
    $response = array('success' => false, 'nombre' => $nombre, 'apellido' => $apellido, 'mensaje' => "El rut ya existe");
    echo json_encode($response);
    exit;
}else{
    //GUARDAR EN BD
    $query = "INSERT INTO operadores (nombre, apellidos, rut, id_region, id_ciudad, direccion, email, celular, equipo1, equipo2, id_rango_sueldo, status, comentario, fecha_ingreso, subio_archivo, clave_web, trabajando, experiencia, licencia) VALUES ('".$nombre."', '".$apellido."', '".$rut."', '".$region."', '".$comuna."', '".$direccion."', '".$correo."', '".$telefono."', '".$maq1."', '".$maq2."', '".$sueldo."',1, '".$message."', NOW(), 0, PASSWORD('".$pass."'), '".$estado."', '" . $experiencia . "', '".$licencia."')";

    $rs = mysqli_query($conn,$query);
    $id_operador= mysqli_insert_id($conn);
    $nombre_archivo="NULL";
    $subio_archivo=0;

    if (move_uploaded_file($_FILES['cv']['tmp_name'], $fichero_subido)) {
    
        $subio_archivo=1;
        $nombre_archivo=$id_operador."_".basename($_FILES['cv']['name']);
        $nombre_archivo_2="'".$id_operador."_".basename($_FILES['cv']['name'])."'";
        rename($dir_subida."".basename($_FILES['cv']['name']), "../uploads_op/".$nombre_archivo);
        $query = "UPDATE operadores SET subio_archivo = ".$subio_archivo.", nombre_archivo=".$nombre_archivo_2." WHERE Id=".$id_operador;
        $rs = mysqli_query($conn,$query);
    } 

    if ($rs === TRUE) {
    // Devolver los datos en formato JSON
    header('Content-Type: application/json');

    // Devolver una respuesta en formato JSON
    $response = array('success' => true, 'nombre' => $nombre, 'apellido' => $apellido);
    echo json_encode($response);

    //ENVIO MAIL A contacto@operamaq.cl
    $to = "contacto@operamaq.cl";
    $subject = "Solicitud de Inscripcion desde Pagina Web";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
    $message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi' crossorigin='anonymous'>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
    <title>Operamaq</title>
    <style>
    body {
        background-color: #F4F6F7;
        font-family: 'Open Sans', sans-serif;	
        padding: 20px;
    }
    a {
        color: #fff;
        text-decoration: none;
        background-color: #007bff;
        padding: 10px;
        border-radius: 5px;
    }
    .container {
        position: relative;
        width: 80%;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,.1);
        text-align: center;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }
    </style>
    </head>
    <body>
    <div class='container'>
    <img src='https://operamaq.cl/files/logo.png'>
    <h1>". $subject ."</h1>
    <hr>
    <p><b>Nombre:</b> ".ucwords($_POST["nombre"]) ." ". ucwords($_POST["apellido"]) ."</p>
    <p><b>Rut:</b> ". $_POST["rut"] ."</p>
    <p><b>Direccion:</b> ". $_POST["direccion"] ." <b>Ciudad:</b> ". $comuna ." <b>Region:</b> ". $region ."</p>
    <p>E-Mail: ". $_POST["correo"] ."</p>
    <p><b>Celular:</b> ". $_POST["telefono"] ."</p>
    <p><b>Equipo1:</b> ". $_POST["maq_1"] ." <b>Equipo2:</b> ". $_POST["maq_2"] ."</p>
    <p><b>Pretensiones de Sueldo:</b> ". $sueldo ."</p>";

    if($_POST['trabajando']==0){
    $message .= "<p>Status: Disponible</p>";
    }else{
    $message .= "<p>Status: Trabajando</p>";  
    }

    $message .= "
    <p>Comentario: ". $_POST["message"] ."</p>";

    if($subio_archivo==1){
        
        $message .= "
        <p>Archivo Adjuntado: <a href='https://operamaq.cl/nuevo/uploads_op/".$nombre_archivo."' class='btn btn-primary'>".$nombre_archivo."</a></p>";
        
    }

    $message .= "
    </div>
    </body>
    </html>";
    
    mail($to, $subject, $message, $headers);

    //ENVIO MAIL A OPERADOR INSCRITO
    $to = $_POST["correo"];
    $subject = "Inscripcion de Operador en Operamaq.cl";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
    $message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi' crossorigin='anonymous'>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
    <title>Operamaq</title>
    <style>
    body {
        background-color: #F4F6F7;
        font-family: 'Open Sans', sans-serif;	
        padding: 20px;
    }
    a {
        color: #fff;
        text-decoration: none;
        background-color: #007bff;
        padding: 10px;
        border-radius: 5px;
    }
    .container {
        position: relative;
        width: 80%;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,.1);
        text-align: center;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }
    </style>
    </head>
    <body>
    <div class='container'>
    <img src='https://operamaq.cl/files/logo.png'>
    <h1>". $subject ."</h1>
    <hr>
    <p>Queremos darte la bienvenida a Operamaq Empresas Spa.</p>
    <p>A continuación podrás encontrar los datos que ingresaste.</p>
    <p><b>Nombre:</b> ".ucwords($_POST["nombre"]) ." ". ucwords($_POST["apellido"]) ."</p>
    <p><b>Rut:</b> ". $_POST["rut"] ."</p>
    <p><b>Direccion:</b> ". $_POST["direccion"] ." <b>Ciudad:</b> ". $comuna ." <b>Region:</b> ". $region ."</p>
    <p>E-Mail: ". $_POST["correo"] ."</p>
    <p><b>Celular:</b> ". $_POST["telefono"] ."</p>
    <p><b>Equipo1:</b> ". $_POST["maq_1"] ." <b>Equipo2:</b> ". $_POST["maq_2"] ."</p>
    <p><b>Pretensiones de Sueldo:</b> ". $sueldo ."</p>";

    if($_POST['trabajando']==0){
    $message .= "<p>Status: Disponible</p>";
    }else{
    $message .= "<p>Status: Trabajando</p>";  
    }

    $message .= "
    <p>Comentario: ". $_POST["message"] ."</p>";

    if($subio_archivo==1){
        
        $message .= "
        <p>Archivo Adjuntado: <a href='https://operamaq.cl/nuevo/uploads_op/".$nombre_archivo."' class='btn btn-primary'>".$nombre_archivo."</a></p>";
        
    }

    $message .= "
    </div>
    </body>
    </html>";
    
    mail($to, $subject, $message, $headers);



    } else {
    // Devolver los datos en formato JSON
    header('Content-Type: application/json');

    // Devolver una respuesta en formato JSON
    $response = array('success' => false, 'nombre' => $nombre, 'apellido' => $apellido, 'mensaje' => "Ha ocurrido un error al registrar los datos!");
    echo json_encode($response);
    }
}
?>