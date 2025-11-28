<?php
$host = "localhost";
$user = "ValentinRuiz";   // el usuario que creaste en MySQL
$pass = "TuPasswordSeguro123";        // la contraseña que le diste
$db   = "pendinails";         // nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
