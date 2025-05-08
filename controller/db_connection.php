<?php
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "inventario";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>