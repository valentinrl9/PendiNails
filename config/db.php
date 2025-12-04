<?php
$host = "localhost";
$user = "ValentinRuiz";   // tu usuario MySQL
$pass = "TuPasswordSeguro123"; // tu contraseña
$db   = "pendinails";     // nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>


