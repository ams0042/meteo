<?php
require 'vendor/autoload.php'; // Cargar las dependencias de Composer

class HoraService {
    private $collection;

    public function __construct() {
        // Conexión a MongoDB
        $client = new MongoDB\Client(getenv('MONGODB_URI'));
        $db = $client->meteo; // Base de datos 'meteo'
        $this->collection = $db->hora; // Colección 'hora'
    }

    public function obtenerHora() {
        $hora = new DateTime("now", new DateTimeZone("UTC"));
        $hora->modify('+2 hours'); // Sumar 2 horas
        $horaActual = $hora->format('H:i:s');

        // Guardar la hora en MongoDB
        $this->collection->insertOne([
            'hora_servidor' => $horaActual,
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ]);

        return $horaActual;
    }
}

$server = new SoapServer(null, ['uri' => "http://localhost/hora.php"]);
$server->setClass("HoraService");
$server->handle();
?>
