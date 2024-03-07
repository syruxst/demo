<?php session_start(); error_reporting(1);
// Conectarse a la base de datos
require_once('../admin/conex.php');
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
    $buscarUser = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$usuario'");
    $row = mysqli_fetch_array($buscarUser);
    $perfil = $row['permiso'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php $folio = $_GET['folio']; 
    $cot = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE `folio` = '$folio'");
        
        while($ver = mysqli_fetch_array($cot)){ 
            $ESTADO = $ver['estado'];
        ?>
        
            <table width="100%" border="0" cellpadding="6">
                <tr>
                    <td>
                        Cliente
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        <?php echo $ver['name_cliente']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Faena
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        <?php echo $ver['faena']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Contacto
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        <?php echo ucwords(strtolower($ver['contacto'])); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Teléfono
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        <?php echo $ver['telefono']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Correo
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        <?php echo strtolower($ver['correo']); ?>
                    </td>
                </tr>
            </table>
            <hr>
            <label>Elavorada por : <?php echo $ver['user_creacion'] ?> el día: <?php echo date("d-m-Y", strtotime($ver['fecha_creacion']));?></label>
            <hr>
            <label>Detalles</label>
            <table width="100%" cellpadding="6" class="table table-striped">
                <tr>
                    <th>
                        N°
                    </th>
                    <th>
                        Serv.
                    </th>
                    <th>
                        V. Uni.
                    </th>
                    <th>
                        Des.
                    </th>
                    <th>
                        Total
                    </th>
                </tr>
            <?php

                $detalles = mysqli_query($conn, "SELECT * FROM `serviceCot` WHERE `id_cotiz` = '$folio'");
                while($rst = mysqli_fetch_array($detalles)){ ?>

                    <tr>
                        <td><?php echo $rst['cantidad']; ?></td>
                        <td><?php echo $rst['servicio']; ?></td>
                        <td><?php echo $rst['unitario']; ?></td>
                        <td><?php echo $rst['descuento']; ?></td>
                        <td><?php echo $rst['total']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-size: 12px;"><?php echo $rst['detalle']?></td>
                    </tr>
                    
            <?php
                } if($ESTADO != 'APROBADO' ){
            ?>
            </table>
            <form  method="post" enctype="multipart/form-data">
                <div class="input-group input-group-sm mb-3">
                    <input type="file" name="pdf" id="pdf" class="form-control" accept=".pdf" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    <input type="hidden" name="folios" id="folios" value="<?php echo $folio; ?>">
                    <input type="hidden" name="botonPresionado" id="botonPresionado" value="">
                    <button type="button" name="btnPendiente" title="Subir Orden de Compra" class="btn btn-primary" id="subirBtn">Subir</button>                
                </div>
            </form>
            <hr>
            <form id="cotizacionForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="folios" id="folios" value="<?php echo $folio; ?>">
            <?php
                if($ver['ruta'] != ''){
                    if ($perfil == 'administracion' || $perfil == 'administrador') {
                        echo '<div class="tablaDiv">
                            <div class="row">
                                <div class="col">
                                    <a href="'.$ver['ruta'].'" target="_blank" title="VER PDF"> Puedes ver la cotizacion aquí <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
                                </div>
                                <div class="col">

                                <div class="input-group input-group-sm mb-3">
                                <select name="estado" id="estado" class="form-control">
                                    <option value="APROBADO">APROBADO</option>
                                    <option value="RECHAZADO">RECHAZADO</option>
                                </select>
                                <input type="hidden" name="botonPresionado" id="botonPresionado" value="">
                                <button type="button" name="btnRevisado" title="Actualizar estado" class="btn btn-success" id="actualizarBtn">REVISADO</button>
                                </div>
                                    
                                </div>
                            </div>
                        </div>';
                    }

                }
            ?>
            </form>
    <?php 
        }
    }
    ?>
</body>
</html>
