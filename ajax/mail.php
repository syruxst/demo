<?php

function enviarCotizacion($numberCot, $detalles) {
    // Configuración básica
    $destinatario = 'daniel@ugalde.cl';
    $asunto = 'Se ha creado una nueva cotización N° ' . $numberCot;
    
    // Crear el cuerpo del mensaje
    $mensaje = 'Por favor revisa la Cotización para su validación. <br>';
    foreach ($detalles as $detalle) {
        $mensaje .= $detalle . "<br>";
    }
    
    // Headers adicionales para el correo
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: Operamaq Empresa Spa <venta@operamaq.cl>' . "\r\n";
    
    // Enviar el correo
    return mail($destinatario, $asunto, $mensaje, $headers);
}

?>