<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Empresa dedicada a suministrar operadores de gran experiencia  en la operación de equipos de pesados, consiguiendo maximizar su producción">

    <meta name="keywords" content="operadores, mineria, soluciones operacionales, maquinarias">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/swiper-bundle.min.css">

    <link rel="stylesheet" href="css/bootsnav.js">

    <link rel="stylesheet" href="node_modules/aos/dist/aos.css">

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

    <title id="page-title">OPERAMAQ</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="./node_modules/sweetalert/dist/sweetalert.min.js"></script>

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

</head>

<body>

<!--Menu principal web-->

<header>

  <img src="https://acreditasys.tech/img/LogoPrincipal.png" alt="" width="230" height="80" title="OPERAMAQ" class="logo">

  <nav>

    <ul>

      <li><a href="../index.php" class="animate__animated animate__backInLeft">Inicio</a></li>

      <li><a href="#somos" class="animate__animated animate__backInLeft">Quiens Somos</a></li>

      <li><a href="#" class="animate__animated animate__backInLeft">Servicios</a>

          <ul>

            <li><a href="#">Empresas</a></li>

            <li><a href="operadores.php">Operadores</a></li>

            <li><a href="ajax/index.php">Admin</a></li>

          </ul>      

        </li>

      <li><a href="contac.php" class="animate__animated animate__backInLeft">Contacto</a></li>


  </ul>

  </nav>

</header>

<nav class="navbar bg-body-tertiary fixed-top" id="menu">

  <div class="input-group">

    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvasLg" aria-controls="navbarOffcanvasLg">

      <span class="navbar-toggler-icon"></span>

    </button>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvasLg" aria-labelledby="navbarOffcanvasLgLabel">

    <div class="offcanvas-body">

      <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="index.php"><i class="fa fa-times-circle" aria-hidden="true"></i> CERRAR MENU</a>

        </li>

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>

        </li>

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

          Productos

        </a>

          <ul class="dropdown-menu">

            <li><a class="dropdown-item" href="#">Accesorios</a></li>

            <li><a class="dropdown-item" href="#">Blusas</a></li>

            <li><a class="dropdown-item" href="#">Cardigan</a></li>

            <li><a class="dropdown-item" href="#">Chaquetas</a></li>

            <li><a class="dropdown-item" href="#">Pantalones</a></li>

            <li><a class="dropdown-item" href="#">Polera</a></li>

            <li><a class="dropdown-item" href="#">Sweaters</a></li>

            <li><a class="dropdown-item" href="#">Tejidos</a></li>

          </ul>

        </li>

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="#">Servicios</a>

        </li>

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="#">Portafolio</a>

        </li>

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="contac.php">Contacto</a>

        </li>

        <li class="nav-item">

          <a class="nav-link active" aria-current="page" href="contac.php">Contacto</a>

        </li>

        <li class="nav-item">

          <a href="mostrarCarrito.php" title="VER CARRITO" class="nav-link active" aria-current="page"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><?php echo (empty($_SESSION['CARRITO']))?0:count($_SESSION['CARRITO'])?></a>

        </li>

      </ul>

    </div>

  </div>

  <a href="#log" title="INGRESAR A CUENTA" class="navbar-brand"><i class="fa fa-user-circle-o fa-2x" aria-hidden="true"></i></a>

  <a href="mostrarCarrito.php" title="VER CARRITO" class="navbar-brand"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><?php echo (empty($_SESSION['CARRITO']))?0:count($_SESSION['CARRITO'])?></a>

</div>

</nav>

