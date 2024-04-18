<?php
session_start();

$dsn = "mysql:host=localhost;dbname=movies_cc_db";
$dbusername = "dbuser";
$dbpassword = "zGfaIW9YDH1GFa2e";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST["gebruikersnaam"];
    $wachtwoord = $_POST["wachtwoord"];
    $herinnermij = isset($_POST["herinnermij"]) ? 1 : 0;

    $query = "SELECT * FROM gebruikers WHERE Gebruikersnaam = ? AND Wachtwoord = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$gebruikersnaam, $wachtwoord]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // If user is found, set session variables
        $_SESSION["gebruikersnaam"] = $gebruikersnaam;
        $_SESSION["rol"] = $user['Rol']; // Assuming 'Rol' is the column name for role in the database
        
        // Check if the user is an admin, then redirect accordingly
        if ($_SESSION["rol"] == 'Admin') {
            header('Location: ../crud.php'); // Redirect to crud.php if user is an admin
        } else {
            header('Location: ../Films.php'); // Redirect to Films.php if user is not an admin
        }
        exit();
    } else {
        echo "Ongeldige inloggegevens";
    }
}
