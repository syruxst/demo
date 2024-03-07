<?php require('admin/conex.php');?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Empresa dedicada a suministrar operadores de gran experiencia  en la operación de equipos de pesados, consiguiendo maximizar su producción">
    <meta name="keywords" content="operadores, mineria, soluciones operacionales, maquinarias">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <!--icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title id="page-title">::: Plataforma Cliente :::</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        window.addEventListener("load", function() {

            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){

                console.log("Estás usando un dispositivo móvil");

            } else {

                console.log("Estás usando una computadora");

            }

        });

    </script>

    <script>

		let titles = ['::: Operamaq :::', '::: Soluciones Operacionales :::', '::: Operadores Expertos :::', '::: Operadores Certificados :::', '::: Operadores Calificados ::'];

		let i = 0;



        function animateTitle() {

                    document.getElementById('page-title').innerHTML = titles[i];

                    i++;

                    if (i >= titles.length) {

                        i = 0;

                    }

                }

                setInterval(animateTitle, 3000);

            

            window.addEventListener("scroll", function(){

            var header = document.querySelector("header");

            header.classList.toggle("abajo",window.scrollY>0);

        });

	</script>
<style>
body {
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: cover;
  align-items: center;
  height: 100vh;
  background: url('./img/fondos/1.jpg') no-repeat center center;
  background-size: cover;
  background-size: cover;
}

        :root {
        --main-color: #F80D0D;
        --verde: #024959;
        }
        .containe-div {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 650px;
        transform: translate(-50%, -50%);
        height: 350px;
        background-color: var(--main-color);
        display: flex;
        border-radius: 20px 20px 20px 20px;
        box-shadow: 0 0 30px #919191ae;
        margin: 0;
        }
        .left {
        width: 250px;
        height: 100%;
        background-color: var(--main-color);
        border-radius: 20px 0px 0px 20px;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: rgb(255, 255, 255) !important;
        padding: 20px;
        font-weight: 600;
        }
        .left p {
        position: absolute;
        bottom: 0;
        font-size: .6em;
        }
        .right {
        width: 400px;
        height: 100%;
        background-color: #fff;
        border-radius: 20px 20px 20px 20px;
        animation: slideRight 1s forwards;
        padding: 20px;
        }
        .inputbox {
        position: relative;
        margin: 30px 0;
        width: 100%;
        border-bottom: 1px solid var(--main-color);
        }
        .inputbox label {
        position: absolute;
        top: 50%;
        left: 5px;
        transform: translateY(-50%);
        color: var(--main-color);
        font-size: 1em;
        pointer-events: none;
        transition: 0.5s;
        }
        .inputbox input:focus ~ label,
        input:valid ~ label {
        top: -5px;
        font-size: .8em;
        }
        .inputbox input {
        width: 100%;
        height: 50px;
        background-color: transparent;
        border: none;
        outline: none;
        font-size: 1em;
        padding: 0 35px 0 5px;
        color: var(--main-color);
        }
        .inputbox i {
        position: absolute;
        right: 8px;
        font-size: 1.2em;
        top: 20px;
        color: var(--main-color)
        }
        .right input[type="submit"] {
        width: 100%;
        height: 50px;
        border: none;
        outline: none;
        background-color: var(--main-color);
        color: #fff;
        font-size: 1em;
        border-radius: 25px;
        cursor: pointer;
        transition: 0.5s;
        }
        .right input[type="submit"]:hover {
        background-color: #fff;
        color: var(--main-color);
        border: 1px solid var(--main-color);
        }
        @keyframes slideRight {
        from {
            left: -100%; /* El div comienza fuera de la pantalla a la izquierda */
        }
        to {
            left: 0; /* Al final de la animaciÃ³n, el div estarÃ¡ en su posiciÃ³n final */
        }
        }
        .globo {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 1px solid #fff;
        left: 50%;
        transform: translate(-50%);
        }
        .globo img {
            width: 100%; /* La imagen ocupará todo el espacio dentro del círculo */
            height: auto;
            border-radius: 50%; /* Hacer la imagen redonda */
        }
        @media (max-width: 700px) {
            body{
                padding: 10px;
            }
            .containe-div {
                flex-direction: column; 
                align-items: center;
                width: 90%;
                height: 600px;
            }

            .left,
            .right {
                max-width: 100%; /* Utiliza todo el ancho disponible */
            }
        }
    </style>
<body>
<section class="tres">
    <div class="containe-div">
        <div class="left">
            <div class="globo">
                <img src="https://acreditasys.tech/img/logoInicioRedondo.png" alt="Logo" width="100%" height="100%">
            </div>    
            <br> 
            <h2 class="animate__animated animate__backInLeft">OPERAMAQ</h2>  
            <label for="">Soluciones Operacionales</label> 
            <label for="">C L I E N T E</label>
            <p>©2023. Todos los derechos reservados.</p>
        </div>
        <div class="animate__animated animate__backInLeft right">
            <form action="./cliente/cliente.php" method="post">
                <div class="inputbox">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input type="text" name="usuario" id="usuario" class="rut-input" autocomplete="off" required maxlength="12">
                    <label for="">Usuario</label>
                </div>
                <div class="inputbox">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" name="pass" id="pass" autocomplete="off" required maxlength="12">
                    <label for="">Contraseña</label>
                </div>
                <input type="submit" value="Ingresar" id="btn-login">
            </form>
        </div>
    </div>
</section>
</body>
</html>