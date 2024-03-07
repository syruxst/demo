<?php require('template/header.php');  require('admin/conex.php');?>

<body>

<div class="politica">

    <div class="conter-politica">

        <iframe width="100%" height="800pxd" src="politicaPrivacidad.php" frameborder="0"></iframe>

        <input type="checkbox" id="politicaPrivacidad" style="width: 20px; height: 20px; transform: scale(1.2); border: 2px solid #ff0000;">

        <label for="politicaPrivacidad"><b>Acepto los términos de la política de privacidad</b></label>

        <button type="button" class="btn btn-primary" onclick="cerrarPolitica()">ENVIAR</button>

    </div>

</div>

<section class="tres">

<div data-aos="fade-up" data-aos-anchor-placement="center-center" class="operador">

  <div class="oper"><h1>OPERADORES</h1></div>

  <p>Se parte de nuestro Equipo de trabajo.</p>

  <img src="img/icon/undraw_join_re_w1lh.svg" alt="" width="100">

  <button type="button" class="btn btn-primary" id="mostrar-formulario">INSCRIBETE</button>

  <hr>

  <p>Si eres un operador de maquinaria y buscas trabajo. No te preocupes, nosotros buscamos trabajo por ti, solo debes inscribirte y pronto recibirás ofertas de empleo.</p>

</div>

<div class="wave" style="height: 200px; overflow: hidden;" >

    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;"><path d="M-3.16,91.03 C197.74,213.08 349.20,-49.85 507.00,90.05 L500.00,149.60 L-0.00,149.60 Z" style="stroke: none; fill: #fff;"></path></svg>

</div>

<div id="formulario-container">

  <form class="formulario" id="formulario" enctype="multipart/form-data" autocomplete="new-password" >

      <input type="text" placeholder="Nombre" style="text-transform:capitalize;" id="nombre" name="nombre" autofocus class="formulario__input" autocomplete="off" required>

      <input type="text" placeholder="Apellido" style="text-transform:capitalize;" id="apellido" name="apellido" autocomplete="off" required>

      <input type="text" placeholder="R.U.N sin puntos ni guión" id="rut" name="rut" maxlength="12" autocomplete="off" onkeypress="validarRut(rut)" required>

      <input type="text" placeholder="Dirección" name="direccion" id="direccion" autocomplete="off" required>

      <select id="selectRegiones" name="regiones" required></select>

      <select id="selectComunas" name="comunas" required></select>

      <div class="icono-input"> 

      <i class="fa fa-envelope" aria-hidden="true"></i>

      <input type="mail" placeholder="Correo" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(cl|com|edu|org|net)$" style="text-transform:lowercase;" onkeyup="javascript:this.value=this.value.toLowerCase();" autocomplete="off" id="correo" name="correo" required>

      </div>

      <div class="icono-input">

      <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>

      <input type="tel" placeholder="Celular" id="telefono" name="telefono" onkeypress="soloNumeros(event)" onfocus="agregarCodigoArea(this)" onblur="if(this.value==='+569')this.value='';" oninput="validarTelefono(this)" maxlength="12" required>

      </div>

      <select id="selectMaquinaria1" name="maq_1" required>

            <option value="0">Seleccione Equipo 1</option>

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

      </select>

      <select id="selectMaquinaria2" name="maq_2">

            <option value="0">Seleccione Equipo 2</option>

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

      <select id="sueldo" name="sueldo" required></select>

      <select id="experiencia" name="experiencia" required></select>

      

<div class="icono-input">

  <input type="password" class="pass" id="password1" name="password1" placeholder="Ingrese su contraseña" autocomplete="off" data-cke-suggestions="off" required>

  <i class="fa fa-eye" aria-hidden="true" id="togglePassword1"></i>

</div>



<div class="icono-input">

  <input type="password" class="pass" id="password2" name="password2" placeholder="Confirme su contraseña" autocomplete="off" data-cke-suggestions="off" required>

  <i class="fa fa-eye" aria-hidden="true" id="togglePassword2"></i>

</div>



<select id="estado" name="estado" class="form-control" required>

    <option value="0">Disponible</option>

    <option value="1">Trabajando</option>

</select>

<input type="text" id="licencia" name="licencia" placeholder="Licencia Formato A2-A5-B-D" class="form-control" maxlength="20" required oninput="this.value = this.value.toUpperCase()">

<input type="file" id="cv" name="cv" placeholder="Subir CV" class="form-control" accept="application/pdf" title="SOLO SUBIR EN FORMATO PDF" required>

