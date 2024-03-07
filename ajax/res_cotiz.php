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
    } 
} else {
    header("Location: ../logInsp.php");
    exit();
}

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
        :root {
            --color: #04C9FA;
        }
        body{
            font-family: 'Roboto', sans-serif;
            padding: 50px;
        }
        a {
            text-decoration: none;
            cursor: pointer;
            transform: scale(1); 
            transition: transform 0.2s; 
        }
        a:hover {
            transform: scale(1.3); 
            color: var(--color);
        }
        .container {
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #A6A7A7;
            text-align: center;
        }
        table{
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1{
            color: var(--color);
        }
        h3{
            color: var(--color);
        }
        i {
            cursor: pointer;
            transform: scale(1); 
            transition: transform 0.2s; 
        }

        i:hover {
            transform: scale(1.3); 
            color: var(--color);
        }
        @media (max-width: 666px) {
            body {
                padding: 20px;
            }
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body background="white">
<div class="container">
<center><h4>RESUMEN DE COTIZACIONES</h4></center>
<table width="100%" border="0" class="table table-striped">
    <tr>
        <th>N°</th>
        <th>TIPO</th>
        <th>FOLIO</th>
        <th>OC</th>
        <th>OT</th>
        <th>FECHA</th>
        <th>EMPRESA</th>
        <th>VALOR</th>
        <th>STATUS</th>
        <th>VENDEDOR</th>
    </tr>
    <?php
    $sql = mysqli_query($conn, "SELECT * FROM `cotiz` ORDER BY `cotiz`.`id_cotiz` DESC");
    $n = 1;
    $totalValor = 0; // Variable acumuladora

    while ($ver = mysqli_fetch_array($sql)) {
        $id_cotiz = $ver['id_cotiz'];
        $ot_encoded = base64_encode($id_cotiz);
        $valor = $ver['valor'];
        
        if($ver['ruta'] != ''){
            $ruta =  '<a href="'.$ver['ruta'].'" target="_blank" title="VER ORDEN DE COMPRA"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
        }
        else{
            $ruta = '';
        }

        // Agregar el valor al total
        $totalValor += $valor;

        $sqli = mysqli_query($conn, "SELECT * FROM `ot` WHERE `id_cotiz` = '".$id_cotiz."'");
        $veri = mysqli_fetch_array($sqli);
        $id_ot = $veri['id_ot'];
        $Ot = base64_encode($id_ot);
        echo '<tr>';
        echo '<td>'.$n.'</td>';
        echo '<td>'.$ver['tipo'].'</td>';
        echo '<td><a href="cotizacionPdf.php?cot='.$ot_encoded.'" target="_blank" title="VER DOCUMENTO">'.$ver['folio'].'</a></td>';
        echo '<td>'.$ruta.'</td>';
        echo '<td><a href="ot_pdf.php?ttw='.$Ot.'" title="VER ORDEN DE OTRABAJO">'.$id_ot.'</a></td>';
        echo '<td>'.date("d-m-Y", strtotime($ver['fecha_creacion'])).'</td>';
        echo '<td>'.$ver['name_cliente'].'</td>';
        echo '<td align="right"> $ '.number_format($valor, 0, '', '.').'</td>';
        echo '<td>'.$ver['estado'].'</td>';
        echo '<td>'.$ver['user_creacion'].'</td>';
        echo '</tr>';
        $n++;
    }
    ?>
</table>
Total de cotizaciones: $ <?php echo number_format($totalValor, 0, '', '.'); ?>
</div>
</body>
</html>