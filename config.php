<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "iot";

$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
