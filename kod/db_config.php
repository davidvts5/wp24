<?php
$servername = "localhost"; // Promenite ukoliko se baza nalazi na drugom serveru
$username = "korisnicko_ime"; // Promenite sa vašim korisničkim imenom za pristup bazi
$password = "lozinka"; // Promenite sa vašom lozinkom za pristup bazi
$dbname = "ime_baze"; // Promenite sa imenom vaše baze

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>