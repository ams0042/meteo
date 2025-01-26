<?php
require 'vendor/autoload.php'; // Cargar las dependencias de Composer

function obtenerHumedad() {
    $client = new MongoDB\Client(getenv('MONGODB_URI'));
    $db = $client->meteo;
    $collection = $db->humedad;

    $humedad = rand(0, 100) . "%";

    // Guardar la humedad en MongoDB
    $collection->insertOne([
        'humedad' => $humedad,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ]);

    return $humedad;
}

$server = new SoapServer(null, ['uri' => "http://localhost/humedad.php"]);
$server->addFunction("obtenerHumedad");
$server->handle();
?>
