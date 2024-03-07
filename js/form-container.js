const boton = document.getElementById('mostrar-formulario'),
      formularioContainer = document.getElementById('formulario-container'),
      inputNombre = document.querySelector('#nombre'),
      inputApellido = document.querySelector('#apellido'),
      inputRut = document.querySelector('#rut'),
      correoInput = document.querySelector('#correo');

      boton.addEventListener('click', (event) => {
        formularioContainer.classList.toggle('mostrar');
        inputNombre.focus();
        
        // Evita que el clic en el botón también active el evento del documento
        event.stopPropagation();
      });
      
      document.addEventListener('click', (event) => {
        const formulario = document.getElementById('formulario');
        
        // Comprueba si el clic ocurrió dentro o fuera del formulario
        if (!formulario.contains(event.target) && formularioContainer.classList.contains('mostrar')) {
          formularioContainer.classList.remove('mostrar');
        }
      });

    // Validar que el nombre solo contenga letras y espacios
    inputNombre.addEventListener('input', (event) => {
        const valor = inputNombre.value;
        const nuevoValor = valor.replace(/[^a-zA-Z\s]/g, ''); // Eliminar caracteres no permitidos, excepto espacios
        
        if (valor !== nuevoValor) {
        inputNombre.value = nuevoValor;
        }
    });

    inputNombre.addEventListener('blur', (event) => {
        let valor = inputNombre.value;
        let nuevoValor = valor.replace(/[^a-zA-Z\s]/g, ''); // Eliminar caracteres no permitidos, excepto espacios
        
        // Eliminar el último espacio al final de la cadena
        nuevoValor = nuevoValor.trimRight();
        
        if (valor !== nuevoValor) {
        inputNombre.value = nuevoValor;
        }
        });

    // Validar que el apellido solo contenga letras y espacios
    inputApellido.addEventListener('input', (event) => {
        const valor = inputApellido.value;
        const nuevoValor = valor.replace(/[^a-zA-Z\s]/g, ''); // Eliminar caracteres no permitidos, excepto espacios
        
        if (valor !== nuevoValor) {
        inputApellido.value = nuevoValor;
        }
        });
    
    inputRut.addEventListener('input', (event) => {
        const validarRut = inputRut.value;
        const nuevoValorRut = validarRut.replace(/[^0-9kK]/g, ''); // Eliminar caracteres no permitidos, excepto espacios

        if (validarRut !== nuevoValorRut) {
            inputRut.value = nuevoValorRut;
        }
    });
    
// Definir función de validación de RUT
function validarRut(rut) {
  // Eliminar espacios en blanco
  rut = rut.replace(/\s/g, '');
  
  // Remover cualquier carácter que no sea un número o la letra "k"
  rut = rut.replace(/[^0-9kK]/g, '');
  
  // Si el último carácter es "k" o "K", convertirlo a mayúscula
  if (rut.slice(-1).toLowerCase() === 'k') {
    rut = rut.slice(0, -1) + 'K';
  }
  
  // Insertar puntos y guión según formato de puntuación 00.000.000-0
  rut = rut.replace(/^(\d{2})(\d{3})(\d{3})/, '$1.$2.$3-');
  
  // Retornar el RUT formateado y validado, o false si es inválido
  return (/^\d{2}\.\d{3}\.\d{3}\-[0-9kK]$/.test(rut)) ? rut : false;
}

