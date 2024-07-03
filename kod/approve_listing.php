<?php
include('db_config.php');

if (isset($_GET['id'])) {
    $listing_id = $_GET['id'];

    // SQL upit za ažuriranje oglasa
    $stmt = $conn->prepare("UPDATE listings1 SET approved = 1 WHERE listing_id = :listing_id");
    $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
    $stmt->execute();

    // Preusmeravanje nazad na stranicu sa upravljanjem listama
    header("Location: manage_listings.php");
    exit;
} else {
    // Ukoliko nije prosleđen ID oglasa, preusmeravanje na početnu stranicu
    header("Location: index.php");
    exit;
}
?>