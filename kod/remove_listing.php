<?php
include('db_config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $listing_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM listings1 WHERE user_id = :user_id AND listing_id = :listing_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
        $stmt->execute();
        echo "success";
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
