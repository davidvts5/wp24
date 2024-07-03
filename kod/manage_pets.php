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
    exit; // Zaustavi dalje izvršavanje skripte nakon preusmerenja
}

// Upit za dobijanje svih kategorija životinja
$stmt_categories = $conn->query("SELECT * FROM categories");
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="ajax.js"></script>
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
                        <a class="nav-link p-2 ms-3 btn btn-primary" href="manage_pets.php"><i class="bi bi-gear"></i> Manage Categories and Breeds</a>
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

<div class="container mt-4">
    <div class="row">
        <!-- Leva kolona za kategorije zivotinja -->
        <div class="col-md-4">
            <h3>Animal Categories</h3>
            <div class="list-group">
                <?php foreach ($categories as $category): ?>
                    <div class="d-flex mb-2 align-items-center">
                        <a href="?category_id=<?= $category['category_id'] ?>" class="btn btn-success me-2"><?= $category['name'] ?></a>
                        <form action="delete_category.php" method="POST">
                            <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
                            <button type="submit" class="btn btn-danger">X</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <form action="add_animal_type.php" method="POST" class="mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control inp" placeholder="Enter new animal type..." name="animal_type" required>
                        <button class="btn btn-admin" type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Desna kolona za upravljanje rasama zivotinja -->
        <div class="col-md-4">
            <h3>Manage Breeds</h3>
            <div>
                <?php
                // Provera da li je prosleđen category_id kroz GET parametar
                if (isset($_GET['category_id'])) {
                    $category_id = $_GET['category_id'];

                    // Upit za dobijanje svih rasa za odabranu kategoriju
                    $stmt_breeds = $conn->prepare("SELECT * FROM breeds WHERE category_id = :category_id");
                    $stmt_breeds->bindParam(':category_id', $category_id, PDO::PARAM_INT);
                    $stmt_breeds->execute();
                    $breeds = $stmt_breeds->fetchAll(PDO::FETCH_ASSOC);

                    // Prikaz svih rasa
                    foreach ($breeds as $breed) {
                        echo '<div class="d-flex mb-2 align-items-center">';
                        echo '<a href="#" class="btn btn-outline-info btn-primary me-2">' . $breed['name'] . '</a>';

                        // Forma za brisanje rase
                        echo '<form action="delete_breed.php" method="POST">
                                <input type="hidden" name="breed_id" value="' . $breed['breed_id'] . '">
                                <button type="submit" class="btn btn-danger">X</button>
                              </form>';

                        echo '</div>';
                    }

                    // Forma za dodavanje nove rase
                    echo '<form action="add_breed.php" method="POST" class="mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-animal" placeholder="Enter new breed..." name="breed_name" required>
                                        <input type="hidden" name="category_id" value="' . (isset($_GET['category_id']) ? $_GET['category_id'] : '') . '">
                                        <!-- Skriveni input koji prenosi category_id -->
                                        <button class="btn btn-admin" type="submit">Add Breed</button>
                                    </div>
                                </form>';
                } else {
                    echo '<p>Select an animal category to manage breeds.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>