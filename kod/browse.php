<?php
global $conn;
require_once ('db_config.php');
session_start();
$isAdmin = false;

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

// Dohvatanje tipova životinja i rasa
$stmt = $conn->prepare("SELECT category_id, name FROM categories");
$stmt->execute();
$animal_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT breed_id, category_id, name FROM breeds");
$stmt->execute();
$breeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Priprema upita za dohvaćanje oglasa sa filtrima
$query = "
    SELECT l.listing_id, l.title, l.price, l.image, b.name AS breed_name, c.name AS category_name
    FROM listings1 l
    JOIN breeds b ON l.breed_id = b.breed_id
    JOIN categories c ON b.category_id = c.category_id
    WHERE l.approved = 1
";

$conditions = [];
$params = [];

// Filtriranje po nazivu oglasa
if (!empty($_GET['search'])) {
    $conditions[] = "l.title LIKE :search";
    $params[':search'] = '%' . $_GET['search'] . '%';
}

// Filtriranje po tipu životinje
if (!empty($_GET['animal_type'])) {
    $conditions[] = "c.category_id = :animal_type";
    $params[':animal_type'] = $_GET['animal_type'];
}

// Filtriranje po rasi
if (!empty($_GET['breed'])) {
    $conditions[] = "b.breed_id = :breed";
    $params[':breed'] = $_GET['breed'];
}

// Filtriranje po starosti
if (!empty($_GET['min_age'])) {
    $conditions[] = "l.age >= :min_age";
    $params[':min_age'] = $_GET['min_age'];
}
if (!empty($_GET['max_age'])) {
    $conditions[] = "l.age <= :max_age";
    $params[':max_age'] = $_GET['max_age'];
}

// Filtriranje po ceni
if (!empty($_GET['min_price'])) {
    $conditions[] = "l.price >= :min_price";
    $params[':min_price'] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $conditions[] = "l.price <= :max_price";
    $params[':max_price'] = $_GET['max_price'];
}

// Kombinovanje uslova
if (count($conditions) > 0) {
    $query .= ' AND ' . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawsPlanet - Browse</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="js.js"></script>
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
                <li class="nav-item">
                    <a class="nav-link p-1 ms-3" href="#"><i class="bi bi-info-circle"></i> About us</a>
                </li>
            </ul>

            <ul class="navbar-nav custom-margin-right">
                <?php if (isset($_SESSION['user_firstname'])): ?>
                <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="btn btn-danger p-2 ms-3" href="admin_page.php">ADMIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary p-2 ms-3" href="add_listing.php">
                            &nbsp;Create Listing&nbsp;
                        </a>
                    </li>
                    <?php elseif (!$isAdmin):?>
                        <li class="nav-item">
                            <a class="btn btn-primary p-2 ms-3" href="add_listing.php">
                                &nbsp;Create Listing&nbsp;
                            </a>
                        </li>
                <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="my_listings.php">My listings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="favorite_pets.php">Favorite pets</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Edit account</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> Account
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="login.php">Sign in</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="register.php">Create an account</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <!-- Desna kolona - forma za filtriranje -->
        <div class="col-md-4 order-1 order-md-2 mb-3">
            <div class="filter-form">
                <h4>Search Filters</h4>
                <form method="GET" action="browse.php">
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Enter ad title">
                    </div>
                    <div class="mb-3">
                        <label for="animal_type" class="form-label">Animal Type</label>
                        <select class="form-select" id="animal_type" name="animal_type">
                            <option value="">Select animal type</option>
                            <?php foreach ($animal_types as $type): ?>
                                <option value="<?php echo $type['category_id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="breed" class="form-label">Breed</label>
                        <select class="form-select" id="breed" name="breed">
                            <option value="">Select breed</option>
                            <?php foreach ($breeds as $breed): ?>
                                <option value="<?php echo $breed['breed_id']; ?>" data-category-id="<?php echo $breed['category_id']; ?>"><?php echo htmlspecialchars($breed['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_age" class="form-label">Age</label>
                        <div class="d-flex">
                            <input type="number" class="form-control me-2" id="min_age" name="min_age" placeholder="Min age">
                            <input type="number" class="form-control" id="max_age" name="max_age" placeholder="Max age">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="min_price" class="form-label">Price</label>
                        <div class="d-flex">
                            <input type="number" class="form-control me-2" id="min_price" name="min_price" placeholder="Min price">
                            <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Max price">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        <!-- Leva kolona - oglasi -->
        <div class="col-md-8 order-2 order-md-1 browse">
            <div class="row">
                <?php
                if (isset($_GET['search'])){
                    $search = $_GET['search'];
                }

                // Izvršavanje upita za dohvatanje podataka o oglasima


                foreach ($ads as $ad) {
                    ?>
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($ad['image']); ?>" class="card-img-top p-2 oglasi" alt="Ad Image">
                            <div class="card-body">
                                <h5 class="card-title"><b><?php echo htmlspecialchars($ad['title']); ?></b></h5>
                                <i><span class="card-text">Price: $<?php echo htmlspecialchars($ad['price']); ?></span></i><br>
                                <u><span class="card-text"> <?php echo htmlspecialchars($ad['category_name']); ?>,</span>
                                <span class="card-text"><?php echo htmlspecialchars($ad['breed_name']); ?></span></u>
                                <a href="listing.php?id=<?php echo htmlspecialchars($ad['listing_id']); ?>" class="btn btn-primary mt-2">View Details</a><br>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php

$conn = null;
?>
