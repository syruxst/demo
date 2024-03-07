<?php session_start(); error_reporting(0);
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
?>
<!DOCTYPE html>
<html lang="en">
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
        :root {
            --color: #04C9FA;
            --primary-bg: #f4f4f4;
            --form-bg: #fff;
            --form-border: #e5e5e5;
            --button-bg: #4caf50;
            --button-hover-bg: #45a049;
            --text-color: #333;
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding: 50px;
            background-color: var(--primary-bg);
            color: var(--text-color);
        }

        h1 {
            color: var(--color);
        }

        hr {
            border: 1px solid var(--form-border);
        }

        form {
            background-color: var(--form-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: var(--button-bg);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--button-hover-bg);
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            justify-content: center;
        } 
        .item {
            width: 200px;
            height: 80px;
            float: left;
            cursor: pointer;
            border: 1px solid #e5e5e5;
            transition: box-shadow 0.3s;
            border-radius: 10px;
            display: flex;
            justify-content: center; 
            align-items: center; 
            flex: 0 0 calc(25% - 20px); 
            margin: 10px;
            text-align: center;
            width: 300px;
        }

        .item:hover {
            border: 1px solid var(--color);
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);
        } 
        a {
            text-decoration: none;
            color: #333;
        }
        a:hover{
            text-decoration: none;
            color: var(--color);
        }
    </style>
</head>
<body>
<center><h1>REVISIÓN DOCUMENTAL</h1></center>
    <hr>
    <?php
        $doc = $_GET['data_document'];
        $sql = "SELECT * FROM `detallle_ot` WHERE id = '$doc'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);

        $rut = $row['rut'];
        $empresa = $row['empresa'];
        $faena = $row['faena'];
        $equipo = $row['equipo'];

        $query = "SELECT * FROM `operadores` WHERE rut = '$rut'";
        $resultado = mysqli_query($conn, $query);
        $fila = mysqli_fetch_array($resultado);

    ?>
    <form id="miFormulario">
        <table width="100%" border="0">
            <tr>
                <td>
                    <label>
                        Nombre del Candidato: <?php echo $fila['nombre']." ".$fila['apellidos']; ?>
                    </label>
                </td>
                <td>
                    <label>
                        Rut: <?php echo $fila['rut']; ?>
                    </label>
                </td>
                <td>
                    <label>
                        Tipo de Licencia: <?php echo $fila['licencia'] ?> 
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        Empres : <?php echo $empresa; ?>
                    </label>
                </td>
                <td>
                    <label>
                        Faena : <?php echo $faena; ?> 
                    </label>
                </td>
                <td>
                    <label>
                        Equipo: <?php echo $equipo; ?>
                    </label>
                </td>
            </tr>
        </table>
        <div class="container">
            <a href="https://acreditasys.tech/uploads_op/<?php echo $fila['nombre_archivo'];?>" target="_blank"><div class="item" title="VER CURRICULUM"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; &nbsp; CURRILUM   </div></a>
            <a href="https://acreditasys.tech/licencias/<?php echo $fila['foto_licencia'];?>" target="_blank"><div class="item" title="VER LICENCIA DE CONDUCIR"><i class="fa fa-address-card-o" aria-hidden="true"></i> &nbsp; &nbsp; LICENCIA CONDUCIR  </div></a>
        </div>
        <label>
            <input type="hidden" name="id" value="<?php echo $doc; ?>">
            <input type="radio" name="opcion" value="SI"> Sí
        </label>
        <br>
        <label>
            <input type="radio" name="opcion" value="NO"> No
        </label>
        <br>
        <button type="button" onclick="enviarDatos()">Guardar</button>
    </form>
</body>
</body>
<script>
    function enviarDatos() {
        var formData = new FormData(document.getElementById("miFormulario"));
        var xhr = new XMLHttpRequest();

        xhr.open("POST", "save_doc.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        swal({
                            title: "Bien hecho!",
                            text: "Operación exitosa: " + response.message,
                            icon: "success",
                            button: "Aceptar!",
                        });
                    } else {
                        swal({
                            title: "Algo sali mal",
                            text: "Fallo: " + response.message + "!",
                            icon: "error",
                            button: "Aceptar!",
                        });
                    }
                } catch (e) {
                    console.error('Error al analizar la respuesta JSON: ' + e);
                }
            } else {
                console.error('Error en la solicitud. Estado: ' + xhr.status);
            }
        };

        xhr.send(formData);
    }
</script>
</html>