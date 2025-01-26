<?php
require 'vendor/autoload.php'; // Cargar las dependencias de Composer

function obtenerViento() {
    $client = new MongoDB\Client(getenv('MONGODB_URI'));
    $db = $client->meteo;
    $collection = $db->viento;

    $viento = rand(0, 50) . " km/h";

    // Guardar la velocidad del viento en MongoDB
    $collection->insertOne([
        'viento' => $viento,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ]);

    return $viento;
}

$server = new SoapServer(null, ['uri' => "http://localhost/viento.php"]);
$server->addFunction("obtenerViento");
$server->handle();
?>
