<?php
session_start();
require_once('../admin/conex.php');
$usuario = $_GET['nombre'];

// Verificar si alguna de las dos variables de sesión existe
if (isset($_SESSION['operador']) || isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión que exista
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
    $Telefono = $ver['celular'];
    $Direccion = $ver['direccion'];
    $comunaSeleccionada = $ver['id_ciudad'];
    $regionSeleccionada = $ver['id_region'];
    $Licencia = $ver['licencia'];
    $Familia = $ver['familia'];
    $foto_licencia = $ver['foto_licencia'];

     // Verificamos si algún dato está vacío o nulo
     if (empty($Nombre) || empty($Apellidos) || empty($Rut) || empty($Email) || empty($Telefono) || empty($Direccion) || empty($comunaSeleccionada) || empty($regionSeleccionada) || empty($Licencia) || empty($Familia) || empty($foto_licencia)) {
        $datosCompletos = false; // No todos los datos están completos
    }
    else {
        $datosCompletos = true; // Todos los datos están completos
    }
}
// Mostrar el botón si todos los datos están completos

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Document</title>
    <style>
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        .container p {
            color: #E0320F ;
            text-align: justify;
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
            border: 1px solid #e5e5e5;
            margin: 5px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);

        }
        /* Estilos para la clase "col" */
        .col {
            background-color: #ffffff;
            padding: 5px;
            border-radius: 3px;
            margin: 3px;
            width: 50%; 
            float: left; 
            box-sizing: border-box;
            text-align: left;
        }
        @media (max-width: 666px) {
            body {
                padding: 10px;
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
    </style>
</head>
<body>
    Hola <?php echo $nombre; ?>
    <div class="container">
        <div class="tabla">
            <?php
                if ($datosCompletos) {
                    $buscarExamenesPendiente = mysqli_query($conn, "SELECT * FROM detallle_ot WHERE rut = '$Rut' AND resultado = ''");
                        while($verificar = mysqli_fetch_array($buscarExamenesPendiente) ){
                            $equipo = $verificar['equipo'];
                            echo '<div class="row"><div class="col">';
                            echo '<img src="../img/exam_3403561.png" alt="Operador" width="30px" height="30px" style="margin-right: 5px;"> <label>Tienes un examen de ' . $equipo . ' pendiente!</label>';
                            echo '</div>';    
                            echo '<div class="col">';
                            echo '<button type="button" name="botonExmane[]" class="btn btn-success" data-examen="examen" data-equipo="' . $equipo . '" data-bs-toggle="modal" data-bs-target="#exampleModal"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Realizar examen</button>';                            echo '</div></div>';
                        }                        
                }else{
                    echo 'Para poder realizar el examen, debes completar tus datos personales. <i class="fa fa-arrow-right" aria-hidden="true"></i> ';
                    echo '<a href="datos.php?nombre=' . $usuario . '" class="btn btn-primary">Completar datos</a>';
                    echo  '<hr><br><br>';
                }
            ?>                
        </div>
        <p>Nota Importante: Si por alguna razón sales de la plataforma cuando ya has comenzado un examen, este quedará como realizado. Por lo que deberás comunicarte con tu supervisor para que te lo vuelva a asignar.</p>
<script>
    var examenes = document.querySelectorAll('[data-examen="examen"]');

    examenes.forEach(function (examen) {
        examen.addEventListener('click', function () {
            var equipo = this.getAttribute('data-equipo');

            swal({
                title: "¿Estás seguro?",
                text: "Una vez que comiences el examen, no podrás detenerlo.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    swal("Exito en tu examen!", {
                        icon: "success",
                    }).then(function () {
                        window.location = "examen.php?nombre=<?php echo $usuario; ?>&examen=" + encodeURIComponent(equipo);
                    });
                } else {
                    swal("El examen ha sido cancelado!");
                }
            });
        });
    });

</script>
</body>
</html>