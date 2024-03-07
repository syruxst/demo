<?php require_once('../admin/conex.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
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
</style>
<body>
    <?php
    //$Id = openssl_decrypt($_GET['id'], COD, KEY);
    $Id = $_GET['id'];
    $sql=mysqli_query($conn,"SELECT * FROM `operadores` WHERE `Id` = '$Id'");
    while($row=mysqli_fetch_assoc($sql)){
       $nombre=ucwords(strtolower($row['nombre']));
       $apellidos=ucwords(strtolower($row['apellidos']));
       $rut=$row['rut'];
       $email=strtolower($row['email']);
       $celular=$row['celular'];
       $direccion=$row['direccion'];
       $id_ciudad=$row['id_ciudad'];
       $id_region=$row['id_region'];
       $equipo1=$row['equipo1'];
       $equipo2=$row['equipo2'];
       $sueldo=$row['id_rango_sueldo'];
       $licencia=$row['licencia'];
       $experiencia=$row['experiencia'];
       $trabajando=$row['trabajando'];
       $requisitos=$row['cumple_requisitos'];
       $status2=$row['status2'];
       $cv="../uploads_op/".$row['nombre_archivo'];
    }
    ?>
    <form id="form_actualizar" name="form_actualizar">
    <table width="100%" border="0" cellspacing="6" cellpadding="6" class="tabla">
        <tr>
            <td>Nombre :</td>
            <td><?php echo $nombre; ?> <?php echo $apellidos; ?></td>
            <td><b>Id Usuario</b> :</td>
            <td><?php echo $Id;?><input type="hidden" name="iduser" value="<?php echo $Id;?>"></td>
        <tr>
            <td>R.U.T :</td>
            <td colspan="3"><?php echo $rut; ?></td>
        </tr>
        <tr>
            <td>Correo :</td>
            <td><?php echo $email; ?></td>
            <td>Teléfono :</td>
            <td><a target='_blank' href='whatsapp://send?phone<?php echo urlencode($celular);?>&amp;text=hola'><?php echo $celular; ?></td>
        </tr>
        <tr>
            <td>Dirección :</td>
            <td colspan="3"><?php echo $direccion; ?> </td>
        </tr>
        <tr>
            <td>Comuna y Región :</td>
            <td colspan="3"><?php echo $id_ciudad. ", " .$id_region. ""; ?></td>
        </tr>
        <tr>
            <td>Equipo 1:</td>
            <td colspan="3">
                <select name="equipo1" id="equipo1" class="form-control">
                    <option value="0" <?php if($equipo1==0){ echo "selected"; } ?>>Seleccione Equipo 2</option>
                    <option value="13" <?php if($equipo1==13){ echo "selected"; } ?>>Bulldozer D6</option>
                    <option value="1" <?php if($equipo1==1){ echo "selected"; } ?>>Bulldozer D8</option>
                    <option value="14" <?php if($equipo1==14){ echo "selected"; } ?>>Bulldozer D9</option>
                    <option value="15" <?php if($equipo1==15){ echo "selected"; } ?>>Bulldozer D10</option>
                    <option value="2" <?php if($equipo1==2){ echo "selected"; } ?>>Camion Aljibe 15 m3</option>
                    <option value="3" <?php if($equipo1==3){ echo "selected"; } ?>>Camion Aljibe 30 m3</option>
                    <option value="19" <?php if($equipo1==19){ echo "selected"; } ?>>Camion Dumper</option>
                    <option value="24" <?php if($equipo1==24){ echo "selected"; } ?>>Camion Lubricador</option>
                    <option value="23" <?php if($equipo1==23){ echo "selected"; } ?>>Camion Petroleador</option>
                    <option value="4" <?php if($equipo1==4){ echo "selected"; } ?>>Camion Pluma 5 ton</option>
                    <option value="16" <?php if($equipo1==16){ echo "selected"; } ?>>Camion Pluma 8 ton</option>
                    <option value="17" <?php if($equipo1==17){ echo "selected"; } ?>>Camion Pluma 10 ton</option>
                    <option value="18" <?php if($equipo1==18){ echo "selected"; } ?>>Camion Pluma 15 ton</option>
                    <option value="5" <?php if($equipo1==5){ echo "selected"; } ?>>Camion Tolva 20 m3</option>
                    <option value="22" <?php if($equipo1==22){ echo "selected"; } ?>>Cargador Frontal</option>
                    <option value="6" <?php if($equipo1==6){ echo "selected"; } ?>>Excavadora 20-22 Ton.</option>
                    <option value="7" <?php if($equipo1==7){ echo "selected"; } ?>>Excavadora 35 Ton.</option>
                    <option value="8" <?php if($equipo1==8){ echo "selected"; } ?>>Excavadora 50 Ton.</option>
                    <option value="20" <?php if($equipo1==20){ echo "selected"; } ?>>Excavadora 70 Ton.</option>
                    <option value="21" <?php if($equipo1==21){ echo "selected"; } ?>>Excavadora 80 Ton.</option>
                    <option value="9" <?php if($equipo1==9){ echo "selected"; } ?>>Minicargador</option>
                    <option value="10" <?php if($equipo1==10){ echo "selected"; } ?>>Motoniveladora</option>
                    <option value="11" <?php if($equipo1==11){ echo "selected"; } ?>>Retroexcavadora</option>
                    <option value="25" <?php if($equipo1==25){ echo "selected"; } ?>>Rigger</option>
                    <option value="12" <?php if($equipo1==12){ echo "selected"; } ?>>Rodillo Compactador</option>
                </select>    
            </td>
        </tr>
        <tr>
            <td>Equipo 2:</td>
            <td colspan="3">	
            <select name="equipo2" id="equipo2" class="form-control">
                    <option value="0" <?php if($equipo2==0){ echo "selected"; } ?>>Seleccione Equipo 2</option>
                    <option value="13" <?php if($equipo2==13){ echo "selected"; } ?>>Bulldozer D6</option>
                    <option value="1" <?php if($equipo2==1){ echo "selected"; } ?>>Bulldozer D8</option>
                    <option value="14" <?php if($equipo2==14){ echo "selected"; } ?>>Bulldozer D9</option>
                    <option value="15" <?php if($equipo2==15){ echo "selected"; } ?>>Bulldozer D10</option>
                    <option value="2" <?php if($equipo2==2){ echo "selected"; } ?>>Camion Aljibe 15 m3</option>
                    <option value="3" <?php if($equipo2==3){ echo "selected"; } ?>>Camion Aljibe 30 m3</option>
                    <option value="19" <?php if($equipo2==19){ echo "selected"; } ?>>Camion Dumper</option>
                    <option value="24" <?php if($equipo2==24){ echo "selected"; } ?>>Camion Lubricador</option>
                    <option value="23" <?php if($equipo2==23){ echo "selected"; } ?>>Camion Petroleador</option>
                    <option value="4" <?php if($equipo2==4){ echo "selected"; } ?>>Camion Pluma 5 ton</option>
                    <option value="16" <?php if($equipo2==16){ echo "selected"; } ?>>Camion Pluma 8 ton</option>
                    <option value="17" <?php if($equipo2==17){ echo "selected"; } ?>>Camion Pluma 10 ton</option>
                    <option value="18" <?php if($equipo2==18){ echo "selected"; } ?>>Camion Pluma 15 ton</option>
                    <option value="5" <?php if($equipo2==5){ echo "selected"; } ?>>Camion Tolva 20 m3</option>
                    <option value="22" <?php if($equipo2==22){ echo "selected"; } ?>>Cargador Frontal</option>
                    <option value="6" <?php if($equipo2==6){ echo "selected"; } ?>>Excavadora 20-22 Ton.</option>
                    <option value="7" <?php if($equipo2==7){ echo "selected"; } ?>>Excavadora 35 Ton.</option>
                    <option value="8" <?php if($equipo2==8){ echo "selected"; } ?>>Excavadora 50 Ton.</option>
                    <option value="20" <?php if($equipo2==20){ echo "selected"; } ?>>Excavadora 70 Ton.</option>
                    <option value="21" <?php if($equipo2==21){ echo "selected"; } ?>>Excavadora 80 Ton.</option>
                    <option value="9" <?php if($equipo2==9){ echo "selected"; } ?>>Minicargador</option>
                    <option value="10" <?php if($equipo2==10){ echo "selected"; } ?>>Motoniveladora</option>
                    <option value="11" <?php if($equipo2==11){ echo "selected"; } ?>>Retroexcavadora</option>
                    <option value="25" <?php if($equipo2==25){ echo "selected"; } ?>>Rigger</option>
                    <option value="12" <?php if($equipo2==12){ echo "selected"; } ?>>Rodillo Compactador</option>
                </select>  
            </td>
        </tr>
        <tr>
            <td>Sueldo :</td>
            <td>
                <!--<?php //echo "$". number_format($sueldo, 0, ',', '.') . "";?>-->
                <select id="sueldo" name="sueldo" class="form-control" required>
                    <option value="600000" <?php if($sueldo=='600000'){echo "selected";} ?>>$ 600.000</option>
                    <option value="700000" <?php if($sueldo=='700000'){echo "selected";} ?>>$ 7000000</option>
                    <option value="800000" <?php if($sueldo=='800000'){echo "selected";} ?>>$ 800.000</option>
                    <option value="900000" <?php if($sueldo=='900000'){echo "selected";} ?>>$ 900.000</option>
                    <option value="1000000" <?php if($sueldo=='1000000'){echo "selected";} ?>>$ 1.000.000</option>
                    <option value="1100000" <?php if($sueldo=='1100000'){echo "selected";} ?>>$ 1.100.000</option>
                    <option value="1200000" <?php if($sueldo=='1200000'){echo "selected";} ?>>$ 1.200.000</option>
                    <option value="1300000" <?php if($sueldo=='1300000'){echo "selected";} ?>>$ 1.300.000</option>
                    <option value="1400000" <?php if($sueldo=='1400000'){echo "selected";} ?>>$ 1.400.000</option>
                    <option value="1500000" <?php if($sueldo=='1500000'){echo "selected";} ?>>$ 1.500.000</option>
                    <option value="1600000" <?php if($sueldo=='1600000'){echo "selected";} ?>>$ 1.600.000</option>
                    <option value="1700000" <?php if($sueldo=='1700000'){echo "selected";} ?>>$ 1.700.000</option>
                    <option value="1800000" <?php if($sueldo=='1800000'){echo "selected";} ?>>$ 1.800.000</option>
                    <option value="1900000" <?php if($sueldo=='1900000'){echo "selected";} ?>>$ 1.900.000</option>
                    <option value="2000000" <?php if($sueldo=='2000000'){echo "selected";} ?>>$ 2.000.000</option>
                </select>
            </td>
            <td>Experiencia :</td>
            <td>
                <select id="experiencia" name="experiencia" class="form-control" required>
                    <option value="Sin experiencia">Sin experiencia</option>
                    <option value="1 año" <?php if($experiencia=='1 año'){echo "selected";} ?>>1 año</option>
                    <option value="2 años" <?php if($experiencia=='2 años'){echo "selected";} ?>>2 años</option>
                    <option value="3 años" <?php if($experiencia=='3 años'){echo "selected";} ?>>3 años</option>
                    <option value="4 años" <?php if($experiencia=='4 años'){echo "selected";} ?>>4 años</option>
                    <option value="5 años" <?php if($experiencia=='5 años'){echo "selected";} ?>>5 años</option>
                    <option value="6 años" <?php if($experiencia=='6 años'){echo "selected";} ?>>6 años</option>
                    <option value="7 años" <?php if($experiencia=='7 años'){echo "selected";} ?>>7 años</option>
                    <option value="8 años" <?php if($experiencia=='8 años'){echo "selected";} ?>>8 años</option>
                    <option value="9 años" <?php if($experiencia=='9 años'){echo "selected";} ?>>9 años</option>
                    <option value="10 años" <?php if($experiencia=='10 años'){echo "selected";} ?>>10 años</option>
                    <option value="+ 10 años" <?php if($experiencia=='+ 10 años'){echo "selected";} ?>>+ 10 años</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Licencia :</td>
            <td><input type="text" id="licencia" name="licencia" class="form-control" value="<?php echo $licencia; ?>" required maxlength="20" oninput="this.value = this.value.toUpperCase()"></td>
            <td>Estado :</td>
            <td>
                <select name="trabajando" id="trabajando" class="form-control" required>
                    <option value="0" <?php if($trabajando==0){ echo "selected"; } ?>>Disponible</option>
                    <option value="1" <?php if($trabajando==1){ echo "selected"; } ?>>Trabajando</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Revisión :</td>
            <td>
                <select name="revision" id="revision" class="form-control" required>
                    <option value="0" <?php if($requisitos=="0"){ echo "selected";} ?>>Por Revisar</option>
                    <option value="1" <?php if($requisitos=="1"){ echo "selected";} ?>>SI</option>
                    <option value="2" <?php if($requisitos=="2"){ echo "selected";} ?>>NO</option>
            </td>
            <td>Estatus :</td>
            <td>
                <select name="status2" id="status2" class="form-control" required>
                    <option value="" <?php if($status2==''){ echo "selected"; } ?>>Sin definir</option>
                    <option value="MOP" <?php if($status2=='MOP'){ echo "selected"; } ?>>MOP</option>
                    <option value="Mineria" <?php if($status2=='Mineria'){ echo "selected"; } ?>>Mineria</option>
                </select>             
            </td>
        </tr>
        <tr>
            <td>Enviar correo</td>
            <td colspan="3">
                <textarea rows="1" cols="60" width="100%" class="form-control"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="<?php echo $cv; ?>" target="_blank"><button type="button" class="btn btn-warning">VER CURRICULUM</button></a>
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                <input class="btn btn-primary" type="submit" value="GUARDAR CAMBIOS">
            </td>
        </tr>
    </table>
    </form>
<script>
    const licencia = document.getElementById('licencia');

    licencia.addEventListener('input', (event) =>{
        const valorLicencia = licencia.value;
        const nuevaLicencia = valorLicencia.replace(/[^A-D1-5-]/g, '');
        if (valorLicencia !== nuevaLicencia) {
            licencia.value = nuevaLicencia;
        }
    });
</script>
</body>
</html>