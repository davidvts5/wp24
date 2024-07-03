<?php
// Uključivanje konekcije ka bazi podataka
include('db_config.php');

// Provera da li je poslat POST zahtev
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['breed_id'])) {
    // Dobijanje breed_id koji treba obrisati
    $breed_id = $_POST['breed_id'];
    $category_id=$_POST['category_id'];

    // SQL upit za brisanje rase iz tabele breeds
    $stmt = $conn->prepare("DELETE FROM breeds WHERE breed_id = :breed_id");
    $stmt->bindParam(':breed_id', $breed_id, PDO::PARAM_INT);

    // Izvršavanje upita
    try {
        $stmt->execute();
        // Ako je uspešno izvršeno brisanje, redirekcija nazad na stranicu za upravljanje rasama
        header("Location: manage_pets.php?category_id=$category_id");
        exit();
    } catch (PDOException $e) {
        // U slučaju greške pri izvršavanju upita, prikazivanje poruke o grešci
        echo "Error: " . $e->getMessage();
    }
} else {
    // Ako nije poslat POST zahtev sa breed_id, redirekcija na početnu stranicu ili drugu odgovarajuću stranicu
    header("Location: index.php");
    exit();
}
?>