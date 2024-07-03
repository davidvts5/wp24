<?php
global $conn, $e;
session_start();
require_once('db_config.php');

if (!isset($_SESSION['user_firstname'])) {
    header("Location: login.php");
    exit;
}

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

// Dobavi kategorije za formu
try {
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Dobavi rase za formu
try {
    $stmt = $conn->prepare("SELECT * FROM breeds");
    $stmt->execute();
    $breeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Provera da li je forma poslata
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $phone = $_POST['phone'];
    $email = $_SESSION['email'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $age=$_POST['age'];
    $user_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $breed_id = $_POST['breed_option'];

    if ($breed_id == 'new') {
        $new_breed = $_POST['new_breed'];
        // Dodaj novu rasu u bazu podataka
        $stmt = $conn->prepare("INSERT INTO breeds (category_id, name) VALUES (:category_id, :name)");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $new_breed, PDO::PARAM_STR);
        $stmt->execute();
        // Dobij novi breed_id
        $breed_id = $conn->lastInsertId();
    }

    // Upload slike
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Proveri da li je fajl slika
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Proveri veličinu fajla
    if ($_FILES["image"]["size"] > 1000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Dozvoli određene formate
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Proveri da li je $uploadOk postavljen na 0 zbog greške
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // Ako je sve u redu, pokušaj da uploaduješ fajl
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Unesi podatke u bazu
            $stmt = $conn->prepare("INSERT INTO listings1 (user_id, category_id, breed_id, title, description, price,age, image,email,phone) VALUES (:user_id, :category_id, :breed_id, :title, :description, :price,:age, :image,:email,:phone)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':breed_id', $breed_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':age', $age, PDO::PARAM_STR);
            $stmt->bindParam(':image', $target_file, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();

            // Poruka o uspehu
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " LISTING IS CREATED!!!!";
        }
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

<div class="container mt-4">
    <h2>Create Listing</h2>
    <form action="add_listing.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $_SESSION['phone']?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id" class="form-label">Animal Type</label>
                    <select class="form-select ddlist" id="category_id" name="category_id" required>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="breed_option" class="form-label">Breed</label>
                    <select class="form-select ddlist" id="breed_option" name="breed_option" required>
                        <option value="">Select existing breed</option>
                        <?php foreach($breeds as $breed): ?>
                            <option value="<?php echo $breed['breed_id']; ?>" data-category-id="<?php echo $breed['category_id']; ?>"><?php echo $breed['name']; ?></option>
                        <?php endforeach; ?>
                        <option value="new">Add new breed</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="new_breed" name="new_breed" placeholder="Enter new breed" style="display: none;">
                </div>
                <div class="form-group">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" step="1" class="form-control" id="age" name="age" required>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Listing</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var categorySelect = document.getElementById('category_id');
        var breedSelect = document.getElementById('breed_option');
        var newBreedInput = document.getElementById('new_breed');

        function filterBreeds() {
            var selectedCategory = categorySelect.value;
            var firstVisibleOption = null;

            for (var i = 0; i < breedSelect.options.length; i++) {
                var option = breedSelect.options[i];
                if (option.getAttribute('data-category-id') === selectedCategory || option.value === 'new') {
                    option.style.display = '';
                    if (!firstVisibleOption) {
                        firstVisibleOption = option;
                    }
                } else {
                    option.style.display = 'none';
                }
            }

            if (firstVisibleOption) {
                breedSelect.value = firstVisibleOption.value;
            }
        }

        function toggleNewBreedInput() {
            if (breedSelect.value === 'new') {
                newBreedInput.style.display = '';
                newBreedInput.required = true;
            } else {
                newBreedInput.style.display = 'none';
                newBreedInput.required = false;
            }
        }

        categorySelect.addEventListener('change', filterBreeds);
        breedSelect.addEventListener('change', toggleNewBreedInput);

        // Initial filtering on page load
        filterBreeds();
        toggleNewBreedInput();
    });
</script>

</body>
</html>