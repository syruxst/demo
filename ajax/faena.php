<?php require_once('../admin/conex.php'); ?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="">

    <link rel="stylesheet" href="../css/style_other.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>

    <title>Document</title>

</head>

<style>

    * {

        margin: 0;

        padding: 0;

        box-sizing: border-box;

    }

    body {

        padding: 0;

    }

  a {

    text-decoration: none;

    color: black;

  }

  a:hover{

    color: blueviolet;

  }

  .edit_oper {

    display: none;

    background-color: rgba(0, 0, 0, 0.63);

    position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; 

    width: 100%; height: auto; justify-content: center;

    align-items: center;

    backdrop-filter: blur(2px);

    padding: 50px;

    overflow-y: auto;

  }

  .form_edit {

    position: fixed;

    top: 50%;

    left: 50%;

    transform: translate(-50%, -50%);

    width: 60%;

    min-width: 300px;

    height: auto;

    background-color: white;

    border-radius: 20px;

    backdrop-filter: blur(10px);

    padding: 20px;

  }

  @media screen and (max-width: 1280px){

    .edit_oper{

        padding: auto;

    }

    .form_edit{

        width: 60%;

        height: auto;

        padding: 20px;

    }

  }

  @media screen and (max-width: 1200px){

    .edit_oper{

        padding: auto;

    }

    .form_edit{

        width: 100%;

        height: auto;

        padding: 20px;

    }

  }

  @media screen and (max-width: 1100px){

    .edit_oper{

        padding: 10px;

    }

    .form_edit{

        width: 100%;

        height: auto;

        padding: 10px;

    }

  }

  @media screen and (max-width: 913px) {

       table {

           width:100%;

       }

       thead {

           display: none;

       }

       tr:nth-of-type(2n) {

           background-color: inherit;

       }

       tr td:first-child {

           background: #f0f0f0;

           font-weight:bold;

           font-size:1em;

       }

       tbody td {

           display: block;

           text-align:center;

       }

       tbody td:before {

           content: attr(data-th);

           display: block;

           text-align:center;

       }

    }

    .contenido{

        width: 100%;

        height: auto;

        padding: 15px;

    }

    .titulo{

        font-size: 14px;

        font-weight: bold;

        color:#C2DBFE;

    }

    hr {

        border: 1px solid #85C1E9;

    }

    .cabecera {

        font-size: 20px;

        font-weight: bold;

    }

    .tboper tr td {

        padding: 5px;

    }

    .logo{

        position: absolute;

        top: 15px;

        right: 150px;

        z-index: 10000;

        width: 60px;

        height: 60px;

    }

    table tr.hover-row:hover {

        background-color: #C2DBFE; 

        cursor: pointer;

    }

</style>

<body>

<div class="logo">

    <img src="../img/logo3.png" width="180"></ing>

</div>

    <center><span class="cabecera">OPERADORES EN FAENA</span></center>

<div class="contenido">

