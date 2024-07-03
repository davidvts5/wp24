<?php
// Uključivanje konekcije ka bazi podataka
include('db_config.php');

// Provera da li je poslat POST zahtev
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'])) {
    // Dobijanje category_id koji treba obrisati
    $category_id = $_POST['category_id'];

    // SQL upit za proveru da li postoje rase povezane sa ovom kategorijom
    $stmt_check_breeds = $conn->prepare("SELECT COUNT(*) AS count_breeds FROM breeds WHERE category_id = :category_id");
    $stmt_check_breeds->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt_check_breeds->execute();
    $result = $stmt_check_breeds->fetch(PDO::FETCH_ASSOC);

    // Provera da li postoje rase povezane sa kategorijom
    if ($result['count_breeds'] > 0) {
        // Ako postoje rase povezane sa kategorijom, ne dozvoli brisanje
        echo "Cannot delete category. There are breeds associated with this category.";
        exit();
    }

    // SQL upit za brisanje kategorije iz tabele categories
    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    // Izvršavanje upita
    try {
        $stmt->execute();
        // Ako je uspešno izvršeno brisanje, redirekcija nazad na stranicu za upravljanje kategorijama
        header("Location: manage_pets.php");
        exit();
    } catch (PDOException $e) {
        // U slučaju greške pri izvršavanju upita, prikazivanje poruke o grešci
        echo "Error: " . $e->getMessage();
    }
} else {
    // Ako nije poslat POST zahtev sa category_id, redirekcija na početnu stranicu ili drugu odgovarajuću stranicu
    header("Location: index.php");
    exit();
}
?>