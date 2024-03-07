<?php
session_start();
error_reporting(0);
$usuario = $_SESSION['usuario'];
require_once('../admin/conex.php');

$buscar_user = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario = '$usuario'");
$dato_encontrado = mysqli_fetch_array($buscar_user);
$Perfil = $dato_encontrado['permiso'];

$ot = $_POST['ot'];

// validacion de ot 
$validarOt = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE `id_ot` = '$ot' AND `estado` != ''");
$validar = mysqli_num_rows($validarOt);
while($validData = mysqli_fetch_array($validarOt)){ $validOper = $validData['equipo']; }


$buscar = mysqli_query($conn, "SELECT * FROM `ot` WHERE `id_ot` = '$ot'");
while($encontrado = mysqli_fetch_array($buscar)){
    $id_cot = $encontrado['id_cotiz'];

    $buscarCot = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE `folio` = '$id_cot'");

    while($resultado = mysqli_fetch_array($buscarCot)){  
        
        echo  '
        <table width="100%" border="0" cellpadding="4">
            <tr>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">CLIENTE</span>
                        <input class="form-control" name="cliente" id="cliente" type="text" value="' . $resultado['name_cliente'] . '" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">FAENA</span>
                        <input class="form-control" name="faena" id="faena" type="text" value="' . $resultado['faena'] . '" readonly>
                    </div>                    
                </td>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">N° COT</span>
                        <input class="form-control" name="cotizacion" id="cotizacion" type="text" value="' . $resultado['folio'] . '" readonly>
                    </div>                    
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">CONTACTO</span>
                        <input class="form-control" name="contacto" id="contacto" type="text" value="' . $resultado['contacto'] . '" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">TELÉFONO</span>
                        <input class="form-control" name="telefono" id="telefono" type="text" value="' . $resultado['telefono'] . '" readonly>
                    </div>                    
                </td>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">CORREO</span>
                        <input class="form-control" name="mail" id="mail" type="text" value="' . $resultado['correo'] . '" readonly>
                    </div>                    
                </td>
            </tr>
        </table>
        <hr>
        ';
        $detalle = mysqli_query($conn, "SELECT * FROM `serviceCot` WHERE `id_cotiz` = '$id_cot'");
        echo '<table width="100%" class="tabla table table-striped" style="font-size: 12px;">';
        echo '
            <tr>
                <th>
                Detalle de la Cotización
                </th>
            </tr>
        ';
        while($verDet = mysqli_fetch_array($detalle)){
            $tipo = $verDet['tipo'];

            $cantidad_query = mysqli_query($conn, "SELECT SUM(cantidad) as total_cantidad 
                        FROM `serviceCot` 
                        WHERE `id_cotiz` = '$id_cot'
                        AND `detalle` NOT LIKE '%TRASLADO%'
                        AND `detalle` NOT LIKE '%SUM%'
                        AND `detalle` NOT LIKE '%MODELO%'");
            $total_cantidad_row = mysqli_fetch_assoc($cantidad_query);
            $total_cantidad = $total_cantidad_row['total_cantidad'];

            $cantidad = $verDet['cantidad'];
            echo '<tr><td>' . $verDet['detalle'] . '</td></tr>';
        }
        echo '</table>';
        
        echo '<button type="button" class="btn btn-primary" id="agregarEquipo" > <i class="fa fa-truck" aria-hidden="true"></i> Agregar Nuevo Equipo</button> &nbsp;';
        echo '<button type="button" class="btn btn-warning" id="guardarFilas"> <i class="fa fa-save" aria-hidden="true"></i> REGISTRAR OT </button> &nbsp;';       
        //echo '<button type="button" class="btn btn-warning" id="guardarTodasFilas"> <i class="fa fa-save" aria-hidden="true"></i> REGISTRAR OT </button> &nbsp;';       
        //echo '<button type="button" class="btn btn-success" id="cerrarOt"><i class="fa fa-save" aria-hidden="true"></i> CERRAR OT</button><br><br>';
        echo '<br><br>';
        echo '<div class="contenidos">';

            $cargarOt = mysqli_query($conn, "SELECT * FROM `detallle_ot` WHERE `id_ot` = '$ot'");
            $numRow = mysqli_num_rows($cargarOt);
            // Calcular cuántas filas adicionales agregar
            $filas_a_agregar = $total_cantidad - $numRow;

            // Asegurarse de que nunca se agreguen filas negativas
            if ($filas_a_agregar < 0) {
                $filas_a_agregar = 0;
            }
            echo '<table width="100%" border="1" cellpadding="4" class="tabla table table-striped" style="font-size: 12px;">
            <tr>
                <th>
                    ITEM
                </th>
                <th>
                    EQUIPO
                </th>
                <th>PATENTE</th>
                <th>
                    FECHA
                </th>
                <th>
                    IP
                </th>
                <th>
                    FOLIO
                </th>
                <th>
                    INF
                </th>
                <th>
                    VB
                </th>
                <th>
                    AUD.
                </th>
            </tr>';

            for ($i = 0; $i < $filas_a_agregar; $i++) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . ' <i class="fa fa-floppy-o save fa-1x" aria-hidden="true" title="GUARDAR FILA DE DATOS" style="color: #FF5733;"></i><input type="hidden" name="id_ot[]" value="' . $ot . '" class="custom-input"></td>';

                echo '<td><select name="equipo[]" class="custom-input" data-column="equipo">';
                    $equipo = mysqli_query($conn, "SELECT * FROM `equiposInsp` ORDER BY `equipo` ASC");
                    while($verEquipo = mysqli_fetch_array($equipo)){
                        echo '<option value="' . $verEquipo['equipo'] . '">' . $verEquipo['equipo'] . '</option>';
                    }
                echo '</select></td>';
                echo '<td><input type="text" name="patente[]" class="custom-input patente-input" placeholder="PATENTE" data-row="' . $i . '"  style="width: 120px;" maxlength="6" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></td>';
                echo '<td><input type="date" name="fecha[]" class="custom-input"></td>';
                echo '<td><select name="insp[]" class="custom-input">';
                $seachInsp = mysqli_query($conn, "SELECT * FROM `insp_eva` ORDER BY `ip` ASC");
                while($rst =  mysqli_fetch_array($seachInsp)){
                    echo '<option value="'.$rst['ip'] .'" title="' . $rst['name'] . '">'.$rst['ip'].'</option>';
                }
                echo '</select></td>'; 
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
            }
            if ($numRow > 0) {
                while ($row = mysqli_fetch_array($cargarOt)) {

                    $icon = '';
                    $color = '';

                    if($row['informe'] == 'APROBADO'){

                        $icon = 'check';
                        $color = '#3FFF33';
                        $document = 'data-document='.$row['id'].'';
                        $infoDocument = 'infoDocument';

                    }else if($row['informe'] == 'RECHAZADO'){

                        $icon = 'times';
                        $color = '#FC0404';
                        $document = 'data-document='.$row['id'].'';
                        $infoDocument = 'infoDocument';

                    }else {

                        $icon = 'info-circle';
                        $color = '#FAD403';
                        $document = '';
                        $infoDocument = '';

                    }

                    $color = "<i class='fa fa-$icon fa-2x' aria-hidden='true' style='color: $color;''></i>";

                    if($row['estado'] == ''){

                        $ICON = 'info-circle';
                        $COLOR = '#FAD403';
                        $DATA = 'data-informe="'.$row['id'].'"';

                    }elseif($row['estado'] == 'APROBADO') {

                        $ICON = 'check';
                        $COLOR = '#3FFF33';
                        $DATA = 'data-informe="'.$row['id'].'"'; 

                    }else{

                        $ICON = 'times';
                        $COLOR = '#FF0000';
                        $DATA = 'data-informe="'.$row['id'].'"';   

                    }

                    if ($Perfil === 'coordinador' || $Perfil === 'administrador') {
                        $COLOR = "<i class='fa fa-$ICON fa-2x info' aria-hidden='true' style='color: $COLOR;' title='$row[estado]' ".$DATA."></i>";
                    } else {
                        $COLOR = "";
                    }

                    echo '<tr>'; 
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td>'.$row['equipo'].'</td>';
                    echo '<td>'.$row['patente'].'</td>';
                    echo '<td>'.date("d-m-Y", strtotime($row['fecha'])).'</td>';
                    echo '<td>'.$row['ip'].'</td>';
                    echo '<td style="font-size:10px;">'.$row['folio'].'</td>';
                    echo '<td>'.$color.'</td>';
                    echo '<td>'.$COLOR.'</td>';
                    echo '<td></td>';
                    echo '</tr>';

                    $i++;
                }
               
            }
    }
}

$timezone = new DateTimeZone('America/Santiago');
$now = new DateTime("now", $timezone); 
$fecha = $now->format("Y-m-d");

    if($validar == $total_cantidad){
        $actualizarOt = "UPDATE `ot` SET `estado` = 'CERRADO', `date_cierre` = '$fecha', `user_cierre` = '$usuario' WHERE `id_ot` = '$ot'";
        $ejecutar = mysqli_query($conn, $actualizarOt);
        if ($ejecutar) {
            echo '<script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>';
            echo '<script> 
                    swal({
                        title: "Bien hecho!",
                        text: "La OT ha sido cerrada correctamente!",
                        icon: "success"
                    }).then(function() {
                        window.location.href = "crear_ot.php"; // Redirige a tu página
                    });
                  </script>';
            exit; 
        }
    }

$conn->close();

?>