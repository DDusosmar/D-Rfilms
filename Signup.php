<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- Bootstrap JS en afhankelijkheden -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Movies.cc</title>  
</head>
<body>
    <header>
        <div>
            <a href="./Home.php">
                <img src="./assets/Images/popcorn.svg" style="width: 10%;" alt="Logo">
            </a>
        </div>
    </header>
    <div>
        <h2>Aanmelden</h2>
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

            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                $gebruikersnaam = $_POST["gebruikersnaam"];
                $email = $_POST["email"];
                $wachtwoord = $_POST["wachtwoord"];
                
                $rol = (strpos($email, 'admin') !== false) ? 'Admin' : 'Kijker';
                
                try {
                    $query = "INSERT INTO gebruikers (Gebruikersnaam, Email, Wachtwoord, Rol) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$gebruikersnaam, $email, $wachtwoord, $rol]);
                    
                    $_SESSION["gebruikersnaam"] = $gebruikersnaam;
                    header('Location: ./Home.php');
                    die();
                    
                } catch (PDOException $e) {
                    die("Query mislukt: " . $e->getMessage());
                }
            }
            ?>
            <form method="POST">
            <table cellspacing="5" cellpadding="0"  width="50%">
                <tr><td><input type='text' name='gebruikersnaam' size='50' placeholder='Gebruikersnaam'></td></tr>
                <tr><td><input type='text' name='email' size='50' placeholder='E-mail'></td></tr>
                <tr><td><input type='password' name='wachtwoord' size='50' placeholder='Wachtwoord'></td></tr>
            </table>
            <input type="submit" name="Inloggen" value="Aanmelden">
        </form>
    </div>
</body>
<footer>
    <div>
        <div>
            <p>&copy; 2024 Alle rechten voorbehouden <a href="#">D&R Filmen</a></p>
            <div></div>
            <img src="./assets/images/footer-bottom-img.png" alt="Online banking Companies">
        </div>
    </div>
</footer>
</html>