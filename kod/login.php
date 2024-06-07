<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// Uključivanje fajla za konekciju sa bazom
global $conn;
require_once "db_config.php";

// Provera da li su podaci poslati metodom POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prihvatanje vrednosti iz forme
    $email = $_POST["email"];
    $password = $_POST["password"];

    // SQL upit za proveru korisnika u bazi
    $sql = "SELECT * FROM user WHERE email = :email";

    // Priprema i izvršavanje SQL upita koristeći PDO
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Dobijanje podataka o korisniku
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Provera da li korisnik postoji i da li je uneta ispravna lozinka
    if ($user && password_verify($password, $user['password'])) {
        // Uspešna prijava, pohranjujemo ime korisnika u sesiju
        session_start();
        $_SESSION['user_id'] = $user['user_id']; // Postavljanje user_id u sesiju
        $_SESSION['email']=$user['email'];
        $_SESSION['phone']=$user['phone'];
        $_SESSION['user_firstname'] = $user['first_name'];

        // Preusmeravamo korisnika na početnu stranicu
        header("Location: index.php");
        exit();
    } else {
        // Neuspešna prijava, možemo sada preusmeriti korisnika nazad na login stranicu sa odgovarajućom porukom
        $error = "Invalid email or password";
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
            </ul>

        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-center">Login to your account</h5><br>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                        <span class="mt-3 ms-3">Don't have an account? <a href="register.php">Sign up</a></span>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
