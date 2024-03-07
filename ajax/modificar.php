<?php require_once('../admin/conex.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            padding: 0;
        }
        a {
        text-decoration: none;
        color: black;
        }
        .contenido{
            width: 100%;
            height: auto;
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="contenido">
<table border="0" width="100%" style="font-size: 12px;" cellspacing="6">
    <tr>
        <td>
            <select class="form-control">
                <option value="0">Seleccione</option>
                <?php 
                    $buscar = mysqli_query($conn, "SELECT * FROM `operadores` ORDER BY `nombre` ASC");
                    while ($result = mysqli_fetch_array($buscar)){
                        echo '<option value="'.$result['id'].'">'.ucwords(strtolower($result['nombre'])).' '.ucwords(strtolower($result['apellidos'])).'</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
</table>
</div>
</body>
</html>