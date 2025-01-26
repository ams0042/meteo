<?php
require 'vendor/autoload.php'; // Cargar las dependencias de Composer

function obtenerPrediccion() {
    $client = new MongoDB\Client(getenv('MONGODB_URI'));
    $db = $client->meteo;
    $collection = $db->meteo;

    $predicciones = ["Sol", "Nublado", "Lluvia"];
    $resultado = [];
    for ($i = 0; $i < 7; $i++) {
        $resultado[] = $predicciones[array_rand($predicciones)];
    }

    // Guardar las predicciones en MongoDB
    $collection->insertOne([
        'prediccion' => $resultado,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ]);

    return implode(", ", $resultado);
}

$server = new SoapServer(null, ['uri' => "http://localhost/meteo.php"]);
$server->addFunction("obtenerPrediccion");
$server->handle();
?>
