function validarSoloLetras(input) {
    var regex = /^[A-Za-z]+$/;
    var valor = input.value;
    
    if (!regex.test(valor)) {
      input.value = valor.slice(0, -1);
    }
  }