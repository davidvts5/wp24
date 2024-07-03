<?php
session_start();
include('db_config.php');

// Provera da li su potrebni podaci prosleđeni kroz POST metod
if (isset($_POST['breed_name'], $_POST['category_id'])) {
    // Uhvati podatke iz POST metode
    $breed_name = $_POST['breed_name'];
    $category_id = $_POST['category_id'];

    try {
        // Priprema SQL upita za unos novog imena rase u bazu podataka
        $stmt = $conn->prepare("INSERT INTO breeds (category_id, name) VALUES (:category_id, :breed_name)");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':breed_name', $breed_name, PDO::PARAM_STR);
        $stmt->execute();

        // Ukoliko je uspešno dodata nova rasa, možete preusmeriti korisnika ili prikazati poruku
        header("Location: manage_pets.php?category_id=$category_id"); // Preusmerava na manage_pets.php sa category_id
        exit();
    } catch (PDOException $e) {
        // Uhvati grešku ako se desi bilo kakav problem sa bazom podataka
        echo "Error: " . $e->getMessage();
    }
} else {
    // Ako nisu pravilno prosleđeni podaci kroz POST metod
    echo "Required data missing.";
}
?>