// Agregar evento de input al input de RUT
inputRut.addEventListener('input', (event) => {
  const rut = inputRut.value;
  // Eliminar espacios en blanco del RUT
  const rutSinEspacios = rut.replace(/\s/g, '');
  const rutValidado = validarRut(rutSinEspacios);
  
  if (rutValidado) {
    // Si el RUT es válido, asignar el valor del input al RUT formateado y validado
    inputRut.value = rutValidado;
    // Resetear estilo del input a su valor por defecto
    inputRut.style.border = '';
    
    // Enviar solicitud AJAX para obtener datos adicionales
    // aquí iría el código AJAX para enviar la solicitud y procesar la respuesta
    console.log('RUT válido:', rutValidado);
  } else {
    // Si el RUT es inválido, cambiar estilo del input y mostrar mensaje de error al usuario
    inputRut.style.border = '2px solid red';
    console.log('RUT inválido');
  }
});
inputRut.addEventListener('blur', (event) => {
    if(rutValidado){

    }else{
        swal({
            title: "Error en  R.U.N!",
            text: "El formato de R.U.N es incorrecto!",
            icon: "info",
            button: "Aceptar",
            timer: 4000
          }).then((value) => {
            // limpiar el formulario
            inputRut.focus();
          });
    }
});

    // Agregar evento de input al input de correo
    function validarCorreo(correo) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(cl|com|edu|org|net)$/;
        return regex.test(correo);
        }
        
        correoInput.addEventListener('input', (event) => {
        const correo = correoInput.value;
        if (validarCorreo(correo)) {
        // Si el correo es válido, hacer algo
        console.log('Correo válido:', correo);
        correoInput.style.border = ''; // Cambiar borde a rojo
        } else {
        // Si el correo es inválido, hacer algo
        console.log('Correo inválido');
        correoInput.style.border = '2px solid red'; // Cambiar borde a rojo
        }
        });
    
    // Solo permitir ingresar números en el input de teléfono
    function soloNumeros(e) {
        var charCode = e.keyCode ? e.keyCode : e.which;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          e.preventDefault();
        }
      }
    
    // Agregar evento de input al input de teléfono
    function agregarCodigoArea(input) {
        if (!input.value.startsWith("+569")) {
          input.value = "+569" + input.value;
        }
      }
      
    function validarTelefono(input) {
        if (input.value.length !== 12) {
          input.style.border = "2px solid red";
        } else {
          input.style.border = "";
        }
     }

    //Cargar regiones
    const regiones = [
        "Seleccione una región",
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
    
    const selectRegiones = document.getElementById("selectRegiones");
    
    regiones.forEach((region) => {
        const option = document.createElement("option");
        option.text = region;
        option.value = region;
        selectRegiones.add(option);
    });

    //Cargar comunas
    const regionesYcomunas = {
        "Seleccione una región": [],
        "Arica y Parinacota": ["Arica", "Camarones", "Putre", "General Lagos"],
        Tarapacá: ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"],
        Antofagasta: ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"],
        Atacama: ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"],
        Coquimbo: ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paiguano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"],
        Valparaíso: ["Valparaíso", "Casablanca", "Concón", "Juan Fernández", "Puchuncaví", "Quintero", "Viña del Mar", "Isla de Pascua", "Los Andes", "Calle Larga", "Rinconada", "San Esteban", "La Ligua", "Cabildo", "Papudo", "Petorca", "Zapallar", "Quillota", "Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Algarrobo", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Catemu", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Quilpué", "Limache", "Olmué", "Villa Alemana"],
        "Metropolitana de Santiago": ["Santiago", "Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "Colina", "Lampa", "Tiltil", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor"],
        "Libertador General Bernardo O'Higgins": ["Rancagua", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "Las Cabras", "Machalí", "Malloa", "Mostazal", "Olivar", "Peumo", "Pichidegua", "Quinta de Tilcoco", "Rengo", "Requínoa", "San Vicente", "Pichilemu", "La Estrella", "Litueche", "Marchihue", "Navidad", "Paredones", "San Fernando", "Chépica", "Chimbarongo", "Lolol", "Nancagua", "Palmilla", "Peralillo", "Placilla", "Pumanque", "Santa Cruz"],
        Maule: ["Talca", "Constitución", "Curepto", "Empedrado", "Maule", "Pelarco", "Pencahue", "Río Claro", "San Clemente", "San Rafael", "Cauquenes", "Chanco", "Pelluhue", "Curicó", "Hualañé", "Licantén", "Molina", "Rauco", "Romeral", "Sagrada Familia", "Teno", "Vichuquén", "Linares", "Colbún", "Longaví", "Parral", "Retiro", "San Javier", "Villa Alegre", "Yerbas Buenas"],
        Ñuble: ["Chillán", "Bulnes", "Cobquecura", "Coelemu", "Coihueco", "Chillán Viejo", "El Carmen", "Ninhue", "Ñiquén", "Pemuco", "Pinto", "Portezuelo", "Quillón", "Quirihue", "Ránquil", "San Carlos", "San Fabián", "San Ignacio", "San Nicolás", "Treguaco", "Yungay"],
        Biobío: ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío"],
        Araucanía: ["Temuco", "Carahue", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Cholchol", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria"],
        "Los Ríos": ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"], 
        "Los Lagos": ["Ancud", "Calbuco", "Castro", "Chaitén", "Chonchi", "Cochamó", "Curaco de Vélez", "Dalcahue", "Fresia", "Frutillar", "Futaleufú", "Hualaihué", "Llanquihue", "Los Muermos", "Maullín", "Osorno", "Palena", "Puerto Montt", "Puerto Octay", "Puerto Varas", "Puqueldón", "Purranque", "Puyehue", "Queilén", "Quellón", "Quemchi", "Quinchao", "Río Negro", "San Juan de la Costa", "San Pablo"], 
        "Aysén del General Carlos Ibáñez del Campo": ["Coihaique", "Lago Verde", "Aisén", "Cisnes", "Guaitecas", "Cochrane", "O'Higgins", "Tortel", "Chile Chico", "Río Ibáñez"],
        "Magallanes y la Antártica": ["Punta Arenas", "Laguna Blanca", "Río Verde", "San Gregorio", "Cabo de Hornos (Ex Navarino)", "Antártica"],
    };
    
    const regionSelector = document.getElementById("selectRegiones");
    const comunaSelector = document.getElementById("selectComunas");
    
    regionSelector.addEventListener("change", function() {
      const regionSeleccionada = regionSelector.value;
      const comunas = regionesYcomunas[regionSeleccionada];
    
      // Limpiamos las opciones anteriores
      comunaSelector.innerHTML = "";
    
      // Agregamos las opciones de las comunas correspondientes
      comunas.forEach(function(comuna) {
        const opcion = document.createElement("option");
        opcion.value = comuna;
        opcion.text = comuna;
        comunaSelector.add(opcion);
      });
    });

    // Experiencias laborales
    const experiencia = [
        "Sin experiencia",
        "1 año",
        "2 años",
        "3 años",
        "4 años",
        "5 años",
        "6 años",
        "7 años",
        "8 años",
        "9 años",
        "10 años",
        "+ 10 años"
    ];
    
    const experienciaSelector = document.getElementById("experiencia");
    
    experiencia.forEach(function(experiencia) {
        const opcion = document.createElement("option");
        opcion.value = experiencia;
        opcion.text = experiencia;
        experienciaSelector.add(opcion);
    });

    // Sueldo pretendido
    const sueldo = [
        "$600.000", 
        "$700.000",
        "$800.000",
        "$900.000",
        "$1.000.000",
        "$1.100.000",
        "$1.200.000",
        "$1.300.000",
        "$1.400.000",
        "$1.500.000",
        "$1.600.000",
        "$1.700.000",
        "$1.800.000",
        "$1.900.000",
        "$2.000.000",
    ];
    
    const sueldoSelector = document.getElementById("sueldo");
    
    sueldo.forEach(function(sueldo) {
        const opcion = document.createElement("option");
        opcion.value = sueldo;
        opcion.text = sueldo;
        sueldoSelector.add(opcion);
    });

    // Validar contraseña
    const   password1 = document.getElementById("password1"),
            password2 = document.getElementById("password2"),
            togglePassword1 = document.getElementById("togglePassword1"),
            togglePassword2 = document.getElementById("togglePassword2");

    togglePassword1.addEventListener("click", function() {
    const type = password1.getAttribute("type") === "password" ? "text" : "password";
    password1.setAttribute("type", type);
    this.querySelector("i").classList.toggle("fa fa-eye-slash");
    });

    togglePassword2.addEventListener("click", function() {
    const type = password2.getAttribute("type") === "password" ? "text" : "password";
    password2.setAttribute("type", type);
    this.querySelector("i").classList.toggle("fa fa-eye-slash");
    });

    function checkPasswords() {
        if (password1.value !== password2.value) {
            password2.style.border = '2px solid red'; // Cambiar borde a rojo

        } else {
            password2.style.border = '';
        }
    }

    function checkPass() {
        if (password1.value !== password2.value) {
            password2.style.border = '2px solid red'; // Cambiar borde a rojo
            password2.focus();

        } else {
            password2.style.border = '';
        }
    }

    password1.addEventListener("keyup", checkPasswords);
    password2.addEventListener("blur", checkPass);
