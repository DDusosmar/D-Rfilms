<script src="https://cdn.tailwindcss.com"></script>
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
    $herinnermij = isset($_POST["herinnermij"]) ? 1 : 0;

    $query = "SELECT * FROM gebruikers WHERE Gebruikersnaam = ? AND Wachtwoord = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$gebruikersnaam, $wachtwoord]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && isset($user['Rol'])) {
        $_SESSION["rol"] = $user['Rol'];
        if ($user['Rol'] == 'Admin') {
            // Redirect the admin to a different website
            header('Location: https://example.com/admin_dashboard.php');
            exit();
        }
    } else {
        if ($user) {
            $_SESSION["gebruikersnaam"] = $gebruikersnaam;
            $_SESSION["rol"] = $user['Rol'];
            if ($herinnermij) {
                setcookie("gebruikersnaam", $gebruikersnaam, time() + (86400 * 30), "/"); // 30 dagen
            }
            header('Location: ../Home.php');
            exit();
        } else {
            echo "<script>alert('Ongeldige inloggegevens')</script>";
            echo '<div class="flex justify-center"><a href="../Login.html"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transform -translate-x-1/2">Probeer Opnieuw</button><a/></div>';
        }
    }
}
?>
