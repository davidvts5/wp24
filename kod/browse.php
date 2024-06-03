<?php
global $conn;
require_once ('db_config.php');
session_start();
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
                    <li class="nav-item">
                        <a class="btn btn-primary p-2 ms-3" href="#">
                            &nbsp;Create Listing&nbsp;
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
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
        <!-- Leva kolona - oglasi -->
        <div class="col-md-8">
            <h2>Ads</h2>
            <div class="row">
                <?php
                // Izvršavanje upita za dohvatanje podataka o oglasima
                $stmt = $conn->prepare("SELECT title, price FROM listings");
                $stmt->execute();

                // Postavljanje rezultata u asocijativni niz
                $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($ads as $ad) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ad['title']); ?></h5>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($ad['price']); ?></p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Desna kolona - forma za filtriranje -->
        <div class="col-md-4">
            <h4>Search Filters</h4>
            <form method="GET" action="browse.php">
                <div class="mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Enter dog name">
                </div>
                <div class="mb-3">
                    <label for="breed" class="form-label">Breed</label>
                    <select class="form-select" id="breed" name="breed">
                        <option value="">Select breed</option>
                        <option value="Labrador">Labrador</option>
                        <option value="German Shepherd">German Shepherd</option>
                        <option value="Golden Retriever">Golden Retriever</option>
                        <!-- Dodajte više opcija po potrebi -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
$conn = null;
?>