<div class="fijo">

  <table border="0" width="100%" style="font-size: 12px; border-collapse: collapse;" cellspacing="8" cellpadding="8" class="tboper">

    <tr>

        <td align="center">

            <span class="titulo">STATUS</span>

        </td>

        <td align="center">

              <span class="titulo">EMPRESA</span>

        </td>

        <td align="center">

            <span class="titulo">FAENA</span>

        </td>

        <td align="center">

            <span class="titulo">OBRA</span>

        </td>

        <td>

            <span class="titulo">EQUIPO</span>

        </td>

        <td>

            <!--<span class="titulo">UBICACIÓN</span>-->

        </td>

    </tr>

    <tr>

      <td>

        <select class="form-control" id="estado" name="estado">

          <option value="">Todos</option>

          <option value="2">Faena</option>

        </select>

      </td>

      <td>

        <select class="form-control" id="criterio" name="criterio">

          <option value="0">Todos</option>

          <option value="1">Cumple</option>

          <option value="2">NO Cumple</option>

        </select>

      </td>

      <td>

      <select class="form-control" id="experiencia" name="experiencia">

          <option value="EXPERIENCIA">Todos</option>

          <option value="SIN EXPERIENCIA">Sin Experiencia</option>

          <option value="1 año">1 año</option>

          <option value="2 años">2 años</option>

          <option value="3 años">3 años</option>

          <option value="4 años">4 años</option>

          <option value="5 años">5 años</option>

          <option value="6 años">6 años</option>

          <option value="7 años">7 años</option>

          <option value="8 años">8 años</option>

          <option value="9 años">9 años</option>

          <option value="10 años">10 años</option>

          <option value="+ 10 años">+ 10 años</option>

        </select>

      </td>

      <td>

      <select class="form-control" id="tipo" name="tipo">

          <option value="0">Todos</option>

          <option value="MOP">M.O.P.</option>

          <option value="Mineria">MINERO</option>

        </select>

      </td>

      <td>

      <select id="selectMaquinaria1" name="maq_1" class="form-control">

            <option value="">Todos</option>

		    <option value="13">Bulldozer D6</option>

            <option value="1">Bulldozer D8</option>

            <option value="14">Bulldozer D9</option>

            <option value="15">Bulldozer D10</option>

            <option value="2">Camión Aljibe 15 m3</option>

            <option value="3">Camión Aljibe 30 m3</option>

            <option value="19">Camión Dumper</option>

            <option value="24">Camión Lubricador</option>

            <option value="23">Camión Petroleador</option>

            <option value="4">Camión Pluma 5 ton</option>

            <option value="16">Camión Pluma 8 ton</option>

            <option value="17">Camión Pluma 10 ton</option>

            <option value="18">Camión Pluma 15 ton</option>

            <option value="5">Camión Tolva 20 m3</option>

            <option value="22">Cargador Frontal</option>

            <option value="6">Excavadora 20-22 Ton.</option>

            <option value="7">Excavadora 35 Ton.</option>

            <option value="8">Excavadora 50 Ton.</option>

            <option value="20">Excavadora 70 Ton.</option>

            <option value="21">Excavadora 80 Ton.</option>

            <option value="9">Minicargador</option>

            <option value="10">Motoniveladora</option>

            <option value="11">Retroexcavadora</option>

            <option value="25">Rigger</option>

		    <option value="12">Rodillo Compactador</option>    

      </select>

      </td>

      <td>

      <select name="region" id="region" class="form-control" required="">

		    <option value="">Seleccione Región</option>

            <option value="Arica y Parinacota">Arica y Parinacota</option>

            <option value="Tarapacá">Tarapacá</option>

            <option value="Antofagasta">Antofagasta</option>

            <option value="Atacama">Atacama</option>

            <option value="Coquimbo">Coquimbo</option>

            <option value="Valparaíso">Valparaíso</option>

            <option value="Metropolitana de Santiago">Metropolitana de Santiago</option>

            <option value="Libertador General Bernardo O'Higgins">Libertador General Bernardo O'Higgins</option>

            <option value="Maule">Maule</option>

            <option value="Ñuble">Ñuble</option>

            <option value="Biobío">Biobío</option>

            <option value="Araucanía">Araucanía</option>

            <option value="Los Ríos">Los Ríos</option>

            <option value="Los Lagos">Los Lagos</option>

            <option value="Aysén del General Carlos Ibáñez del Campo">Aysén del General Carlos Ibáñez del Campo</option>

            <option value="Magallanes y la Antártica">Magallanes y la Antártica</option>

        </select>

      </td>

      <td>

<!--<select class="form-control" id="region" name="region"></select>-->

      </td>

    </tr>

  </table>

  <hr>

  <div class="input-group">

  <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Buscar Operador">

  <input type="hidden" class="form-control" id="familia" name="familia" placeholder="Ingresar Mensajes">

  </div>

  <hr>

  </div>

  <form action="cotizacion.php" method="post">

    <div style="overflow-y: scroll; max-height: 400px;">

        <table width="100%" border="1" id="tabla-resultados" class="tabla table table-striped " style="font-size: 14px;">

        

        </table>

    </div>

  </form>

</div>

<div class="edit_oper" style="display: none;">

    <div class="form_edit">

    </div>

    <button onclick="closeEditForm()" class='btn btn-primary' id="afuera">Cerrar y Actualizar</button>

</div>

</body>

<script type="text/javascript">

  window.addEventListener('load', function() {

    //Cargar regiones

    const regiones = [

            "Buscar región",

            "Arica y Parinacota",

            "Tarapacá",

            "Antofagasta",

            "Atacama",

            "Coquimbo",

            "Valparaíso",

            "Metropolitana de Santiago",

            "Libertador General Bernardo O'Higgins",

            "Maule",

            "Ñuble",

            "Biobío",

            "Araucanía",

            "Los Ríos",

            "Los Lagos",

            "Aysén del General Carlos Ibáñez del Campo",

            "Magallanes y la Antártica"

        ];

        

        const selectRegiones = document.getElementById("region");

        

        regiones.forEach((region) => {

            const option = document.createElement("option");

            option.text = region;

            option.value = region;

            //selectRegiones.add(option);

        });  

  });

// Agregar el evento change a cada campo de selección

document.addEventListener("DOMContentLoaded", buscar);

document.getElementById("estado").addEventListener("change", buscar);

document.getElementById("experiencia").addEventListener("change", buscar);

document.getElementById("tipo").addEventListener("change", buscar);

document.getElementById("selectMaquinaria1").addEventListener("change", buscar);

document.getElementById("region").addEventListener("change", buscar);

document.getElementById("criterio").addEventListener("change", buscar);

