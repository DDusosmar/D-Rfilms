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
    $email = $_POST["email"];
    $wachtwoord = $_POST["wachtwoord"];
    
    $rol = (strpos($email, 'admin') !== false) ? 'Admin' : 'Kijker';
    
    try {
        $query = "INSERT INTO gebruikers (Gebruikersnaam, Email, Wachtwoord, Rol) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$gebruikersnaam, $email, $wachtwoord, $rol]);
        
        // Fetch user's role from the database
        $query = "SELECT Rol FROM gebruikers WHERE Email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Store user's role in session
        $_SESSION["rol"] = $rol;
        
        $_SESSION["gebruikersnaam"] = $gebruikersnaam;
        header('Location: ../Home.php');
        exit();
        
    } catch (PDOException $e) {
        die("Query mislukt: " . $e->getMessage());
    }
}