<textarea placeholder="Comentarios" rows="2" cols="100" class="form-control" id="message" name="message" maxlength="999" style="resize:none"></textarea>

<hr>

    <table width="100%">

        <tr>

            <td>

                <button type="submit" class="btn btn-primary" name="btEnviar" onclick="validarPolitica(event)">ENVIAR</button>

            </td>

        </tr>

    </table>

</form>

</div>

</section>

<section class="button-icons">

    <a href="https://www.facebook.com/OperamaqEmpresa" target="_blank" class="fa fa-facebook-official" id="facebook"></a>

    <a href="https://api.whatsapp.com/send?phone=+56927527140&amp;text=Hola+Operamaq,+soluciones+operacionales" target="_blank" class="fa fa-whatsapp" id="whatsapp"></a>

    <a href="https://www.instagram.com/operamaq2023/" target="_blank" class="fa fa-instagram" id="instagram"></a>

</section>

<div class="tarjeta"></div>

<div id="mensaje"></div>

<script type="text/javascript" src="js/form-container.js"></script>



<script>

$(document).ready(function() {

  $("#formulario").submit(function(event) {

    // detener la acción por defecto del formulario  Seleccione una región

    event.preventDefault();



    if ($('#selectMaquinaria1').val() == 0) {

        swal({

            title: "Advertencia!",

            text: "Debes seleccionar un equipo!",

            icon: "info",

            button: "Aceptar!",

            timer: 4000

          }).then((value) => {

            $("#selectMaquinaria1").focus();

          });

          return false;

    }



    // Validar el formato del archivo

    var archivo = $("#cv")[0].files[0];

    if (archivo && archivo.type !== "application/pdf") {

      swal({

        title: "Advertencia!",

        text: "El archivo seleccionado debe ser en formato PDF.",

        icon: "info",

        button: "Aceptar!",

        timer: 4000

      });

      return false;

    }



    // obtener los datos del formulario

    var formData = new FormData(this);



    // realizar la llamada AJAX al archivo api.php

    $.ajax({

      url: "api/api.php",

      type: "POST",

      data: formData,

      dataType: "json",

      contentType: false, // Establecer a false para permitir que jQuery configure automáticamente el tipo de contenido

      processData: false, // Establecer a false para evitar el procesamiento automático de datos

      success: function(response) {

        // procesar la respuesta del servidor

        if (response.success) {

          // mostrar un mensaje de éxito con las variables recibidas

          swal({

            title: "Gracias " + response.nombre + " " + response.apellido + "!",

            text: "Tus datos han sido guardado con exito!",

            icon: "success",

            button: "Aceptar!",

            timer: 4000

          }).then((value) => {

            // limpiar el formulario

            $("#formulario")[0].reset();

          });

        } else {

          // mostrar un mensaje de error

          swal({

            title: "Estimado " + response.nombre + " " + response.apellido + "!",

            text: " " + response.mensaje + "!",

            icon: "info",

            button: "Aceptar!",

            timer: 4000

          });

        }

      },

      error: function() {

        // mostrar un mensaje de error en caso de un error de red o del servidor 

        swal({

            title: "Error en Servidor!",

            text: "Ha ocurrido un error al enviar los datos al servidor!",

            icon: "error",

            button: "Aceptar",

            timer: 4000

          });

        }

    });

  });

});

</script>

<script type="text/javascript">



const licencia = document.getElementById('licencia');



licencia.addEventListener('input', (event) =>{

    const valorLicencia = licencia.value;

    const nuevaLicencia = valorLicencia.replace(/[^A-D1-5-]/g, '');

    if (valorLicencia !== nuevaLicencia) {

        licencia.value = nuevaLicencia;

    }

});

function validarPolitica(event) {

    var checkbox = document.getElementById("politicaPrivacidad");

    if (!checkbox.checked) {

      event.preventDefault(); // Evita que el formulario se envíe

        swal({

            title: "Política de Privacidad",

            text: "Acepta la Política de Privacidad de Operamaq Empresa Spa para continuar.",

            icon: "info",

            button: "Aceptar",

            customClass: {

                content: 'text-center text-justify'

        }

        }).then(function() {

            abrirPolitica();

        });

    }

  }

function abrirPolitica() {

    var politicaDiv = document.querySelector('.politica');

    politicaDiv.classList.add('mostrar');

}

function cerrarPolitica() {

  var politicaDiv = document.querySelector('.politica');

  politicaDiv.classList.remove('mostrar');

}

</script>

<?php  require('template/foot.php'); ?>

