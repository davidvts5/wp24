<?php
include ('db_config.php');
global $conn;
session_start();

$isAdmin = false;

// Provera da li je korisnik ulogovan
if (isset($_SESSION['user_id'])) {
    // Ako je korisnik ulogovan, dobijamo ulogu korisnika iz baze podataka
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Provera uloge korisnika
    if ($user && $user['role'] === 'admin') {
        $isAdmin = true;
    }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-primary p-2 ms-3" href="add_listing.php">
                            &nbsp;Create Listing&nbsp;
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($_SESSION['user_firstname']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="my_listings.php">My listings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="favorite_pets.php">Favorite pets</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="edit_account.php">Edit account</a></li>
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

<?php
// Dohvatanje oglasa
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo $id;
    try {
        // Dohvatanje osnovnih podataka o oglasu
        $stmt = $conn->prepare("SELECT * FROM listings1 WHERE listing_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ad = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dohvatanje imena kategorije i rase na osnovu breed_id
        if ($ad) {
            $stmtCategory = $conn->prepare("SELECT name FROM categories WHERE category_id = :category_id");
            $stmtCategory->bindParam(':category_id', $ad['category_id'], PDO::PARAM_INT);
            $stmtCategory->execute();
            $category = $stmtCategory->fetch(PDO::FETCH_ASSOC);

            $stmtBreed = $conn->prepare("SELECT name FROM breeds WHERE breed_id = :breed_id");
            $stmtBreed->bindParam(':breed_id', $ad['breed_id'], PDO::PARAM_INT);
            $stmtBreed->execute();
            $breed = $stmtBreed->fetch(PDO::FETCH_ASSOC);

            // Dohvatanje informacija o korisniku koji je postavio oglas
            $stmtUser = $conn->prepare("SELECT first_name, last_name, email, phone FROM user WHERE user_id = :user_id");
            $stmtUser->bindParam(':user_id', $ad['user_id'], PDO::PARAM_INT);
            $stmtUser->execute();
            $userDetails = $stmtUser->fetch(PDO::FETCH_ASSOC);

            echo "<div class='container mt-5 ms-6 p-5 shadow-lg border-primary'>";
            if(isset($_SESSION['user_firstname'])) {
                echo "<button class='des btn btn-primary add-to-favorites' data-id='" . htmlspecialchars($ad['listing_id']) . "'>Add to favorites <i class='bi bi-heart-fill'></i></button>";
            }
            echo "<div class='row'>";
            echo "<div class='col-md-6'>";

            echo "<img src='" . htmlspecialchars($ad['image']) . "' class='shadow-lg border mb-3' width='50%' alt='Ad Image'>";

            echo "<br><p><strong>Posted by:</strong></p>";
            echo "<p>User: " . htmlspecialchars($userDetails['first_name'] . " " . $userDetails['last_name']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($userDetails['email']) . "</p>";
            echo "<p>Phone Number: " . htmlspecialchars($userDetails['phone']) . "</p>";
            echo '<button type="button" class="btn btn-primary p-2 ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal">';
            echo 'Send Message';
            echo '</button>';
            echo "</div>";

            echo "<div class='col-md-6'>";
            if (isset($_SESSION['first_name'])) {
                echo "<button class='des btn btn-primary add-to-favorites' data-id='" . htmlspecialchars($ad['listing_id']) . "'>Add to favorites <i class='bi bi-heart-fill'></i></button>";
            }
            echo "<h1>" . htmlspecialchars($ad['title']) . "</h1>";
            echo "<h5><i>Price: $" . htmlspecialchars($ad['price']) . "</i></h5>";
            echo "<p><i>Age: " . htmlspecialchars($ad['age']) . "</i></p>";
            echo '<u>
                    <span class="card-text">' . htmlspecialchars($category['name']) . ',</span>
                    <span class="card-text">' . htmlspecialchars($breed['name']) . '</span>
                  </u>';
            echo "<hr>";
            echo "<p>" . htmlspecialchars($ad['description']) . "</p>";

            // Prikaz informacija o korisniku koji je postavio oglas


            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "Listing not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "Listing ID not provided.";
}
?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Sending message to user</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" readonly value="<?php echo htmlspecialchars($userDetails['first_name']) ?> <?php echo htmlspecialchars($userDetails['last_name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Message</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="message_text" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="send_message">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>