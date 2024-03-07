<?php
require_once('../admin/conex.php');
session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: formulario_inicio_sesion.php");
    exit();
}
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    $sql = mysqli_query($conn, "SELECT * FROM empresa WHERE id = '$nombre'");
        while($resultado = mysqli_fetch_array($sql)){
            $Nombre = $resultado['nombre'];
            $Rut = $resultado['rut'];
            $Giro = $resultado['giro'];
            $Direccion = $resultado['direccion'];
            $Region = $resultado['comuna'];
            $Comuna = $resultado['ciudad'];
            $Contacto = $resultado['contacto'];
            $Correo = $resultado['correo'];
            $Telefono = $resultado['telefono'];
        }
} else {
    echo "Nombre no proporcionado";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .centro {
            position: fixed; /* Posicionamiento fijo */
            top: 50%; /* Centrar verticalmente */
            left: 50%; /* Centrar horizontalmente */
            transform: translate(-50%, -50%); /* Ajustar para que el centro del div coincida con el centro de la pantalla */
            width: 80%; /* Puedes ajustar esto según tus necesidades */
            height: 400px; /* Puedes ajustar esto según tus necesidades */
            background-color: #f3f3f3; /* Puedes ajustar esto según tus necesidades */
            border-radius: 10px;
            padding: 10px;
        }
        .tablaDiv{
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
            width: 100%;    
            height: 100%;
        }        
        .row {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: auto;
        }
        .col {
            flex-grow: 1;
            height: 100%;
            width: 100%;
            padding: 5px;
            margin: 5px;
        } 
    </style>
</head>
<body>
    <button id="cerrarBtn" type="button" class="btn btn-primary">Cerrar</button>
    <div class="centro">
        <div class="tablaDiv">
            <div class="row">
                <div class="col">
                    <input type="hidden" name="nombre" id="nombreDIV" value="<?php echo $nombre;?>">
                    <input type="text" name="empresa" id="empresaDIV" placeholder="Nombre de la empresa" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  oninput="allowLettersOnly(this)" value="<?php echo $Nombre;?>" autocomplete="off" required>
                </div>
                <div class="col">
                    <input type="text" name="rut" id="rutDIV" placeholder="R.U.T" class="form-control" style="text-transform:uppercase;" oninput="formatRUT(this)" maxlength="10" value="<?php echo $Rut;?>" autocomplete="off" readonly>
                </div>
                <div class="col">
                    <input type="text" name="giro" id="giroDIV" placeholder="Giro" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" oninput="allowLettersOnly(this)" value="<?php echo $Giro;?>" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="direccion" id="direccionDIV" placeholder="Dirección" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $Direccion;?>" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="region" id="region" placeholder="Región" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $Region;?>" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="comuna" id="comuna" placeholder="Comuna" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?php echo $Comuna;?>" autocomplete="off">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="text" name="contacto" id="contactoDIV" placeholder="Contacto" class="form-control" style="text-transform:capitalize;" oninput="allowLettersOnly(this)" value="<?php echo $Contacto;?>" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="telefono" id="telefonoDIV" placeholder="Teléfono" class="form-control" oninput="formatPhone(this)" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="14" value="<?php echo $Telefono;?>" autocomplete="off">
                </div>
                <div class="col">
                    <input type="text" name="correo" id="correoDIV" placeholder="Correo Electronico" class="form-control" onblur="validateEmail(this)" style="text-transform:lowercase;" value="<?php echo $Correo;?>" autocomplete="off">
                    <span id="emailError" style="color: red;"></span>                
                </div>
            </div>
            <div class="row">
                <div class="col">
                <button type="button" class="btn btn-primary" id="guardarCambiosBtn"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar Cambios</button>                    
                <button type="button" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar Registro</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>