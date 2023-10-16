<?php

// Variables de conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$database = "timeclock";

// Conectamos a la base de datos
$mysqli = new mysqli($host, $username, $password, $database);

// Comprobamos la conexión
if ($mysqli->connect_error) {
  die("Error al conectar a la base de datos: " . $mysqli->connect_error);
}

// Establecemos la codificación de la conexión
$mysqli->set_charset("utf8mb4");

?>