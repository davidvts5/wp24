<?php
global $conn;
session_start();
include('db_config.php');
// Provera da li je korisnik ulogovan
if (isset($_SESSION['user_id'])) {
    // Ako je korisnik ulogovan, dobijamo ulogu korisnika iz baze podataka
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT role FROM user WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Provera uloge korisnika
    if ($user && $user['role'] === 'admin') {
        $isAdmin = true;
    }
}
if(!$isAdmin)
{
    header("Location:index.php");
}
if ($isAdmin) {
    $stmt = $conn->prepare("SELECT listing_id, title, price, image FROM listings1 WHERE approved =1");
    $stmt->execute();
    $activeAds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Ukoliko korisnik nije admin, preusmeravanje na početnu stranicu
    header("Location: index.php");
    exit;
}
if ($isAdmin) {
    $stmt = $conn->prepare("SELECT listing_id, title, price, image FROM listings1 WHERE approved =0");
    $stmt->execute();
    $waitingAds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Ukoliko korisnik nije admin, preusmeravanje na početnu stranicu
    header("Location: index.php");
    exit;
}
if ($isAdmin) {
    $stmt = $conn->prepare("SELECT listing_id, title, price, image FROM listings1 WHERE approved =2");
    $stmt->execute();
    $inactiveAds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Ukoliko korisnik nije admin, preusmeravanje na početnu stranicu
    header("Location: index.php");
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawsPlanet</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm p-2">
    <div class="container-fluid">
        <a class="navbar-brand p-1 ms-6" href="index.php">PawsPlanet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link p-1 ms-3" aria-current="page" href="browse.php"><i class="bi bi-search"></i> Browse</a>
                </li>
                <li class="nav-item admin-border">
                    <a class="nav-link p-1 ms-3" href="#"><i class="bi bi-info-circle"></i> About us</a>
                </li>
            </ul>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link p-2 ms-5 custom-margin-left btn btn-primary" href="manage_pets.php"><i class="bi bi-gear"></i> Manage Categories and Breeds</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2 ms-3 btn btn-primary" href="manage_listings.php"><i class="bi bi-gear"></i> Manage Listings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2 ms-3 btn btn-primary" href="manage_users.php"><i class="bi bi-gear"></i> Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2 ms-3 btn btn-primary btn btn-admin" href="admin_page.php"><i class="bi bi-gear"></i> ADMIN</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3 browse">
    <div class="row">
        <div class="col">
            <h2>Listings Waiting for Approval</h2>
            <div class="scrollable">
                <div class="d-flex flex-nowrap">
                    <?php foreach ($waitingAds as $ad): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($ad['image']); ?>" class="card-img-top p-2 oglasi" alt="Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($ad['price']); ?></p>
                                <a href="approve_listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-success">Accept</a>
                                <a href="decline_listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-danger">Decline</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <hr class="border-primary">
    <div class="row mt-3">
        <div class="col">
            <h2>Active Listings</h2>
            <div class="scrollable">
                <div class="d-flex flex-nowrap">
                    <?php foreach ($activeAds as $ad): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($ad['image']); ?>" class="card-img-top p-2 oglasi" alt="Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($ad['price']); ?></p>

                                    <a href="listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-success me-2">View Details</a>
                                    <br>
                                    <a href="inactive_listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-danger">Delete Listing</a>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <hr class="border-primary">
    <div class="row mt-3">
        <div class="col">
            <h2>Inactive Listings</h2>
            <div class="scrollable">
                <div class="d-flex flex-nowrap ">
                    <?php foreach ($inactiveAds as $ad): ?>
                        <div class="card bg-dark-subtle">
                            <img src="<?php echo htmlspecialchars($ad['image']); ?>" class="card-img-top p-2 oglasi " alt="Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($ad['price']); ?></p>
                                <a href="approve_listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-success">Activate listing</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>