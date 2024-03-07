// Función que detecta si el usuario ha hecho scroll en la página
function mostrarContenido() {
    var contenido = document.querySelector('.contenido');
    var contenidoPos = contenido.getBoundingClientRect().top;
    var pantallaPos = window.innerHeight / 1.3; // Cambiar este valor para ajustar la posición en la que aparece el contenido
    if (contenidoPos < pantallaPos) {
      contenido.style.opacity = '1';
    }
  }
  
  // Agregamos un evento al hacer scroll en la página
  window.addEventListener('scroll', mostrarContenido);