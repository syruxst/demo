<script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
<?php
$url = 'https://graph.facebook.com/v16.0/100637266390595/messages';
$token = 'EAAySun9qCeoBAEplh0m0Fozyz00F0igVOmWxcYV6w8NWvVv0ZBdvU3AdYysLgrzABsikqBLAuc4dOeqZCwzpU2bJ6nuQ4YS5khdEDMhMXDEaZCl2610A4CibukIRA3MBwmyNcNFlHQJSTf4SphhA5Ill4mSSbPgKZA49ZBMhurDZB3PkaWMsFg';
//EAAySun9qCeoBAEplh0m0Fozyz00F0igVOmWxcYV6w8NWvVv0ZBdvU3AdYysLgrzABsikqBLAuc4dOeqZCwzpU2bJ6nuQ4YS5khdEDMhMXDEaZCl2610A4CibukIRA3MBwmyNcNFlHQJSTf4SphhA5Ill4mSSbPgKZA49ZBMhurDZB3PkaWMsFg
$phoneNumbers = $_POST['numeros'];
$mensaje = $_POST['mensaje'];

$header = 'Operamaq Empresas';
$content = $mensaje;
$footer = '------------------------' . "\n" . 'Gracias por confiar en nosotros.';

foreach ($phoneNumbers as $phoneNumber) {
    $headerFormatted = '*' . $header . '*';
    $message = array(
        'messaging_product' => 'whatsapp',
        'to' => $phoneNumber,
        'type' => 'text',
        'text' => array(
            'body' => $headerFormatted . "\n\n" . $content . "\n\n" . $footer
        )
    );

    $dataJson = json_encode($message);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        echo "Mensaje enviado con éxito a " . $phoneNumber . "<br>";
    } else {
        echo "Error al enviar el mensaje a " . $phoneNumber . "<br>";
    }
}
?>