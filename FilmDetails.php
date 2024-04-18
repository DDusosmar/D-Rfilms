<?php
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
        <nav class="bg-transparent p-5 flex justify-between items-center">
            <a href=""><img src="Images/popcorn.svg" alt="Movies.cc Logo" class="w-10"></a>
            <h1 class="text-3xl font-bold">D&R Films</h1>
            <div class="hidden md:flex md:items-center md:w-auto">
        <div class="md:flex ">
            <a href="Home.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-white mr-4">
                Home
            </a>
            <a href="AboutUs.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-white mr-4">
                About
            </a>
            <a href="Films.php" class="block mt-4 md:inline-block md:mt-0 text-black hover:text-white">
                Movies
            </a>
        </div>
        </nav>
    <main class="container mx-auto py-8">
        <article>
            <section class="mx-auto bg-white p-8 rounded-lg shadow-lg">
                <?php
                $servername = "localhost";
                $username = "dbuser";
                $password = "zGfaIW9YDH1GFa2e";
                $dbname = "movies_cc_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $filmId = isset($_GET['filmId']) ? $_GET['filmId'] : '';

                if (!empty($filmId)) {
                    $query = "SELECT f.FilmID, f.Titel, f.Regisseurnaam, f.Releasejaar, f.Beschrijving, f.PosterImage, g.Genrenaam, h.GebruikerID
                    FROM films f, genre g, gebruikers h
                    WHERE f.FilmID = '$filmId'";
         

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<div class='flex items-center justify-center'>";
                        echo "<div class='max-w-md'>";
                        echo "<img src='" . htmlspecialchars($row["PosterImage"]) . "' alt='" . htmlspecialchars($row["Titel"]) . "' class='w-full'>";
                        echo "<div class='mt-4'>";
                        echo "<h2 class='text-xl font-bold'>" . htmlspecialchars($row["Titel"]) . "</h2>";
                        echo "<p><span class='font-semibold'>Genre:</span> " . htmlspecialchars($row["Genrenaam"]) . "</p>";
                        echo "<p><span class='font-semibold'>Regisseur:</span> " . htmlspecialchars($row["Regisseurnaam"]) . "</p>";
                        echo "<p><span class='font-semibold'>Releasejaar:</span> " . htmlspecialchars($row["Releasejaar"]) . "</p>";
                        echo "<p><span class='font-semibold'>Beschrijving:</span> " . htmlspecialchars($row["Beschrijving"]) . "</p>";
                        echo "<p><span class='font-semibold'>Beoordeel:</span></p>";
                        echo "<form action='phpexecutables/beoordeling.php' method='post' class='inline'>";
                        echo "<input type='hidden' name='film_id' value='" . $row["FilmID"] . "'>";
                        echo "<input type='hidden' name='gebruiker_id' value='" . $row["GebruikerID"] . "'>";
                        echo "<input type='radio' id='rating1' name='rating' value='1' class='mr-1'>";
                        echo "<label for='rating1'>1 ster</label>";
                        echo "<input type='radio' id='rating2' name='rating' value='2' class='mr-1'>";
                        echo "<label for='rating2'>2 sterren</label>";
                        echo "<input type='radio' id='rating3' name='rating' value='3' class='mr-1'>";
                        echo "<label for='rating3'>3 sterren</label>";
                        echo "<input type='radio' id='rating4' name='rating' value='4' class='mr-1'>";
                        echo "<label for='rating4'>4 sterren</label>";
                        echo "<input type='radio' id='rating5' name='rating' value='5' class='mr-1'>";
                        echo "<label for='rating5'>5 sterren</label>";
                        echo "<button type='submit' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Beoordelen</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<p>Geen film gevonden.</p>";
                    }
                } else {
                    echo "<p>Geen film geselecteerd.</p>";
                }

                $conn->close();
                ?>
            </section>
        </article>
    </main>

    <?php
    $conn = new mysqli($servername, $username, $password, $dbname);

// Controleren op fouten
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Controleren of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ontvangen van de gegevens van het formulier
    $film_id = $_POST["film_id"];
    $rating = $_POST["rating"];

    // Invoegen van de beoordeling in de database
    $sql = "INSERT INTO film_beoordelingen (FilmID, GebruikerID, Beoordeling) VALUES ('$film_id', '1', '$rating')";
    if ($conn->query($sql) === TRUE) {
        echo "Beoordeling succesvol toegevoegd.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Sluit de databaseverbinding
$conn->close();
    ?>
    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto">
            <p class="text-center text-gray-600">&copy; 2024 Alle rechten voorbehouden <a href="#" class="text-blue-600">D&R Filmen</a></p>
            <img src="Images/footer-bottom-img.png" alt="Online banking Companies" class="mx-auto mt-4" style="width: 200px;">
        </div>
    </footer>
</body>
</html>
