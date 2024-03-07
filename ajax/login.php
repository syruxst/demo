<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>:: Ingreso Operadores ::</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: cover;
            align-items: center;
            height: 100vh;
            background: url('../img/fondos/4.jpg') no-repeat center center;
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
            font-size: .7em;
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
        /* Estilo para el modal */
        .modal {
        display: none; /* Oculta el modal por defecto */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Fondo semi-transparente */
        z-index: 1;
        }

        /* Estilo para el contenido del modal */
        .modal-content {
        background-color: #fff;
        padding: 20px;
        width: 400px;
        margin: 0 auto;
        margin-top: 100px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        position: relative;
        }

        /* Estilo para el botón de cerrar el modal (la "x") */
        .close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
        }
        .submit-button {
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

        .submit-button:hover {
        background-color: #fff;
        color: var(--main-color);
        border: 1px solid var(--main-color);
        }        
        /* Agrega más estilos según tus preferencias */
        #recuperar-contrasena {
        color: var(--main-color); /* Cambia el color del texto a tu color principal */
        text-decoration: none; /* Elimina el subrayado predeterminado */
        font-size:0.8em; /* Cambia el tamaño de fuente */
        transition: color 0.3s; /* Agrega una transición de color suave */
        }

        #recuperar-contrasena:hover {
        color: #024959; /* Cambia el color al pasar el cursor sobre el enlace */
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
</head>
<body style="background-color: #ebfafce4;">
    <div class="containe-div">
        <div class="left">
            <div class="globo">
            <img src="https://acreditasys.tech/img/logoInicioRedondo.png" alt="Logo" width="100%" height="100%">
            </div>    
            <br> 
            <h2 class="animate__animated animate__backInLeft">OPERAMAQ</h2>  
            <label for="">Soluciones Operacionales</label> 
            <br>
            <h4>OPERADORES</h4>
            <p>©2023. Todos los derechos reservados.</p>
        </div>
        <div class="animate__animated animate__backInLeft right">
            <form method="post" action="buscarUser.php">
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
            <br>
            <a href="" id="recuperar-contrasena">Restablecer Contraseña <i class="fa fa-unlock" aria-hidden="true"></i></a>
        </div>
    </div>
<div id="modal-recuperar-contrasena" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Recuperar Contraseña</h2>
        <form id="recuperar-contrasena-form">
            <div class="inputbox">
                <input type="text" id="correo-recuperacion" class="rut-input" placeholder="Ingrese su R.U.N" autocomplete="off" required maxlength="12" required>
            </div>
            <input type="submit" value="Enviar" class="submit-button">
        </form>
    </div>
</div>
<div class="loading-overlay" id="loading-overlay">
  <div class="loader"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function formatRut(rut) {
        const rutInputs = document.querySelectorAll(".rut-input");

        rutInputs.forEach(input => {
            input.addEventListener("input", function() {
                const inputValue = this.value.trim();
                const cleanedValue = inputValue.replace(/[^\dKk.-]/g, "");
                const formattedValue = formatRut(cleanedValue);
                this.value = formattedValue;
            });
        });

        function formatRut(rut) {
        rut = rut.toUpperCase();
        if (rut.length > 1) {
            const lastChar = rut[rut.length - 1];
            rut = rut.substring(0, rut.length - 1).replace(/\./g, "").replace(/\-/g, "");
            rut = rut.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            rut = rut + "-" + lastChar;
        }
        return rut;
        }
    }
    formatRut();
});
const btnRecuperarContrasena = document.getElementById("recuperar-contrasena");
const modalRecuperarContrasena = document.getElementById("modal-recuperar-contrasena");
const closeModalButton = document.querySelector(".close");

// Abre el modal cuando se hace clic en el botón
btnRecuperarContrasena.addEventListener("click", function(event) {
  event.preventDefault(); // Evita la acción predeterminada del enlace
  modalRecuperarContrasena.style.display = "block";
});

// Cierra el modal si se hace clic en el botón de cerrar ("x")
closeModalButton.addEventListener("click", function() {
  modalRecuperarContrasena.style.display = "none";
});

// Evita que el modal se cierre cuando se hace clic dentro del formulario
const recuperarContrasenaForm = document.getElementById("recuperar-contrasena-form");

recuperarContrasenaForm.addEventListener("click", function(event) {
  event.stopPropagation(); // Evita que el evento de clic se propague al modal
});

// Cierra el modal si se hace clic fuera del contenido del modal
window.addEventListener("click", function(event) {
  if (event.target === modalRecuperarContrasena) {
    modalRecuperarContrasena.style.display = "none";
  }
});

// Agrega aquí la lógica para enviar el formulario de recuperación de contraseña
recuperarContrasenaForm.addEventListener("submit", function(event) {
  event.preventDefault(); // Evita que se envíe el formulario de manera predeterminada

  // Mostrar el indicador de carga
  document.getElementById("loading-overlay").style.display = "block";

  // Aquí puedes agregar código para enviar la solicitud de recuperación de contraseña al servidor
  const correoRecuperacion = document.getElementById("correo-recuperacion").value;

  // Realiza una solicitud AJAX al servidor para solicitar la recuperación de contraseña
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "guardarContrasenaTemporal.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      // Ocultar el indicador de carga
      document.getElementById("loading-overlay").style.display = "none";

      if (xhr.status === 200) {
        // La solicitud se completó con éxito
        const response = xhr.responseText;
        if (response === "exito") {
          // Puedes mostrar un mensaje de éxito al usuario
          swal("¡Bien hecho!", "Se ha enviado un correo con tu nueva contraseña!", "success");
          modalRecuperarContrasena.style.display = "none"; // Cierra el modal
        } else {
          // Mostrar un mensaje de error en caso de problemas en el servidor
          swal("¡Algo salió mal!", "NO se ha podido restablecer tu contraseña!", "error");
        }
      } else {
        // Mostrar un mensaje de error en caso de problemas de conexión
        swal("¡Ups!", "Error de conexión!", "info");
      }
    }
  };

  // Envía los datos al servidor
  const params = `correo=${correoRecuperacion}`;
  xhr.send(params);
});

</script>
</body>
</html>