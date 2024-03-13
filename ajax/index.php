<?php
session_start();
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
    header("Location: ../index.php");

    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="../img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../img/icons/ms-icon-144x144.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Administración ::</title>
    <script>
			$(document).ready(function() {
				$('li').click(function () {
					var url = $(this).attr('rel');
					$('#iframe').attr('src', url);
					$('#iframe').reload();
				});
			});	
	</script>
    <style>
        a{
            text-decoration: none;
            color: #000;
        }
        a:hover{
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="contenedor">
<div class="menu">
        <ul>
        <?php if($perfil == 'administracion'){ ?>
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li rel="estado_resultado.php">EST. RESULTADO <i class="fa fa-line-chart" aria-hidden="true"></i></li>
                <li>VENTAS
                    <ul class="submenu">
                        <li rel="crearEmpresa.php">CREAR EMPRESA</li>
                        <li rel="crearCotizacion.php">CREAR COTIZACION</li>
                        <li rel="buscar_Cotizacion.php">SUBIR OC.</li>
                        <li rel="res_cotiz.php">RESUMEN COTIZACIÓN</li>
                    </ul>
                </li> 
                <li>OF. TECNICA
                    <ul class="submenu">
                        <li rel="servicios_realizados.php">SERVICIOS REALIZADOS</li>
                        <li rel="prog_semanal.php">PROGRAMA SEMANAL</li>    
                        <li rel="resumen_ot.php">RESUMEN OT</li>   
                    </ul>
                </li>
                <li>ADMINISTRACION
                    <ul class="submenu">
                        <li rel="buscar_Cotizacion.php">APROBACION COT.</li>
                        <li rel="administracion/administracion/">ADMIN. CHILEVALORA</li>
                        <li rel="inn/administracion/">ADMIN. INN</li>
                        <li rel="certificado.php">CERTIFICADOS</li>
                        <li rel="estadoPago.php">EDP Evaluador</li>
                    </ul>            
                </li>
                <li>SGC
                    <ul class="submenu">
                        <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                        <li rel="inn/inn.php">CALIDAD INN</li>
                    </ul>
                </li>
            <?php } if($perfil == 'calidad'){ ?> 
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li>VENTAS
                    <ul class="submenu">
                        <li rel="crearEmpresa.php">CREAR EMPRESA</li>
                        <li rel="crearCotizacion.php">CREAR COTIZACION</li>
                        <li rel="buscar_Cotizacion.php">SUBIR OC.</li>
                        <li rel="res_cotiz.php">RESUMEN COTIZACIÓN</li>
                    </ul>
                </li> 
                <li>OF. TECNICA
                    <ul class="submenu">
                        <li rel="crear_ot.php">CREAR OT</li>     
                        <li rel="crearPrueba.php">ADMINISTRAR PRUEBAS</li>    
                        <li rel="buscar_Cotizacion.php">FLUJO PROCESO</li>   
                        <li rel="servicios_realizados.php">SERVICIOS REALIZADOS</li>
                        <li rel="prog_semanal.php">PROGRAMA SEMANAL</li>    
                        <li rel="resumen_ot.php">RESUMEN OT</li>   
                    </ul>
                </li>
                <li>SGC
                    <ul class="submenu">
                        <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                        <li rel="inn/inn.php">CALIDAD INN</li>
                    </ul>
                </li>
            <?php } if($perfil == 'administrador'){
                if($usuario == 'dugalde'){
                   echo '<li rel="maestro/home.php/">MAESTRO <i class="fa fa-cog" aria-hidden="true"></i></li>';
                }
            ?>
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li>RENTAL OPERADORES
                    <ul class="submenu">
                        <li rel="oper.php">OPERADORES</li>
                        <li rel="crear_oper.php">CREAR OPERADOR</li>
                        <li rel="cotizacion.php">NOMINA</li>
                    </ul>
                </li>
                <li>VENTAS
                    <ul class="submenu">
                        <li rel="crearEmpresa.php">CREAR EMPRESA</li>
                        <li rel="crearCotizacion.php">CREAR COTIZACION</li>
                        <li rel="buscar_Cotizacion.php">SUBIR OC.</li>
                        <li rel="res_cotiz.php">RESUMEN COTIZACIÓN</li>
                    </ul>
                </li> 
                <li>OF. TECNICA
                    <ul class="submenu">
                        <li rel="crear_ot.php">CREAR OT</li>     
                        <li rel="crearPrueba.php">ADMINISTRAR PRUEBAS</li>    
                        <li rel="buscar_Cotizacion.php">FLUJO PROCESO</li>   
                        <li rel="servicios_realizados.php">SERVICIOS REALIZADOS</li>
                        <li rel="prog_semanal.php">PROGRAMA SEMANAL</li>    
                        <li rel="resumen_ot.php">RESUMEN OT</li>   
                    </ul>
                </li>
                <li>FINANCIERO
                    <ul class="submenu">
                        <li rel="estado_resultado.php">EST. RESULTADO <i class="fa fa-line-chart" aria-hidden="true"></i></li>
                        <li rel="kpi.php">Kpi Admin</li>
                        <li rel="kpi_costos.php">Costos</li>
                    </ul>
                </li>
                <li>ADMINISTRACION
                    <ul class="submenu">
                        <li rel="buscar_Cotizacion.php">APROBACION COT.</li>
                        <li rel="administracion/administracion/">ADMIN. CHILEVALORA</li>
                        <li rel="inn/administracion/">ADMIN. INN</li>
                        <li rel="certificado.php">CERTIFICADOS</li>
                        <li rel="estadoPago.php">EDP Evaluador</li>
                    </ul>            
                </li>
                <li>SGC
                    <ul class="submenu">
                        <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                        <li rel="inn/inn.php">CALIDAD INN</li>
                    </ul>
                </li>
            <?php } if($perfil == 'coordinador'){ ?> 
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li>OF. TECNICA
                    <ul class="submenu">
                        <li rel="crear_ot.php">CREAR OT</li>     
                        <li rel="crearPrueba.php">ADMINISTRAR PRUEBAS</li>    
                        <li rel="buscar_Cotizacion.php">FLUJO PROCESO</li>   
                        <li rel="servicios_realizados.php">SERVICIOS REALIZADOS</li>
                        <li rel="prog_semanal.php">PROGRAMA SEMANAL</li>    
                        <li rel="resumen_ot.php">RESUMEN OT</li>   
                    </ul>
                </li>
                <li>SGC
                    <ul class="submenu">
                        <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                        <li rel="inn/inn.php">CALIDAD INN</li>
                    </ul>
                </li>
            <?php } if ($perfil == 'oftecnica'){ ?>
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li>OF. TECNICA
                    <ul class="submenu">
                        <li rel="crear_ot.php">CREAR OT</li>     
                        <li rel="crearPrueba.php">ADMINISTRAR PRUEBAS</li>    
                        <li rel="buscar_Cotizacion.php">FLUJO PROCESO</li>   
                        <li rel="servicios_realizados.php">SERVICIOS REALIZADOS</li>
                        <li rel="prog_semanal.php">PROGRAMA SEMANAL</li>    
                        <li rel="resumen_ot.php">RESUMEN OT</li>   
                    </ul>
                </li>
                <li>SGC
                    <ul class="submenu">
                        <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                        <li rel="inn/inn.php">CALIDAD INN</li>
                    </ul>
                </li>            
            <?php } if($perfil == 'venta'){?> 
                <li rel="home.php">HOME <i class="fa fa-home" aria-hidden="true"></i></li>
                <li rel="data_kpi.php">KPI <i class="fa fa-bar-chart" aria-hidden="true"></i></li>
                <li>VENTAS
                <ul class="submenu">
                    <li rel="crearEmpresa.php">CREAR EMPRESA</li>
                    <li rel="crearCotizacion.php">CREAR COTIZACION</li>
                    <li rel="buscar_Cotizacion.php">SUBIR OC.</li>
                    <li rel="res_cotiz.php">RESUMEN COTIZACIÓN</li>
                </ul>
            </li> 
            <li>SGC
                <ul class="submenu">
                    <li rel="administracion/calidad.php">CALIDAD CHILEVALORA</li>
                    <li rel="inn/inn.php">CALIDAD INN</li>
                </ul>
                </li>
            <?php } ?>
        </ul>
        <div class="welcome-message">
            Bienvenido <?php echo $usuario;?> 
            <a href="#" title="Cerrar sesión" id="logout-link">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </a>
        </div>
    </div>
        <div class="pantalla">
            <!-- Contenido de la pantalla aquí -->
            <iframe id="iframe"
                width="100%"
                height="100%"
                frameborder="0"
                src="home.php">
            </iframe>
        </div>
</div>
<script>
document.getElementById('logout-link').addEventListener('click', function(event) {
    event.preventDefault(); 

    swal({
        title: "Estas seguro?",
        text: "Si cierras tu sesión perderas datos sin guardar!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            window.location.href = "../api/logout.php";
        } else {
            swal("Tu sesión esta a salvo!");
        }
    });
});
</script>
</body>
</html>