document.getElementById("nombre").addEventListener("keyup", buscar);



function cargarPagina() {

    // Lógica de inicialización de la página



    buscar();

}



document.addEventListener("DOMContentLoaded", cargarPagina);



var estado = "";

var experiencia = "";

var tipo = "";

var familia = "";

var selectMaquinaria1 = "";

var region = "";

var criterio = "";

var nombre = "";



function buscar() {

    estado = document.getElementById("estado").value;

    experiencia = document.getElementById("experiencia").value;

    tipo = document.getElementById("tipo").value;

    familia = document.getElementById("familia").value;

    selectMaquinaria1 = document.getElementById("selectMaquinaria1").value;

    region = document.getElementById("region").value;

    criterio = document.getElementById("criterio").value;

    nombre = document.getElementById("nombre").value;



    var xhttp = new XMLHttpRequest();

    var respuestaServidor = ""; // Variable para almacenar la respuesta del servidor



    // Realizar una solicitud AJAX al servidor para obtener los resultados filtrados

    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            // Almacenar la respuesta del servidor en la variable global

            respuestaServidor = this.responseText;

            // Actualizar la tabla con los resultados recibidos del servidor

            document.getElementById("tabla-resultados").innerHTML = respuestaServidor;

        }

    };



    // Construir la URL de solicitud con los parámetros seleccionados

    var url = "buscar_faena.php?familia=" + familia +

              "&estado=" + estado +

              "&experiencia=" + experiencia +

              "&tipo=" + tipo +

              "&selectMaquinaria1=" + selectMaquinaria1 +

              "&region=" + region +

              "&criterio=" + criterio +

              "&nombre=" + nombre;



    xhttp.open("GET", url, true);

    xhttp.send();

}



function loadEditForm(id) {

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            // Actualizar el contenido del div de edición

            document.querySelector('.edit_oper .form_edit').innerHTML = this.responseText;

            

            // Mostrar el div de edición

            document.querySelector('.edit_oper').style.display = 'block';



            // Capturar el evento de envío del formulario dentro de loadEditForm

            document.getElementById('form_actualizar').addEventListener('submit', function(e) {

                e.preventDefault(); // Prevenir el envío del formulario por defecto



                // Obtener los datos del formulario

                var formData = new FormData(this);



                // Crear una nueva instancia de XMLHttpRequest

                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {

                    if (this.readyState == 4) {

                        if (this.status == 200) {

                            // Los datos se enviaron correctamente y la respuesta del servidor está disponible en this.responseText

                            console.log("Los datos se enviaron correctamente.");

                            console.log("Respuesta del servidor:", this.responseText);

                            swal({

                                title: "Felicitades!",

                                text: "Los datos se han guardado correctamente!",

                                icon: "success",

                                button: "Aceptar",

                                timer: 4000

                            });

                        } else {

                            // Ha ocurrido un error al enviar los datos

                            console.log("Error al enviar los datos. Código de estado:", this.status);

                            swal({

                                title: "Error en Servidor!",

                                text: "Ha ocurrido un error al enviar los datos al servidor!",

                                icon: "error",

                                button: "Aceptar",

                                timer: 4000

                            });                        

                        }

                    }

                };

                

                // Enviar los datos al archivo actualizar_oper.php

                xhttp.open("POST", "actualizar_oper.php", true);

                xhttp.send(formData);

            });

        }

    };

    xhttp.open("GET", "edit_oper.php?id=" + id, true);

    xhttp.send();

}



function closeEditForm() {

    // Actualizar la tabla con los resultados recibidos del servidor después de un breve retraso

    setTimeout(function() {

        // Utilizar los criterios almacenados en las variables globales

        var url = "buscar.php?familia=" + familia +

                  "&estado=" + estado +

                  "&experiencia=" + experiencia +

                  "&tipo=" + tipo +

                  "&selectMaquinaria1=" + selectMaquinaria1 +

                  "&region=" + region +

                  "&criterio=" + criterio;



        // Realizar la solicitud AJAX nuevamente

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                // Almacenar la respuesta del servidor en la variable global

                respuestaServidor = this.responseText;

                // Actualizar la tabla con los resultados recibidos del servidor

                document.getElementById("tabla-resultados").innerHTML = respuestaServidor;

            }

        };



        xhttp.open("GET", url, true);

        xhttp.send();

    }, 1000);



    // Ocultar el div de edición

    document.querySelector('.edit_oper').style.display = 'none';

    

    // Actualizar la página anterior

    window.history.back();

}



const licencia = document.getElementById('licencia');



licencia.addEventListener('input', (event) =>{

    const valorLicencia = licencia.value;

    const nuevaLicencia = valorLicencia.replace(/[^A-D1-5-]/g, '');

    if (valorLicencia !== nuevaLicencia) {

        licencia.value = nuevaLicencia;

    }

});

</script>

</html>