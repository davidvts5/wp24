<?php
// Uključivanje konekcije sa bazom podataka
include('db_config.php');

// Provera da li je zahtev poslat metodom POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Provera da li je polje animal_type postavljeno i nije prazno
    if (isset($_POST['animal_type']) && !empty($_POST['animal_type'])) {
        // Dobijanje unetog imena tipa životinje iz forme
        $animalType = $_POST['animal_type'];

        // Priprema SQL upita za dodavanje novog tipa životinje u bazu
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindParam(':name', $animalType, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: manage_pets.php");
        exit;
    }
}
?>
