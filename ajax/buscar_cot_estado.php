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


            $cliente = $_POST['cliente'];
            $mostrar = mysqli_query($conn, "SELECT * FROM `cotiz` WHERE `name_cliente` = '$cliente'");
            while ($cargar = mysqli_fetch_array($mostrar)) {
                echo '<div class="timelines">
                    <div class="circles-containers">';
            
                if ($cargar['fecha_aprobacion'] != '0000-00-00') {
                    echo '<div class="circles"></div>';
                    echo '<div class="circles"></div>';
                    echo '<div class="rectangulo" data-folio="' . $cargar['folio'] . '">' . $cargar['folio'] . '</div>';
                } else {
                    if ($cargar['fecha_pendiente'] != '0000-00-00') {
                        echo '<div class="circles"></div>';
                        echo '<div class="rectangulo" data-folio="' . $cargar['folio'] . '">' . $cargar['folio'] . '</div>';
                        echo '<div class="circles"></div>';
                    } else {
                        echo '<div class="rectangulo" data-folio="' . $cargar['folio'] . '">' . $cargar['folio'] . '</div>';
                        echo '<div class="circles"></div>';
                        echo '<div class="circles"></div>';
                    }
                }
            
                    // cargar ot
                    $id_cotiz = $cargar['folio'];
                    $ot = mysqli_query($conn, "SELECT * FROM `ot` WHERE `id_cotiz` = '$id_cotiz'");
                    if (mysqli_num_rows($ot) > 0) {
                        while($OtVer = mysqli_fetch_array($ot)){
                            echo '<div class="rectangulo" data-folio="' . $OtVer['id_ot'] . '">' . $OtVer['id_ot'] . '</div>';
                            echo '<div class="circles"></div>';
                            echo '<div class="circles"></div>';
                            echo '<div class="circles"></div>';
                        }
                    }else{
                        echo '<div class="circles"></div>
                                <div class="circles"></div>
                                <div class="circles"></div>
                                <div class="circles"></div>';
                    }
                    echo '</div></div>';


                    echo '<script>
                resultado.addEventListener("mouseenter", function() {
                    this.classList.add("hovered");
                });
                </script>';

                echo ' <script>
                resultado.addEventListener("mouseleave", function() {
                    this.classList.remove("hovered");
                });
                </script>                
                ';
            }
        ?>